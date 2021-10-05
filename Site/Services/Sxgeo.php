<?php

namespace ServiceBoiler\Prf\Site\Services;


use GuzzleHttp\Exception\GuzzleException;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Models\CacheIpLocation;


class Sxgeo
{

	/**
	 * @var \GuzzleHttp\Client|null
	 */
	private $client;
	/**
	 * @var string|null
	 */
	private $token;
	/**
	 * @var array
	 */
	private $params = [];
	/**
	 * @var array
	 */
	private $options = [];


	/**
	 * Sxgeo constructor.
	 */
	public function __construct()
	{

        $this->token = env('SXGEO_TOKEN', null);
		
	    $this->options = [
			'base_uri' => env('SXGEO_URL', null),
			'timeout' => '5',
		];
	}

	

    public function location($ipAddress)
	{


	    $this->params = [
            'base_uri' => env('SXGEO_URL', null),
            'timeout' => '5',
            'method' => 'GET',
			'uri' => $this->token ."/json/".$ipAddress,
            
		];
        $this->client = new \GuzzleHttp\Client($this->options);

        $response=$this->client->request($this->params['method'],$this->params['uri']);
        if ($response->getStatusCode() === Response::HTTP_OK) {
            $data = json_decode($response->getBody()->getContents(), true);
            if ($data['region']['iso'] == 'UA-43') {
                $data['region']['iso'] = 'RU-KRM';
                $data['country']['iso'] = 'RU';
            }

            return $data;

        }

	}

    

	private function logException($method, $message)
	{
		event(new ExceptionEvent($method, $message));
	}
}