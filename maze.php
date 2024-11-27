<?php

header('Content-Type: image/png');

function getTile($tile, $mazeTile){        
    $tuile = imagecreatetruecolor(64, 64);
    $cadrant = array("murN"=>$tile["murN"], "murS"=>$tile["murS"], "murE"=>$tile["murE"], "murW"=>$tile["murW"]);
    switch($cadrant){

        // 0 murs
        case array("murN"=>false, "murS"=>false, "murE"=>false, "murW"=>false): // aucun mur
            $x = 0;
            $angle = 0;
            break;
        
        // 3 murs
        case array("murN"=>true, "murS"=>false, "murE"=>true, "murW"=>true): // ouverture S
            $x = 2;
            $angle = 0;
            break;
        case array("murN"=>true, "murS"=>true, "murE"=>false, "murW"=>true): // ouverture E
            $x = 2;
            $angle = 90;
            break;
        case array("murN"=>false, "murS"=>true, "murE"=>true, "murW"=>true): // ouverture N
            $x = 2;
            $angle = 180;
            break;
        case array("murN"=>true, "murS"=>true, "murE"=>true, "murW"=>false): // ouverture W
            $x = 2;
            $angle = 270;
            break;
        

        // 2 murs opposés
        case array("murN"=>false, "murS"=>false, "murE"=>true, "murW"=>true): // ouverture N et S
            $x = 4;
            $angle = 0;
            break;
        case array("murN"=>true, "murS"=>true, "murE"=>false, "murW"=>false): // ouverture W et E
            $x = 4;
            $angle = 90;
            break;
        

        // 2 murs adjacents
        case array("murN"=>true, "murS"=>false, "murE"=>true, "murW"=>false): // ouverture S et W
            $x = 1;
            $angle = 0;
            break;
        case array("murN"=>true, "murS"=>false, "murE"=>false, "murW"=>true): // ouverture S et E
            $x = 1;
            $angle = 90;
            break;
        case array("murN"=>false, "murS"=>true, "murE"=>false, "murW"=>true): // ouverture N et E
            $x = 1;
            $angle = 180;
            break;
        case array("murN"=>false, "murS"=>true, "murE"=>true, "murW"=>false): // ouverture N et W
            $x = 1;
            $angle = 270;
            break;
        

        // 1 mur
        case array("murN"=>false, "murS"=>false, "murE"=>true, "murW"=>false): // ouverture N W S
            $x = 3;
            $angle = 0;
            break;
        case array("murN"=>true, "murS"=>false, "murE"=>false, "murW"=>false): // ouverture W S E
            $x = 3;
            $angle = 90;
            break;
        case array("murN"=>false, "murS"=>false, "murE"=>false, "murW"=>true): // ouverture S E N
            $x = 3;
            $angle = 180;
            break;
        case array("murN"=>false, "murS"=>true, "murE"=>false, "murW"=>false): // ouverture E N W
            $x = 3;
            $angle = 270;
            break;
        default:
            $x = 0;
            $angle = 0;
            break;
    }
    imagecopy($tuile, $mazeTile, 0, 0, $x*64, 0, 64, 64);
    $tuile = imagerotate($tuile, $angle, 0);
    return $tuile;
}

function afficherMaze($maze, $longueur, $largeur, $solving, $entree, $sortie){
    $tuile = null;
    $mazeTileWhite = imagecreatefrompng('./images/2D_Maze_Tiles_White.png');
    $mazeTileRed = imagecreatefrompng('./images/2D_Maze_Tiles_Red.png');
    $mazeTile = null;
    $affichage = imagecreatetruecolor(($longueur*64), ($largeur*64));
    $a = 0;
    $b = 0;
    $temp = solveMaze($maze, $longueur, $largeur, $entree, $sortie);
    $solve = $temp["path"];
    $maze = $temp["maze"];
    if($maze == null){
        echo "Maze is null";
        return;
    }
    for($i = 0; $i < count($maze); $i++){
        if(in_array($i, $solve) && $solving == "true"){
            $mazeTile = $mazeTileRed;
        }
        else{

            $mazeTile = $mazeTileWhite;
        }
        $tuile = getTile($maze[$i], $mazeTile);
        imagecopy($affichage, $tuile, $a, $b, 0, 0, 64, 64);
        
        $a += 64;
        if($a >= $longueur*64){
            $a = 0;
            $b += 64;
        }
    }
    //print_r($solve);
    imagepng($affichage);
    imagedestroy($tuile);
    imagedestroy($mazeTileWhite);
    imagedestroy($affichage);
    


}

