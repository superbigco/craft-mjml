<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\web\View;
use GuzzleHttp\Client;

use mikehaertl\shellcommand\Command;
use superbig\mjml\exceptions\MJMLException;
use superbig\mjml\MJML;
use superbig\mjml\models\MJMLModel;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLService extends Component
{
    public function parse(string $html): ?MJMLModel
    {
        $settings = MJML::$plugin->getSettings();
        $hash = md5($html);
        $client = new Client([
            'base_uri' => 'https://api.mjml.io/v1/',
            'auth' => [App::parseEnv($settings->appId), App::parseEnv($settings->secretKey)],
        ]);

        try {
            $response = Craft::$app->getCache()->getOrSet("mjml-{$hash}", function() use ($html, $client) {
                $request = $client->post('render', [
                    'json' => [
                        'mjml' => $html,
                    ],
                ]);

                return Json::decodeIfJson((string)$request->getBody());
            });

            return MJMLModel::create([
                'html' => $response['html'],
                'mjml' => $response['mjml'],
            ]);
        } catch (\Exception $e) {
            Craft::error(
                Craft::t('mjml',
                    'Error rendering MJML: {error}',
                    ['error' => $e->getMessage()]
                ),
                'mjml'
            );

            return null;
        }
    }

    public function include(string $template = '', $variables = [], $renderMethod = 'cli')
    {
        try {
            $templatePath = Craft::$app->getView()->resolveTemplate($template, View::TEMPLATE_MODE_SITE);

            if (!$templatePath) {
                throw new MJMLException('Could not find template: ' . $template);
            }

            $html = file_get_contents($templatePath);

            if (empty($html)) {
                throw new MJMLException('Could not render template ' . $template . ' : The template was empty');
            }

            $hash = md5($html);

            /** @var MJMLModel|null $output */
            $output = Craft::$app->getCache()->getOrSet("mjml-{$hash}-{$renderMethod}", function() use ($html, $renderMethod) {
                return $renderMethod === 'cli' ? $this->parseCli($html) : $this->parse($html);
            });

            if (!$output) {
                throw new MJMLException('Could not render template: ' . $template);
            }

            return Craft::$app->getView()->renderString($output->output(), $variables);
        } catch (MJMLException $e) {
            Craft::error('Could not generate output: ' . $e->getMessage(), 'mjml');
        }
    }

    /**
     * @param string|null $html
     *
     * @return MJMLModel|null
     * @throws ErrorException
     * @throws Exception
     */
    public function parseCli(?string $html = null): ?MJMLModel
    {
        $settings = MJML::$plugin->getSettings();
        $configArgs = App::parseEnv($settings->mjmlCliConfigArgs);

        $mjmlCliIncludesPath = App::parseEnv($settings->mjmlCliIncludesPath);
        if (!empty($mjmlCliIncludesPath)) {
            $configArgs = "{$configArgs} --config.filePath {$mjmlCliIncludesPath}";
        }

        $nodePath = App::parseEnv($settings->nodePath);
        $mjmlCliPath = App::parseEnv($settings->mjmlCliPath);
        $mjmlPath = "{$nodePath} {$mjmlCliPath}";
        $hash = md5($html);
        $tempPath = Craft::$app->getPath()->getTempPath() . "/mjml/mjml-{$hash}.html";
        $tempOutputPath = Craft::$app->getPath()->getTempPath() . "/mjml/mjml-output-{$hash}.html";

        // Check if Node.js exists
        if (!file_exists($nodePath)) {
            throw new InvalidConfigException("Node.js executable not found at path: {$nodePath}");
        }

        // Check if MJML CLI exists
        if (!file_exists($mjmlCliPath)) {
            throw new InvalidConfigException("MJML CLI executable not found at path: {$mjmlCliPath}");
        }

        try {
            if (!file_exists($tempOutputPath)) {
                FileHelper::writeToFile($tempPath, $html);

                $cmd = "$mjmlPath $tempPath $configArgs -o $tempOutputPath";

                $this->executeShellCommand($cmd);
            }
        } catch (MJMLException $e) {
            Craft::error('Could not generate output: ' . $e->getMessage(), 'mjml');

            return null;
        }

        if (!file_exists($tempOutputPath)) {
            Craft::error('Could not find generated output: ' . $tempOutputPath, 'mjml');

            return null;
        }

        $output = file_get_contents($tempOutputPath);

        if (empty($output)) {
            return null;
        }

        return MJMLModel::create([
            'html' => $output,
            'mjml' => $html,
        ]);
    }

    /**
     * Execute a shell command
     *
     * @param string $command
     *
     * @return string
     */
    protected function executeShellCommand(string $command): string
    {
        // Create the shell command
        $shellCommand = new Command();
        $shellCommand->setCommand($command);

        // If we don't have proc_open, maybe we've got exec
        if (!\function_exists('proc_open') && \function_exists('exec')) {
            $shellCommand->useExec = true;
        }

        // Return the result of the command's output or error
        if (!$shellCommand->execute()) {
            throw new MJMLException("Failed to run {$command}: " . $shellCommand->getError());
        }

        return $shellCommand->getOutput();
    }
}
