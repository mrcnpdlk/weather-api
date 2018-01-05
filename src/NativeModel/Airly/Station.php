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
     * @var \mrcnpdlk\Weather\NativeModel\GeoPoint
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
     * Station constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->id              = $oData->id;
            $this->name            = $oData->name;
            $this->vendor          = $oData->vendor;
            $this->location        = new GeoPoint($oData->location->latitude, $oData->location->longitude);
            $this->distance        = $oData->distance ?? null;
            $this->airQualityIndex = $oData->airQualityIndex ?? null;
            $this->pollutionLevel  = $oData->pollutionLevel ?? null;
            $this->pm10            = $oData->pm10 ?? null;
            $this->pm25            = $oData->pm25 ?? null;
            $this->measurementTime = isset($oData->measurementTime) ? Carbon::parse($oData->measurementTime)->format('Y-m-d H:i:s') : null;
            $this->address         = new Address($oData->address);
        }
    }
}
