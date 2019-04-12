<?php
use PHPUnit\Framework\TestCase;
use Franky\Filesystem\File;

class FileTest extends TestCase
{
    public function testGet()
    {
          $File = new File();
          $this->assertSame($File->PermisosArchivo('./'),'drwxr-xr-x');
          return $data;
    }
}
