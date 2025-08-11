<?php
include "./_common.php";

$sql="select * from g5_conference_registration where cr_id='{$cr_id}'";
$registration=sql_fetch($sql);

if($registration['cr_payment_method']=="카드" && $registration['cr_receipt_price']>0){
	$sql="select * from g5_membership_payment_log where mp_order_id='{$registration['cr_od_id']}'";
	$tmp_row=sql_fetch($sql);

	$paymentKey = $tmp_row['mp_payment_key'];

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

	if ($err) {
		//echo "cURL Error #:" . $err;
	} else {
		$sql="update g5_conference_registration set cr_payment_status='cancelled' where cr_id='{$cr_id}'";
		sql_query($sql);
	}

}

$sql="update g5_conference_registration set cr_status='cancelled' where cr_id='{$cr_id}'";
sql_query($sql);

?>
