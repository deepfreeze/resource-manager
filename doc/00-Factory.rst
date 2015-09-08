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



     : format :
     : basepath :
     : file_templates :

Plugin Definitions
==================

This section contains information regarding the various translation source plugins that are available.

PHP Array
=========

: Name :
    `php-array

: Options :
