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


namespace mrcnpdlk\Weather;


use Carbon\Carbon;
use Curl\Curl;
use mrcnpdlk\Weather\NativeModel\GeoPoint;
use mrcnpdlk\Weather\NativeModel\OWM\UVIndexResponse;
use mrcnpdlk\Weather\NativeModel\OWM\WeatherResponse;

/**
 * Class NativeOWMApi
 *
 * @package mrcnpdlk\Weather
 * @see     https://developer.airly.eu/docs#nearestSensorMeasurementsUsingGET
 */
class NativeOWMApi extends NativeApi
{
    /**
     * Get UV index for any day
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $oGeoPoint
     * @param \DateTime                              $oDateTime
     *
     * @return \mrcnpdlk\Weather\NativeModel\OWM\UVIndexResponse|null
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getUVIndex(GeoPoint $oGeoPoint, \DateTime $oDateTime)
    {
        /**
         * @var string          $res
         * @var UVIndexResponse $oJson
         * @var int             $timestamp
         */
        // Fix - getting end of the day
        $timestamp = Carbon::createFromTimestamp($oDateTime->getTimestamp())->endOfDay()->getTimestamp();
        $res       = $this->request(
            'uvi/history',
            [
                'lat'   => $oGeoPoint->lat,
                'lon'   => $oGeoPoint->lon,
                'start' => $timestamp,
                'end'   => $timestamp,
            ]
        );
        if (count($res)) {
            $oJson = $this->jsonMapper->map($res[0], new UVIndexResponse());

            return $oJson;
        }

        return null;


    }

    /**
     * Get UV index for current day
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $oGeoPoint
     *
     * @return \mrcnpdlk\Weather\NativeModel\OWM\UVIndexResponse
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getUVIndexCurrent(GeoPoint $oGeoPoint): UVIndexResponse
    {
        /**
         * @var string          $res
         * @var UVIndexResponse $oJson
         */
        $res = $this->request(
            'uvi',
            [
                'lat' => $oGeoPoint->lat,
                'lon' => $oGeoPoint->lon,
            ]
        );


        $oJson = $this->jsonMapper->map($res, new UVIndexResponse());

        return $oJson;
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $oGeoPoint
     *
     * @return WeatherResponse
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getWeather(GeoPoint $oGeoPoint): WeatherResponse
    {
        /**
         * @var string          $res
         * @var WeatherResponse $oJson
         */
        $res = $this->request(
            'weather',
            [
                'lat' => $oGeoPoint->lat,
                'lon' => $oGeoPoint->lon,
            ]
        );


        $oJson = $this->jsonMapper->map(json_decode($res), new WeatherResponse());

        return $oJson;
    }

    /**
     * @param string $suffix
     * @param array  $params Request key-value pair params
     *
     * @return mixed
     * @throws \mrcnpdlk\Weather\Exception
     */
    private function request(string $suffix, array $params = [])
    {
        try {
            $params          = array_merge($params, $this->oClient->getOWMParams());
            $params['appid'] = $this->oClient->getOWMToken();

            $url = sprintf('%s/%s', $this->oClient->getOWMRestUrl(), ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($url, $params) {
                    $oCurl = new Curl();
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYHOST, 2);
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYPEER, false);
                    $oCurl->get($url, $params);

                    if ($oCurl->error) {
                        throw new \RuntimeException(sprintf('Curl error: %s', $oCurl->error_message), $oCurl->error_code);
                    }

                    return $oCurl->response;
                },
                [__METHOD__, $suffix, $params],
                20
            );
            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $suffix, \gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw new Exception('Request Error', 1, $e);
        }
    }

}
