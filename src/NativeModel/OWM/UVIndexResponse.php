<?php
/**
 * Created by Marcin.
 * Date: 05.01.2018
 * Time: 19:05
 */

namespace mrcnpdlk\Weather\NativeModel\OWM;


use Carbon\Carbon;

/**
 * Class UVIndexResponse
 *
 * @package mrcnpdlk\Weather\NativeModel\OWM
 */
class UVIndexResponse
{
    /**
     * @var float
     */
    public $lat;
    /**
     * @var float
     */
    public $lon;
    /**
     * @var string
     */
    public $date_iso;
    /**
     * @var integer
     */
    public $date;
    /**
     * @var float
     */
    public $value;

    /**
     * Time converting
     *
     * @param int|null $time
     *
     * @return $this
     */
    public function setDate(int $time = null)
    {
        $this->date = $time ? Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s') : null;

        return $this;
    }
}
