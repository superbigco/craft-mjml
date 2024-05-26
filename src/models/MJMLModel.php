<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml\models;

use craft\base\Model;
use craft\helpers\Template;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLModel extends Model
{
    public string $html;
    public string $mjml;

    public function output(): \Twig\Markup
    {
        return Template::raw($this->html);
    }

    /**
     * @param array{html: string, mjml: string} $results
     * @return MJMLModel
     */
    public static function create(array $results): MJMLModel
    {
        return new self($results);
    }

    /**
     * @inheritdoc
     */
    public function defineRules(): array
    {
        return [
            [['html', 'mjml'], 'string'],
        ];
    }
}
