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

 //var_dump($_POST);
if($_POST["blnSaveRecord"] == 1){
	//echo "user saving";
	if($_POST["blnIsNewAttendance"] == "true" || $blnIsNewAttendance == "true"){
		$objAttendanceData->setDate($_POST["dtmDate"]);
		$objAttendanceData->setServiceType($_POST["strServiceType"]);
		$objAttendanceData->setMaleCount($_POST["intMale"]);
		$objAttendanceData->setFemaleCount($_POST["intFemale"]);
		$objAttendanceData->setFemaleStudentCount($_POST["intFemaleStdField"]);
		$objAttendanceData->setMaleStudentCount($_POST["intMaleStd"]);
		$objAttendanceData->setPreacher($_POST["strPreacher"]);
		$objAttendanceData->setMessage($_POST["strMessage"]);
		$objAttendanceData->setFirstTimer($_POST["intFirstTimer"]);
		$objAttendanceData->setChildrenCount($_POST["intChildrenFieldField"]);
		$objAttendanceData->setTotal($_POST["intTotalField"]);
		$objAttendanceData->setSundaySchoolCount($_POST["intSundaySchool"]);
		saveAttendance($objAttendanceData);
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
		$objAttendanceData->setServiceType($_POST["strServiceType"]);
		$objAttendanceData->setMaleCount($_POST["intMale"]);
		$objAttendanceData->setFemaleCount($_POST["intFemale"]);
		$objAttendanceData->setFemaleStudentCount($_POST["intFemaleStdField"]);
		$objAttendanceData->setMaleStudentCount($_POST["intMaleStd"]);
		$objAttendanceData->setPreacher($_POST["strPreacher"]);
		$objAttendanceData->setMessage($_POST["strMessage"]);
		$objAttendanceData->setFirstTimer($_POST["intFirstTimer"]);
		$objAttendanceData->setChildrenCount($_POST["intChildrenFieldField"]);
		$objAttendanceData->setTotal($_POST["intTotalField"]);
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
		// alert(typeof(arrJsAttendanceRecords));
		var keys = Object.keys(arrJsAttendanceRecords); // grab the indices for each object
		for (var i = 0; i < keys.length; i++) {
		    var objKeys = Object.keys(arrJsAttendanceRecords[keys[i]]); // grab the object keys
		    for(var k = 0; k< objKeys.length; k++){
		    	//alert("key: "+ objKeys[k] + arrJsAttendanceRecords[keys[i]][objKeys[k]]);
		    	//alert(typeof(objKeys[k]));
		    	switch(objKeys[k]){
					case "dtmDate":
						document.getElementById('dateField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "strServiceType":
						document.getElementById('ServiceField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intMale":
						document.getElementById('MaleField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intFemale":
						document.getElementById('FemaleField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intFemaleStudents":
						document.getElementById('FemaleStdField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intMaleStudents":
						document.getElementById('MaleStdField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "strMessage":
						document.getElementById('messageField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;			
					case "strPreacher":
						document.getElementById('PreacherField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intFirstTimer":
						document.getElementById('FirstTimerField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;					
					case "intChildren":
						document.getElementById('ChildrenField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
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

	function CalcTotal(){
		var intMaleValue = document.getElementById('MaleField').value;
		var intMaleStudentVaue = document.getElementById('MaleStdField').value;
		var intFemaleValue = document.getElementById('FemaleField').value;
		var intFemaleStudentValue = document.getElementById('FemaleStdField').value;
		var intChildrenValue = document.getElementById('ChildrenField').value;
		var intTotal = 0;
		if(intMaleStudentVaue == "" || intFemaleValue == "" || intFemaleStudentValue == "" || intChildrenValue == "" || intMaleValue == ""){
			alert("The fields below must be entered before calculating.\n Male\n Male Students \n Female \n Female Students \n Children");
		}
		else{
			intTotal = (parseInt(intMaleValue) + parseInt(intFemaleValue) + parseInt(intMaleStudentVaue) + parseInt(intChildrenValue) + parseInt(intFemaleStudentValue));
			document.getElementById('totalField').value = intTotal;
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
		Service Type: <input id="ServiceField" class="attDetailsInputField" type="text" name="strServiceType"></input><br/>
		<span style="padding-top:0px;">Message: </span><span><textarea id="messageField" class="attDetailsInputField" name="strMessage" cols="50" rows="4"></textarea></span><br/>
		Preacher: <input id="PreacherField" class="attDetailsInputField" type="text" name="strPreacher"></input><br/>
		First Timer: <input id="FirstTimerField" class="attDetailsInputField" type="text" name="intFirstTimer"></input><br/>
		Male: <input id="MaleField" class="attDetailsInputField" type="text" name="intMale"></input><br/>
		Male Students: <input id="MaleStdField" class="attDetailsInputField" type="text" name="intMaleStd"></input><br/>
		Female: <input id="FemaleField" class="attDetailsInputField" type="text" name="intFemale"></input><br/>
		Female Students: <input id="FemaleStdField" class="attDetailsInputField" type="text" name="intFemaleStdField"></input><br/>
		Children: <input id="ChildrenField" class="attDetailsInputField" type="text" name="intChildrenFieldField"></input><br/>
		intTotal: <input id="totalField" class="attDetailsInputField" type="text" name="intTotalField"></input> <input type="button" value="Calc" onclick="CalcTotal()"><br/>		
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
	$strSQL = "SELECT *
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
	$strServiceType = $objAttendance->getServiceType();
	$intMaleCount = $objAttendance->getMaleCount();
	$intFemaleCount = $objAttendance->getFemaleCount();
	$intChildrenCount = $objAttendance->getChildrenCount();
	$intFirstTimer = $objAttendance->getFirstTimer();
	$strPreacher = $objAttendance->getPreacher();
	$strMessage = $objAttendance->getMessage();
	$intFirstTimer = $objAttendance->getFirstTimer();
	$intFemaleStudentCount = $objAttendance->getFemaleStudentCount();
	$intMaleStudentCount = $objAttendance->getMaleStudentCount();
	$intTotal = $objAttendance->getTotal();
	$intSundaySchool = $objAttendance->getSundaySchoolCount();

	$strSQL = "UPDATE asikpo_attendance.tblChurchAttendance
				SET";

	foreach ($arrOldAttendance as $intKey => $arrRows) {
		foreach ($arrRows as $mixKey => $mixValues) {
			if ($mixKey == "dtmDate" && $objAttendance->getDate() != $mixValues) {
				$strSQL .= " dtmDate = '$dtmDate'".",";
			}
			else if($mixKey == "strServiceType" && $objAttendance->getServiceType() != $mixValues){
				$strSQL .= " strServiceType = '$strServiceType'".",";			
			}
			else if($mixKey == "intFirstTimer" && $objAttendance->getFirstTimer() != $mixValues){
				$strSQL .= " intFirstTimer = '$intFirstTimer'".",";
			}
			else if($mixKey == "intMale" && $objAttendance->getMaleCount() != $mixValues){
				$strSQL .= " intMale = '$intMaleCount'".",";
			}
			else if ($mixKey == "intFemale" && $objAttendance->getFemaleCount() != $mixValues) {
				$strSQL .= " intFemale = '$intFemaleCount'".",";
			}
			else if($mixKey == "intChildren" && $objAttendance->getChildrenCount() != $mixValues){
				$strSQL .= " intChildren = '$intChildrenCount'".",";
			}
			else if($mixKey == "intMaleStudents" && $objAttendance->getMaleStudentCount() != $mixValues){
				$strSQL .= " intMaleStudents = '$intMaleStudentCount'".",";
			}
			else if ($mixKey == "intFemaleStudents" && $objAttendance->getFemaleStudentCount() != $mixValues){
				$strSQL .= " intFemaleStudents = '$intFemaleStudentCount'".",";
			}
			else if ($mixKey == "strMessage" && $objAttendance->getMessage() != $mixValues){
				$strSQL .= " strMessage = '$strMessage'".",";
			}
			else if ($mixKey == "strPreacher" && $objAttendance->getPreacher() != $mixValues){
				$strSQL .= " strPreacher = '$strPreacher'".",";
			}
			else if($mixKey == "intTotal" && $objAttendance->getTotal() != $mixValues){
				$strSQL .= " intTotal = '$intTotal'".",";
			}
			else if($mixKey == "intSundaySchool" && $objAttendance->getSundaySchoolCount() != $mixValues){
				$strSQL .= " intSundaySchool = '$intSundaySchool'".",";
			}
		}
	}
		$strSQL = substr($strSQL, 0, strlen($strSQL)-1); // drop extra commas
		$strSQL .= " WHERE intAttendanceID = $intAttendanceID";
	
	$rsResult = mysqli_query($connection, $strSQL);
	//echo "Query Used: ". $strSQL;
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
	global $arrRecords;
	$dtmDate = $objAttendance->getDate();
	$intAttendanceID = $objAttendance->getAttendanceID();
	$strServiceType = $objAttendance->getServiceType();
	$intMaleCount = $objAttendance->getMaleCount();
	$intFemaleCount = $objAttendance->getFemaleCount();
	$intChildrenCount = $objAttendance->getChildrenCount();
	$intFirstTimer = $objAttendance->getFirstTimer();
	$strPreacher = $objAttendance->getPreacher();
	$strMessage = $objAttendance->getMessage();
	$intFirstTimer = $objAttendance->getFirstTimer();
	$intFemaleStudentCount = $objAttendance->getFemaleStudentCount();
	$intMaleStudentCount = $objAttendance->getMaleStudentCount();
	$intTotal = $objAttendance->getTotal();
	$intSundaySchool = $objAttendance->getSundaySchoolCount();

	$strSQL = "INSERT INTO asikpo_attendance.tblChurchAttendance
				(dtmDate, strServiceType, strMessage, strPreacher, intFirstTimer, intMale, intMaleStudents, intFemale, intFemaleStudents, intChildren, intTotal, intSundaySchool)	 
		VALUES ('$dtmDate', '$strServiceType', '$strMessage', '$strPreacher', '$intFirstTimer', '$intMaleCount', '$intMaleStudentCount', '$intFemaleCount', '$intFemaleStudentCount',
				'$intChildrenCount', '$intTotal', '$intSundaySchool')";
	$rsResult = mysqli_query($connection, $strSQL);
	if($rsResult){
		echo "Records successfully saved";
		$arrRecords = loadAttendaceByID(mysqli_insert_id($connection));
	}

}
?>
