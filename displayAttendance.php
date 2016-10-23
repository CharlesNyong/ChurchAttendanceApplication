<?php
include("Conn.php");
	class attendance{

		var $dtmDate;
		var $intDay;
		var $intAttendanceID;
		var $intMen;
		var $intWomen;
		var $intChildren;
		var $intTotal;
		var $intSundaySchoolCount;
		var $arrAttendanceRecords;
		var $strSQLString;


		function attendance(){

		}

		function javaScript(){
			ob_start();
			$strJs ="";	
		?>
			<link rel="stylesheet" type="text/css" href="attendance.css">
			<link rel="stylesheet" type="text/css" href="dhtmlxSuite/codebase/dhtmlx.css"></link>
			<link rel="stylesheet" type="text/css" href="dhtmlxGrid/skins/skyblue/dhtmlxgrid.css"></link>
			<!-- <link rel="stylesheet" type="text/css" href="dhtmlxGrid/codebase/dhtmlxgrid.css"></link> -->
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
			<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
			<script type="text/javascript" src="dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
			<script type="text/javascript" src="test.js"></script> 
			<script>
				var objobjMyGrid;
				var intAttendanceDetails = 0;
				var blnNewAttendance;
				window.onload = function(){
					 $("#dateFilter").datepicker();
					 // objMyGrid = new dhtmlXGridObject('attendanceDiv');                 
      //   			  objMyGrid.setHeader("Date,Day,MenCount,WomenCount,ChildrenCount,Total,SundaySchoolCount");//the headers of columns  
      //   			  objMyGrid.setImagePath("dhtmlxGrid/skins/skyblue/imgs/dhxgrid_skyblue/");
      //   			  objMyGrid.setInitWidths("80,80,80,100,100,100,115");          //the widths of columns  
      //   			  objMyGrid.setColAlign("left,left,left,left,left,left,left");       //the alignment of columns   
      //   			  objMyGrid.setColTypes("ro,ro,ro,ro,ro,ro,ro");
      //   			  objMyGrid.init();      //finishes initialization and renders the grid on the page 
      //      			  objMyGrid.enableSmartRendering(true);
      //      			  objMyGrid.load("attendanceXML.php", "xml");
				}

				function filterByDate(){
					var dtmSelectedDate = document.getElementById("dateFilter").value;
					document.getElementById("dateEntered").value = dtmSelectedDate;
					document.getElementById("frmFilter").submit();
				}
				
				function deleteAttendance(intAttendanceID){
					var response = confirm("Are you sure you want to delete this attendance?");
					if(response == true){
						document.getElementById("attendanceID").value = intAttendanceID;
						//document.getElementById("dateEntered").value = dtmSelectedDate
						document.getElementById("blnDeleteAttendance").value = 1;
						document.getElementById("frmFilter").submit();
						//alert("You said yes");
					}
				}

				function showPopUpWindow(intAttendanceID){
					if(intAttendanceID == null){
						blnNewAttendance = true;
					}
					else{
						blnNewAttendance = false;
					}

					var strURL = "attendanceDetails.php?intAttendanceID="+intAttendanceID+"&blnIsNewAttendance="+blnNewAttendance;
					window.open(strURL,intAttendanceDetails,'width=640,height=350,scrollbars=yes');
					intAttendanceDetails++;
				}
			</script>	
			<?
			$strJs .= ob_get_contents();
			ob_end_clean();
			return $strJs;	
		}

		function toHTML(){
			ob_start();
			$strHTML = $this->javaScript();	
			?>
			<div class="newAttendanceBtn" onclick="showPopUpWindow(null)">New Attendance</div>
			<hr/>
			<form method="post" id="frmFilter" action="<?=$_SERVER['PHP_SELF'];?>">
				<fieldset style="width:400px;">
					<legend>Filters</legend>
					Filter By Date: <input type="text" id="dateFilter" class="dateDropDown"></input><br/> 
					<input type="button" value="Filter" onclick="filterByDate()"/>
				</fieldset>
				<input type="hidden" value="" id="dateEntered" name="dateEntered"/>
				<input type="hidden" value="" id="attendanceID" name="intAttendanceID"/>
				<input type="hidden" value="0" id="blnDeleteAttendance" name="blnDeleteAttendance"/>
			</form>
			<? var_dump($_POST); 
			if($_POST["blnDeleteAttendance"] == 1){
					$this->deleteAttendance($_POST["intAttendanceID"]);
				}
				if ($_POST["dateEntered"]) {
					$_SESSION["dateEntered"] = $_POST["dateEntered"]; 
				}

				if ($_POST["dateEntered"]  == "") {
					session_start();
					$this->loadAttendance($_SESSION["dateEntered"]);
				}
				else{
					$this->loadAttendance($_POST["dateEntered"]);	
				}
				
			?>
			<table border="1" style="width:900px; text-align:center;">
				<tr>
					<th>RowNO</th>
					<th>Date</th>
					<th>Day</th>
					<th>Men</th>
					<th>Women</th>
					<th>Children</th>
					<th>Total</th>
					<th>Sunday School</th>
				</tr>
				<?if($this->arrAttendanceRecords != NULL){
					$intRowCount = 0;
					//echo $this->intAttendanceID;
					foreach ($this->arrAttendanceRecords as $intAttID => $arrRows) {?>
					<?$intRowCount++;?>
						<tr>
						<td ondblclick=<?echo "deleteAttendance(".$this->intAttendanceID.")";?> ><a href="#" onclick=<?echo "showPopUpWindow(".$this->intAttendanceID.")";?> ><?echo $intRowCount;?></a></td>	
						<?foreach ($arrRows as $mixKey => $mixValue) {?>
							<?if($mixKey != "intAttendanceID"){?>
								<td><?echo $mixValue?></td>
							<?}?>	
						<?}?>
						</tr> 
					<?}
				}
				else{?>
					<tr><td colspan='8'><h3 style='text-align:center;'>The is no record for that date.</h3></td></tr>
				<?}?>	
			</table>
			<? 	
			$strHTML .= ob_get_contents();
			ob_end_clean();
			return $strHTML;	
		}

		function deleteAttendance($intAttendanceID){
			global $connection;
			$strSQL = "DELETE FROM asikpo_attendance.tblChurchAttendance
			 			WHERE intAttendanceID = '$intAttendanceID' "; 
				
			$rsResult = mysqli_query($connection, $strSQL);
			if($rsResult){
				echo "<script>alert('Item Deleted')</script>";
			}	
		}

		function loadAttendance($dtmDateToFilter){
			global $connection;
			if ($dtmDateToFilter) {
				$pieces = explode("/", $dtmDateToFilter);
				$dtmNewDate = date("Y-m-d",strtotime($pieces[2]."-".$pieces[0]."-".$pieces[1]));
				$this->dtmDate = $dtmNewDate;	
			}
			else{
				$this->dtmDate = null;
			} 		
			$strSQL = "SELECT intAttendanceID, dtmDate, strDay, intMen, intWomen, intChildren, intTotal, intSundaySchool
			 			FROM asikpo_attendance.tblChurchAttendance
			 			WHERE dtmDate = '$this->dtmDate' "; 
				
			$rsResult = mysqli_query($connection, $strSQL);
			//$this->strSQLString = $strSQL;
			while ($arrRow = mysqli_fetch_assoc($rsResult)) {
				$this->intAttendanceID = $arrRow["intAttendanceID"];
				$this->arrAttendanceRecords[$arrRow["intAttendanceID"]] = $arrRow;
			}
		}
	}
	$objDisplayAttendance = new attendance();
	echo $objDisplayAttendance->toHTML();
?>
