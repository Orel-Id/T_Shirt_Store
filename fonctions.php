<?php
define('USER', 'root');
define('PASS', 'hello');
define('NOM_DB', 'TshirtStore');
define('HOST', 'localhost');

/* Empecher les injection sql */
function sanitize_string($str) {
    if (get_magic_quotes_gpc()) {
        $sanitize = mysqli_real_escape_string(stripslashes($str));
    } else {
        $sanitize = mysqli_real_escape_string($str);
    }
    return $sanitize;
}
function MenuCatMainNav(){
    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        $sql = "SELECT Categories.Libelle FROM categories
                WHERE Categories.Date_Supression IS NULL";
        $html= "";
        if ($results = mysqli_query($connexion, $sql)) {
            while($row = mysqli_fetch_row($results)) {
                $html = $html."<li><a href='".$row[0].".php'> ".$row[0]."</a></li> ";
                $error = false;
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $html;
        }else{
            $error = true;
            $message = "Erreur requête";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    return $message;
}

function selectAdminAjout($sql, $idSelected = -1){
    if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        $html= "";
        if ($results = mysqli_query($connexion, $sql)) {
            while ($row = mysqli_fetch_row($results)) {
                if($idSelected == -1){
                    $html = $html."<option value='" . $row[0] . "'>" . $row[1] . "</option>";

                }elseif($row[0] == $idSelected){
                    $html = $html."<option value='" . $row[0] . "' selected='selected'>" . $row[1] . "</option>";
                }else{
                    $html = $html."<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                }
                $error = false;
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $html;
        } else {
            $error = true;
            $message = "Erreur requête";
        }
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }
    return $message;

}

/*********************************************************************************************************
 *   **SelectConstrutor**                                                                                *
 * Fonction permettant de retourner le code html d'un select contenant l'id ou le nom d'un élément d'une *
 *  DB comme valeur et le nom du champs comme attribut                                                   *
 *   title => titre du select                                                                            *
 *   sql => requete DB                                                                                   *
 *   idSelect => Id a attribuer au select                                                                *
 *   FunctionName => Le nom de la fonction JS au "On Change"                                             *
 *   distinct => bool afin de déterminer si c'est l'id ou le nom qui doit etre attribué et distinct      *
 *                                                                                                       *
 *********************************************************************************************************/
function SelectConstrutor($title,$sql,$idSelect,$FunctionName,$distinct){
    $selectHtml = "<select id=$idSelect onchange=$FunctionName>";
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {

        if ($results = mysqli_query($connexion, $sql)){
            if($distinct){
                $selectHtml = $selectHtml."<option value='none' selected='selected'>--".$title."--</option>";
                while ($row = mysqli_fetch_row($results)) {
                    $nomId = str_replace(" ","_",$row[0]);
                    $nomId = str_replace("&","ET",$nomId);
                    $selectHtml =  $selectHtml . "<option value='" . $nomId . "'>" . $row[0] . "</option>";
                }
            }else{
                $selectHtml = $selectHtml."<option value='0'>--Choisir ".$title."--</option>";
                while ($row = mysqli_fetch_row($results)) {
                    $selectHtml = $selectHtml . "<option value='" . $row[0] . "'>" . $row[1] . "</option>";
                }
            }

            $selectHtml = $selectHtml."</select>";
            mysqli_free_result($results);
            mysqli_close($connexion);
            return $selectHtml;
        } else {
            $error = true;
            $message = "Erreur requête";
        }
    }else {
        $error = true;
        $message = "Echec connexion DB";
    }
    return array("error"=>$error,"message"=>$message);
}

/*********************************************************************************************************
*   **show Stock**                                                                                       *
 * Fonction permettant de retourner le code html d'un tableau contenant le stock en fonction de filtres  *
 * spécifiés dans le tableau tab passé en parametre. Dans tab la clé correspond à la table sur laquelle  *
 * le filtre s'applique et la valeur l'élément à filtrer                                                 *
 *                                                                                                       *
 *     ----- ATTENTION ----- Cette fonction utilise la fonction 'SelectConstructor                       *
 *                                                                                                       *
 *********************************************************************************************************/
function showStock($tab = 'none'){
    $html = "
    <div class='MyConteneur'>
    <div class='stock'>
     <form action='Ajout_TShirt.php' method='post'>
    <TABLE class='table-responsive table table-hover table-dark myTable'>
         <thead>
            <tr>";
    /********** NOM **********/
    $sql_TShirt = "SELECT DISTINCT T_Shirt.Name FROM T_Shirt
                WHERE T_Shirt.Date_suppression IS NULL";
    $html = $html."<th>".SelectConstrutor("Nom",$sql_TShirt,"mySelectNom","filtreNom()",true)."</th>";

    /********** CATEGORIES **********/
    $sql_Categorie = "SELECT Categories.ID, Categories.Libelle FROM categories
                WHERE Categories.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Catégorie",$sql_Categorie,"mySelectCategorie","filtreCategorie()",false)."</th>";

    /********** TAILLES **********/
    $sql_Taille = "SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                WHERE Tailles.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Tailles",$sql_Taille,"mySelectTaille","filtreTaille()",false)."</th>";

    /********** GENRES **********/
    $sql_Genre = "SELECT Genre.ID, Genre.Libelle FROM Genre
                WHERE Genre.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Genre",$sql_Genre,"mySelectGenre","filtreGenre()",false)."</th>";

    /********** ARTISTES **********/
    $sql_Artiste = "SELECT Artiste.ID, Artiste.Nom FROM Artiste
                WHERE Artiste.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Artiste",$sql_Artiste,"mySelectArtiste","filtreArtiste()",false)."</th>";

    /********** COULEURS **********/
    $sql_Couleur = "SELECT Couleur.ID, Couleur.Libelle FROM Couleur
                WHERE Couleur.Date_Supression IS NULL";
    $html = $html."<th>".SelectConstrutor("Couleur",$sql_Couleur," mySelectCouleur","filtreCouleur()",false)."</th>";

    $html = $html."<th>Stock</th>
                <th><button> <img class='stockImg' src='assets/edit.svg' alt='Modifier' width='15px' height='15px'></button></th>
                <th><button> <img class='stockImg' src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></th>
            </tr>
        </thead>";

    /*********** TABLEAU DE RESULTATS ***********/

    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $sql = "SELECT T_Shirt.ID, T_Shirt.Name, Categories.Libelle, T_Shirt.NB_stock, Tailles.Libelle , Genre.Libelle,CONCAT(Artiste.Nom,' ',Artiste.Prenom),Couleur.Libelle FROM T_Shirt
                JOIN Categories ON Categories.ID = T_Shirt.ID_Catégorie
                JOIN Tailles ON Tailles.ID = T_Shirt.ID_Taille
                JOIN Genre ON Genre.ID = T_Shirt.ID_Sexe
                JOIN Artiste ON Artiste.ID = T_Shirt.ID_Artiste
                JOIN Couleur ON Couleur.ID = T_Shirt.ID_Couleur
                WHERE T_Shirt.Date_suppression IS NULL";

        /***** FILTRES *****/

        if(isset($tab['categorie']) AND $tab['categorie'] != 0){
            $sql = $sql." AND Categories.ID = ".$tab['categorie'] ." ";
        }
        if(isset($tab['taille']) AND $tab['taille'] != 0){
            $sql = $sql." AND Tailles.ID = ".$tab['taille'] ." ";
        }
        if(isset($tab['genre']) AND $tab['genre'] != 0){
            $sql = $sql." AND Genre.ID = ".$tab['genre'] ." ";
        }
        if(isset($tab['couleur']) AND $tab['couleur'] != 0){
            $sql = $sql." AND Couleur.ID = ".$tab['couleur'] ." ";
        }
        if(isset($tab['artiste']) AND $tab['artiste'] != 0){
            $sql = $sql." AND Artiste.ID = ".$tab['artiste'] ." ";
        }

        if(isset($tab['nom']) AND strlen($tab['nom']) != 0 AND $tab['nom'] != 'none'){
            $nom = str_replace("_"," ",$tab['nom']);
            $nom = "'".$nom."'";
            $nom = str_replace("ET","&",$nom);
            echo "<br>Le nom est: ".$nom;
            $sql = $sql." AND T_Shirt.Name = ".$nom ." ";
            }

            /* VARIFICATION A SUPPRIMER
                print_r($tab);
                echo "<br>La requete est: ".$sql;
            // VARIFICATION A SUPPRIMER */

            if ($results = mysqli_query($connexion, $sql)) {
                while ($row = mysqli_fetch_row($results)) {
                    $html = $html . "<tr><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] . "</td><td>" . $row[7] . "</td><td>" . $row[3] . "</td><td> <input type='radio' id='tshirtModifId".$row[0]."' name='ModifTshirt' value=".$row[0]."
            ></td><td> <input type='checkbox' id='tshirtDelId".$row[0]."' name='DeleteTshirt' value=".$row[0]."
            ></td></tr>";
                    $error = false;
                }
                mysqli_free_result($results);
                mysqli_close($connexion);
                $html = $html . "</tbody></TABLE></form></div></div>";
            } else {
                $error = true;
                $message = "Erreur requête";
            }

        }else {
            $error = true;
            $message = "Echec connexion DB";
        }
    if ($error) {
        return $message;
    } else {
        return $html;
    }
}

function filtreUrl($filtre){
    if(count($_GET) != 0){
        $location = "admin.php?";
        if(!array_key_exists ($filtre,$_GET)){
            $end_location = $filtre."=";
        }
        foreach ($_GET as $key => $val){
            if($key != $filtre){
                $location = $location.$key."=".$val."&";
            }else{
                $end_location = $filtre."=";
            }
        }
        $location = $location.$end_location;
    }else{
        $location = "admin.php?".$filtre."=";
    }

    return $location;
}

function MotifCategorie($id,$bool_save,$newName){
    if(isset($id) AND is_numeric($id) AND $id !=0 AND $bool_save){
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            $html = "<div id='motifCategorie' class='motifCategorie'><form action='admin.php' method='post'><table>";
            $sql = "SELECT Categories.ID, Categories.Libelle FROM Categories WHERE Categories.ID=".$id." " ;
            echo $sql."<br>";
            if ($results = mysqli_query($connexion, $sql)) {
                while($row = mysqli_fetch_row($results)){
                    $html = $html."<tr><td><input name='IdCatModif' type='hidden' value=".$id."></td>";
                    $html = $html."<tr><td><label for='libelleCatModif'>Nouveau Nom:</label></td>";
                    $html = $html."<td><input id='libelleCatModif' type='text' name='libelleCatModif' value='".$row[1]."'></td></tr>";
                    $html = $html."<tr><td colspan='2'><button type='submit'>Enregistrer</button></td> </tr>";
                }
            }else{
                $error = true;
                $message = "Erreur d'ajout";
            }
        }else{
            $error = true;
            $message = "Echec connexion DB";
        }
        $html = $html."</table></form></div>";
        if($error){
            return $message;
        }else{
            return $html;
        }
    }elseif (isset($id) AND is_numeric($id) AND $id !=0 AND !$bool_save) {
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            $sql = "UPDATE Categories SET
             Categories.Libelle ='" . $newName . "'
            WHERE Categories.ID=" . $id . " ";
            echo "<br>La requete de sauvegarde est: ".$sql;
            if ($results = mysqli_query($connexion, $sql)) {
                while ($row = mysqli_fetch_row($results)) {
                    $html = $html . "<tr><td><label for='libelleCatModif'>Nouveau Nom:</label></td>";
                    $html = $html . "<td><input id='libelleCatModif' type='text' name='libelleCatModif' value='" . $row[1] . "'></td></tr>";
                    $html = $html . "<tr><td colspan='2'><button type='submit'>Enregistrer</button></td> </tr>";
                }
            } else {
                $error = true;
                $message = "Erreur d'ajout";
            }
        } else {
            $error = true;
            $message = "Echec connexion DB";
        }
    }
}

