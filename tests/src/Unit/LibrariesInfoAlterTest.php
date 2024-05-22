<?php

declare(strict_types=1);

namespace Drupal\Tests\dg_cookiebot\Unit;

use Drupal\dg_cookiebot\CookieCategory;
use Drupal\dg_cookiebot\LibrariesCategoriesManagerInterface;
use Drupal\dg_cookiebot\LibrariesInfoAlter;
use Drupal\Tests\UnitTestCase;
use Prophecy\Argument;

/**
 * @covers \Drupal\dg_cookiebot\LibrariesInfoAlter
 *
 * @group dg_cookiebot
 */
final class LibrariesInfoAlterTest extends UnitTestCase {

  /**
   * Libraries categories manager.
   *
   * @var \Drupal\dg_cookiebot\LibrariesCategoriesManagerInterface
   */
  private LibrariesCategoriesManagerInterface $librariesCategoriesManager;

  /**
   * {@inheritDoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $manager = $this->prophesize(LibrariesCategoriesManagerInterface::class);
    $manager
      ->getForExtensionLibrary('core', 'once')
      ->willReturn(CookieCategory::NECESSARY);
    $manager
      ->getForExtensionLibrary(Argument::any(), Argument::any())
      ->willReturn(NULL);
    $this->librariesCategoriesManager = $manager->reveal();
  }

  /**
   * Nothing is changed when library info does not contain javascript.
   *
   * @test
   */
  public function itDoesNotChangeInfoWhenLibraryHasNoJavascript(): void {
    $libraries = [
      'once' => [
        'css' => ['test/me/now.css' => []],
      ],
    ];

    $alterer = new LibrariesInfoAlter($this->librariesCategoriesManager);
    $alterer->alter($libraries, 'core');

    self::assertEquals(
      [
        'once' => [
          'css' => ['test/me/now.css' => []],
        ],
      ],
      $libraries
    );
  }

  /**
   * Nothing is changed when there is no cookie category for the library.
   *
   * @test
   */
  public function itDoesNotChangeInfoWhenNoCookieCategoryForLibrary(): void {
    $libraries = [
      'twice' => [
        'js' => ['test/me/now.js' => []],
      ],
    ];

    $alterer = new LibrariesInfoAlter($this->librariesCategoriesManager);
    $alterer->alter($libraries, 'core');

    self::assertEquals(
      [
        'twice' => [
          'js' => ['test/me/now.js' => []],
        ],
      ],
      $libraries
    );
  }

  /**
   * Cookie category is added to each javascript file.
   *
   * @test
   */
  public function itAddsCategoryToEachJavascriptFileInfo(): void {
    $libraries = [
      'once' => [
        'js' => [
          'test/one.js' => [],
          'test/two.js' => [],
        ],
      ],
    ];

    $alterer = new LibrariesInfoAlter($this->librariesCategoriesManager);
    $alterer->alter($libraries, 'core');

    self::assertEquals(
      [
        'once' => [
          'js' => [
            'test/one.js' => [
              'attributes' => [
                'data-cookieconsent' => 'necessary',
              ],
            ],
            'test/two.js' => [
              'attributes' => [
                'data-cookieconsent' => 'necessary',
              ],
            ],
          ],
        ],
      ],
      $libraries
    );
  }

}
