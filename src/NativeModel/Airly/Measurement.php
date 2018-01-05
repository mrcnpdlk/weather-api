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
     * Measurement constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->fromDateTime = isset($oData->fromDateTime) ? Carbon::parse($oData->fromDateTime)->format('Y-m-d H:i:s') : null;
            $this->tillDateTime = isset($oData->tillDateTime) ? Carbon::parse($oData->tillDateTime)->format('Y-m-d H:i:s') : null;
            $this->measurements = new MeasurementData($oData->measurements);
        }
    }
}
