<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * The different cookie categories.
 */
enum CookieCategory: string {

  case NECESSARY = 'necessary';
  case PREFERENCES = 'preferences';
  case STATISTICS = 'statistics';
  case MARKETING = 'marketing';
  case IGNORE = 'ignore';

  /**
   * Get the translated label of the category.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The label.
   */
  public function label(): TranslatableMarkup {
    return match($this) {
      self::NECESSARY => new TranslatableMarkup('Necessary'),
      self::PREFERENCES => new TranslatableMarkup('Preferences'),
      self::STATISTICS => new TranslatableMarkup('Statistics'),
      self::MARKETING => new TranslatableMarkup('Marketing'),
      self::IGNORE => new TranslatableMarkup('Ignore'),
    };
  }

  /**
   * Get the translated description of the category.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   *   The description.
   */
  public function description(): TranslatableMarkup {
    return match($this) {
      self::NECESSARY => new TranslatableMarkup('Libraries that are needed to guarantee website functionality.'),
      self::PREFERENCES => new TranslatableMarkup('Libraries about setting user choices to navigate the website.'),
      self::STATISTICS => new TranslatableMarkup('Libraries used for analytical purposes.'),
      self::MARKETING => new TranslatableMarkup('Libraries used for targeting the user with (personalized) adds.'),
      self::IGNORE => new TranslatableMarkup('Libraries that are ignored by Cookiebot.'),
    };
  }

}
