# Foodics


Foodics challenge for Order REST Api.
The project is built on `Laravel(10 Framework)`.

## Technology Stack
It is built on Laravel 10.0 `(Latest)` which requires PHP 8.0 + to run it.
* Laravel
* MySQL

## Features
* Store Order API
* Code Documentation
* UNIT `TEST`
* Scalable Database Design
* Design Pattern (Repositry Pattern to abstract implmentation)
* Clean Code
* Send Email On Low Stock
* Live Stock Update on Order



## Code Documentation Route
```bash
/docs
```
![Demo1](https://raw.githubusercontent.com/MSaddamKamal/wireMedia/main/doc1.JPG)

## Tests
```bash
php artisan test
```

## Installation

Create `.env` file at the root of the project and add database credentials.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodics
DB_USERNAME=root
DB_PASSWORD=

```

Now add mail credentials in `.env` or set `MAIL_DRIVER=log` for local

```bash
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

```

Install composer vendor packages by following command via [composer]

```bash
composer install
```

Run migrations and seeders by following command

```bash
php artisan migrate:fresh --seed
```

Clear Cache

```bash
php artisan optimize
```
[Node.js]: https://nodejs.org/en/
[npm]: https://www.npmjs.com/
[composer]:https://getcomposer.org/
[npm install]: https://docs.npmjs.com/getting-started/installing-npm-packages-locally
[sandbox]: https://docs.npmjs.com/getting-started/installing-npm-packages-locally



## Troubleshooting

Run following commands for troubleshooting

```bash
php artisan optimize
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)

