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
    $rowData[1] = PHPExcel_Style_NumberFormat::toFormattedString($rowData[1], "D/M/YYYY");
    echo $rowData[0].' ';
    echo $rowData[1].' ';
    echo $rowData[19].' ';
    echo "<hr>";


    $count++;

    if($count > 30)
        break;
}