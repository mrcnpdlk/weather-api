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
 * Time: 22:20
 */

namespace mrcnpdlk\Weather\Model\Gios;

/**
 * Class Commune
 *
 * @package mrcnpdlk\Weather\Model\Gios
 */
class Commune
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $districtName;
    /**
     * @var string
     */
    public $provinceName;

    /**
     * Commune constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->name         = $oData->communeName;
            $this->districtName = $oData->districtName;
            $this->provinceName = $oData->provinceName;
        }
    }
}
