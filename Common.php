<?php
include_once("Conn.php");

if($_POST["action"]){
	session_start();
	$_POST["action"]();
}

$arrMonthAbbrev = array('January' => "Jan", 
						'Feburary' => "Feb",
						'March' => "Mar",
						'April' => "Apr",
						'May' => "May",
						'June' => "June",
						'July' => "July",
						'August' => "Aug",
						'September' => "Sep",
						'October' => "Oct",
						'November' =>"Nov",
						'December' => "Dec");

function formatDate($dtmDate){
	global $arrMonthAbbrev;
	$arrDateInfo = explode("-", $dtmDate);
	$dt = DateTime::createFromFormat('!m', $arrDateInfo[1]);
	$strMonth =  $dt->format('F');

	$strFormattedDate = $arrMonthAbbrev[$strMonth] . " ". $arrDateInfo[2];
	return $strFormattedDate;
}

function validateLimitValues($intPageNumber){
	$intPageNumberToReturn;
	if($intPageNumber == "" || $intPageNumber == 1){
		$intPageNumberToReturn = 1;
	}
	else{
		$intPageNumberToReturn = ($intPageNumberToReturn * 10);
	}
	return $intPageNumberToReturn;
}

function deleteAttendanceAjax(){
	global $connection;
	$intAttendanceID = $_POST["intAttendanceID"];
	$strSQL = "DELETE FROM asikpo_attendance.tblChurchAttendance
	 			WHERE intAttendanceID = '$intAttendanceID' "; 
		
	$rsResult = mysqli_query($connection, $strSQL);
	if($rsResult){
		$data[1] = true;
	}	
	echo json_encode($data);
}

function validateLogin(){
	global $connection;
	$strUserName = $_POST["userName"];
	$strPassword = $_POST["Pword"];

	$strSQL = "SELECT intAdminID
				FROM asikpo_attendance.tblAdministrator
				WHERE strUserName = '$strUserName'
				AND strPassword = '$strPassword' ";

	$rsResult = mysqli_query($connection, $strSQL);
	//echo "Query Used: ". $strSQL;
	$arrRow = mysqli_fetch_assoc($rsResult);

	if($arrRow["intAdminID"]){
		$data[1] = true;
	}
	else{
		$data[1] = false;
	}

	echo json_encode($data);					
}
?>
