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
use Location\Ellipsoid;
use mrcnpdlk\Weather\Exception;

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
     * @return \Location\Coordinate
     */
    public function getCoordinate(): Coordinate
    {
        return new Coordinate($this->lat, $this->lon);
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $refPoint
     *
     * @return float Distance in meters between points
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getDistance(GeoPoint $refPoint): float
    {
        try {
            $oRefPoint  = new Coordinate($refPoint->lat, $refPoint->lon);
            $calculator = new Vincenty();

            return $calculator->getDistance($this->getCoordinate(), $oRefPoint);
        } catch (\Exception $e) {
            throw new Exception('Cannot calculate distance', 1, $e);
        }
    }

    /**
     * @param int      $width
     * @param int|null $height
     *
     * @return GeoRectangle
     * @see https://github.com/anthonymartin/GeoLocation.php/blob/master/src/AnthonyMartin/GeoLocation/GeoLocation.php
     */
    public function getRectangle(int $width, int $height = null): GeoRectangle
    {
        $height      = $height ?? $width;
        $earthRadius = Ellipsoid::createDefault()->getArithmeticMeanRadius();
        $angularLat  = $height / 2 / $earthRadius;
        $angularLon  = $width / 2 / $earthRadius;

        $latRad = deg2rad($this->lat);
        $lonRad = deg2rad($this->lon);

        $minLat  = $latRad - $angularLat;
        $maxLat  = $latRad + $angularLat;
        $MIN_LAT = deg2rad(-90);   // -PI/2
        $MAX_LAT = deg2rad(90);    //  PI/2
        $MIN_LON = deg2rad(-180);  // -PI
        $MAX_LON = deg2rad(180);   //  PI

        if ($minLat > $MIN_LAT && $maxLat < $MAX_LAT) {
            $deltaLon = asin(sin($angularLon) / cos($latRad));
            $minLon   = $lonRad - $deltaLon;
            if ($minLon < $MIN_LON) {
                $minLon += 2 * M_PI;
            }
            $maxLon = $lonRad + $deltaLon;
            if ($maxLon > $MAX_LON) {
                $maxLon -= 2 * M_PI;
            }
        } else {
            // a pole is within the distance
            $minLat = max($minLat, $MIN_LAT);
            $maxLat = min($maxLat, $MAX_LAT);
            $minLon = $MIN_LON;
            $maxLon = $MAX_LON;
        }

        return new GeoRectangle(rad2deg($minLat), rad2deg($maxLat), rad2deg($minLon), rad2deg($maxLon));
    }
}
