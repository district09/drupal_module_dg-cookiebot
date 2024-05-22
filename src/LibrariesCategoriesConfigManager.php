<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Libraries categories manager based on configuration.
 */
final class LibrariesCategoriesConfigManager implements LibrariesCategoriesManagerInterface {

  /**
   * Config key where the storage is located.
   *
   * @var string
   */
  public const CONFIG_NAME = 'dg_cookiebot.libraries';

  /**
   * Create new based on config.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
  ) {}

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
  ): ?CookieCategory {
    /** @var \Drupal\dg_cookiebot\CookieCategory[] $extensionConfig */
    $extensionConfig = $this->getExtension($extension);
    if (empty($extensionConfig)) {
      return NULL;
    }

    if (isset($extensionConfig[$library])) {
      return $extensionConfig[$library];
    }

    return $extensionConfig['*'] ?? NULL;
  }

  /**
   * {@inheritDoc}
   */
  private function getAll(): array {
    static $all = [];

    if (!$all) {
      $all = $this->normalizeConfig(
        $this->configFactory->get(self::CONFIG_NAME)
      );
    }

    return $all;
  }

  /**
   * {@inheritDoc}
   */
  private function getExtension(string $extension): array {
    $all = $this->getAll();

    return $all[$extension] ?? [];
  }

  /**
   * Normalize the config into a list of all extensions and their libraries.
   *
   * @param \Drupal\Core\Config\Config $config
   *   The config.
   *
   * @return array[]
   *   Array of extensions keyed by their name.
   */
  private function normalizeConfig(Config $config): array {
    $extensions = [];

    foreach (CookieCategory::cases() as $category) {
      $this->normalizeCategory(
        $extensions,
        $category,
        $config->get($category->value) ?? []
      );
    }

    return $extensions;
  }

  /**
   * Normalize the libraries of a category.
   *
   * @param array $extensions
   *   The list of extensions.
   * @param \Drupal\dg_cookiebot\CookieCategory $category
   *   The category the libraries are being normalized of.
   * @param array $libraries
   *   The libraries being normalized.
   */
  private function normalizeCategory(
    array &$extensions,
    CookieCategory $category,
    array $libraries,
  ): void {
    foreach ($libraries as $libraryName) {
      $parts = \explode('/', $libraryName);

      $extensionName = $parts[0];
      $library = $parts[1] ?? '*';

      $extensions[$extensionName][$library] = $category;
    }
  }

}
