# Laravel Comments Module

[![Build Status](https://travis-ci.org/php-soft/laravel-comments.svg)](https://travis-ci.org/php-soft/laravel-comments)

> This is RESTful APIs

## 1. Installation

Install via composer - edit your `composer.json` to require the package.

```js
"require": {
    // ...
    "php-soft/laravel-comment": "dev-master",
}
```

Then run `composer update` in your terminal to pull it in.
Once this has finished, you will need to add the service provider to the `providers` array in your `app.php` config as follows:

```php
'providers' => [
    // ...
    PhpSoft\ArrayView\Providers\ArrayViewServiceProvider::class,
    PhpSoft\Comments\Providers\CommentServiceProvider::class,
]
```

## 2. Migration and Seeding

Now generate the migration:

```sh
$ php artisan ps-comments:migrate
```

It will generate the migration files. You may now run it with the artisan migrate command:

```sh
$ php artisan migrate
```



## 3. Usage

Add routes in `app/Http/routes.php`

```php
Route::group(['middleware'=>'auth'], function() {

    Route::get('/comments/{url}', '\PhpSoft\Comments\Controllers\CommentController@index')->where('url', '.*');
    Route::post('/comments', '\PhpSoft\Comments\Controllers\CommentController@store');
    Route::patch('/comments/{id}', '\PhpSoft\Comments\Controllers\CommentController@update');
    Route::delete('/comments/{id}', '\PhpSoft\Comments\Controllers\CommentController@destroy');
});
```

***You can remove middlewares if your application don't require check authenticate and permission!***