<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Loader\FileSystemLoader;
use DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions;
use DeepFreeze\Intl\Resource\ResourceManagerOptions;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceManagerOptionsTest extends TestCase {
  public function testAvailableLocaleDefaultValue() {
    $options = new ResourceManagerOptions();
    $this->assertSame(array(), $options->getAvailableLocales());
  }

  public function testAvailableLocaleSetter() {
    $options = new ResourceManagerOptions();
    $options->setAvailableLocales(array('en-US'));
    $this->assertSame(array('en-US'), $options->getAvailableLocales());
  }

  public function testFallbackLocaleSetter() {
    $options = new ResourceManagerOptions();
    $options->setFallbackLocale('en-US');
    $this->assertSame('en-US', $options->getFallbackLocale());
  }
}
