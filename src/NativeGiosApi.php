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

/**
 * Created by Marcin.
 * Date: 03.01.2018
 * Time: 21:22
 */

namespace mrcnpdlk\Weather;


use Curl\Curl;
use mrcnpdlk\Weather\NativeModel\GeoPoint;
use mrcnpdlk\Weather\NativeModel\GeoRectangle;
use mrcnpdlk\Weather\NativeModel\Gios\Data;
use mrcnpdlk\Weather\NativeModel\Gios\Sensor;
use mrcnpdlk\Weather\NativeModel\Gios\Station;
use mrcnpdlk\Weather\NativeModel\Gios\StationQualityIndex;

class NativeGiosApi extends NativeApi
{
    /**
     * Usługa sieciowa udostępniająca listę stacji pomiarowych
     *
     * @return \mrcnpdlk\Weather\NativeModel\Gios\Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findAll(): array
    {
        /**
         * @var \mrcnpdlk\Weather\NativeModel\Gios\Station[] $answer
         * @var \stdClass[]                                  $tList
         */
        $tList  = $this->request('station/findAll');
        $answer = $this->jsonMapper->mapArray($tList, [], Station::class);

        return $answer;
    }

    /**
     * @param GeoPoint $oPoint Center point coordinates
     * @param int|null $radius Max distance in meters
     *
     * @return \mrcnpdlk\Weather\NativeModel\Gios\Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findNearestStation(GeoPoint $oPoint, int $radius = null): Station
    {
        /**
         * @var float $distance
         */
        $distance       = $radius;
        $nearestStation = null;
        $tAll           = $this->findAll();
        foreach ($tAll as $station) {
            $delta = $station->getLocation()->getDistance($oPoint);
            if ($distance === null) {
                $distance = $delta;
            }
            if ($delta < $distance) {
                $nearestStation           = $station;
                $distance                 = $delta;
                $nearestStation->distance = $delta;
            }
        }

        return $nearestStation;
    }

    /**
     * @param \mrcnpdlk\Weather\NativeModel\GeoRectangle $oRectangle
     *
     * @return array
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findStations(GeoRectangle $oRectangle): array
    {
        /**
         * @var Station[] $answer
         */
        $answer = [];
        $tAll   = $this->findAll();
        foreach ($tAll as $station) {
            if ($oRectangle->getPolygon()->contains($station->getLocation()->getCoordinate())) {
                $station->distance = $station->getLocation()->getDistance($oRectangle->getCenter());
                $answer[]          = $station;
            }
        }

        usort($answer, function (Station $a, Station $b) {
            return $a->distance <=> $b->distance;
        });

        return $answer;
    }

    /**
     * Usługa sieciowa udostępniająca indeks jakości powietrza na podstawie podanego identyfikatora stacji pomiarowej
     *
     * @param int $stationId
     *
     * @return StationQualityIndex
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getAirQualityIndex(int $stationId): StationQualityIndex
    {
        /**
         * @var StationQualityIndex $answer
         */
        $res    = $this->request(sprintf('%s/%s', 'aqindex/getIndex', $stationId));
        $answer = $this->jsonMapper->map($res, new StationQualityIndex());

        return $answer;
    }

    /**
     * Usługa sieciowa udostępniająca dane pomiarowe na podstawie podanego identyfikatora stanowiska pomiarowego
     *
     * @param int $sensorId
     *
     * @return \mrcnpdlk\Weather\NativeModel\Gios\Data
     * @throws \JsonMapper_Exception
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getSensorData(int $sensorId): Data
    {
        /**
         * @var Data $answer
         */
        $res = $this->request(sprintf('%s/%s', 'data/getData', $sensorId));

        $answer = $this->jsonMapper->map($res, new Data());

        return $answer;
    }

    /**
     * Usługa sieciowa udostępniająca listę stanowisk pomiarowych dostępnych na wybranej stacji pomiarowej
     *
     * @param int $stationId
     *
     * @return array|\mrcnpdlk\Weather\NativeModel\Gios\Sensor[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getSensorsForStation(int $stationId): array
    {
        /**
         * @var \mrcnpdlk\Weather\NativeModel\Gios\Sensor[] $answer
         * @var \stdClass[]                                 $tList
         */
        $answer = [];
        $tList  = $this->request(sprintf('%s/%s', 'station/sensors', $stationId));

        $answer = $this->jsonMapper->mapArray($tList, [], Sensor::class);

        return $answer;
    }

    /**
     * @param string $suffix
     *
     * @return mixed
     * @throws \mrcnpdlk\Weather\Exception
     */
    private function request(string $suffix)
    {
        try {
            $url = sprintf('%s/%s', $this->oClient->getGiosRestUrl(), ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($url) {
                    $oCurl = new Curl();
                    $oCurl->get($url);

                    if ($oCurl->error) {
                        throw new \RuntimeException('Curl error', $oCurl->error_code);
                    }

                    return json_decode($oCurl->response);
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
