# Release Notes

## v0.0.7 (2021-10-09)

### Updated
- Dark mode on product reviews

### Fixed
- Bug on product association with collections and categories
- Bug on product edit `type` attribute
- Bug on display selected product with image when create and update discount

## v0.0.6 (2021-09-30)

### Added
- Add [Spatie/media-library](https://spatie.be/docs/laravel-medialibrary/v9/introduction) in place of `File` to save files in database
- Add Filepond to upload files
- Multiple upload files on product create and edit component
- Publish Spatie media library package on shopper installation

### Updated
- Use `uploads` filesystem as collections name to save products, category and brand files.
- Method to retrieve product (or category, collection) first image.

### Fixed
- Livewire upload error on update product
- Variant update stock

### Removed
- `File` Class for managing file. No longer used
- phpcs
- phpinsights

## v0.0.5 (2021-08-30)

### Added
- Livewire Trix component
- Multiselect with **Choices.js** input to create and update product

### Updated
- AlpineJS from V2 to V3
- improve user experience

### Fixed
- Fix error on create product (issue [#62](https://github.com/shopperlabs/framework/issues/62))
- Fix bug on create new admin user
- Fix bug on User Model fillable attributes

## v0.0.4 (2021-08-25)

### Added
- Add booted method to `Filetable` Trait to remove all assets when delete a model
- Seo custom Attribute array to Categories, Collections, Brands & Products Livewire Forms
- Seo Form on categories and brands livewire components

### Updated
- Attribute type on Livewire Create and Edit component.
- UI improved

### Fixed
- Typo error for instance on Discounts Livewire component

## v0.0.3 (2021-08-04)

### Update
- Fix error on `TwoFactorLoginRequest` when user try to login

## v0.0.2 (2021-07-22)

### Update
- Laravel Livewire to v2.5
- Laravel UI Modal to v1.0.0

### Fixed
- Installation version error on guzzle
- Fix error on Shopper prefix `Shopper::prefix()` return value before installation of the package ([#54](https://github.com/shopperlabs/framework/pull/54))
- Fix error on publishing for vendor files
- Fix error on `LaravelUIModal() not defined`
- Fix error on mapbox configuration when setting up your store for the first time

## v0.0.1 (2021-07-07)

Initial release.
