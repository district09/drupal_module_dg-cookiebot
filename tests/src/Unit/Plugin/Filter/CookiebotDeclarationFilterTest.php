<?php

declare(strict_types=1);

namespace Drupal\dg_cookiebot\Tests\Unit\Plugin\Filter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\dg_cookiebot\Plugin\Filter\CookiebotDeclarationFilter;
use Drupal\Tests\UnitTestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers \Drupal\dg_cookiebot\Plugin\Filter\CookiebotDeclarationFilter
 *
 * @group dg_cookiebot
 */
class CookiebotDeclarationFilterTest extends UnitTestCase {

  use ProphecyTrait;

  /**
   * Proper dependencies are injected.
   *
   * @test
   */
  public function properDependenciesAreInjected(): void {
    $config = $this->prophesize(ImmutableConfig::class);
    $configFactory = $this->prophesize(ConfigFactoryInterface::class);
    $configFactory
      ->get('cookiebot.settings')
      ->willReturn($config->reveal())
      ->shouldBeCalled();

    $renderer = $this->prophesize(RendererInterface::class);
    $languageManager = $this->prophesize(LanguageManagerInterface::class);

    $container = $this->prophesize(ContainerInterface::class);
    $container
      ->get('config.factory')
      ->willReturn($configFactory->reveal())
      ->shouldBeCalled();
    $container
      ->get('renderer')
      ->willReturn($renderer->reveal())
      ->shouldBeCalled();
    $container
      ->get('language_manager')
      ->willReturn($languageManager->reveal())
      ->shouldBeCalled();

    CookiebotDeclarationFilter::create(
      $container->reveal(),
      [],
      'dg_cookiebot_declaration',
      ['provider' => 'foo']
    );
  }

  /**
   * Cookiebot javascript tag is injected.
   *
   * @param string|null $cookiebotClientId
   *   The cookiebot id.
   * @param string $currentLanguageId
   *   The current language ID.
   * @param string $text
   *   The text to manipulate.
   * @param string $expectedText
   *   The expected text.
   * @param bool $shouldHaveCacheTags
   *   The cache tags should been set.
   *
   * @dataProvider textProvider
   *
   * @test
   */
  public function tokenIsProcessedIntoJavascriptTag(
    ?string $cookiebotClientId,
    string $currentLanguageId,
    string $text,
    string $expectedText,
    bool $shouldHaveCacheTags
  ): void {
    $filter = new CookiebotDeclarationFilter(
      [],
      'dg_cookiebot_declaration',
      ['provider' => 'foobar'],
      $this->createConfigFactoryMock($cookiebotClientId),
      $this->createRendererMock($cookiebotClientId, $currentLanguageId, $shouldHaveCacheTags),
      $this->createLanguageManagerMock($currentLanguageId)
    );

    $result = $filter->process($text, $currentLanguageId);
    $this->assertEquals($expectedText, $result->getProcessedText());
    $this->assertEquals(
      $shouldHaveCacheTags ? ['cookiebot:cbid'] : [],
      $result->getCacheTags()
    );
  }

  /**
   * Data provider to test the input filter.
   *
   * @return array
   *   Rows containing:
   *   - string|null The cookiebot client id.
   *   - string Current language id.
   *   - string The text to process by the filter.
   *   - string The expected processed text.
   *   - bool Should the cache tags been set.
   */
  public function textProvider(): array {
    return [
      'Text not changed if there is no placeholder in it' => [
        '123',
        'nl',
        'Test text',
        'Test text',
        FALSE,
      ],
      'Placeholder replaced by empty string if there is no Cookiebot client id' => [
        '',
        'nl',
        'Test [COOKIEBOT_DECLARATION] text',
        'Test  text',
        TRUE,
      ],
      'Placeholder without surrounding <p> tags is replaced' => [
        '123',
        'nl',
        'Test [COOKIEBOT_DECLARATION] text',
        'Test <REPLACED> text',
        TRUE,
      ],
      'Placeholder with surrounding <p> tags is replaced' => [
        '123',
        'nl',
        'Test <p>[COOKIEBOT_DECLARATION]</p> text',
        'Test <REPLACED> text',
        TRUE,
      ],
    ];
  }

  /**
   * Create a config factory based on the given cookiebot client id.
   *
   * @param string|null $cookiebotClientId
   *   The cookiebot client ID.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   The mocked config factory.
   */
  private function createConfigFactoryMock(?string $cookiebotClientId): ConfigFactoryInterface {
    $config = $this->prophesize(ImmutableConfig::class);
    $config->get('cookiebot_cbid')->willReturn($cookiebotClientId);

    $configFactory = $this->prophesize(ConfigFactoryInterface::class);
    $configFactory
      ->get('cookiebot.settings')
      ->willReturn($config->reveal());

    return $configFactory->reveal();
  }

  /**
   * Create a language manager based on the given current language ID.
   *
   * @param string $currentLanguageId
   *   The current language id.
   *
   * @return \Drupal\Core\Language\LanguageManagerInterface
   *   The mocked language manager.
   */
  private function createLanguageManagerMock(string $currentLanguageId): LanguageManagerInterface {
    $currentLanguage = $this->prophesize(LanguageInterface::class);
    $currentLanguage->getId()->willReturn($currentLanguageId);

    $languageManager = $this->prophesize(LanguageManagerInterface::class);
    $languageManager->getCurrentLanguage()->willReturn($currentLanguage->reveal());

    return $languageManager->reveal();
  }

  /**
   * Create a renderer mock from cookiebot client id and current language.
   *
   * The renderer will always return "<REPLACED>" so we can test for that
   * partial string.
   *
   * @param string|null $cookiebotClientId
   *   The cookiebot client id.
   * @param string $currentLanguageId
   *   The current language id.
   * @param bool $shouldBeRendered
   *   The render method should be called.
   *
   * @return \Drupal\Core\Render\RendererInterface
   *   The mocked renderer.
   *
   * @throws \Exception
   */
  private function createRendererMock(
    ?string $cookiebotClientId,
    string $currentLanguageId,
    bool $shouldBeRendered
  ): RendererInterface {
    $renderer = $this->prophesize(RendererInterface::class);

    if ($cookiebotClientId && $shouldBeRendered) {
      $expectedToRender = [
        '#theme' => 'cookiebot_declaration',
        '#cookiebot_src' => 'https://consent.cookiebot.com/' . $cookiebotClientId . '/cd.js',
        '#cookiebot_culture' => $currentLanguageId,
      ];
      $renderer
        ->render($expectedToRender)
        ->willReturn('<REPLACED>')
        ->shouldBeCalled();
    }
    else {
      $renderer
        ->render(Argument::any())
        ->shouldNotBeCalled();
    }

    return $renderer->reveal();
  }

}
