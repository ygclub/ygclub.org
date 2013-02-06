<?php
require_once 'person.php';

class PersonTest extends PHPUnit_Framework_TestCase {
    public $test;

    public function setup() {
        $this->test = new Person('John');
    }

    public function testName() {
        $john = $this->test->getName();
        $this->assertTrue($john == 'John');
    }
}
?>