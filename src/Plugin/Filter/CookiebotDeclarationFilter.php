<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot\Plugin\Filter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Replaces a "[COOKIEBOT_DECLARATION]" placeholder by actual javascript tag.
 *
 * @Filter(
 *   id = "filter_cookiebot_declaration",
 *   title = @Translation("Replace Cookiebot declaration placeholder"),
 *   description = @Translation("Replaces the <code>[COOKIEBOT_DECLARATION]</code> placeholder by the actual list of cookies used within the website."),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE
 * )
 */
class CookiebotDeclarationFilter extends FilterBase implements ContainerFactoryPluginInterface {

  /**
   * The placeholder for the Cookiebot list.
   *
   * @var string
   */
  public const PLACEHOLDER = '[COOKIEBOT_DECLARATION]';

  /**
   * The cookiebot configuration.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private $cookiebotConfig;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  private $renderer;

  /**
   * Language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  private $languageManager;

  /**
   * CookiebotCookieList constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $configFactory,
    RendererInterface $renderer,
    LanguageManagerInterface $languageManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->cookiebotConfig = $configFactory->get('cookiebot.settings');
    $this->renderer = $renderer;
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('renderer'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode): FilterProcessResult {
    $result = new FilterProcessResult($text);
    if (!strpos($text, self::PLACEHOLDER)) {
      return $result;
    }

    $placeholder = preg_quote(self::PLACEHOLDER);
    $newText = preg_replace(
      '#<p>' . $placeholder . '</p>|' . $placeholder . '#',
      $this->createMarkup(),
      $text
    );
    $result->setProcessedText($newText);
    $result->setCacheTags(['cookiebot:cbid']);

    return $result;
  }

  /**
   * Helper to create the actual tag.
   *
   * @return string
   *   The markup to inject into the text.
   */
  private function createMarkup(): string {
    $cookiebotClientId = $this->cookiebotConfig->get('cookiebot_cbid');
    if (!$cookiebotClientId) {
      return '';
    }

    $element = [
      '#theme' => 'cookiebot_declaration',
      '#cookiebot_src' => 'https://consent.cookiebot.com/' . $cookiebotClientId . '/cd.js',
      '#cookiebot_culture' => $this->languageManager->getCurrentLanguage()->getId(),
    ];

    return (string) $this->renderer->render($element);
  }

}
