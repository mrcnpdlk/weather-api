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
 */

/**
 * Created by Marcin.
 * Date: 03.01.2018
 * Time: 22:08
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;


use mrcnpdlk\Weather\NativeModel\GeoPoint;

class Station
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $stationName;
    /**
     * @var string
     */
    public $dateStart;
    /**
     * @var string|null
     */
    public $dateEnd;
    /**
     * @var float
     */
    public $gegrLat;
    /**
     * @var float
     */
    public $gegrLon;
    /**
     * @var string|null
     */
    public $addressStreet;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\City|null
     */
    public $city;
    /**
     * @var float|null
     */
    public $distance;

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getLocation(): GeoPoint
    {
        return new GeoPoint($this->gegrLat, $this->gegrLon);
    }

}
