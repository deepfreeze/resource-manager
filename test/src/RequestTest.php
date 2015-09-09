<?php


namespace DeepFreezeTest\Intl\Resource;

use DeepFreeze\Intl\Resource\Request;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase as TestCase;

class RequestTest extends TestCase
{
  public function testDomainIsPropagated() {
    $request = new Request('test-domain', 'zz');
    $this->assertSame('test-domain', $request->getTextDomain());
  }

  /**
   * @dataProvider dataLanguageTagParsing
   * @param $languageTag
   * @param $expected
   */
  public function testLanguageTagParsing($languageTag, $expected) {
    $request = new Request('domain', $languageTag);
    $actual = $this->serializeRequest($request);
    $this->assertSame($expected, $actual);
  }


  public function dataLanguageTagParsing() {
    return array(
      array(
        'en',
        $this->serializeLanguageParams('en', 'en', '', '', '', array(), '', array()),
      ),
      array(
        'en-Latn',
        $this->serializeLanguageParams('en-Latn', 'en', 'Latn', '', '', array(), '', array()),
      ),
      array(
        'en-001',
        $this->serializeLanguageParams('en-001', 'en', '', '001', '', array(), '', array()),
      ),
      array(
        'en-US',
        $this->serializeLanguageParams('en-US', 'en', '', 'US', '', array(), '', array()),
      ),
      array(
        'de-1901',
        $this->serializeLanguageParams('de-1901', 'de', '', '', '1901', array('1901'), '', array()),
      ),
      array(
        'en-Latn-US',
        $this->serializeLanguageParams('en-Latn-US', 'en', 'Latn', 'US', '', array(), '', array()),
      ),
      array(
        'en-x-private',
        $this->serializeLanguageParams('en-x-private', 'en', '', '', '', array(), 'x-private', array('x' => array('private'))),
      ),
      array(
        'x-private',
        $this->serializeLanguageParams('x-private', '', '', '', '', array(), 'x-private', array('x' => array('private'))),
      ),
      array(
        'en-u-ca-buddhist',
        $this->serializeLanguageParams('en-u-ca-buddhist', 'en', '', '', '', array(), 'u-ca-buddhist', array('u' => array('ca' => 'buddhist'))),
      ),
      array(
        'sl-SL-rozaj-solba-1994',
        $this->serializeLanguageParams('sl-SL-rozaj-solba-1994', 'sl', '', 'SL', 'rozaj-solba-1994', array('rozaj', 'solba', '1994'), '', array()),
      )
    );
  }


  /**
   * Test the serialization method
   */
  public function testSerializeRequest() {
    /**
     * @var Request|MockInterface $request
     */
    $request = \Mockery::mock('DeepFreeze\Intl\Resource\Request');
    $request->shouldReceive('getLanguageTag')->andReturn('en-Latn-001-oed-q-unknown-extension-u-ca-buddhist-co-standard-z-must-before-extx-x-test-private');
    $request->shouldReceive('getLanguage')->andReturn('en');
    $request->shouldReceive('getScript')->andReturn('Latn');
    $request->shouldReceive('getRegion')->andReturn('001');
    $request->shouldReceive('getExtensionsTag')
      ->andReturn('q-unknown-extension-u-ca-buddhist-co-standard-z-must-before-extx-x-test-private');
    $request->shouldReceive('getVariants')->andReturn(array('oed'));
    $request->shouldReceive('getVariantsTag')->andReturn('oed');
    $request->shouldReceive('getExtensions')->andReturn(array(
      'q' => array('unknown', 'extension',),
      'u' => array('ca' => 'buddhist', 'co' => 'standard',),
      'z' => array('must', 'before', 'extx'),
      'x' => array('test', 'private',),
    ));
    $expected = array(
      'tag' => 'en-Latn-001-oed-q-unknown-extension-u-ca-buddhist-co-standard-z-must-before-extx-x-test-private',
      'language' => 'en',
      'script' => 'Latn',
      'region' => '001',
      'variant-tag' => 'oed',
      'variants' => array(
        'oed',
      ),
      'extension-tag' => 'q-unknown-extension-u-ca-buddhist-co-standard-z-must-before-extx-x-test-private',
      'extensions' => array(
        'q' => array(
          'unknown',
          'extension',
        ),
        'u' => array(
          'ca' => 'buddhist',
          'co' => 'standard',
        ),
        'z' => array(
          'must',
          'before',
          'extx',
        ),
        'x' => array(
          'test',
          'private',
        ),
      ),
    );
    $this->assertSame($expected, $this->serializeRequest($request));
  }

  private function serializeRequest(Request $request) {
    return $this->serializeLanguageParams(
      $request->getLanguageTag(),
      $request->getLanguage(),
      $request->getScript(),
      $request->getRegion(),
      $request->getVariantsTag(),
      $request->getVariants(),
      $request->getExtensionsTag(),
      $request->getExtensions()
    );
  }

  private function serializeLanguageParams($tag, $language, $script, $region, $variantTag, $variants, $extensionTag, $extensions) {
    return array(
      'tag' => $tag,
      'language' => $language,
      'script' => $script,
      'region' => $region,
      'variant-tag' => $variantTag,
      'variants' => $variants ?: array(),
      'extension-tag' => $extensionTag,
      'extensions' => $extensions ?: array(),
    );
  }

}
