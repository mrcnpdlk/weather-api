<?php
/**
 * Created by Marcin.
 * Date: 05.01.2018
 * Time: 19:05
 */

namespace mrcnpdlk\Weather\NativeModel\OWM;


use Carbon\Carbon;

/**
 * Class WeatherResponse
 *
 * @package mrcnpdlk\Weather\NativeModel\OWM
 */
class WeatherResponse
{
    /**
     * City identification
     *
     * @var integer
     */
    public $id;
    /**
     * Data receiving time
     *
     * @var string
     */
    public $dt;
    /**
     * City name
     *
     * @var string
     */
    public $name;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public $coord;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\OWM\Param\Sys
     */
    public $sys;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\OWM\Param\Main
     */
    public $main;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\OWM\Param\Wind
     */
    public $wind;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\OWM\Param\Clouds
     */
    public $clouds;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\OWM\Param\Weather[]
     */
    public $weather;
    /**
     * @var string
     */
    public $base;
    /**
     * @var integer
     */
    public $visibility;
    /**
     * @var integer
     */
    public $cod;

    /**
     * Time converting
     *
     * @param int|null $time
     *
     * @return $this
     */
    public function setDt(int $time = null)
    {
        $this->dt = $time ? Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s') : null;

        return $this;
    }
}
