<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 17.11.2014
 * Time: 14:12
 */

namespace samson\fs;

use samson\core\CompressableService;

/**
 * Local file system adapter implementation
 * @package samson\upload
 */
class LocalFileService extends CompressableService implements IFileSystem
{
    /** @var string Identifier */
    protected $id = 'fs_local';

    /**
     * Write data to a specific relative location
     *
     * @param mixed $data Data to be written
     * @param string $filename File name
     * @param string $uploadDir Relative file path
     * @return string|boolean Relative path to created file, false if there were errors
     */
    public function write($data, $filename = '', $uploadDir = '')
    {
        // Build path to writing file
        $path = $uploadDir.'/'.$filename;

        // Put file and return true if at least one byte is written
        if (file_put_contents($path, $data) !== false) {
            return $uploadDir.'/';
        } else { // We have failed my lord..
            return false;
        }
    }

    /**
     * Check existing current file in current file system
     * @param $filename string Filename
     * @return boolean File exists or not
     */
    public function exists($filename)
    {
        return file_exists($filename);
    }

    /**
     * Read the file from current file system
     * @param $filePath string Path to file
     * @param $filename string
     * @return mixed
     */
    public function read($fullname, $filename = null)
    {
        return file_get_contents($fullname);
    }

    /**
     * Move file to selected location
     * @param $filePath string Path to file
     * @param $filename string
     * @param $uploadDir string
     * @return bool|string False if failed otherwise path to moved file
     */
    public function move($filePath, $filename, $uploadDir)
    {
        // Build new path
        $newPath = $uploadDir.'/'.$filename;

        // If this file is not already exists
        if ($filePath != $newPath) {

            // Copy file to a new location
            copy($filePath, $newPath);

            // Remove current file
            $this->delete($filePath);

            return $newPath;
        }

        return false;
    }

    /**
     * Delete file from current file system
     * @param $filename string File for deleting
     * @return mixed
     */
    public function delete($filename)
    {
        unlink($filename);
    }
}
