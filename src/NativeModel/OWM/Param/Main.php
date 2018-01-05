<?php
/**
 * Created by Marcin.
 * Date: 05.01.2018
 * Time: 19:31
 */

namespace mrcnpdlk\Weather\NativeModel\OWM\Param;


class Main
{
    /**
     * @var float
     */
    public $temp;
    /**
     * @var float
     */
    public $humidity;
    /**
     * @var float
     */
    public $temp_min;
    /**
     * @var float
     */
    public $temp_max;
    /**
     * @var float
     */
    public $pressure;
    /**
     * @var float|null
     */
    public $sea_level;
    /**
     * @var float|null
     */
    public $grnd_level;
}
