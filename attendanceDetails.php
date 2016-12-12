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
		$objAttendanceData->setFemaleSingleCount($_POST["intFemaleStdField"]);
		$objAttendanceData->setMaleSingleCount($_POST["intMaleStd"]);
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
		$objAttendanceData->setFemaleSingleCount($_POST["intFemaleStdField"]);
		$objAttendanceData->setMaleSingleCount($_POST["intMaleStd"]);
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
					case "intFemaleSingle":
						document.getElementById('FemaleStdField').value = arrJsAttendanceRecords[keys[i]][objKeys[k]];
						break;
					case "intMaleSingle":
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
		var intMaleSingleVaue = document.getElementById('MaleStdField').value;
		var intFemaleValue = document.getElementById('FemaleField').value;
		var intFemaleSingleValue = document.getElementById('FemaleStdField').value;
		var intChildrenValue = document.getElementById('ChildrenField').value;
		var intTotal = 0;
		if(intMaleSingleVaue == "" || intFemaleValue == "" || intFemaleSingleValue == "" || intChildrenValue == "" || intMaleValue == ""){
			alert("The fields below must be entered before calculating.\n Male\n Male Students \n Female \n Female Students \n Children");
		}
		else{
			intTotal = (parseInt(intMaleValue) + parseInt(intFemaleValue) + parseInt(intMaleSingleVaue) + parseInt(intChildrenValue) + parseInt(intFemaleSingleValue));
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
	<fieldset style="width:510px;">
		<legend>Attendance details</legend>
		<div>
			<span style="float:left;">Date: <input id="dateField" class="attDetailsInputField" type="text" name="dtmDate"></input></span>
			<span style="margin-left: 30px;">Service Type: <input id="ServiceField" class="attDetailsInputField" type="text" name="strServiceType"></input></span>
		</div><br/>	
		<div>
			<span style="float:left">First Timer: <input id="FirstTimerField" class="attDetailsInputField" type="text" name="intFirstTimer"></input></span>
			<span style="margin-left:80px;">Preacher: <input id="PreacherField" class="attDetailsInputField" type="text" name="strPreacher"></input></span>
		</div><br/>
		<div>
			<span style="float:left">Male: <input id="MaleField" class="attDetailsInputField" type="text" name="intMale"></input></span>
			<span style="margin-left: 115px;">Male Single: <input id="MaleStdField" class="attDetailsInputField" type="text" name="intMaleStd"></input></span>
		</div><br/>
		<div>
			<span style="float:left">Female: <input id="FemaleField" class="attDetailsInputField" type="text" name="intFemale"></input></span>
			<span style="margin-left: 100px;">Female Single: <input id="FemaleStdField" class="attDetailsInputField" type="text" name="intFemaleStdField"></input></span>
		</div><br/>
		<div>
			<span style="float:left">Children: <input id="ChildrenField" class="attDetailsInputField" type="text" name="intChildrenFieldField"></input></span>
			<span style="margin-left: 95px;">Total: <input id="totalField" class="attDetailsInputField" type="text" name="intTotalField"></input> <input type="button" value="Calc" onclick="CalcTotal()"></span>
		</div><br/>		
		<div>
			<span >Sunday School: <input id="sundaySchField" class="attDetailsInputField" type="text" name="intSundaySchool"></input></span>
		</div><br/>
		<span>Message: <textarea id="messageField" class="attDetailsInputField" name="strMessage" cols="50" rows="4"></textarea></span><br/>
		<? var_dump($_GET);
		if($blnIsNewAttendance == "false" || !$blnIsNewAttendance  || $_SESSION["blnIsNewAttendance"] == "false" || $_GET["intAttendanceID"] != "null" || $_POST["AttendanceID"] != ""){
			echo "first condition";?>
			<input type="button" title="This button saves any update you make to any of the fields above" value="Save Edits" onclick="saveChanges(null)"/>
		<?}?>
		<?if($blnIsNewAttendance == "true" || $blnIsNewAttendance  || $_SESSION["blnIsNewAttendance"] == "true" || !$_GET["intAttendanceID"] == null || $_POST["AttendanceID"]==""){?>	 
			<input type="button"  title="This button saves new attendance you are trying to create" value="Save New Attendance" onclick="saveChanges(true)"/>
		 <?}?>
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
	//echo "Query Used: ". $strSQL;
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
	$intFemaleSingleCount = $objAttendance->getFemaleSingleCount();
	$intMaleSingleCount = $objAttendance->getMaleSingleCount();
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
			else if($mixKey == "intMaleSingle" && $objAttendance->getMaleSingleCount() != $mixValues){
				$strSQL .= " intMaleSingle = '$intMaleSingleCount'".",";
			}
			else if ($mixKey == "intFemaleSingle" && $objAttendance->getFemaleSingleCount() != $mixValues){
				$strSQL .= " intFemaleSingle = '$intFemaleSingleCount'".",";
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
	$intFemaleSingleCount = $objAttendance->getFemaleSingleCount();
	$intMaleSingleCount = $objAttendance->getMaleSingleCount();
	$intTotal = $objAttendance->getTotal();
	$intSundaySchool = $objAttendance->getSundaySchoolCount();

	$strSQL = "INSERT INTO asikpo_attendance.tblChurchAttendance
				(dtmDate, strServiceType, strMessage, strPreacher, intFirstTimer, intMale, intMaleSingle, intFemale, intFemaleSingle, intChildren, intTotal, intSundaySchool)	 
			VALUES ('$dtmDate', 
				'$strServiceType', 
				'$strMessage', 
				'$strPreacher', 
				'$intFirstTimer', 
				'$intMaleCount', 
				'$intMaleSingleCount', '$intFemaleCount', '$intFemaleSingleCount','$intChildrenCount', '$intTotal', '$intSundaySchool')";
	$rsResult = mysqli_query($connection, $strSQL);
	//echo "Query :". $strSQL;
	if($rsResult){
		echo "Records successfully saved";
		$arrRecords = loadAttendaceByID(mysqli_insert_id($connection));
	}

}
?>
