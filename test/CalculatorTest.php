<?php
class CalculatorTest extends \PHPUnit\Framework\TestCase
{

  public function testAdd()
  {
    $cal = new Calculator();
    $result = $cal->add(2, 2);
  }
}