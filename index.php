<?php
error_reporting(0);
include 'header.php';
session_start();
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
if (@$_REQUEST['Cancel'] == 'Cancel') {
    $Insertstatus = "Update EBIZ.VISITOR_GATE_PASS set STATUS='CANCELED' WHERE ID = $editid";
    $statement = oci_parse($con, $Insertstatus);
    $save = oci_execute($statement);
    $comit = oci_commit($con);

    if (!$save) {
        ?>
        <script>
            alert("Gate Pass has been cancelled successfully");
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
        $qrydetail = "SELECT * FROM EBIZ.VISITOR_GATE_PASS WHERE ID=".$_GET['id'];

        //  echo"<pre>";
//print_r($_POST);die;
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $err = 0;

        if (empty($_POST["TimeOut"])) {

            if (empty($_POST["VisitorName"]) || ctype_space($_POST["VisitorName"])) {
                $VisitorNameErr = "Visitor Name is required";
                $err = 1;
            } else {
                $VisitorName = test_input($_POST["VisitorName"]);
                if (!preg_match("/^[a-zA-Z .]*$/", $VisitorName)) {
                    $VisitorNameErr = "Only letters and white space allowed";
                    $err = 1;
                }
            }

            if (empty($_POST["OrganizationName"]) || ctype_space($_POST["OrganizationName"])) {
                $OrganizationNameErr = "Organization Name is required";
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
            
            if (empty($_POST["WhomtoMeetinICAT"]) || ctype_space($_POST["WhomtoMeetinICAT"])) {

                $WhomToMeetInICATErr = "Whom to meet is required";
                $err = 1;
            }
            
            if (empty($_POST["DepartmentOfVisit"]) || ctype_space($_POST["DepartmentOfVisit"])) {

                $DepartmentOfVisitErr = "Department name is required";
                $err = 1;
            }
            
            if (empty($_POST["Location"]) || ctype_space($_POST["Location"])) {

                $LocationErr = "Location is required";
                $err = 1;
            }

            if (empty($_POST["Purpose"]) || ctype_space($_POST["Purpose"])) {

                $PurposeErr = "Purpose is required";
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

       // echo "<pre>";print_r($_POST);die;
	
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
				
				$form_secret = isset($_POST["form_secret"])?$_POST["form_secret"]:'';
				 
				if(isset($_SESSION["FORM_SECRET"])) {
					if(strcasecmp($form_secret, $_SESSION["FORM_SECRET"]) === 0) {
                $SQLInsert = "INSERT INTO EBIZ.VISITOR_GATE_PASS
                (ID,VISITOR_TYPE, VISITOR_NAME, ORG_NAME, LOCATION, CONTACT_NUMBER, EMP_MEET, PURPOSE, DEPT_ID,
                GROUP_INDIVIDUAL, NUM_OF_PERSON, BELONGINGS, TIME_IN, TIME_OUT, MATERIAL, ARMED, REMARKS, ID_NUM, GATE_PASS_NO, GATE_PASS_DATE, STATUS) 
                VALUES ('" . $ID . "','" . $_POST['TypeOfVisitor'] . "','" . $_POST['VisitorName'] . "', '" . $_POST['OrganizationName'] . "', '" . $_POST['Location'] . "', '" . $_POST['ContactNumber'] . "', 
                    '" . $_POST['WhomtoMeetinICAT'] . "', '" . $_POST['Purpose'] . "', '" . $_POST['DepartmentOfVisit'] . "', '" . $_POST['IndividualGroup'] . "', 
                    '" . $_POST['NumberofPerson'] . "', '" . $_POST['MaterialDeclarationBelongings'] . "', '" . $_POST['TimeIn'] . "', '" . $_POST['TimeOut'] . "', 
                    '" . $_POST['MaterialReturnableNonReturnable'] . "', '" . $_POST['WhetherArmed'] . "', '" . $_POST['Remarks'] . "', '" . $_POST['IdentityCardNumber'] . "', lpad('" . $GATE_PASS_NO . "',6,0), to_date('" . $GATE_PASS_DATE . "','dd-mm-yyyy'),'OPEN')";
                // echo $conn;
					 unset($_SESSION["FORM_SECRET"]);
					}else {
						//Invalid secret key
					}
				} else {
				//Secret key missing ?>
				 <script>
                    alert("Form data has already been processed!");
				 </script>
			<?php	}
                //die;
            } else if (!empty($_POST["TimeOut"]) || !empty($_POST["Remarks"])) {

                $SQLInsert = "Update EBIZ.VISITOR_GATE_PASS set TIME_OUT = '" . $_POST['TimeOut'] . "', REMARKS = '" . $_POST['Remarks'] . "',STATUS='CLOSED' WHERE ID = $editid";
            } else {
                if ($_POST['TypeOfVisitor'] == 1) {
                    $name = $_POST['VisitorName1'];
                } else {
                    $name = $_POST['VisitorName'];
                }
                $SQLInsert = "Update EBIZ.VISITOR_GATE_PASS set VISITOR_TYPE = '" . $_POST['TypeOfVisitor'] . "', VISITOR_NAME = '" . $_POST['VisitorName'] . "',
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
                    window.location.assign('index.php?id=<?php echo $_GET['id']; ?>');

                </script>
            <?php } else if (!$editid) {
                ?>
                <script>
                    alert("Data has been saved successfuly");
                    window.location.assign('print.php');
                </script>
            <?php } else { ?>
                <script>
                    alert("Data has been saved successfuly");
                    window.location.assign('summary.php');
                </script>
            <?php } ?>
            <?php
        }
    }
} else {
    
}
?>
<?php 
//ADDED BY ABHILASH JAISWAL 22-12-2015

$secret=md5(uniqid(rand(), true));
$_SESSION['FORM_SECRET'] = $secret;
?>
<input type="hidden" id="stat" value=<?php echo $arr[0]['STATUS']; ?> >
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <div style="float:left; width:28%;"><h3>Visitor Gate Pass</h3></div>
                            <div style="float:left; width:72%; text-align:right;"> 
							<input style="border-color: black; border-width: 2px; border-style: solid" type="text" name="ContactNumber1" value=" " placeholder="Contact Number..." class=" " id="ContactNumber1">
							<input type="submit" id='submit2' name="Submit2" value="Submit" title="Submit" style="width:15%; margin: 0 auto;">
							<?php if (!empty($arr[0]['GATE_PASS_NO'])) { ?>
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
					<div id="error" style="background-color: #fff;color: red"></div>
                    <div class="form-bottom">
                        <form role="form" action="" method="post">
							<input type='hidden' value = '<?php echo $_SESSION['FORM_SECRET']; ?>' name ='form_secret'> 
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
                                $(document).ready(function () {
									
									$('#submit2').click(function(){
                                        if($('#ContactNumber1').val()==''){
                                            alert('Please enter contact number.');
                                            $('#ContactNumber1').focusin();
                                        }else{
                                            var conn = $('#ContactNumber1').val();
                                            $.ajax({
                                               type:"POST",
                                               url:"contact.php",
                                               dataType:"json",
                                               data:{contact:conn},
                                               success:function(response){
                                                   //alert();
                                                   $('#VisitorName').val(response['VISITOR_NAME'][0]);
                                                   $('#OrganizationName').val(response['ORG_NAME'][0]);
                                                   $('#Location').val(response['LOCATION'][0]);
                                                   $('#ContactNumber').val(response['CONTACT_NUMBER'][0]);
                                                   $('#WhomToMeetInICAT').val(response['EMP_MEET'][0]);
                                                   $('#Purpose').val(response['PURPOSE'][0]);
                                                   $('#DepartmentOfVisit').val(response['DEPT_ID'][0]);
                                                   $('#IndividualGroup').val(response['GROUP_INDIVIDUAL'][0]);
                                                   $('#NumberofPerson').val(response['NUM_OF_PERSON'][0]);
                                                   $('#MaterialDeclarationBelongings').val(response['BELONGINGS'][0]);
                                                   $('#MaterialReturnableNonReturnable').val(response['MATERIAL'][0]);
                                                   $('#WhetherArmed').val(response['ARMED'][0]);
                                               },
                                               error:function(xhr,ajaxOptions, thrownError){
                                                        //alert(thrownError);
                                                        $('#error').html('No Results found for this '+conn);
                                                    }
                                            });
                                        }
                                    });
									
									$('html').bind('keypress', function (e)
                                    {
                                        if (e.keyCode == 13)
                                        {
                                            return false;
                                        }
                                    });
									
                                    //  alert($("#stat").val());
                                    $("#ContactNumber1").hide();
                                    $("#submit2").hide();
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

                                    $('#TypeOfVisitor').on('change', function () {
                                        if (this.value == '1')
                                        {
                                            $("#ContactNumber1").show();
                                            $("#submit2").show();
                                        }
                                        else
                                        {
                                            $("#ContactNumber1").hide();
                                            $("#submit2").hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-first-name">Visitor Name</label>
                                <input type="text" style="text-transform: uppercase" name="VisitorName" value="<?php if (!empty($arr[0]['VISITOR_NAME'])) echo $arr[0]['VISITOR_NAME']; ?>" placeholder="Visitor Name..." class="form-first-name form-control" id="VisitorName">
                                <span class="error"> <?php echo $VisitorNameErr; ?></span> </div>
                            <div class="form-group left-mar">
                                <label class="sr-only" for="form-last-name">Organization Name</label>
                                <input type="text" style="text-transform: uppercase" name="OrganizationName" value="<?php if (!empty($arr[0]['ORG_NAME'])) echo $arr[0]['ORG_NAME']; ?>" placeholder="Organization Name..." class="form-first-name form-control" id="OrganizationName">
                                <span class="error"> <?php echo $OrganizationNameErr; ?></span> </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-first-name">Location</label>
                                <textarea name="Location" style="text-transform: uppercase" cols="0" rows="0"  placeholder="Location..." class="form-first-name form-control" id="Location"><?php if (!empty($arr[0]['LOCATION'])) echo $arr[0]['LOCATION']; ?></textarea>
                            <span class="error"> <?php echo $ContactNumberErr; ?></span>
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
                                    <!--<option selected="" value="<?php //echo $output['EMP_CODE']        ?> "><?php //echo $output['EMP_NM']        ?></option> -->
                                </select>
                                <span class="error"> <?php echo $WhomToMeetInICATErr; ?></span>
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="form-last-name">Purpose</label>
                                <input type="text" style="text-transform: uppercase" name="Purpose" value="<?php if (!empty($arr[0]['PURPOSE'])) echo $arr[0]['PURPOSE']; ?>" placeholder="Purpose..." class="form-last-name form-control" id="Purpose">
                                <span class="error"> <?php echo $PurposeErr; ?></span>
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
                                <span class="error"> <?php echo $DepartmentOfVisitErr; ?></span>
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
                                <textarea name="MaterialDeclarationBelongings" maxlength="50" style="text-transform: uppercase" placeholder="Material Declaration / Belongings..." class="form-last-name form-control" id="MaterialDeclarationBelongings"><?php if (!empty($arr[0]['BELONGINGS'])) echo trim($arr[0]['BELONGINGS']); else echo ''; ?></textarea>
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
                                <input type="submit" id='submit' name="Submit" value="Submit" title="Submit"  style="width:15%; margin: 0 auto;">
                                <input type="reset" id='reset' name="Reset" value="Reset" title="Reset" style="width:15%; margin: 0 auto;">
                                <input type="button" onclick="redirect()" title="Exit" style="width:15%; " value="Exit">
                                <?php if (!empty($editid) && $arr[0]['STATUS'] == 'OPEN') { ?>
                                    <input type="button" name="Print" onclick="sendprint('<?php echo $editid; ?>')" value="Print" title="Print" id="Print" style="width:15%; margin: 0 auto;">
                                <?php } ?>
<!--<input type="submit" name="Print" onclick="frames['frame1'].print()" value="Print" title="Print" id="Print" style="width:15%; margin: 0 auto;">-->
                                <input type="submit" id='cancel' name="Cancel" value="Cancel" title="Cancel" style="width:15%; margin: 0 auto;">

                                <!--<iframe src="print.php" style="display:none" name="frame1"></iframe>-->

                                <script>
                                    function sendprint(id) {
										//window.print();
                                        window.location.assign("print.php?id="+id);
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