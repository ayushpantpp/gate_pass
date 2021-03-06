<style>
ul.paginate li {
	display: inline-block;
	padding: 3px;
	 background: #2A49A5;
    	border: 1px solid #082783;
    	box-shadow: 0 1px #4C6BC7 inset;
    	font-weight:bold;
	color: white;
	margin: 6px;
}
ul.paginate li.current {
	background-color:orange;
}
</style>
<?php
error_reporting(0);
include 'header.php';
include 'db.php';
include 'paging.php';

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
    $empname = "SELECT EMP_NM FROM EBIZ.org" . '$hcm$emp$prf' . " WHERE EMP_CODE = '" . $ID . "'";
    $statement = oci_parse($con, $empname);
    oci_execute($statement);
    oci_fetch_all($statement, $output1);
    //echo '<pre>';print_r($output1);die;
    $name1 = $output1['EMP_NM'][0];
    return $name1;
}

if (!empty($_REQUEST['gtnumber_1'])) {
    $conditions .= 'GATE_PASS_NO=' . $_REQUEST['gtnumber_1'] . ' AND ';
}
if (!empty($_REQUEST['gtnumber_2'])) {
    $conditions .= "UPPER(VISITOR_NAME)='" . strtoupper($_REQUEST['gtnumber_2']) . "' AND ";
}
if (!empty($_REQUEST['gtnumber_3'])) {
    $conditions .= "UPPER(ORG_NAME)='" . strtoupper($_REQUEST['gtnumber_3']) . "' AND ";
}
if (!empty($_REQUEST['dept'])) {
    $conditions .= "DEPT_ID='" . $_REQUEST['dept'] . "' AND ";
}
if (!empty($_REQUEST['contactperson'])) {
    $conditions .= "EMP_MEET='" . $_REQUEST['contactperson'] . "' AND ";
}
if (!empty($_REQUEST['fromdate'])) {
    $conditions .= "GATE_PASS_DATE>='" . $_REQUEST['fromdate'] . "' AND ";
}
if (!empty($_REQUEST['todate'])) {
    $conditions .="GATE_PASS_DATE<='" . $_REQUEST['todate'] . "' AND ";
}
$limit = 20;
if(isset($_GET['page']) && !empty($_GET['page']))
	$page = $_GET['page'];
else
	$page = 1;
$offset = ($page - 1) * $limit;
$maxRnum = $offset + $limit;
$countquery = "SELECT COUNT(ID) as totalgp FROM EBIZ.VISITOR_GATE_PASS WHERE $conditions 1=1 ORDER BY GATE_PASS_NO DESC";
$getvisitordetail = "SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS,RWNUM FROM ( SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS,ROWNUM as RWNUM FROM (SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS FROM EBIZ.VISITOR_GATE_PASS WHERE $conditions 1=1 ORDER BY GATE_PASS_NO DESC)) WHERE RWNUM > $offset AND RWNUM <= $maxRnum";
//die;
$statement = oci_parse($con, $getvisitordetail);
oci_execute($statement);
oci_fetch_all($statement, $res2);
$statement = oci_parse($con, $countquery);
oci_execute($statement);
oci_fetch_all($statement, $countarr);
$count = $countarr['TOTALGP'][0];
//echo "<pre>"; print_r($res2); die;
//$GATE_PASS=$res2['GATE_PASS_NO'][1];

