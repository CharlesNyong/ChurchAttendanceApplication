<?php
include("Conn.php");
	class attendance{

		var $dtmDate;
		var $intDay;
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
			<form method="post" id="frmFilter" action="<?=$_SERVER['PHP_SELF'];?>">
				<fieldset style="width:400px;">
					<legend>Filters</legend>
					Filter By Date: <input type="text" id="dateFilter" class="dateDropDown"></input><br/> 
					<input type="button" value="Filter" onclick="filterByDate()"/>
				</fieldset>
				<input type="hidden" value="" id="dateEntered" name="dateEntered"/>
			</form>
			<?$this->loadAttendance($_POST["dateEntered"])?>
			<table border="1" style="width:900px; text-align:center;">
				<tr>
					<th>Date</th>
					<th>Day</th>
					<th>Men</th>
					<th>Women</th>
					<th>Children</th>
					<th>Total</th>
					<th>Sunday School</th>
				</tr>
				<?if($this->arrAttendanceRecords != NULL){
					foreach ($this->arrAttendanceRecords as $intAttID => $arrRows) {?>
						<tr>
						<?foreach ($arrRows as $mixKey => $mixValue) {?>
							<?if($mixKey != "intAttendanceID"){?>
								<td><?echo $mixValue?></td>
							<?}?>	
						<?}?>
						</tr> 
					<?}
				}
				else{?>
					<tr><td colspan='7'><h3 style='text-align:center;'>There are no records for that date.</h3></td></tr>
				<?}?>	
			</table>
			<? 	
			$strHTML .= ob_get_contents();
			ob_end_clean();
			return $strHTML;	
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
				$this->arrAttendanceRecords[$arrRow["intAttendanceID"]] = $arrRow;
			}
		}
	}
	$objDisplayAttendance = new attendance();
	echo $objDisplayAttendance->toHTML();
?>
