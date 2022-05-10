# MJML plugin for Craft CMS 3.x

Render Twig emails with MJML, the only framework that makes responsive email easy.

![Screenshot](resources/icon.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require superbig/craft-mjml

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for MJML.

## MJML Overview

MJML is a markup language designed to reduce the pain of coding a responsive email. Its semantic syntax makes it easy and straightforward and its rich standard components library speeds up your development time and lightens your email codebase. MJML’s open-source engine generates high quality responsive HTML compliant with best practices.

## Configuring MJML

```php
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
    'nodePath'  => '',

    // The path to where the MJML cli installed with npm is located, i.e. `/usr/local/bin/mjml`
    'mjmlCliPath'   => '',

    // cli config args, e.g. `--config.minify true`',
    'mjmlCliConfigArgs'   => '',

    // The app id received by email
    'appId'     => '',

    // Enter the secret key received by email
    'secretKey' => '',
];

```

## Using MJML

You can either use the MJML cli locally, or the MJML API.

To use the cli, you need to install both Node and [the mjml executable](https://mjml.io/documentation/#installation):

To use the API, you need to [request a API key](https://mjml.io/api).  

Dynamic example with MJML CLI:

```twig
{% apply mjmlCli %}
    {%- apply spaceless %}
        <mjml>
            <mj-body>
                <mj-section>
                    <mj-column>
                        <mj-text font-size="20px" color="#F45E43" font-family="helvetica">{{ entry.title }}</mj-text>
                    </mj-column>
                </mj-section>
                {% for block in entry.matrixTestField.all() %}
                    {% if block.type == 'image' %}
                        {% set image = block.image.one() %}
                        {% if image %}
                            <mj-section>
                                <mj-column>
                                    <mj-image width="100" src="{{ image.url }}"></mj-image>
                                </mj-column>
                            </mj-section>
                        {% endif %}
                    {% endif %}
                    {% if block.type == 'text' %}
                        <mj-section><mj-column><mj-divider border-color="#F45E43"></mj-divider><mj-text font-size="20px" color="#F45E43" font-family="helvetica">{{ block.text }}</mj-text></mj-column></mj-section>
                    {% endif %}
                {% endfor %}
            </mj-body>
        </mjml>
    {% endapply %}
{% endapply %}
```

To use the API instead, swap `mjmlCli` with `mjml`.

### Caching

The above examples will be cached. If you are passing Twig variables, each output will however be unique, rendering the cache ineffective.

In this instance you probably would like to use the `include` method:

```twig
{{ craft.mjml.include('path/to/template.twig', { 
    subject: 'Static subject', 
    email: contact.email, 
}) }}
```

The `include` method uses the CLI option by default, but you can set it to use the MJML API by passing `api` as the third option, like so:

```twig
{{ craft.mjml.include('path/to/template.twig', { 
    subject: 'Static subject', 
    email: contact.email, 
}, 'api') }}
```

Here is an example passing a contact in a [newsletter template inside the Campaign plugin](https://putyourlightson.com/plugins/campaign#mjml). The template path here is relative to your site templates root.

This will first render the MJML template once, cache it, then it will render the dynamic parts with Twig for each instance.

### Includes

A caveat if you want to use includes:

Twig's built-in `include` method won't work in combination with MJML inside the template passed to the plugin `include` method.

This is because MJML is rendered first, before Twig, so if you include MJML in a partial that won't be rendered.

#### Workaround for MJML includes

_Note that this is only supported for the CLI option._

A workaround for partials is to use the `<mj-include />` tag to. Any partials referenced here will be relative to the Site templates root.

```html
<mj-include path="./mjml-partial.twig" />
```

Note that you have to append the file extension here. This will resolve to `/templates/mjml-partial.twig`.

Another caveat with `mj-include` is that the content of partials isn't currently included when checking the cache of a rendered MJML template.

This means that if you render a MJML template that in turns has a `<mj-include />` partial, then changes the content of the partial, the cache will be stale and your template won't reflect the changes.

A workaround for now is to clear the `storage/runtime/temp/mjml` folder in case this happens to you.

Brought to you by [Superbig](https://superbig.co)
