<?php
error_reporting(0);
include 'header.php';
include 'db.php';
global $con;

$TypeOfVisitorErr = $VisitorNameErr = $OrganizationNameErr = $LocationErr = $ContactNumberErr = $WhomToMeetInICATErr = $PurposeErr = $DepartmentOfVisitErr = $IndividualGroupErr = $NumberofPersonErr = $MaterialDeclarationBelongingsErr = $TimeInErr = $TimeOutErr = $MaterialReturnableNonReturnableErr = $WhetherArmedErr = $RemarksErr = $IdentityCardNumberErr = "";
$TypeOfVisitor = $VisitorName = $OrganizationName = $Location = $ContactNumber = $WhomToMeetInICAT = $Purpose = $DepartmentOfVisit = $IndividualGroup = $NumberofPerson = $MaterialDeclarationBelongings = $TimeIn = $TimeOut = $MaterialReturnableNonReturnable = $WhetherArmed = $Remarks = $IdentityCardNumber = "";

//print_r ($_REQUEST);die;

$getemp = "SELECT EMP_CODE,EMP_NM FROM EBIZ.org" . '$hcm$emp$prf' . " ORDER BY EMP_NM ASC";
$get = oci_parse($con, $getemp);
oci_execute($get);
oci_fetch_all($get, $output);

/* * ********EDIT********* */

$editid = $_GET['id'];
if ($_REQUEST['Cancel'] == 'Cancel') {
    $Insertstatus = "Update EBIZ.VISITOR_GATE_PASS set STATUS='CANCELED' WHERE ID = $editid";
    $statement = oci_parse($con, $Insertstatus);
    $save = oci_execute($statement);
    $comit = oci_commit($con);

    if (!$save) {
        ?>
        <script>
            alert("Gate Pass has been canceled successfuly");
            window.location.assign('summary.php');

        </script>
    <?php } else {
        ?>
        <script>
            alert("Gate Pass has been canceled successfuly");

            window.location.assign('summary.php');
        </script>
        <?php
    }
}

$getdetails = "SELECT lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,ID, VISITOR_TYPE, NUM_OF_PERSON, GATE_PASS_DATE, PURPOSE, ARMED, MATERIAL, GROUP_INDIVIDUAL, BELONGINGS, CONTACT_NUMBER, VISITOR_NAME, ORG_NAME, LOCATION, DEPT_ID, TIME_IN, TIME_OUT, EMP_MEET, ID_NUM, STATUS FROM EBIZ.VISITOR_GATE_PASS where ID=$editid";
$statement = oci_parse($con, $getdetails);
oci_execute($statement);
oci_fetch_all($statement, $res2);


$arr = array();

for ($a = 0; $a < count($res2['GATE_PASS_NO']); $a++) {

    // echo "<pre>";print_r($res2['GATE_PASS_NO'][0]);
    $arr[] = array('ID' => $res2['ID'][$a], 'NUM_OF_PERSON' => $res2['NUM_OF_PERSON'][$a], 'VISITOR_TYPE' => $res2['VISITOR_TYPE'][$a], 'PURPOSE' => $res2['PURPOSE'][$a], 'ARMED' => $res2['ARMED'][$a], 'MATERIAL' => $res2['MATERIAL'][$a], 'GROUP_INDIVIDUAL' => $res2['GROUP_INDIVIDUAL'][$a], 'BELONGINGS' => $res2['BELONGINGS'][$a], 'CONTACT_NUMBER' => $res2['CONTACT_NUMBER'][$a], 'GATE_PASS_NO' => $res2['GATE_PASS_NO'][$a], 'GATE_PASS_DATE' => $res2['GATE_PASS_DATE'][$a], 'VISITOR_NAME' => $res2['VISITOR_NAME'][$a], 'ORG_NAME' => $res2['ORG_NAME'][$a], 'LOCATION' => $res2['LOCATION'][$a], 'DEPT_ID' => $res2['DEPT_ID'][$a], 'TIME_IN' => $res2['TIME_IN'][$a], 'TIME_OUT' => $res2['TIME_OUT'][$a], 'EMP_MEET' => $res2['EMP_MEET'][$a], 'ID_NUM' => $res2['ID_NUM'][$a], 'STATUS' => $res2['STATUS'][$a]);
}

//echo "<pre>";print_r($arr);die;
function getMaxId() {
    global $con;
    //print_r($con);die;
    $qry = "SELECT MAX(ID)as ID FROM EBIZ.VISITOR_GATE_PASS";
    // echo $con;die;
    $valueinsert = oci_parse($con, $qry);
    oci_execute($valueinsert);
    oci_fetch_all($valueinsert, $output);

    $id = $output['ID'][0] + 1;
    return $id;
}

