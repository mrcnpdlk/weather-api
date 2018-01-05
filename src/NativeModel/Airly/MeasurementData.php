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

/**
 * Class MeasurementData
 *
 * @package mrcnpdlk\Weather\NativeModel\Airly
 */
class MeasurementData
{
    /**
     * @var float|null
     */
    public $airQualityIndex;
    /**
     * @var float|null
     */
    public $pm1;
    /**
     * @var float|null
     */
    public $pm25;
    /**
     * @var float|null
     */
    public $pm10;
    /**
     * @var float|null
     */
    public $pressure;
    /**
     * @var float|null
     */
    public $humidity;
    /**
     * @var float|null
     */
    public $temperature;
    /**
     * @var integer|null
     */
    public $pollutionLevel;

    /**
     * MeasurementData constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->airQualityIndex = $oData->airQualityIndex ?? null;
            $this->pm1             = $oData->pm1 ?? null;
            $this->pm25            = $oData->pm25 ?? null;
            $this->pm10            = $oData->pm10 ?? null;
            $this->pressure        = $oData->pressure ?? null;
            $this->humidity        = $oData->humidity ?? null;
            $this->temperature     = $oData->temperature ?? null;
            $this->pollutionLevel  = $oData->pollutionLevel ?? null;
        }
    }
}
