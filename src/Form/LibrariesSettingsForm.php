<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot\Form;

use Drupal\Core\Asset\AssetCollectionGroupOptimizerInterface;
use Drupal\Core\Asset\AssetQueryStringInterface;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\dg_cookiebot\CookieCategory;
use Drupal\dg_cookiebot\LibrariesCategoriesConfigManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Set how the Drupal libraries should be seen by Cookiebot.
 */
final class LibrariesSettingsForm extends ConfigFormBase {

  /**
   * Create new form.
   *
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cacheTagsInvalidator
   *   Cache tags invalidator.
   * @param \Drupal\Core\Asset\AssetCollectionGroupOptimizerInterface $jsOptimizer
   *   JS Optimizer.
   * @param \Drupal\Core\Asset\AssetQueryStringInterface $queryString
   *   Query String processor.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface|null $typedConfigManager
   *   Typed config manager.
   */
  public function __construct(
    private readonly CacheTagsInvalidatorInterface $cacheTagsInvalidator,
    private readonly AssetCollectionGroupOptimizerInterface $jsOptimizer,
    private readonly AssetQueryStringInterface $queryString,
    ConfigFactoryInterface $config_factory,
    ?TypedConfigManagerInterface $typedConfigManager = NULL,
  ) {
    parent::__construct($config_factory, $typedConfigManager);
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache_tags.invalidator'),
      $container->get('asset.js.collection_optimizer'),
      $container->get('asset.query_string'),
      $container->get('config.factory'),
      $container->get('config.typed'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId(): string {
    return 'dg_cookiebot_libraries_settings';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(LibrariesCategoriesConfigManager::CONFIG_NAME);

    $form['help'] = $this->buildFormHelp();
    $categories = CookieCategory::cases();
    foreach ($categories as $category) {
      $form[$category->value] = $this->buildFormCategoryType(
        $category,
        $config->get($category->value) ?? []
      );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config(LibrariesCategoriesConfigManager::CONFIG_NAME);
    $categories = CookieCategory::cases();

    $libraries = [];
    foreach ($categories as $category) {
      $this->normalizeValues(
        $libraries,
        $category,
        $form_state->getValue($category->value)
      );
    }

    $config->setData($libraries);
    $config->save();

    $this->clearCaches();

    parent::submitForm(
      $form,
      $form_state
    );
  }

  /**
   * Normalize the submitted data of a specific type.
   *
   * @param array $libraries
   *   The existing list of libraries.
   * @param \Drupal\dg_cookiebot\CookieCategory $category
   *   The cookie category.
   * @param string $value
   *   The submitted value of a single category.
   */
  private function normalizeValues(array &$libraries, CookieCategory $category, string $value): void {
    $rawValues = \explode(PHP_EOL, \trim($value));
    $rawLibraries = array_filter(\array_map('trim', $rawValues));

    $libraries[$category->value] = $rawLibraries;
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames(): array {
    return [LibrariesCategoriesConfigManager::CONFIG_NAME];
  }

  /**
   * Create the help text for users.
   *
   * @return array
   *   The help text structure.
   */
  private function buildFormHelp(): array {
    $help = [
      '#type' => 'container',
      '#value' => [],
      '#tree' => TRUE,
    ];
    $help['cookiebot'] = $this->buildFormHelpLine(new TranslatableMarkup(
      'Cookies can be classified as different categories. Cookiebot will try to classify the scripts automatically. These settings allow overriding the auto classification for Drupal (javascript) libraies.'
    ));

    $help['usage'] = $this->buildFormHelpLine(new TranslatableMarkup(
      'Add the module name and specific library (e.g. "core/once") to add set for a specific library or add only the module name to set for all libraries of that module (e.g. "core/*").'
    ));
    $help['lines'] = $this->buildFormHelpLine(new TranslatableMarkup(
      'Add one item per line.'
    ));

    return $help;
  }

  /**
   * Build a single help line.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup $helpText
   *   The help text.
   *
   * @return array
   *   The help text line.
   */
  private function buildFormHelpLine(TranslatableMarkup $helpText): array {
    return [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $helpText,
    ];
  }

  /**
   * Create the settings form for a single cookie classification.
   *
   * @param \Drupal\dg_cookiebot\CookieCategory $category
   *   The cookie category.
   * @param string[] $defaultValues
   *   The default values.
   *
   * @return array
   *   The form element.
   */
  private function buildFormCategoryType(
    CookieCategory $category,
    array $defaultValues,
  ): array {
    return [
      '#type' => 'textarea',
      '#title' => $category->label(),
      '#description' => $category->description(),
      '#default_value' => implode(PHP_EOL, $defaultValues),
    ];
  }

  /**
   * Clear caches when config changes.
   */
  private function clearCaches(): void {
    $this->cacheTagsInvalidator->invalidateTags(['library_info']);
    $this->jsOptimizer->deleteAll();
    $this->queryString->reset();
  }

}
