### Laralite Skeleton

This is the Laralite skeleton project, with the basic template for getting started with a Laralite project.

### Technologies used

- PHP 7.3+
- Composer
- NPM
- Laravel 7
- Laravel Passport for oAuth
- Linux (Ubuntu)

### How to compile assets

Asset compilation is handled by Laravel mix, and configured in the `./webpack.mix.js` file, documentation can be found [here](https://laravel-mix.com/docs/5.0/installation).

### How to setup project

1. Setup Valet environment locally
2. Setup database
3. Copy `.env.example` and update values to relevant values
4. Run commands below...

```
// Install PHP dependencies
composer install -a

// Install JavaScript dependencies
npm install

// Compile assets
npm run dev

// Compile Laralite assets
cd Modules/Laralite/

npm install 

npm run dev

// Run database migrations
artisan migrate 

// Run passport install
artisan passport:install
```

In addition, for initial deployment the keys for authentication need to be registered, this is done with the following;

```
artisan passport:keys
```

### Install sanctum

[Read more here](https://laravel.com/docs/7.x/sanctum#how-it-works)

```
artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

artisan migrate
```
