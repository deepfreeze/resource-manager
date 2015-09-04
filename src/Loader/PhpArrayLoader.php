<?php
namespace DeepFreeze\Intl\Resource\Loader;

class PhpArrayLoader {
  /**
   * Returns an instance of the LoaderOptions
   * @return PhpArrayLoaderOptions
   */
  public function getOptions() {
    return new PhpArrayLoaderOptions();
  }
}
