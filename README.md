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

## Create Migration

Now generate the Hawkeye migration:

`php artisan hawkeye:migration`

It will generate the `<timestamp>_hawkeye_setup_tables.php` migration. You may now run it with the artisan migrate command:

`php artisan migrate`
After the migration, one new table will be present:

`hawkeye` — stores file records and its meta data

## Configuration

You need to publish the configuration for this package to further customize the storage path of files. 
Just use `php artisan vendor:publish` and a `hawkeye.php` file will be created in your `app/config` directory.

### Configuration file

A typical `Hawkeye` configuration file should look like as follows:

```php
<?php

return [
    'hawkeye_base_path' => 'images/',
    'images' => [
        'banner' => '1200x800',
        'thumbnail' => '300x200',
        'large' => '600x500',
    ],
];
```

### Base directory configuration

Setup the base directory in `config/hawkeye.php` where your files will be stored.

    'hawkeye_base_path' => 'path/to/directory',

For e.g. if you want to have your files in `public/images` directory then,

    'hawkeye_base_path' => 'images/',
    
#### Note: Don't forget the forward slash after `images`
    
or if, you want to store them outside public directory, let's suppose in `storage/images`, then give the path to directory

    'hawkeye_base_path' => '../storage/images/',

Hawkeye will store the files in appropriate directory.

### Image Resizing configuration

You can configure Hawkeye to resize uploaded images in various dimensions you require. It comes with some default image dimensions for resizing images according to industry standards, but you are free to configure according to your needs. If you need some different values, edit the values accordingly in  `config/hawkeye.php` :

    'images' => [
        'banner' => '1200x800',
        'thumbnail' => '300x200',
        'large' => '600x500',
    ],
    
If you want to stick with above configuration and also add some custom configuration for your app, you can do that as well. Just add the needed dimension and name for your configuration.

    'images' => [
        'banner' => '1200x800',
        'thumbnail' => '300x200',
        'large' => '600x500',
        'your-configuration' => '800x600',
    ],

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

#### Uploading and resizing files

In your controller, you can write

```php
<?php

use Viraj\Hawkeye\HawkeyeFacade as Hawkeye;

public function uploadFile()
{
    $files = Hawkeye::upload('file_upload')->resize()->get();
    var_dump($files);
}
```

The above code will upload, resize (to all the image types specified in configuration) and give you a nested array of file names (md5 hashed names):

```php
array (size=2)
  'list' => 
    array (size=8)
      0 => string 'd67d8ab4f4c10bf22aa353e27879133c.png' (length=36)
      1 => string 'd645920e395fedad7bbbed0eca3fe2e0.png' (length=36)
      2 => string 'd67d8ab4f4c10bf22aa353e27879133c_1200_800.png' (length=45)
      3 => string 'd67d8ab4f4c10bf22aa353e27879133c_300_200.png' (length=44)
      4 => string 'd67d8ab4f4c10bf22aa353e27879133c_600_500.png' (length=44)
      5 => string 'd645920e395fedad7bbbed0eca3fe2e0_1200_800.png' (length=45)
      6 => string 'd645920e395fedad7bbbed0eca3fe2e0_300_200.png' (length=44)
      7 => string 'd645920e395fedad7bbbed0eca3fe2e0_600_500.png' (length=44)
  'separated' => 
    array (size=2)
      0 => 
        array (size=4)
          'original' => string 'd67d8ab4f4c10bf22aa353e27879133c.png' (length=36)
          'banner' => string 'd67d8ab4f4c10bf22aa353e27879133c_1200_800.png' (length=45)
          'thumbnail' => string 'd67d8ab4f4c10bf22aa353e27879133c_300_200.png' (length=44)
          'large' => string 'd67d8ab4f4c10bf22aa353e27879133c_600_500.png' (length=44)
      1 => 
        array (size=4)
          'original' => string 'd645920e395fedad7bbbed0eca3fe2e0.png' (length=36)
          'banner' => string 'd645920e395fedad7bbbed0eca3fe2e0_1200_800.png' (length=45)
          'thumbnail' => string 'd645920e395fedad7bbbed0eca3fe2e0_300_200.png' (length=44)
          'large' => string 'd645920e395fedad7bbbed0eca3fe2e0_600_500.png' (length=44)
```

The above response has 2 parameters:

`list` - It has a list of all files that have been uploaded and resized.

