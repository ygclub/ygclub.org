<?php

class person
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

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