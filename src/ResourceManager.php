<?php

namespace DeepFreeze\Intl\Resource;

use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;
use DeepFreezeSpi\Intl\Resource\ResourceLoaderInterface;

class ResourceManager
{
  /**
   * @var ResourceManagerOptions
   */
  private $options;

  /**
   * @var ResourceLoaderInterface[];
   */
  private $loaders;

  /**
   * @var TextDomainManager
   */
  private $textDomainManager;


  /**
   * @var string[]
   */
  private $languageResolution = array();

  /**
   * @var string[]
   */
  private $languageResolutionChain = array();

  /**
   * Loaded messages
   * This array is laid out as:
   *    $messages [ $availableLocale : string ] : Messages
   *
   * @var array[]
   */
  private $messages;

  /**
   * Basic map of loaders to instantiable classes.
   * Future support of dynamically registering plugins will have to come later.
   * @var string[]
   */
  private $plugins = array(
    'php-array' => 'DeepFreeze\Intl\Resource\Loader\PhpArrayLoader',
  );

  /**
   * Default language to use for this instance.
   * If this isn't set, it will default to the fallback language.
   * @var string
   */
  private $currentLanguage;

  /**
   * Set the requested language for this manager.
   * @param string $language
   */
  public function setRequestedLanguage($language) {
    $language = $this->resolveLanguage($language);
    $this->currentLanguage = $language;
  }

  /**
   * Retrieve the current language.
   *
   * This language may not match the requested language.
   * This language will be one of the available-languages, or the fallback language.
   *
   * @return string
   */
  public function getCurrentLanguage() {
    if (null === $this->currentLanguage) {
      $this->currentLanguage = $this->getOptions()->getFallbackLanguage();
    }

    return $this->currentLanguage;
  }

  /**
   * @return ResourceManagerOptions
   */
  public function getOptions() {
    if (null === $this->options) {
      $this->options = new ResourceManagerOptions();
    }
    return $this->options;
  }

  /**
   * @param ResourceManagerOptions $options
   */
  public function setOptions(ResourceManagerOptions $options) {
    $this->options = $options;
  }

  /**
   * Returns a loader instance for the given plugin name.
   * @param string $pluginName
   * @return ResourceLoaderInterface
   */
  public function getLoader($pluginName) {
    $pluginName = strtolower($pluginName);
    // If the plugin is already loaded, use it.
//    if (isset($this->loaders[$pluginName])) {
//      return $this->loaders[$pluginName];
//    }

    // Look-up a concrete instance
    if (!isset($this->plugins[$pluginName])) {
      throw new InvalidArgumentException('pluginName',
        $pluginName,
        sprintf('The loader plugin with the nome "%s" is not registered.', (string)$pluginName));
    }

    // Assign to class cache
    $plugin = new $this->plugins[$pluginName];
    return $plugin;
  }


  /**
   * Retrieve a message.
   * @param string $domain
   * @param string $messageKey
   * @param string $requestedLocale
   * @return string
   */
  public function getMessage($domain, $messageKey, $requestedLocale=null) {
    $locale = $requestedLocale ? $this->resolveLanguage($requestedLocale) : $this->getCurrentLanguage();
    if (!$this->isTextDomainLoaded($domain, $locale)) {
      $this->loadTextDomain($domain, $locale);
    }

    if (!isset($this->messages[$domain][$locale][$messageKey])) {
      return sprintf("%s[%s]:%s", $domain, $locale, $messageKey);
    }

    return $this->messages[$domain][$locale][$messageKey];
  }

  /**
   * Add a translation source definition
   *
   * Translation sources are processed in the order they are added, with messages loaded later
   * overriding corresponding messages provided by previous loaders.
   *
   * @param string $textDomain
   * @param ResourceLoaderInterface $loader
   */
  public function addTranslationSource($textDomain, ResourceLoaderInterface $loader) {
    $this->getTextDomainManager()->addLoader($textDomain, $loader);
  }


  /**
   * Is the text domain loaded.
   *
   * @param string $textDomain
   * @param string $language
   * @return bool
   */
  private function isTextDomainLoaded($textDomain, $language) {
    $resolvedLanguage = $this->resolveLanguage($language);
    if (isset($this->messages[$textDomain][$resolvedLanguage])) {
      return true;
    }
    return false;
  }

  /**
   * @return TextDomainManager
   */
  private function getTextDomainManager() {
    if (null === $this->textDomainManager) {
      $this->textDomainManager = new TextDomainManager();
    }
    return $this->textDomainManager;
  }


  /**
   * Load a given text domain.
   *
   * This function will cycle through all the loaders in order, appending the results to the
   * compiled Message instance.
   * @param $textDomain
   * @param $language
   */
  private function loadTextDomain($textDomain, $language) {
    if ($this->isTextDomainLoaded($textDomain, $language)) {
      return;
    }

    $resolvedLanguage = $this->resolveLanguage($language);
    $resolvedLanguageChain = $this->resolveLanguageChain($language);
    $loadedMessages = $this->getTextDomainManager()->loadLanguageChain($textDomain, $resolvedLanguageChain);
    $this->messages[$textDomain][$resolvedLanguage] = new Messages($loadedMessages);
  }


  /**
   * Resolve the requested language into it's constituent language chain.
   */
  private function resolveLanguage($language) {
    if (isset($this->languageResolution[$language])) {
      return $this->languageResolution[$language];
    }
    // TODO: Resolve language fully.
    if (in_array($language, $this->getOptions()->getAvailableLanguages())) {
      return $language;
    }
    return $this->getOptions()->getFallbackLanguage();
  }


  /**
   * Returns the appropriate fallback language chain for a given language.
   *
   * In other words, if a system has the available languages as en-CA, en-US, de-DE
   * Default language is de-DE
   * And a user requests en-SG; this will return array(en-CA, en-US, de-DE).
   * If a user requests fi-FI; will return array(de-DE)
   *
   * TODO: Use External Locale Provider
   * @param string $language
   * @return string[]
   */
  private function resolveLanguageChain($language) {
    $resolvedLanguage = $this->resolveLanguage($language);
    if (isset($this->languageResolutionChain[$resolvedLanguage])) {
      return $this->languageResolutionChain[$resolvedLanguage];
    }
    $chain = array();
    $request = new Request('na', $language);
    $chain[] = $request->getLanguageTag();
    $chain[] = implode('-', array_filter(array($request->getLanguage(), $request->getScript(), $request->getRegion())));
    $chain[] = implode('-', array_filter(array($request->getLanguage(), $request->getRegion())));
    $chain[] = implode('-', array_filter(array($request->getLanguage(), $request->getScript())));
    $chain[] = implode('-', array_filter(array($request->getLanguage())));
    $chain[] = $this->getOptions()->getFallbackLanguage();
    $chain = array_filter(array_unique($chain));
    return $chain;
  }
}
