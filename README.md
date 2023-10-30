# Laravel RESTful API Boilerplate

Boilerplate for RESTful API applications built with Laravel 8/9/10.

This package provides a minimal and simple starting point for building a 
RESTful API application. Among other things, the package allows you to easily 
manage user and API tokens, logs requests and provides uniformed and standardized 
RESTful responses.

This package will add these features:
- Artisan commands to create and delete users
- Artisan commands to generate, extend and revoke API token (with Sanctum)
- Database migration and model for request logging
- Group middleware for automatic request logging
- Middleware for route specific request logging
- Adds a ping endpoint (/api/v1/ping)
- Adds a profile endpoint (/api/v1/me)
- Adds a fallback for all non-existing endpoints
- Removes default frontend files
- Adds robots.txt to disallow everything

## Installation

Install the package with Composer:

`
composer require kfoobar/laravel-restful
`

Add all necessary files with this command:

`
php artisan restful:install
`

***Please notice!***

This package is meant to be installed on a fresh Laravel project since it will overwrite a few default files.


## Instructions

Coming soon...

## Contributing

Contributions are welcome!

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
