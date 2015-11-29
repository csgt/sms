<?php
return [
  /*
	|--------------------------------------------------------------------------
	| Provider
	|--------------------------------------------------------------------------
	|
	| Empresa que presta el servicio de envio de SMS
	| Soporta:  interactuamovil
	|
	*/
	'provider'  => env('SMS_API_PROVIDER', 'interactuamovil'),
	
	/*
	|--------------------------------------------------------------------------
	| API
	|--------------------------------------------------------------------------
	|
	| Credenciales y URL del api del provider
	|
	*/
	'apikey'    => env('SMS_API_KEY', ''),
	'apisecret' => env('SMS_API_SECRET', ''),
	'apiurl'    => env('SMS_API_URL', 'https://apps.interactuamovil.com/tigocorp/api')
];