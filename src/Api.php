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
 *
 */

/**
 * Created by Marcin.
 * Date: 04.01.2018
 * Time: 23:17
 */

namespace mrcnpdlk\Weather;


use Curl\Curl;
use mrcnpdlk\Weather\Model\Address;
use mrcnpdlk\Weather\Model\SunSchedule;
use mrcnpdlk\Weather\NativeModel\GeoPoint;

class Api
{
    /**
     * @var GeoPoint
     */
    private $location;
    /**
     * @var \mrcnpdlk\Weather\Model\Address
     */
    private $address;
    /**
     * @var
     */
    private $sunSchedule;
    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Api constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     */
    public function __construct(Client $oClient)
    {
    }

    /**
     * @return \mrcnpdlk\Weather\Model\Address
     */
    public function getAddress(): Address
    {
        if (null === $this->address) {
            $oCurl = new \Curl\Curl();
            $oCurl->get('http://nominatim.openstreetmap.org/reverse.php', [
                'lat'    => $this->getLocation()->lat,
                'lon'    => $this->getLocation()->lon,
                'format' => 'json',
            ]);
            if ($oCurl->error) {
                throw new \RuntimeException(sprintf('Cannot revers geocode. Reason: %s', $oCurl->error_message));
            }

            $resAddress    = json_decode($oCurl->response);
            $this->address = new Address($resAddress->address ?? null);
        }

        return $this->address;
    }

    /**
     * @return \DateTime
     */
    private function getDateTime(): \DateTime
    {
        if (null === $this->dateTime) {
            $this->setDateTime();
        }

        return $this->dateTime;
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     */
    public function getLocation(): GeoPoint
    {
        if (null === $this->location) {
            $this->setLocation();
        }

        return $this->location;
    }

    /**
     * Get timing for Sun
     *
     * @return \mrcnpdlk\Weather\Model\SunSchedule
     */
    public function getSunSchedule()
    {
        if (null === $this->sunSchedule) {
            $res               = date_sun_info(
                $this->getDateTime()->getTimestamp(),
                $this->getLocation()->lat,
                $this->getLocation()->lon
            );
            $this->sunSchedule = new SunSchedule($res);
        }

        return $this->sunSchedule;
    }

    /**
     * Setting Date for library.
     * If NULL, then NOW and current timezone is set.
     *
     * @param \DateTime|null $oDateTime
     *
     * @return $this
     */
    public function setDateTime(\DateTime $oDateTime = null)
    {
        $this->dateTime    = $oDateTime ?? new \DateTime();
        $this->sunSchedule = null;

        return $this;
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint|null $oGeoPoint
     *
     * @return $this
     */
    public function setLocation(GeoPoint $oGeoPoint = null)
    {
        if ($oGeoPoint) {
            $this->location = $oGeoPoint;
        } else { // get location by Public IP  - we use 2 APIs for assurance
            $oCurl = new Curl();
            $oCurl->get('http://freegeoip.net/json/');  // faster API - ca 200 ms

            if ($oCurl->error) {
                $oCurl->get('https://ipapi.co/json');  // slower API - ca 500 ms
                if ($oCurl->error) {
                    throw new \RuntimeException(sprintf('Cannot find location via public IP. Reason: %s', $oCurl->error_message));
                }

                $resIpAPi       = json_decode($oCurl->response);
                $this->location = new GeoPoint($resIpAPi->latitude, $resIpAPi->longitude);
            } else {
                $resFreeGeoIp   = json_decode($oCurl->response);
                $this->location = new GeoPoint($resFreeGeoIp->latitude, $resFreeGeoIp->longitude);
            }
        }
        $this->sunSchedule = null;

        return $this;
    }


}
