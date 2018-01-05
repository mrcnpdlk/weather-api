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

/**
 * Class NativeAirlyApi
 *
 * @package mrcnpdlk\Weather
 * @see     https://developer.airly.eu/docs#nearestSensorMeasurementsUsingGET
 */
class NativeAirlyApi extends NativeApi
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $oLogger;
    /**
     * @var \mrcnpdlk\Psr16Cache\Adapter
     */
    private $oCacheAdapter;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var string
     */
    private $apiToken;

    /**
     * NativeGiosApi constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     *
     * @throws \mrcnpdlk\Weather\Exception
     */
    protected function __construct(Client $oClient)
    {
        parent::__construct($oClient);
        $this->oLogger       = $oClient->getLogger();
        $this->oCacheAdapter = $oClient->getCacheAdapter();
        $this->apiUrl        = $oClient->getAirlyRestUrl();
        $this->apiToken      = $oClient->getAirlyToken();
    }

    /**
     * Nearest sensor's current detailed measurements
     * /v1/nearestSensor/measurements
     *
     * @param float    $lat    Latitude
     * @param float    $lon    Longitude
     * @param int|null $radius Max distance in meters
     *
     * @return Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findNearestStation(float $lat, float $lon, int $radius = null): Station
    {
        $res = $this->request('nearestSensor/measurements', [
            'latitude'    => $lat,
            'longitude'   => $lon,
            'maxDistance' => $radius,
        ]);

        return new Station(json_decode($res));
    }

    /**
     * Current sensors list for a map region
     * List of sensors IDs with coordinates, addresses and current pollution level
     *
     * /v1/sensors/current
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $oGeoPoint Rectangle center point
     * @param float                                  $w         Rectangle with in meters
     * @param float|null                             $h         Rectangle height in meters
     *
     * @return Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findStations(GeoPoint $oGeoPoint, float $w, float $h = null): array
    {
        /**
         * @var Station[] $answer
         */
        $answer     = [];
        $oRectangle = $oGeoPoint->getRectangle($w, $h);
        $res        = $this->request('sensors/current', [
            'southwestLat'  => $oRectangle->getSW()->lat,
            'southwestLong' => $oRectangle->getSW()->lon,
            'northeastLat'  => $oRectangle->getNE()->lat,
            'northeastLong' => $oRectangle->getNE()->lon,
        ]);

        foreach ((array)json_decode($res) as $item) {
            $oStation           = new Station($item);
            $oStation->distance = $oStation->location->getDistance($oGeoPoint);
            $answer[]           = $oStation;
        }

        return $answer;
    }

    /**
     * Current sensors list (Airly's and official monitoring stations from http://powietrze.gios.gov.pl) for a map region
     * List of sensors IDs with coordinates, addresses and current pollution level
     *
     * /v1/sensorsWithWios/current
     *
     * @param \mrcnpdlk\Weather\NativeModel\GeoPoint $oGeoPoint Rectangle center point
     * @param float                                  $w         Rectangle with in meters
     * @param float|null                             $h         Rectangle height in meters
     *
     * @return Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findStationsWithWios(GeoPoint $oGeoPoint, float $w, float $h = null): array
    {
        /**
         * @var Station[] $answer
         */
        $answer     = [];
        $oRectangle = $oGeoPoint->getRectangle($w, $h);
        $res        = $this->request('sensorsWithWios/current', [
            'southwestLat'  => $oRectangle->getSW()->lat,
            'southwestLong' => $oRectangle->getSW()->lon,
            'northeastLat'  => $oRectangle->getNE()->lat,
            'northeastLong' => $oRectangle->getNE()->lon,
        ]);

        foreach ((array)json_decode($res) as $item) {
            $oStation           = new Station($item);
            $oStation->distance = $oStation->location->getDistance($oGeoPoint);
            $answer[]           = $oStation;
        }

        return $answer;
    }

    /**
     * @param int $stationId
     *
     * @return \mrcnpdlk\Weather\NativeModel\Airly\Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getStation(int $stationId): Station
    {
        $res = $this->request(sprintf('sensors/%s', $stationId));

        return new Station(json_decode($res));
    }

    /**
     * Sensor's current detailed measurements and historical pollution level
     * /v1/sensor/measurements
     *
     * @param int $stationId
     *
     * @return \mrcnpdlk\Weather\NativeModel\Airly\MeasurementResponse
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getStationMeasurements(int $stationId): MeasurementResponse
    {
        $res = $this->request('sensor/measurements', ['sensorId' => $stationId]);

        return new MeasurementResponse(json_decode($res));
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
            $url = sprintf('%s/%s', $this->apiUrl, ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($url, $params) {
                    $oCurl = new Curl();
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYHOST, 2);
                    $oCurl->setOpt(\CURLOPT_SSL_VERIFYPEER, false);
                    $oCurl->setHeader('apikey', $this->apiToken);
                    $oCurl->get($url, $params);

                    if ($oCurl->error) {
                        throw new \RuntimeException(sprintf('Curl error: %s', $oCurl->error_message), $oCurl->error_code);
                    }

                    return $oCurl->response;
                },
                [__METHOD__, $suffix],
                600
            );
            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $suffix, \gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw new Exception('Request Error', 1, $e);
        }
    }

}
