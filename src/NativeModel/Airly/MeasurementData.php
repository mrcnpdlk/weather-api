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
}
