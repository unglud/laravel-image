<?php namespace Unglued\LavaImage\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * Created by Alexander 'unglued' Matrosov.
 * Company: Apus Agency
 * Site: http://www.apus.ag
 * E-mail: alex@apus.ag
 * Date: 01/05/15
 * Copyright (c) 2006-2015 Apus Agency
 */

class LavaImage  extends Facade{
    protected static function getFacadeAccessor() { return 'lavaimage'; }
}