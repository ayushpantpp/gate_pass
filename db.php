<?php

$conn = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 172.168.2.174)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))";
$con = oci_connect("system", "manager1", $conn);
if ($con) {
    //echo 'Successfully connected to Oracle.';
    //  OCILogoff($con);
} else {
    $err = OCIError();
    echo 'Connection failed.' . $err[text];
}


$conn1 = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 132.132.5.86)(PORT = 1521)))(CONNECT_DATA=(SERVICENAME=orcl.essdomain.com)))";
$con1 = oci_connect("system", "manager1", $conn);
if ($con1) {
    //echo 'Successfully connected to Oracle.';
    //  OCILogoff($con);
} else {
    $err = OCIError();
    //echo 'Connection failed.' . $err[text];
}

?>
