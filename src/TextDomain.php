<?php

namespace DeepFreeze\Intl\Resource;


/**
 * Class TextDomain
 *
 * Stub class to handle messages for a given locale.
 *
 * @package DeepFreeze\Intl\Resource
 */
class TextDomain extends \ArrayObject {
  public function mergeArray(array $array) {
    $this->exchangeArray(array_replace($this->getArrayCopy(), $array));
  }


  public function mergeTextDomain(TextDomain $textDomain) {
    $this->mergeArray($textDomain->getArrayCopy());
  }
}
