<?php 

/*******************************************************************
*	This class calculates image dimensions and sets a maximum dimension
*
********************************************************************/ 
class Image
{
	//maximum dimension of either side
	protected $_max;

	protected $_original_height;
	protected $_original_width;
	protected $_factor;

	protected $_desired_height;
	protected $_desired_width;

	function __construct(){}
	
	function setHeight($height){
		$this->_original_height = $height;
	}
	function setWidth($width){
		$this->_original_width = $width;
	}
	function setMax($max){
		$this->_max = $max;
	}

	function calculateDimensions(){
		if (($this->_original_height <= $this->_max) && ($this->_original_width <= $this->_max)){ // if both dimensions within limit
			//don't change anything
			$this->_desired_height = $this->_original_height;
			$this->_desired_width = $this->_original_width;
		} else {
			//depending on aspect ratio
			if ($this->_original_width >= $this->_original_height) { //landscape or square
				$this->_desired_width = $this->_max; // the width takes the maximum size
				$this->_factor= $this->_max / $this->_original_width ;
				$this->_desired_height = $this->_original_height * $this->_factor;
			} else { // portrait
				$this->_desired_height = $this->_max;
				$this->_factor = $this->_original_height / $this->_max;
				$this->_desired_width = $this->_original_width / $this->_factor;
			}
		}
	}

	function getDesiredHeight(){
		return $this->_desired_height;
	}
	function getDesiredWidth(){
		return $this->_desired_width;
	}
}
?>