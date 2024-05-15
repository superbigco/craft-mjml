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
    public string $mjmlVersion;

    public function output(): \Twig\Markup
    {
        return Template::raw($this->html);
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['html', 'mjml'], 'string'],
        ];
    }
}
