<?php 

/**
*   This script is solely for the sending me email
*   it is used for feedback and for blog suggestions
*   via an ajax request from lb pages
*
* 	==
*	IMPORTANT: ALL SANITIZATION AND VALIDATION SHOULD BE MADE AT CLIENT SIDE
*	===
*
*	required POST variables: key, kind & submittedText
*
*
*/ 

$hidden_key = "qsdljkhqepoi3420$98346adfs34"; //used to make sure call is made from my own script;

if ($_POST['key'] == $hidden_key) { //can proceed

	$submitted = $_POST['submittedText'];
	$messageKind = $_POST['kind']; //'FEEDBACK' or 'SUBMISSION' 
	if(mail("mustapha.hamoui@gmail.com", "[lebaneseblogs.com] $messageKind", $submitted)){ //success
		echo "Email Sent Succesfully";
	}else {
		echo "failure to send email";
	};
} else {
	echo "failure to authenticate";
}
?>