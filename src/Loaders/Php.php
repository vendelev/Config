<?php

namespace vendelev\config\Loaders;

use Exception;
use UnexpectedValueException;
use vendelev\config\Exceptions\LoadException;
use vendelev\config\Interfaces\LoaderInterface;

class Php implements LoaderInterface
{
    /**
     * @param string $filePath
     *
     * @return array
     * @throws LoadException|UnexpectedValueException
     */
    public function load($filePath)
    {
        try {
            $returnValue = require($filePath);
        } catch (Exception $e) {
            throw new LoadException('PHP file threw an exception', 0, $e);
        }

        if (!is_array($returnValue)) {
            throw new UnexpectedValueException('PHP file does not return an array');
        }

        return $returnValue;
    }
}