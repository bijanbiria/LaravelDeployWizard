# Laravel Deploy Wizard

[![Latest Stable Version](https://poser.pugx.org/bijanbiria/laravel-deploy-wizard/v/stable)](https://packagist.org/packages/bijanbiria/laravel-deploy-wizard)
[![License](https://poser.pugx.org/bijanbiria/laravel-deploy-wizard/license)](https://packagist.org/packages/bijanbiria/laravel-deploy-wizard)

Laravel Deploy Wizard is a powerful Laravel Installer inspired by the WordPress installation process. It provides a user-friendly wizard for setting up Laravel applications easily, handling environment configuration, database setup, and executing necessary Artisan commands.

---

## 🚀 **Features**

* Interactive step-by-step setup wizard
* Automatic `.env` file generation if it does not exist
* Database connection setup and validation
* Route-based installation flow (`deploy-wizard` by default)
* Support for publishing configuration and views for customization
* Executes custom Artisan commands after installation

---

## 📦 **Installation**

You can install the package via Composer:

```bash
composer require bijanbiria/laravel-deploy-wizard
```

---

## 🔧 **Configuration**

After installation, you may publish the configuration file to customize the route and final commands:

```bash
php artisan vendor:publish --tag=deploy-wizard-config
```

The configuration file will be published at:
`config/deploywizard.php`

**Default Configuration:**
```php
return [
    'route' => 'deploy-wizard',         // Route for accessing the wizard
    'complete_route' => 'home',         // Route to redirect after installation
    'final_commands' => [
        'php artisan route:cache',      // Cache application routes
        'php artisan migrate --force', // Run migrations
    ],
];
```

---

## 🗂️ **Publishing Views**

If you want to customize the look and feel of the installer, you can publish the views:

```bash
php artisan vendor:publish --tag=deploy-wizard-views
```

The views will be published to:
`resources/views/vendor/deploy-wizard`

You can edit these Blade files to match your UI design.

---

## 🛠️ **Usage**

After installation, you can simply visit the route:

```
http://yourdomain.com/deploy-wizard
```

💡 **Note:** You don't even need to manually visit this page! If the `.env` file is missing and you try to access any route in your application, the wizard will automatically launch and redirect you to the deployment page to complete the setup.

If the `.env` file does not exist, it will be created automatically from `.env.example`. If `APP_KEY` is missing, a new key will be generated, and all migrations and seeders will be executed automatically.

The wizard is structured in 3 main steps:

1. **Step 1:** Application Configuration (App Name, URL, Environment)
2. **Step 2:** Locale Settings (Locale, Fallback Locale, Faker Locale)
3. **Step 3:** Database Configuration (DB Connection, Host, Port, Credentials)

At the end of Step 3, custom Artisan commands defined in the configuration will be executed.

---

## ⚙️ **Custom Route**

You can customize the route in the configuration file:

```php
return [
    'route' => 'install-wizard' // Default is 'deploy-wizard'
];
```

Access it via:
```
http://yourdomain.com/install-wizard
```

---

## ✨ **Final Commands**

You can define your own list of Artisan commands to be executed after the installation process. Simply update the `final_commands` array in `config/deploywizard.php`:

```php
'final_commands' => [
    'php artisan storage:link',
    'php artisan migrate',
    'php artisan db:seed',
    'php artisan view:clear'
]
```

These commands will be executed sequentially once the wizard is complete.

---

## 🎓 **License**

This package is open-sourced software licensed under the **MIT license**.

---

## ✨ **Contributing**

Feel free to submit issues and pull requests. We are open to new ideas!

---

## 👤 **Author**

* **Bijan Biria** - [bijanbiria@gmail.com](mailto:bijanbiria@gmail.com)

## 📰 **Article Link**

* **bijanbiria.com** - [Laravel Deploy Wizard – Revolutionizing Laravel Installation](https://bijanbiria.com/laravel/laravel-deploy-wizard/)
