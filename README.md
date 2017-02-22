VatsimSSO for Laravel
=========

**Version:** 3.0

The VatsimSSO package integrates with the VATSIM.net Single Sign On, which lets your users log themselves in using their VATSIM ID. This is especially useful for official vACCs and ARTCCs.

This package supports both Laravel 4 and 5.

Installation
--------------

Use [Composer](http://getcomposer.org) to install the VatsimSSO and dependencies.

```sh
$ composer require vatsim/sso-laravel 3.*
```

### Set up

Add the provider and facade to your `app.php` config file (Laravel 4: `app/config/app.php`, Laravel 5: `config/app.php`).

- **Provider:** `Vatsim\OAuthLaravel\OAuthServiceProvider`
- **Facade:** `Vatsim\OAuthLaravel\SSOFacade`

### Configuration file
Use artisan to publish the configuration file. Change the settings accordingly.
```sh
# Laravel 4 --> app/config/packages/vatsim/sso-laravel/config.php
$ artisan config:publish vatsim/sso-laravel

# Laravel 5 --> config/vatsim-sso.php
$ artisan vendor:publish --provider="Vatsim\OAuthLaravel\OAuthServiceProvider"
```
It is __strongly__ recommended you use Laravel's built-in support for environment files to protect sensitive data. Additional details can be found in the comments of the config file.

## Usage
### Logging In
The first step would be to send a request to VATSIM to let the user login. The easiest approach would be using the `login` function. The function takes three parameters.
#### Parameters
| Parameter       | Type   | Description |
| --------------- | ------ | ----------- |
| `$returnUrl`    | string | The URL to which the user should be redirected after the login is successful |
| `$success`      | Closure | Callback function containing the actions needed to be done when you are able to let the user authenticate (ie. when your key/secret are correct). The function will return three variables: `$key`, `$secret` and `$url`. |
| *`$error`*      | Closure | *Default: null* – Callback function for error handling. The function will provide one argument: an instance of `VATSIM\OAuth\SSOException`. If no callback is provided, the `SSOException` will be thrown. |

#### Success
The success parameter provides three arguments: `$key`, `$secret` and `$url`. The `key` and `secret` should be stored in a session for the validation process. The `url` will be used to redirect the user to the VATSIM SSO site.

#### Error
Optional parameter. If this parameter is ignored and an error occurs, a `SSOException` will be thrown. If you pass a function then one parameter will be returned `$error`, which is the instance of `SSOException`.

#### Example
```php
$returnUrl = '...'; // load URL from config

return VatsimSSO::login(
    $returnUrl,
    function($key, $secret, $url) {
        Session::put('vatsimauth', compact('key', 'secret'));
        return Redirect::to($url);
    },
    function($e) {
        throw $e; // Do something with the exception
    }
);
```

If you prefer not to use the `->login()` function, you may use `->requestToken($returnUrl)`. This will return an object containing the `key` and `secret` or throw `VATSIM\OAuth\SSOException` if an error occurs. Then use `->redirectUrl()` to get the URL for the redirect.

### Validating login
After the login has been successful, we need to get the user data from VATSIM. Also for this we wrote a function to make it easier for you.
#### Parameters
| Parameter       | Type   | Description |
| --------------- | ------ | ----------- |
| `$key`          | string | The `key` stored in the session at login |
| `$secret`       | string | The `secret` stored in the session at login |
| `$verifier`     | string | The `oauth_verifier` passed in the query string |
| `$success`      | Closure | Callback function containing the actions needed to be done when the login has been successful. |
| *`$error`*      | Closure | *Default: null* – Callback function for error handling (could be because of wrong key/secret/verifier). The function will provide one argument: an instance of `VATSIM\OAuth\SSOException`. If no callback is provided, the `SSOException` will be thrown. |

#### Success
The success parameter returns two variables: `$user` and `$request`. The `user` variable will be an object containing all user data available to your organisation. The `request` variable will give you information about the request.

#### Error
Optional parameter. If this parameter is ignored and an error occurs, a `SSOException` will be thrown. If you pass a function then one parameter will be returned `$error`, which is the instance of `SSOException`.

#### Example
```php
$session = Session::get('vatsimauth');

return VatsimSSO::validate(
    $session['key'],
    $session['secret'],
    Input::get('oauth_verifier'),
    function($user, $request) {
        // At this point we can remove the session data.
        Session::forget('vatsimauth');
        
        Auth::loginUsingId($user->id);
        return Redirect::home();
    },
    function($error) {
        throw $e; // Do something with the exception
    }
);
```

If you prefer not to use the `->validate()` function, you may use `->checkLogin($key, $secret, $verifier)`. This will return an object containing the `user` and `request` objects or throw `VATSIM\OAuth\SSOException` if an error occurs.


License
----

MIT

**Free Software, Hell Yeah!**