`separated` - It has a segregated/separated list of all uploaded files and resized images as well, if any!

## getList()

If you only want the list of all uploaded and resized files without the data about image type, you can use `getList()` method as follows:

```php
<?php

use Viraj\Hawkeye\HawkeyeFacade as Hawkeye;

public function uploadFile()
{
    $files = Hawkeye::upload('file_upload')->resize()->getList();
    var_dump($files);
}
```

The 

```php

array (size=8)
      0 => string 'd67d8ab4f4c10bf22aa353e27879133c.png' (length=36)
      1 => string 'd645920e395fedad7bbbed0eca3fe2e0.png' (length=36)
      2 => string 'd67d8ab4f4c10bf22aa353e27879133c_1200_800.png' (length=45)
      3 => string 'd67d8ab4f4c10bf22aa353e27879133c_300_200.png' (length=44)
      4 => string 'd67d8ab4f4c10bf22aa353e27879133c_600_500.png' (length=44)
      5 => string 'd645920e395fedad7bbbed0eca3fe2e0_1200_800.png' (length=45)
      6 => string 'd645920e395fedad7bbbed0eca3fe2e0_300_200.png' (length=44)
      7 => string 'd645920e395fedad7bbbed0eca3fe2e0_600_500.png' (length=44)

```

## Resizing for specific image types

Sometimes, you don't want to resize your images in all the types specified in configuration. Don't worry, Hawkeye has a provision for that as well. Specify the name of `image types` you want the uploaded images to be resized into in `resize()` method and Hawkeye will resize into those types only.

```php
<?php

use Viraj\Hawkeye\HawkeyeFacade as Hawkeye;

public function uploadFile()
{
    $files = Hawkeye::upload('file_upload')->resize(['banner', 'large'])->get();
    var_dump($files);
}
```

The above code will just resize your images in only 2 types - `banner` and `large`. The response will be:

```php
array (size=2)
  'list' => 
    array (size=6)
      0 => string 'a5bfc9e07964f8dddeb95fc584cd965d.png' (length=36)
      1 => string 'a5771bce93e200c36f7cd9dfd0e5deaa.png' (length=36)
      2 => string 'a5bfc9e07964f8dddeb95fc584cd965d_1200_800.png' (length=45)
      3 => string 'a5bfc9e07964f8dddeb95fc584cd965d_600_500.png' (length=44)
      4 => string 'a5771bce93e200c36f7cd9dfd0e5deaa_1200_800.png' (length=45)
      5 => string 'a5771bce93e200c36f7cd9dfd0e5deaa_600_500.png' (length=44)
  'separated' => 
    array (size=2)
      0 => 
        array (size=3)
          'original' => string 'a5bfc9e07964f8dddeb95fc584cd965d.png' (length=36)
          'banner' => string 'a5bfc9e07964f8dddeb95fc584cd965d_1200_800.png' (length=45)
          'large' => string 'a5bfc9e07964f8dddeb95fc584cd965d_600_500.png' (length=44)
      1 => 
        array (size=3)
          'original' => string 'a5771bce93e200c36f7cd9dfd0e5deaa.png' (length=36)
          'banner' => string 'a5771bce93e200c36f7cd9dfd0e5deaa_1200_800.png' (length=45)
          'large' => string 'a5771bce93e200c36f7cd9dfd0e5deaa_600_500.png' (length=44)
```

## generateFullImagePathFor()

This function returns the full image path for a particular file name (hashed) with extension.

### Example 1 (Resized image - It has dimesnions appended to the name)

```
    Hawkeye::generateFullImagePathFor('45c48cce2e2d7fbdea1afc51c7c6ad26_1200_200.jpg')
    
    Output:
    "images/45c/48c/ce2/e2d/7fb/dea/1af/c51/c7c/6ad/45c48cce2e2d7fbdea1afc51c7c6ad26_1200_200.jpg"
```

### Example 2 (Original image - It has original name)

```
    Hawkeye::generateFullImagePathFor('45c48cce2e2d7fbdea1afc51c7c6ad26.jpg')
    
    Output:
    "images/45c/48c/ce2/e2d/7fb/dea/1af/c51/c7c/6ad/45c48cce2e2d7fbdea1afc51c7c6ad26.jpg"
```
## License

Hawkeye is free software distributed under the terms of the MIT license
