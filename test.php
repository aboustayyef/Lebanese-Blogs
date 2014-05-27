<?php 
	/* testing */

$title = "This is the best";
$title2 = "five reasons why i suck";
$title3 = "12 different ways to eat mango";

if (isListicle($title)){
	echo $title . " is a listicle <br>";
}

if (isListicle($title2)){
	echo $title2 . " is a listicle <br>";
}

if (isListicle($title3)){
	echo $title3 . " is a listicle <br>";
}

function isListicle($title){
  $title = strtolower($title); 
  $parts = explode(" ", $title);
  $firstWord = $parts[0];
  $secondWord = $parts[1];
  $listOfNumbers = array('3','4','5','6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', 'three','four','five','six','seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
  if ((in_array($firstWord, $listOfNumbers))||(in_array($secondWord, $listOfNumbers))) {
    return TRUE;
  }else{
    return FALSE;
  }
}
?>