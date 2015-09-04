<?php

namespace DeepFreezeTest\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\ResourceManager;
use PHPUnit_Framework_TestCase as TestCase;

class ResourceManagerTest extends TestCase {
  public function testDefaultOptionsInstanceIsCreated() {
    $manager = new ResourceManager();
    $options = $manager->getOptions();
    $this->assertInstanceOf('DeepFreeze\Intl\Resource\ResourceManagerOptions', $options);
  }
}
