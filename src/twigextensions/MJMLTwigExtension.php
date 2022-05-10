<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml\twigextensions;

use superbig\mjml\MJML;

use Craft;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLTwigExtension extends \Twig\Extension\AbstractExtension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'MJML';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('mjml', [$this, 'mjml']),
            new \Twig\TwigFilter('mjmlCli', [$this, 'mjmlCli']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
        ];
    }

    /**
     * @param null $html
     *
     * @return string
     */
    public function mjml($html = null)
    {
        $result = MJML::$plugin->mjmlService->parse($html);

        if (!$result) {
            return null;
        }

        return $result->output();
    }

    /**
     * @param null $html
     *
     * @return string
     * @throws \yii\base\ErrorException
     */
    public function mjmlCli($html = null)
    {
        $result = MJML::$plugin->mjmlService->parseCli($html);

        if (!$result) {
            return null;
        }

        return $result->output();
    }
}
