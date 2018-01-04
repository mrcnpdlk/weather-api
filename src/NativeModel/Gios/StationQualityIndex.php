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
 * Time: 23:59
 */

namespace mrcnpdlk\Weather\NativeModel\Gios;


class StationQualityIndex
{
    /**
     * Station ID
     *
     * @var integer
     */
    public $id;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $st;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $so2;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $no2;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $co;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $pm10;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $pm25;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $o3;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel
     */
    public $c6h6;

    /**
     * StationQualityIndex constructor.
     *
     * @param \stdClass|null $oData
     */
    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->id = $oData->id;
            foreach (['st', 'so2', 'no2', 'co', 'pm10', 'pm25', 'o3', 'c6hh6'] as $index) {
                if (isset($oData->{$index . 'IndexLevel'})) {
                    $this->{$index} = new Index(
                        $oData->{$index . 'CalcDate'},
                        $oData->{$index . 'SourceDataDate'},
                        $oData->{$index . 'IndexLevel'}

                    );
                }
            }
        }
    }

}
