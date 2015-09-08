<?php

namespace DeepFreeze\Intl\Resource\Exception;

class InvalidArgumentException extends \RuntimeException implements ExceptionInterface {
  private $argumentName;
  private $argumentValue;

  /**
   * @param string $paramName
   * @param int $paramValue
   * @param string $message
   * @param int $code
   * @param \Exception $previousException
   */
  public function __construct($paramName, $paramValue, $message='', $code=0, \Exception $previousException=null) {
    $this->argumentName = $paramName;
    $this->argumentValue = $paramValue;
    $message = $message ?: sprintf('Invalid Argument provided.  Name=%s  Value=%s', json_encode($paramName), json_encode($paramValue));
    parent::__construct($message, $code, $previousException);
  }

  /**
   * @return mixed
   */
  public function getArgumentName() {
    return $this->argumentName;
  }

  /**
   * @return mixed
   */
  public function getArgumentValue() {
    return $this->argumentValue;
  }


}
