<?php namespace Viraj\Hawkeye;

interface FileRepositoryInterface
{
    public function storeFileAndGetName($fileData);
}