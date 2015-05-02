# laravel-image
Basic image saver for Laravel 5.
If you need save uploaded image to some place and to database, best way to do it, save image in public folder with unique name and then save that name to database. So this package will do it for you.

## Installation

Laravel Image is distributed as a composer package. So you first have to add the package to your `composer.json` file:

```
"unglud/laravel-image": "~0.1"
```

Then you have to run `composer update` to install the package and it dependencies if needed. Once this is completed, you have to add the service provider to the providers array in `config/app.php`:

```
'Unglued\LavaImage\LavaImageServiceProvider',
```

Run php artisan vendor:publish to publish this package configuration. Afterwards you can edit the file `config/lavaimage.php`.

## Saving Image

Use `LavaImage::save()` to save image to `public/uploads`, this method generate unique 8 char filename and put file to deep tree folder structure.

```php
use Unglued\LavaImage\Facades\LavaImage;

$fileHash = LavaImage::save('http://lorempixel.com/300/300/');

// $fileHash == 203bad62
// and file stored in /public/uploads/2/0/203bad62.jpg

$myModel = new MyModel();
$myModel->image = $fileHash;
$myModel->save();
```

You can specify another folder structure, like any depth or folder name length

```
for 203bad62 it can be
/2/0/203bad62.jpg
/2/0/3/b/203bad62.jpg
/20/203bad62.jpg
/20/3b/203bad62.jpg
etc....
```

You can specify size as second argument for center fit cropping

```php
LavaImage::save('http://lorempixel.com/300/300/', [100,100]);
```

As first argument you can pass any data, what [Intervention/image make method](http://image.intervention.io/api/make) support

```php
// create a new image resource from file
LavaImage::save('public/foo.jpg');

// or create a new image resource from binary data
LavaImage::save(file_get_contents('public/foo.jpg'));

// create a new image from gd resource
LavaImage::save(imagecreatefromjpeg('public/foo.jpg'));

// create a new image directly from an url
LavaImage::save('http://example.com/example.jpg');

// create a new image directly from Laravel file upload
LavaImage::save(Input::file('photo'));
```

Any time after saving you can retrieve generated hash by `LavaImage::getImageCode()`

## Getting Image

If you need get image, use hash you know

```php
// for http path (http://example.com/uploads/2/0/203bad62.jpg)
LavaImage::getImage($hash);

// for absolute path (/home/var/laravel/public/uploads/2/0/203bad62.jpg)
LavaImage::getImage(LavaImage::getImageCode(), true)
```


## License

Laravel Image is released under the MIT Licence. See the bundled LICENSE file for details.
