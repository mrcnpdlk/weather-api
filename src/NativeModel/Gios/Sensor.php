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
 * Time: 23:25
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;


class Sensor
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var integer
     */
    public $stationId;
    /**
     * @var string
     */
    public $dateStart;
    /**
     * @var string
     */
    public $dateEnd;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\SensorParam
     */
    public $param;

    /**
     * Sensor constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->id        = $oData->id;
            $this->stationId = $oData->stationId;
            $this->dateStart = $oData->sensorDateStart;
            $this->dateEnd   = $oData->sensorDateEnd;
            $this->param     = new SensorParam($oData->param);
        }
    }
}
