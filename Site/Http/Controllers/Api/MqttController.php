<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Filters\EsbUserProduct\EsbUserProductSerialSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductMounterFilter;
use ServiceBoiler\Prf\Site\Filters\Product\ProductSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Product\SearchFilter;
use ServiceBoiler\Prf\Site\Filters\ProductCanBuyFilter;
use ServiceBoiler\Prf\Site\Filters\Product\LimitFilter;
use ServiceBoiler\Prf\Site\Http\Resources\EsbUserProductCollection;
use ServiceBoiler\Prf\Site\Http\Resources\EsbUserProductSmCollection;
use ServiceBoiler\Prf\Site\Models\EsbUserProduct;
use ServiceBoiler\Prf\Site\Models\Serial;
use ServiceBoiler\Prf\Site\Repositories\ProductRepository;
use ServiceBoiler\Prf\Site\Repositories\EsbUserProductRepository;

class MqttController extends Controller
{
    protected $products;
    protected $status;

    /**
     * Create a new controller instance.
     *
     * @param ProductRepository $products
     */
    public function __construct(ProductRepository $products, EsbUserProductRepository $esbUserProducts)
    {
        $this->products = $products;
        $this->esbUserProducts = $esbUserProducts;
    }

    public function getArcStatus($deviceId)
    {
    $host='146.158.13.233';
    $port='8086';
    $dbname='TEST';
    
    $database = \InfluxDB\Client::fromDSN(sprintf('influxdb://mqttuser:mqttpassword@%s:%s/%s', $host, $port, $dbname));

    $client = $database->getClient();
    
    $database = $client->selectDB($dbname);

    $result = $database->query("select value from mqtt_consumer WHERE topic = '$deviceId/prefferedTemp' ORDER BY time DESC LIMIT 1");
    $points = $result->getPoints();
    $this->status['prefferedTemp'] = $points[0]['value'];    
    
    $result = $database->query("select value from mqtt_consumer WHERE topic = '$deviceId/temperature' ORDER BY time DESC LIMIT 1");
    $points = $result->getPoints();
    $this->status['temperature'] = $points[0]['value']; 
    
    $result = $database->query("select value from mqtt_consumer WHERE topic = '$deviceId/flame' ORDER BY time DESC LIMIT 1");
    $points = $result->getPoints();
    $this->status['flame'] = $points[0]['value'];   
    
    return $this->status;
    
    }
    
    public function getTemperature($deviceId)
    {
        $server   = '146.158.13.233';
        $port     = 1883;
        $clientId = 'php-sm';

        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUsername('mqttuser')
            ->setPassword('mqttpassword');
        
       
        
        $mqtt->connect($connectionSettings, true);
        $mqtt->publish('$deviceId/prefferedTemp', '44', 0);
        $mqtt->disconnect();
        $mqtt->connect($connectionSettings, true);
        // $mqtt->subscribe('$deviceId/prefferedTemp', function ($topic, $message)  use ($mqtt) {
            // $this->status['prefferedTemp'] = $message;
           //$mqtt->interrupt();
            
        // },1);
        
        $mqtt->subscribe($deviceId.'/temperature', function ($topic, $message)  use ($mqtt) {
            $this->status['temperature'] = $message;
            
        },0);
       
         $mqtt->subscribe($deviceId.'/flame', function ($topic, $message)  use ($mqtt) {
             $this->status['flame'] = $message;
             $mqtt->interrupt();
            
         },0);
       
       $mqtt->loop(true,true,10);
        
       // usleep(10000);
        
        $mqtt->loop(true,true);
       
        $mqtt->disconnect();
         
        return $this->status;
    }
    
    public function setTemperature(Request $request, $deviceId)
    {
        $server   = '146.158.13.233';
        $port     = 1883;
        $clientId = 'php-sm';

        
        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
        $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
            ->setUsername('mqttuser')
            ->setPassword('mqttpassword');
        
        $mqtt->connect($connectionSettings, true);
        $mqtt->publish($deviceId.'/prefferedTemp', $request->temperature, 0);
        $mqtt->disconnect();
        
        return [];
    }
    
}