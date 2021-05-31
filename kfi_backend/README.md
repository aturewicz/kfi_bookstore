# kfi_bookstore - backend

## Tech
Project uses:
* Symfony ver. 5.2

If you want see all dependencies check files:
* composer.json

## Installation

**Downloaded Composer dependencies**
```
composer install
```

**Configure the .env (or .env.local) File**

Open the `.env` file and make any adjustments you need - specifically
`DATABASE_URL`. Or, if you want, you can create a `.env.local` file
and *override* any configuration you need there (instead of changing
`.env` directly).

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`

**Set up the Database**

Again, make sure `.env` is set up for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

**Import CSV file to database**
```
php bin/console app:csv-import products.csv
```

Location products.csv file: `project_location/data/`.
If you want add/update rows in database use this same command (example: `php bin/console app:csv-import products.csv`)

**Start the development web server**

Install the Symfony local web server.
"Downloading the Symfony client" instructions found here: https://symfony.com/download - you only need to do this once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve --allow-http
```

Now check out the site at `https://localhost:8000/_profiler`

## TODO LIST
* refactoring
* tests