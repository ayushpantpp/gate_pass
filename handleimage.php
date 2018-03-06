<?php
$name = date('YmdHis');
$newname="images/".$name.".jpg";
$file = file_put_contents( $newname, file_get_contents('php://input') );
if (!$file) {
	print "200";
	exit();
}
else
{
    //$_SESSION["myvalue"]=$value;	
}
print "$newname\n";
//$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $newname;
//print "$url\n";

?>
