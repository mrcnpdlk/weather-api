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
use mrcnpdlk\Weather\Client;
use mrcnpdlk\Weather\Model\GeoPoint;
use mrcnpdlk\Weather\Model\Gios\Data;
use mrcnpdlk\Weather\Model\Gios\Sensor;
use mrcnpdlk\Weather\Model\Gios\Station;
use mrcnpdlk\Weather\Model\Gios\StationQualityIndex;

class NativeGiosApi extends NativeApi
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
     * NativeGiosApi constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     */
    protected function __construct(Client $oClient)
    {
        parent::__construct($oClient);
        $this->oLogger       = $oClient->getLogger();
        $this->oCacheAdapter = $oClient->getCacheAdapter();
        $this->apiUrl        = $oClient->getGiosRestUrl();
    }

    /**
     * Usługa sieciowa udostępniająca listę stacji pomiarowych
     *
     * @return \mrcnpdlk\Weather\Model\Gios\Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findAll(): array
    {
        /**
         * @var \mrcnpdlk\Weather\Model\Gios\Station[] $answer
         * @var \stdClass[]                            $tList
         */
        $answer = [];
        $tList  = $this->request('station/findAll');
        foreach ($tList as $item) {
            $answer[] = new Station($item);
        }

        return $answer;
    }

    /**
     * @param float $lan
     * @param float $lon
     *
     * @return \mrcnpdlk\Weather\Model\Gios\Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findNearest(float $lan, float $lon): Station
    {
        /**
         * @var float $distance
         */
        $distance       = null;
        $nearestStation = null;
        $tAll           = $this->findAll();
        foreach ($tAll as $station) {
            $delta = $station->location->getDistance(new GeoPoint($lan, $lon));
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
     * Usługa sieciowa udostępniająca indeks jakości powietrza na podstawie podanego identyfikatora stacji pomiarowej
     *
     * @param int $stationId
     *
     * @return mixed
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getAirQualityIndex(int $stationId)
    {
        $res = $this->request(sprintf('%s/%s', 'aqindex/getIndex', $stationId));

        return new StationQualityIndex($res);
    }

    /**
     * Usługa sieciowa udostępniająca dane pomiarowe na podstawie podanego identyfikatora stanowiska pomiarowego
     *
     * @param int $sensorId
     *
     * @return \mrcnpdlk\Weather\Model\Gios\Data
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getSensorData(int $sensorId): Data
    {
        $res = $this->request(sprintf('%s/%s', 'data/getData', $sensorId));

        return new Data($res);
    }

    /**
     * Usługa sieciowa udostępniająca listę stanowisk pomiarowych dostępnych na wybranej stacji pomiarowej
     *
     * @param int $stationId
     *
     * @return array|\mrcnpdlk\Weather\Model\Gios\Sensor[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getSensorsForStation(int $stationId): array
    {
        /**
         * @var \mrcnpdlk\Weather\Model\Gios\Sensor[] $answer
         * @var \stdClass[]                           $tList
         */
        $answer = [];
        $tList  = $this->request(sprintf('%s/%s', 'station/sensors', $stationId));
        foreach ($tList as $item) {
            $answer[] = new Sensor($item);
        }

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
            $url = sprintf('%s/%s', $this->apiUrl, ltrim($suffix, '/'));
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
