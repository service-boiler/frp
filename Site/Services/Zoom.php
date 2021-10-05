<?php

namespace ServiceBoiler\Prf\Site\Services;


use GuzzleHttp\Exception\GuzzleException;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
//use ServiceBoiler\Prf\Site\Events\Zoom\ExceptionEvent;
use ServiceBoiler\Prf\Site\Exceptions\Zoom\ZoomException;
use ServiceBoiler\Prf\Site\Models\Webinar;
use ServiceBoiler\Prf\Site\Models\User;

class Zoom
{

	/**
	 * @var \GuzzleHttp\Client|null
	 */
	private $client;
	/**
	 * @var string|null
	 */
	private $platformToken;
	/**
	 * @var array
	 */
	private $params = [];
	/**
	 * @var array
	 */
	private $options = [];


	/**
	 * Zoom constructor.
	 */
	public function __construct()
	{
		$this->platformToken = env('ZOOM_TOKEN', null);
		
		$key = env('ZOOM_API_SECRET');
               $payload = array(
        "iss" => env('ZOOM_API_KEY'),
        'exp' => time() + 3600,
            );
            
            $jwt = JWT::encode($payload, $key);
        
        $this->options = [
			'base_uri' => env('ZOOM_URL', null),
			'timeout' => config('site.zoom_timeout', 35.0),
			'headers' => [
				'Content-Type' => 'multipart/form-data',
                "Authorization" => "Bearer " .$jwt,
			],
		];
	}

	
	public function createWebinar(array $data)
	{

        
        $this->params = [
			'method' => 'POST',
			'uri' => '/v2/users/me/webinars',
            'data' => [
				'key' => 'json',
				'value' => [
                            "topic" => $data['topic'],
                            "agenda" => $data['agenda'],
                            "type" => 5,
                            "start_time" => $data['start_time'],
                            "duration" => "60", 
                            "settings" => [
                                    "host_video"=> "true",
                                    "panelists_video"=> "false",
                                    "approval_type"=> 0,
                                    "audio"=> "both",
                                    "auto_recording"=> "cloud",
                                    "enforce_login"=> "false",
                                    "close_registration"=> "false",
                                    "show_share_button"=> "true",
                                    "allow_multiple_devices"=> "true",
                                    "practice_session"=> "true",
                                    "hd_video"=> "true",
                                    "question_answer"=> "true",
                                    "registrants_confirmation_email"=> "true",
                                    "on_demand"=> "false",
                                    "request_permission_to_unmute_participants"=> "false",
                                    "contact_name"=> "FERROLI Академия",
                                    "contact_email"=> "info@ferroli.ru",
                                    "meeting_authentication"=> "false", //Требовать логиниться
                                    "registrants_restrict_number"=> 0,
                                    "registrants_email_notification"=> "true",
                                    "post_webinar_survey"=> "false"
                                  ],
                        ],
			],

		];
        //dd($this);
		return $this;
	}
	
	public function addWebinarRegistrantDefaultQuestions(Webinar $webinar)
	{

        
        $this->params = [
			'method' => 'PATCH',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants/questions",
            'data' => [
				'key' => 'json',
				'value' => [
                            "questions" => [
                                    ["field_name"=> "last_name","required"=>"true"],
                                    ["field_name"=> "city","required"=>"true"],
                                    ["field_name"=> "country","required"=>"true"],
                                    ["field_name"=> "phone","required"=>"false"],
                                    ["field_name"=> "org","required"=>"false"],
                                    ["field_name"=> "job_title","required"=>"false"],
                                    
                                  ],
                        ],
			],

		];
        //dd($this);
		return $this;
	}
	
    public function getWebinars(array $data)
	{

        
        $this->params = [
			'method' => 'GET',
			'uri' => '/v2/users/me/webinars?page_size=300',
            
		];
        
		return $this;
	}
 
    

    public function getWebinarRegistrants(Webinar $webinar, $status='approved')
	{
        
        $this->params = [
			'method' => 'GET',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants?page_size=300&status=$status",
            
		];
        
		return $this;
	}

    public function getWebinarRegistrantQuestions(Webinar $webinar)
	{
        
        $this->params = [
			'method' => 'GET',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants/questions",
            
		];
        
		return $this;
	}
    
    // public function getWebinarRegistrant($webinarId, $registrantId)
	// {

        
        // $this->params = [
			// 'method' => 'GET',
			// 'uri' => "/v2/webinars/$webinarId/registrants/$registrantId",
            
		// ];
        
