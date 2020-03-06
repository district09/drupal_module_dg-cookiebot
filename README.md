# Digipolis Cookiebot

The City of Ghent has opted for [Cookiebot] to allow visitors to approve loading
of (third party) party cookies.

This module extends the [Cookiebot module] with extra functionality:

* A placeholder filter to add the Cookiebot declaration into formatted text.

## Installation

Add the git source to the composer repositories: edit `composer.json` in the
project root and add following lines in the `repositories` section:

```json
        {
            "type": "composer",
            "url": "https://packagist.gentgrp.gent.be"
        }
```

Add the following Cookiebot module patch to have Cookiebot respect the language
of the current page:

```json
    "drupal/cookiebot": {
      "#3071334 Allow to set the language and multilingual support": "https://www.drupal.org/files/issues/2019-10-29/cookiebot-allow_to_set_the_language-3071334-8.patch"
    },
```

Install the module using composer:

```bash
composer require gent-drupal/dg_cookiebot
```

Enable the module:

```bash
drush -y en dg_cookiebot
```

Update the Cookiebot configuration at `admin/config/cookiebot`:

* Enter the **Cookiebot Domain Group ID** (CBID).
* Enable the **Automatically block all cookies** checkbox.
* Enable the **Use the current Drupal language** checkbox.
* Enable the **Exclude admin pages** checkbox.

It's possible to overwite the Cookiebot Domain Group ID (CBID) in the
`settings.php` file:

```php
/**
 * Cookiebot.
 */
$config['cookiebot.settings']['cookiebot_cbid'] = 'COOKIEBOT DOMAIN GROUP ID';
```

## Create cookie declaration page

This module adds a filter to replace a token within formatted text content by
the proper javascript tag to load the Cookie Declaration (list of cookies used
within the website).

### Add the text filter

* Open `/admin/config/content/formats` and configure the text format where the
  token should be used.
* Enable the "Replace Cookiebot declaration placeholder" filter.
* Make sure that the filter is the last one in the list.

### Create the Cookie declaration page

* Create a new page on the path `/cookie-declaration`.
* Add to the content (formatted text) the `[COOKIEBOT_DECLARATION]` placeholder
  where the list of cookies in use should be displayed.

### Update Cookiebot configuration

The cookie consent popup is by default loaded on all page, also on the page
containing the cookie declaration. Add this page to the excluded paths.

* Open the Cookiebot configuration on `admin/config/cookiebot`.
* Add `/cookie-declaration` to the "Exclude paths".

The Cookies declartion will now be loaded on the `/cookie-declaration` page.

## Video without cookies

Services like Youtube and Vimeo provide by default iframes that track the users
with cookies. They provide a no-cookie domain but that is not wat is used by
default.

To avoid loading this iframes and the cookies that come with them, Cookiebot
removes the iframe content until the visitor has allowed marketing cookies.

This is how video's can be embeded without cookies:

### Patch the video_embed_field module

Add patches to the [video_embed_field module] so is has an option to embed video
without cookies:

```json
    "drupal/video_embed_field": {
      "#2998257 Provide 'do not track' option for vimeo": "https://www.drupal.org/files/issues/2019-02-18/video_embed_field_2998257_5.patch",
      "#2973246 Youtube Privacy Enhanced Mode": "https://www.drupal.org/files/issues/2019-06-08/video_embed_field_youtube_nocookie_2973246_41.patch"
    },
```

Open the `admin/config/media/video-embed-field` page and change the "Privacy
mode" to "Enabled".

### Overwite the video-embed-iframe template

The `video-embed-iframe.html.twig` template needs to be overwritten to let
Cookiebot know, by the `data-cookieconsent="necessary"` property, that these can
be safely loaded.

```html
{#
/**
 * @file
 * Display an iframe with alterable components.
 */
#}
<iframe{{ attributes }}{% if url is not empty %} src="{{ url }}{% if query is not empty %}?{{ query | url_encode }}{% endif %}{% if fragment is not empty %}#{{ fragment }}{% endif %}"{% endif %} data-cookieconsent="necessary"></iframe>
```

## Link to edit the Cookie consent

Once the user has set his cookie consent he has by default no link to
review/edit his consent.

Add a menu item (e.g. to the footer menu) to update the cookie consent. The path
of the menu item should be `/cookiebot-renew`. It will be automatically
rewritten to trigger the cookie consent popup.

It's always possible to add a custom link to trigger opening the cookie consent
popup:

```html
<a href="javascript:Cookiebot.renew()">Update cookie consent</a>
```

[Cookiebot]: https://www.cookiebot.com/
[Cookiebot module]: https://www.drupal.org/project/cookiebot
[video_embed_field module]: https://www.drupal.org/project/video_embed_field
