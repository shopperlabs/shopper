# Livewire Starter Kit

<img src="/screenshots/{{version}}/livewire-starterkit.png" class="simple-screenshoot" alt="Livewire Starter kits">

## Prerequisites

Before following along with this guide, make sure you have the following requirements:

- PHP 8.2
- MySQL 8.0+

## Installation

In your Shopper app project, after installing the [Starter kit](/docs/starter-kits#installation), you need to run the command:

```shell
php artisan shopper:starter-kit:install livewire
```

This command publishes views, routes, controllers, and other resources to your application. The starter kits publishes all of its code to your 
application so that you have full control and visibility over its features and implementation.

## Structure

When you install the Laravel Shopper Livewire Starter Kit, the following directories and files are published to your application. 
This structure provides simplest foundation for building your e-commerce storefront while giving you full control over customization.

### Actions

This directory (`app/Actions`) contains action classes that handle specific business logic, such as cart management, or order processing. 
These actions are designed to be reusable and modular. Here the published files :

- `CountriesWithZone.php`
- `CreateOrder.php`
- `GetCountriesByZone.php`
- `ZoneSessionManager.php`
- `Payment/PayWithCash.php`

### Contracts

The `Contracts` directory defines interfaces for key functionalities, such as commands or services. These contracts allow you to implement your own logic while adhering to a consistent structure.

### DTO

The DTO (Data Transfer Objects) folder includes classes that encapsulate data structures used across the application. 
These objects ensure clean and consistent data handling. Here the published files :

- `AddressData.php`
- `CountryByZoneData.php`
- `OptionData.php`
- `PriceData.php`

### Middleware

The Middleware directory includes custom `ZoneDetector` middleware for handling selected zone for the customer.

### Models

In your models folder, some models will be added (or replaced if you had models with the same name), such as : 

- `User.php`
- `Brand.php`
- `Category.php`
- `Channel.php`
- `Collection.php`
- `Product.php`
- `ProductVariant.php`

The models copied are to match the configuration of the Shopper models (inside your `config/shopper/models.php` files) that were copied with this installation.

### Pages

**Livewire Pages Components**
- `Account/Orders.php`
- `Account/Addresses.php`
- `Home.php`
- `SingleProduct.php`
- `Checkout.php`

**Volt Pages Components**
- `pages.order.confirmed`
- `pages.account.index`
- `pages.account.profile`
- `pages.account.orders.detail`

### Views and Components

...
