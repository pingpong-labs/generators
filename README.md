Laravel Generators
==========

[![Build Status](https://travis-ci.org/pingpong-labs/generators.svg)](https://travis-ci.org/pingpong-labs/generators)
[![Latest Stable Version](https://poser.pugx.org/pingpong/generators/v/stable.svg)](https://packagist.org/packages/pingpong/generators)
[![Total Downloads](https://poser.pugx.org/pingpong/generators/downloads.svg)](https://packagist.org/packages/pingpong/generators)
[![Latest Unstable Version](https://poser.pugx.org/pingpong/generators/v/unstable.svg)](https://packagist.org/packages/pingpong/generators)
[![License](https://poser.pugx.org/pingpong/generators/license.svg)](https://packagist.org/packages/pingpong/generators)

### Quick Installation Via Composer

```
composer require "pingpong/generators:dev-master"
```

Next, register new service provider to `providers` array in `app/config/app.php`.

```php
'Pingpong\Generators\GeneratorsServiceProvider'
```

Done.

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

**Generate a new migration**

```php
use Pingpong\Generate\MigrationGenerator;

$generator = new MigrationGenerator($path, 'create_users_table');

$generator = new MigrationGenerator($path, 'create_users_table', 'name:string, username:string');

$generator = new MigrationGenerator($path, 'add_remember_token_to_users_table', 'remember_token:string:nullable');

$generator = new MigrationGenerator($path, 'remove_username_from_users_table', 'username:string');

$generator = new MigrationGenerator($path, 'drop_users_table', 'name:string, username:string');
```

### License

This package is open-sourced software licensed under [The BSD 3-Clause License](http://opensource.org/licenses/BSD-3-Clause)