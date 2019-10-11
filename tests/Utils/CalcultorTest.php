<?php
namespace App\Tests\Utils;
use App\Utils\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $calculator = new Calculator();
        $result = $calculator->add(10, 32);
        
        $this->assertEquals(42, $result);
    }
}
?>