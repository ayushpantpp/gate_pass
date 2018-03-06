<?php
//error_reporting(2);
date_default_timezone_set('Asia/Kolkata');
error_reporting(0);
@ini_set('display_errors',1);
$conn = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SERVER = DEDICATED)
      (SERVICE_NAME = XE)
      ))";
$con = oci_connect("ebiz", "ebizebiz", $conn);
if ($con) { 
    /* $query = "SELECT * FROM EBIZ.VISITOR_GATE_PASS";
    $statement = oci_parse($con, $query);
    oci_execute($statement);
    oci_fetch_all($statement, $output);
    echo "<pre>"; print_r($output); */
    //echo 'Successfully connected to Oracle.';
    //OCILogoff($con);
} else { 
    $err = OCIError();
    echo 'Connection failed.' . $err[text];
    print_r($err); die;
} 

/*
$conn1 = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 132.132.5.86)(PORT = 1521)))(CONNECT_DATA=(SERVICENAME=orcl.essdomain.com)))";
$con1 = oci_connect("system", "manager1", $conn);
if ($con1) {
    //echo 'Successfully connected to Oracle.';
    //  OCILogoff($con);
} else {
    $err = OCIError();
    echo 'Connection failed.' . $err[text];
}
*/
?>

