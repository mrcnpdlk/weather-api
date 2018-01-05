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

/**
 * Class Measurement
 *
 * @package mrcnpdlk\Weather\NativeModel\Airly
 */
class Measurement
{
    /**
     * @var string
     */
    public $fromDateTime;
    /**
     * @var string
     */
    public $tillDateTime;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Airly\MeasurementData
     */
    public $measurements;

    /**
     * @param string $time
     */
    public function setFromDateTime(string $time)
    {
        $this->fromDateTime = $time ? Carbon::parse($time)->format('Y-m-d H:i:s') : null;
    }

    /**
     * @param string $time
     */
    public function setTillDateTime(string $time)
    {
        $this->tillDateTime = $time ? Carbon::parse($time)->format('Y-m-d H:i:s') : null;
    }
}
