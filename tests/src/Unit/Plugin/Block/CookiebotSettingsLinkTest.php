<?php

declare(strict_types=1);

namespace Drupal\Tests\dg_cookiebot\Unit\Plugin\Block;

use Drupal\dg_cookiebot\Plugin\Block\CookiebotSettingsLink;
use Drupal\Tests\UnitTestCase;

/**
 * @covers \Drupal\dg_cookiebot\Plugin\Block\CookiebotSettingsLink
 *
 * @group dg_cookiebot
 */
class CookiebotSettingsLinkTest extends UnitTestCase {

  /**
   * Block contains simple render array.
   *
   * @test
   */
  public function buildContainsSimpleRenderArray(): void {
    $block = new CookiebotSettingsLink(
      [],
      'dg_cookiebot_settings_link',
      ['provider' => 'dg_cookiebot']
    );

    $expected = ['#theme' => 'dg_cookiebot_settings_link'];
    $this->assertEquals(
      $expected,
      $block->build()
    );
  }

}
