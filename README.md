# WEATHER API - Polish weather + air quality

## Installation

Install the latest version with [composer](https://packagist.org/packages/mrcnpdlk/weather-api)
```bash
composer require mrcnpdlk/weather-api
```

## Basic usage

### Cache
Library supports Cache bundles based on [PSR-16](http://www.php-fig.org/psr/psr-16/) standard.

For below example was used [phpfastcache/phpfastcache](https://github.com/PHPSocialNetwork/phpfastcache).
`phpfastcache/phpfastcache` supports a lot of endpoints, i.e. `Files`, `Sqlite`, `Redis` and many other. 
More information about using cache and configuration it you can find in this [Wiki](https://github.com/PHPSocialNetwork/phpfastcache/wiki). 

```php

    /**
     * Cache in system files
     */
    $oInstanceCacheFiles = new \phpFastCache\Helper\Psr16Adapter(
        'files',
        [
            'defaultTtl' => 3600 * 24, // 24h
            'path'       => sys_get_temp_dir(),
        ]);
    /**
     * Cache in Redis
     */
    $oInstanceCacheRedis = new \phpFastCache\Helper\Psr16Adapter(
        'redis',
        [
            "host"                => null, // default localhost
            "port"                => null, // default 6379
            'defaultTtl'          => 3600 * 24, // 24h
            'ignoreSymfonyNotice' => true,
        ]);

```

### Log

Library also supports logging packages based on [PSR-3](http://www.php-fig.org/psr/psr-3/) standard, i.e. very popular
[monolog/monolog](https://github.com/Seldaek/monolog).

```php

$oInstanceLogger = new \Monolog\Logger('name_of_my_logger');
$oInstanceLogger->pushHandler(new \Monolog\Handler\ErrorLogHandler(
        \Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM,
        \Psr\Log\LogLevel::DEBUG
    )
);

```

### GIOS Authentication
GIOS (Główny Inspektorat Ochrony Środowiska) provide [API](http://powietrze.gios.gov.pl/pjp/content/api) without any authentication.

## Example methods

