<?php
include 'header.php';
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
    $empname = "SELECT EMP_NM FROM EBIZ.org" . '$hcm$emp$prf' . " WHERE EMP_CODE = $ID";
    $statement = oci_parse($con, $empname);
    oci_execute($statement);
    oci_fetch_all($statement, $output1);
    //echo '<pre>';print_r($output1);die;
    $name1 = $output1['EMP_NM'][0];
    return $name1;
}

if (!empty($_POST['gtnumber'])) {
    $conditions .= 'GATE_PASS_NO=' . $_POST['gtnumber'] . ' AND ';
}
if (!empty($_POST['visitorname'])) {
    $conditions .= "VISITOR_NAME='" . $_POST['visitorname'] . "' AND ";
}
if (!empty($_POST['orgname'])) {
    $conditions .= "ORG_NAME='" . $_POST['orgname'] . "' AND ";
}
if (!empty($_POST['fromdate'])) {
    $conditions .= "GATE_PASS_DATE='" . $_POST['fromdate'] . "' AND ";
}
if (!empty($_POST['todate'])) {
    $conditions .="GATE_PASS_DATE='" . $_POST['todate'] . "' AND ";
}

$getvisitordetail = "SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS FROM EBIZ.VISITOR_GATE_PASS WHERE $conditions 1=1 ORDER BY GATE_PASS_NO DESC";
//die;
$statement = oci_parse($con, $getvisitordetail);
oci_execute($statement);
oci_fetch_all($statement, $res2);
//echo "<pre>"; print_r($res2);die;

$arr = array();

for ($a = 0; $a < count($res2['GATE_PASS_NO']); $a++) {
    $arr[] = array('ID' => $res2['ID'][$a], 'GATE_PASS_NO' => $res2['GATE_PASS_NO'][$a], 'GATE_PASS_DATE' => $res2['GATE_PASS_DATE'][$a],
        'VISITOR_NAME' => $res2['VISITOR_NAME'][$a], 'ORG_NAME' => $res2['ORG_NAME'][$a], 'LOCATION' => $res2['LOCATION'][$a],
        'DEPT_ID' => $res2['DEPT_ID'][$a], 'TIME_IN' => $res2['TIME_IN'][$a], 'TIME_OUT' => $res2['TIME_OUT'][$a], 'EMP_MEET' => $res2['EMP_MEET'][$a],
        'ID_NUM' => $res2['ID_NUM'][$a], 'STATUS' => $res2['STATUS'][$a],);
}
//echo "<pre>";print_r($arr);die;
?>

<div class="top-content">
  <div class="inner-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 form-box">
          <div class="form-top">
            <div class="form-top-left">
              <div style="float:left; width:50%;">
                <h3>Visitor Gate Pass Summary</h3>
              </div>
              <div style="float:left; width:50%; text-align:right;"> <a href="/icat/index.php"><b>Gate Pass Entry</b></a></div>
            </div>
            <div class="form-top-right"> <i class="fa fa-pencil"></i> </div>
            <!-- <div class="form-top-left">
                            

                        </div>--> 
          </div>
          <div class="form-bottom1" style="width:100%;">
            <table class="col-md-12 table-bordered table-striped table-condensed cf">
              <tr>
                <td><form name="summary" action="summary.php" method="POST">
                    <table width="100%" border="0" cellspacing="1" cellpadding="3">
                      <tr>
                        <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Gate Pass NO:</td>
                        <td width="17%" align="left" valign="middle"><?php
