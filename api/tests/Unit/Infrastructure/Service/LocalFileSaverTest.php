<?php

namespace Test\Unit\Infrastructure\Service;

use App\Infrastructure\Service\LocalFileSaver;
use PHPUnit\Framework\TestCase;

class LocalFileSaverTest extends TestCase
{
    const TEST_BASE_PATH = 'tmp/';

    public function test_saves_file_with_contents(): void
    {
        $localFileSaver = new LocalFileSaver(self::TEST_BASE_PATH);
        
        $file_route = $localFileSaver("hello", "hello.txt");     
        
        $this->assertFileExists($file_route);
        $file_contents = file_get_contents($file_route);
        $this->assertEquals($file_contents, "hello");
    }
    
    public function tearDown(): void
    {
        unlink(self::TEST_BASE_PATH . "hello.txt");
    }
}