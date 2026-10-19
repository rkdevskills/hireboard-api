# HireBoard API

![Laravel CI](https://github.com/rkdevskills/hireboard-api/actions/workflows/laravel.yml/badge.svg)

A RESTful API for a job board platform built with Laravel 12 and Sanctum authentication.

## Features
- Token-based authentication (Laravel Sanctum)
- Role-based access control (Employer / Candidate)
- Job listings with CRUD operations
- Search and filtering (keyword, location, type, salary)
- Application management with status tracking
- API Resources for consistent JSON responses
- Policy-based authorization
- PHPUnit feature tests (12 tests)

## Tech Stack
- Laravel 12
- MySQL
- Laravel Sanctum
- PHP 8.2+

## Installation

1. Clone the repo
   git clone https://github.com/rkdevskills/hireboard-api.git

2. Install dependencies
   composer install

3. Copy .env file
   cp .env.example .env

4. Generate app key
   php artisan key:generate

5. Configure your database in .env

6. Run migrations
   php artisan migrate

7. Start the server
   php artisan serve

## Running Tests
   php artisan test

## API Endpoints

### Auth
- POST /api/register
- POST /api/login
- POST /api/logout
- GET  /api/me

### Jobs
- GET    /api/jobs
- POST   /api/jobs
- GET    /api/jobs/{id}
- PUT    /api/jobs/{id}
- DELETE /api/jobs/{id}

### Applications
- GET  /api/applications
- POST /api/applications
- GET  /api/applications/{id}
- PUT  /api/applications/{id}

## Testing Credentials
Employer:
- email: employer@test.com
- password: password

Candidate:
- email: candidate@test.com
- password: password

## CI/CD
- Continuous Integration via GitHub Actions
- Runs automatically on every push to main
- PHP 8.4, SQLite in-memory database
- All 14 tests must pass before merge