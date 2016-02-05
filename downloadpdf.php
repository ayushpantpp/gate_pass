<?php

error_reporting(0);
include 'db.php';
global $con;

require('fpdf/fpdf.php');

//create a FPDF object
$pdf = new FPDF();


$data = json_decode($_POST['data_arr'], true);

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
    $empname = "SELECT * FROM EBIZ.org" . '$hcm$emp$prf' . " WHERE EMP_CODE = '" . $ID . "'";
    $statement = oci_parse($con, $empname);
    oci_execute($statement);
    oci_fetch_all($statement, $output1);
    //echo '<pre>';print_r($output1);die;
    $name1 = $output1['INITIAL'][0] . ' ' . $output1['EMP_NM'][0];
    return $name1;
}

/*
  $getvisitordetail = "SELECT ID,lpad(GATE_PASS_NO,6,0) as GATE_PASS_NO,GATE_PASS_DATE,VISITOR_NAME,ORG_NAME,LOCATION,DEPT_ID,TIME_IN,TIME_OUT,EMP_MEET,ID_NUM,STATUS FROM EBIZ.VISITOR_GATE_PASS WHERE $conditions 1=1 ORDER BY GATE_PASS_NO DESC";

  $statement = oci_parse($con, $getvisitordetail);
  oci_execute($statement);
  oci_fetch_all($statement, $res2);
  //echo "<pre>"; print_r($res2);die;

  $arr = array();

  for ($a = 0; $a < count($res2['GATE_PASS_NO']); $a++) {
  $arr[] = array('ID' => $res2['ID'][$a], 'GATE_PASS_NO' => $res2['GATE_PASS_NO'][$a], 'GATE_PASS_DATE' => $res2['GATE_PASS_DATE'][$a],
  'VISITOR_NAME' => $res2['VISITOR_NAME'][$a], 'ORG_NAME' => $res2['ORG_NAME'][$a], 'DEPT_ID' => $res2['DEPT_ID'][$a],
  'EMP_MEET' => $res2['EMP_MEET'][$a]);
  }
 */

//echo "<pre>";print_r($arr);die;

class PDF extends FPDF {

    // Page header
    function Header() {
        // Logo
        $this->Image('assets/img/icat-logo1.png', 10, 6, 30);
        // Arial bold 
        $this->SetFont('Arial', 'B', 13);
        // Move to the right
        $this->Cell(93);
        // Title
        $this->Cell(100, 10, 'International Centre for Automotive Technology', 2, 0, 'U');
        // Line break
        $this->Ln(8);
        //print a cell inside cell
        $this->SetX($this->GetX() - 100);
        // Arial bold 
        $this->SetFont('Arial', '', 13);
        // Move to the right
        $this->Cell(30);
        // Title
        $this->Cell(100, 10, 'Visitor Gate Pass Summary', 2, 0, '');
        // Line break
        $this->Ln(10);
    }

    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' ', 0, 0, 'C');
        
        $today = date("d/m/Y");  
        $this->Cell(0, 10, $today, 0, 0, 'C'); 
    }

// Load data
    function LoadData($arr) {
        // Read file lines
        $data = $arr;
        //echo "<pre>"; print_r($arr);
        return $data;
    }

    function ImprovedTable($header, $data) {
        $this->SetFont('', 'B');

        $w = array(12, 15, 32, 30, 29, 40, 13, 13, 15);
//echo "<pre>"; print_r($arr);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'LR');
        $this->Ln();
        $this->SetFont('');

        foreach ($data as $row) {
            //echo $row;
            $this->Cell($w[0], 6, $row['GATE_PASS_NO'], 'LR');
            $this->Cell($w[1], 6, $row['GATE_PASS_DATE'], 'LR');
            $this->Cell($w[2], 6, strtoupper($row['VISITOR_NAME']), 'LR');
            $this->Cell($w[3], 6, strtoupper($row['ORG_NAME']), 'LR');
            $this->Cell($w[4], 6, strtoupper(getdeptname($row['DEPT_ID'])), 'LR');
            $this->Cell($w[5], 6, strtoupper(getempname($row['EMP_MEET'])), 'LR');
            $this->Cell($w[6], 6, $row['TIME_IN'], 'LR');
            $this->Cell($w[7], 6, $row['TIME_OUT'], 'LR');
            $this->Cell($w[8], 6, $row['STATUS'], 'LR');
            $this->Ln();
        }

        $this->Cell(array_sum($w), 0, '', 'T');
    }

}

$pdf = new PDF();
// Column headings
$header = array('GP No.', 'GP Date', 'Visitor Name', 'Organization', 'Department', 'Contact Person', 'In', 'Out', 'Status');
// Data loading
//$data = $pdf->LoadData($arr);
$pdf->SetFont('Arial', '', 7);
$pdf->AddPage();
$pdf->ImprovedTable($header, $data);

//Output the document
$pdf->Output('Gate_Pass_Report.pdf', 'D');
