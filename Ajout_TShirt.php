<?php
require ('header_admin.php');
require ('fonctions.php');

if(isset($_GET["message"])){
    if($_GET["error"]){
        echo "<p class='error'><span class='message'>Status: </span>".$_GET["message"]."</p>";
    }else{
        echo "<p class='notError'><span class='message'>Status: </span>".$_GET["message"]."</p>";
    }
}
    if((isset($_POST["ModifTshirt"]) AND is_numeric($_POST["ModifTshirt"])) OR (isset($_GET["ModifTshirt"]) AND is_numeric($_GET["ModifTshirt"]))){

        if(isset($_POST["ModifTshirt"])) {
            $htmlResult = TShirtAddEdit($_POST["ModifTshirt"],isset($_GET["tshirtListeMin"]));
            $idTshirt = $_POST["ModifTshirt"];
        }elseif (isset($_GET["ModifTshirt"])){
            $htmlResult = TShirtAddEdit($_GET["ModifTshirt"],isset($_GET["tshirtListeMin"]));
            $idTshirt = $_GET["ModifTshirt"];
        }

        /* Affiche le formulaire d'ajout ou de modification */
        if(!$htmlResult["ERROR"]) {
            echo $htmlResult["HTML"];
            $idTshirtList = $htmlResult["IdListImage"];
        }else {
            echo $htmlResult["Message"];
        }

        /* Affiche un tableau avec les images avant de les ajouter à la DB */
        if(isset($_GET["tshirtListeMin"])){
            echo AffichageImgtempo($_GET["tshirtListeMin"]);
        }

        echo "idModif= ".$idTshirt;
        /* Affiche le formulaire d'ajout d'image */
            echo AddImg($idTshirt);
        ?>

        <?php
}else{

        $htmlResult = TShirtAddEdit(-1,isset($_GET["tshirtListeMin"]));
        if(!$htmlResult["ERROR"]) {
            echo $htmlResult["HTML"];
            $idTshirtList = $htmlResult["IdListImage"];
        }else {
            echo $htmlResult["Message"];
        }

        ?>


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

    echo  AddImg();
    ?>

    </main>
    </body>
    </html>

    <?php
}
?>
