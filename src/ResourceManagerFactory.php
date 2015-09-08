<?php

namespace DeepFreeze\Intl\Resource;


use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;

/**
 * Class ResourceManagerFactory
 *
 * Basic factory instance for instantiating the Resource Manager from a configuration array.
 * For more details {@see 00-Documentation.rst}.
 *
 * @package DeepFreeze\Intl\Resource
 */
class ResourceManagerFactory
{
  public static function factory(array $config) {
    $resourceManager = new ResourceManager();
    $options = $resourceManager->getOptions();

    // Avaliable languages is optional
    if (isset($config['available_languages'])) {
      $options->setAvailableLanguages($config['available_languages']);
    }

    // Fallback language is optional
    if (isset($config['fallback_language'])) {
      $options->setFallbackLanguage($config['fallback_language']);
    }

    // Translation sources are optional, though yields items useless
    if (!empty($config['translation_sources'])) {
      if (!is_array($config['translation_sources'])) {
        throw new InvalidArgumentException('translation_sources', $config['translation_sources'],
          'Parameter "translation_sources" must be of type array.');
      }

      // Process each of the translation sources
      foreach ($config['translation_sources'] as $key => $translationSource) {
        // Translation Sources must be an array
        if (!is_array($translationSource)) {
          throw new InvalidArgumentException('translation_sources:' . $key, $translationSource, 'Each translation source must be of type "array".');
        }

        // Plugin type is required
        if (!isset($translationSource['type'])) {
          throw new InvalidArgumentException('type', null, sprintf('Translation source "%s" is missing a value for "type".', $key));
        }

        // Options are options, though this would make the manager somewhat useless
        $loader = $resourceManager->getLoader($translationSource['type']);
        if (isset($translationSource['options'])) {
          $loader->getOptions()->fromArray($translationSource['options']);
        }

        if (isset($translationSource['text_domains'])) {
          if (!is_array($translationSource['text_domains'])) {
            throw new InvalidArgumentException('text_domains', null, sprintf('The option "text_domains" must be of type "array".'));
          }
          foreach ($translationSource['text_domains'] as $textDomain) {
            $resourceManager->addTranslationSource($textDomain, $loader);
          }
        }
      }
    }

    return $resourceManager;
  }
}
