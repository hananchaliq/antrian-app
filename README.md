<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/hananchaliq/<nama-repo>/actions"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP Version"></a>
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel Version"></a>
<a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License"></a>
</p>

## About Sistem Antrian Online

Sistem Antrian Online is a web-based queue management application built with Laravel and Tailwind CSS. Designed to be lightweight, responsive, and deployable on standard shared hosting without requiring separate WebSocket servers, the application streamlines daily queue operations through:

- Real-time queue status synchronization using HTTP JSON Polling (2-second interval).
- Public visitor interface for generating new queue numbers and monitoring active calls without page reloads.
- Authenticated admin control panel for managing daily queue progression (*next*, *previous*, and waiting list monitoring).
- Database-agnostic schema migrations compatible with MySQL and PostgreSQL.
- Responsive interface styled with Tailwind CSS for both desktop monitor displays and mobile devices.

Sistem Antrian Online is accessible, fast, and provides the essential tools required for structured service queue operations.

## Installation

Follow these steps to set up the application in your local development environment:

1. Clone the repository and navigate to the project directory:

```bash
git clone https://github.com/hananchaliq/antrian-app.git
cd <nama-repo>
```

2. Install PHP and frontend dependencies:

```bash
composer install
npm install && npm run build
```

3. Copy the environment configuration file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in `.env`, run the migrations, and start the local development server:

```bash
php artisan migrate
php artisan serve
```

## Real-Time Architecture

To ensure seamless compatibility across standard shared hosting environments, real-time updates rely on lightweight HTTP JSON polling via the Fetch API rather than persistent socket connections.

The public display and visitor views poll the status endpoint every 2 seconds to synchronize the active queue number and waiting list automatically, keeping server overhead minimal while maintaining responsive updates.

## Contributing

Contributions, issue reports, and feature suggestions are welcome. Feel free to fork the repository and submit a pull request with clear descriptions of your changes.

## Security Vulnerabilities

If you discover a security vulnerability within this application, please open an issue or contact the maintainer directly via [GitHub](https://github.com/hananchaliq). All security concerns will be reviewed promptly.

## License

Sistem Antrian Online is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT), developed by [Hanan Nurdin Ramadhan Chaliq](https://github.com/hananchaliq).
