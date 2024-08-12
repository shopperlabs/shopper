# Two Factor Authenticator
Secure your account with two-step authentication

Two-step authentication (also known as two-factor authentication or multifactor authentication) provides a more secure login process. When you attempt to sign in, you need to complete two separate steps:

1. Enter the account password.
2. Authenticate through a mobile app security key.

These two steps will make much more difficult for an unauthorized person to access your account. Even if they are able to find your password, they will not be able to connect without the second step.

Authentication in two secure steps rests on the combination of two factors, which can be something you know (such as your combination of connection and password), something you have (such as a code to Only one use provided by an authentication application or SMS) or something you are (providing biometric authentication, such as a fingerprint).

Two-step authentication can be configured for all accounts, but the store owner can not activate it for staff. The staff must put it in place for his own accounts. If you have multiple employees who manage your shop.

## Enabling Two-Factor Authentication
To enable two-step authentication, you'll need first to download an authenticator app to your mobile device. The app will be able to scan QR codes and retrieve authentication data for you. Recommended authenticator apps:

- [Google Authenticator](https://support.google.com/accounts/answer/1066447)
- [Duo Mobile](https://guide.duo.com/third-party-accounts)
- [Amazon AWS MFA](https://aws.amazon.com/iam/details/mfa)
- [Authenticator](https://www.microsoft.com/store/p/microsoft-authenticator/9nblgggzmcj6)

In addition, you should store the listed recovery codes in a secure password manager such as [1Password](https://1password.com).

When you install an authenticator app, make sure that you follow its instructions carefully. After your app is successfully downloaded and set up, you can activate the feature in Shopper.

From your administrator interface, click on your name with account picture in the upper right corner. Next click on **Personal Account**

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/account-dropdown.png" alt="Account dropdown">
    <div class="caption">Account Dropdown</div>
</div>

Scroll to the two factor authenticate section on the screen, click **Enable authentication**. This action will trigger a modal to ask you to confirm your password

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/two-factor-section.png" alt="Two factor section Screenshot">
    <div class="caption">Two factor section</div>
</div>

Enter your current password in the space provided and click **Enable**.

If the user loses access to their mobile device, the login page will allow them to authenticate using one of their recovery codes instead of the temporary token provided by their mobile device's authenticator application.

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/two-factor-code.png" alt="Two factor code Screenshot">
    <div class="caption">Two factor QRcode & Recovery Code</div>
</div>

This feature is inspired by [Laravel Fortify](https://laravel.com/docs/9.x/fortify) which is implemented in [Laravel Jetstream](https://jetstream.laravel.com/2.x/introduction.html)

Now when you try to log in, two-factor authentication will require your mobile device.

## Logging in with Two-Factor Authentication

You will go to the Shopper administration login page. You will enter your email address and password and click on the **Login** button. On the next page, you need to authenticate using the method you've used to set up two-factor authentication.

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/auth-two-factor-authentication.png" alt="Two factor login Screenshot">
    <div class="caption">Two factor Login</div>
</div>

If you used a two-factor authentication app, open it and retrieve the code you will be given and click on the login button

If you have rather copied the recovery codes in an application like 1Password you will have to recover a code, enter it to connect to your store.

## Disable Two-Factor Authentication

From your administrator interface, click on your name and your account photo in the upper right corner and click on Personnal Account menu.

In the Two-factor authentication section, use the Disable button for the authentication method you want to deactivate. This will ask you for a password confirmation, you enter your password and click on confirm to completely deactivate the Two-factor authentication.

<div class="screenshot">
    <img src="/img/screenshots/{{version}}/two-factor-disable.png" alt="Two factor disable Screenshot">
    <div class="caption">Two factor disable</div>
</div>
