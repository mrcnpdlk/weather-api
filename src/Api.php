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
    private $currentDateTime;
    /**
     * @var NativeOWMApi
     */
    private $oOWMApi;
    /**
     * @var NativeGiosApi
     */
    private $oGiosApi;
    /**
     * @var NativeAirlyApi
     */
    private $oAirlyApi;

    /**
     * Api constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     */
    public function __construct(Client $oClient)
    {
        $this->oOWMApi         = NativeOWMApi::create($oClient);
        $this->oGiosApi        = NativeGiosApi::create($oClient);
        $this->oAirlyApi       = NativeAirlyApi::create($oClient);
        $this->currentDateTime = new \DateTime();
    }

    /**
     * @return \mrcnpdlk\Weather\Model\Address
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getAddress(): Address
    {
        try {
            if (null === $this->address) {
                $oCurl = new Curl();
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
        } catch (\Exception $e) {
            throw new Exception('Address is unavailable for set location', 1, $e);
        }
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\GeoPoint
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getLocation(): GeoPoint
    {
        if (null === $this->location) {
            $this->setLocation();
        }

        return $this->location;
    }

    /**
     * @return \mrcnpdlk\Weather\NativeModel\Gios\Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getNearestGiosStation()
    {
        return $this->oGiosApi->findNearestStation($this->getLocation());
    }

    /**
     * Get timing for Sun
     *
     * @return \mrcnpdlk\Weather\Model\SunSchedule
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getSunSchedule(): SunSchedule
    {
        if (null === $this->sunSchedule) {
            $res               = date_sun_info(
                $this->currentDateTime->getTimestamp(),
                $this->getLocation()->lat,
                $this->getLocation()->lon
            );
            $this->sunSchedule = new SunSchedule($res);
        }

        return $this->sunSchedule;
    }

    /**
     * Get UV index
     *
     * @see https://en.wikipedia.org/wiki/Ultraviolet_index
     * @return float|null
     */
    public function getUVIndex()
    {
        try {
            $res = $this->oOWMApi->getUVIndex($this->getLocation(), $this->currentDateTime);
            if ($res) {
                return $res->value;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint|null $oGeoPoint
     *
     * @return $this
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function setLocation(GeoPoint $oGeoPoint = null)
    {
        if ($oGeoPoint) {
            $this->location = $oGeoPoint;
        } else { // get location by Public IP  - we use 2 APIs for assurance
            try {
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
            } catch (\Exception $e) {
                throw new Exception('Cannot set location');
            }
        }
        $this->sunSchedule = null;

        return $this;
    }

}
