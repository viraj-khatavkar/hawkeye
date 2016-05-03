<?php namespace Viraj\Hawkeye;

use Intervention\Image\ImageManagerStatic as Image;
use Viraj\Hawkeye\Exceptions\InvalidFileException;

class Hawkeye
{
    use HawkeyeTrait;

    private $files;
    private $uploadedFiles;

    public function request($filename)
    {
        if ($filename != '') {
            return new UploadedFile($filename, new FileRepository());
        }

        throw new InvalidFileException("File is corrupted or Invalid");
    }

    /**
     * @param $filename
     * @return $this
     * @throws \Viraj\Hawkeye\Exceptions\InvalidUploadedFileException
     */
    public function upload($filename)
    {
        $fileObjects = $this->normalize($_FILES[$filename])
                            ->createFileObjects();


        $count = 0;

        foreach ($fileObjects as $fileObject) {
            $upload = new Upload($fileObject['uploaded'], $fileObject['original'], new FileRepository());

            $hashedName = $upload->upload();

            $this->uploadedFiles['list'][$count] = $hashedName;
            $this->uploadedFiles['separated'][]['original'] = $hashedName;

            $count++;
        }

        return $this;
    }

    /**
     * Returns array of file objects.
     *
     * File objects are created using SplFileInfo Class.
     *
     * @return array
     */
    private function createFileObjects()
    {
        foreach ($this->files as $file) {
            $fileObjects[] = [
                'original' => new \SplFileInfo($file['name']),
                'uploaded' => new \SplFileInfo($file['tmp_name']),
            ];
        }

        return $fileObjects;
    }

    /**
     * Normalized the $_FILES array.
     *
     * @param $argument
     * @return $this
     */
    private function normalize($argument)
    {
        if (is_array($argument['name'])) {
            foreach ($argument as $file_parameter => $value_array) {
                foreach ($value_array as $key => $value) {
                    $this->files[$key][$file_parameter] = $value;
                }
            }
        } else {
            foreach ($argument as $key => $value) {
                $this->files[0][$key] = $value;
            }
        }

        return $this;
    }

    public function resize($options = [])
    {
        /**
         * Looping through all the image types of configuration and setting the image types as specified in options
         * array. If options array is empty, then all the image types from configuration will be considered. These
         * image types array will be then used to decide the resize dimensions for an uploaded image.
         */
        if (count($options) > 0) {
            foreach (config('hawkeye.images') as $key => $value) {
                if (in_array($key, $options)) {
                    $images_types[$key] = $value;
                }
            }
        } else {
            $images_types = config('hawkeye.images');
        }

        $count = 0;

        //Looping through all the uploaded files to resize them
        foreach ($this->uploadedFiles['separated'] as $file) {

            //Retrieving file extension, hashed_name, directory path to create a new name for resized image.
            $file_meta = explode('.', $file['original']);
            $hash = $file_meta[0];
            $directoryPath = $this->generateDirectoryPathFromName($hash);
            $file_name = $directoryPath . '/' . $file['original'];

            //Looping through all the image types for which image needs to be resized.
            foreach ($images_types as $key => $value) {
                $dimensions = explode('x', $value);

                $scaled_image_name = $hash . '_' . $dimensions[0] . '_' . $dimensions[1] . '.' . $file_meta[1];
                $scaled_image_path = $directoryPath . '/' . $scaled_image_name;

                Image::make($file_name)
                     ->resize($dimensions[0], $dimensions[1])
                     ->save($scaled_image_path);

                $this->uploadedFiles['separated'][$count][$key] = $scaled_image_name;
                array_push($this->uploadedFiles['list'], $scaled_image_name);
            }
            $count++;
        }

        return $this;
    }
    
    public function fit($options = [])
    {
        /**
         * Looping through all the image types of configuration and setting the image types as specified in options
         * array. If options array is empty, then all the image types from configuration will be considered. These
         * image types array will be then used to decide the resize dimensions for an uploaded image.
         */
        if (count($options) > 0) {
            foreach (config('hawkeye.images') as $key => $value) {
                if (in_array($key, $options)) {
                    $images_types[$key] = $value;
                }
            }
        } else {
            $images_types = config('hawkeye.images');
        }

        $count = 0;

        //Looping through all the uploaded files to resize them
        foreach ($this->uploadedFiles['separated'] as $file) {

            //Retrieving file extension, hashed_name, directory path to create a new name for resized image.
            $file_meta = explode('.', $file['original']);
            $hash = $file_meta[0];
            $directoryPath = $this->generateDirectoryPathFromName($hash);
            $file_name = $directoryPath . '/' . $file['original'];

            //Looping through all the image types for which image needs to be resized.
            foreach ($images_types as $key => $value) {
                $dimensions = explode('x', $value);

                $scaled_image_name = $hash . '_' . $dimensions[0] . '_' . $dimensions[1] . '.' . $file_meta[1];
                $scaled_image_path = $directoryPath . '/' . $scaled_image_name;

                Image::make($file_name)
                     ->fit($dimensions[0], $dimensions[1])
                     ->save($scaled_image_path);

                $this->uploadedFiles['separated'][$count][$key] = $scaled_image_name;
                array_push($this->uploadedFiles['list'], $scaled_image_name);
            }
            $count++;
        }

        return $this;
    }

    public function get()
    {
        return $this->uploadedFiles;
    }

    public function getList()
    {
        return $this->uploadedFiles['list'];
    }

    public function getSeparated()
    {
        return $this->uploadedFiles['separated'];
    }
}
