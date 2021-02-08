# Digipolis Cookiebot

Adds extra functionality to the [Cookiebot module].

[![Github][github-badge]][github-link]

[![Build Status Master][travis-badge]][travis-link]
[![Maintainability][codeclimate-maint-badge]][codeclimate-maint-link]
[![Test Coverage][codeclimate-cover-badge]][codeclimate-cover-link]

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
    "url": "https://digipolis.repo.repman.io"
}
```

Add the following Cookiebot module patches to:

- Have Cookiebot respect the language of the current page.
- Fix Cookiebot auto blocking breaking Drupal core Domready functionality.

```json
"drupal/cookiebot": {
    "#3071334 Allow to set the language and multilingual support": "https://www.drupal.org/files/issues/2019-10-29/cookiebot-allow_to_set_the_language-3071334-8.patch",
    "#3091260 Blockmode `Auto` will not work with core's domready library": "https://www.drupal.org/files/issues/2020-01-29/cookiebot-attach_behaviors-3091260-30.patch"
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

## Link to edit the Cookie consent

Once the user has set his cookie consent he has by default no link to
review/edit his consent.

### Menu item

Add a menu item (e.g. to the footer menu) to update the cookie consent. The path
of the menu item should be `/cookiebot-renew`. It will be automatically
rewritten to trigger the cookie consent popup.

### Use block

There is a "Cookie settings link" block available to add the Cookie consent link
to a region on the website.

### Custom link

It's always possible to add a custom link to trigger opening the cookie consent
popup:

```html
<a href="javascript:Cookiebot.renew()">Update cookie consent</a>
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

This module will set the proper consent category to the video embed iframes so
they will no longer be blocked.

[Cookiebot]: https://www.cookiebot.com/
[Cookiebot module]: https://www.drupal.org/project/cookiebot
[video_embed_field module]: https://www.drupal.org/project/video_embed_field

[link-drupal]: https://www.drupal.org/project/drupal
[link-package]: https://github.com/digipolisgent/php_package_dg-flanders-basicregisters

[github-badge]: https://img.shields.io/badge/github-DigipolisGent_Cookiebot-blue.svg?logo=github
[github-link]: https://github.com/digipolisgent/drupal_module_dg-cookiebot

[travis-badge]: https://travis-ci.com/digipolisgent/drupal_module_dg-cookiebot.svg?token=anXPs46DEwgxP8RmJPAJ&branch=1.x "Travis build master"
[travis-link]: https://travis-ci.com/digipolisgent/drupal_module_dg-cookiebot/branches

[codeclimate-maint-badge]: https://api.codeclimate.com/v1/badges/5f2e8b272e71e2143b93/maintainability
[codeclimate-maint-link]: https://codeclimate.com/repos/5e6242ba0a957401b6012c05/maintainability
[codeclimate-cover-badge]: https://api.codeclimate.com/v1/badges/5f2e8b272e71e2143b93/test_coverage
[codeclimate-cover-link]: https://codeclimate.com/repos/5e6242ba0a957401b6012c05/test_coverage
