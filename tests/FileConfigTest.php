<?php

namespace vendelev\config\Test;

use PHPUnit_Framework_TestCase;
use vendelev\config\FileConfig;

/**
 * @coversDefaultClass \vendelev\config\FileConfig
 */
class FileConfigTest extends PHPUnit_Framework_TestCase
{
    protected $path    = '/tmp/FileConfigTest';
    protected $default = 'Default';
    protected $custom  = 'Custom';
    protected $file    = 'array.php';

    /**
     * @var FileConfig
     */
    protected $config = null;

    /**
     * @test
     * @covers ::load()
     * @covers ::__construct()
     */
    public function load()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertInstanceOf('\Vendelev\Config\FileConfig', $this->getConfig());
    }

    /**
     * @test
     * @expectedException \vendelev\config\Exceptions\UnsupportedTypeException
     * @covers ::getLoader()
     */
    public function getLoader()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        FileConfig::load('test.test');
    }

    /**
     * @test
     * @expectedException \vendelev\config\Exceptions\FileNotExistException
     * @covers ::getFilePath()
     * @covers ::__construct()
     */
    public function getFilePath()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        FileConfig::load('array2.php');
    }

    /**
     * @test
     * @covers ::checkFile()
     * @covers ::all()
     */
    public function checkFile()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        mkdir($this->getCustom());
        file_put_contents($this->getCustom() .'/'. $this->file, '<?php return ["test" => 123];');

        $this->assertEquals(['test' => 123], FileConfig::load($this->file)->all());
    }

    /**
     * @test
     * @covers ::getCache()
     * @covers ::set()
     * @covers ::get()
     * @covers ::has()
     */
    public function cache()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertInstanceOf('\vendelev\cache\RuntimeCache', $this->getConfig()->getCache());

        $this->getConfig()->set('test2', 123);
        $this->assertTrue($this->getConfig()->has('test2'));
        $this->assertEquals(123, $this->getConfig()->get('test2'));
    }

    /**
     * @test
     * @covers ::setSeparate()
     * @covers ::getSeparate()
     */
    public function separate()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals('/', $this->getConfig()->getSeparate());
        $this->assertEquals('.', $this->getConfig()->setSeparate('.')->getSeparate());
    }

    /**
     * @test
     * @covers ::getPath()
     * @covers ::setPath()
     */
    public function path()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        FileConfig::setPath('/test23/');
        $this->assertEquals('/test23/', FileConfig::getPath());
    }

    /**
     * @test
     * @covers ::getServerType()
     * @covers ::setServerType()
     */
    public function serverType()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals('Default', FileConfig::getServerType());

        FileConfig::setServerType('Prod');
        $this->assertEquals('Prod', FileConfig::getServerType());
    }

    public function setUp()
    {
        $this->setPath(time());

        mkdir($this->getPath());
        mkdir($this->getDefault());

        FileConfig::setPath($this->getPath());

        copy(__DIR__ .'/mocks/loaders/php/array.php', $this->getDefault() .'/'. $this->file);

        $this->setConfig(FileConfig::load($this->file));
    }

    public function tearDown()
    {
        if (is_file($this->getDefault() .'/'. $this->file)) {
            unlink($this->getDefault() .'/'. $this->file);
        }

        if (is_dir($this->getDefault())) {
            rmdir($this->getDefault());
        }

        if (is_file($this->getCustom() .'/'. $this->file)) {
            unlink($this->getCustom() .'/'. $this->file);
        }

        if (is_dir($this->getCustom())) {
            rmdir($this->getCustom());
        }

        rmdir($this->getPath());
    }

    /**
     * @return FileConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param FileConfig $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path.= $path;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->getPath() .'/'. $this->default;
    }

    /**
     * @return string
     */
    public function getCustom()
    {
        return $this->getPath() .'/'. $this->custom;
    }
}
