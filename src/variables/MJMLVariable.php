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

use superbig\mjml\MJML;

use Craft;
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
     * @param null $html
     *
     * @return MJMLModel|null
     */
    public function parse($html = null)
    {
        return MJML::$plugin->mjmlService->parse($html);
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
