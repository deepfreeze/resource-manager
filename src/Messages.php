<?php

namespace DeepFreeze\Intl\Resource;


/**
 * Class TextDomain
 *
 * Stub class to handle messages for a given locale.
 *
 * @package DeepFreeze\Intl\Resource
 */
class Messages extends \ArrayObject {
  /**
   * Merges the given array on top of the current instance.
   * @param array $array
   */
  public function mergeArray(array $array) {
    $this->exchangeArray(array_replace($this->getArrayCopy(), $array));
  }

  /**
   * Merges the given Messages on top of this instance.
   * @param Messages $textDomain
   */
  public function mergeMessages(Messages $textDomain) {
    $this->mergeArray($textDomain->getArrayCopy());
  }
}
