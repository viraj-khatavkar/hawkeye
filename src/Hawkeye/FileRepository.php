<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Contracts\FileRepositoryInterface;
use Illuminate\Support\Facades\DB;

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
        $this->fileName = md5(DB::table(config('hawkeye.hawkeye_table'))->insertGetId($fileData));

        return $this->fileName;
    }

}