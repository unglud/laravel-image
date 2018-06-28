<?php
declare(strict_types=1);

namespace Unglued\LavaImage\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Created by Alexander 'unglued' Matrosov.
 * Date: 01/05/15
 * @method static save($data, array $size = [])
 * @method static getImage($hash, $server = false)
 */
class LavaImage extends Facade{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(): string { return 'lavaimage'; }
}