?>
<?php 


//Génère un tableau de tuiles en objet php
function defMaze($n, $m){
    $maze = array();
    for($i=0; $i<$n*$m; $i++){
        $tile = array(
            "composante" => $i,
            "murN" => true,
            "murS" => true,
            "murE" => true,
            "murW" => true
        );
        $maze[] = $tile;
    }
    return $maze;
};

//Permet d'abaisser la composante de toutes les tuiles concernées
function editComposante($maze, $composante, $new_composante){
    $min = min($composante, $new_composante);
    $max = max($composante, $new_composante);
    //printf("Min : $min , Max : $max\n");
    for($i=0; $i<count($maze); $i++){
        if($maze[$i]["composante"] == $max){
            $maze[$i]["composante"] = $min;
        }
    }

    return $maze;
};

// chooses a random side(N,S,E,W) with a different component
// than the one at index $i
function editTile($maze, $width, $height, $i, $cadrant){
    if(count($cadrant) == 0) return $maze; // condition darret de la recursivite
    $pos_x = $i % $width;
    $pos_y = intdiv($i, $width);

    //print("pos_x : $pos_x, pos_y : $pos_y\n");
    if(in_array("murW",array_keys($cadrant)))
    if($pos_x == 0 || $cadrant["murW"] == false)
        unset($cadrant["murW"]);

    if(in_array("murE",array_keys($cadrant)))
    if($pos_x == $width - 1 || $cadrant["murE"] == false)
        unset($cadrant["murE"]);

    if(in_array("murN",array_keys($cadrant)))
    if($pos_y == 0 || $cadrant["murN"] == false)
        unset($cadrant["murN"]);

    if(in_array("murS",array_keys($cadrant)))
    if($pos_y == $height - 1 || $cadrant["murS"] == false)
        unset($cadrant["murS"]);

    if(count($cadrant) == 0) return $maze; // condition darret de la recursivite
    $face = rand(0, count($cadrant)-1);
    //print_r(array_keys($cadrant));
    //print_r(array_keys($cadrant)[$face]);
    switch(array_keys($cadrant)[$face]){
        case "murN":
            if($maze[$i - $width]["composante"] != $maze[$i]["composante"]){
                $maze[$i]["murN"] = false;
                $maze[$i - $width]["murS"] = false;
                return editComposante($maze, $maze[$i - $width]["composante"], $maze[$i]["composante"]);
            }else{
                unset($cadrant["murN"]);
                return editTile($maze, $width, $height, $i, $cadrant);
            }
        case "murS":
            if($maze[$i + $width]["composante"] != $maze[$i]["composante"]){
                $maze[$i]["murS"] = false;
                $maze[$i + $width]["murN"] = false;
                return editComposante($maze, $maze[$i + $width]["composante"], $maze[$i]["composante"]);
            }else{
                unset($cadrant["murS"]);
                return editTile($maze, $width, $height, $i, $cadrant);
            }
        case "murE":
            if($maze[$i + 1]["composante"] != $maze[$i]["composante"]){
                $maze[$i]["murE"] = false;
                $maze[$i + 1]["murW"] = false;
                return editComposante($maze, $maze[$i + 1]["composante"], $maze[$i]["composante"]);
            }else{
                unset($cadrant["murE"]);
                return editTile($maze, $width, $height, $i, $cadrant);
            }
        case "murW":
            if($maze[$i - 1]["composante"] != $maze[$i]["composante"]){
                $maze[$i]["murW"] = false;
                $maze[$i - 1]["murE"] = false;
                return editComposante($maze, $maze[$i - 1]["composante"], $maze[$i]["composante"]);
            }else{
                unset($cadrant["murW"]);
                return editTile($maze, $width, $height, $i, $cadrant);
            }
    }
}

