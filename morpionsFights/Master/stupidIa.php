<?php
/*
* Tic Tac Toe stupid IA
* For programming challenge https://github.com/jeannedhack/programmingChallenges
* Gnieark 2015 
* licensed under the Do What the Fuck You Want to Public License http://www.wtfpl.net/
*/

$cases=array("0-0","0-1","0-2","1-0","1-1","1-2","2-0","2-1","2-2");
//filling array
$freeCases=array();
foreach($cases as $case){
	if (!isset($_GET[$case])){
		echo "wrong parameters ".$case; die;
	}
	if($_GET[$case]==""){
		$freeCases[]=$case;
	}
}
if(!isset($_GET['you'])){
	echo "wrong parameters 2"; die;
}
if (count($freeCases)==0){
	echo "error. Grid is full, beach!";
	die;
}
//have all parameters lets play the game
//Stupid IA, just random
shuffle($freeCases);
echo $freeCases[0];
