## TusenFryd Hittegods

#### Table of contents
- [About](#about)
- [Installation Instructions](#installation-instructions)
    - [Build the Front End Assets with Mix](#build-the-front-end-assets-with-mix)
    - [Optionally Build Cache](#optionally-build-cache)
- [Seeds](#seeds)
    - [Seeded Roles](#seeded-roles)
    - [Seeded Permissions](#seeded-permissions)
    - [Seeded Users](#seeded-users)
    - [Themes Seed List](#themes-seed-list)

### About
Laravel 7 based system for managing lost and found items in the park in one centralized panel with powerful search utilizing elasticsearch

### Installation Instructions
1. Run `git clone https://github.com/jeremykenedy/laravel-auth.git laravel-auth`
2. Create a MySQL database for the project
    * ```mysql -u root -p```, if using Vagrant: ```mysql -u homestead -psecret```
    * ```create database laravelAuth;```
    * ```\q```
3. From the projects root run `cp .env.example .env`
4. Configure your `.env` file
5. Run `composer update` from the projects root folder
6. From the projects root folder run:
```
php artisan vendor:publish --tag=laravelroles &&
php artisan vendor:publish --tag=laravel2step
```
7. From the projects root folder run `sudo chmod -R 755 ../laravel-auth`
8. From the projects root folder run `php artisan key:generate`
9. From the projects root folder run `php artisan migrate`
10. From the projects root folder run `composer dump-autoload`
11. From the projects root folder run `php artisan db:seed`
12. Compile the front end assets with [npm steps](#using-npm) or [yarn steps](#using-yarn).

#### Build the Front End Assets with Mix
##### Using Yarn:
1. From the projects root folder run `yarn install`
2. From the projects root folder run `yarn run dev` or `yarn run production`
  * You can watch assets with `yarn run watch`

##### Using NPM:
1. From the projects root folder run `npm install`
2. From the projects root folder run `npm run dev` or `npm run production`
  * You can watch assets with `npm run watch`

#### Optionally Build Cache
1. From the projects root folder run `php artisan config:cache`

### Seeds
##### Seeded Roles
  * Unverified - Level 0
  * User  - Level 1
  * Administrator - Level 5

##### Seeded Permissions
  * view.users
  * create.users
  * edit.users
  * delete.users

##### Seeded Users

|Email|Password|Access|
|:------------|:------------|:------------|
|user@user.com|password|User Access|
|admin@admin.com|password|Admin Access|

##### Themes Seed List
  * [ThemesTableSeeder](https://github.com/jeremykenedy/laravel-auth/blob/master/database/seeds/ThemesTableSeeder.php)
  * NOTE: A lot of themes render incorrectly on Bootstrap 4 since their core was built to override Bootstrap 4. These will be updated soon and ones that do not render correctly will be removed from the seed. In the mean time you can remove them from the seed or manaully from the UI or database.

##### Blocked Types Seed List
  * [BlockedTypeTableSeeder.php](https://github.com/jeremykenedy/laravel-auth/blob/master/database/seeds/BlockedTypeTableSeeder.php)

|Slug|Name|
|:------------|:------------|
|email|E-mail|
|ipAddress|IP Address|
|domain|Domain Name|
|user|User|
|city|City|
|state|State|
|country|Country|
|countryCode|Country Code|
|continent|Continent|
|region|Region|

##### Blocked Items Seed List
  * [BlockedItemsTableSeeder.php](https://github.com/jeremykenedy/laravel-auth/blob/master/database/seeds/BlockedItemsTableSeeder.php)

|Type|Value|Note|
|:------------|:------------|:------------|
|domain|test.com|Block all domains/emails @test.com|
|domain|test.ca|Block all domains/emails @test.ca|
|domain|fake.com|Block all domains/emails @fake.com|
|domain|example.com|Block all domains/emails @example.com|
|domain|mailinator.com|Block all domains/emails @mailinator.com|
