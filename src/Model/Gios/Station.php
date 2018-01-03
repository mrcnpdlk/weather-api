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
 * Time: 22:08
 */

namespace mrcnpdlk\Weather\Model\Gios;


use mrcnpdlk\Weather\Model\GeoPoint;

class Station
{
    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $dateStart;
    /**
     * @var string|null
     */
    public $dateEnd;
    /**
     * @var \mrcnpdlk\Weather\Model\GeoPoint
     */
    public $location;
    /**
     * @var string
     */
    public $addressStreet;
    /**
     * @var \mrcnpdlk\Weather\Model\Gios\City
     */
    public $city;

    /**
     * Station constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->id            = $oData->id;
            $this->name          = $oData->stationName;
            $this->dateStart     = $oData->dateStart;
            $this->dateEnd       = $oData->dateEnd;
            $this->location      = new GeoPoint((float)$oData->gegrLat, (float)$oData->gegrLon);
            $this->addressStreet = $oData->addressStreet;
            $this->city          = new City($oData->city);

        }
    }
}
