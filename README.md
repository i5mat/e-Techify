<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Introduction 

e-Techify is an e-commerce, inventory management and tracking system. This system is designed by Ismat which is created for Xmiryna Technology and PSM(FYP).

## Setup and Deployment 🔧

1. To get started, fork this repository to your GitHub account.
   

2. Clone the forked repo from your account using:
```bash
git clone https://github.com/i5mat/e-Techify
```

3. Install Composer Dependencies
```bash
composer install
```

4. Install NPM Dependencies
```bash
npm install
```

5. Create a copy of your .env file
```bash
cp .env.example .env
```

6. Generate an app encryption key
```bash
php artisan key:generate
```

7. Create an empty MySQL database for application.
   

8. In the .env file, add database information to allow Laravel to connect to the database.
```bash
DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to match the credentials of the database you just created.
```

9. Migrate the database
```bash
php artisan migrate
```

10. Enjoy the project! 😃🔥


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
