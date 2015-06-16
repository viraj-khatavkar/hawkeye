# Hawkeye
A Laravel package for uploading files automatically in a nested md5 hash naming folder structure. A mini CDN for optimized file storage for Laravel.

## Objective
Normally, we upload a file into a specific folder. In small applications, this is perfectly fine, but in large applications having about thousands of files in a single folder hampers performance. Hawkeye aims to solve this problem. Hawkeye retrieves the uploaded file, creates an unique id and hashes the id with md5. The hashed file is used to create the nested folder structure for the file.

    md5 hash of uploaded file => c4ca4238a0b923820dcc509a6f75849b.jpg

The above hashed name will be used to create a directory as

    c4c/a42/38a/0b9/238/20d/cc5/09a/6f7/584/c4ca4238a0b923820dcc509a6f75849b.jpg

## Installation

In order to install Hawkeye, just add 

    "viraj/hawkeye": "1.0.*"

to your composer.json. Then run `composer install` or `composer update`.

Then in your `config/app.php` add 

    ''Viraj\Hawkeye\HawkeyeServiceProvider'',
    
in the providers array and

    ''Hawkeye' 	=> 'Viraj\Hawkeye\HawkeyeFacade','
    
to the `aliases` array.
