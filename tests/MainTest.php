<?php
namespace tests;

use samson\fs\FileService;
use samson\fs\LocalFileService;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>
 * on 04.08.14 at 16:42
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /** @var \samson\fs\FileService Pointer to file service */
    public $fileService;

    /** Test reading */
    public function testRead()
    {
        // Create instance
        $this->fileService = new LocalFileService(__DIR__.'../');

        // Read current file data
        $data = $this->fileService->read(__FILE__);

        // Compare current file with data readed
        $this->assertStringEqualsFile(__FILE__, $data, 'File service read failed');
    }

    /** Test file service writing and reading */
    public function testWriteRead()
    {
        // Get instance using services factory as error will signal other way
        $this->fileService = \samson\core\Service::getInstance('samson\fs\LocalFileService');

        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Write data to temporary file
        $this->fileService->write('123', $path);

        // Read data from file
        $data = $this->fileService->read($path);

        // Perform test
        $this->assertEquals('123', $data, 'File service writing failed');
    }

    /** Test file service deleting */
    public function testDelete()
    {
        // Get instance using services factory as error will signal other way
        $this->fileService = \samson\core\Service::getInstance('samson\fs\LocalFileService');

        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Delete temporary file
        $this->fileService->delete($path);

        // Perform test
        $this->assertFileNotExists($path, 'File service deleting failed');
    }

    /** Test file service existing */
    public function testExists()
    {
        // Get instance using services factory as error will signal other way
        $this->fileService = \samson\core\Service::getInstance('samson\fs\LocalFileService');

        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Write data to temporary file
        $exists = $this->fileService->exists($path);

        // Perform test
        $this->assertEquals(true, $exists, 'File service exists failed');
    }

    /** Test file service moving */
    public function testMove()
    {
        // Get instance using services factory as error will signal other way
        $this->fileService = \samson\core\Service::getInstance('samson\fs\LocalFileService');

        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Create test dir
        $testDir = sys_get_temp_dir().'/testDir/';
        mkdir($testDir, 0777);

        // Move file to a new dir
        $newPath = $this->fileService->move($path, basename($path), $testDir);

        // Perform test
        $this->assertFileExists($newPath, 'File service move failed - Moved file not found');
        $this->assertFileNotExists($path, 'File service move failed - Original file is not deleted');
    }
}
