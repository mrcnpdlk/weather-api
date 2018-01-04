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
 * Time: 00:56
 */

namespace mrcnpdlk\Weather\Model\Gios;

/**
 * Class DataValue
 *
 * @package mrcnpdlk\Weather\Model\Gios
 */
class DataValue
{
    /**
     * @var string
     */
    public $date;

    /**
     * @var float
     */
    public $value;

    /**
     * DataValue constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->date  = $oData->date;
            $this->value = $oData->value;
        }
    }
}
