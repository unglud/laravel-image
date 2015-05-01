<?php
/**
 * Created by Alexander 'unglued' Matrosov.
 * Company: Apus Agency
 * Site: http://www.apus.ag
 * E-mail: alex@apus.ag
 * Date: 01/05/15
 * Copyright (c) 2006-2015 Apus Agency
 */

namespace Unglued\LavaImage;

use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;

class LavaImage {

    /**
     * @var ImageManager
     */
    private $imageManager;
    private $path;
    private $pathUrl;
    private $hash;
    private $depth;
    private $len;
    private $type;

    public function __construct(ImageManager $imageManager){

        $this->imageManager = $imageManager;

        $this->pathUrl = '/uploads/';
        $this->path = public_path() . $this->pathUrl;

        $this->depth = app('config')->get('lavaimage.depth', 2);
        $this->len = app('config')->get('lavaimage.len', 1);
    }

    public function save($data, $size = []){
        try {
            $img = $this->imageManager->make($data);
        } catch(NotReadableException $e) {
            $img = $this->imageManager->make(public_path() . '/images/noimage.jpg');
        }

        switch($img->mime()) {
            case 'image/png':
                $this->type = 'png';
                break;

            case 'image/gif':
                $this->type = 'gif';
                break;

            default:
                $this->type = 'jpg';
        }

        if(!empty($size))
            $img->fit($size[0], $size[1])->save($this->generatePath());
        else
            $img->save($this->generatePath());

        return $this->hash;
    }

    protected function generatePath(){
        while(true){
            $fileName = hash('crc32', md5(rand()));

            $this->hash = $fileName;

            $dir = $this->resolvePath($fileName);

            if(!is_dir($this->path . $dir))
                mkdir($this->path . $dir, 0775, true);

            $file = $this->path . $dir . $fileName . '.' . $this->type;
            if(file_exists($file))
                continue;

            return $file;
        }
        return false;
    }

    protected function resolvePath($hash){
        $dir = '';
        for($i = 0, $j = 0; $i < $this->depth; $i++, $j += $this->len){
            $dir .= substr($hash, $j, $this->len) . '/';
        }

        return $dir;
    }

    public function getImageCode(){
        return $this->hash;
    }

    public function getImage($hash, $server = false){
        $dir = $this->resolvePath($hash);

        $imgPath = glob(public_path() . '/uploads/' . $dir . $hash . '.*');
        if(!isset($imgPath[0]))
            return false;

        if($server)
            return $imgPath[0];

        return url(str_replace(public_path(), '', $imgPath[0]));

    }


}
