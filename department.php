<?php
error_reporting(0);
include 'header.php';
include 'db.php';
$con;
$departmentnameErr = "";
$departmentname = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['Submit'])) {



        function getMaxId($id) {
            global $con;
            //print_r($con);die;
            $qry = "SELECT MAX(ID)as ID FROM EBIZ.DEPARTMENT";
            // echo $con;die;
            $valueinsert = oci_parse($con, $qry);
            oci_execute($valueinsert);
            oci_fetch_all($valueinsert, $output);
            //var_dump($output);
            //echo "<pre>";
            // print_r($output);die;

            $id = $output['ID'][0] + 1;
            return $id;
        }

        //$departmentname=$_POST["departmentname"];
        $err = 0;
        if (empty($_POST["departmentname"]) || ctype_space($_POST["departmentname"])) {
            $departmentnameErr = "Department name is required";
            $err = 1;
        } else {
            $departmentname = test_input($_POST["departmentname"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $departmentname)) {
                $departmentnameErr = "Only letters and white space allowed";
                $err = 1;
            }
        }
        $ID = getMaxId($_POST["ID"]);
        //  echo "dddddddd".$ID;die;
        //print_r($ID);die;
        if ($err == 0) {
            $SQLInsert = "INSERT INTO EBIZ.DEPARTMENT(ID,NAME) VALUES ('" . $ID . "','" . $departmentname . "')";
            // echo $conn;die;
            $statement = oci_parse($con, $SQLInsert);
            $save = oci_execute($statement);
            $comit = oci_commit($con);
            //oci_free_statement($statement);
            if (!$save) {
                ?>
                <script>   alert("Data has not been saved");
                    window.location.assign('department.php');</script> 
            <?php } else {
                ?>
                <script>   alert("Data has not been saved");
                    window.location.assign('department.php');</script> <?php
            }
        }
    }
}
?>

<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <h3>Add Department</h3>

                        </div>
                    </div>
                    <div class="form-bottom" style="width:100%;">  
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td>
                                    <form name="depart" action="department.php" method="POST">
                                        <table width="100%" border="0" cellspacing="1" cellpadding="3">
                                            <tr>
                                                <td width="13%" height="60px">Department Name:</td>
                                                <td width="80%"><input name="departmentname" value="<?php echo $departmentname; ?>" placeholder="Department*" type="text" id="departmentname">
                                                    <span class="error"> <?php echo $departmentnameErr; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td><input type="submit" name="Submit" value="Submit"></td>
                                            </tr>
                                        </table>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>