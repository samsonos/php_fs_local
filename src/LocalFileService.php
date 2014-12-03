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
class LocalFileService extends AbstractFileService
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
        // Cut base dir name
        if (__SAMSON_BASE__ != '/') {
            $filename = str_replace(__SAMSON_BASE__, '', $filename);
        }
        // Cut first back slash to retrieve relative path
        if ($filename[0] === '/') {
            $filename = substr($filename, 1);
        }
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
     * Delete file from current file system
     * @param $filename string File for deleting
     * @return mixed
     */
    public function delete($filename)
    {
        unlink($filename);
    }

    /**
     * Get file extension in current file system
     * @param $filePath string Path
     * @return string|bool false if extension not found, otherwise file extension
     */
    public function extension($filePath)
    {
        return pathinfo($filePath, PATHINFO_EXTENSION);
    }

    /**
     * Get recursive $path listing collection
     * @param string $path Path for listing contents
     * @param array $extensions Collection of file extensions to filter
     * @param int $maxLevel Maximum nesting level
     * @param int $level Current nesting level of recursion
     * @param array $restrict Collection of restricted paths
     * @return array $path recursive directory listing
     */
    public function dir(
        $path,
        $extensions = null,
        $maxLevel = null,
        $level = 0,
        $restrict = array('.git', '.svn', '.hg', '.settings')
    ) {
        return array();
    }
}
