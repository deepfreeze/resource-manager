<?php

namespace DeepFreeze\Intl\Resource;

class ResourceManagerOptions {
  /**
   * @var string[]
   */
  private $availableLocales = array();

  /**
   * @var string
   */
  private $fallbackLocale;

  /**
   * @return \string[]
   */
  public function getAvailableLocales() {
    return $this->availableLocales;
  }

  /**
   * @param \string[] $availableLocales
   */
  public function setAvailableLocales(array $availableLocales) {
    $this->availableLocales = $availableLocales;
  }

  /**
   * @return string
   */
  public function getFallbackLocale() {
    return $this->fallbackLocale;
  }

  /**
   * @param string $fallbackLocale
   */
  public function setFallbackLocale($fallbackLocale) {
    $this->fallbackLocale = $fallbackLocale;
  }


}
