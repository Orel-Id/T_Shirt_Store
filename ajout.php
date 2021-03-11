
<?php
require "fonctions.php";



if(isset($_POST["nom"]) AND $_POST["nom"] != "" AND strlen($_POST["nom"]) <100){
    $nom = $_POST["nom"];
}else{
    $error = true;
    $message = "Le nom est trop long ou vide";
}

if(isset($_POST["description"]) AND $_POST["description"] != "" ){

    $description = $_POST["description"];
    $description = str_replace("'","''",$description);
}else{
    $error = true;
    $message = "Description vide";
}

if(isset($_POST["categorie"]) AND is_numeric($_POST["categorie"])){
    $idCategorie = $_POST["categorie"];
}else{
    $error = true;
    $message = "La Catégorie est incorrecte";
}

if(isset($_POST["couleur"]) AND is_numeric($_POST["couleur"])){
    $idCouleur = $_POST["couleur"];
}else{
    $error = true;
    $message = "La Couleur est incorrecte";
}

if(isset($_POST["artistes"]) AND is_numeric($_POST["artistes"])){
    $idArtiste = $_POST["artistes"];
}else{
    $error = true;
    $message = "L'artiste' est incorrecte";
}
if(isset($_POST["genre"]) AND is_numeric($_POST["genre"])){
    $idGenre = $_POST["genre"];
}else{
    $error = true;
    $message = "Le genre est incorrecte";
}
if(!$error){

        foreach ($_POST["tab"] as $key => $val) {
            $sql = "INSERT INTO T_Shirt SET
            Name = '".$nom."',
            Description = '".$description."',
            Date_creation = Now(),
            ID_Catégorie =".$idCategorie.",
            ID_Couleur = ".$idCouleur.",
            ID_Artiste = ".$idArtiste.",
            ID_Sexe = ".$idGenre.",
           ";
                $idTaille = $key;
                $nbStock = $val;
                $sql = $sql." NB_stock=".$val;
                $sql = $sql.", ID_Taille=".$key;
                echo "<br><br>La requete est: ".$sql;

                if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
                    if ($results = mysqli_query($connexion, $sql)) {
                        $idTshirtAdd[] = mysqli_insert_id($connexion);
                    }else {
                        $error = true;
                        $message = "Erreur requête";
                    }
                }else{
                    $error = true;
                    $message = "Echec connexion DB";
                }
            }
        echo "<br>".$message;
        print_r($idTshirtAdd);
}

//--> Ajouter prix dans formulaire et DB
print_r($_POST);
echo "<br>";
print_r($_FILES);
if(isset($_FILES["tshirt"])){
    upload_image('assets/upload/',array('.png', '.gif', '.jpg', '.jpeg'),"tshirt",100000);
}

?>