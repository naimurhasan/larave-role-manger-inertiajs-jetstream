## Role Manager

This is an open source app to manage role and permissions in laravel. Built Over Laravel Inertiajs Jetstream.

![Laravel Inertiajs Jetstream Role Manager](https://github.com/naimurhasan/larave-inertiajs-jetstream-role-manger/blob/master/_screenshot.jpg?raw=true)


## How to Use

Assuming you don't have created any project. And you can start with this project if you need roles and permission in your app.

- Clone This Repo
- run 'npm run dev' in project dir
- run 'composer install'
- edit env database
- run 'php artisan migrate'

## How to Login

The register feature is disabled (commented) in config/fortify.php. So you can't register dynamically. I disabled this so Only Super Admin Can Create Admin.

- admin login 
- ** 'Email' : admin@demo.com 
- ** 'Pass' : demo
- super admin login
- ** 'Email' : superadmin@demo.com
- ** 'Pass' Demo