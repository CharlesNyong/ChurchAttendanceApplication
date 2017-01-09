<?php
include_once("Conn.php");

if($_GET){
	$strErrorMessage = $_GET["strErrorMessage"];
	echo "<script>alert(".$strErrorMessage.")</script>";
}
ob_start();
?>
<!Doctype>
<html>
<head>
<title>Login Page</title>
<link rel="stylesheet" type="text/css" href="login.css">
<script src="common.js"></script>
</head>
<body bgcolor="#c4c4c4">
<center>	
	<div id="loginDiv">
		<form method="post" id="loginForm" action="checkLogin.php">
				<table width="300" border="0">
				<tr>
					<td>Username:</td>
					<td><input type="text" id="UserName" style="width:180px;" name="UserName"></input></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" id="PassWord" style="width: 180px;" name="PassWord"></input></td>
				</tr>
				<tr>
					<td></td><td><input type="submit" value="Login"/></td>
				</tr>
				</table>
			<input type="hidden" value="0" id="blnAuthenticate" name="blnAuthenticate"/>
		</form>
	</div>
</center>		
</body>
</html>	
<?
$strHTML = ob_get_contents();
ob_end_clean();
echo $strHTML;
?>
