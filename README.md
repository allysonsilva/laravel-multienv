# Laravel Multi ENVs

<p align="center">
    <img src="/images/art/socialcard.png" alt="Social Card of Laravel Multi ENVs">
</p>

[![PHP Version][ico-php]][link-php]
[![Laravel Version][ico-laravel]][link-laravel]
[![CI Status][ico-actions]][link-actions]
[![PHPCS - GitHub Workflow Status](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpcs.yml/badge.svg)](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpcs.yml)
[![PHPMD - GitHub Workflow Status](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpmd.yml/badge.svg)](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpmd.yml)
[![PHPStan - GitHub Workflow Status](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpstan.yml/badge.svg)](https://github.com/allysonsilva/laravel-multienv/actions/workflows/phpstan.yml)
[![Coverage Status][ico-codecov]][link-codecov]
[![Latest Version][ico-version]][link-packagist]
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

> Use **multiple** `.envs` files and have a chain of precedence for the environment variables in these different `.envs` files. Use the `.env` file in a custom way to manipulate environment variables by domain (*multi-tenant*).

## 🚀  Installation

### Requirements

The package has been developed and tested to work with the following minimum requirements:

- *PHP 8.0*
- *Laravel 9.0*

### Laravel version Compatibility

| Laravel | PHP |    Package   |
|:-------:|:---:|:------------:|
|    9.x  | 8.0 |   **^2.0**   |
|    8.x  | 7.4 |   **^1.0**   |

### Install the Package

You can install the package via Composer:

```bash
composer require allysonsilva/laravel-multienv
```

### Publish the Config

You can then publish the package's config file by using the following command:

```bash
php artisan vendor:publish --tag="envs-config"
```

## 🔧  Configuration

1. Add trait to **kernel console** `app/Console/Kernel.php`:
    ```diff
    <?php

    namespace App\Console;

    use Illuminate\Console\Scheduling\Schedule;
    +use Allyson\MultiEnv\Concerns\ConsoleCallTrait;
    +use Allyson\MultiEnv\Concerns\BootstrappersTrait;
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

    class Kernel extends ConsoleKernel
    {
    +    use BootstrappersTrait, ConsoleCallTrait;
    ```

2. Add trait to **kernel http** `app/Http/Kernel.php`:
    ```diff
    <?php

    namespace App\Http;

    +use Allyson\MultiEnv\Concerns\BootstrappersTrait;
    use Illuminate\Foundation\Http\Kernel as HttpKernel;

    class Kernel extends HttpKernel
    {
    +    use BootstrappersTrait;
    ```

## 📖  Usage

> **The use of this package happens by manipulating the `.envs` files in the project.**

### Using multiple `.env` files in the **project root**

- *You can use as many `.env` files as you like.*

- By default, when listing `.env` files to give priority to the last ones, the "natural order" algorithm is used, more specifically PHP's `strnatcmp` function, thus the environment variables of the last `.envs` will have priority / precedence over all others.
  - You can use the `config('envs.sorted')` to custom sort the `.envs` files. *The last items in the array will have priority over the others.*

- Use the `config('envs.ignored')` regex to ignore `.env` files that should not be processed/handled.

#### See it in action

Assuming we have 3 `.env` files in the root of the application with their *environment variables* as follows:

```diff
.
├── app
├── bootstrap
├── config
├── database
├── envs
│   ├── .env.site1.test
│   └── .env.site2.test
├── lang
├── public
├── resources
├── routes
├── storage
├── tests
├── .env
├── .env.example
+├── .envA
+├── .envB
+├── .envC
├── .gitattributes
├── .gitignore
├── README.md
├── artisan
├── composer.json
└── phpunit.xml
```

*Obs: The [`envs`](#using-multiple-env-files-per-domain) folder in the application structure below will be explained later.*

Each `.env` file has its environment variables set as follows:

**`.envA`:**

```
ENV_NAME="NAME Env A"
APP_URL=http://env-a.test

ENV_FILE_A=.envA
```

**`.envB`:**

```
ENV_NAME="NAME Env B"
APP_URL=http://env-b.test

ENV_FILE_B=.envB
```

**`.envC`:**

```
ENV_NAME="NAME Env C"
APP_URL=http://env-c.test

ENV_FILE_C=.envC
```

As it is, the `.envC` file is the last one listed in the structure above, so it will override any *environment variables* defined in the preceding `.env` files, and the *environment variables* that exist in the other files. more is not in the last (priority), it will continue to be used normally, the result / consolidated of the *environment variables* of the three files are:

```
ENV_NAME="NAME Env C"
APP_URL=http://env-c.test

ENV_FILE_A=.envA
ENV_FILE_B=.envB
ENV_FILE_C=.envC
```

Using the configuration of `config('envs.sorted')`, you can customize the default order of file priorities:

```php
'sorted' => [
    '.envA',
    '.envC',
    '.envB',
],
```

As above, the result of the *environment variables* of the 3 files would be:

```
ENV_NAME="NAME Env B"
APP_URL=http://env-b.test

ENV_FILE_A=.envA
ENV_FILE_C=.envC
ENV_FILE_B=.envB
```

### Using multiple `.env` files **per domain**

In the same way that you use [multiple envs files in the root of the application](#using-multiple-env-files-in-the-project-root), it is possible to use `.env` files per domain:

- These files are located in the configuration folder of `config('envs.folder')`, which by default the folder name is `envs`, as seen in the project listing above.

- In order for the `.env` file to match the domain / subdomain, it must be created as follows: `.env.<domain>`.

- To configure a different `.env` filename than the default, add the domain to `config('envs.domains')`, having the `env` key of the domain set to your preference.

#### See it in action

Using the same structure as the previous example, if the request / domain is `site1.test`, and there is a file `.env.site1.test` inside the `envs` folder, then the *environment variables* in that file will override all the other *environment variables*.

Assuming that the `.env.site1.test` file has the following variables:

```
ENV_NAME="NAME Env SITE 1"
APP_URL=http://site1.test
```

When *environment variables* are used in the project, they will have the following results:

```
ENV_NAME="NAME Env SITE 1"
APP_URL=http://site1.test

ENV_FILE_A=.envA
ENV_FILE_C=.envC
ENV_FILE_B=.envB
```

To see the examples in action, let's use [this laravel application](https://github.com/allysonsilva/laravel-multienv-use).

#### Domain custom `.env` file name

When the domain's `.env` filename is different from the default which is: `.env.<domain>`, then set the `env` key in the domain configs in `config('envs.domains') `, as follows:

```php
'domains' => [
    'your-domain.tld' => [
        'env' => '.env.custom-name',
    ],
],
```

### Using `config:cache` and `route:cache` **per domain**

**You can cache configs and routes by domain.**

#### Caching *configs* by domain - `config:cache`

A new `--domain` option is available in the command. Using this option, the environment variables from the domain's `.env` file in the `envs` folder will override and take precedence over all others.

Use the `config('envs.domains')` configuration to customize the `.php` file that will be saved and used as a cache of configs. This ensures that multiple configuration files per domain can exist and be used in the same project.

To generate and use the `.php` file with a custom name of the domain cache settings, use the following code as an example:

```php
'domains' => [
    'site2.test' => [
        'APP_CONFIG_CACHE' => 'config-site2-test.php',
    ],
],
```

#### Caching *routes* by domain - `route:cache`

As in the section above, the command to create the route cache file has a new `--domain` option, which will be used to filter only the routes that have the domain according to the option value.

It is also possible to have a custom name for the routes cache file, such as the configuration cache, is through the `APP_ROUTES_CACHE` key, as in the example below:

```php
'domains' => [
    'site2.test' => [
        'APP_ROUTES_CACHE' => 'routes-v7-site2-test.php',
    ],
],
```

## 🧪  Testing

``` bash
composer test:unit
```

## 📝  Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about the changes on this package.

## 🤝  Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email github@allyson.dev instead of using the issue tracker.

## 🏆  Credits

- [Allyson Silva](https://github.com/allysonsilva)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-php]: https://img.shields.io/packagist/php-v/allysonsilva/laravel-multienv?color=%234F5B93&logo=php
[ico-laravel]: https://img.shields.io/static/v1?label=laravel&message=%E2%89%A59.0&color=ff2d20&logo=laravel
[ico-actions]: https://github.com/allysonsilva/laravel-multienv/actions/workflows/ci.yml/badge.svg
[ico-codecov]: https://codecov.io/gh/allysonsilva/laravel-multienv/branch/main/graph/badge.svg?token=H546OKODQB
[ico-version]: https://img.shields.io/packagist/v/allysonsilva/laravel-multienv.svg?label=stable

[link-php]: https://www.php.net
[link-laravel]: https://laravel.com
[link-actions]: https://github.com/allysonsilva/laravel-multienv/actions/workflows/ci.yml
[link-codecov]: https://codecov.io/gh/allysonsilva/laravel-multienv
[link-packagist]: https://packagist.org/packages/allysonsilva/laravel-multienv