$arr = array();
for ($a = 0; $a < count($res2['GATE_PASS_NO']); $a++) {
    $arr[] = array('ID' => $res2['ID'][$a], 'GATE_PASS_NO' => $res2['GATE_PASS_NO'][$a], 'GATE_PASS_DATE' => $res2['GATE_PASS_DATE'][$a],
        'VISITOR_NAME' => $res2['VISITOR_NAME'][$a], 'ORG_NAME' => $res2['ORG_NAME'][$a], 'LOCATION' => $res2['LOCATION'][$a],
        'DEPT_ID' => $res2['DEPT_ID'][$a], 'TIME_IN' => $res2['TIME_IN'][$a], 'TIME_OUT' => $res2['TIME_OUT'][$a], 'EMP_MEET' => $res2['EMP_MEET'][$a],
        'ID_NUM' => $res2['ID_NUM'][$a], 'STATUS' => $res2['STATUS'][$a],);
}
//echo "<pre>";print_r($arr);
//echo '<pre>' ;print_r($_REQUEST);
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
                            <div style="float:left; width:50%; text-align:right;"> <a href="<?php echo $root_url; ?>/index.php"><b>Gate Pass Entry</b></a></div>
                        </div>
                        <div class="form-top-right"> <i class="fa fa-pencil"></i> </div>

                    </div>
                    <div class="form-bottom1" style="width:100%;">
                        <table class="col-md-12 table-bordered table-striped table-condensed cf">
                            <tr>
                                <td><form name="summary" action="summary.php" method="GET">
                                        <table width="100%" border="0" cellspacing="1" cellpadding="3">
                                            <tr>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Gate Pass NO:
                                                </td>
                                                <td width="17%" align="left" valign="middle">  
                                                    <input type="text" value="<?php echo $_REQUEST['gtnumber_1']; ?>" name="gtnumber_1" placeholder="Gate Pass No...." id="gtnumber_1" onkeyup="showgtno(this.value, '1');" autocomplete="off">
                                                    <ul id="gtnumber_show_1" class="gtnumber_show"></ul>
                                                </td>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Visitor Name:</td>
                                                <td width="17%" align="left" valign="middle">
                                                    <input type="text" value="<?php echo $_REQUEST['gtnumber_2']; ?>" name= "gtnumber_2" placeholder="Visitor Name...." id="gtnumber_2" onkeyup="showgtno(this.value, '2');"  autocomplete="off">
                                                    <ul id="gtnumber_show_2" class="gtnumber_show"></ul>
                                                </td>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Organization Name:</td>
                                                <td width="17%" align="left" valign="middle">
                                                    <input  type="text" value="<?php echo $_REQUEST['gtnumber_3']; ?>" name= "gtnumber_3" placeholder="Organization Name...." id="gtnumber_3" onkeyup="showgtno(this.value, '3');"   autocomplete="off">
                                                    <ul id="gtnumber_show_3" class="gtnumber_show"></ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Department: </td>
                                                <td width="17%" align="left" valign="middle"><?php
                                                    global $con;
                                                    $query = "SELECT DISTINCT DEPT_ID FROM EBIZ.VISITOR_GATE_PASS";
                                                    $statement = oci_parse($con, $query);
                                                    oci_execute($statement);
                                                    oci_fetch_all($statement, $res3);
                                                    ?>
                                                    <select name="dept" class="form-first-name form-control" id="dept">
                                                        <option  value="" selected>--SELECT--</option>
                                                        <?php
                                                        for ($i = 0; $i < count($res3['DEPT_ID']); $i++) {
                                                            $selected = '';
                                                            if ($_REQUEST['dept'] == $res3['DEPT_ID'][$i]) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                            echo '<option  value="' . $res3['DEPT_ID'][$i] . '"' . $selected . '>' . getdeptname($res3['DEPT_ID'][$i]) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">Contact Person:</td>
                                                <td width="17%" align="left" valign="middle"><?php
                                                    global $con;
                                                    $query = "SELECT DISTINCT EMP_MEET FROM EBIZ.VISITOR_GATE_PASS";
                                                    $statement = oci_parse($con, $query);
                                                    oci_execute($statement);
                                                    oci_fetch_all($statement, $res4);
                                                    ?>
                                                    <select name="contactperson" class="form-first-name form-control" id="contactperson">
                                                        <option  value="" selected>--SELECT--</option>
                                                        <?php
                                                        for ($i = 0; $i < count($res4['EMP_MEET']); $i++) {
                                                            $selected = '';
                                                            if ($_REQUEST['contactperson'] == $res4['EMP_MEET'][$i]) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                            echo '<option  value="' . $res4['EMP_MEET'][$i] . '"' . $selected . '>' . getempname($res4['EMP_MEET'][$i]) . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">From Date:</td>
                                                <?php
                                                $selected = '';
                                                if (isset($_REQUEST['fromdate'])) {
                                                    echo $selected = $_REQUEST['fromdate'];
                                                    //die;
                                                }
                                                ?>
                                                <td width="17%" align="left" valign="middle"><input  type="text" value="<?php echo $selected; ?>" name= "fromdate" id="fromdate"></td>
                                                <td width="13%" height="60px" align="right" valign="middle" class="right-mar">To Date:</td>
                                                <?php
                                                $selected = '';
                                                if (isset($_REQUEST['todate'])) {
                                                    echo $selected = $_REQUEST['todate'];
                                                    //die;
                                                }
                                                ?>
                                                <td width="17%" align="left" valign="middle"><input  type="text" value="<?php echo $selected; ?>" name= "todate" id="todate"></td>
                                                <td width="13%" align="right" valign="middle" class="right-mar">&nbsp;</td>
                                                <td width="17%" align="left" valign="middle">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td width="13%" align="right" valign="middle">&nbsp;</td>
                                                <td width="17%" align="left" valign="middle"><input type="submit" name="Submit" value="Search" class="shiva"></td>
                                                <td width="13%" align="right" valign="middle">&nbsp;</td>
                                                <td width="17%" align="left" valign="middle">&nbsp;</td>
                                                <td width="13%" align="right" valign="middle">&nbsp;</td>
                                                <td width="17%" align="left" valign="middle">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </form></td>
                            </tr>
                        </table>
                        <form name="frm2" action="downloadpdf.php" method="post">
                            <input type="hidden" name="data_arr"  value='<?php echo json_encode($arr); ?>' />
                            <input type="Submit" id="downloadfile" value="Download File" name="btn" class='shiva'>
                        </form>
                    </div>
                    <script type="text/javascript">
                        var requests = new Array();

                        function showgtno(val, type) {
                            if (val == '') {
                                $('#gtnumber_show_' + type).html('');
                                $('#gtnumber_show_' + type).hide();
                                return false;
                            }
                            requests.push(
                                    $.ajax({
                                        type: "GET",
                                        cache: false,
                                        beforeSend: function () {
                                            for (i = 0; i < requests.length; i++) {
                                                requests[i].abort();
                                            }
                                        },
                                        url: "ajax.php?id=" + val + "&type=" + type,
                                        success: function (data) { //alert(data);
                                            $("#gtnumber_show_" + type).html(data);
                                            $("#gtnumber_show_" + type).show();

                                            $('#gtnumber_show_' + type + ' li').click(function () {
                                                var return_value = $(this).text();
                                                //$('#gtnumber_' + type).attr('value', return_value);
                                                $('#gtnumber_' + type).val(return_value);
                                                $('#gtnumber_show_' + type).html('');
                                                $('#gtnumber_show_' + type).hide();

                                            });
                                        }
                                    })
                                    );
                        }
//$('html').bind('keypress', function (e)
                        $(document).keyup(function (e)
                        {
                            if (e.keyCode == 27)
                            {
                                $('#gtnumber_show_1').html('');
                                $('#gtnumber_show_1').hide();
                                $('#gtnumber_show_2').html('');
                                $('#gtnumber_show_2').hide();
                                $('#gtnumber_show_3').html('');
                                $('#gtnumber_show_3').hide();
                                for (i = 0; i < requests.length; i++) {
                                    requests[i].abort();
                                }
                                return false;
                            }
                        });


                        $("#fromdate").datepicker({
                            dateFormat: 'dd-M-y',
                            changeMonth: true,
                            changeYear: true,
                            onSelect: function (selected) {

                                $("#todate").datepicker("option", "minDate", selected)

                            }

                        });
                        $("#todate").datepicker({
                            dateFormat: 'dd-M-y',
                            changeMonth: true,
                            changeYear: true,
                            onSelect: function (selected) {

                                $("#fromdate").datepicker("option", "maxDate", selected)

                            }
                        });
                    </script>
                    <div class="form-bottom table-responsive" style="width:100%; padding:0px; overflow-x: scroll;">
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
                                    <td><?php echo strtoupper($arr[$i]['VISITOR_NAME']); ?></td>
                                    <td><?php echo strtoupper($arr[$i]['ORG_NAME']); ?></td>
                                    <td><?php echo strtoupper($arr[$i]['LOCATION']); ?></td>
                                    <td><?php echo strtoupper(getdeptname($arr[$i]['DEPT_ID'])); ?></td>
                                    <td><?php echo $arr[$i]['TIME_IN']; ?></td>
                                    <td><?php echo $arr[$i]['TIME_OUT']; ?></td>
                                    <td><?php echo strtoupper(getempname($arr[$i]['EMP_MEET'])); ?></td>
                                    <td style="word-break: break-all;" width="100px"><?php echo $arr[$i]['ID_NUM']; ?></td>
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
		    <div class="form-bottom table-responsive" >
			<?php
			$paging = new Paging();
			$totalPages = ceil($count/$limit);
			if($totalPages > 0){
				echo $paging->pagesDiv($limit,$count,$page);
			}
			?>
			
		
		    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .gtnumber_show{position:absolute;z-index:99;  background: #f8f8f8 none repeat scroll 0 0;border: 1px solid #ddd;border-radius: 4px;box-shadow: none;color: #888;font-family: 'Roboto',sans-serif;font-size: 14px;font-weight: 300;line-height: 35px;margin: 0;padding: 0 10px;transition: all 0.3s ease 0s;vertical-align: middle;width:206px;display:none;}
    .gtnumber_show li{list-style:none;}
    .gtnumber_show li a{color:#888;}
</style>
