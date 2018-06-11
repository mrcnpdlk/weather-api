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
     * @var string|null
     */
    public $stCalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $stIndexLevel;
    /**
     * @var mixed
     * @todo Not described
     */
    public $stIndexStatus;
    /**
     * @var mixed
     * @todo Not described
     */
    public $stIndexCrParam;
    /**
     * @var string|null
     */
    public $stSourceDataDate;
    /**
     * @var string|null
     */
    public $so2CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $so2IndexLevel;
    /**
     * @var string|null
     */
    public $so2SourceDataDate;
    /**
     * @var string|null
     */
    public $no2CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $no2IndexLevel;
    /**
     * @var string|null
     */
    public $no2SourceDataDate;
    /**
     * @var string|null
     */
    public $coCalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $coIndexLevel;
    /**
     * @var string|null
     */
    public $coSourceDataDate;
    /**
     * @var string|null
     */
    public $pm10CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $pm10IndexLevel;
    /**
     * @var string|null
     */
    public $pm10SourceDataDate;
    /**
     * @var string|null
     */
    public $pm25CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $pm25IndexLevel;
    /**
     * @var string|null
     */
    public $pm25SourceDataDate;
    /**
     * @var string|null
     */
    public $o3CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $o3IndexLevel;
    /**
     * @var string|null
     */
    public $o3SourceDataDate;
    /**
     * @var string|null
     */
    public $c6h6CalcDate;
    /**
     * @var \mrcnpdlk\Weather\NativeModel\Gios\IndexLevel|null
     */
    public $c6h6IndexLevel;
    /**
     * @var string|null
     */
    public $c6h6SourceDataDate;

}
