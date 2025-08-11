<?php
$sub_menu = "600210";
require_once './_common.php';

$sql = "SELECT * FROM g5_conference_registration WHERE cr_id = {$cr_id}";
$registration = sql_fetch($sql);

$sql="update g5_conference_registration set cr_payment_date='{$cr_payment_date}', cr_receipt_price='{$cr_receipt_price}', cr_update_date=now()  where cr_id='{$cr_id}'";
sql_query($sql);

if($cr_receipt_price>0 && $registration['cr_registration_fee']==$cr_receipt_price){
	$sql="update g5_conference_registration set cr_payment_status='completed' where cr_id='{$cr_id}'";
	sql_query($sql);
}

echo '<script>parent.location.reload();</script>';

?>