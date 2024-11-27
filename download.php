<?php
//Open Classrooms pour récupérer l'url d'un fichier
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$fullUrl = $protocol . $host . $requestUri;
$localhost = str_replace('download.php', 'maze.php', $fullUrl);

$image = imagecreatefrompng($localhost);
if ($image === false) {
    die("Erreur : Impossible de charger l'image depuis l'URL.");
}

$longueur = 5;
if(isset($_GET["longueur"]) && $_GET["longueur"] != null){
    $longueur = $_GET["longueur"];
}
$hauteur = 5;
if(isset($_GET["hauteur"]) && $_GET["hauteur"]!= null){
    $hauteur = $_GET["hauteur"];
}
$type = "png";
if(isset($_GET["type"]) && $_GET["type"] != null){
    $type = $_GET["type"];
}

$width = $longueur * 64;
if(isset($_GET["width"]) && $_GET["width"] != null){
    $width = $_GET["width"];
}

$height = $hauteur * 64;
if(isset($_GET["height"]) && $_GET["height"] != null){
    $height = $_GET["height"];
}


$download = imagecreatetruecolor($width, $height);


if($type == "jpeg"){
    header('Content-Type: image/jpeg');
    header('Content-Disposition: attachment; filename="maze.jpeg"');
    imagecopyresized($download, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
    imagejpeg($download, null, 100);
}else{ 
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="maze.png"');
    imagecopyresized($download, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));

    imagepng($download);
}

imagedestroy($image);
imagedestroy($download);
?>

