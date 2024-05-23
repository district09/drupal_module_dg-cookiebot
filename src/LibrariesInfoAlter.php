<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot;

/**
 * Alters the libraries based on the cookiebot libraries configuration.
 */
final class LibrariesInfoAlter implements LibrariesInfoAlterInterface {

  /**
   * Create new service.
   *
   * @param \Drupal\dg_cookiebot\LibrariesCategoriesManagerInterface $manager
   *   The libraries config manager.
   */
  public function __construct(
    private readonly LibrariesCategoriesManagerInterface $manager,
  ) {}

  /**
   * {@inheritDoc}
   */
  public function alter(array &$libraries, string $extension): void {
    foreach ($libraries as $libraryName => &$libraryInfo) {
      if (!isset($libraryInfo['js'])) {
        continue;
      }

      $category = $this->manager->getForExtensionLibrary($extension, $libraryName);
      if (!$category) {
        return;
      }

      $this->alterLibrary($libraryInfo, $category->value);
    }
  }

  /**
   * Alter a single library.
   *
   * @param array $libraryInfo
   *   The library info to alter.
   * @param string $cookieCategory
   *   The cookie category.
   */
  private function alterLibrary(array &$libraryInfo, string $cookieCategory): void {
    foreach ($libraryInfo['js'] as &$fileInfo) {
      $fileInfo['attributes']['data-cookieconsent'] = $cookieCategory;
    }
  }

}
