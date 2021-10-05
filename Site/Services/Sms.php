<?php

namespace ServiceBoiler\Prf\Site\Services;


use GuzzleHttp\Exception\GuzzleException;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use ServiceBoiler\Prf\Site\Exceptions\Sms\SmsException;
use ServiceBoiler\Prf\Site\Models\User;

use \SoapClient;
use \SoapFault;

/**
 * Class Client for https://mcommunicator.ru/M2M/m2m_api.asmx
 * @package MtsCommunicator
 */
class Sms
{
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    protected $wsdlUrl = 'http://www.mcommunicator.ru/m2m/m2m_api.asmx?WSDL';

    /**
     * @var SoapClient
     */
    private $client;

    public function __construct()
    {
        $this->password = env('MTS_API_KEY');
        $soapOptions = ['soap_version' => SOAP_1_2];
        if ($this->token) {
            $soapOptions['stream_context'] = stream_context_create([
                'http' => [
                    'header' => 'Authorization: Bearer ' . $this->token
                ]
            ]);
        }
        $this->client = new SoapClient($this->wsdlUrl, $soapOptions);
    }

    public function sendSms($function, array $data)
    {
        
             $params['login'] = '';
             $params['password'] = $this->password;
             $params['msid'] = $this->preparePhone($data['phone']);
             $params['message'] =  $data['message'];
        
        $response = new Response();
        try {
            $response->result = $this->client->{$function}($params);
        } catch (SoapFault $e) {
            $response->error = $e->getMessage();
        }
        return !empty($response->error) ? false : true;
    }

    public static function preparePhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) === 10)
        {
            return '7' . $phone;
        }
        if (strpos($phone, '8') === 0)
        {
            return '7' . substr($phone, 1);
        }
        return $phone;
    }
}