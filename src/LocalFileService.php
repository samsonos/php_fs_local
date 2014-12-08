<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 17.11.2014
 * Time: 14:12
 */

namespace samson\fs;

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
     * @return string|false Relative path to created file, false if there were errors
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
     * @return string
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
     * Define if $filePath is directory
     * @param string $filePath Path
     * @return boolean Is $path a directory or not
     */
    public function isDir($filePath)
    {
        return is_dir($filePath);
    }

    /**
     * Get recursive $path listing collection
     * @param string $path Path for listing contents
     * @param array $extensions Collection of file extensions to filter
     * @param int $maxLevel Maximum nesting level
     * @param int $level Current nesting level of recursion
     * @param array $restrict Collection of restricted paths
     * @param array     $result   Collection of restricted paths
     * @return array $path recursive directory listing
     */
    public function dir(
        $path,
        $extensions = null,
        $maxLevel = null,
        $level = 0,
        $restrict = array('.git', '.svn', '.hg', '.settings'),
        & $result = array()
    ) {
        // If we have nesting level limit
        if (isset($maxLevel) && $level > $maxLevel) {
            // Exit recursion
            return $result;
        }

        // Check if path does not exists or if we cannot read a path
        $handle = opendir($path);
        if (!file_exists($path) || $handle === false) {
            return $result;
        }

        // If type-filter is passed make it array anyway
        $extensions = isset($extensions) && !is_array($extensions) ? array($extensions) : $extensions;

        // Fastest reading method
        while (false !== ($entry = readdir($handle))) {
            // Ignore root paths
            if ($entry == '..' || $entry == '.') {
                continue;
            }

            // Build full REAL path to entry
            $fullPath = realpath($path . '/' . $entry);

            // If this is a file
            if (!$this->isDir($fullPath)) {
                // Check file type if type filter is passed
                if (!isset($extensions) || in_array(pathinfo($fullPath, PATHINFO_EXTENSION), $extensions)) {
                    $result[] = $fullPath;
                }
            } else { // This is a folder
                // Check if this full folder path is not ignored
                if (in_array($fullPath, $restrict) === false) {
                    // Go deeper in recursion
                    $this->dir($fullPath, $extensions, $maxLevel, ++$level, $restrict, $result);
                }
            }
        }

        // Close reading handle
        closedir($handle);

        // Sort results
        sort($result);

        return $result;
    }
}
