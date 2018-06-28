<?php
declare(strict_types=1);

/**
 * Created by Alexander 'unglued' Matrosov.
 * Date: 03/05/15
 */

namespace Tests;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\TestCase;
use Unglued\LavaImage\LavaImage;

require_once __DIR__ . '/functions.php';

class LavaImageTest extends TestCase
{

    private $manager;

    function setUP(){
        $this->manager = \Mockery::mock('Intervention\Image\ImageManager');
    }

    function test_constructor(){
        $img = $this->getImg();

        $this->assertObjectHasAttribute('imageManager', $img);

        $this->assertObjectHasAttribute('pathUrl', $img);
        $this->assertAttributeContains('/uploads/', 'pathUrl', $img);

        $this->assertObjectHasAttribute('path', $img);
        $this->assertAttributeContains('/some/path/public/uploads/', 'path', $img);

        $this->assertObjectHasAttribute('depth', $img);
        $this->assertEquals(2, $this->readAttribute($img, 'depth'));

        $this->assertObjectHasAttribute('len', $img);
        $this->assertEquals(1, $this->readAttribute($img, 'len'));
    }

    function getImg(){
        return new LavaImageStub($this->manager);
    }

    function test_it_must_generate_path(){
        $img = $this->getImg();

        File::shouldReceive('makeDirectory');

        $managerImage = \Mockery::mock('ManagerImage');
        $managerImage->shouldReceive('mime')->andReturn('image/jpg');
        $managerImage->shouldReceive('save');

        $this->manager->shouldReceive('make')->with('/someimage.jpg')->andReturn($managerImage);

        $hash = $img->save('/someimage.jpg');

        $this->assertEquals('796aef28', $hash);

        $this->assertEquals('796aef28', $img->getImageCode());

        $this->assertObjectHasAttribute('type', $img);
        $this->assertAttributeContains('jpg', 'type', $img);

    }

    function test_it_must_return_http_path_by_code(){
        $img = $this->getImg();
        File::shouldReceive('glob')->andReturn(['/uploads/7/9/796aef28.jpg']);

        $path = $img->getImage('796aef28');

        $this->assertEquals('http://example.com/uploads/7/9/796aef28.jpg', $path);
    }

    function test_it_must_return_absolute_path_by_code(){
        $img = $this->getImg();
        File::shouldReceive('glob')->andReturn(['/some/path/public/uploads/7/9/796aef28.jpg']);
        $path = $img->getImage('796aef28', true);

        $this->assertEquals('/some/path/public/uploads/7/9/796aef28.jpg', $path);
    }

    protected function tearDown(){
        \Mockery::close();
        File::clearResolvedInstances();
    }

}

class LavaImageStub extends LavaImage
{
    protected function getHash(): string
    {
        return '796aef28';
    }

}
