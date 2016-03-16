## Setup Instructions

- Install PHP 5.6
- Install composer (https://getcomposer.org)
- Install Laravel (https://laravel.com/docs/5.2/installation)
- Install postgres
- Copy .env.example into .env and change parameters according to your local setup
- `composer install`
- `php artisan migrate`
- `php artisan serve`
- Navigate to http://localhost:8000

After every pull:

`composer update`
`php artisan migrate`
`php artisan serve`
