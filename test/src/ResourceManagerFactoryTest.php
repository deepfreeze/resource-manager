<?php

namespace DeepFreezeTest\Intl\Resource;

use DeepFreeze\Intl\Resource\ResourceManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceManagerFactoryTest extends TestCase
{
  public function testFactoryConfiguration() {
    $config = array(
      'fallback_language' => 'qps',
      'available_languages' => array(
        'en-CA',
        'qps',
      ),
      'translation_sources' => array(
        array(
          'type' => 'php-array',
          'options' => array(
            'base_path' => __DIR__ . '/../files/',
            'file_templates' => array(
              'simple.en-UK.res.php',
            ),
          ),
          'text_domains' => array(
            'domain-a',
            'domain-b',
          ),
        ),
      ),
    );
    $manager = ResourceManagerFactory::factory($config);
    $this->assertSame('qps', $manager->getOptions()->getFallbackLanguage());
    $this->assertSame(array('en-CA', 'qps'), $manager->getOptions()->getAvailableLanguages());
    $this->assertSame('Message 5 UK', $manager->getMessage('domain-a', 'key5'));
  }

  public function testFactoryWithMultipleTranslationSources() {
    $config = array(
      'fallback_language' => 'fr',
      'available_languages' => array(
        'en-UK',
        'fr-FR',
      ),
      'translation_sources' => array(
        array(
          'type' => 'php-array',
          'options' => array(
            'base_path' => __DIR__ . '/../files/',
            'file_templates' => array(
              'simple.{language}.res.php',
              'simple.{language}-{region}.res.php',
            ),
          ),
          'text_domains' => array(
            'domain-a',
            'domain-b',
          ),
        ),
        array(
          'type' => 'php-array',
          'options' => array(
            'base_path' => __DIR__ . '/../files/',
            'file_templates' => array(
              'simple.{language}.override.res.php',
              'simple.{language}-{region}.override.res.php',
            ),
          ),
          'text_domains' => array(
            'domain-a',
          ),
        )
      ),
    );
    $manager = ResourceManagerFactory::factory($config);
    $manager->setRequestedLanguage('en-UK');
    $this->assertSame('fr', $manager->getOptions()->getFallbackLanguage());
    $this->assertSame(array('en-UK', 'fr-FR'), $manager->getOptions()->getAvailableLanguages());
    $this->assertSame('Message 1 UK Override', $manager->getMessage('domain-a', 'key1'));
    $this->assertSame('Message 2 UK', $manager->getMessage('domain-a', 'key2'));
    $this->assertSame('Message 3', $manager->getMessage('domain-a', 'key3'));
    $this->assertSame('French 4', $manager->getMessage('domain-a', 'key4'));
    $this->assertSame('Message 5 UK', $manager->getMessage('domain-a', 'key5'));
    $this->assertSame('French 6', $manager->getMessage('domain-a', 'key6'));
    $this->assertSame('Message 1 UK', $manager->getMessage('domain-b', 'key1'));
  }
}

