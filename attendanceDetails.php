<?php
include("Conn.php");
include("attendanceData.php");
session_start();

$intAttendanceID = isset($_GET["intAttendanceID"])? $_GET["intAttendanceID"] : null;
$blnIsNewAttendance = $_GET["blnIsNewAttendance"];
$_SESSION["blnIsNewAttendance"] = $blnIsNewAttendance;
$arrRecords = loadAttendaceByID($intAttendanceID);
$objAttendanceData = new attendanceData();
$strError = "";

// if($blnIsNewAttendance) {
// 	echo "IsAttendance ". $blnIsNewAttendance;
// 	$objAttendanceData = new attendanceData();	
// }

// var_dump($_POST);
if($_POST["blnSaveRecord"] == 1){
	echo "user saving";
	if($_POST["blnIsNewAttendance"] == "true" || $blnIsNewAttendance == "true"){
		$objAttendanceData->setDate($_POST["dtmDate"]);
		$objAttendanceData->setDay($_POST["strDay"]);
		$objAttendanceData->setMenCount($_POST["intMen"]);
		$objAttendanceData->setWomenCount($_POST["intWomen"]);
		$objAttendanceData->setChildrenCount($_POST["intChildren"]);
		$objAttendanceData->setTotal($_POST["intTotal"]);
		$objAttendanceData->setSundaySchoolCount($_POST["intSundaySchool"]);
		//saveAttendance($objAttendanceData);
	}
	else{
		$strError = "Error: Please use the Save Edits button to save any updates! You will have to close this window and try this again.";
	}
}

