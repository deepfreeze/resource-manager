<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Loader\FileSystemLoader;
use DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions;
use PHPUnit_Framework_TestCase as TestCase;

class FileSystemLoaderTest extends TestCase {
  public function testDefaultOptions() {
    $loader = new FileSystemLoader();
    $this->assertInstanceOf('DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions', $loader->getOptions());
  }

  public function testExplicitOptions() {
    $loader = new FileSystemLoader();
    $options = new FileSystemLoaderOptions();
    $loader->setOptions($options);
    $this->assertSame($options, $loader->getOptions());
  }
}
