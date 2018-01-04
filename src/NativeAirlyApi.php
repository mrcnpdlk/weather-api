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
use mrcnpdlk\Weather\Model\Airly\Station;

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
     * @param float $lat    Latitude
     * @param float $lon    Longitude
     * @param int   $radius Max distance in meters
     *
     * @return Station
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getNearestStation(float $lat, float $lon, int $radius): Station
    {
        $res = $this->request('nearestSensor/measurements', [
            'latitude'    => $lat,
            'longitude'   => $lon,
            'maxDistance' => $radius,
        ]);

        return new Station(json_decode($res));

    }

    /**
     * @param string $suffix
     *
     * @return mixed
     * @throws \mrcnpdlk\Weather\Exception
     */
    private function request(string $suffix, array $params)
    {
        try {
            $url = sprintf('%s/%s', $this->apiUrl, ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($url, $params) {
                    $oCurl = new Curl();
                    $oCurl->setHeader('apikey', $this->apiToken);
                    $oCurl->get($url, $params);

                    if ($oCurl->error) {
                        throw new \RuntimeException(sprintf('Curl error: %s', $oCurl->error_message), $oCurl->error_code);
                    }

                    return $oCurl->response;
                },
                [__METHOD__, $suffix],
                1
            );
            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $suffix, \gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw new Exception('Request Error', 1, $e);
        }
    }

}
