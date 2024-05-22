<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot;

/**
 * Service to alter the library info of core and modules.
 *
 * This will set the cookie category to all libraries as configured in
 * dg_cookiebot.libraries.
 *
 * @see \Drupal\dg_cookiebot\Form\LibrariesSettingsForm
 */
interface LibrariesInfoAlterInterface {

  /**
   * Alter a single extension.
   *
   * @param array $libraries
   *   All libraries of given extension.
   * @param string $extension
   *   The extension name.
   */
  public function alter(array &$libraries, string $extension): void;

}
