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

/**** OUVERTURE DE CONNEXION ****/
if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
    ?>


    <h2><span>Gestion des Couleurs</span></h2>
    <div id="Couleurs" class="couleurs">
    <form action="Edit_Options.php" method="post">
        <TABLE class="table-responsive table table-hover table-dark mySmallTable">
            <thead>
            <th>Id</th>
            <th>Couleur</th>
            <th>Stock</th>
            <th colspan="2">Actions</th>
            </thead>
            <tbody>
            <?php
            $sql ="SELECT Couleur.ID,Couleur.Libelle, SUM(T_Shirt.NB_stock) FROM Couleur
                    LEFT JOIN T_Shirt ON T_Shirt.ID_Couleur = Couleur.ID
                    WHERE Couleur.Date_Supression IS null
                    GROUP BY Couleur.ID,Couleur.Libelle";
            if ($results = mysqli_query($connexion, $sql)){
                while ($row = mysqli_fetch_row($results)) {
                    if($row[0]==null AND $row[2] == null){
                        $id=0;
                        $nb=0;
                    }else{
                        $id=$row[0];
                        $nb=$row[2];
                    }
                    echo '<form action="Gestion_Couleurs.php" method="post">';
                    echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[0]." name='idModifCouleur'><img class='stockImg' src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                    echo '</form>';
                    if($row[2]==0){
                        echo "<td><button type='submit' value=".$row[0]." name='idSuppCouleur' ><img class='stockImg' src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                    }else{
                        echo "<td><button type='submit' value=".$row[0]." disabled><img class='stockImg' src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                    }
                }
                mysqli_free_result($results);
            } else {
                $error = true;
                $message = "Erreur requête";
            }
            ?>
            </tbody>
        </TABLE>

    </form>
    </div>
<?php
     if(isset($_POST["idModifCouleur"]) AND is_numeric($_POST["idModifCouleur"])){
        $html = ' <h2><span>Modifier une couleur</span></h2>
        <div class="add">
              <form action="Edit_Options.php" method="post">
                  <table class="addTab">
                      <tr>
                          <td><input type="hidden" name="idToEdit" value='.$_POST["idModifCouleur"].'></td>
                          <td><label for="ModifCouleur">Nouvelle Couleur: </label></td>
                          <td><input type="text" id="ModifCouleur" name="ModifCouleur" value="';
        $sql = "SELECT Couleur.Libelle FROM Couleur
            WHERE Couleur.ID = ".$_POST["idModifCouleur"].";";
            if ($results = mysqli_query($connexion, $sql)) {
                if($row = mysqli_fetch_row($results)){
                    $html = $html.$row[0];
                }
                $html = $html.'""/></td>
                      </tr>
                      <tr>
                          <td colspan="2"><button type="submit" class="btn btn-secondary">Enregistrer</button></td>
                      </tr>
                  </table>
              </form>
        </div>';
                echo $html;
                echo $message;
                mysqli_free_result($results);
                mysqli_close($connexion);
                $error = false;
            }else{
                $error = true;
                $message = "Erreur de sélection";
            }
    }else{
    ?>
         <h2><span>Ajouter une couleur</span></h2>
         <div class="add">
             <form action="Edit_Options.php" method="post">
                 <table class="addTab">
                     <tr>
                         <td><label for="NewCouleur">Nouvelle Couleur:</label></td>
                         <td> <input type="text" id="NewCouleur" name="NewCouleur" placeholder="Nom couleur..."/></td>
                     </tr>
                     <tr>
                         <td colspan="2"><button type="submit" class="btn btn-secondary">Enregistrer</button></td>
                     </tr>
                 </table>
             </form>
         </div>
    <?php
     }
}
?>