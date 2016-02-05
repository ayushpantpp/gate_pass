<?php
include 'db.php';
global $con;
//echo $_POST['contact'].'<br/>';

$query = "SELECT * FROM EBIZ.VISITOR_GATE_PASS WHERE CONTACT_NUMBER = '".$_POST['contact']."' ORDER BY ID DESC ";
//$query = "SELECT * FROM EBIZ.VISITOR_GATE_PASS WHERE CONTACT_NUMBER = '".$_POST['contact']."' ORDER BY ID DESC OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY";
$statement = oci_parse($con, $query);
oci_execute($statement);
oci_fetch_all($statement, $res3);                            
echo json_encode($res3);         
//die;
?>