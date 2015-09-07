<?php

namespace DeepFreeze\Intl\Resource;

use DeepFreezeSpi\Intl\Resource\ResourceRequestInterface;

class Request implements ResourceRequestInterface {
  /**
   * @var string
   */
  private $textDomain;
  /**
   * @var string
   */
  private $requestedLanguage;
  /**
   * @var string
   */
  private $languageTag;
  /**
   * @var string
   */
  private $language;
  /**
   * @var string
   */
  private $script;
  /**
   * @var string
   */
  private $region;
  /**
   * @var string[]
   */
  private $variants = array();
  /**
   * @var string[][]
   */
  private $extensions = array();
  /**
   * @var string[]
   */
  private $renderedExtensions = array();

  public function __construct($textDomain, $targetLanguage, $requestedLanguage=null) {
    $this->setTextDomain($textDomain);
    $this->setLanguage($targetLanguage);
    $this->setRequestedLanguage($requestedLanguage);
  }

  /**
   * @param string $textDomain
   */
  private function setTextDomain($textDomain) {
    $this->textDomain = $textDomain;
  }

  /**
   * @param string $requestedLanguage
   */
  private function setRequestedLanguage($requestedLanguage) {
    $this->requestedLanguage = $requestedLanguage;
  }

  /**
   * @param string $language
   */
  private function setLanguage($language) {
    // Parsing
  }

  /**
   * @return string
   */
  public function getTextDomain() {
    return $this->textDomain;
  }

  /**
   * @return string
   */
  public function getRequestedLanguage() {
    return $this->requestedLanguage;
  }

  /**
   * @return string
   */
  public function getLanguageTag() {
    return $this->languageTag;
  }

  /**
   * @return string
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * @return string
   */
  public function getScript() {
    return $this->script;
  }

  /**
   * @return string
   */
  public function getRegion() {
    return $this->region;
  }

  /**
   * @return \string[]
   */
  public function getVariants() {
    return $this->variants;
  }

  /**
   * @param $key
   * @return \string[]
   */
  public function getExtension($key) {
    return isset($this->extensions[$key]) ? $this->extensions[$key] : array();
  }

  /**
   * @param $key
   * @return string
   */
  public function getExtensionTag($key) {
    return isset($this->renderedExtensions[$key]) ? $this->renderedExtensions[$key] : null;
  }

  /**
   * @return string
   */
  public function getExtensionsTag() {
    return implode('-', $this->renderedExtensions);
  }

  /**
   * @return string
   */
  public function getVariantsTag() {
    return implode('-', $this->variants);
  }

  /**
   * @return \string[][]
   */
  public function getExtensions() {
    return $this->extensions;
  }

}
