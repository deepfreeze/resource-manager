<?php

namespace DeepFreeze\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;
use DeepFreezeSpi\Intl\Resource\LoaderOptionsInterface;

class PhpArrayLoaderOptions implements LoaderOptionsInterface {
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

  /**
   * Set options for this instance using the given array.
   * This method does not reset state before applying the options.
   * @param array $options
   */
  public function fromArray(array $options) {
    foreach ($options as $key => $value) {
      $method = 'set' . str_replace('_', '', $key);
      if (!method_exists($this, $method)) {
        throw new InvalidArgumentException('option_key', $key, sprintf('There is no corresponding setter for option "%s".', $key));
      }
      $this->$method($value);
    }
  }
}
