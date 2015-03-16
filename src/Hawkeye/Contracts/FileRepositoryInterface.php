<?php namespace Viraj\Hawkeye\Contracts;

interface FileRepositoryInterface
{
    public function storeFileAndGetName($fileData);
}