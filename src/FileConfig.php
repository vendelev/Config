<?php

namespace vendelev\config;

use vendelev\cache\RuntimeCache;
use vendelev\config\Exceptions\FileNotExistException;
use vendelev\config\Exceptions\UnsupportedTypeException;
use vendelev\config\Interfaces\LoaderInterface;

/**
 * @package vendelev\config
 */
class FileConfig
{
    /**
     * @var RuntimeCache
     */
    protected $cache = null;

    /**
     * @var string
     */
    protected $separate = '/';

    /**
     * @var string
     */
    protected static $confPath = '';

    /**
     * @var string
     */
    protected static $serverType = 'Default';

    /**
     * @param string $fileName
     * @param string $separate
     *
     * @return FileConfig
     */
    public static function load($fileName, $separate='/')
    {
        $instance = new self($fileName);
        $instance->setSeparate($separate);

        return $instance;
    }

    /**
     * @param string $fileName
     *
     * @throws FileNotExistException
     */
    public function __construct($fileName)
    {
        $loader  = $this->getLoader($fileName);
        $filePath= $this->getFilePath(self::getPath(), $fileName);

        if ($filePath) {
            $this->getCache()->setAll($loader->load($filePath));
        } else {
            throw new FileNotExistException('File '. $fileName .' does not exist');
        }
    }

    /**
     * @param string $fileName
     *
     * @return LoaderInterface
     * @throws UnsupportedTypeException
     */
    protected function getLoader($fileName)
    {
        $file = explode('.', $fileName);
        $ext  = array_pop($file);

        $class = __NAMESPACE__ .'\\Loaders\\'. ucfirst($ext);

        if (!class_exists($class)) {
            throw new UnsupportedTypeException('Unsupported '. $ext .' type');
        }

        return new $class();
    }

    /**
     * @param string $path
     * @param string $fileName
     *
     * @return bool|string
     */
    protected function getFilePath($path, $fileName)
    {
        $returnValue = $this->checkFile($path, 'Custom', $fileName);

        if (!$returnValue) {
            $returnValue = $this->checkFile($path, self::getServerType(), $fileName);
        }

        return $returnValue;
    }

    /**
     * @param string $path
     * @param string $type
     * @param string $file
     *
     * @return bool|string
     */
    protected function checkFile($path, $type, $file)
    {
        $filePath = implode('/', [$path, $type, $file]);

        if (is_file($filePath)) {
            return $filePath;
        } else {
            return false;
        }
    }

    /**
     * @param string     $path 'services/service/version'
     * @param null|mixed $defaultValue
     *
     * @return null|mixed
     */
    public function get($path, $defaultValue = null)
    {
        return $this->getCache()->get($path, $defaultValue);
    }

    /**
     * @param string $path 'services/service/version'
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($path, $value)
    {
        $this->getCache()->set($path, $value);

        return $this;
    }

    /**
     * @param string $path 'services/service/version'
     *
     * @return bool
     */
    public function has($path)
    {
        return $this->getCache()->has($path);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->getCache()->getAll();
    }

    /**
     * @return string
     */
    public static function getPath()
    {
        return self::$confPath;
    }

    /**
     * @param string $confPath
     */
    public static function setPath($confPath)
    {
        self::$confPath = $confPath;
    }

    /**
     * @return string
     */
    public static function getServerType()
    {
        return self::$serverType;
    }

    /**
     * @param string $serverType
     */
    public static function setServerType($serverType)
    {
        self::$serverType = $serverType;
    }

    /**
     * @return RuntimeCache
     */
    public function getCache()
    {
        if ($this->cache === null) {
            $this->cache = new RuntimeCache();
            $this->cache->setSeparate($this->getSeparate());
        }

        return $this->cache;
    }

    /**
     * @return string
     */
    public function getSeparate()
    {
        return $this->separate;
    }

    /**
     * @param string $separate
     *
     * @return $this
     */
    public function setSeparate($separate)
    {
        $this->separate = $separate;
        $this->getCache()->setSeparate($separate);

        return $this;
    }
}