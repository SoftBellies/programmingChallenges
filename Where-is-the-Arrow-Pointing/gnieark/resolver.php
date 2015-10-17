<?php
/*
* By Gnieark https://blog-du-grouik.tinad.fr oct 2015
* Anwser to "code golf" http://codegolf.stackexchange.com/questions/57952/where-is-the-arrow-pointing in PHP
*/

//ouvrir le fichier contenant la "carte et l'envoyer dans un array 2 dimmension
$mapLines=explode("\n",file_get_contents('./input.txt'));
$i=0;
foreach($mapLines as $ligne){
    $map[$i]=str_split($ligne,1);
    if((!isset($y)) && in_array('S',$map[$i])){
        //tant qu'à parcourir la carte, on cherche le "S" s'il n'a pas déjà été trouvé.
        $y=$i;
        $x=array_search('S',$map[$i]);
    }
    $i++;
}
if(!isset($y)){
    echo "Il n'y a pas de départ S dans ce parcours";
    die;
}
echo "\n".file_get_contents('./input.txt')."\nLe départ est aux positions ".$map[$y][$x]." [".$x.",".$y."]. Démarrage du script...\n";

$previousX=-1; // Contiendra l'ordonnée de la position précédente. (pour le moment, une valeur incohérente)
$previousY=-1; // Contiendra l'absycede la position précédente. (pour le moment, une valeur incohérente)
$xMax=count($map[0]) -1;
$yMax=count($map) -1;
$previousCrosses=array(); //On ne gardera en mémoire que les croisements, pas l'ensemble du chemin.

while(1==1){ // C'est un défi de codagee, pas un script qui sera en prod. j'assume.
    switch($map[$y][$x]){
        case "S":
            //même fonction que "+"
        case "+":
            //on peut aller dans les 4 directions
            $target=whereToGoAfterCross($x,$y,$previousX,$previousY);
            if($target){
	      go($target[0],$target[1]);
            }else{
	      goToPreviousCross();
            }
            break;
        case "s":
            goToPreviousCross();
            break;
        case "-":
	    //déplacement horizontal
	    if($previousX < $x){
	      //vers la droite
	      $targetX=$x+1;
	      if(in_array($map[$y][$targetX],array('-','+','S','>','^','V'))){
		go($targetX,$y);
	      }else{
		//On est dans un cul de sac
		goToPreviousCross();
	      }
	    }else{
	      //vers la gauche
	      $targetX=$x-1;
	      if(in_array($map[$y][$targetX],array('-','+','S','<','^','V'))){
		go($targetX,$y);
	      }else{
		//On est dans un cul de sac
		goToPreviousCross();
	      }
	    }
            break;
        case "|":
	    //déplacement vertical
	    if($previousY < $y){
	      //on descend (/!\ y augmente vers le bas de la carte)
	      $targetY=$y+1;
	      if(in_array($map[$targetY][$x],array('|','+','S','>','<','V'))){
		go ($x,$targetY);
	      }else{
		goToPreviousCross();
	      }
	    }else{
	    //on Monte (/!\ y augmente vers le bas de la carte)
	      $targetY=$y - 1;
	      if(in_array($map[$targetY][$x],array('|','+','S','>','<','V'))){
		go ($x,$targetY);
	      }else{
		goToPreviousCross();
	      } 
	    }
            break;

	case "^":
	case "V":
	case ">":
	case "<":
	  wheAreOnAnArrow($map[$y][$x]);
	  break;
    }
}
function wheAreOnAnArrow($arrow){

  global $x,$y,$xMax,$yMax,$map;
  switch($arrow){
    case "^":
      $targetX=$x;
      $targetY=$y -1;
      $charsOfTheLoose=array(" ","V","-","s");
      break;
    case "V":
      $targetX=$x;
      $targetY=$y + 1;
      $charsOfTheLoose=array(" ","^","-","s");
      break;
    case ">":
      $targetX=$x + 1;
      $targetY=$y;
      $charsOfTheLoose=array(" ","<","|","s");   
      break;
    case "<":
      $targetX=$x - 1;
      $targetY=$y;
      $charsOfTheLoose=array(" ",">","|","s");   
      break;
    default:     
      break;
  }
  if(($targetX <0) OR ($targetY<0) OR ($targetX>$xMax) OR ($targetY>$yMax) OR (in_array($map[$targetY][$targetX],$charsOfTheLoose))){
      //on sort du cadre ou on tombe sur un caractere inadapté
      goToPreviousCross();
  }else{
    if(preg_match("/^[a-z]$/",strtolower($map[$targetY][$targetX]))){
      //WIN
      echo "WIN: ".$map[$targetY][$targetX]."\n";
      die;
     }else{
      //on va sur la cible
      go($targetX,$targetY);
     }
  }
}
function whereToGoAfterCross($xCross,$yCross,$previousX,$previousY){
  
            //haut
            if(canGoAfterCross($xCross,$yCross +1 ,$xCross,$yCross,$previousX,$previousY)){
                return array($xCross,$yCross +1);
            }elseif(canGoAfterCross($xCross,$yCross -1 ,$xCross,$yCross,$previousX,$previousY)){
                //bas
                return array($xCross,$yCross -1);
            }elseif(canGoAfterCross($xCross-1,$yCross,$xCross,$yCross,$previousX,$previousY)){
                //gauche
                return array($xCross-1,$yCross);
            }elseif(canGoAfterCross($xCross+1,$yCross,$xCross,$yCross,$previousX,$previousY)){
                //droite
                return array($xCross+1,$yCross);
            }else{
	      //pas de direction possible
	      return false;
            }  
}

