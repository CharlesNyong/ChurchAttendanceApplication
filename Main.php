<?php
if(!$_GET["blnAuthenticated"]){
	header("Location: login.php");
}
ob_start();
?>
<!Doctype html>
<html>
<head>
<title>Application Directory</title>
<link rel="stylesheet" type="text/css" href="attendance.css">
</head>
<body style="background-color:#F0F8FF;">
<h2 style="text-align:center;">TOG Application Directory</h2>
<table border="1" class="appDirectoryTable">
	<tr>
		<td>
			<div class="dirTableCell" title="This opens the attendance manager application"><a href="displayAttendance.php?blnAuthenticated='1'">Attendance Manager Application</a></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="dirTableCell" title="This opens the member manager application"><a href="displayMemberManager.php">Member Manager Application</a></div>
		</td>
	</tr>		
</table>
</body>
</html>
<?
$strHTML = ob_get_contents();
ob_end_clean();
echo $strHTML;
?>
