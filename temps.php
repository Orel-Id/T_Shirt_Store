


    /***** ICICICICICICIC **********/
<?php
require ('header_admin.php');
require ('fonctions.php');

/**** OUVERTURE DE CONNEXION ****/
if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
print_r($_POST);
if((isset($_POST["ModifTshirt"]) AND is_numeric($_POST["ModifTshirt"])) OR (isset($_GET["ModifTshirt"]) AND is_numeric($_GET["ModifTshirt"]))){
/*$sql = "SELECT Image.lien_image, Image.lien_miniature, T_Shirt.Name, T_Shirt.ID_Taille,T_Shirt.ID_Couleur,T_Shirt.ID_Sexe,T_Shirt.ID_Catégorie,T_Shirt.ID_Artiste FROM liste_images
          JOIN T_Shirt ON T_Shirt.ID_liste_img = liste_images.ID
          JOIN Image ON Image.ID_Liste_Image = liste_images.ID
          WHERE T_Shirt.ID =".$_POST["ModifTshirt"];*/
if(isset($_POST["ModifTshirt"])) {
$idTshirt = $_POST["ModifTshirt"];
}elseif (isset($_GET["ModifTshirt"])){
$idTshirt = $_GET["ModifTshirt"];
}

$sql = "SELECT T_Shirt.Name, T_Shirt.Description, T_Shirt.ID_Taille,T_Shirt.ID_Couleur,T_Shirt.ID_Sexe,T_Shirt.ID_Catégorie,T_Shirt.ID_Artiste,T_Shirt.NB_stock, T_Shirt.ID_liste_img
                FROM T_Shirt
                WHERE T_Shirt.ID =".$idTshirt;
if ($results = mysqli_query($connexion, $sql)){
if($row = mysqli_fetch_row($results)) {
print_r($row);
?>
    <h2><span>Modifier un t-shirt</span></h2>
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
                <td colspan="2"><label for="nom">Nom</label></td><td colspan="2"><input class="form-control" type="text" id="name" name="nom" value="<?php echo $row[0]; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><label for="description">Description</label></td><td colspan="2"><input class="form-control" type="text" id="description" name="description" value="<?php echo $row[1]; ?>"></td>
            </tr>
            <tr>
                <td colspan="2"><label for="categorie">Catégorie</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="categorie" id="categorie-select">
                        <option value="">--Choix Catégorie--</option>
                        <?php
                        echo selectAdminAjout("SELECT Categories.ID, Categories.Libelle FROM categories
                                        WHERE Categories.Date_Supression IS NULL",$row[5]);
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2"><label for="couleur">Couleur</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="couleur" id="couleur-select">
                        <option value="">--Choix Couleur--</option>
                        <?php
                        echo selectAdminAjout("SELECT Couleur.ID, Couleur.Libelle FROM Couleur
                                                    WHERE Couleur.Date_Supression IS NULL",$row[3]);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><label for="artistes">Artiste</label></td>
                <td colspan="2">
                    <select  class="custom-select mr-sm-2" name="artistes" id="artistes-select">
                        <option value="">--Choix Artiste--</option>
                        <?php
                        echo selectAdminAjout("SELECT Artiste.ID, CONCAT(Artiste.Nom,' ',Artiste.Prenom) FROM Artiste
                                                    WHERE Artiste.Date_Supression IS NULL",$row[6]);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><label for="genre">Genre</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="genre" id="genre-select">
                        <option value="">--Choix Genre--</option>
                        <?php
                        echo selectAdminAjout("SELECT Genre.ID, Genre.Libelle FROM Genre
                                                    WHERE Genre.Date_Supression IS NULL",$row[4]);
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="4">Taille & Quantité</th>
            </tr>
            <tr>
                <td><label for='taille'>Taille: </label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="taille" id="taille-select">
                        <option value="">--Choix Taille--</option>
                        <?php
                        echo selectAdminAjout("SELECT Tailles.ID, Tailles.Libelle FROM Tailles
                                        WHERE Tailles.Date_Supression IS NULL", $row[2]);
                        ?>
                    </select>
                </td>
                <?php

                echo '<td><input class="form-control" type="number" name="tab['.$row[0].']" id="taille" value="'.$row[7].'"></td>';
                ?>
            </tr>


            <tr>
                <th colspan="4"><label for="SaveT-shirt"><input class="btn btn-secondary" type="submit" id="SaveT-shirt" value="Enregistrer"></th>
            </tr>
            </tbody>
        </table>

        <?php
        $sql_img = " SELECT Image.lien_image, Image.lien_miniature, T_Shirt.Name FROM liste_images
                JOIN T_Shirt ON T_Shirt.ID_liste_img = liste_images.ID
                JOIN Image ON Image.ID_Liste_Image = liste_images.ID
                WHERE T_Shirt.ID =".$idTshirt;
        echo "<table >";
        $i=0;
        if ($results_img = mysqli_query($connexion, $sql_img)) {
        while($row_img = mysqli_fetch_row($results_img)) {
        if($i%4 == 0){echo "<tr>";}
        $lien = ajoute_lien($row_img[0],$row_img[1],$row_img[2]);
        echo "<td>".$lien."<td>";
        $i++;
        if($i%4 == 0){echo "</tr>";}
        }
        }
        mysqli_free_result($results_img);
        echo '</table>';
        }
        mysqli_free_result($results);
        echo '<input type="hidden" name="idModif" value="' .$idTshirt . '">';
        if($i!=0){

        if (isset($_GET["tshirtListeMin"])) {
        echo '<div class="imgAdd"><input type="hidden" name="imgListe" value="' .$row[8]. '"></div>';
        }

        }
        ?>
    </form>
</div>

<?php
if(isset($_GET["tshirtListeMin"])){
echo "<h2><span>Images Ajoutées</span></h2>";
$session_data = unserialize($_GET["tshirtListeMin"]);
echo "<table >";
$i=0;
foreach ($session_data as $val_lien) {
if($i%4 == 0){echo "<tr>";}
$lien = ajoute_lien($val_lien["chemin_image"], $val_lien["chemin_miniature"], $val_lien["altFichier"]);
echo "<td>".$lien."<td>";
$i++;
if($i%4 == 0){echo "</tr>";}
}
echo '</table>';
}
?>
    <h2><span>Ajouter images</span></h2>
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
        </table>
    </div>
    <?php
    echo '<input type="hidden" name="idModif" value="' .$idTshirt . '">';
    ?>
</form>


    </div>

    </main>
    </body>
    </html>
<?php
}else{
echo "Erreur de requete";
}
}else{
?>

    <h2><span>Ajouter un t-shirt</span></h2>
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
                <td colspan="2"><label for="nom">Nom</label></td><td colspan="2"><input class="form-control" type="text" id="name" name="nom"></td>
            </tr>
            <tr>
                <td colspan="2"><label for="description">Description</label></td><td colspan="2"><input class="form-control" type="text" id="description" name="description"></td>
            </tr>
            <tr>
                <td colspan="2"><label for="categorie">Catégorie</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="categorie" id="categorie-select">
                        <option value="">--Choix Catégorie--</option>
                        <?php
                        echo selectAdminAjout("SELECT Categories.ID, Categories.Libelle FROM categories
                                        WHERE Categories.Date_Supression IS NULL");
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2"><label for="couleur">Couleur</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="couleur" id="couleur-select">
                        <option value="">--Choix Couleur--</option>
                        <?php
                        echo selectAdminAjout("SELECT Couleur.ID, Couleur.Libelle FROM Couleur
                                                    WHERE Couleur.Date_Supression IS NULL");
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><label for="artistes">Artiste</label></td>
                <td colspan="2">
                    <select  class="custom-select mr-sm-2" name="artistes" id="artistes-select">
                        <option value="">--Choix Artiste--</option>
                        <?php
                        echo selectAdminAjout("SELECT Artiste.ID, CONCAT(Artiste.Nom,' ',Artiste.Prenom) FROM Artiste
                                                    WHERE Artiste.Date_Supression IS NULL");
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><label for="genre">Genre</label></td>
                <td colspan="2">
                    <select class="custom-select mr-sm-2" name="genre" id="genre-select">
                        <option value="">--Choix Genre--</option>
                        <?php
                        echo selectAdminAjout("SELECT Genre.ID, Genre.Libelle FROM Genre
                                                    WHERE Genre.Date_Supression IS NULL");
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th colspan="4">Taille & Quantité</th>
            </tr>
            <?php
            $sql_taille = "SELECT Tailles.ID, Tailles.Libelle FROM Tailles 
                                        WHERE Tailles.Date_Supression IS NULL";
            if ($results = mysqli_query($connexion, $sql_taille)){
            $i = 0;
            while ($row = mysqli_fetch_row($results)) {
            if($i%2 == 0){echo "<tr>";}
            echo "<td><label for='".$row[0]."'>".$row[1]."</label></td>";
            echo '<td><input class="form-control" type="number" name="tab['.$row[0].']" id="taille'.$row[1].'"></td>';
            if($i%2 != 0){echo "</tr>";}
            $i++;
            }
            mysqli_free_result($results);
            } else {
            $error = true;
            $message = "Erreur requête";
            }
            if(isset($_GET["tshirtListeMin"])) {
            echo '<div class="imgAdd"><input type="hidden" name="img" value="true"></div>';
            }
            ?>
            <tr>
                <th colspan="4"><label for="SaveT-shirt"><input class="btn btn-secondary" type="submit" id="SaveT-shirt" value="Enregistrer"></th>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<?php
if(isset($_GET["tshirtListeMin"])){
$session_data = unserialize($_GET["tshirtListeMin"]);
echo "<table >";
$i=0;
foreach ($session_data as $val_lien) {
if($i%4 == 0){echo "<tr>";}
$lien = ajoute_lien($val_lien["chemin_image"], $val_lien["chemin_miniature"], $val_lien["altFichier"]);
echo "<td>".$lien."<td>";
$i++;
if($i%4 == 0){echo "</tr>";}
}
echo '</table>';
}
?>
    <h2><span>Ajouter images</span></h2>
    <form method="POST" action="ajout.php" enctype="multipart/form-data" class="">
        <div class="add">
            <table  class="addTab">
                <tr>
                    <td><label for="tshirtimageAdd">Choose file...</label></td>
                    <td> <input type="file" id="tshirtimageAdd" name="tshirt"><input  type="hidden" name="MAX_FILE_SIZE" value="100000"></td>
                </tr>
                <tr>
                    <td colspan="2"><input class="btn btn-secondary" type="submit" name="envoyer" value="Envoyer le fichier"></td>
                </tr>
            </table>
        </div>
    </form>


    </div>

    </main>
    </body>
    </html>


<?php
}
}
?>
