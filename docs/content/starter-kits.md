# Starter Kits

## Introduction

Shopper is a powerful, headless e-commerce framework designed to give developers the flexibility to build tailored online shopping experiences.
By default, Shopper comes without a frontend, allowing you to choose your preferred stack—whether it's React, Vue, Svelte, or any other technology.
This headless approach ensures that you have complete control over the user interface and can create a truly unique storefront.

However, if you're looking for a quick start or a bit of guidance, Shopper offers a **Starter Kit** inspired by [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze).
This kit provides a simple, pre-configured frontend setup to help you hit the ground running. Whether you're building a custom solution
or leveraging the starter kit, Shopper empowers you to deliver e-commerce experiences with ease.

<img src="/screenshots/{{version}}/starter-kits.png" class="simple-screenshoot" alt="Starter kits">

## Quickstart

To help you get started quickly and efficiently, Laravel Shopper offers three starter kits, each built on the same stacks as Laravel Breeze:
[Blade](https://laravel.com/docs/blade), [Livewire](https://livewire.laravel.com/), and [Inertia](https://inertiajs.com/). These kits are designed
to streamline your development process while giving you the flexibility to choose the stack that best suits your project needs.

### Livewire

For developers who love the interactivity of modern web applications but want to stay within the Laravel ecosystem, the [Livewire Starter Kit](/docs/livewire-starter-kit) is
the way to go. [Livewire](https://livewire.laravel.com/) allows you to build dynamic, reactive interfaces using PHP and Blade, eliminating the need
for complex JavaScript setups. This kit is perfect for those who want a seamless, full-stack Laravel experience.

What makes this kit even more powerful is that **Shopper itself is built with Livewire**. This means you can save significant development time,
as many of the components you might need can be already available and customizable within the framework. Shopper is designed as a **Building Block Components**,
giving you access to all its components and the freedom to extends or replace them to fit your specific requirements.

### Blade (soon)

The [Blade Starter Kit](/docs/blade-starter-kit) is perfect for developers who prefer a traditional server-rendered approach. Built with Laravel's powerful
Blade templating engine, this kit provides a simple and intuitive way to create your e-commerce storefront. It’s ideal for those
who want a lightweight setup without the complexity of frontend frameworks. **Stay tuned—this kit will be available soon!**

### Inertia (soon)

If you prefer a modern, single-page application (SPA) approach, the [Inertia Starter Kit](/docs/inertia-starter-kit) is your best choice. Built with [Inertia.js](https://inertiajs.com/),
this kit allows you to use popular frontend frameworks like [React](https://react.dev/) or [Vue.js](https://vuejs.org/) while still leveraging Laravel’s backend capabilities.
It’s ideal for developers who want a smooth, app-like user experience with minimal setup. **This kit will also be available soon!**

### Why Choose a Starter Kit?

These starter kits are designed to save you time and effort by providing a solid foundation for your Shopper store. Whether you’re building
a simple storefront or a complex e-commerce platform, these kits give you the flexibility to start with a stack you’re comfortable with—or
explore new ones. And since Shopper is headless by default, you can always customize or replace the frontend as your project evolves.

## Prerequisites

For each starter kit there will be prerequisites that will be shared, but in the documentation for each starter kit, other more
specific prerequisites will be added to get the project off the ground.

- PHP 8.2+
- Laravel 10+
- MySQL 8.0+ / MariaDB 10.2+ / PostgreSQL 9.4+
- All [Requirements](/docs/requirements) need by Shopper to work

## Installation

The Starter Kit requires a Shopper backend to be installed and running to work. Laravel Shopper is a headless e-commerce framework, 
so storefronts connect to it to provide commerce features to customers. This setup allows you to build a fully customizable frontend while 
leveraging the powerful backend capabilities of Laravel Shopper.

```shell
composer require shopper/starter-kits --dev
```

After installing the Starter kits package, you may execute the Artisan command. This command accepts the name of the stack you prefer 
(`livewire`, `blade (soon)` or `inertia (soon)`)

```shell
php artisan shopper:starter-kit:install
```

This command will install composer and npm dependencies needed to run the starter kit, and publish the views, routes, controllers and other resources required by your application.

:::warning
**New Applications Only**

Starter Kits should only be installed into new Shopper store app. When you install a starter kit, its files and configurations will be copied 
directly into your project. If you attempt to install a starter kit into an existing Laravel application, there is a risk that files with the 
same names (e.g., controllers, views, or configuration files) may be overwritten, leading to unexpected behavior or data loss. To avoid these 
issues, we strongly recommend using the starter kits only with new projects.
:::

## Features

The Laravel Shopper Starter Kit provides a foundation for building modern e-commerce storefronts. Here are some of the key features


### Collections

Your store's [Manual Collections](/docs/collections#overview) are showcased on the homepage. If you don't see any collections, make sure to [create collections](/docs/collections#create-collection).

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-collections.png" alt="Collections">
  <div class="caption">Home collections</div>
</div>

### Latest Products

Your store's products are showcased on the homepage. If you don't see any products, make sure to [add products](/docs/products#create-product) in your store.

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-products.png" alt="home product">
  <div class="caption">Home products</div>
</div>

- Browse all products
- View detailed product information, including images, descriptions, and pricing.

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-product-view.png" alt="detail product">
  <div class="caption">Detail product</div>
</div>

### Authentication

The entire authentication system is built directly into the Laravel Shopper Starter Kit, so you don’t need to install [Laravel Breeze](https://github.com/laravel/breeze) separately. 
The starter kit is inspired by `Breeze`, providing the authentication system, including user registration, login, password reset, and account management.

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-auth.png" alt="Login">
  <div class="caption">Login screen</div>
</div>

### Sales Zone

In Shopper, sales [zones](/docs/zones) are optional but recommended for an enhanced sales experience. By default, the system works without zones, 
but defining one allows users to select their preferred zone.

To enable zone selection:
* Create and activate a zone in the Shopper admin panel.
* Set it as the default zone using the `SHOPPER_DEFAULT_ZONE` environment variable in your `.env` file.

After this modification, in the footer you will see the selection button for the delivery zone.

<img src="/screenshots/{{version}}/starterkit-enable-zone.png" class="simple-screenshoot" alt="selection zone">

When you click on it, you'll see a list of available zones, depending on what you've saved in your admin panel.

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-zones.png" alt="Login">
  <div class="caption">Zones screen</div>
</div>

### Profile Management

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-account.png" alt="account">
  <div class="caption">Account screen</div>
</div>

**View order history and details.**
<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-orders.png" alt="account">
  <div class="caption">Orders screen</div>
</div>

**Manage billing & shipping addresses**
<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-addresses.png" alt="addresses">
  <div class="caption">Addresses screen</div>
</div>

### Cart Management

- Add and remove products from the cart.
- Adjust quantities of items in the cart.
- Real-time cart updates and calculations.

<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-cart.png" alt="cart">
  <div class="caption">Cart screen</div>
</div>

### Checkout process

**Billing & Shipping address delivery**
<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-checkout-addresses.png" alt="Checkout">
  <div class="caption">Checkout addresses</div>
</div>

**Shipping method & calculations**
<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-checkout-shipping-method.png" alt="Checkout">
  <div class="caption">Checkout Shipping</div>
</div>

**Support for multiple payment gateways**
<div class="screenshot">
  <img src="/screenshots/{{version}}/starterkit-checkout-payment.png" alt="Checkout">
  <div class="caption">Checkout payment</div>
</div>

## Customization & Optimization

- Pre-built, customizable components for rapid development.
- Responsive design for seamless mobile and desktop experiences.
- Easy integration with your preferred frontend stack (Blade, Livewire, or Inertia).
- Caching and optimization tools for better scalability.

