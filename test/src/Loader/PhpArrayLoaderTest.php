<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Loader\FileSystemLoader;
use DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions;
use DeepFreeze\Intl\Resource\Loader\PhpArrayLoader;
use DeepFreeze\Intl\Resource\Loader\PhpArrayLoaderOptions;
use DeepFreeze\Intl\Resource\Request;
use PHPUnit_Framework_TestCase as TestCase;

class PhpArrayLoaderTest extends TestCase {
  public function testGetOptionReturnsCorrectInterface() {
    $loader = new PhpArrayLoader();
    $options = $loader->getOptions();
    $this->assertInstanceOf('DeepFreezeSpi\Intl\Resource\LoaderOptionsInterface', $options);
    $this->assertInstanceOf('DeepFreeze\Intl\Resource\Loader\PhpArrayLoaderOptions', $options);
  }

  public function testLoadSingleFile() {
    $loader = new PhpArrayLoader();
    $options = $loader->getOptions();
    $options->setBasePath(__DIR__ . '/../../files');
    $options->setFileTemplates(array('simple.en.res.php'));
    $result = $loader->load('domain', 'en');
    $expected = array(
      'key1' => 'Message 1',
      'key2' => 'Message 2',
      'key3' => 'Message 3',
    );
    $this->assertSame($expected, $result);
  }

  public function testLoadWithOverloadedFiles() {
    $loader = new PhpArrayLoader();
    $options = $loader->getOptions();
    $options->setBasePath(__DIR__ . '/../../files');
    $options->setFileTemplates(array(
      'simple.en.res.php',
      'simple.en-UK.res.php',
    ));
    $result = $loader->load('domain', 'en');
    $expected = array(
      'key1' => 'Message 1 UK',
      'key2' => 'Message 2 UK',
      'key3' => 'Message 3',
      'key5' => 'Message 5 UK',
    );
    $actual = $result;
    ksort($actual);
    $this->assertSame($expected, $actual);
  }

}
