<?php


namespace DeepFreezeTest\Intl\Resource;

use DeepFreeze\Intl\Resource\Messages;
use PHPUnit_Framework_TestCase as TestCase;

class MessagesTest extends TestCase {
  /**
   * @dataProvider dataMergeArrayOverwritesExistingValues
   * @param array $baseArray
   * @param array $valuesToMerge
   * @param array $expected
   */
  public function testMergeArrayOverwritesExistingValues(array $baseArray, array $valuesToMerge, array $expected) {
    $messages = new Messages($baseArray);
    $messages->mergeArray($valuesToMerge);
    $actual = $messages->getArrayCopy();
    ksort($actual);
    $this->assertSame($expected, $actual);
  }

  /**
   * @dataProvider dataMergeArrayOverwritesExistingValues
   * @param array $baseArray
   * @param array $valuesToMerge
   * @param array $expected
   */
  public function testMergeMessagesOverwritesExistingValues(array $baseArray, array $valuesToMerge, array $expected) {
    $messages = new Messages($baseArray);
    $mergeMessages = new Messages($valuesToMerge);
    $messages->mergeMessages($mergeMessages);
    $actual = $messages->getArrayCopy();
    ksort($actual);
    $this->assertSame($expected, $actual);
  }


  public function dataMergeArrayOverwritesExistingValues() {
    return array(
      'empty-source' => array(
        array(),
        array('k1' => 'M1', 'k2' => 'M2'),
        array('k1' => 'M1', 'k2' => 'M2'),
      ),
      'empty-merge' => array(
        array('k1' => 'M1', 'k2' => 'M2'),
        array(),
        array('k1' => 'M1', 'k2' => 'M2'),
      ),
      'partial-overwrite' => array(
        array('k1' => 'M1', 'k2' => 'M2', 'k3' => 'M3'),
        array('k2' => 'M2O', 'k4' => 'M4'),
        array('k1' => 'M1', 'k2' => 'M2O', 'k3' => 'M3', 'k4' => 'M4'),
      ),
    );
  }
}
