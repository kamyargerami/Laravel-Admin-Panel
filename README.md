# Admin Panel

## Installation

```
composer install

npm install
```

if  you are using cpanel:
```
cp .htaccess.example .htaccess
```

Setup your database information in .env file

```
php artisan migrate

php artisan db:seed
```

## Production Setup

### Queue
you must add queue to supervisor for notifications

https://laravel.com/docs/8.x/queues#supervisor-configuration

### Redis
You need to install that in production for better experience with cache and add details in .env file.
if you don't have redis, you can use 'file' for CACHE_DRIVER

## Development

### composer.locks
please don't commit your local version to git , (it breaks composer if you use higher version on your local machine)

### env Variables
please don't use env variable. because we use artisan optimize command it doesn't get value from .env file.

you must put this env variables in app/config directory

### Versioning
Don't commit anything in git without merge request.

### Front End
if you need to add a custom css or custom js code you must add it into style or script section in blade page.

if you need to add new style that must run on every page you can add it into _custom.scss file in resources directory.

if you need to add new script that must run on every page you can add it into custom.js file in resources directory.

after that you must run:

```
npm run prod
```

#### Dont commit any css or js library in public folder.
if you need extra library into your page that cannot be used by a CDN, you can move it into plugins directory in public folder.
