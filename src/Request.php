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

  /**
   * @param string $textDomain
   */
  public function setTextDomain($textDomain) {
    $this->textDomain = $textDomain;
  }

  /**
   * @param string $requestedLanguage
   */
  public function setRequestedLanguage($requestedLanguage) {
    $this->requestedLanguage = $requestedLanguage;
  }

  /**
   * @param string $languageTag
   */
  public function setLanguageTag($languageTag) {
    $this->languageTag = $languageTag;
  }

  /**
   * @param string $language
   */
  public function setLanguage($language) {
    $this->language = $language;
  }

  /**
   * @param string $script
   */
  public function setScript($script) {
    $this->script = $script;
  }

  /**
   * @param string $region
   */
  public function setRegion($region) {
    $this->region = $region;
  }

  /**
   * @param \string[] $variants
   */
  public function setVariants($variants) {
    $this->variants = $variants;
  }

  /**
   * @param \string[][] $extensions
   */
  public function setExtensions($extensions) {
    $this->extensions = $extensions;
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
