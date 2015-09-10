<?php

namespace DeepFreeze\Intl\Resource;
use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;
use DeepFreezeSpi\Intl\Resource\ResourceLoaderInterface;

/**
 * Class TextDomainManager
 * Separate manager for TextDomains, to declutter it from the Resource Manager.
 * This class is not designed for general use outside this library.
 * @package DeepFreeze\Intl\Resource
 */
class TextDomainManager {
  /**
   * TextDomain Loaders.
   * array [ $textDomain ] : ResourceLoaderInterface[]
   * @var array
   */
  private $loaders = array();

  /**
   * @param string $namespace
   * @return bool
   */
  public function isDomainDefined($namespace) {
    return isset($this->loaders[$namespace]);
  }


  public function addLoader($textDomain, ResourceLoaderInterface $loader) {
    if (!isset($this->loaders[$textDomain])) {
      $this->loaders[$textDomain] = array();
    }
    $this->loaders[$textDomain][] = $loader;
  }

  /**
   * @param string $textDomain
   * @param string[] $fallbackChain
   * @return string[]
   */
  public function loadLanguageChain($textDomain, array $fallbackChain) {
    $this->requireDomain($textDomain);

    $messages = array();
    // Later defined languages are used as a fallback.
    foreach ($fallbackChain as $language) {
      $result = $this->loadLanguage($textDomain, $language);
      // Merge new results underneath current result
      $messages = array_replace($result, $messages);
    }
    return $messages;
  }

  /**
   * @param string $textDomain
   * @param string $language
   * @return string[]
   */
  public function loadLanguage($textDomain, $language) {
    $this->requireDomain($textDomain);
    /**
     * @var ResourceLoaderInterface $loader
     */
    $messages = array();
    // Later defined loaders overwrite messages.
    foreach ($this->loaders[$textDomain] as $loader) {
      $result = $loader->load($textDomain, $language);
      $messages = array_replace($messages, $result);
    }
    return $messages;
  }

  /**
   * Ensure that the requested domain is defined.
   * Throws an excetpion on error
   * @throws InvalidArgumentException
   * @param string $textDomain
   */
  private function requireDomain($textDomain) {
    if (!$this->isDomainDefined($textDomain)) {
      throw new InvalidArgumentException('textDomain', $textDomain, 'The Text Domain requested is not registered.');
    }
  }
}
