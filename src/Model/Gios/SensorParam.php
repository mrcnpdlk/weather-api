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


namespace mrcnpdlk\Weather\Model\Gios;

/**
 * Class SensorParam
 *
 * @package mrcnpdlk\Weather\Model\Gios
 */
class SensorParam
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
    public $formula;
    /**
     * @var string
     */
    public $code;

    /**
     * SensorParam constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->id      = $oData->idParam;
            $this->name    = $oData->paramName;
            $this->formula = $oData->paramFormula;
            $this->code    = $oData->paramCode;
        }
    }
}
