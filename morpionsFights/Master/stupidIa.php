<?php
$cases=array("0-0","0-1","0-2","1-0","1-1","1-2","2-0","2-1","2-2");
//remplir l'array
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
//have all parameters lets play the game
//Stupid IA, juste random
if (count($freeCases)==0){
	echo "error. la grille est déjà pleine enfoiré";
	die;
}
shuffle($freeCases);
echo $freeCases[0];
