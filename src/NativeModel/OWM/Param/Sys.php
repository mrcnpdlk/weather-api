<?php
/**
 * Created by Marcin.
 * Date: 05.01.2018
 * Time: 19:27
 */

namespace mrcnpdlk\Weather\NativeModel\OWM\Param;


use Carbon\Carbon;

class Sys
{
    /**
     * @var integer
     */
    public $type;
    /**
     * @var integer
     */
    public $id;
    /**
     * @var float
     */
    public $message;
    /**
     * @var string
     */
    public $country;
    /**
     * @var integer
     */
    public $sunrise;
    /**
     * @var integer
     */
    public $sunset;

    /**
     * Time converting
     *
     * @param int|null $time
     *
     * @return $this
     */
    public function setSunrise(int $time = null)
    {
        $this->sunrise = $time ? Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s') : null;

        return $this;
    }

    /**
     * Time converting
     *
     * @param int|null $time
     *
     * @return $this
     */
    public function setSunset(int $time = null)
    {
        $this->sunset = $time ? Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s') : null;

        return $this;
    }
}
