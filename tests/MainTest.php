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
    /** @var \samson\fs\LocalFileService Pointer to file service */
    public $fileService;

    /** Tests init */
    public function setUp()
    {
        // Get instance using services factory as error will signal other way
        $this->fileService = \samson\core\Service::getInstance('samson\fs\LocalFileService');
    }

    /** Test reading */
    public function testRead()
    {
        // Read current file data
        $data = $this->fileService->read(__FILE__);

        // Compare current file with data readed
        $this->assertStringEqualsFile(__FILE__, $data, 'File service read failed');
    }

    /** Test file service writing */
    public function testWrite()
    {
        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Create test dir
        $testDir = sys_get_temp_dir().'/testDir/';
        mkdir($testDir, 0777);

        // Write data to temporary file
        $writtenFile = $this->fileService->write('123', basename($path), $testDir);

        // Perform test
        $this->assertStringEqualsFile($writtenFile, '123');
    }

    /** Test file service writing failed */
    public function testFailWrite()
    {
        // Create path to null file
        $path = __DIR__.'/test/test.txt';

        // Write data to temporary file
        $writtenFile = $this->fileService->write('123', $path);

        // Perform test
        $this->assertEquals(false, $writtenFile, '123');
    }

    /** Test file service deleting */
    public function testDelete()
    {
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

    /** Test file service moving to existing file */
    public function testMoveToExisting()
    {
        // Create temporary file
        $path = tempnam(sys_get_temp_dir(), 'test');

        // Move file to a new dir
        $newPath = $this->fileService->move($path, basename($path), dirname($path));

        // Perform test
        $this->assertEquals(false, $newPath, 'File service move failed - Moved file not found');
    }

    /** Test file service extension method */
    public function testExtension()
    {
        // Move file to a new dir
        $extension = $this->fileService->extension(__FILE__);

        // Perform test
        $this->assertEquals('php', $extension, 'File service extension method failed - Extension is not correct');
    }
}
