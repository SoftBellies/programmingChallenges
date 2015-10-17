<?php
/*
* By Gnieark https://blog-du-grouik.tinad.fr oct 2015
* Anwser to "code golf" http://codegolf.stackexchange.com/questions/57952/where-is-the-arrow-pointing in PHP
* short version
*/
$a=explode("\n",file_get_contents('./input.txt'));
$i=0;
foreach($a as $b){
    $c[$i]=str_split($b,1);
    if((!isset($y)) && in_array('S',$c[$i])){
        $y=$i;
        $x=array_search('S',$c[$i]);
    }
    $i++;
}
if(!isset($y)){
    echo "Il n'y a pas de départ S dans ce parcours";
    die;
}
echo "\n".file_get_contents('./input.txt')."\nLe départ est aux positions ".$c[$y][$x]." [".$x.",".$y."]. Démarrage du script...\n";
$d=-1; 
$e=-1; 
$f=count($c[0]) -1;
$g=count($c) -1;
$h=array(); 
while(1==1){ 
    switch($c[$y][$x]){
        case "S":
        case "+":
            $j=a($x,$y,$d,$e);
            if($j){ c($j[0],$j[1]);}
            else{b();}
            break;
        case "s":
            b();
            break;
        case "-":
	    if($d < $x){
	      $k=$x+1;
	      if(in_array($c[$y][$k],array('-','+','S','>','^','V'))){c($k,$y);}
	      else{b();}
	    }else{
	      $k=$x-1;
	      if(in_array($c[$y][$k],array('-','+','S','<','^','V'))){c($k,$y);}
	      else{b();}
	    }
            break;
        case "|":
	    if($e < $y){
	      $l=$y+1;
	      if(in_array($c[$l][$x],array('|','+','S','>','<','V'))){c ($x,$l);}
	      else{b();}
	    }else{
	      $l=$y - 1;
	      if(in_array($c[$l][$x],array('|','+','S','>','<','V'))){c ($x,$l);}else{b();} 
	    }
            break;
	case "^":
	case "V":
	case ">":
	case "<":
	  d($c[$y][$x]);
	  break;
    }
}
function d($m){
  global $x,$y,$f,$g,$c;
  switch($m){
    case "^":
      $k=$x;
      $l=$y -1;
      $n=array(" ","V","-","s");
      break;
    case "V":
      $k=$x;
      $l=$y + 1;
      $n=array(" ","^","-","s");
      break;
    case ">":
      $k=$x + 1;
      $l=$y;
      $n=array(" ","<","|","s");   
      break;
    case "<":
      $k=$x - 1;
      $l=$y;
      $n=array(" ",">","|","s");   
      break;
    default:     
      break;
  }
  if(($k <0) OR ($l<0) OR ($k>$f) OR ($l>$g) OR (in_array($c[$l][$k],$n))){b();}else{
    if(preg_match("/^[a-z]$/",strtolower($c[$l][$k]))){
      echo "WIN: ".$c[$l][$k]."\n";
      die;
     }else{c($k,$l);}
  }
}
function a($xCross,$yCross,$d,$e){
    if(e($xCross,$yCross +1 ,$xCross,$yCross,$d,$e)){return array($xCross,$yCross +1);}
    elseif(e($xCross,$yCross -1 ,$xCross,$yCross,$d,$e)){return array($xCross,$yCross -1);}
    elseif(e($xCross-1,$yCross,$xCross,$yCross,$d,$e)){return array($xCross-1,$yCross);}
    elseif(e($xCross+1,$yCross,$xCross,$yCross,$d,$e)){return array($xCross+1,$yCross);}
    else{return false;}  
}
function e($o,$p,$q,$r,$s,$t){
    global $h,$f,$g,$c;
    if(($o < 0) OR ($p < 0) OR ($o >= $f) OR ($p >= $g)){return false;}
    if(
	($c[$p][$o]==" ")
	OR (($o==$s)&&($p==$t))
	OR (($o==$q)&&($c[$p][$o]=="-"))
	OR (($p==$r)&&($c[$p][$o]=="|"))
	OR ((isset($h[$q."-".$r])) && (in_array($o."-".$p,$h[$q."-".$r])))
   ){return false;}
    return true;    
}

function c($k,$l){
    global $d,$e,$x,$y,$h,$c;
    if(($c[$y][$x]=='S')OR ($c[$y][$x]=='+')){
        $h[$x."-".$y][]=$d."-".$e;
        $h[$x."-".$y][]=$k."-".$l; 
    }
    $d=$x;
    $e=$y;
    $x=$k;
    $y=$l;
    echo "deplacement en ".$x.";".$y."\n";   
}
function b(){
  global $x,$y,$d,$e,$f,$g,$h;
  foreach($h as $v => $u){
    list($w,$z)=explode("-",$v);
    $aa=a($w,$z,-1,-1);
    if($aa){
      c($w,$z);
      return true;
    } 
  }
  echo "Aucun chemin n'est possible\n";
  die;
}
