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
     * Measurement constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->currentMeasurements = $oData->currentMeasurements ?? null;
            foreach ($oData->history ?? [] as $item) {
                $this->history[] = new Measurement($item);
            }
        }
    }
}
