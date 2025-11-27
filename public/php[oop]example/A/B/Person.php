<?php

namespace A\B;

define('AJYAL', true); // This constant belongs to the global namespace, not this namespace
const LARAVEL = 'laravel A'; // While this constant belongs to this namespace — and this is the difference between them

function hello()
{
    echo 'Hello A';
}

class Person
{

    const MALE = 'm';
    const FEMALE = 'f';
    public $name;
    public $gender;
    public $age;

    public static $country;

    public function __construct()
    {
        echo __CLASS__;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this; // return this object
    }
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public static function setCountry($country)
    {
        // self::$country = $country;
        static::$country = $country;
        //                          (self & static) 
        // Both are used inside classes to access:
        //         - Static properties
        //         - Static methods
        //         - Constants
        //         But they work in a different way when using inheritance.
    }

    public function name()
    {
        return $this->name;
    }
    public function age()
    {
        return $this->age;
    }
}
