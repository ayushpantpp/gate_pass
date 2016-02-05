<?php
error_reporting(2);
include 'db.php';
global $con;
$pass_no=$_GET['id'];
$type=$_GET['type'];
if($type=='1')
{ 

	$query = "SELECT DISTINCT GATE_PASS_NO FROM EBIZ.VISITOR_GATE_PASS WHERE GATE_PASS_NO LIKE '%$pass_no%' order by GATE_PASS_NO desc";
	$statement1 = oci_parse($con, $query);
	oci_execute($statement1);
	oci_fetch_all($statement1, $res0);
	$html='';
	for($i=0;$i<count($res0['GATE_PASS_NO']);$i++)
	{								
		$html.='<li style="cursor:pointer;"><a>'.$res0['GATE_PASS_NO'][$i].'</a></li>';
	}
}
if($type=='2')
{
        $pass_no=strtoupper($pass_no);
	$query = "SELECT DISTINCT VISITOR_NAME FROM EBIZ.VISITOR_GATE_PASS WHERE UPPER(VISITOR_NAME) LIKE '%$pass_no%' order by VISITOR_NAME asc";
	$statement1 = oci_parse($con, $query);
	oci_execute($statement1);
	oci_fetch_all($statement1, $res0);
	$html='';
	for($i=0;$i<count($res0['VISITOR_NAME']);$i++)
	{								
		$html.='<li style="cursor:pointer;"><a>'.$res0['VISITOR_NAME'][$i].'</a></li>';
	}
}
if($type=='3')
{
        $pass_no=strtoupper($pass_no);
	$query = "SELECT DISTINCT ORG_NAME FROM EBIZ.VISITOR_GATE_PASS WHERE UPPER(ORG_NAME) LIKE '%$pass_no%' order by ORG_NAME asc";
	$statement1 = oci_parse($con, $query);
	oci_execute($statement1);
	oci_fetch_all($statement1, $res0);
	$html='';
	for($i=0;$i<count($res0['ORG_NAME']);$i++)
	{								
		$html.='<li style="cursor:pointer;"><a>'.$res0['ORG_NAME'][$i].'</a></li>';
	}
}

echo $html;
?>