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


namespace mrcnpdlk\Weather\NativeModel\Gios;

/**
 * Class SensorParam
 *
 * @package mrcnpdlk\Weather\NativeModel\Gios
 */
class SensorParam
{
    /**
     * @var integer
     */
    public $idParam;
    /**
     * @var string
     */
    public $paramName;
    /**
     * @var string
     */
    public $paramFormula;
    /**
     * @var string
     */
    public $paramCode;
}
