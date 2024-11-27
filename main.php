<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        img{
            max-height: 90%;
            margin: 20px;
            padding: 20px;
            float: right;
            
        }
        input[type="number"],
        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        button{
            display: block;
            margin: 15px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
<?php 


$longueur = 5;
if(isset($_GET["longueur"]) && $_GET["longueur"] != null){
    $longueur = $_GET["longueur"];
}
$hauteur = 5;
if(isset($_GET["hauteur"]) && $_GET["hauteur"]!= null){
    $hauteur = $_GET["hauteur"];
}
$solving = "false";
if(isset($_GET["solving"]) && $_GET["solving"] != null){
    if($_GET["solving"] == "true" || $_GET["solving"] == "on")
        $solving = "true";
    else 
        $solving = "false";
}


$gen  = 0;
if(isset($_GET["gen"]) && $_GET["gen"] != null){
    $gen = $_GET["gen"];
}
$entree = 0;
if(isset($_GET["entree"]) && $_GET["entree"] != null){
    $entree = $_GET["entree"];
}
$sortie = 0;
if(isset($_GET["sortie"]) && $_GET["sortie"] != null){
    $sortie = $_GET["sortie"];
}
if($sortie == $entree){
    $sortie = $entree + 1;
}

$width = $longueur * 64;
if(isset($_GET["width"]) && $_GET["width"] != null){
    $width = $_GET["width"];
}

$height = $hauteur * 64;
if(isset($_GET["height"]) && $_GET["height"] != null){
    $height = $_GET["height"];
}


$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$requestUri = $_SERVER['REQUEST_URI'];
$fullUrl = $protocol . $host . $requestUri;
$localhost = str_replace('main.php', 'maze.php', $fullUrl);





?>




<head>
    <title>Création de labyrinthe</title>
</head>
<body>
    
    <form method="get" action="main.php" id="gen">
        <label for="longueur">Longueur:</label>
        <input type="number" id="longueur" name="longueur" value= <?php echo $longueur?> ><br>
        
        <label for="hauteur">Hauteur:</label>
        <input type="number" id="hauteur" name="hauteur" value= <?php echo $hauteur?> ><br>
        
        <label for="solving">
            <input type="checkbox" id="solving" name="solving" checked= <?php echo $solving == "true"?> >
            Solving
        </label><br>
       
       

        <label for="gen">Gen:</label>
        <input type="number" id="gen" name="gen" value= <?php echo $gen?> ><br>
        
        <label for="entree">Entree:</label>
        <input type="number" id="entree" name="entree" value= <?php echo $entree?> ><br>
        
        <label for="sortie">Sortie:</label>
        <input type="number" id="sortie" name="sortie" value= <?php echo $sortie?> ><br>
        
        <input type="submit" value="Generate Maze">
    
    </form>
    <br>
    <select id="type" name="type" form="download">
    <option value="jpeg">jpeg</option>
    <option value="png">png</option>
    </select>
    <br>
    <form method="get" action="download.php" id="download">
    <input type="hidden" id="longueur" name="longueur" value= <?php echo $longueur?> >
    <input type="hidden" id="hauteur" name="hauteur" value= <?php echo $hauteur?> >
    <input type="hidden" id="solving" name="solving" value= <?php echo $solving?> >
    <input type="hidden" id="gen" name="gen" value= <?php echo $gen?> >
    <input type="hidden" id="entree" name="entree" value= <?php echo $entree?> >
    <input type="hidden" id="sortie" name="sortie" value= <?php echo $sortie?> >
    <input type="hidden" id="download" name="download" value="true">
    <label for="sortie">Width:</label>
    <input type="number" id="sortie" name="width" value= <?php echo $width?> ><br>
    <label for="sortie">Height:</label>
    <input type="number" id="sortie" name="height" value= <?php echo $height?> ><br>
    <input type="submit" value="Download">

    </form>

</body>



<?php
$url = "./maze.php?longueur=$longueur&hauteur=$hauteur&gen=$gen&solving=$solving&entree=$entree&sortie=$sortie";

//echo "Longueur: $longueur, Hauteur: $hauteur, Solving: $solving, Gen: $gen, Entree: $entree, Sortie: $sortie<br>";
echo "<br><img id='maze' src='$url' width='1000' height='1000' alt='labyrinthe affiché'/>";

?>
