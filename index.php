<?php
/**
 * Created by PhpStorm.
 * User: neelsaraiya
 * Date: 4/04/17
 * Time: 10:14 AM
 */

//  Include PHPExcel_IOFactory
include 'Classes/PHPExcel/IOFactory.php';
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('xdebug.max_nesting_level', 40000);
ini_set('memory_limit','300M');

$inputFileName = './uploads/VicTasLocumRoster020317.xlsx';

//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($inputFileName);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(1);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

$startRow = 3775; //Monday April 3 2017
$count = 1;

//  Loop through each row of the worksheet in turn
for ($row = $startRow; $row <= $highestRow; $row++){
    $filter = 'A' . $row . ':' . 'AA' . $row;
    //echo $filter;
    //  Read a row of data into an array
    $rowData = $sheet->rangeToArray($filter,
        NULL,
        TRUE,
        FALSE);
    echo "<pre>";
    $rowData = $rowData[0];
    $rowData[1] = PHPExcel_Style_NumberFormat::toFormattedString($rowData[1], "YYYY-MM-DD");
    echo $rowData[0].' '; //day
    echo $rowData[1].' '; //date
    echo $rowData[19].' '; //
    echo "<hr>";


	$conn = dbconn();
	$sql = "INSERT INTO timings (date, roster)
	VALUES ('".$rowData[1]."','".$rowData[19]."')";

	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

    $count++;

    if($count > 30)
        break;
}

$conn->close();


function dbconn(){
	$servername = "localhost";
	$username = "neelsara_user";
	$password = "bl*T6T7Z.L;C";
	$dbname = "neelsara_sivaniroster";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	return $conn;
}