		// return $this;
	// }
    public function addWebinarUserRegistrant(Webinar $webinar, User $user)
	{   
        list($lastName, $firstName) = explode(' ', $user->getAttribute('name'));
        
        if($user->parents()->exists()){
            $org=$user->parents()->first()->name;
        } elseif($user->type_id!=3){
            $org=$user->name;
        } else {
            $org="не указано";
        }
        
         $this->params = [
			'method' => 'POST',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants",
            'data' => [
				'key' => 'json',
				'value' => [
                            "email" => "$user->email",
                            "first_name" => $firstName,
                              "last_name" => $lastName,
                              "address" => $user->addresses()->first()->full,
                              "city" => $user->addresses()->first()->locality,
                              "country" => $user->addresses()->first()->country->alpha2,
                              "phone" => $user->contacts()->first()->phones()->first()->number,
                              "state" => $user->addresses()->first()->region->name,
                              "org" => $org,
                              "job_title" => 'service.ferroli.ru/admin/users/$user->id',
                              "comments" => "service.ferroli.ru/admin/users/$user->id",
                                                    ],
			],

		];
        
        return $this;
	}
    
    public function cancelWebinarUserRegistrant(Webinar $webinar, User $user)
	{   
         $zoom_registrant_id=$webinar->participants()->find($user->id)->pivot->zoom_registrant_id;
         
         $this->params = [
			'method' => 'PUT',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants/status",
            'data' => [
				'key' => 'json',
				'value' => [
                            "action" => "cancel",
                            "registrants" => [
                                    ["id"=> $zoom_registrant_id]]
                                                    ],
			],

		];
        //dd($this);
		return $this;
	}
    
    public function statusWebinarUserRegistrant(Webinar $webinar, User $user)
	{   
         $zoom_registrant_id=$webinar->participants()->find($user->id)->pivot->zoom_registrant_id;
         
         $this->params = [
			'method' => 'GET',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants/status",
            'data' => [
				'key' => 'json',
				'value' => [
                            "action" => "cancel",
                            "registrants" => [
                                    ["id"=> $zoom_registrant_id]]
                                                    ],
			],

		];
        //dd($this);
		return $this;
	}
    public function getWebinarRegistrant(Webinar $webinar,$registrant_id)
	{
         $this->params = [
			'method' => 'GET',
			'uri' => "/v2/webinars/$webinar->zoom_id/registrants/$registrant_id"

		];
        //dd($this);
		return $this;
	}
    
    public function getWebinar(Webinar $webinar)
	{

        
        $this->params = [
			'method' => 'GET',
			//'uri' => "/v2/webinars/$webinar->zoom_id",
			'uri' => "/v2/report/webinars/$webinar->zoom_id",
            
		];
        
		return $this;
	}
    
    public function getWebinarStatistic(Webinar $webinar)
	{

        
        $this->params = [
			'method' => 'GET',
			'uri' => "/v2/report/webinars/$webinar->zoom_id/participants?page_size=300",
            
		];
        
		return $this;
	}

	

	/**
	 * @return bool|mixed
	 */
	public function json()
	{
		try {
			$response = $this->request();
			if ($response->getStatusCode() === Response::HTTP_OK) {
				$data = json_decode($response->getBody(), true);
				if ($data['code'] == 0) {
					return $data;
				}
				$this->logException($this->params['uri'], $data['message']);
			} else {
				$this->logException($this->params['uri'], $response->getReasonPhrase());
			}
		} catch (ZoomException $exception) {
			$this->logException($this->params['uri'], $exception->getMessage());
		} catch (GuzzleException $exception) {
			$this->logException($this->params['uri'], $exception->getMessage());
		}

		return false;
	}

	/**
	 * Отправить запрос
	 *
	 * @return mixed|\Psr\Http\Message\ResponseInterface
	 * @throws ZoomException
	 * @throws GuzzleException
	 */
	public function request()
	{
		$this->check();

		$this->client = new \GuzzleHttp\Client($this->options);

		if($this->params['method']=='POST'){
            return $this->client->request($this->params['method'],$this->params['uri'],[$this->params['data']['key'] => $this->params['data']['value']]);
           
        } elseif($this->params['method']=='PUT'){
            return $this->client->request($this->params['method'],$this->params['uri'],[$this->params['data']['key'] => $this->params['data']['value']]);
        } elseif($this->params['method']=='PATCH'){
            return $this->client->request($this->params['method'],$this->params['uri'],[$this->params['data']['key'] => $this->params['data']['value']]);
        } elseif($this->params['method']=='GET'){
            try {
                return $this->client->request($this->params['method'],$this->params['uri']);
            } catch (GuzzleHttp\Exception\RequestException $exception) {
                    return back()->withError($exception->getMessage())->withInput();
            }
        }
        
		
	}

	/**
	 * @throws ZoomException
	 */
	private function check()
	{
		if (is_null($this->options['base_uri'])) {
			throw new ZoomException(trans('site::webinar.zoom.exceptions.url_not_set'));
		}

		if (!key_exists('method', $this->params)) {
			throw new ZoomException(trans('site::webinar.zoom.exceptions.method_not_set'));
		}

		if (!key_exists('uri', $this->params)) {
			throw new ZoomException(trans('site::webinar.zoom.exceptions.method_not_set'));
		}

	}

	private function logException($method, $message)
	{
		event(new ExceptionEvent($method, $message));
	}
}