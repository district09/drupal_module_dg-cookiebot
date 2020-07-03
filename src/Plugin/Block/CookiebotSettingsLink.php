<?php

namespace Drupal\dg_cookiebot\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Block with link to update cookie settings.
 *
 * @Block(
 *   id = "dg_cookiebot_settings_link",
 *   admin_label = @Translation("Cookie settings link")
 * )
 */
class CookiebotSettingsLink extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'dg_cookiebot_settings_link',
    ];
  }

}
