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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLTwigExtension extends AbstractExtension
{
    public function getName(): string
    {
        return 'MJML';
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('mjml', [$this, 'mjml']),
            new TwigFilter('mjmlCli', [$this, 'mjmlCli']),
        ];
    }

    public function getFunctions(): array
    {
        return [
        ];
    }

    public function mjml($html = null): ?string
    {
        $result = MJML::$plugin->mjmlService->parse($html);

        if (!$result) {
            return null;
        }

        return $result->output();
    }

    public function mjmlCli($html = null): ?string
    {
        $result = MJML::$plugin->mjmlService->parseCli($html);

        if (!$result) {
            return null;
        }

        return $result->output();
    }
}
