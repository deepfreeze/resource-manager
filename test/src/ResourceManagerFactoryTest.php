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
}