$ID = getMaxId(); //die("hhh");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

//print_r($_SERVER['HTTP_REFERER']);die;
    if (isset($_POST['Submit'])) {
        //echo '<pre>';print_r($_POST);die;
        $qrydetail = "SELECT * FROM EBIZ.VISITOR_GATE_PASS WHERE ID=$id";

        //  echo"<pre>";
//print_r($_POST);die;
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $err = 0;

        if (empty($_POST["TimeOut"]) || empty($_POST["Remarks"])) {

            if (empty($_POST["VisitorName"]) || ctype_space($_POST["VisitorName"])) {
                $VisitorNameErr = "Visitor Name is required";
                $err = 1;
            } else {
                $VisitorName = test_input($_POST["VisitorName"]);
                if (!preg_match("/^[a-zA-Z ]*$/", $VisitorName)) {
                    $VisitorNameErr = "Only letters and white space allowed";
                    $err = 1;
                }
            }

            if (empty($_POST["OrganizationName"]) || ctype_space($_POST["OrganizationName"])) {
                $OrganizationNameErr = "Please enter organization Name";
                $err = 1;
            } else {
                $OrganizationName = test_input($_POST["OrganizationName"]);
            }

            if (empty($_POST["ContactNumber"]) || ctype_space($_POST["ContactNumber"])) {

                $ContactNumberErr = "Contact Number is required";
                $err = 1;
            } else {
                $ContactNumber = test_input($_POST["ContactNumber"]);
                if (!preg_match("/^\+?[0-9]{1}[0-9]{3,14}$/", $ContactNumber)) {
                    $ContactNumberErr = "Please enter a valid contact number";
                    $err = 1;
                }
            }

            if (empty($_POST["NumberofPerson"]) || ctype_space($_POST["NumberofPerson"])) {

                $NumberofPersonErr = "Number of person is required";
                $err = 1;
            }
			
			if (!empty($editid) && empty($_POST["IdentityCardNumber"]) || ctype_space($_POST["IdentityCardNumber"])) {

                $IdentityCardNumberErr = "Identity Card Number is required";
                $err = 1;
            }
			
        }/* else {
          $NumberofPerson = test_input($_POST["NumberofPerson"]);
          if (!preg_match("/^\+?[0-9]{1}[0-9]{3,14}$/", $NumberofPerson)) {
          $NumberofPersonErr = "Please enter a valid detail";
          $err = 1;
          }
          } */

        //echo "<pre>";print_r($output);die;

        $ID = getMaxId($_POST["ID"]);
        $DepartmentOfVisit = $_POST["DepartmentOfVisit"];
        //echo $DepartmentOfVisit;die;

        if ($err == 0) {

            $GATE_PASS_DATE = date("d-m-Y");
            $date = date_create();
            $qry1 = "SELECT MAX(GATE_PASS_NO)as GATE_PASS_NO FROM EBIZ.VISITOR_GATE_PASS";
            // echo $con;die;
            $valueinsert = oci_parse($con, $qry1);
            oci_execute($valueinsert);
            oci_fetch_all($valueinsert, $output);

            $GATE_PASS_NO = $output['GATE_PASS_NO'][0] + 1;
            if ($editid == '') {
                if ($_POST['TypeOfVisitor'] == 1) {
                    $name = $_POST['VisitorName1'];
                } else {
                    $name = $_POST['VisitorName'];
                }
                $SQLInsert = "INSERT INTO EBIZ.VISITOR_GATE_PASS
                (ID,VISITOR_TYPE, VISITOR_NAME, ORG_NAME, LOCATION, CONTACT_NUMBER, EMP_MEET, PURPOSE, DEPT_ID,
                GROUP_INDIVIDUAL, NUM_OF_PERSON, BELONGINGS, TIME_IN, TIME_OUT, MATERIAL, ARMED, REMARKS, ID_NUM, GATE_PASS_NO, GATE_PASS_DATE, STATUS) 
                VALUES ('" . $ID . "','" . $_POST['TypeOfVisitor'] . "','" . $name . "', '" . $_POST['OrganizationName'] . "', '" . $_POST['Location'] . "', '" . $_POST['ContactNumber'] . "', 
                    '" . $_POST['WhomtoMeetinICAT'] . "', '" . $_POST['Purpose'] . "', '" . $_POST['DepartmentOfVisit'] . "', '" . $_POST['IndividualGroup'] . "', 
                    '" . $_POST['NumberofPerson'] . "', '" . $_POST['MaterialDeclarationBelongings'] . "', '" . $_POST['TimeIn'] . "', '" . $_POST['TimeOut'] . "', 
                    '" . $_POST['MaterialReturnableNonReturnable'] . "', '" . $_POST['WhetherArmed'] . "', '" . $_POST['Remarks'] . "', '" . $_POST['IdentityCardNumber'] . "', lpad('" . $GATE_PASS_NO . "',6,0), to_date('" . $GATE_PASS_DATE . "','dd-mm-yyyy'),'OPEN')";
                // echo $conn;
                //die;
            } else if (!empty($_POST["TimeOut"]) || !empty($_POST["Remarks"])) {

                $SQLInsert = "Update EBIZ.VISITOR_GATE_PASS set TIME_OUT = '" . $_POST['TimeOut'] . "', REMARKS = '" . $_POST['Remarks'] . "',STATUS='CLOSED' WHERE ID = $editid";
            } else {
                if ($_POST['TypeOfVisitor'] == 1) {
                    $name = $_POST['VisitorName1'];
                } else {
                    $name = $_POST['VisitorName'];
                }
                $SQLInsert = "Update EBIZ.VISITOR_GATE_PASS set VISITOR_TYPE = '" . $_POST['TypeOfVisitor'] . "', VISITOR_NAME = '" . $name . "',
                        ORG_NAME = '" . $_POST['OrganizationName'] . "', LOCATION = '" . $_POST['Location'] . "', CONTACT_NUMBER = '" . $_POST['ContactNumber'] . "',
                        EMP_MEET = '" . $_POST['WhomtoMeetinICAT'] . "', PURPOSE = '" . $_POST['Purpose'] . "', DEPT_ID = '" . $_POST['DepartmentOfVisit'] . "',
                        GROUP_INDIVIDUAL = '" . $_POST['IndividualGroup'] . "', NUM_OF_PERSON = '" . $_POST['NumberofPerson'] . "', BELONGINGS = '" . $_POST['MaterialDeclarationBelongings'] . "',
                        MATERIAL = '" . $_POST['MaterialReturnableNonReturnable'] . "', ARMED = '" . $_POST['WhetherArmed'] . "', ID_NUM = '" . $_POST['IdentityCardNumber'] . "', STATUS = 'ISSUED', TIME_OUT = '" . $_POST['TIME_OUT'] . "', REMARKS = '" . $_POST['REMARKS'] . "' WHERE ID = $editid";
            }
            $statement = oci_parse($con, $SQLInsert);
            $save = oci_execute($statement);
            $comit = oci_commit($con);
            //oci_free_statement($statement);
            if (!$save) {
                ?>
                <script>
                    alert("Data has not been saved");
                    window.location.assign('index.php');

                </script>
            <?php } else if(!$editid) {
                ?>
                <script>
					alert("Data has been saved successfuly");
					window.location.assign('print.php');
				</script>
					<?php } else { ?>
				<script>
					alert("Data has been saved successfuly");
					window.location.assign('index.php');
				</script>
					<?php } ?>
				<?php
            }
        }
    } else {
        
    }

