<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Loader\FileSystemLoader;
use DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions;
use DeepFreeze\Intl\Resource\Loader\PhpArrayLoader;
use DeepFreeze\Intl\Resource\Loader\PhpArrayLoaderOptions;
use PHPUnit_Framework_TestCase as TestCase;

class PhpArrayLoaderTest extends TestCase {
  public function testGetOptionReturnsCorrectInterface() {
    $loader = new PhpArrayLoader();
    $options = $loader->getOptions();
    $this->assertInstanceOf('DeepFreezeSpi\Intl\Resource\LoaderOptionsInterface', $options);
    $this->assertInstanceOf('DeepFreeze\Intl\Resource\Loader\PhpArrayLoaderOptions', $options);
  }

}
