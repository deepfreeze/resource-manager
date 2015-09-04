<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Loader\FileSystemLoader;
use DeepFreeze\Intl\Resource\Loader\FileSystemLoaderOptions;
use DeepFreeze\Intl\Resource\Loader\PhpArrayLoaderOptions;
use PHPUnit_Framework_TestCase as TestCase;

class PhpArrayLoaderOptionsTest extends TestCase {
  public function testBasePathInitialValueIsNull() {
    $options = new PhpArrayLoaderOptions();
    $this->assertNull($options->getBasePath());
  }


  /**
   * @dataProvider dataBasePathMutators
   * @param $value
   * @param $expected
   */
  public function testBasePathMutators($value, $expected) {
    $options = new PhpArrayLoaderOptions();
    $options->setBasePath($value);
    $this->assertSame($expected, $options->getBasePath());
  }

  public function dataBasePathMutators() {
    return array(
      array('../', '..'),
      array('/', '/'),
      array('/some/path', '/some/path'),
    );
  }

  public function testFileTemplateDefaultsToEmptyArray() {
    $options = new PhpArrayLoaderOptions();
    $this->assertSame(array(), $options->getFileTemplates());
  }

  public function testFileTemplateMutatorsWork() {
    $options = new PhpArrayLoaderOptions();
    $testValue = array('1', '2');
    $options->setFileTemplates($testValue);
    $this->assertSame($testValue, $options->getFileTemplates());
  }
}
