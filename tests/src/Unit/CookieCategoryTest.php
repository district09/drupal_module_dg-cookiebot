<?php

declare(strict_types=1);

namespace Drupal\Tests\dg_cookiebot\Unit;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\dg_cookiebot\CookieCategory;
use Drupal\Tests\UnitTestCase;

/**
 * @covers \Drupal\dg_cookiebot\CookieCategory
 *
 * @group dg_cookiebot
 */
final class CookieCategoryTest extends UnitTestCase {

  /**
   * Categories have proper label.
   *
   * @test
   */
  public function itHasProperLabel(): void {
    self::assertEquals(
      new TranslatableMarkup('Necessary'),
      CookieCategory::NECESSARY->label()
    );
    self::assertEquals(
      new TranslatableMarkup('Preferences'),
      CookieCategory::PREFERENCES->label()
    );
    self::assertEquals(
      new TranslatableMarkup('Statistics'),
      CookieCategory::STATISTICS->label()
    );
    self::assertEquals(
      new TranslatableMarkup('Marketing'),
      CookieCategory::MARKETING->label()
    );
    self::assertEquals(
      new TranslatableMarkup('Ignore'),
      CookieCategory::IGNORE->label()
    );
  }

  /**
   * Categories have proper description.
   *
   * @test
   */
  public function itHasProperDescription(): void {
    self::assertEquals(
      new TranslatableMarkup('Libraries that are needed to guarantee website functionality.'),
      CookieCategory::NECESSARY->description()
    );
    self::assertEquals(
      new TranslatableMarkup('Libraries about setting user choices to navigate the website.'),
      CookieCategory::PREFERENCES->description()
    );
    self::assertEquals(
      new TranslatableMarkup('Libraries used for analytical purposes.'),
      CookieCategory::STATISTICS->description()
    );
    self::assertEquals(
      new TranslatableMarkup('Libraries used for targeting the user with (personalized) adds.'),
      CookieCategory::MARKETING->description()
    );
    self::assertEquals(
      new TranslatableMarkup('Libraries that are ignored by Cookiebot.'),
      CookieCategory::IGNORE->description()
    );
  }

}
