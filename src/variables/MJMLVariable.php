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

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLVariable
{
    // Public Methods
    // =========================================================================

    /**
     * @param null|string $html
     *
     * @return MJMLModel|null
     */
    public function parse($html = null)
    {
        return MJML::$plugin->mjmlService->parse($html);
    }

    /**
     * @param string $template
     *
     * @return MJMLModel|null
     */
    public function include(string $template = '', $variables = null, $renderMethod = 'cli')
    {
        return Template::raw(MJML::$plugin->mjmlService->include($template, $variables, $renderMethod));
    }

    /**
     * @param null $html
     *
     * @return MJMLModel|null
     * @throws \yii\base\ErrorException
     */
    public function parseCli($html = null)
    {
        return MJML::$plugin->mjmlService->parseCli($html);
    }
}
