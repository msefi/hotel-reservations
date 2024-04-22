## Table of contents

- [About](#about)
- [Features](#features)
- [Installation](#installation)


## About

Aplikasi reservasi hotel berbasis web menggunakan laravel 11, Reservasi dapat dilakukan melalui halaman web (user) maupun melalui halaman admin.

## Features

#### Halaman User

```
http://localhost:8000
```

- Register
- Login
- Search Hotels
- Select Rooms
- Reservation & Reschedule

#### Halaman Admin

```
http://localhost:8000/admin
```
email: admin@admin.com
password: admin

- Login
- Manage user
- Manage Hotels
- Manage Rooms
- Manage Reservations
- Report

## Installation

Clone repository
```
git clone https://gitlab.com/sefi06/hotel-reservations.git
```

Go to directory
```
cd hotel-reservations
```

Install Composer
```
composer install
```

Setting .env from .env.example, adjust APP_URL and database connection
```
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel_reservations
DB_USERNAME=root
DB_PASSWORD=
```

Run database migrations & seeds
```
php artisan migrate --seed
```

Generate encryption key
```
php artisan key:generate
```

Link storage directory
```
php artisan storage:link
```

Let's start
```
php artisan serve
```