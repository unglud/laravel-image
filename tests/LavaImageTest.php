<?php

/**
 * Created by Alexander 'unglued' Matrosov.
 * Company: Apus Agency
 * Site: http://www.apus.ag
 * E-mail: alex@apus.ag
 * Date: 03/05/15
 * Copyright (c) 2006-2015 Apus Agency
 */
namespace Unglued\LavaImage;


function public_path(){
    return '/some/path/public';
}

function app(){
    $mock = \Mockery::mock('AppObject');
    $mock->shouldReceive('get')->with('lavaimage.depth', 2)->andReturn(2);
    $mock->shouldReceive('get')->with('lavaimage.len', 1)->andReturn(1);
    return $mock;
}

function hash($alg, $data){
    return '796aef28';
}

function mkdir($path){ }

function glob($path){
    return [str_replace('*', 'jpg', $path)];
}

function url($path){
    return 'http://example.com' . $path;
}


class LavaImageTest extends \PHPUnit_Framework_TestCase {

    private $manager;

    function setUP(){
        $this->manager = \Mockery::mock('Intervention\Image\ImageManager');
    }

    function test_constructor(){
        $img = $this->getImg();

        $this->assertObjectHasAttribute('imageManager', $img);

        $this->assertObjectHasAttribute('pathUrl', $img);
        $this->assertEquals('/uploads/', \PHPUnit_Framework_Assert::readAttribute($img, 'pathUrl'));

        $this->assertObjectHasAttribute('path', $img);
        $this->assertEquals('/some/path/public/uploads/', \PHPUnit_Framework_Assert::readAttribute($img, 'path'));

        $this->assertObjectHasAttribute('depth', $img);
        $this->assertEquals(2, \PHPUnit_Framework_Assert::readAttribute($img, 'depth'));

        $this->assertObjectHasAttribute('len', $img);
        $this->assertEquals(1, \PHPUnit_Framework_Assert::readAttribute($img, 'len'));
    }

    function getImg(){
        return new LavaImage($this->manager);
    }

    function test_it_must_generate_path(){
        $img = $this->getImg();

        $managerImage = \Mockery::mock('ManagerImage');
        $managerImage->shouldReceive('mime')->andReturn('image/jpg');
        $managerImage->shouldReceive('save');

        $this->manager->shouldReceive('make')->with('/someimage.jpg')->andReturn($managerImage);

        $hash = $img->save('/someimage.jpg');

        $this->assertEquals('796aef28', $hash);

        $this->assertEquals('796aef28', $img->getImageCode());

        $this->assertObjectHasAttribute('type', $img);
        $this->assertEquals('jpg', \PHPUnit_Framework_Assert::readAttribute($img, 'type'));

    }

    function test_it_must_return_http_path_by_code(){
        $img = $this->getImg();

        $path = $img->getImage('796aef28');

        $this->assertEquals('http://example.com/uploads/7/9/796aef28.jpg', $path);
    }

    function test_it_must_return_absolute_path_by_code(){
        $img = $this->getImg();

        $path = $img->getImage('796aef28', true);

        $this->assertEquals('/some/path/public/uploads/7/9/796aef28.jpg', $path);
    }

    protected function tearDown(){
        \Mockery::close();
    }



}
