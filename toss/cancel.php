<?php
include "../common.php";

error_reporting(E_ALL);
ini_set("display_errors", true);


$paymentKey = "zzzgb_ka20250716180845SoUO8";

$url = "https://api.tosspayments.com/v1/payments/{$paymentKey}/cancel";
$data = ['paymentKey' => $paymentKey];

$secretKey = $config['cf_pg_secretkey']; 
$credential = base64_encode($secretKey . ':');


$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'cancelReason' => '구매자 변심'
  ]),
  CURLOPT_HTTPHEADER => [
    "Authorization: Basic ".$credential,
    "Content-Type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$responseJson = json_decode($response);
echo print_r($responseJson);
echo print_r($err);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}