if($_POST["blnUpdateRecord"] == 1){
	if ($_POST["blnIsNewAttendance"] == "false" || $blnIsNewAttendance == "false") {
		 if($_POST["AttendanceID"] == ""){
			$objAttendanceData->setAttendanceID($intAttendanceID); 	
		 }
		 else{
			$objAttendanceData->setAttendanceID($_POST["AttendanceID"]);
		}	
		$objAttendanceData->setDate($_POST["dtmDate"]);
		$objAttendanceData->setDay($_POST["strDay"]);
		$objAttendanceData->setMenCount($_POST["intMen"]);
		$objAttendanceData->setWomenCount($_POST["intWomen"]);
		$objAttendanceData->setChildrenCount($_POST["intChildren"]);
		$objAttendanceData->setTotal($_POST["intTotal"]);
		$objAttendanceData->setSundaySchoolCount($_POST["intSundaySchool"]);
		updateAttendanceRecord($objAttendanceData);
	}
	else{
		$strError = "Error: Please use the Save New Attendance button to save new attendance! You will have to close this window and try this again.";
	}
}

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
	function doOnLoad(){
		var arrJsAttendanceRecords;
		var strError =  '<?echo $strError?>';
		if(strError){
			alert(strError);
			arrJsAttendanceRecords = JSON.parse('<?echo json_encode($_POST)?>');	
		}
		else{
			arrJsAttendanceRecords = JSON.parse('<?echo json_encode($arrRecords)?>');
		}
		loadAttendanceOnPage(arrJsAttendanceRecords);
	}

	function loadAttendanceOnPage(arrJsAttendanceRecords){
		alert(typeof(arrJsAttendanceRecords));
		var keys = Object.keys(arrJsAttendanceRecords);
		for (var i = 0; i < keys.length; i++) {
		    var objKeys = Object.keys(arrJsAttendanceRecords[keys[i]]);
		    for(var k = 0; k< objKeys.length; k++){
		    	//alert("key: "+ objKeys[k] + arrJsAttendanceRecords[keys[i]][objKeys[k]]);
		    	//alert(typeof(objKeys[k]));
		    	switch(objKeys[k]){
					case "dtmDate":
						document.getElementById('dateField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "strDay":
						document.getElementById('dayField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intMen":
						document.getElementById('menField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intWomen":
						document.getElementById('womenField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intChildren":
						document.getElementById('childrenField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intTotal":
						document.getElementById('totalField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intSundaySchool":
						document.getElementById('sundaySchField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];						
				}
		    }		
		}
	}

	/* TODO:
		call a php function to retrieve 
		the modified field values and
		save them to a database.
		After saving, load the updated record again
		by just refreshing the page.
	*/
	function saveChanges(mixValue){
		if(checkForm()){
			if (mixValue == null) { // we are updating the record here
				document.getElementById("updateRecord").value = 1;
				document.getElementById("saveRecord").value = 0;
				document.getElementById("intAttendanceID").value = '<?echo $intAttendanceID; ?>';
				document.getElementById("blnIsNewAtt").value = '<?echo $blnIsNewAttendance;?>';
				document.getElementById("frmInputFields").submit();
			}
			else{
				document.getElementById("saveRecord").value = 1;
				document.getElementById("blnIsNewAtt").value = '<?echo $blnIsNewAttendance;?>';
				document.getElementById("updateRecord").value = 0;
				document.getElementById("frmInputFields").submit();
			}
		}
		else{
			return false;
		}
	}


	function checkForm(){
		strError = "";
		if(document.getElementById('dateField').value == ""){
			strError += "Please enter a date for this attendance";	
		} 	
		if(strError != ""){
			alert(strError);
			return false;
		}
		return true;
	}
</script>

<body onload="doOnLoad()">
<form method="post" id="frmInputFields" action="<?=$_SERVER['PHP_SELF'];?>">
	<fieldset style="width:400px;">
		<legend>Attendance details</legend>
		Date: <input id="dateField" class="attDetailsInputField" type="text" name="dtmDate"></input><br/>
		Day: <input id="dayField" class="attDetailsInputField" type="text" name="strDay"></input><br/>
		Number of men: <input id="menField" class="attDetailsInputField" type="text" name="intMen"></input><br/>
		Number of women: <input id="womenField" class="attDetailsInputField" type="text" name="intWomen"></input><br/>
		Number of children: <input id="childrenField" class="attDetailsInputField" type="text" name="intChildren"></input><br/>
		Total: <input id="totalField" class="attDetailsInputField" type="text" name="intTotal"></input><br/>
		Sunday School: <input id="sundaySchField" class="attDetailsInputField" type="text" name="intSundaySchool"></input><br/><br/>
		<input type="button" title="This button saves any update you make to any of the fields above" value="Save Edits" onclick="saveChanges(null)"/> 
		<input type="button"  title="This button saves new attendance you are trying to create" value="Save New Attendance" onclick="saveChanges(true)"/>
	</fieldset>
	<input type="hidden" value="0" id="updateRecord" name="blnUpdateRecord"/>
	<input type="hidden" value="" id="intAttendanceID" name="AttendanceID"/>
	<input type="hidden" value="0" id="saveRecord" name="blnSaveRecord"/>
	<input type="hidden" value="" id="blnIsNewAtt" name="blnIsNewAttendance"/>
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

function updateAttendanceRecord($objAttendance){
	global $connection;
	$arrOldAttendance = loadAttendaceByID($objAttendance->getAttendanceID());
	$dtmDate = $objAttendance->getDate();
	$intAttendanceID = $objAttendance->getAttendanceID();
	$strDay = $objAttendance->getDay();
	$intMenCount = $objAttendance->getMenCount();
	$intWomenCount = $objAttendance->getWomenCount();
	$intChildrenCount = $objAttendance->getChildrenCount();
	$intTotal = $objAttendance->getTotal();
	$intSundaySchool = $objAttendance->getSundaySchoolCount();

	$strSQL = "UPDATE asikpo_attendance.tblChurchAttendance
				SET";

	foreach ($arrOldAttendance as $intKey => $arrRows) {
		foreach ($arrRows as $mixKey => $mixValues) {
			if ($mixKey == "dtmDate" && $objAttendance->getDate() != $mixValues) {
				$strSQL .= " dtmDate = '$dtmDate'".",";
			}
			else if($mixKey == "strDay" && $objAttendance->getDay() != $mixValues){
				$strSQL .= " strDay = '$strDay'".",";			
			}
			else if($mixKey == "intMen" && $objAttendance->getMenCount() != $mixValues){
				$strSQL .= " intMen = '$intMenCount'".",";
			}
			else if ($mixKey == "intWomen" && $objAttendance->getWomenCount() != $mixValues) {
				$strSQL .= " intWomen = '$intWomenCount'".",";
			}
			else if($mixKey == "intChildren" && $objAttendance->getChildrenCount() != $mixValues){
				$strSQL .= " intChildren = '$intChildrenCount'".",";
			}
			else if($mixKey == "intTotal" && $objAttendance->getTotal() != $mixValues){
				$strSQL .= " intTotal = '$intTotal'".",";
			}
			else if($mixKey == "intSundaySchool" && $objAttendance->getSundaySchoolCount() != $mixValues){
				$strSQL .= " intSundaySchool = '$intSundaySchool'";
			}
		}
	}

	if($strSQL[strlen($strSQL)-1] == ","){
		$strSQL[strlen($strSQL)-1] = " WHERE intAttendanceID = $intAttendanceID";
	}
	else{
		$strSQL .= " WHERE intAttendanceID = $intAttendanceID";
	}
	$rsResult = mysqli_query($connection, $strSQL);
	
	if($rsResult){
		global $arrRecords;
		global $intAttendanceID;
		global $blnIsNewAttendance;
		$arrRecords = loadAttendaceByID($objAttendance->getAttendanceID());
		$intAttendanceID = $objAttendance->getAttendanceID();
		$blnIsNewAttendance = false;
		//$arrJsArray = json_encode($arrUpdatedRecords);
		//var_dump($arrUpdatedRecords);
		echo "Records successfully Updated..";
		//echo "<script>alert('inside php function')</script>";
	}
	//echo "Sql value: ". $strSQL;		


}	

function saveAttendance($objAttendance){
	global $connection;
	$dtmDate = $objAttendance->getDate();
	$strDay = $objAttendance->getDay();
	$intMenCount = $objAttendance->getMenCount();
	$intWomenCount = $objAttendance->getWomenCount();
	$intChildrenCount = $objAttendance->getChildrenCount();
	$intTotal = $objAttendance->getTotal();
	$intSundaySchool = $objAttendance->getSundaySchoolCount();

	$strSQL = "INSERT INTO asikpo_attendance.tblChurchAttendance
				(dtmDate, strDay, intMen, intWomen, intChildren, intTotal, intSundaySchool)	 
				VALUES ('$dtmDate', '$strDay', '$intMenCount', '$intWomenCount', '$intChildrenCount', '$intTotal', '$intSundaySchool')";
	$rsResult = mysqli_query($connection, $strSQL);
	if($rsResult){
		echo "Records successfully saved";
	}

}
?>
