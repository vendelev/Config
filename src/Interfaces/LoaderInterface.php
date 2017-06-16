<?php

namespace vendelev\config\Interfaces;


interface LoaderInterface
{
    /**
     * @param string $filePath
     *
     * @return array
     */
    public function load($filePath);
}