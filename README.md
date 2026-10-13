# HireBoard API

A RESTful API for a job board platform built with Laravel 12 and Sanctum.

## Features
- Token-based authentication (Laravel Sanctum)
- Role-based access control (Employer / Candidate)
- Job listings with search and filtering
- Application management with status tracking
- API Resources for consistent JSON responses
- Policy-based authorization

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