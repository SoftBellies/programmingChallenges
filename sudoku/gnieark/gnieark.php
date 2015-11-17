<?php


//separer les map
$linesByLines=explode("\n",file_get_contents("easy.txt"));
$grilleNumber=-1;
$i=-1;

while($i<count($linesByLines)){
  do{
    $i++;
  }while (preg_match("/^\+-/", $linesByLines[$i])); //credit Zigazou pour cette ligne
      

  //on est sur la ligne de bordure haute
  $grilleNumber++;
  $lineNumber=0;

  do{
      $line=str_split(substr($linesByLines[$i],1,-1));
      for($j=0;$j<count($line);$j=$j+2){
	$grille[$grilleNumber][$lineNumber][]=$line[$j];
      }
      //echo "!!!".substr($linesByLines[$i],1,-1)."!!!\n";
      
      $lineNumber++;
      $i++;
  }while (!preg_match("/^\+-/", $linesByLines[$i]));

  viewGrid(resolveSudoku($grille[$grilleNumber]));

}

function resolveSudoku($grille){
   
    //compter les cases libres
    $numberOfFreeCases=0;
    for ($y=0;$y<9;$y++){
        for($x=0;$x<9;$x++){
            if($grille[$y][$x]==" "){
                $numberOfFreeCases++;
            }
        }
    }

    while( $numberOfFreeCases>0){

    
       // echo $numberOfFreeCases."\n";
        //toutes les cases
        for ($y=0;$y<9;$y++){
            for($x=0;$x<9;$x++){
                //si la case est vide
                if($grille[$y][$x]==" "){
                    $numbersAvailable=array();
                    for($k=1;$k<10;$k++){
                        if (canBePlaced($k,$x,$y,$grille)){
                            $numbersAvailable[]=$k;
                        }
                    }
                    if(count($numbersAvailable)==1){
                        //only 1 number can be placed there
                        $grille[$y][$x]=$numbersAvailable[0];
                        $numberOfFreeCases--;
                    }else{
                        //tester si une des valeurs doit etre là dans le sens où elle ne peut pas etre
                        // placée ailleurs dans son petit carré, sa ligne ou sa colonne
                        
                        $listsOfFreeCasesSame=listFreeCasesNearMe($x,$y,$grille);
                        foreach ($listsOfFreeCasesSame as $freeCasesSame){
			    foreach ($numbersAvailable as $number){
			      $cantPlace=true;
				foreach($freeCasesSame as $case){
				  if(canBePlaced($number,$case['x'],$case['y'],$grille)){
				    $cantPlace=false;
				    break;
				  }
				}
				if($cantPlace){
				  //on va le placer ici
				  $grille[$y][$x]= $number;
				  $numberOfFreeCases--;
				  break 2;
				}
			    }
			}
                    }
                    

            }
        }
      }
    }

    return $grille;


}

function listFreeCasesNearMe($x,$y,$grid){
    //ligne
    $freeOnSameLine=array();
    for($i=0;$i<9;$i++){
        if(($grid[$y][$i]==" ") && ($x<>$i)){
            $freeOnSameLine[]=array('x'=>$i,'y'=>$y);
        }
    }
    //colonne
    $freeOnSameColumn=array();
    for($i=0;$i<9;$i++){
        if(($grid[$i][$x]==" ") && ($y<>$i)){
            $freeOnSameColumn[]=array('x'=>$x,'y'=>$i);
        }
    }    
    //carré 3X3
    if($x<3){
        $xMin=0;
    }elseif(($x>2) && ($x<6)){
        $xMin=3;
    }else{
        //x>=6
        $xMin=6;
    }

    if($y<3){
        $yMin=0;
    }elseif(($y>2) && ($y<6)){
        $yMin=3;	
    }else{
        //y>=6
        $yMin=6;
    }
    for($k=$yMin;$k<$yMin + 3;$k++){
        for($l=$xMin;$l<$xMin + 3;$l++){
            if($grid[$k][$l]==" "){
                $freeOnSameCarre[]=array('x'=>$l,'y'=>$k);
            }
        }
    }
    return array($freeOnSameLine,$freeOnSameColumn,$freeOnSameCarre);
}
function canBePlaced($number,$x,$y,$grid){
    		  //vis à vis des valeurs sur la ligne
                  if(in_array($number,$grid[$y])){
                    return false;
                  }
		  
		  // vis à vis des valeurs libres sur la colonne
		  $col=array();
		  $valeursPossiblesEnColonnes=array();
		  for($k=0;$k<9;$k++){
		      if($number==$grid[$k][$x]){
                        return false;
		      }
		  }
		  
		  //valeurs libres petit carré
		  if($x<3){
		      $xMin=0;
		  }elseif(($x>2) && ($x<6)){
		      $xMin=3;
		  }else{
		      //x>=6
		      $xMin=6;
		  }

		  if($y<3){
		      $yMin=0;
		  }elseif(($y>2) && ($y<6)){
		      $yMin=3;	
		  }else{
		      //y>=6
		      $yMin=6;
		  }
		  
		  $valeursPossiblesPetitCarre=array();
		  $carre=array();
		  for($k=$yMin;$k<$yMin + 3;$k++){
		      for($l=$xMin;$l<$xMin + 3;$l++){
			  if($number==$grid[$k][$l]){
                            return false;
			  }
		      }
		  }
                return true;
}

function viewGrid($grid){
    echo "+-----------------+\n";
    foreach($grid as $line){
     echo "|";
        echo implode(" ",$line);
       echo "|\n";
    }
    echo "+-----------------+\n";
}