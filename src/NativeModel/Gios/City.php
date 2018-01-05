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
 * Time: 22:19
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;

/**
 * Class City
 *
 * @package mrcnpdlk\Weather\NativeModel\Gios
 */
class City
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
     * @var \mrcnpdlk\Weather\NativeModel\Gios\Commune
     */
    public $commune;
}
