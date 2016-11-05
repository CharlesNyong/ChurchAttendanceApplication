<?php

class attendanceData{

		var $dtmDate;
		var $strServiceType;
		var $intAttendanceID;
		var $intMale;
		var $intMaleStudent;
		var $intFemale;
		var $intFemaleStudent;
		var $intChildren;
		var $intTotal;
		var $intSundaySchoolCount;
		var $strMessage;
		var $strPreacher;
		var $intFirstTimer;
		var $arrAttendanceRecords;


		function attendanceData(){

		}

		function setDate($dtmDate){
			$this->dtmDate = $dtmDate;
		}
		function setAttendanceID($intAttendanceID){
			$this->intAttendanceID = $intAttendanceID;
		}
		function setServiceType($strServiceType){
			$this->strServiceType = $strServiceType;
		}
		function setMaleCount($intNoOfMen){
			$this->intMale = $intNoOfMen;
		}
		function setFemaleCount($intNoOfWomen){
			$this->intFemale = $intNoOfWomen;
		}
		function setMaleStudentCount($intNoOfMaleStd){
			$this->intMaleStudent = $intNoOfMaleStd;
		}
		function setFemaleStudentCount($intNoOfFemaleStd){
			$this->intFemaleStudent = $intNoOfFemaleStd;
		}
		function setChildrenCount($intChildrenCount){
			$this->intChildren = $intChildrenCount;
		}
		function setPreacher($strPreacherName){
			$this->strPreacher = $strPreacherName;
		}
		function setMessage($strMessage){
			$this->strMessage = $strMessage;
		}
		function setFirstTimer($intNoOfFirstTimer){
			$this->intFirstTimer = $intNoOfFirstTimer;
		}
		function setTotal($intTot){
			$this->intTotal = $intTot;
		}
		function setSundaySchoolCount($intSunSchoolCount){
			$this->intSundaySchoolCount = $intSunSchoolCount;
		}

		function getDate(){
			return $this->dtmDate;
		}
		function getAttendanceID(){
			return $this->intAttendanceID;
		}
		function getServiceType(){
			return $this->strServiceType;
		}
		function getMaleCount(){
			return $this->intMale;
		}
		function getFemaleCount(){
			return $this->intFemale;
		}
		function getMaleStudentCount(){
			return $this->intMaleStudent;
		}
		function getFemaleStudentCount(){
			return $this->intFemaleStudent;
		}
		function getChildrenCount(){
			return $this->intChildren;
		}
		function getPreacher(){
			return $this->strPreacher;
		}
		function getMessage(){
			return $this->strMessage;
		}
		function getFirstTimer(){
			return $this->intFirstTimer;
		}
		function getTotal(){
			return $this->intTotal;
		}
		function getSundaySchoolCount(){
			return $this->intSundaySchoolCount;
		}
}

?>
