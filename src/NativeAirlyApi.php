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


use Curl\Curl;
use mrcnpdlk\Weather\NativeModel\Airly\MeasurementResponse;
use mrcnpdlk\Weather\NativeModel\Airly\Station;
use mrcnpdlk\Weather\NativeModel\GeoPoint;
use mrcnpdlk\Weather\NativeModel\GeoRectangle;

/**
 * Class NativeAirlyApi
 *
 * @package mrcnpdlk\Weather
 * @see     https://developer.airly.eu/docs#nearestSensorMeasurementsUsingGET
 */
class NativeAirlyApi extends NativeApi
{
    /**
     * Nearest sensor's current detailed measurements
     * /v1/nearestSensor/measurements
     *
     * @param GeoPoint $oPoint Center point
     * @param int|null $radius Max distance in meters
     *
     * @return Station
     * @throws \mrcnpdlk\Weather\Exception
     * @throws \JsonMapper_Exception
     */
    public function findNearestStation(GeoPoint $oPoint, int $radius = null): Station
    {
        /**
         * @var Station $answer
         */
        $res    = $this->request('nearestSensor/measurements', [
            'latitude'    => $oPoint->lat,
            'longitude'   => $oPoint->lon,
            'maxDistance' => $radius,
        ]);
        $answer = $this->jsonMapper->map($res, new Station());

        return $answer;
    }

    /**
     * Current sensors list for a map region
     * List of sensors IDs with coordinates, addresses and current pollution level
     *
     * /v1/sensors/current
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoRectangle $oRectangle Rectangle view
     *
     * @return Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findStations(GeoRectangle $oRectangle): array
    {
        /**
         * @var Station[] $answer
         * @var Station[] $tList
         */
        $res = $this->request('sensors/current', [
            'southwestLat'  => $oRectangle->getSW()->lat,
            'southwestLong' => $oRectangle->getSW()->lon,
            'northeastLat'  => $oRectangle->getNE()->lat,
            'northeastLong' => $oRectangle->getNE()->lon,
        ]);

        $tList = $this->jsonMapper->mapArray((array)json_decode($res), [], Station::class);

        foreach ($tList as $station) {
            $station->distance = $station->getLocation()->getDistance($oRectangle->getCenter());
        }

        usort($tList, function (Station $a, Station $b) {
            return $a->distance <=> $b->distance;
        });

        return $tList;
    }

    /**
     * Current sensors list (Airly's and official monitoring stations from http://powietrze.gios.gov.pl) for a map region
     * List of sensors IDs with coordinates, addresses and current pollution level
     *
     * /v1/sensorsWithWios/current
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoRectangle $oRectangle Rectangle view
     *
     * @return Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findStationsWithWios(GeoRectangle $oRectangle): array
    {
        /**
         * @var Station[] $tList
         */
        $res = $this->request('sensorsWithWios/current', [
            'southwestLat'  => $oRectangle->getSW()->lat,
            'southwestLong' => $oRectangle->getSW()->lon,
            'northeastLat'  => $oRectangle->getNE()->lat,
            'northeastLong' => $oRectangle->getNE()->lon,
        ]);

        $tList = $this->jsonMapper->mapArray((array)json_decode($res), [], Station::class);

        foreach ($tList as $station) {
            $station->distance = $station->getLocation()->getDistance($oRectangle->getCenter());
        }

        usort($tList, function (Station $a, Station $b) {
            return $a->distance <=> $b->distance;
        });

        return $tList;
    }

    /**
     * @param int $stationId
     *
     * @return \mrcnpdlk\Weather\NativeModel\Airly\Station
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getStation(int $stationId): Station
    {
        /**
         * $var string $res
         *
         * @var Station $answer
         */
        $res = $this->request(sprintf('sensors/%s', $stationId));

        $answer = $this->jsonMapper->map(json_decode($res), new Station());

        return $answer;
    }

    /**
     * Sensor's current detailed measurements and historical pollution level
     * /v1/sensor/measurements
     *
     * @param int $stationId
     *
     * @return \mrcnpdlk\Weather\NativeModel\Airly\MeasurementResponse
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getStationMeasurements(int $stationId): MeasurementResponse
    {
        /**
         * $var string $res
         *
         * @var MeasurementResponse $answer
         */
        $res = $this->request('sensor/measurements', ['sensorId' => $stationId]);

        $answer = $this->jsonMapper->map($res, new MeasurementResponse());

        return $answer;
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
            $url = sprintf('%s/%s', $this->oClient->getAirlyRestUrl(), ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($url, $params) {
                    $oCurl = new Curl();
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYHOST, 2);
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYPEER, false);
                    $oCurl->setHeader('apikey', $this->oClient->getAirlyToken());
                    $oCurl->get($url, $params);

                    if ($oCurl->error) {
                        throw new \RuntimeException(sprintf('Curl error: %s', $oCurl->error_message), $oCurl->error_code);
                    }

                    return $oCurl->response;
                },
                [__METHOD__, $suffix, $params],
                600
            );
            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $suffix, \gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw new Exception('Request Error', 1, $e);
        }
    }

}
