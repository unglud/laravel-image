<?php
declare(strict_types=1);
function public_path()
{
    return '/some/path/public';
}

function app()
{
    $mock = \Mockery::mock('AppObject');
    $mock->shouldReceive('get')->with('lavaimage.depth', 2)->andReturn(2);
    $mock->shouldReceive('get')->with('lavaimage.len', 1)->andReturn(1);

    return $mock;
}

function url($path)
{
    return 'http://example.com' . $path;
}
