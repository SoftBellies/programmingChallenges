<?php
sapin(1);
sapin(2);
sapin(3);
sapin(4);
sapin(5);
function sapin($taille){

$sapinEtoiles=array();
$countEtoile=1;
$decrement=2;
  //les branches
  for($branches=1;$branches <= $taille;$branches++){
    for ($lignes= 1; $lignes <= ($branches +3); $lignes++){
      //mettre les *
      $sapinEtoiles[]=$countEtoile;
      $countEtoile=$countEtoile+2;
     }
     //décrementer count *
     if(!fmod($branches,2) == 0){
	//ligne paire
	$decrement=$decrement + 2;
     }
    $countEtoile=$countEtoile-$decrement;
  }
  $baseLenght=$countEtoile + $decrement;
  
  //on a le sapin dans un array
  foreach($sapinEtoiles as $lines){
    $nbespaces=($baseLenght-$lines)/2;
    for($char=0;$char<$nbespaces;$char++){
      echo " ";
    }
    for($char=0;$char<$lines;$char++){
      echo "*";
    }
    echo "\n";
  }
  
  //le tronc
  //nbe de blancs
  $nbespaces=($baseLenght-$taille)/2;
  if(!fmod($branches,2) == 0){
	//ligne paire
	$nbespaces=$nbespaces-1;
  }
  for($ligne=0;$ligne<$taille;$ligne++){
    for($spaces=0;$spaces<$nbespaces;$spaces++){
      echo " ";
    }
    for($pied=0;$pied<$taille;$pied++){
      echo "|";
    }
    if(!fmod($branches,2) == 0){
	//ligne paire
	echo "|";
    }
    echo "\n";
  }
}
