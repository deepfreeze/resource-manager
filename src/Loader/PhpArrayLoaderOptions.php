<?php

namespace DeepFreeze\Intl\Resource\Loader;

class PhpArrayLoaderOptions {
  /**
   * @var string
   */
  private $basePath;
  /**
   * @var string[]
   */
  private $fileTemplates = array();

  /**
   * @return string
   */
  public function getBasePath() {
    return $this->basePath;
  }

  /**
   * @param string $basePath
   */
  public function setBasePath($basePath) {
    if (preg_match('#./$#', $basePath)) {
      $basePath = substr($basePath, 0, -1);
    }
    $this->basePath = $basePath;
  }


}
