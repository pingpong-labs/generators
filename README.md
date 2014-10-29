Simple generators package for Laravel framework
==========

[![Build Status](https://travis-ci.org/pingpong-labs/generators.svg)](https://travis-ci.org/pingpong-labs/generators)

### Quick Installation Via Composer

```
composer require "pingpong/generators:dev-master"
```

### Documentation

**Generate a new controller**

```php
$path = app_path('controllers');

$generator = new Pingpong\Generators\ControllerGenerator($path, 'HomeController');

$generator->generate();
```

You may also set the namespace for the class by specify the `namespace` key in the `options` array. The `options` array is the third argument in the generator class. For example :

```php
$options = ['namespace' => 'App\\Controllers'];

$generator = new Pingpong\Generators\ControllerGenerator($path, 'HomeController', $options);

$generator->generate();
```

**Generate a new model**

```php
$generator = new Pingpong\Generators\ModelGenerator($path, 'User');

$generator->generate();
```

**Generate a new seed**

```php
$generator = new Pingpong\Generators\SeedGenerator($path, 'UsersTableSeeder');

$generator->generate();
```

**Generate a new filter**

```php
$generator = new Pingpong\Generators\FilterGenerator($path, 'AdminFilter');

$generator->generate();
```

**Generate a new form request**

```php
$generator = new Pingpong\Generators\FormRequestGenerator($path, 'LoginRequest');

$generator->generate();
```

**Generate a new command**

```php
$generator = new Pingpong\Generators\CommandGenerator($path, 'FooCommand');

$generator->generate();
```

**Generate a new service provider**

```php
$generator = new Pingpong\Generators\ProviderGenerator($path, 'BarServiceProvider');

$generator->generate();
```

### License

This package is open-sourced software licensed under [The BSD 3-Clause License](http://opensource.org/licenses/BSD-3-Clause)