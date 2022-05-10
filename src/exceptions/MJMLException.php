<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

namespace superbig\mjml\exceptions;

use yii\base\Exception;

/**
 * @author    Superbig
 * @package   MJML
 * @since     1.0.0
 */
class MJMLException extends Exception
{
    public $cliCommand = '';

    public function setCliCommand(string $cmd)
    {
        $this->cliCommand = $cmd;
    }

    public function getCliCommand()
    {
        return $this->cliCommand;
    }
}
