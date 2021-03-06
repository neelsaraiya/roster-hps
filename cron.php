<?php

//  Include PHPExcel_IOFactory
include 'Classes/PHPExcel/IOFactory.php';
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
ini_set('xdebug.max_nesting_level', 40000);
ini_set('memory_limit','300M');


include 'functions.php';
include 'email.php';

$inputFileName = "./" . $email_number . "-" . $filename;
$fileloc = 'uploads/'.$inputFileName;
//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($fileloc);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($fileloc);
    $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($fileloc,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(1);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

$startRow = 3775; //Monday April 3 2017
$count = 1;
$conn = dbconn();
$sql = "DELETE FROM timings;";
$conn->query($sql);

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



	$sql = "INSERT INTO timings (date, roster)
	VALUES ('".$rowData[1]."','".$rowData[19]."')";

	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

    $count++;

//    if($count > 30)
//        break;
}

$conn->close();
rename($fileloc, "uploads/processed/".$inputFileName);




