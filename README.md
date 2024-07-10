# Laravel

Laravel is a powerful PHP framework known for its expressive syntax and developer-friendly features. It follows the MVC architecture, offering robust tools like Eloquent ORM for database management and Blade templating for UI rendering. With built-in authentication, caching, and robust community support, Laravel simplifies web development while ensuring scalability and maintainability. It's favored for its extensive documentation, active community, and regular updates, making it a top choice for building modern web applications.

*******************
Server Requirements
*******************

PHP version 8.2.12 or newer is recommended.

It should work on 8.2.12 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

Laravel version 10 or newer is recommended


************
Installation
************

Please see the `installation section <https://laravel.com/docs/10.x/installation>`- of the Laravel User Guide.

## Copy the example env file and make the required configuration changes in the .env file
```bash
    cp .env.example .env
```
## Generate a new application key
```bash
    php artisan key:generate
```
## Run the database migrations (**Set the database connection in .env before migrating**)
```bash
   $ php artisan migrate
```
