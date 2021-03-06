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
 * Date: 04.01.2018
 * Time: 00:03
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;

/**
 * Class IndexLevel
 *
 * @package mrcnpdlk\Weather\NativeModel\Gios
 */
class IndexLevel
{
    /**
     * Level ID (0-5)
     *
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $indexLevelName;
}
