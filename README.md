# Laravel Social App APIs

## Clone the repo
```
git clone https://github.com/CodeWithHBKY/laravel-social-apis.git
```

## After installation

### Step 1
```
cd laravel-social-apis/
```

### Step 2
```
composer update
```
or

```
composer install
```

### Create Database
first run XAMPP and start apache and sql

#### Then goto
```
localhost/phpmyadmin
```
create database after database creation goto .env in the project and add the database name to
```
DB_DATABASE=social_apis
```
"social_apis" will be your database's name

### Migrate

```
php artisan migrate
```

### Run project

```
php artisan server
```