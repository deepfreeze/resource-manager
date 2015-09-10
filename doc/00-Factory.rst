=============================
Factory Option Configurations
=============================

Configuration Keys

: available_languages :
  string[]

  Unordered list of available locales to use

: fallback_language :
  string[]

  Ordered list of locales to use as fallback.
  Language Fallback is done in reverse order.

  For example, ["en", "en_UK"] would entail that should a

: locale_provider :

: text_domains :
    array[]

    Describes the defined text domains

: translation_sources : array
   : type : string

     Plugin used to realise this translation source

   : options : array

     Options represents plugin specific options used to configure the loader. Details of each
     are given below.


Plugin Definitions
==================

This section contains information regarding the various translation source plugins that are available.

PHP Array
=========

: Name :
    `php-array

: Options :

    : base_path : string : Optional

        Path to use a base for the file_templates.  If this value is not set, or is not an absolute
        file path, then file loading will follow the search path as it applies to "include" statements
        in PHP.

    : file_templates : string[] : Required

        An ordered list of file templates.

        The most specific file template matched, will load.
        Entries defined later will take priority.

        If a variable substitution has not been replaced during a load-request, that entry will
        not be attempted.


        The following variable substitutions are supported:


            +=============+--------------+-------------------------+
            | {language}  | Language     | en                      |
            +=============+--------------+-------------------------+
            | {script}    | Script       | Latn                    |
            +=============+--------------+-------------------------+
            | {region}    | Region       | US                      |
            +=============+--------------+-------------------------+
            | {variants}  | Variants     | 1996                    |
            |             |              | rozaj-solba-1994        |
            +=============+--------------+-------------------------+
            | {exensions} | Extensions   | u-ca-buddhist           |
            +============+---------------+--------------------------------------------+
            | {tag}       | Language Tag | en                                         |
            |             |              | en-US                                      |
            |             |              | en-Latn-US                                 |
            |             |              | de-DE-1996                                 |
            |             |              | sl-Latn-SL-rozaj-solba-1994                |
            |             |              | sl-Latn-SL-rozaj-solba-1994-u-ca-buddhist  |
            +=============+--------------+--------------------------------------------+
