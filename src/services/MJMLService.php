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

use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\Template;
use craft\web\View;
use GuzzleHttp\Client;
use mikehaertl\shellcommand\Command;
use superbig\mjml\MJML;

use Craft;
use craft\base\Component;
use superbig\mjml\exceptions\MJMLException;
use superbig\mjml\models\MJMLModel;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @param $html
     *
     * @return null|MJMLModel
     */
    public function parse($html)
    {
        $settings = MJML::$plugin->getSettings();
        $hash = md5($html);
        $client = new Client([
            'base_uri' => 'https://api.mjml.io/v1/',
            'auth' => [$settings->appId, $settings->secretKey],
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

            return new MJMLModel([
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
     * @param null $html
     *
     * @return MJMLModel|null
     * @throws \yii\base\ErrorException
     */
    public function parseCli($html = null)
    {
        $settings = MJML::$plugin->getSettings();
        $configArgs = "{$settings->mjmlCliConfigArgs}";
        $mjmlPath = "{$settings->nodePath} {$settings->mjmlCliPath}";
        $hash = md5($html);
        $tempPath = Craft::$app->getPath()->getTempPath() . "/mjml/mjml-{$hash}.html";
        $tempOutputPath = Craft::$app->getPath()->getTempPath() . "/mjml/mjml-output-{$hash}.html";

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

        return new MJMLModel([
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
