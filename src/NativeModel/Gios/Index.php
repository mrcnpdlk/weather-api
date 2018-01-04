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
 * Time: 00:02
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;


class Index
{
    /**
     * @var string
     */
    public $calcDate;
    /**
     * @var string
     */
    public $sourceDataDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $level;

    /**
     * Index constructor.
     *
     * @param string|null    $calcDate
     * @param string|null    $sourceDataDate
     * @param \stdClass|null $level
     */
    public function __construct(string $calcDate = null, string $sourceDataDate = null, \stdClass $level = null)
    {
        $this->calcDate       = $calcDate;
        $this->sourceDataDate = $sourceDataDate;
        $this->level          = new IndexLevel($level);
    }
}
