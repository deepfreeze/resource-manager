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

  /**
   * @return \string[]
   */
  public function getFileTemplates() {
    return $this->fileTemplates;
  }

  /**
   * Ordered array of file templates.
   * @param \string[] $fileTemplates
   */
  public function setFileTemplates(array $fileTemplates) {
    $stripLeadingSeparator = function($e) {
      if ($e{0} === '/' || $e{0} === PATH_SEPARATOR) {
        $e = (string)substr($e, 1);
      }
      return $e;
    };
    $fileTemplates = array_map($stripLeadingSeparator, $fileTemplates);
    $this->fileTemplates = $fileTemplates;
  }

}
