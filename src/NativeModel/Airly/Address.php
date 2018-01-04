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

namespace mrcnpdlk\Weather\NativeModel\Airly;

/**
 * Class Address
 *
 * @package mrcnpdlk\Weather\NativeModel\Airly
 */
class Address
{
    /**
     * @var string
     */
    public $streetNumber;
    /**
     * @var string
     */
    public $route;
    /**
     * @var string
     */
    public $locality;
    /**
     * @var string
     */
    public $country;

    /**
     * Address constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->streetNumber = $oData->streetNumber;
            $this->route        = $oData->route;
            $this->locality     = $oData->locality;
            $this->country      = $oData->country;
        }
    }
}
