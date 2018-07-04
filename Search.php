<?php
 
    /*Dados da requisição, no caso dos campos DiscountValue, 
      IncreasePercent, IncreaseValue, só é permitido uma modalidade, isso vale para acréscimo,
      lembre-se que ambos não podem coexistir na mesma instrução*/
	  
	$transmitObject = array(
		'Cpf' => "562.496.112-07",
		'Email' => "Informe e-mail",
		'Price' => 200,
	);
	
	
	//Criando um JSON
	$jsonObject = json_encode($transmitObject);
	
	
	//Definindo a URL de requisição
	//$url = "https://api.koin.com.br/V1/AccountService.svc/Search";
	$url = "http://api.qa.koin.in:8000/V1/AccountService.svc/Search";
	
	//Chaves de autenticação
	$consumerKey = "1BFCF567A63E4B6FB38F6A22FFA21FFE"; 
	$secretKey = "50856FDA556747A7860C3295C25FEA26";
	
	//convertendo o formato do timezone para UTC 
	date_default_timezone_set("UTC");
	

	//Obtendo a hora do servidor
	$time = time();
    
	//Criando o hash de autenticação
	$binaryHash = hash_hmac('sha512', $url.$time, $secretKey, true);
	
	//Convertendo para Base64
	$hash = base64_encode($binaryHash);
	
	//Enviando a requisição
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonObject);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json; charset=utf-8", 
											   "Content-Length:".strlen($jsonObject), 
											   "Authorization: {$consumerKey},{$hash},{$time}"));
	

		
	try {
		
		$response = curl_exec($ch);
		curl_close ($ch);
		
		echo $response;
		
	}
	catch (Exception $e) 
	{
	  echo "Ocorreu um erro : ",  $e->getMessage(), "\n";
	}
	
	?>