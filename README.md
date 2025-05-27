### GetPayIn Content Scheduler 🚀
Backend Developer Coding Challenge: Content Scheduler
A Laravel application with Filament PHP admin panel for scheduling and managing posts.

### Table of Contents
- Features
- Requirements
- Installation
- Configuration
- Usage
- Admin Access
### Features ✨
- User authentication system

- Post management (CRUD operations)

- Scheduled post publishing

- Admin dashboard with Filament PHP

- Queue system for background processing
### Requirements 📋
- PHP 8.1+

- Laravel 10.x

- Composer 2.0+

- MySQL 5.7+ / MariaDB 10.3+

### Installation 🛠️
* ``` git clone https://github.com/ayaashraf88/posts.git ```
* Install dependencies:
    - composer install
* Set up environment:
    - ``` cp .env.example .env ```
    - ``` php artisan key:generate ```
* Configure your database in .env:
    - DB_CONNECTION=mysql
    - DB_HOST=127.0.0.1
    - DB_PORT=3306
    - DB_DATABASE=your_db_name
    - DB_USERNAME=your_db_user
    - DB_PASSWORD=your_db_password
* Run migrations and seeders:
    - ``` php artisan migrate --seed ```
### Usage 🚀
* Start the development server:
    - ``` php artisan serve ```
* Running Queue Workers
For scheduled posts to publish automatically:
    - ``` php artisan queue:work ```
### Admin Access 🔑
- Access the Filament admin panel at: /admin

- Default Admin Credentials:

    - Email: admin@getpayin.com

    - Password: 12345678