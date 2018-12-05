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

use superbig\mjml\MJML;

use Craft;
use craft\base\Model;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $nodePath = '';

    /**
     * @var string
     */
    public $mjmlCliPath = '';

    /**
     * @var string
     */
    public $appId = '';

    /**
     * @var string
     */
    public $secretKey = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['appId', 'secretKey', 'mjmlCliPath', 'nodePath'], 'string'],
            [['appId', 'secretKey'], 'required'],
        ];
    }
}
