<?php

namespace DeepFreeze\Intl\Resource;

class ResourceManagerOptions {
  /**
   * @var string[]
   */
  private $availableLanguages = array();

  /**
   * @var string
   */
  private $fallbackLanguage;

  /**
   * @return \string[]
   */
  public function getAvailableLanguages() {
    return $this->availableLanguages;
  }

  /**
   * @param \string[] $availableLanguages
   */
  public function setAvailableLanguages(array $availableLanguages) {
    $this->availableLanguages = $availableLanguages;
  }

  /**
   * @return string
   */
  public function getFallbackLanguage() {
    return $this->fallbackLanguage;
  }

  /**
   * @param string $fallbackLanguage
   */
  public function setFallbackLanguage($fallbackLanguage) {
    $this->fallbackLanguage = $fallbackLanguage;
  }


}
