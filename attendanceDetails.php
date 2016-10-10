<?php
include("Conn.php");
$intAttendanceID = isset($_GET["intAttendanceID"])? $_GET["intAttendanceID"] : null;
$arrRecords = loadAttendaceByID($intAttendanceID);
//var_dump($arrRecords);
ob_start();
$strHTML = "";
?>
<!Doctype html>
<html>
<head>
	<title>Attendance Details</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
</head>
<script>
	window.onload = function(){
		var mixColValues;
		var mixKey;
		<?
		foreach ($arrRecords as $intKey => $arrRows) {
			foreach ($arrRows as $mixKey => $mixValues) {?>
				mixColValues = '<?echo $mixValues; ?>';
				mixKey = '<?echo $mixKey; ?>';
				switch(mixKey){
					case "dtmDate":
						document.getElementById('dateField').value = mixColValues;
						break;
					case "strDay":
						document.getElementById('dayField').value = mixColValues;
						break;
					case "intMen":
						document.getElementById('menField').value = mixColValues;
						break;
					case "intWomen":
						document.getElementById('womenField').value = mixColValues;
						break;
					case "intChildren":
						document.getElementById('childrenField').value = mixColValues;
						break;
					case "intTotal":
						document.getElementById('totalField').value = mixColValues;
						break;
					case "intSundaySchool":
						document.getElementById('sundaySchField').value = mixColValues;						
				}	
			<?}
		}?>
	}

	/* TODO:
		call a php function to retrieve 
		the modified field values and
		save them to a database.
		After saving, load the updated record again
		by just refreshing the page.
	*/
	function saveChanges(){

	}
</script>
<body>
<form method="post" id="frmInputFields" action="<?=$_SERVER['PHP_SELF'];?>">
	<fieldset style="width:400px;">
		<legend>Attendance details</legend>
		Date: <input id="dateField" class="attDetailsInputField" type="text"></input><br/>
		Day: <input id="dayField" class="attDetailsInputField" type="text"></input><br/>
		Number of men: <input id="menField" class="attDetailsInputField" type="text"></input><br/>
		Number of women: <input id="womenField" class="attDetailsInputField" type="text"></input><br/>
		Number of children: <input id="childrenField" class="attDetailsInputField" type="text"></input><br/>
		Total: <input id="totalField" class="attDetailsInputField" type="text"></input><br/>
		Sunday School: <input id="sundaySchField" class="attDetailsInputField" type="text"></input><br/>
		<input type="button" value="Save Edits" onclick="saveChanges()"/>
	</fieldset>
	<input type="hidden" value="" id="dateEntered" name="intMen"/>
	<input type="hidden" value="" id="dateEntered" name="intWomen"/>
	<input type="hidden" value="" id="dateEntered" name="intChildren"/>
	<input type="hidden" value="" id="dateEntered" name="intTotal"/>
	<input type="hidden" value="" id="dateEntered" name="intSundaySchool"/>
</form>
</body>
</html>
<? $strHTML .= ob_get_contents();
	ob_end_clean();
	echo $strHTML;

function loadAttendaceByID($intAttendanceID){
	global $connection;
	$arrAttendanceRecords = array();
	$strSQL = "SELECT intAttendanceID, dtmDate, strDay, intMen, intWomen, intChildren, intTotal, intSundaySchool
			 	FROM asikpo_attendance.tblChurchAttendance
			 	WHERE intAttendanceID = ". $intAttendanceID; 
				
	$rsResult = mysqli_query($connection, $strSQL);
	//$this->strSQLString = $strSQL;
	while ($arrRow = mysqli_fetch_assoc($rsResult)) {
		$arrAttendanceRecords[] = $arrRow;
	}

	return $arrAttendanceRecords;
}	
?>
