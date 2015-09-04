<?php

namespace DeepFreeze\Intl\Resource;

class ResourceManager {
  /**
   * @var ResourceManagerOptions
   */
  private $options;

  /**
   * @return ResourceManagerOptions
   */
  public function getOptions() {
    if (null === $this->options) {
      $this->options = new ResourceManagerOptions();
    }
    return $this->options;
  }

  /**
   * @param ResourceManagerOptions $options
   */
  public function setOptions(ResourceManagerOptions $options) {
    $this->options = $options;
  }


}
