<?php
echo shikakugen(8,4,5);
function shikakugen($largeur,$hauteur,$nbeCarres){
 a:
    $buff="T ".$largeur." ".$hauteur."\n";
    $grille=array();
    $aireParCarre=array();
    //préparer la grille dans un array
    for($i=0;$i<$largeur;$i++){
        for($j=0;$j<$hauteur;$j++){
            $grille[$i][$j]=0; //0 -> case libre 1 -> case prise par un carré
        }
    }
    
    //tirer en random l'aire de chaque carré
    $aireRestante=$largeur * $hauteur;
    for($i=1;$i<$nbeCarres;$i++){
	$aireOK=false;
	while($aireOK==false){
	  $aire=rand(2,$aireRestante - $nbeCarres + $i);
	  //on va éliminer quelques valeurs.
	  $aireOK=true;
	  if((in_array($aire,array(2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41, 43, 47, 53, 59, 61, 67, 71, 73, 79, 83, 89, 97))) 
	      && ($aire>$largeur) 
	      && ($aire>$hauteur)){
	      /* 
	      * C'est un nombre premier, donc le seul carré posssible aura un coté =1 et l'autre coté= aire. 
	      * si ce dernier coté est supérieur aux cotés de la grille, on ne pourra jamais le placer.
	      */
	      $aireOK=false;
	  }
        }
        $aireParCarre[]=$aire;
        $aireRestante=$aireRestante-$aire;
        //on remplit un passage un array contenant les diviseurs possibles de l'aire.
        $diviseurs[$aire]=trouveDiviseurs($aire);
    }
    
    //le dernier carré prend l'aire restante
    if($aireRestante < 2){goto a;}//dans les regles de zigazou, carré de taille 1 interdit
    $aireParCarre[]=$aireRestante;
    $diviseurs[$aireRestante]=trouveDiviseurs($aireRestante);
    shuffle($aireParCarre);
    
    foreach($aireParCarre as $carre){
        //chercher la première position x,y libre
        for($y=0;$y<$hauteur;$y++){
            for($x=0;$x<$largeur;$x++){
            
                if($grille[$x][$y]==0){
                    //tenter de placer le carré là
                    $largeurMax=largeurLibre($x,$y,$grille);
                    $hauteurMax=hauteurLibre($x,$y,$grille);
                    $largeurRetenue=0;
                    $hauteurRetenue=0;              
                    //on place le carré avec la largeur la plus grande possible
                    foreach($diviseurs[$carre] as $div){
                        if(($div[0] <= $largeurMax) && ($div[1] <= $hauteurMax)){
                            $largeurRetenue=$div[0];
                            $hauteurRetenue=$div[1];
                            $xRetenu=$x;
                            $yRetenu=$y;
                        }
                    }
                    if($largeurRetenue > 0){
                        //placer le carré
                        //choisir un nombre au hazar dans la liste des carrés qu'on va placer
                        $placeIndice=rand(1,$largeurRetenue*$hauteurRetenue);
                        $count=1;
                        for($xTemp=$xRetenu;$xTemp<$xRetenu + $largeurRetenue;$xTemp++){
                            for($yTemp=$yRetenu;$yTemp<$yRetenu + $hauteurRetenue;$yTemp++){
                                $grille[$xTemp][$yTemp]=1;
                                if($count==$placeIndice){
                                   $buff.=$largeurRetenue*$hauteurRetenue." ".$xTemp." ".$yTemp."\n";
                                }
                                $count++;
                            }
                        }
                        break 2; //le carré est placé, sert à rien de tester les autres cases.
                    }
                }
            }
        }
        if($largeurRetenue==0){
            //limpossibilité de placer le carré. retentons aec un autre tirage d'aires
            goto a;
        }
    }
    
    return $buff;
}
function trouveDiviseurs($nombre){
    // Retourne les couples  largeur et hauteur pour une aire donnée
    static $diviseurs = array();
    if(isset($diviseurs[$nombre])){
        //si jamais ça a déja été calculé
        return $diviseurs[$nombre];
    }
    $diviseurs[$nombre]=array();
    for($i=1;$i<=sqrt($nombre);$i++){
        if (fmod($nombre,$i)==0){
            $diviseurs[$nombre][]=array($i,$nombre/$i);
        }
    }
    return $diviseurs[$nombre];
}
function largeurLibre($posX,$posY,$grille){
    $count=0;
    while(isset($grille[$posX+$count][$posY])){
        if($grille[$posX+$count][$posY]>0){
            break;
        }
        $count ++;
    }
    return $count;
}
function hauteurLibre($posX,$posY,$grille){
    $count=0;
    while(isset($grille[$posX][$posY+$count])){
        if ($grille[$posX][$posY+$count]>0){
            break;
        }
        $count ++;
    }
    return $count;
}
