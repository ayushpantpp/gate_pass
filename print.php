<?php
error_reporting(0);

include 'header_print.php';
include 'db.php';

global $con;

function getdeptname($ID) {
    global $con;
    $deptname = "SELECT NAME FROM EBIZ.DEPARTMENT WHERE ID = $ID";
    $statement = oci_parse($con, $deptname);
    oci_execute($statement);
    oci_fetch_all($statement, $output);
    //echo '<pre>';print_r($output);die;
    $name = $output['NAME'][0]; //die;
    return $name;
}

function getempname($ID) {
    global $con;
    $empname = "SELECT * FROM EBIZ.org" . '$hcm$emp$prf' . " WHERE EMP_CODE = '".$ID."'";
    $statement = oci_parse($con, $empname);
    oci_execute($statement);
    oci_fetch_all($statement, $output1);
    //echo '<pre>';print_r($output1);die;
    $name1 = $output1['INITIAL'][0].' '.$output1['EMP_NM'][0];
    return $name1;
}
$id = @$_GET['id'];
if(!empty($id))
		$condition = "AND ID=".$id;
else
		$condition = '';
$getvisitordetail = "SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,CONTACT_NUMBER,PURPOSE,NUM_OF_PERSON,MATERIAL,BELONGINGS,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS FROM EBIZ.VISITOR_GATE_PASS WHERE $conditions 1=1 $condition ORDER BY GATE_PASS_NO DESC";
//die;
$statement = oci_parse($con, $getvisitordetail);
oci_execute($statement);
oci_fetch_all($statement, $res2);
//echo "<pre>"; print_r($res2);die;

$arr = array();

for ($a = 0; $a < count($res2['GATE_PASS_NO']); $a++) {
    $arr[] = array('ID' => $res2['ID'][$a], 'GATE_PASS_NO' => $res2['GATE_PASS_NO'][$a], 'GATE_PASS_DATE' => $res2['GATE_PASS_DATE'][$a],
        'VISITOR_NAME' => $res2['VISITOR_NAME'][$a], 'ORG_NAME' => $res2['ORG_NAME'][$a], 'LOCATION' => $res2['LOCATION'][$a],
        'DEPT_ID' => $res2['DEPT_ID'][$a], 'TIME_IN' => $res2['TIME_IN'][$a], 'TIME_OUT' => $res2['TIME_OUT'][$a], 'INITIAL' => $res2['INITIAL'][$a], 'EMP_MEET' => $res2['EMP_MEET'][$a],
        'ID_NUM' => $res2['ID_NUM'][$a], 'STATUS' => $res2['STATUS'][$a], 'CONTACT_NUMBER' => $res2['CONTACT_NUMBER'][$a], 'PURPOSE' => $res2['PURPOSE'][$a],
        'NUM_OF_PERSON' => $res2['NUM_OF_PERSON'][$a], 'MATERIAL' => $res2['MATERIAL'][$a], 'BELONGINGS' => $res2['BELONGINGS'][$a]);
}
//echo "<pre>";print_r($arr);die;
//echo '<pre>' ;print_r($_POST);
?>

