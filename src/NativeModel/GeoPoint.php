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
 * Time: 22:15
 */

namespace mrcnpdlk\Weather\NativeModel;


use Location\Coordinate;
use Location\Distance\Vincenty;

class GeoPoint
{
    /**
     * @var float
     */
    public $lat;
    /**
     * @var float
     */
    public $lon;

    /**
     * GeoPoint constructor.
     *
     * @param float $lat
     * @param float $lon
     */
    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $refPoint
     *
     * @return float Distance in meters between points
     */
    public function getDistance(GeoPoint $refPoint): float
    {
        try {
            $oSelfPoint = new Coordinate($this->lat, $this->lon);
            $oRefPoint  = new Coordinate($refPoint->lat, $refPoint->lon);
            $calculator = new Vincenty();

            return $calculator->getDistance($oSelfPoint, $oRefPoint);
        } catch (\Exception $e) {
            
        }
    }
}
