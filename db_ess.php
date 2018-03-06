<?php

$conn = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=132.132.5.212)(PORT=1521))(CONNECT_DATA=(SID=ESS)))";
$con = oci_connect("ebiz", "ebiz", $conn);
if ($con) {
    //echo 'Successfully connected to Oracle.';
    //  OCILogoff($con);
} else {
    $err = OCIError();
    echo 'Connection failed.' . $err[text];
}


$conn1 = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=132.132.5.212)(PORT=1521))(CONNECT_DATA=(SID=ESS)))";
$con1 = oci_connect("ebiz", "ebiz", $conn);
if ($con1) {
    //echo 'Successfully connected to Oracle.';
    //  OCILogoff($con);
} else {
    $err = OCIError();
    //echo 'Connection failed.' . $err[text];
}

?>
