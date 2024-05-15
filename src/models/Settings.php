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

use Craft;

use craft\base\Model;
use superbig\mjml\MJML;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class Settings extends Model
{
    public string $nodePath = '';
    public string $mjmlCliPath = '';
    public string $mjmlCliConfigArgs = '';
    public string $mjmlCliIncludesPath = '';
    public string $appId = '';
    public string $secretKey = '';

    public function init(): void
    {
        $this->mjmlCliIncludesPath = Craft::$app->getView()->getTemplatesPath();

        parent::init();
    }

    public function behaviors()
    {
        
    }

    public function rules(): array
    {
        return [
            [['appId', 'secretKey', 'mjmlCliPath', 'nodePath', 'mjmlCliConfigArgs'], 'string'],
            [['appId', 'secretKey'], 'required'],
        ];
    }
}
