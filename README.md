# laravel-image
Basic image saver for Laravel 5.

If you need save uploaded image to some place and to a database, the best way to do that is to save an image in public folder with the unique name and then save that name to the database.

So this package will do it for you.

[![GitHub release](https://img.shields.io/github/release/unglud/laravel-image.svg)](https://github.com/unglud/laravel-image/releases)
[![Build Status](https://travis-ci.org/unglud/laravel-image.svg?branch=master)](https://travis-ci.org/unglud/laravel-image)
[![License](https://img.shields.io/packagist/l/unglud/laravel-image.svg)](https://github.com/unglud/laravel-image/blob/master/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/unglud/laravel-image.svg)](https://packagist.org/packages/unglud/laravel-image)

## Installation

Laravel Image is distributed as a composer package:
```
composer require unglud/laravel-image
```

Then you have to run `composer update` to install the package and it dependencies if needed. Once this is completed, you have to add the service provider to the providers array in `config/app.php`:

```
'Unglued\LavaImage\LavaImageServiceProvider',
```

Run `php artisan vendor:publish` to publish this package configuration. Afterward, you can edit the file `config/lavaimage.php`.

## Saving Image

Use `LavaImage::save()` to save image to `public/uploads`, this method generate unique 8 char filename and put file to [deep tree folder structure](http://serverfault.com/a/95454).

```php
use Unglued\LavaImage\Facades\LavaImage;

$fileHash = LavaImage::save('http://lorempixel.com/300/300/');

// $fileHash == 203bad62
// and file stored in /public/uploads/2/0/203bad62.jpg

$myModel = new MyModel();
$myModel->image = $fileHash;
$myModel->save();
```

### File structure
You can specify another folder structure, like any depth or folder name length

```
for 203bad62 it can be
/2/0/203bad62.jpg
/2/0/3/b/203bad62.jpg
/20/203bad62.jpg
/20/3b/203bad62.jpg
etc....
```

### Crop and save
You can specify size as second argument for center fit cropping

```php
LavaImage::save('http://lorempixel.com/300/300/', [100,100]);
```

As the first argument, you can pass any data, what [Intervention/image make method](http://image.intervention.io/api/make) support

```php
// save image from file
LavaImage::save('public/foo.jpg');

// or save image from binary data
LavaImage::save(file_get_contents('public/foo.jpg'));

// save image from gd resource
LavaImage::save(imagecreatefromjpeg('public/foo.jpg'));

// save image directly from an url
LavaImage::save('http://example.com/example.jpg');

// save image directly from Laravel file upload
LavaImage::save(Input::file('photo'));
```

Any time after saving you can retrieve generated hash by `LavaImage::getImageCode()`

## Getting Image

Then you need to get an image, use hash you know

```php
// for http path (http://example.com/uploads/2/0/203bad62.jpg)
$hash = '203bad62'
LavaImage::getImage($hash);

// for absolute path (/home/var/laravel/public/uploads/2/0/203bad62.jpg)
LavaImage::getImage(LavaImage::getImageCode(), true)
```


## License

Laravel Image is released under the MIT Licence. See the bundled LICENSE file for details.
