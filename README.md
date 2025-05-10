# Laravel Deploy Wizard

[![Latest Stable Version](https://poser.pugx.org/bijanbiria/laravel-deploy-wizard/v/stable)](https://packagist.org/packages/bijanbiria/laravel-deploy-wizard)
[![License](https://poser.pugx.org/bijanbiria/laravel-deploy-wizard/license)](https://packagist.org/packages/bijanbiria/laravel-deploy-wizard)

Laravel Deploy Wizard is a Laravel Installer similar to WordPress installation process. It allows developers to easily configure and deploy Laravel applications with a user-friendly wizard.

---

## 🚀 Features

* Interactive setup wizard
* Auto .env file generation
* Database connection validation
* Route configuration for installation
* Easy to extend and configure

---

## 📦 Installation

You can install the package via Composer:

```bash
composer require bijanbiria/laravel-deploy-wizard
```

---

## 🔧 Configuration

Publish the configuration file if you want to customize the installation route:

```bash
php artisan vendor:publish --provider="Bijanbiria\LaravelDeployWizard\Providers\DeployWizardProvider"
```

The config file will be published at:
`config/deployWizard.php`

---

## 🛠️ Usage

After installation, simply visit the route:

```
http://yourdomain.com/deploy-wizard
```

If the `.env` file does not exist or the database connection is not configured, the wizard will be launched automatically.

---

## ⚙️ Custom Route

You can customize the route in the configuration file:

```php
return [
    'route' => 'install-wizard' // Default is 'deploy-wizard'
];
```

---

## 🎓 License

This package is open-sourced software licensed under the **MIT license**.

---

## ✨ Contributing

Feel free to submit issues and pull requests. We are open to new ideas!

---

## 👤 Author

* **Bijan Biria** - [bijanbiria@gmail.com](mailto:bijanbiria@gmail.com)