//                                                    
                                                    global $con;
                                                    $query = "SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO FROM EBIZ.VISITOR_GATE_PASS ORDER BY GATE_PASS_NO DESC";
                                                    $statement = oci_parse($con, $query);
                                                    oci_execute($statement);
                                                    oci_fetch_all($statement, $output);
                                                    ?>
                          <select name="gtnumber" class="form-first-name form-control" id="gtnumber">
                            <option  value="" selected>--SELECT--</option>
                            <?php
                                                        for ($i = 0; $i < count($output['GATE_PASS_NO']); $i++) {
                                                            echo '<option  value="' . $output['GATE_PASS_NO'][$i] . '">' . $output['GATE_PASS_NO'][$i] . '</option>';
                                                        }
                                                        ?>
                          </select></td>
                        <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Visitor Name:</td>
                        <td width="17%" align="left" valign="middle"><?php
                                                    global $con;
                                                    $query = "SELECT DISTINCT VISITOR_NAME FROM EBIZ.VISITOR_GATE_PASS";
                                                    $statement = oci_parse($con, $query);
                                                    oci_execute($statement);
                                                    oci_fetch_all($statement, $res);
                                                    ?>
                          <select name="visitorname" class="form-first-name form-control" id="visitorname">
                            <option  value="" selected>--SELECT--</option>
                            <?php
                                                        for ($i = 0; $i < count($res['VISITOR_NAME']); $i++) {
                                                            echo '<option  value="' . $res['VISITOR_NAME'][$i] . '">' . $res['VISITOR_NAME'][$i] . '</option>';
                                                        }
                                                        ?>
                          </select></td>
                        <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Organization Name:</td>
                        <td width="17%" align="left" valign="middle"><?php
                                                    global $con;
                                                    $query = "SELECT DISTINCT ORG_NAME FROM EBIZ.VISITOR_GATE_PASS";
                                                    $statement = oci_parse($con, $query);
                                                    oci_execute($statement);
                                                    oci_fetch_all($statement, $res1);
                                                    ?>
                          <select name="orgname" class="form-first-name form-control" id="orgname">
                            <option  value="" selected>--SELECT--</option>
                            <?php
                                                        for ($i = 0; $i < count($res1['ORG_NAME']); $i++) {
                                                            echo '<option  value="' . $res1['ORG_NAME'][$i] . '">' . $res1['ORG_NAME'][$i] . '</option>';
                                                        }
                                                        ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td width="13%" height="60px" align="right" valign="middle" class="right-mar">From Date:</td>
                        <td width="17%" align="left" valign="middle"><input  type="text" name= "fromdate" id="fromdate" readonly="readonly"></td>
                        <td width="13%" height="60px" align="right" valign="middle" class="right-mar">To Date:</td>
                        <td width="17%" align="left" valign="middle"><input  type="text" name= "todate" id="todate" readonly="readonly"></td>
                        <td width="13%" align="right" valign="middle" class="right-mar">&nbsp;</td>
                        <td width="17%" align="left" valign="middle">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="13%" align="right" valign="middle">&nbsp;</td>
                        <td width="17%" align="left" valign="middle"><input type="submit" name="Submit" value="Search"></td>
                        <td width="13%" align="right" valign="middle">&nbsp;</td>
                        <td width="17%" align="left" valign="middle">&nbsp;</td>
                        <td width="13%" align="right" valign="middle">&nbsp;</td>
                        <td width="17%" align="left" valign="middle">&nbsp;</td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
            </table>
          </div>
          <script type="text/javascript">
                        $("#fromdate").datepicker({
                                    dateFormat: 'dd-M-y',
                                    changeMonth: true,
                                    changeYear: true,
                                    onSelect: function(selected) {

                                        $("#todate").datepicker("option", "minDate", selected)

                                    }

                                });
                                $("#todate").datepicker({
                                    dateFormat: 'dd-M-y',
                                    changeMonth: true,
                                    changeYear: true,
                                    onSelect: function(selected) {

                                        $("#fromdate").datepicker("option", "maxDate", selected)

                                    }
                                });
                    </script>
          <div class="form-bottom table-responsive" style="width:100%; padding:0px;">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="table">
              <tr>
                <th>Gate Pass N0.</th>
                <th>Gate Pass Date</th>
                <th>Visitor Name</th>
                <th>Organization Name</th>
                <th>Location</th>
                <th>Department Of Visit</th>
                <th>Time IN</th>
                <th>Time OUT</th>
                <th>Whom To Meet</th>
                <th>ID Card No.</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              <?php for ($i = 0; $i < count($arr); $i++) { ?>
              <tr>
                <td><?php echo $arr[$i]['GATE_PASS_NO']; ?></td>
                <td><?php echo $arr[$i]['GATE_PASS_DATE']; ?></td>
                <td><?php echo $arr[$i]['VISITOR_NAME']; ?></td>
                <td><?php echo $arr[$i]['ORG_NAME']; ?></td>
                <td><?php echo $arr[$i]['LOCATION']; ?></td>
                <td><?php echo getdeptname($arr[$i]['DEPT_ID']); ?></td>
                <td><?php echo $arr[$i]['TIME_IN']; ?></td>
                <td><?php echo $arr[$i]['TIME_OUT']; ?></td>
                <td><?php echo getempname($arr[$i]['EMP_MEET']); ?></td>
                <td><?php echo $arr[$i]['ID_NUM']; ?></td>
                <td><?php echo $arr[$i]['STATUS']; ?></td>
                <td><?php
                                        if ($arr[$i]['STATUS'] == 'CLOSED' || $arr[$i]['STATUS'] == 'CANCELED') {
                                            echo '<a href="index.php?id=' . $arr[$i]['ID'] . '">View</a>';
                                        } else {
                                            echo '<a href="index.php?id=' . $arr[$i]['ID'] . '">Edit</a>';
                                        }
                                        ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
