<?php
/**
 * Created by Marcin.
 * Date: 06.01.2018
 * Time: 21:37
 */

namespace mrcnpdlk\Weather\Model;


use Carbon\Carbon;

class SunSchedule
{
    /**
     * @var null|string
     */
    public $sunrise;
    /**
     * @var null|string
     */
    public $sunset;
    /**
     * @var string|null
     */
    public $dayDuration;
    /**
     * @var null|string
     */
    public $transit;

    /**
     * @var null|string
     */
    public $civilTwilightBegin;
    /**
     * @var null|string
     */
    public $civilTwilightEnd;
    /**
     * @var null|string
     */
    public $nauticalTwilightBegin;
    /**
     * @var null|string
     */
    public $nauticalTwilightEnd;
    /**
     * @var null|string
     */
    public $astronomicalTwilightBegin;
    /**
     * @var null|string
     */
    public $astronomicalTwilightEnd;

    /**
     * SunSchedule constructor.
     *
     * @param array $tData
     */
    public function __construct(array $tData)
    {
        $this->sunrise                   = \is_int($tData['sunrise']) ? Carbon::createFromTimestamp($tData['sunrise'])->toTimeString() : null;
        $this->sunset                    = \is_int($tData['sunset']) ? Carbon::createFromTimestamp($tData['sunset'])->toTimeString() : null;
        $this->transit                   = \is_int($tData['transit']) ? Carbon::createFromTimestamp($tData['transit'])->toTimeString() : null;
        $this->civilTwilightBegin        = \is_int($tData['civil_twilight_begin']) ? Carbon::createFromTimestamp($tData['civil_twilight_begin'])->toTimeString() : null;
        $this->civilTwilightEnd          = \is_int($tData['civil_twilight_end']) ? Carbon::createFromTimestamp($tData['civil_twilight_end'])->toTimeString() : null;
        $this->nauticalTwilightBegin     = \is_int($tData['nautical_twilight_begin']) ? Carbon::createFromTimestamp($tData['nautical_twilight_begin'])->toTimeString() : null;
        $this->nauticalTwilightEnd       = \is_int($tData['nautical_twilight_end']) ? Carbon::createFromTimestamp($tData['nautical_twilight_end'])->toTimeString() : null;
        $this->astronomicalTwilightBegin = \is_int($tData['astronomical_twilight_begin']) ? Carbon::createFromTimestamp($tData['astronomical_twilight_begin'])->toTimeString() : null;
        $this->astronomicalTwilightEnd   = \is_int($tData['astronomical_twilight_end']) ? Carbon::createFromTimestamp($tData['astronomical_twilight_end'])->toTimeString() : null;
        if (\is_int($tData['sunrise']) && \is_int($tData['sunset'])) {
            $sunrise           = Carbon::createFromTimestamp($tData['sunrise']);
            $sunset            = Carbon::createFromTimestamp($tData['sunset']);
            $diff              = $sunrise->diffInMinutes($sunset);
            $this->dayDuration = (int)($diff / 60) . ':' . str_pad($diff % 60, 2, '0', \STR_PAD_LEFT);
        }
    }

}
