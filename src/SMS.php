<?php 

namespace Csgt\SMS;
use Config, View, Exception;

class SMS {
  private $apiKey;
  private $apiUrl;
 
  public function __construct() {
    $this->apiKey    = config("csgtsms.apikey");
    $this->apiUrl    = config("csgtsms.apiurl");

    if ($this->apiUrl[strlen($this->apiUrl)-1] !== '/') {
      $this->apiUrl .= '/';
    }
  }

  public function enviar($aNumero, $aMensaje) {
    $response = ['codigoerror'=>0, 'error'=>'', 'data'=>[]];

    
    $params = http_build_query([
      'msisdn'  => $aNumero,
      'message' => $aMensaje,
      'api_key' => $this->apiKey
    ]);
    $url    = trim($this->apiUrl . 'send_to_contact?' . $params);

    try {

      $ch = curl_init();
      $timeout = 10;
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $data = curl_exec($ch);
      curl_close($ch);
      $json    = json_decode($data);

      if (!$json) {
        $response['codigoerror'] = 2;
        $response['error']       = $data;
        return $response;
      }

      if ($json->sms_sent==1) {
        $response['data'] = $data;
        return $response;
      }
      else {
        $response['codigoerror'] = 1;
        $response['error']       = 'Error enviando';
        $response['data']        = $data;
        return $response;
      }    
    }
    catch (Exception $e) {
      $response['codigoerror'] = 3;
      $response['error']       = $e->getMessage();
      return $response;
    }
    
  }
}