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
  private $languageTag;
  /**
   * @var string
   */
  private $language = '';
  /**
   * @var string
   */
  private $script = '';
  /**
   * @var string
   */
  private $region = '';
  /**
   * @var string[]
   */
  private $variants = array();
  /**
   * @var string[][]
   */
  private $extensions = array();
  /**
   * @var string
   */
  private $extensionTag;
  /**
   * @var string[]
   */
  private $renderedExtensions = array();

  public function __construct($textDomain, $targetLanguage) {
    $this->setTextDomain($textDomain);
    $this->setLanguage($targetLanguage);
  }

  /**
   * @param string $textDomain
   */
  private function setTextDomain($textDomain) {
    $this->textDomain = $textDomain;
  }

  /**
   * Basic Parsing.
   * This will need to leverage an external library.
   * @param string $languageTag
   */
  private function setLanguage($languageTag) {
    // Replace any UNDERSCORE with HYPHEN-MINUS.
    $languageTag = str_replace('_', '-', $languageTag);
    if (preg_match('#^[^a-z0-9A-Z\-]+$#', $languageTag)) {
      throw new Exception\InvalidArgumentException('languageTag', $languageTag,
        'Language tags permit only the characters A-Z, a-z, 0-9, and HYPHEN-MINUS (%x2D).');
    }

    // Private Use Tag
    if (preg_match('#^x(\-[a-zA-Z0-9]{2,8})+$#', $languageTag)) {
      $parts = explode('-', substr($languageTag, 2));
      $this->extensions['x'] = $parts;
      return;
    }

    // Nominal Formatted
    $matcher = '#^' .
      '(?P<language>[a-z]{2,3}(?:-[a-z]{3}){0,3})' .               # language 2*3 ALPHA ["-" EXTLANG]
      '(?:-(?P<script>[a-z]{4}))?' .                              # script     4 ALPHA
      '(?:-(?P<region>(?:[a-z]{2})|(?:[0-9]{3})))?' .             # region     2 ALPHA | 3 DIGIT
      '(?P<variants>(?:-(?:(?:[0-9][a-z0-9]{3})|(?:[a-z0-9]{5,8})))*)' .  # variant  5*8 ALNUM | 1 DIGIT 3 ALNUM
      '(?P<extensions>(?:-[a-wy-z0-9](?:-[a-z0-9]{2,8})+)*)' .    # extension  1 SINGLETON 1* ("-" 2*8 ALNUM )
      '(?P<private>(?:-x(?:-[a-z0-9]{1,8})+)?)' .              # private-use  "x" 1* ("-" 1*8 ALNUM )
      '$#i';

    if (!preg_match($matcher, strtolower($languageTag), $matches)) {
      throw new Exception\InvalidArgumentException('languageTag', $languageTag,
        'Language tag format not recognised.');
    }
    $this->language = strtolower($matches['language']);
    $this->script = ucfirst(strtolower($matches['script'])) ?: '';
    $this->region = strtoupper($matches['region']) ?: '';
    if ($matches['variants']) {
      $variants = explode('-', substr($matches['variants'], 1));
      $this->variants = $variants;
    }

    if ($matches['extensions']) {
      $variants = explode('-', substr($matches['extensions'], 1));
      $currentKey = null;
      $currentParts = array();
      foreach ($variants as $subtag) {
        if (strlen($subtag) === 1) {
          if ($currentKey) {
            $this->setExtension($currentKey, $currentParts);
          }
          $currentKey = $subtag;
          continue;
        }
        $currentParts[] = $subtag;
      }
      $this->setExtension($currentKey, $currentParts);
    }
    ksort($this->extensions);

    if ($matches['private']) {
      $private = explode('-', substr($matches['private'], 3));
      $this->extensions['x'] = $private;
    }
  }

  /**
   * @param string $extensionKey
   * @param string[] $segments
   */
  private function setExtension($extensionKey, array $segments) {
    switch ($extensionKey) {
      case 'u' :
        $extensionArray = array();
        $key = '';
        $attributes = array();
        $value = array();
        foreach ($segments as $segment) {
          // New Key
          if (strlen($segment) === 2) {
            if (empty($key) && !empty($attributes)) {
              $extensionArray['attributes'] = array_combine($attributes, $attributes);
            }
            if (!empty($key)) {
              $extensionArray[$key] = $value ? implode('-', $value) : 'true';
            }
            $key = $segment;
            $value = array();
            continue;
          }
          if ($key) {
            $value[] = $segment;
          } else {
            $attributes[] = $segments;
          }
        }
        if ($key || $value) {
          $extensionArray[$key] = $value ? implode('-', $value) : 'true';
        }
        break;
      default:
        $extensionArray = $segments;
    }
    $this->extensions[$extensionKey] = $extensionArray;

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
  public function getLanguageTag() {
    if (null === $this->languageTag) {
      $this->languageTag = implode('-', array_filter(array(
        $this->language,
        $this->script,
        $this->region,
        $this->getVariantsTag(),
        $this->getExtensionsTag(),
      )));
    }
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
    if (!isset($this->extensions[$key])) {
      return '';
    }
    if (!isset($this->renderedExtensions[$key])) {
      if (empty($this->extensions[$key])) {
        return '';
      }
      switch ($key) {
        case 'u' :
          $renderedValue = array();
          foreach ($this->extensions[$key] as $subKey => $value) {
            if ($subKey === 'attributes') {
              $renderedValue[] = implode('-', $value);
              continue;
            }
            if ($value === 'true') {
              $renderedValue[] = $subKey;
              continue;
            }
            $renderedValue[] = sprintf('%s-%s', $subKey, $value);
          }
          $renderedValue = implode('-', $renderedValue);
          break;
        default:
          $renderedValue =            implode('-', $this->extensions[$key]);
      }
      $this->renderedExtensions[$key] = sprintf('%s-%s', $key, $renderedValue);
    }
    return $this->renderedExtensions[$key];
  }

  /**
   * @return string
   */
  public function getExtensionsTag() {
    if (null === $this->extensionTag) {
      $parts = array();
      foreach ($this->extensions as $key => $value) {
        $parts[$key] = $this->getExtensionTag($key);
      }
      uksort($parts, function($a, $b) {
        if ($a === 'x') { return 1;}
        if ($b === 'x') { return -1; }
        return strcmp($a, $b);
      });
      $this->extensionTag = implode('-', $parts);
    }
    return $this->extensionTag;
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
