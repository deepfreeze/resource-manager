<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\ResourceManagerOptions;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceManagerOptionsTest extends TestCase {
  public function testAvailableLocaleDefaultValue() {
    $options = new ResourceManagerOptions();
    $this->assertSame(array(), $options->getAvailableLanguages());
  }

  public function testAvailableLocaleSetter() {
    $options = new ResourceManagerOptions();
    $options->setAvailableLanguages(array('en-US'));
    $this->assertSame(array('en-US'), $options->getAvailableLanguages());
  }

  public function testFallbackLocaleSetter() {
    $options = new ResourceManagerOptions();
    $options->setFallbackLanguage('en-US');
    $this->assertSame('en-US', $options->getFallbackLanguage());
  }
}
