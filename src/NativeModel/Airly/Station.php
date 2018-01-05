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


namespace mrcnpdlk\Weather\NativeModel\Airly;


use Carbon\Carbon;
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
    public $name;
    /**
     * @var string
     */
    public $vendor;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\Location
     */
    public $location;
    /**
     * @var float|null
     */
    public $distance;
    /**
     * @var integer|null
     */
    public $airQualityIndex;
    /**
     * @var integer|null
     */
    public $pollutionLevel;
    /**
     * @var integer
     */
    public $pm10;
    /**
     * @var integer
     */
    public $pm25;
    /**
     * @var string
     */
    public $measurementTime;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\Address
     */
    public $address;

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getLocation(): GeoPoint
    {
        return new GeoPoint($this->location->latitude, $this->location->longitude);
    }

    /**
     * @param string $time
     */
    public function setMeasurementTime(string $time)
    {
        $this->measurementTime = $time ? Carbon::parse($time)->format('Y-m-d H:i:s') : null;
    }
}
