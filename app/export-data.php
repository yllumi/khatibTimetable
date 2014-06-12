<?php
session_start();
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';

// open database
$dbfile = 'assets/saved/'.$_SESSION['filename'].$_SESSION['file_ext'];
$db = new SQLite3($dbfile);

$tabledata = $db->querySingle("SELECT matrix FROM nodes_version ORDER BY rowid DESC");
$tabledata = json_decode(str_replace('\"', '"', $tabledata));

$columndata = $db->query("SELECT code, title, desc FROM column");
$rowdata = $db->query("SELECT code, title, desc FROM row");
$nodedata = $db->query("SELECT code, title, desc FROM node");

$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Toni Haryanto")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Jadwal Khutbah");


/* Timetable Data */
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'JADWAL KHOTBAH JUM\'AH')
			->setCellValue('A3', 'NO')
			->setCellValue('B3', 'TGL/BLN/TH')
			->setCellValue('C3', 'KODE  KHOTIB / KODE MASJID');

$c = 2;
while($column = $columndata->fetchArray(SQLITE3_ASSOC)){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($alphabet[$c].'4', $column['code']);
	$c++;
}

$bataskolom = $c;
$i = 6;
$no = 1;
//print_r($tabledata);
foreach ($tabledata as $row) {
	$date = $rowdata->fetchArray(SQLITE3_ASSOC);

	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, $no)
					->setCellValue('B'.$i, $date['code']);

	$c = 2;
	foreach($row as $node){
		if($c < $bataskolom)
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($alphabet[$c].$i, $node->code);
		$c++;
	}
	$i++;
	$no++;
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Data Table');


/* Column Data */
$objWorkSheet = $objPHPExcel->createSheet(1);
$objWorkSheet->setTitle('Data Masjid');

$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A1', 'DAFTAR MASJID')
			->setCellValue('A3', 'KODE')
			->setCellValue('B3', 'NAMA MASJID')
			->setCellValue('C3', 'KETERANGAN');

$i = 4;
$columndata = $db->query("SELECT code, title, desc FROM column");
while($column = $columndata->fetchArray(SQLITE3_ASSOC)){
	$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue('A'.$i, $column['code'])
				->setCellValue('B'.$i, $column['title'])
				->setCellValue('C'.$i, $column['desc']);
	$i++;
}


/* Node Data */
$objWorkSheet = $objPHPExcel->createSheet(2);
$objWorkSheet->setTitle('Data Khotib');

$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('A1', 'DAFTAR KHOTIB')
			->setCellValue('A3', 'KODE')
			->setCellValue('B3', 'NAMA KHOTIB')
			->setCellValue('C3', 'KETERANGAN');

$i = 4;
while($node = $nodedata->fetchArray(SQLITE3_ASSOC)){
	$objPHPExcel->setActiveSheetIndex(2)
				->setCellValue('A'.$i, $node['code'])
				->setCellValue('B'.$i, $node['title'])
				->setCellValue('C'.$i, $node['desc']);
	$i++;
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$filename = 'data';
if(isset($_GET['filename']))
	$filename = $_GET['filename'];

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

$db->close();
exit;
