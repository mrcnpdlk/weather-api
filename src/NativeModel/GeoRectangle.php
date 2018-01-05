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

namespace mrcnpdlk\Weather\NativeModel;


use Location\Polygon;

class GeoRectangle
{
    /**
     * @var float
     */
    public $latMin;
    /**
     * @var float
     */
    public $latMax;
    /**
     * @var float
     */
    public $lonMin;
    /**
     * @var float
     */
    public $lonMax;

    /**
     * GeoRectangle constructor.
     *
     * @param float $latMin
     * @param float $latMax
     * @param float $lonMin
     * @param float $lonMax
     */
    public function __construct(float $latMin, float $latMax, float $lonMin, float $lonMax)
    {
        $this->latMin = $latMin;
        $this->latMax = $latMax;
        $this->lonMin = $lonMin;
        $this->lonMax = $lonMax;
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getCenter(): GeoPoint
    {
        return new GeoPoint(($this->latMin + $this->latMax) / 2, ($this->lonMin + $this->lonMax) / 2);
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getNE(): GeoPoint
    {
        return new GeoPoint($this->latMax, $this->lonMax);
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getNW(): GeoPoint
    {
        return new GeoPoint($this->latMax, $this->lonMin);
    }

    /**
     * @return \Location\Polygon
     */
    public function getPolygon(): Polygon
    {
        $oPolygon = new Polygon();
        $oPolygon->addPoint($this->getNE()->getCoordinate());
        $oPolygon->addPoint($this->getNW()->getCoordinate());
        $oPolygon->addPoint($this->getSW()->getCoordinate());
        $oPolygon->addPoint($this->getSE()->getCoordinate());

        return $oPolygon;
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getSE(): GeoPoint
    {
        return new GeoPoint($this->latMin, $this->lonMax);
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getSW(): GeoPoint
    {
        return new GeoPoint($this->latMin, $this->lonMin);
    }

}
