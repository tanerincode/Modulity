# Laravel Module Generator

Simple Laravel Module Generator.

[![Build Status](https://travis-ci.org/tanerincode/module-generator.svg?branch=master)](https://packagist.org/packages/tanerincode/module-generator) [![MIT License](https://camo.githubusercontent.com/e65c945b219ec6c6f63826a83df905b3191ae52c/68747470733a2f2f706f7365722e707567782e6f72672f6c61726176656c2f6672616d65776f726b2f6c6963656e73652e737667)](LICENSE) [![Coverage Status](https://coveralls.io/repos/github/tanerincode/module-generator/badge.svg?branch=master)](https://coveralls.io/github/tanerincode/module-generator?branch=master)
[![security](https://img.shields.io/badge/security-2%2F4-green)](SECURITY.md)
[![stable](https://img.shields.io/badge/stable-2.0.1-yellowgreen)](https://packagist.org/packages/tanerincode/module-generator)


## Getting Started

This Package: Automatically creates a module or part in a Laravel Modular Pattern.

### Prerequisites

What things you need to install the software and how to install them

```
"php": "^7.2.0",
"illuminate/console": "^5.3.0",
"illuminate/support": "^5.3.0",
"laravel/laravel" : "^5.6.*"
""
```

### Installing

A step by step series of examples that tell you how to get a development env running

Step 1 : require composer package

```
composer require tanerincode/module-generator
```


Step 2 : if this package not working automatically add provider in `config/app.php` 

```
TanerInCode\Modulity\Providers\ModulityServiceProvider::class,
```

Step 3 : Publish config file and select `mgenerator.php` 

```
php artisan vendor:publish
```

Step 4 : Update Modules Namespace, `mgenerator.php` or `.env` file 

```
'name_space' => getenv("MODULE_GENERATOR_NAMESPACE", 'ChangeHere')
```

OR

```
MODULE_GENERATOR_NAMESPACE=ChangeHere
```

**PS: Do not touch `src_url`.**


Last Step : Replace the Psr-4 field in the Composer.json file with the namespace of your choice.

```
"autoload": {
    ...
    "psr-4": {
        ...
        "TanerInCode\\" : "app/Modules"
    }
```


## Built With

* [Laravel](https://laravel.com/docs/5.7) - The laravel framework

## Authors

* **Taner Tombas** - [TanerInCode](https://github.com/tanerincode)


## License

This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/tanerincode/module-generator/blob/master/LICENSE) file for details

