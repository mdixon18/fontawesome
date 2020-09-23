# FontAwesome Icons
A Laravel Nova FontAwesome Icon field

## Installation:

You can install the package in to a Laravel app that uses Nova via composer:

```bash
composer require mdixon18/fontawesome
```

## Usage:
Add the below to app/Nova resources.

```php
  use Mdixon18\Fontawesome\Fontawesome;

  Fontawesome::make('Icon')
```

You can override the text for the field button like so

```php
  Fontawesome::make('Icon')->addButtonText('Click Me!')
```

You can set a default icon for when an icon has not been set like so. First parameter is the type e.g. far, fas, fab and the second is the icon name (without fa-)

```php
  Fontawesome::make('Icon')->defaultIcon('far', 'check-circle')
```

If you want to persist the default icon (when they press clear it brings back the default so it can't be empty) then add the following:

```php
  Fontawesome::make('Icon')->addButtonText('Click Me!')->defaultIcon('far', 'check-circle')->persistDefaultIcon()
```

You can limit the icons the user can choose from like so
```php
  Fontawesome::make('Icon')->only([
    'facebook',
    'twitch',
    'twitter',
  ])
```

You can use FontAwesome Pro by doing the following (remember to get your license key!)
```php
  Fontawesome::make('Icon')->pro()
```

## Support:
mdixon14717@gmail.com

## License:
The MIT License (MIT). Please see [License File](LICENSE) for more information.
