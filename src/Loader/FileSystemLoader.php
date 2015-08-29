<?php

namespace DeepFreeze\Intl\Resource\Loader;

class FileSystemLoader {
  /**
   * Instance Options
   * @var FileSystemLoaderOptions
   */
  private $options;

  /**
   * @return FileSystemLoaderOptions
   */
  public function getOptions() {
    if (null === $this->options) {
      $this->options = new FileSystemLoaderOptions();
    }
    return $this->options;
  }

  /**
   * @param FileSystemLoaderOptions $options
   */
  public function setOptions(FileSystemLoaderOptions $options) {
    $this->options = $options;
  }


}
