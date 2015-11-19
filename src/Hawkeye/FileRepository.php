<?php namespace Viraj\Hawkeye;

use Illuminate\Support\Facades\DB;
use Viraj\Hawkeye\Contracts\FileRepositoryInterface;

class FileRepository implements FileRepositoryInterface
{

    /**
     * MD5 hash for id
     *
     * @var
     */
    public $fileName;

    public function storeFileAndGetName($fileData)
    {
        $this->fileName = md5(DB::table('hawkeye')->insertGetId($fileData));

        return $this->fileName;
    }

}