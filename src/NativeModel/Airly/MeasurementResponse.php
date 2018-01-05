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
 * Class MeasurementResponse
 *
 * @package mrcnpdlk\Weather\NativeModel\Airly
 */
class MeasurementResponse
{
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\MeasurementData
     */
    public $currentMeasurements;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\Measurement[]
     */
    public $history = [];
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\Measurement[]
     */
    public $forecast;

}
