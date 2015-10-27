<?php

$reads=explode("\n",file_get_contents("input.txt"));

while(count($reads)>1){
    $couples=array();
    for($countGauche=0;$countGauche<count($reads);$countGauche++){
    //tester à gauche
        for($countDroite=0;$countDroite<count($reads);$countDroite++){
            for($i=strlen($reads[$countGauche]) ;$i>0;$i--){
                $chaineATest=substr($reads[$countGauche],0,$i);
                if (($countGauche!==$countDroite) && (preg_match("/".$chaineATest."$/",$reads[$countDroite]))){
                    //tester si cette chaine se retrouve à  droite dans d'autres reads
                    $couples[$countGauche."-".$countDroite]=$i;
                    break;
                }  
            }
        }
    }
  //chercher le couple ayant la plus grosse chaine en commun
    $coupleKey=getKeyOfValueMax($couples);
    if(!$coupleKey){
        echo "malheureusement je trouve ".count($reads)." séquences que je n'arrive pas à diminuer plus:\n";
         print_r($reads);
         die;
    }
    list($gauche,$droite)=explode("-",$coupleKey);
    //cancaténer les deux chaines en supprimant la partie commune
    $reads=arrayDelTwoValuesAndAddOne($reads,$reads[$droite],$reads[$gauche],substr($reads[$droite],0,-$couples[$coupleKey]).$reads[$gauche]);
}

echo $reads[0];

function arrayDelTwoValuesAndAddOne($array,$delValue1,$delValue2,$addValue){
    foreach($array as $key=>$value){
        if($value==$delValue1){
            unset($array[$key]);
            break;
        }
    }
     $array=array_values($array);
    foreach($array as $key=>$value){
            if($value==$delValue2){
                unset($array[$key]);
                break;
            }
     }
     $array=array_values($array);
     $array[]=$addValue;
     return $array;
}
function getKeyOfValueMax($array){
    $max=0;
    foreach($array as $key => $value){
        if($value > $max){
            $value=$max;
            $maxKey=$key; 
        }
    }
    if(isset($key)){
        return $key;
    }else{
        return false;
    }
}
