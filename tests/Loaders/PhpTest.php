<?php

namespace vendelev\config\Test\Loaders;

use UnexpectedValueException;
use vendelev\config\Loaders\Php;

/**
 * @coversDefaultClass \Vendelev\Config\Loaders\Php
 */
class PhpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers ::load()
     */
    public function load()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $loader = new Php();
        $this->assertEquals(['test' => 1234], $loader->load(__DIR__ .'/../mocks/loaders/php/array.php'));
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     * @covers ::load()
     */
    public function loadBad()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $loader = new Php();
        $loader->load(__DIR__ .'/../mocks/loaders/php/string.php');
    }

    /**
     * @test
     * @expectedException \Vendelev\Config\Exceptions\LoadException
     * @covers ::load()
     */
    public function loadException()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $loader = new Php();
        $loader->load(__DIR__ . '/../mocks/loaders/php/exception.php');
    }
}