/***************************************************************************************************
 * Fonctions Miniature:                                                                             *
 *  - liste_repertoire : paramétre lien vers le répertoire                                         *
 *      Parcourt le répertoire courant et tous ses sous répertoires                                *
 *  - est_image : paramétre l'image à tester                                                       *
 *    Teste si le fichier passé en paramètre correspond aux extensions de $type_ok                 *
 * - genre_miniature: paramétres = lien répertoire, lien image, lien miniature                     *
 *    Génère la miniature de l'image dans le sous-répertoire 'miniature' si elle n'existe pas déjà *
 * - ajoute_lien: paramètres = lien image, lien miniature, le fichier                              *
 *    Crée le lien et retourne le tableau                                                          *
 * - affichage : /                                                                                 *
 *    Affiche le tableau                                                                           *
 **************************************************************************************************/
error_reporting(E_ALL | E_STRICT);
$types_ok = array ('image/jpeg', 'image/gif', 'image/png');
$tabl_exclus = array ('.', '..', 'miniature');
$tabl_liens = array();
function liste_repertoire($dir) {
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            $chemin_fichier = $dir.'/'.$file;
            if (is_dir($chemin_fichier)) {
                if (!in_array($file, $GLOBALS['tabl_exclus'])) {
                    liste_repertoire($dir.'/'.$file);
                }
            } else {
                if (est_image($chemin_fichier)) {
                    $chemin_miniature = $dir.'/miniature/'.$file;
                    if (!file_exists($chemin_miniature)) {
                        genere_miniature($dir, $chemin_fichier, $chemin_miniature);
                    }
                    ajoute_lien_tab($chemin_fichier, $chemin_miniature, $file);
                }
            }
        }
        closedir($handle);
    }
    if(isset($GLOBALS['tabl_liens'])){
       return $GLOBALS['tabl_liens'];
    }
}
function est_image($chemin_fichier) {
    if (list($GLOBALS['largeur'], $GLOBALS['hauteur'], $type) = getimagesize($chemin_fichier)) {
        $type = image_type_to_mime_type($type);
        if (in_array($type, $GLOBALS['types_ok'])) {
            $ext = explode("/", $type);
            $GLOBALS['extension'] = $ext[1];
            return true;
        }
    }
    return false;
}
function genere_miniature($dir, $chemin_image, $chemin_miniature) {
    // Calcul du ratio entre la grande image et la miniature
    $taille_max = 100;
    if ($GLOBALS['largeur'] <= $GLOBALS['hauteur']) {
        $ratio = $GLOBALS['hauteur'] / $taille_max;
    } else {
        $ratio = $GLOBALS['largeur'] / $taille_max;
    }

    // Définition des dimensions de la miniature
    $larg_miniature = $GLOBALS['largeur'] / $ratio;
    $haut_miniature = $GLOBALS['hauteur'] / $ratio;

    // Crée la ressource image pour la miniature
    $destination = imagecreatetruecolor($larg_miniature, $haut_miniature);

    // Retourne un identifiant d'image jpeg, gif ou png
    $source = call_user_func('imagecreatefrom'.$GLOBALS['extension'], $chemin_image);

    // Redimensionne la grande image
    imagecopyresampled(    $destination,
        $source,
        0, 0, 0, 0,
        $larg_miniature,
        $haut_miniature,
        $GLOBALS['largeur'],
        $GLOBALS['hauteur']);

    // Si le répertoire de miniature n'existe pas, on le crée
    if (!is_dir($dir.'/miniature')) {
        mkdir ($dir.'/miniature', 0700);
    }

    // Écriture physique de l'image
    call_user_func('image'.$GLOBALS['extension'], $destination, $chemin_miniature);

    // Détruit les ressources temporaires créées
    imagedestroy($destination);
    imagedestroy($source);
}
function ajoute_lien($chemin_image, $chemin_miniature, $file) {
    // Récupère la taille de la miniature sous forme HTML (width="xxx" height="yyy")
    $taille_html_miniature = getimagesize($chemin_miniature);
    $taille_html_miniature = $taille_html_miniature[3];

    // Rajoute le lien vers l'image au tableau global $GLOBALS['tabl_liens']
    $lien = '<a href="'.$chemin_image.'">';
    $lien .= '<img src="'.$chemin_miniature.'" '.$taille_html_miniature.' alt="'.$file.' target="_blank">';
    $lien .= '</a>'."\n";

    return $lien;
    //array_push($GLOBALS['tabl_liens'], $lien);
}
function ajoute_lien_tab($chemin_image, $chemin_miniature, $file) {

    $tab = array(
        "chemin_image"  => $chemin_image,
        "chemin_miniature" => $chemin_miniature,
        "altFichier"=> $file,
    );

    // Récupère la taille de la miniature sous forme HTML (width="xxx" height="yyy")
    $taille_html_miniature = getimagesize($chemin_miniature);
    $taille_html_miniature = $taille_html_miniature[3];

    // Rajoute le lien vers l'image au tableau global $GLOBALS['tabl_liens']
    $lien = '<a href="'.$chemin_image.'">';
    $lien .= '<img src="'.$chemin_miniature.'" '.$taille_html_miniature.' alt="'.$file.'">';
    $lien .= '</a>'."\n";

    array_push($GLOBALS['tabl_liens'], $tab);
}
function upload_image($dossier,$extensions,$nom_fichier,$taille_maxi){
    //$dossier = 'upload/';
    $fichier = basename($_FILES[$nom_fichier]['name']);
   // $taille_maxi = 100000;
    $taille = filesize($_FILES[$nom_fichier]['tmp_name']);
    //$extensions = array('.png', '.gif', '.jpg', '.jpeg');
    $extension = strrchr($_FILES[$nom_fichier]['name'], '.');
//Début des vérifications de sécurité...
    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
        $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
    }
    if($taille>$taille_maxi)
    {
        $erreur = 'Le fichier est trop gros...';
    }
    if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
    {
        //On formate le nom du fichier ici...
        $fichier = strtr($fichier,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
        $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
        if(move_uploaded_file($_FILES[$nom_fichier]['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
        {
            echo 'Upload effectué avec succès !';
        }
        else //Sinon (la fonction renvoie FALSE).
        {
            echo 'Echec de l\'upload !';
        }
    }
    else
    {
        echo $erreur;
    }
}

function TShirtAddEdit($idTshirt= -1,$imgAdd){
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        if($idTshirt!=-1){
            $title = "Modifier un t-shirt";

            $sql = "SELECT T_Shirt.Name, T_Shirt.Description, T_Shirt.ID_Taille,T_Shirt.ID_Couleur,T_Shirt.ID_Sexe,T_Shirt.ID_Catégorie,T_Shirt.ID_Artiste,T_Shirt.NB_stock, T_Shirt.ID_liste_img,T_Shirt.Prix
                        FROM T_Shirt
                        WHERE T_Shirt.ID =".$idTshirt;

                if ($results = mysqli_query($connexion, $sql)) {
                    if ($row = mysqli_fetch_row($results)) {
                        $nom = $row[0];
                        $description = $row[1];
                        $IdTaille = $row[2];
                        $IdCouleur = $row[3];
                        $IdSexe = $row[4];
                        $IdCategorie = $row[5];
                        $IdArtiste = $row[6];
                        $NbStock = $row[7];
                        $listeTShirt = $row[8];
                        $prix = $row[9];
                    }
                }else{
                    $message = "TShirt non trouvé";
                    $error = true;
                }
            }else{
                $title = "Ajouter un t-shirt";
                $nom = "";
                $description = "";
                $IdTaille = -1;
                $IdCouleur = -1;
                $IdSexe = -1;
                $IdCategorie = -1;
                $IdArtiste = -1;
                $NbStock = "";
                $listeTShirt = -1;
                $prix = 0;
            }
        }else{
            $message = "Echec de la connexion";
            $error = true;
        }


                $html = '<div class="AjoutImg">
                  <h2><span>'.$title.'</span></h2>
            <div class="ajoutTShirt">
                <form action="ajout.php" method="post">
                    <table class="table-responsive table table-hover table-dark myMiddleTable">
                        <thead>
                        <tr>
                            <th colspan="4">Informations T-Shirt</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2"><label for="nom">Nom</label></td><td colspan="2"><input class="form-control" type="text" id="name" name="nom" value="'.$nom.'"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="description">Description</label></td><td colspan="2"><input class="form-control" type="text" id="description" name="description" value="'.$description.'"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="prix">Prix</label></td><td colspan="2"><input class="form-control" type="number" id="prix" name="prix" value="'.$prix.'"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="categorie">Catégorie</label></td>
                            <td colspan="2">
                                <select class="custom-select mr-sm-2" name="categorie" id="categorie-select">
                                    <option value="">--Choix Catégorie--</option>';

                $html = $html.selectAdminAjout("SELECT Categories.ID, Categories.Libelle FROM categories
                                        WHERE Categories.Date_Supression IS NULL",$IdCategorie);

                $html = $html.'</select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><label for="couleur">Couleur</label></td>
                            <td colspan="2">
                                <select class="custom-select mr-sm-2" name="couleur" id="couleur-select">
                                    <option value="">--Choix Couleur--</option>';
                $html = $html.selectAdminAjout("SELECT Couleur.ID, Couleur.Libelle FROM Couleur
                                                    WHERE Couleur.Date_Supression IS NULL",$IdCouleur);
                $html = $html.'</select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="artistes">Artiste</label></td>
                            <td colspan="2">
                                <select  class="custom-select mr-sm-2" name="artistes" id="artistes-select">
                                    <option value="">--Choix Artiste--</option>';
                $html = $html.selectAdminAjout("SELECT Artiste.ID, CONCAT(Artiste.Nom,' ',Artiste.Prenom) FROM Artiste
                                                    WHERE Artiste.Date_Supression IS NULL",$IdArtiste);
                $html = $html.' </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label for="genre">Genre</label></td>
                            <td colspan="2">
                                <select class="custom-select mr-sm-2" name="genre" id="genre-select">
                                    <option value="">--Choix Genre--</option>';
                $html = $html.selectAdminAjout("SELECT Genre.ID, Genre.Libelle FROM Genre
                                                    WHERE Genre.Date_Supression IS NULL",$IdSexe);
                $html = $html.' </select>
                            </td>
                        </tr>';

    if($idTshirt!=-1) {
        $html = $html . '<tr>
                            <th colspan="4">Taille & Quantité</th>
                        </tr>
                        <tr>
                        <td><label for="taille">Taille: </label></td>
                            <td colspan="2">
                                <select class="custom-select mr-sm-2" name="taille" id="taille-select">
                                    <option value="">--Choix Taille--</option>';
        $html = $html . selectAdminAjout("SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                                        WHERE Tailles.Date_Supression IS NULL", $IdTaille);
        $html = $html . ' </select>
                        </td>';
        $html = $html . '<td><input class="form-control" type="number" name="nbstock" id="taille" value="' . $NbStock . '"></td></tr>';


             }else{
        $sql_taille = "SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                                        WHERE Tailles.Date_Supression IS NULL";
        /**** OUVERTURE DE CONNEXION ****/
        if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
            if ($results = mysqli_query($connexion, $sql_taille)) {
                $i = 0;
                while ($row = mysqli_fetch_row($results)) {
                    if ($i % 2 == 0) {
                        echo "<tr>";
                    }
                    $html = $html ."<td><label for='" . $row[0] . "'>" . $row[1] . "</label></td>";
                    $html = $html .'<td><input class="form-control" type="number" name="tab[' . $row[0] . ']" id="taille' . $row[1] . '"></td>';
                    if ($i % 2 != 0) {
                        $html = $html ."</tr>";
                    }
                    $i++;
                }
                mysqli_free_result($results);
            } else {
                $error = true;
                $message = "Erreur requête";
            }
        }else {
            $error = true;
            $message = "Erreur connexion";
        }
        mysqli_free_result($results);
        mysqli_close($connexion);
    }
    if($imgAdd){
        $html = $html .'<div class="imgAdd"><input type="hidden" name="img" value="true"></div>';
    }
    $html = $html .'<tr>
            <th colspan="4"><label for="SaveT-shirt"><input class="btn btn-secondary" type="submit" id="SaveT-shirt" value="Enregistrer"></th>
        </tr>
        </tbody>
            </table>
               </form>
                  </div>
                    </div>';

        return array(
            "HTML" => $html,
            "IdListImage" => $listeTShirt,
            "ERROR" => $error,
            "Message" => $message);
    }

function AddImg($idTShirt=-1){
    $html = ' <h2><span>Ajouter images</span></h2>
        <form method="POST" action="ajout.php" enctype="multipart/form-data" class="">
            <div class="add">
                <table  class="addTab">
                    <tr>
                        <td><label for="tshirtimageModifAdd">Choose file...</label></td>
                        <td> <input type="file" id="tshirtimageModifAdd" name="tshirt"><input  type="hidden" name="MAX_FILE_SIZE" value="100000"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input class="btn btn-secondary" type="submit" name="envoyer" value="Envoyer le fichier"></td>
                    </tr>
                </table>';
    if($idTShirt!=-1){
        $html = $html.'<input type="hidden" name="idModif" value=' .$idTShirt . '>';
    }
    $html = $html.' </div>
        </form>';
    return $html;
}
function AffichageImgtempo($tshirtListeMin)
{
    $html = "<h2><span>Images Ajoutées</span></h2>";
    $session_data = unserialize($tshirtListeMin);
    $html = $html . "<table >";
    $i = 0;
    foreach ($session_data as $val_lien) {
        if ($i % 4 == 0) {
            echo "<tr>";
        }
        $lien = ajoute_lien($val_lien["chemin_image"], $val_lien["chemin_miniature"], $val_lien["altFichier"]);
        $html = $html . "<td>" . $lien . "<td>";
        $i++;
        if ($i % 4 == 0) {
            echo "</tr>";
        }
    }
    $html = $html . '</table>';

    return $html;
}
function AffichageImg($idList){
    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
        $sql = "SELECT Image.lien_image,Image.lien_miniature,Image.AltImage FROM Image
                WHERE Image.ID_Liste_Image =".$idList;
        $tab = [];
        if ($results = mysqli_query($connexion, $sql)) {
           $html = "<table><tr>";
            while ($row = mysqli_fetch_row($results)) {
                $html = $html."<td>".ajoute_lien($row[0],$row[1],$row[2])."</td>";
                $error = false;
            }
            mysqli_free_result($results);
            mysqli_close($connexion);
            $html = $html . "</tr></table>";
        } else {
            $error = true;
            $message = "Erreur requête";
        }

    }else {
        $error = true;
        $message = "Echec connexion DB";
    }

    if(!$error){
        return $html;
    }else{
        return $message;
    }
}

    ?>