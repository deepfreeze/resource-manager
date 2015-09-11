<?php
namespace DeepFreeze\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;
use DeepFreeze\Intl\Resource\Messages;
use DeepFreeze\Intl\Resource\Request;
use DeepFreezeSpi\Intl\Resource\ResourceLoaderInterface;
use DeepFreezeSpi\Intl\Resource\ResourceRequestInterface as ResourceRequest;

class PhpArrayLoader implements ResourceLoaderInterface
{
  /**
   * @var PhpArrayLoaderOptions;
   */
  private $options;

  /**
   * Returns an instance of the LoaderOptions
   * @return PhpArrayLoaderOptions
   */
  public function getOptions() {
    if (null === $this->options) {
      $this->options = new PhpArrayLoaderOptions();
    }
    return $this->options;
  }


  /**
   * Set the Options instance
   * @param PhpArrayLoaderOptions $options
   */
  public function setOptions(PhpArrayLoaderOptions $options) {
    $this->options = $options;
  }

  /**
   * Process loading a file.

   * @return string[]
   */
  public function load($textDomain, $language) {
    $request = new Request($textDomain, $language);
    $fileNames = $this->resolveTemplates($request);
    $fileNames = array_reverse($fileNames);
    foreach ($fileNames as $filename) {
      $result = include $filename;
      if (!is_array($result)) {
        throw new InvalidArgumentException('file', $result, 'Resource file must return an array.');
      }
      return $result;
    }
    return array();
  }


  /**
   * @param ResourceRequest $request
   * @return array
   */
  private function resolveTemplates(ResourceRequest $request) {
    $templates = $this->getOptions()->getFileTemplates();
    $basePath = $this->getOptions()->getBasePath();
    $templates = $this->resolveTemplateSubstitutions($request, $templates);
    $templates = $this->resolveFilenames($basePath, $templates);
    return $templates;
  }

  /**
   * @param ResourceRequest $request
   * @param array $templates
   * @return array
   */
  private function resolveTemplateSubstitutions(ResourceRequest $request, array $templates) {
    $substitutions = array_filter(array(
      '{text-domain}' => $request->getTextDomain(),
      '{language}' => $request->getLanguage(),
      '{region}' => $request->getRegion(),
      '{script}' => $request->getScript(),
      '{variants}' => $request->getVariants(),
      '{extensions}' => $request->getExtensionsTag(),
      '{tag}' => $request->getLanguageTag(),
    ));

    // Replace any tag substitutions
    $templates = array_map(function ($template) use ($substitutions) {
      return str_replace(array_keys($substitutions), array_values($substitutions), $template);
    },
      $templates);

    // Filter out any template path that still contains an unresolved substitution
    $templates = array_filter($templates,
      function ($template) {
        return !preg_match('#{(text-domain|language|region|script|variants|extensions|tag)}#', $template);
      });
    return $templates;
  }

  /**
   * @param array $templates
   * @return array
   */
  private function resolveFilenames($basePath=null, array $templates) {
    // Resolve the file-paths, removing any non-existent.
    $templates = array_filter(array_map(function ($template) use ($basePath) {
      $template = $basePath . '/' . $template;
      return stream_resolve_include_path($template);
    },
      $templates));
    return $templates;
  }

}
