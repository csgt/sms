<?php 

namespace Csgt\SMS;
use Config, View, Exception;

class SMS {
  private $apiKey;
  private $apiSecret;
  private $apiUrl;
 
  public function __construct() {
    $this->apiKey    = config("csgtsms.apikey");
    $this->apiSecret = config("csgtsms.apisecret");
    $this->apiUrl    = config("csgtsms.apiurl");

    if ($this->apiUrl[strlen($this->apiUrl)-1] !== '/') {
      $this->apiUrl .= '/';
    }
  }

  public function enviar($aNumero, $aMensaje) {
    $response = ['codigoerror'=>0, 'error'=>'', 'data'=>[]];

    $body = [
      'msisdn'  => $aNumero,
      'message' => $aMensaje,
    ];

    $body = json_encode($body);

    $url    = $this->apiUrl . 'messages/send_to_contact';

    $datetime = gmdate("D, d M Y H:i:s T");
    $authentication = $this->apiKey.$datetime.$body;
    $hash = hash_hmac("sha1",$authentication, $this->apiSecret,true);
    $hash = base64_encode($hash);
    $headers = [
      "Content-type: application/json",
      "Date: $datetime",
      "Authorization: IM $this->apiKey:$hash",
      "X-IM-ORIGIN: IM_SDK_PHP",
    ];
        
    $options = [
      'http' => [
        'header'        => $headers,
        'method'        => 'POST',
        'content'       => $body,
        'ignore_errors' => true,
      ],
    ];

    try {
      $context                 = stream_context_create($options);
      $data                    = file_get_contents($url,false, $context);
      $json                    = json_decode($data);
      $response['codigoerror'] = (property_exists($json, 'code')?$json->code:0);
      $response['error']       = (property_exists($json, 'error')?$json->error:'');
      $response['data']        = $json;
      return $response;
    }
    catch (Exception $e) {
      $response['codigoerror'] = 1;
      $response['error']       = $e->getMessage();
      return $response;
    }
  }
}