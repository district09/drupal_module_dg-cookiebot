<?php

declare(strict_types=1);

namespace Drupal\Tests\dg_cookiebot\Unit;

use Drupal\Core\Config\Config;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\dg_cookiebot\CookieCategory;
use Drupal\dg_cookiebot\LibrariesCategoriesConfigManager;
use Drupal\Tests\UnitTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \Drupal\dg_cookiebot\LibrariesCategoriesConfigManager
 *
 * @group dg_cookiebot
 */
final class LibrariesCategoriesConfigManagerTest extends UnitTestCase {

  use ProphecyTrait;

  /**
   * Config factory to test with.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private ConfigFactoryInterface $configFactory;

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->configFactory = $this->createConfigFactoryStub([
      'necessary' => ['bar/*', 'foo/necessary'],
      'preferences' => ['bar/*', 'foo/preferences', 'bar/test'],
      'statistics' => ['bar/*', 'foo/statistics', 'bazz/*'],
      'marketing' => ['bar/*', 'foo/marketing'],
      'ignore' => ['bar/*', 'foo/ignore'],
    ]);
  }

  /**
   * No category when not in config.
   *
   * @test
   */
  public function itReturnsNullWhenExtensionCategoryNotInConfig(): void {
    $manager = new LibrariesCategoriesConfigManager($this->configFactory);

    self::assertNull($manager->getForExtensionLibrary('bizz', 'necessary'));
    self::assertNull($manager->getForExtensionLibrary('foo', 'bar'));
  }

  /**
   * Extension category when no specific library but global config exists.
   *
   * @test
   */
  public function itReturnsExtensionCategoryWhenNoLibraryConfig(): void {
    $manager = new LibrariesCategoriesConfigManager($this->configFactory);

    self::assertEquals(
      CookieCategory::STATISTICS,
      $manager->getForExtensionLibrary('bazz', 'whatever')
    );
  }

  /**
   * Global ignore has precedence on other global category settings.
   *
   * @test
   */
  public function itGivesPrecedenceToGlobalIgnoreCategory(): void {
    $manager = new LibrariesCategoriesConfigManager($this->configFactory);

    self::assertEquals(
      CookieCategory::IGNORE,
      $manager->getForExtensionLibrary('bar', 'whatever')
    );
  }

  /**
   * Specific category is loaded when set for extension library.
   *
   * @test
   */
  public function itReturnsSpecificExtensionLibrary(): void {
    $manager = new LibrariesCategoriesConfigManager($this->configFactory);

    self::assertEquals(
      CookieCategory::PREFERENCES,
      $manager->getForExtensionLibrary('bar', 'test')
    );
  }

  /**
   * Create config factory stub.
   *
   * @param array $configValues
   *   The config in the dg_cookiebot.libraries config file.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   The config factory stub
   */
  private function createConfigFactoryStub(array $configValues): ConfigFactoryInterface {
    $config = $this->prophesize(Config::class);

    foreach ($configValues as $category => $libraries) {
      $config->get($category)->willReturn($libraries);
    }
    $config->get(Argument::any())->willReturn(NULL);

    $factory = $this->prophesize(ConfigFactoryInterface::class);
    $factory->get('dg_cookiebot.libraries')->willReturn($config->reveal());

    return $factory->reveal();
  }

}
