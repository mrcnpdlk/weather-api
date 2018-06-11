# WEATHER API - (not only) Polish weather + air quality

# Contents
1. [Installation](#installation)
2. [Basic usage](#basic-usage)
    1.  [Cache](#cache)
    2.  [Logger](#log)
3. [External sources](#external-sources)
    1.  [Chief Inspectorate of Environmental Protection](#gios)
    2.  [Airly](#airly)
    2.  [Open Weather Map](#owm)
4. [API Usage](#api-usage)
    1. [Create instance](#create-instance)
    1. [Available methods](#methods)
    1. [Examples](#examples)

## Installation

Install the latest version with [composer](https://packagist.org/packages/mrcnpdlk/weather-api)
```bash
composer require mrcnpdlk/weather-api
```

## Basic usage

### Cache
Library supports Cache bundles based on [PSR-16](http://www.php-fig.org/psr/psr-16/) standard.

For below example was used [phpfastcache/phpfastcache](https://github.com/PHPSocialNetwork/phpfastcache) V7.
`phpfastcache/phpfastcache` supports a lot of endpoints, i.e. `Files`, `Sqlite`, `Redis` and many other. 
More information about using cache and configuration it you can find in this [Wiki](https://github.com/PHPSocialNetwork/phpfastcache/wiki). 

```php

    /**
     * Cache in system files
     */
    $oFilesConfig = new \Phpfastcache\Drivers\Files\Config();
    $oFilesConfig->setPath(sys_get_temp_dir())->setDefaultTtl(60);
    $oInstanceCacheFiles = new \Phpfastcache\Helper\Psr16Adapter('files', $oFilesConfig);
    /**
     * Cache in Redis
     */
    $oRedisConfig = new \Phpfastcache\Drivers\Redis\Config();
    $oRedisConfig
        ->setHost('localhost')
        ->setPort(6379)
        ->setDefaultTtl(60)
        ;
    $oInstanceCacheRedis = new \Phpfastcache\Helper\Psr16Adapter('redis', $oRedisConfig);


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
)->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor());
```

## External sources

### GIOS
[GIOS](http://powietrze.gios.gov.pl/pjp/home) (Chief Inspectorate of Environmental Protection - Główny Inspektorat Ochrony Środowiska) provide [API](http://powietrze.gios.gov.pl/pjp/content/api) without any authentication.

### Airly
[Airly](https://airly.eu/en/) builds networks of air quality sensors that can be deployed across entire cities or counties.
If you want to use external data using this API you have to get the free Token key from [here](https://developer.airly.eu/) .

### OWM

[Open Weather Map](http://openweathermap.org/) is well know project for weather and forecast. To use it, get the free Token Key from [here](http://openweathermap.org/appid) .

## API usage

### Create instance
```php
$oClient = new \mrcnpdlk\Weather\Client();
$oClient
    ->setCacheInstance($oInstanceCacheFiles)
    ->setLoggerInstance($oInstanceLogger)
    ->setAirlyConfig('AIRLY_TOKEN')  // not required but recommended
    ->setOWMConfig('OWM_TOKEN')  // not required but recommended
;
$oApi = new \mrcnpdlk\Weather\Api($oClient);
```

By default API uses:
  - current geo location found using public IP of machine where script is run
  - current date of machine where script is run

We are able to change location calling `setLocation()` method.

```php
use mrcnpdlk\Weather\NativeModel\GeoPoint;

$oApi->setLocation(new GeoPoint(51.758158,19.544377));
```

### Methods
| Name | Description |Uses external sources? |
| -------- | -------- |-------- |
| getAddress()   | Getting address using revers geocode|no   |
| getSunSchedule()   | Getting surise, sunset, twilight timing etc|no   |
| getNearestGiosStation()   | Getting nearest GIOS station|no   |
| getUVIndex()   | UV Index|yes, OWM   |
| getWeather()   | weather|yes, OWM   |

### Examples

#### getAddress()
```
mrcnpdlk\Weather\Model\Address Object
(
    [countryCode] => pl
    [province] => łódzkie
    [commune] => Łódź
    [place] => Łódź
    [neighbourhood] => Katedralna
    [street] => Wincentego Tymienieckiego
    [homeNr] => 16
    [postalCode] => 90-365
)
```

#### getSunSchedule()
Method can give inappropriate results for location near pole.
```
mrcnpdlk\Weather\Model\SunSchedule Object
(
    [sunrise] => 07:46:57
    [sunset] => 15:50:12
    [dayDuration] => 8:03
    [transit] => 11:48:34
    [civilTwilightBegin] => 07:07:27
    [civilTwilightEnd] => 16:29:42
    [nauticalTwilightBegin] => 06:24:41
    [nauticalTwilightEnd] => 17:12:27
    [astronomicalTwilightBegin] => 05:44:05
    [astronomicalTwilightEnd] => 17:53:03
)
```
#### getUVIndex()
```
0.43
```
#### getNearestGiosStation()
```
mrcnpdlk\Weather\NativeModel\Gios\Station Object
(
    [id] => 10058
    [stationName] => Łódź-Jana Pawła II 15
    [dateStart] => 2015-10-27 00:00:00
    [dateEnd] =>
    [gegrLat] => 51.754613
    [gegrLon] => 19.434925
    [addressStreet] => al. Jana Pawła II 15
    [city] => mrcnpdlk\Weather\NativeModel\Gios\City Object
        (
            [id] => 516
            [name] => Łódź
            [commune] => mrcnpdlk\Weather\NativeModel\Gios\Commune Object
                (
                    [communeName] => Łódź
                    [districtName] => Łódź
                    [provinceName] => ŁÓDZKIE
                )

        )

    [distance] => 2253.495
)
```
`mrcnpdlk\Weather\NativeModel\Gios\Station` has implemented two methods:

| Name | Description |
| -------- | -------- |
| getAirQualityIndex()   | Return air quality index  |
| getSensors()   | Return list of available sensors for station  |
For each sensor `getData()` method return list of historical measurement data.


## Native API