?>
<input type="hidden" id="stat" value=<?php echo $arr[0]['STATUS']; ?> >
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <div style="float:left; width:46%;"><h3>Visitor Gate Pass</h3></div>
                            <div style="float:left; width:54%; text-align:right;"> <?php if (!empty($arr[0]['GATE_PASS_NO'])) { ?>
                                    <b>Gate Pass Date : </b><?php echo $arr[0]['GATE_PASS_DATE']; ?> &nbsp;
                                    <b>Gate Pass No : </b><?php echo $arr[0]['GATE_PASS_NO']; ?>&nbsp;
                                    <b>Status : </b><?php
                                    echo $arr [0]['STATUS'];
                                }
                                ?> 
                            </div>
                        </div>
                        <div class="form-top-right"> <i class="fa fa-pencil"></i> </div>
                    </div>
                    <div class="form-bottom">
                        <form role="form" action="" method="post">
                            <div class="form-group">
                                <label class="sr-only" for="form-first-name">Type Of Visitor</label>
                                <select name="TypeOfVisitor" value="<?php echo $TypeOfVisitor; ?>" placeholder="Type Of Visitor..." class="form-first-name form-control" id="TypeOfVisitor">
                                    <?php
//if (!empty($arr[0]['VISITOR_TYPE'])) {
                                    if ($arr[0]['VISITOR_TYPE'] == 1) {
                                        ?>
                                        <option  value="1" selected>Regular</option>
                                        <option  value="2" >Occasional</option>
                                    <?php } else { ?>
                                        <option  value="1" >Regular</option>
                                        <option  value="2" selected>Occasional</option>
                                        <?php
                                    }
                                    // }
                                    ?>
                                </select>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    //  alert($("#stat").val());
                                    $("#VisitorName1").hide();
                                    $("#Print").removeAttr('disabled');
                                    if ($("#stat").val() == 'ISSUED') {
                                        //alert("vivek");
                                        $("#TimeOut").removeAttr('disabled');
                                        $("#Remarks").removeAttr('disabled');
                                        $("#TypeOfVisitor").attr('disabled', 'disabled');
                                        $("#VisitorName").attr('readonly', true);
                                        $("#VisitorName1").attr('readonly', true);
                                        $("#OrganizationName").attr('readonly', true);
                                        $("#Location").attr('readonly', true);
                                        $("#ContactNumber").attr('readonly', true);
                                        $("#WhomToMeetInICAT").attr('disabled', 'disabled');
                                        $("#DepartmentOfVisit").attr('disabled', 'disabled');
                                        $("#IndividualGroup").attr('disabled', 'disabled');
                                        $("#NumberofPerson").attr('readonly', true);
                                        $("#MaterialDeclarationBelongings").attr('readonly', true);
                                        $("#MaterialReturnableNonReturnable").attr('disabled', 'disabled');
                                        $("#TimeIn").attr('readonly', true);
                                        $("#WhetherArmed").attr('disabled', 'disabled');
                                        $("#Purpose").attr('readonly', true);
                                        $("#Print").attr('disabled', 'disabled');
                                        $("#reset").attr('disabled', 'disabled');
                                    } else {
                                        $("#TimeOut").attr('disabled', 'disabled');
                                        $("#Remarks").attr('disabled', 'disabled');
                                    }

                                    if ($("#stat").val() == 'OPEN') {

                                        $("#IdentityCardNumber").removeAttr('disabled');
                                        $("#Print").removeAttr('disabled');
                                        $("#cancel").removeAttr('disabled');
                                    } else {
                                        $("#IdentityCardNumber").attr('disabled', 'disabled');
                                        $("#cancel").attr('disabled', 'disabled');
                                    }

                                    if ($("#stat").val() == 'CANCELED') {
                                        $("#submit").attr('disabled', 'disabled');
                                        $("#reset").attr('disabled', 'disabled');
                                        $("#Print").attr('disabled', 'disabled');
                                    }

                                    if ($("#stat").val() == 'CLOSED') {
                                        $("#submit").attr('disabled', 'disabled');
                                        $("#reset").attr('disabled', 'disabled');
                                        $("#Print").attr('disabled', 'disabled');
                                    }

                                    $('#TypeOfVisitor').on('change', function() {
                                        if (this.value == '1')
                                        {
                                            $("#VisitorName1").show();
                                            $("#VisitorName").hide();
                                        }
                                        else
                                        {
                                            $("#VisitorName").show();
                                            $("#VisitorName1").hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-first-name">Visitor Name</label>
                                <input type="text" name="VisitorName" value="<?php if (!empty($arr[0]['VISITOR_NAME'])) echo $arr[0]['VISITOR_NAME']; ?>" placeholder="Visitor Name..." class="form-first-name form-control" id="VisitorName">
                                <?php
                                global $con;
                                $query = "SELECT DISTINCT VISITOR_NAME FROM EBIZ.VISITOR_GATE_PASS WHERE VISITOR_TYPE = 1";
                                $statement = oci_parse($con, $query);
                                oci_execute($statement);
                                oci_fetch_all($statement, $res);
                                ?>
                                <select name="VisitorName1" value="<?php if (!empty($arr[0]['VISITOR_NAME'])) echo $arr[0]['VISITOR_NAME']; ?>" placeholder="Visitor Name..." class="form-first-name form-control" id="VisitorName1">
                                    <option  value="" selected>--SELECT--</option>
                                    <?php
                                    for ($i = 0; $i < count($res['VISITOR_NAME']); $i++) {
                                        echo '<option  value="' . $res['VISITOR_NAME'][$i] . '">' . $res['VISITOR_NAME'][$i] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="error"> <?php echo $VisitorNameErr; ?></span> </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Organization Name</label>
                                <input type="text" name="OrganizationName" value="<?php if (!empty($arr[0]['ORG_NAME'])) echo $arr[0]['ORG_NAME']; ?>" placeholder="Organization Name..." class="form-first-name form-control" id="OrganizationName">
                                <span class="error"> <?php echo $OrganizationNameErr; ?></span> </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-first-name">Location</label>
                                <textarea name="Location" cols="0" rows="0"  placeholder="Location..." class="form-first-name form-control" id="Location"><?php if (!empty($arr[0]['LOCATION'])) echo $arr[0]['LOCATION']; ?></textarea>
                            </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Contact Number</label>
                                <input type="text" name="ContactNumber" value="<?php if (!empty($arr[0]['CONTACT_NUMBER'])) echo $arr[0]['CONTACT_NUMBER']; ?>" placeholder="Contact Number..." class="form-last-name form-control" id="ContactNumber">
                                <span class="error"> <?php echo $ContactNumberErr; ?></span> </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-first-name">Whom to Meet in ICAT</label>
                                <select name="WhomtoMeetinICAT" value="<?php echo $WhomToMeetInICAT; ?>" placeholder="Whom to Meet in ICAT..." class="form-first-name form-control" id="WhomToMeetInICAT">
                                    <option  value="" selected>--SELECT--</option>
                                    <?php
                                    for ($i = 0; $i < count($output['EMP_CODE']); $i++) {
                                        if ($arr[0]['EMP_MEET'] == $output['EMP_CODE'][$i]) {
                                            echo '<option value="' . $output['EMP_CODE'][$i] . '" selected>' . $output['EMP_NM'][$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $output['EMP_CODE'][$i] . '">' . $output['EMP_NM'][$i] . '</option>';
                                        }
                                    }
                                    ?>
                                    <!--<option selected="" value="<?php //echo $output['EMP_CODE']       ?> "><?php //echo $output['EMP_NM']       ?></option> -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-last-name">Purpose</label>
                                <input type="text" name="Purpose" value="<?php if (!empty($arr[0]['PURPOSE'])) echo $arr[0]['PURPOSE']; ?>" placeholder="Purpose..." class="form-last-name form-control" id="Purpose">
                            </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-first-name">Department of Visit</label>
                                <?php
                                global $con;
                                $query = "SELECT ID,NAME FROM EBIZ.DEPARTMENT";
                                $statement = oci_parse($con, $query);
                                oci_execute($statement);
                                oci_fetch_all($statement, $output);
                                ?>
                                <select name="DepartmentOfVisit" value="<?php echo $DepartmentOfVisit; ?>" placeholder="Department of Visit..." class="form-first-name form-control" id="DepartmentOfVisit">
                                    <?php ?>
                                    <option  value="" selected>--SELECT--</option>
                                    <?php
                                    for ($i = 0; $i < count($output['NAME']); $i++) {
                                        if ($arr[0]['DEPT_ID'] == $output['ID'][$i]) {
                                            echo '<option  value="' . $output['ID'][$i] . '" selected>' . $output['NAME'][$i] . '</option>';
                                        } else {
                                            echo '<option  value="' . $output['ID'][$i] . '">' . $output['NAME'][$i] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Individual / Group</label>
                                <select id="IndividualGroup" value="<?php echo $IndividualGroup; ?>" name="IndividualGroup" class="form-first-name form-control">
                                    <?php
                                    //if (!empty($arr[0]['GROUP_INDIVIDUAL'])) {
                                    if ($arr[0]['GROUP_INDIVIDUAL'] == 1) {
                                        ?>
                                        <option  value="1" selected>Individual</option>
                                        <option  value="2" >Group</option>
                                    <?php } else { ?>
                                        <option  value="1" selected>Individual</option>
                                        <option  value="2" >Group</option>
                                        <?php
                                        //  }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-first-name">Number of Persons</label>
                                <input type="text" name="NumberofPerson" value="<?php if (!empty($arr[0]['NUM_OF_PERSON'])) echo $arr[0]['NUM_OF_PERSON']; ?>" placeholder="Number of Persons..." class="form-first-name form-control" id="NumberofPerson">
                                <span class="error"> <?php echo $NumberofPersonErr; ?></span> </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Material Declaration / Belongings</label>
                                <textarea name="MaterialDeclarationBelongings" placeholder="Material Declaration / Belongings..." class="form-last-name form-control" id="MaterialDeclarationBelongings"> <?php if (!empty($arr[0]['BELONGINGS'])) echo $arr[0]['BELONGINGS']; ?></textarea>
                            </div>

                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Time Out</label>
                                <?php $time = date("g:i a"); ?>
                                <input type="text" name="TimeOut" readonly="true" value="<?php
                                if (!empty($arr[0]['TIME_OUT'])) {
                                    echo $arr[0]['TIME_OUT'];
                                } else if (!empty($arr[0]['ID_NUM']) && empty($arr[0]['TIME_OUT'])) {
                                    echo $time;
                                }
                                ?>" placeholder="Time Out..." class="form-last-name form-control" id="TimeOut">
                            </div>
                            <div class="form-group ">
                                <label class="sr-only" for="form-first-name">Time In</label>
                                <?php $time = date("g:i a"); ?>
                                <input type="text" readonly="true" name="TimeIn" value="<?php
                                if (!empty($arr[0]['TIME_IN'])) {
                                    echo $arr[0]['TIME_IN'];
                                } else {
                                    echo $time;
                                }
                                ?>" placeholder="Time In..." class="form-first-name form-control" id="TimeIn">
                            </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-first-name">Material Returnable / Non-Returnable</label>
                                <select name="MaterialReturnableNonReturnable" value="<?php echo $MaterialReturnableNonReturnable; ?>" placeholder="Material Returnable / Non-Returnable..." class="form-first-name form-control" id="MaterialReturnableNonReturnable">
                                    <?php
                                    // if (!empty($arr[0]['MATERIAL'])) {
                                    if ($arr[0]['MATERIAL'] == 1) {
                                        ?>
                                        <option  value="1" selected>No</option>
                                        <option  value="2" >Yes</option>
                                    <?php } else { ?>
                                        <option  value="1" selected>No</option>
                                        <option  value="2" >Yes</option>
                                        <?php
                                        //      }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group left-mar ">
                                <label class="sr-only" for="form-about-yourself">Remarks</label>
                                <textarea name="Remarks" value="<?php if (!empty($arr[0]['REMARKS'])) echo $arr[0]['REMARKS']; ?>" placeholder="Remarks..." class="form-about-yourself form-control" id="Remarks"></textarea>
                            </div>

                            <div class="form-group ">
                                <label class="sr-only" for="form-last-name">Whether Armed?</label>
                                <select id="WhetherArmed" name="WhetherArmed" value="<?php echo $WhetherArmed; ?>" class="form-first-name form-control">
                                    <?php
                                    // if (!empty($arr[0]['ARMED'])) {
                                    if ($arr[0]['ARMED'] == 1) {
                                        ?>
                                        <option  value="1" selected>No</option>
                                        <option  value="2" >Yes</option>
                                    <?php } else { ?>
                                        <option  value="1" selected>No</option>
                                        <option  value="2" >Yes</option>
                                        <?php
                                        // }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-email">Identity Card Number</label>
                                <input type="text" name="IdentityCardNumber" value="<?php if (!empty($arr[0]['ID_NUM'])) echo $arr[0]['ID_NUM']; ?>" placeholder="Identity Card Number..." class="form-email form-control" id="IdentityCardNumber">
								<span class="error"> <?php echo $IdentityCardNumberErr; ?></span>
							</div>
                            <div class="col-sm-12" style="margin:0px; padding:0px; text-align: center;">
                                <input type="submit" id='submit' name="Submit" value="Submit" title="Submit" style="width:15%; margin: 0 auto;">
                                <input type="reset" id='reset' name="Reset" value="Reset" title="Reset" style="width:15%; margin: 0 auto;">
                                <input type="button" onclick="redirect()" title="Exit" style="width:15%; " value="Exit">
								<?php if(!empty($editid) && $arr[0]['STATUS'] == 'OPEN'){ ?>
									<input type="button" name="Print" onclick="sendprint()" value="Print" title="Print" id="Print" style="width:15%; margin: 0 auto;">
								<?php } ?>
                                <!--<input type="submit" name="Print" onclick="frames['frame1'].print()" value="Print" title="Print" id="Print" style="width:15%; margin: 0 auto;">-->
                                <input type="submit" id='cancel' name="Cancel" value="Cancel" title="Cancel" style="width:15%; margin: 0 auto;">

                                <!--<iframe src="print.php" style="display:none" name="frame1"></iframe>-->
								
                                <script>
								function sendprint(){
									window.location.assign("print.php");
								}
								
								function redirect() {
                                    window.location.assign("/icat/summary.php");
                                }
                                </script>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>