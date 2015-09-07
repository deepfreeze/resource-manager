<?php
namespace DeepFreeze\Intl\Resource\Loader;

use DeepFreeze\Intl\Resource\Exception\InvalidArgumentException;
use DeepFreeze\Intl\Resource\TextDomain;
use DeepFreezeSpi\Intl\Resource\ResourceRequestInterface as ResourceRequest;

class PhpArrayLoader
{
  /**
   * Returns an instance of the LoaderOptions
   * @return PhpArrayLoaderOptions
   */
  public function getOptions() {
    return new PhpArrayLoaderOptions();
  }

  /**
   * Process loading a file.
   * @param ResourceRequest $request
   * @param PhpArrayLoaderOptions $options
   * @return TextDomain
   */
  public function load(ResourceRequest $request, PhpArrayLoaderOptions $options) {
    $templates = array_reverse($options->getFileTemplates());
    $filenames = $this->resolveTemplates($request, $templates, $options->getBasePath());

    $messages = new TextDomain();
    foreach ($filenames as $filename) {
      $result = include $filename;
      if (!is_array($result)) {
        throw new InvalidArgumentException('file', $result, 'Resource file must return an array.');
      }

      $messages->mergeArray($result);
    }
    return $messages;
  }


  /**
   * @param ResourceRequest $request
   * @param array $templates
   * @return array
   */
  private function resolveTemplates(ResourceRequest $request, array $templates, $basePath) {
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
        return !preg_match('#{(language|region|script|variants|extensions|tag)}#', $template);
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