//Génère un tableau représentant un labyrinthe
function loadMaze($longueur, $hauteur, $gen){
    $tab[] = array($longueur*$hauteur);
    for($i=1; $i<=$longueur*$hauteur; $i++){
        $tab[$i-1] = $i;
    }
    $maze = defMaze($longueur, $hauteur);
    srand($gen);
    for($i=0; $i<$longueur*$hauteur; $i++){ // on parcours toutes les cases du tableau
        $cadrant = array("murN" => $maze[$i]["murN"], "murS" => $maze[$i]["murS"], "murE" => $maze[$i]["murE"], "murW" => $maze[$i]["murW"]);
        $maze = editTile($maze, $longueur, $hauteur, $i, $cadrant);
        //print("Tile $i done!\n");
    }   
    
    return $maze;
}
function setEntreeSortie($maze, $entree, $sortie, $longueur, $hauteur){
    

    if($entree === null){
        $entree = rand(0, $longueur);
    }
    if($sortie === null){
        $sortie = rand(0, $longueur)*($hauteur-1);
    }

    $val = $entree;
    for($i=0; $i<2; $i++){
        $pos_x = $val % $longueur;
        $pos_y = intdiv($val, $longueur);
        if($pos_x == 0)
            $maze[$val]["murW"] = false;
        else if($pos_x == $longueur - 1)
            $maze[$val]["murE"] = false;
        else if($pos_y == 0)
            $maze[$val]["murN"] = false;
        else if($pos_y == $hauteur - 1)
            $maze[$val]["murS"] = false;   
        else if($i == 0){
            $val = rand(0, $longueur);
            $maze[$val]["murN"] = false;
            $entree = $val;
        }
        else{
            $val = rand(0, $longueur);
            $val = $val + $longueur*($hauteur-1);
            $maze[$val]["murS"] = false;
            $sortie = $val;
        }
        $val = $sortie;


    }
    return array("entree" => $entree, "sortie" => $sortie, "maze" => $maze);

}

function solveMaze($maze, $longueur, $hauteur, $entree, $sortie){
    $temp = setEntreeSortie($maze, $entree, $sortie, $longueur, $hauteur);
    $entree = $temp["entree"];
    $sortie = $temp["sortie"];
    $maze = $temp["maze"];
    $index = $entree;
    $tile = $maze[$index];
    $path = array();
    $went = array();
    array_push($path, $index);
    array_push($went, $index);
    while($index != $sortie){    
        if($tile["murN"] == false && !in_array($index - $longueur, $went) && $index - $longueur >= 0){
            array_push($path ,$index - $longueur);
            $index -= $longueur;
        }else if($tile["murS"] == false && !in_array($index + $longueur, $went) && $index + $longueur < $longueur*$hauteur){
            array_push($path ,$index + $longueur);
            $index += $longueur;
        }else if($tile["murE"] == false && !in_array($index + 1, $went) && $index % $longueur != $longueur - 1){
            array_push($path ,$index + 1);
            $index += 1;
        }else if($tile["murW"] == false && !in_array($index - 1, $went) && $index % $longueur != 0){
            array_push($path ,$index - 1);
            $index -= 1;
        }else{
            array_pop($path);
            $index = end($path);
            if(count($path) == 0){
                return array("path" => array(), "maze" => $maze);
            }}
        $tile = $maze[$index];
        if(!in_array($index, $went)){
            array_push($went, $index);}
        
    }

    return array("path" => $path, "maze" => $maze);
    }



$longueur = $_GET["longueur"];
$hauteur = $_GET["hauteur"];
$solving = $_GET["solving"];
$gen = $_GET["gen"];
$entree = $_GET["entree"];
$sortie = $_GET["sortie"];

$maze = loadMaze($longueur, $hauteur, $gen);
//echo "Longueur: $longueur, Hauteur: $hauteur, Solving: $solving, Gen: $gen, Entree: $entree, Sortie: $sortie<br>";

afficherMaze($maze, $longueur, $hauteur, $solving, $entree, $sortie);
?>


