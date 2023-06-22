# supermarket-php-challenge

[![Coverage Status](https://coveralls.io/repos/github/reinaldorauch/supermarket-php-challenge/badge.svg?branch=main)](https://coveralls.io/github/reinaldorauch/supermarket-php-challenge?branch=main)

The backend php application for a supermaket selling system.

The frontend is [this repo](https://github.com/reinaldorauch/supermarket-php-challenge-front)

## Install the Application

You'll need to have PHP 8.2 installed for optimal results but PHP 7.4 should run just fine.

Also, you'll need a PostgreSQL database ready to be used by the app.

In the repository, you need to set the `.env` file with the postgres url in PDO DSN format
(example in `./.env.template` file) and a secret to be used for signing JWT tokens

```env
DATABASE_URL="pgsql:host=localhost;port=5432;dbname=supermarket-php-challenge;user=postgres;password=test"
SECRET="a_cryptographic_secure_pseudorandom_data"
```

Then, run the `./docs/database/00001_create_db.sql` SQL file in that Postgres database to create the schema
structure and seed the first user.

To run the application in development, you can run these commands

```bash
cd [supermarket-php-challenge]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:

```bash
cd [supermarket-php-challenge]
docker-compose up -d
```

Then, you can open the [Adminer](https://www.adminer.org/) db management interface and import the `./docs/database/00001_create_db.sql` file to generate the database schema.

After that, open `http://localhost:8000` in your browser.

## Testing

You'll need to complete the database setup process above before you can run the tests.

Run this command in the application directory to run the test suite:

```bash
composer test
```

That's it! Now go build something cool.
