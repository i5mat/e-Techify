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
