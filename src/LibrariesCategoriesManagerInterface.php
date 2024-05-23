<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot;

/**
 * Get the proper libraries categories.
 */
interface LibrariesCategoriesManagerInterface {

  /**
   * Get the proper category for given extension library.
   *
   * @param string $extension
   *   The extension name.
   * @param string $library
   *   The library name.
   *
   * @return \Drupal\dg_cookiebot\CookieCategory|null
   *   The category, if any.
   */
  public function getForExtensionLibrary(
    string $extension,
    string $library,
  ): ?CookieCategory;

}
