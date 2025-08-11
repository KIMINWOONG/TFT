<?php
include "./_common.php";

$sql="select * from g5_conference_registration where cr_id='{$cr_id}'";
$registration=sql_fetch($sql);

$sql="update g5_conference_registration set cr_status='cancelled' where cr_id='{$cr_id}'";
sql_query($sql);

?>
