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
use mrcnpdlk\Weather\Model\Gios\Station;

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
    protected function __construct(\mrcnpdlk\Weather\Client $oClient)
    {
        parent::__construct($oClient);
        $this->oLogger       = $oClient->getLogger();
        $this->oCacheAdapter = $oClient->getCacheAdapter();
        $this->apiUrl        = $oClient->getGiosRestUrl();
    }

    /**
     * @return \mrcnpdlk\Weather\Model\Gios\Station[]
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function findAll()
    {
        /**
         * @var \mrcnpdlk\Weather\Model\Gios\Station[] $answer
         */
        $answer = [];
        $tList = $this->request('station/findAll');
        foreach ($tList as $item){
            $answer[] = new Station($item);
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
            $self = $this;
            $url  = sprintf('%s/%s', $this->apiUrl, ltrim($suffix, '/'));
            $this->oLogger->debug(sprintf('REQ: %s', $suffix));
            $resp = $this->oCacheAdapter->useCache(
                function () use ($self, $url) {
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
            $this->oLogger->debug(sprintf('RESP: %s, type is %s', $suffix, gettype($resp)));

            return $resp;
        } catch (\Exception $e) {
            throw new Exception('Request Error', 1, $e);
        }
    }

}
