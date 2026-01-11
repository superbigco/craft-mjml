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
use craft\behaviors\EnvAttributeParserBehavior;

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
    public string $appId = '';
    public string $secretKey = '';
    public string $apiUrl = '';

    public function defineBehaviors(): array
    {
        return [
            'parser' => [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => [
                    'nodePath',
                    'mjmlCliPath',
                    'mjmlCliConfigArgs',
                    'appId',
                    'secretKey',
                    'apiUrl',
                ],
            ],
        ];
    }

    public function defineRules(): array
    {
        return [
            [['appId', 'secretKey', 'mjmlCliPath', 'nodePath', 'mjmlCliConfigArgs', 'apiUrl'], 'string'],
            [['appId', 'secretKey'], 'required', 'when' => fn(Settings $model) => !empty($model->appId) || !empty($model->secretKey)],
            [['mjmlCliPath', 'nodePath'], 'required', 'when' => fn(Settings $model) => !empty($model->mjmlCliPath) || !empty($model->nodePath)],
        ];
    }

    public function attributeLabels()
    {
        return [
            'appId' => Craft::t('mjml', 'API App ID'),
            'secretKey' => Craft::t('mjml', 'API Secret Key'),
            'apiUrl' => Craft::t('mjml', 'API URL'),
            'mjmlCliPath' => Craft::t('mjml', 'MJML Cli Path'),
            'nodePath' => Craft::t('mjml', 'Node.js Path'),
            'mjmlCliConfigArgs' => Craft::t('mjml', 'MJML Cli Config Arguments'),
        ];
    }
}
