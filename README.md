# Hawkeye
A Laravel package for uploading files automatically in a nested md5 hash naming folder structure. A mini CDN for optimized file storage for Laravel.

## Objective
Normally, we upload a file into a specific folder. In small applications, this is perfectly fine, but in large applications having about thousands of files in a single folder hampers performance. Hawkeye aims to solve this problem. Hawkeye retrieves the uploaded file, creates an unique id and hashes the id with md5. The hashed file is used to create the nested folder structure for the file.

    md5 hash of uploaded file => c4ca4238a0b923820dcc509a6f75849b.jpg

The above hashed name will be used to create a directory as

    c4c/a42/38a/0b9/238/20d/cc5/09a/6f7/584/c4ca4238a0b923820dcc509a6f75849b.jpg

## Installation

In order to install Hawkeye, just add 

    "viraj/hawkeye": "dev-master"

to your composer.json. Then run `composer install` or `composer update`.

Then in your `config/app.php` add 

    'Viraj\Hawkeye\HawkeyeServiceProvider',
    
in the providers array and

    'Hawkeye' 	=> 'Viraj\Hawkeye\HawkeyeFacade',
    
to the `aliases` array.

## Configuration

You need to publish the configuration for this package to further customize the storage path of files. 
Just use `php artisan vendor:publish` and a `hawkeye.php` file will be created in your `app/config` directory.

Setup the base directory in `config/hawkeye.php` where your files will be stored.

    'hawkeye_base_path' => 'path/to/directory',

For e.g. if you want to have your files in `public\images` directory then,

    'hawkeye_base_path' => 'images/',
    
or if, you want to store them outside public directory, let's suppose in `storage/images`, then give the path to directory

    'hawkeye_base_path' => '../storage/',

Hawkeye will store the files in appropriate directory.

## Create Migration

Now generate the Hawkeye migration:

`php artisan hawkeye:migration`

It will generate the `<timestamp>_hawkeye_setup_tables.php` migration. You may now run it with the artisan migrate command:

`php artisan migrate`
After the migration, one new table will be present:

`hawkeye` — stores file records and its meta data

## Upload Files using Hawkeye

### upload.blade.php

```html

<form open="hawkeye" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    /* You can have multiple file upload or single file. Hawkeye will handle it out of the box for you. */
    <input type="file" name="file_upload[]" multiple> OR <input type="file" name="file_upload">
    <input type="submit" value="Upload">
</form>

```

### FileController.php

In your controller, you can write

```php
<?php

use Viraj\Hawkeye\HawkeyeFacade as Hawkeye;

public function uploadFile()
{
    $files = Hawkeye::upload('file_upload');
    var_dump($files);
}
```

The `upload` method will give you an array of file names (md5 hashed names) for multiple files. 

```php
array (size=2)
  0 => string 'c9f0f895fb98ab9159f51fd0297e236d.docx' (length=37)
  1 => string '45c48cce2e2d7fbdea1afc51c7c6ad26.pdf' (length=36)
```

And it will give a string for single file.

    c9f0f895fb98ab9159f51fd0297e236d.docx

## License

Hawkeye is free software distributed under the terms of the MIT license
