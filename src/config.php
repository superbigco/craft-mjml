<?php
/**
 * MJML plugin for Craft CMS 3.x
 *
 * Render Twig emails with MJML, the only framework that makes responsive email easy.
 *
 * @link      https://superbig.co
 * @copyright Copyright (c) 2018 Superbig
 */

/**
 * MJML config.php
 *
 * This file exists only as a template for the MJML settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'mjml.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // The path to where the your version of Node is located, i.e. `/usr/local/bin/node`
    'nodePath' => '',

    // The path to where the MJML cli installed with npm is located, i.e. `/usr/local/bin/mjml`
    'mjmlCliPath' => '',

    // cli config args, e.g. `--config.minify true`',
    'mjmlCliConfigArgs' => '',

    // The app id received by email
    'appId' => '',

    // Enter the secret key received by email
    'secretKey' => '',
];
