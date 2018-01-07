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


use mrcnpdlk\Weather\NativeGiosApi;

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
    public $sensorDateStart;
    /**
     * @var string|null
     */
    public $sensorDateEnd;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\SensorParam
     */
    public $param;

    /**
     * @return \mrcnpdlk\Weather\NativeModel\Gios\Data
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getData()
    {
        return NativeGiosApi::getInstance()->getSensorData($this->id);
    }

}