function canGoAfterCross($xTo,$yTo,$xNow,$yNow,$xPrevious,$yPrevious){
    global $previousCrosses,$xMax,$yMax,$map;
    if(($xTo < 0) OR ($yTo < 0) OR ($xTo >= $xMax) OR ($yTo >= $yMax)){return false;}// ça sort des limites de la carte
    if(
	($map[$yTo][$xTo]==" ") // on ne va pas sur un caractere vide
	OR (($xTo==$xPrevious)&&($yTo==$yPrevious)) //on ne peut pas revenir sur nos pas (enfin, ça ne servirait à rien dans cet algo)
	OR (($xTo==$xNow)&&($map[$yTo][$xTo]=="-")) //Déplacement vertical, le caractere suivant ne peut etre "-"
	OR (($yTo==$yNow)&&($map[$yTo][$xTo]=="|")) // Déplacement horizontal, le caractère suivant ne peut être "|"
	OR ((isset($previousCrosses[$xNow."-".$yNow])) && (in_array($xTo."-".$yTo,$previousCrosses[$xNow."-".$yNow]))) //croisement, ne pas prendre une direction déjà prise
   ){    
      return false;
    }
    return true;    
}

function go($targetX,$targetY){
    global $previousX,$previousY,$x,$y,$previousCrosses,$map;
    if(($map[$y][$x]=='S')OR ($map[$y][$x]=='+')){
        //on enregistre le croisement dans lequel on renseigne la direction prise et la direction d'origine
        $previousCrosses[$x."-".$y][]=$previousX."-".$previousY;
        $previousCrosses[$x."-".$y][]=$targetX."-".$targetY; 
    }
    $previousX=$x;
    $previousY=$y;
    $x=$targetX;
    $y=$targetY;
    //debug
    echo "deplacement en ".$x.";".$y."\n";   
}
function goToPreviousCross(){
  global $x,$y,$previousX,$previousY,$xMax,$yMax,$previousCrosses;
  
  /*
  * On va remonter aux précédents croisements jusqu'à ce 
  * qu'un nouveau chemin soit exploitable
  */
  foreach($previousCrosses as $key => $croisement){
    list($crossX,$crossY)=explode("-",$key);
    $cross=whereToGoAfterCross($crossX,$crossY,-1,-1);
    if($cross){
      go($crossX,$crossY);
      return true;
    } 
  }
  //si on arrive là c'est qu'on est bloqués
  echo "Aucun chemin n'est possible\n";
  die;
}