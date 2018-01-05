<?php
/**
 * WEATHER-API
 *
 * Copyright © 2018 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 *
 */

/**
 * Created by Marcin.
 * Date: 04.01.2018
 * Time: 23:17
 */

namespace mrcnpdlk\Weather;


use mrcnpdlk\Weather\NativeModel\GeoPoint;

class Api
{
    public function __construct(Client $oClient)
    {
    }

    public static function getGeocodeFromGoogle($location)
    {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($location) . '&sensor=false';
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($ch));
    }

    public function setLocation(GeoPoint $oGeoPoint)
    {

    }

    public function setLocationLazy()
    {

    }
}
