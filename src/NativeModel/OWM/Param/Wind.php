<?php
/**
 * Created by Marcin.
 * Date: 05.01.2018
 * Time: 20:02
 */

namespace mrcnpdlk\Weather\NativeModel\OWM\Param;


class Wind
{
    /**
     * @var float
     */
    public $speed;
    /**
     * @var integer
     */
    public $deg;
    /**
     * @var float|null
     */
    public $gust;
}
