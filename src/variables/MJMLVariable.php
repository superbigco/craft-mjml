<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml\variables;

use craft\helpers\Template;
use superbig\mjml\MJML;

use superbig\mjml\models\MJMLModel;
use yii\base\Exception;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLVariable
{
    public function parse(string $html = null): ?MJMLModel
    {
        return MJML::$plugin->mjmlService->parse($html);
    }

    public function include(string $template = '', $variables = null, $renderMethod = 'cli'): \Twig\Markup
    {
        return Template::raw(MJML::$plugin->mjmlService->include($template, $variables, $renderMethod));
    }

    /**
     * @param string|null $html
     *
     * @return MJMLModel|null
     * @throws \yii\base\ErrorException
     * @throws Exception
     */
    public function parseCli($html = null): ?MJMLModel
    {
        return MJML::$plugin->mjmlService->parseCli($html);
    }
}
