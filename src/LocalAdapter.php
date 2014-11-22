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
class LocalAdapter implements IAdapter
{
    public function __construct()
    {
        $uploadDir = m('samson_fs_local')->adapterParameters;

        // If upload path does not exists - create it
        if (isset($uploadDir) && !file_exists($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
    }

    /**
     * @param mixed $data
     * @param string $filename
     * @param string $uploadDir
     * @see \samson\fs\iAdapter::write()
     * @return bool|string
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

    public function exists($filename)
    {
        return file_exists($filename);
    }

    public function read($fullname, $filename)
    {
        return $fullname;
    }

    public function move($filePath, $filename, $uploadDir)
    {
        if ($filePath != $uploadDir.'/'.$filename) {
            copy($filePath, $uploadDir.'/'.$filename);
            $this->delete($filePath);
        }
    }

    public function delete($filename)
    {
        unlink($filename);
    }
}
