<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml;

use superbig\mjml\services\MJMLService;
use superbig\mjml\variables\MJMLVariable;
use superbig\mjml\twigextensions\MJMLTwigExtension;
use superbig\mjml\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class MJML
 *
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 *
 * @property  MJMLService $mjmlService
 * @method  Settings getSettings()
 */
class MJML extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var MJML
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new MJMLTwigExtension());

        $this->setComponents([
            'mjmlService' => MJMLService::class,
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['mjml/render'] = 'mjml/default';
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function(Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('mjml', MJMLVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'mjml',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'mjml/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}
