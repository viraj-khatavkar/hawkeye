<?php namespace Viraj\Hawkeye;

use Illuminate\Support\Facades\DB;

class FileRepository implements FileRepositoryInterface
{

    public function storeFileAndGetName($fileData)
    {
        $id = DB::table('users')->insertGetId($fileData);

        return md5($id);
    }

}