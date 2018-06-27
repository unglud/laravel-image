<?php
declare(strict_types=1);
/**
 * Created by Alexander 'unglued' Matrosov.
 * Date: 01/05/15
 */

namespace Unglued\LavaImage;

use Illuminate\Support\Facades\File;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;

/**
 * Class LavaImage
 * @package Unglued\LavaImage
 */
class LavaImage
{

    /**
     * @var ImageManager
     */
    protected $imageManager;
    protected $path;
    protected $pathUrl;
    protected $depth;
    protected $len;
    protected $type;
    private $hash;

    /**
     * LavaImage constructor.
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {

        $this->imageManager = $imageManager;

        $this->pathUrl = '/uploads/';
        $this->path = public_path() . $this->pathUrl;

        $this->depth = app('config')->get('lavaimage.depth', 2);
        $this->len = app('config')->get('lavaimage.len', 1);
    }

    /**
     * @param $data
     * @param array $size
     * @return mixed
     */
    public function save($data, $size = [])
    {
        try {
            $img = $this->imageManager->make($data);
        } catch (NotReadableException $e) {
            $img = $this->imageManager->make(public_path() . '/images/noimage.jpg');
        }

        switch ($img->mime()) {
            case 'image/png':
                $this->type = 'png';
                break;

            case 'image/gif':
                $this->type = 'gif';
                break;

            default:
                $this->type = 'jpg';
        }

        if (!empty($size)) {
            $img->fit($size[0], $size[1])->save($this->generatePath());
        } else {
            $img->save($this->generatePath());
        }

        return $this->hash;
    }

    public function getImageCode()
    {
        return $this->hash;
    }

    /**
     * @param $hash
     * @param bool $server
     * @return bool|string
     */
    public function getImage($hash, $server = false)
    {
        $dir = $this->resolvePath($hash);

        $imgPath = File::glob(public_path() . '/uploads/' . $dir . $hash . '.*');
        if (!isset($imgPath[0])) {
            return false;
        }

        if ($server) {
            return $imgPath[0];
        }

        return url(str_replace(public_path(), '', $imgPath[0]));

    }

    /**
     * @return bool|string
     */
    protected function generatePath()
    {
        while (true) {
            $fileName = $this->getHash();

            $this->hash = $fileName;

            $dir = $this->resolvePath($fileName);
            File::makeDirectory($this->path . $dir, 0755, true);

            $file = $this->path . $dir . $fileName . '.' . $this->type;
            if (file_exists($file)) {
                continue;
            }

            return $file;
        }

        return false;
    }

    /**
     * @param $hash
     * @return string
     */
    protected function resolvePath($hash)
    {
        $dir = '';
        for ($i = 0, $j = 0; $i < $this->depth; $i++, $j += $this->len) {
            $dir .= substr($hash, $j, $this->len) . '/';
        }

        return $dir;
    }

    /**
     * @return string
     */
    protected function getHash()
    {
        return hash('crc32', md5((string) mt_rand()));
    }
}
