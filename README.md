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

## Support:
mdixon14717@gmail.com

## License:
The MIT License (MIT). Please see [License File](LICENSE) for more information.