<div class="container">
            <div class="row">
                <div class="col-sm-12 form-box">
                     <div class="form-bottom table-responsive" style="width:100%; padding:0px;">
                        <table class="table" width="100%" cellspacing="1" cellpadding="0" border="0" align="center" id="print-padd">
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Gate Pass Number :</td>
                                <td width="20%" align="left" valign="middle"><?php echo $arr[0]['GATE_PASS_NO']; ?></td>
                                <td width="25%" align="right" valign="middle" class="print-padd">Gate Pass Date :</td>
                                <td width="30%" align="left" valign="middle"><?php echo $arr[0]['GATE_PASS_DATE']; ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Visitor Name :</td>
                                <td align="left" valign="middle"><?php echo strtoupper($arr[0]['VISITOR_NAME']); ?></td>
                                <td align="right" valign="middle" class="print-padd">Organization Name :</td>
                                <td align="left" valign="middle"><?php echo strtoupper($arr[0]['ORG_NAME']); ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Location :</td>
                                <td align="left" valign="middle"><?php echo strtoupper($arr[0]['LOCATION']); ?></td>
                                <td align="right" valign="middle" class="print-padd">Contact Number :</td>
                                <td align="left" valign="middle"><?php echo $arr[0]['CONTACT_NUMBER']; ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Whom to Meet :</td>
                                <td align="left" valign="middle"><?php echo strtoupper(getempname($arr[0]['EMP_MEET'])); ?></td>
                                <td align="right" valign="middle" class="print-padd">Purpose :</td>
                                <td align="left" valign="middle"><?php echo strtoupper($arr[0]['PURPOSE']); ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Department of Visit :</td>
                                <td align="left" valign="middle"><?php echo strtoupper(getdeptname($arr[0]['DEPT_ID'])); ?></td>
                                <td align="right" valign="middle" class="print-padd">Number of Person :</td>
                                <td align="left" valign="middle"><?php echo $arr[0]['NUM_OF_PERSON']; ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Material Declaration :</td>
                                <td align="left" valign="middle"><?php echo strtoupper($arr[0]['BELONGINGS']); ?></td>
                  <!--              <td align="right" valign="middle" class="print-padd">Material Returnable :</td>
                                <td align="left" valign="middle"><?php //echo $arr[0]['MATERIAL']; ?></td>-->
                                <td align="right" valign="middle" class="print-padd">Time In :</td>
                                <td align="left" valign="middle"><?php echo $arr[0]['TIME_IN']; ?></td>
                            </tr>
                            <tr>
                                <td width="25%" align="right" valign="middle" class="print-padd">Time Out :</td>
                                <td align="left" valign="middle"><?php echo $arr[0]['TIME_OUT']; ?></td>
                                <td align="right" valign="middle" class="print-padd">ID Card Number :</td>
                                <td align="left" valign="middle"><?php echo $arr[0]['ID_NUM']; ?></td>
                            </tr>
                <!--            <tr>
                              
                              <td align="right" valign="middle">&nbsp;</td>
                              <td align="left" valign="middle">&nbsp;</td>-->
                            </tr>
                             <!--<tr>
                                <td width="15%" align="right" valign="middle" class="print-padd" height="50px" style="line-height:50px;">Guard Signature :</td>
                                <td align="left" valign="middle">&nbsp;</td>
                                <td align="right" valign="middle">&nbsp;</td>
                                <td align="left" valign="middle" class="print-padd" height="50px" style="line-height:50px; text-align:left !important;">Visitor Signature :</td>
                            </tr>-->
                            <tr>
                                <td height="50px" colspan="4" align="left" valign="middle" >
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
									<td width="20%" height="25" align="center" valign="middle" class="print-padd" style="text-align:center !important;">Visitor Signature :</td>
                                    <td width="20%" height="25" align="center" valign="middle" class="print-padd" style="text-align:center !important;">Guard Signature :</td>
                                    <td width="20%" align="center" valign="middle" class="print-padd" style="text-align:center !important;">Contact Person Name :</td>
                                    <td width="20%" align="center" valign="middle" class="print-padd" style="text-align:center !important;">Contact Person employee Id :</td>
									<td width="20%" align="center" valign="middle" class="print-padd" style="text-align:center !important;">Contact Person Signature :</td>
                                  </tr>
                                  <tr>
                                    <td height="25" align="center" valign="middle" style="text-align:center !important;"></td>
                                    <td width="20%" height="25" align="center" valign="middle" style="text-align:center !important;"></td>
                                    <td width="20%" align="center" valign="middle" style="text-align:center !important;"></span></td>
                                    <td width="20%" align="center" valign="middle" style="text-align:center !important;"></span></td>
                                    <td width="20%" align="center" valign="middle" style="text-align:center !important;"></span></td>
                                  </tr>
                                </table>
								</td>
                            </tr>
                        </table>   
                    </div>
                </div>
            </div>
        </div>
		<script>
		window.print();
		setTimeout(function(){window.location.assign('summary.php')},1000);;
		</script>