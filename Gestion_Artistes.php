<?php
require ('header_admin.php');
require ('fonctions.php');

/**** OUVERTURE DE CONNEXION ****/
if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
    if(isset($_GET["message"])){
        if($_GET["error"]){
            echo "<p class='error'><span class='message'>Status: </span>".$_GET["message"]."</p>";
        }else{
            echo "<p class='notError'><span class='message'>Status: </span>".$_GET["message"]."</p>";
        }
    }
    ?>


    <h2><span>Gestion des Artistes</span></h2>
    <div id="Artistes" class="artistes">
    <form action="Edit_Options.php" method="post">
        <TABLE class="table-responsive table table-hover table-dark mySmallTable">
            <thead>
            <th>Id</th>
            <th>Artiste</th>
            <th>Stock</th>
            <th colspan="2">Actions</th>
            </thead>
            <tbody>
            <?php
            $sql ="SELECT Artiste.ID,CONCAT(Artiste.Prenom,' ',Artiste.Nom), SUM(T_Shirt.NB_stock) 
                    FROM Artiste
                    LEFT JOIN T_Shirt ON T_Shirt.ID_Artiste = Artiste.ID
                    WHERE Artiste.Date_Supression IS null 
                    GROUP BY Artiste.ID,Artiste.Prenom,Artiste.Nom";
            if ($results = mysqli_query($connexion, $sql)){
                while ($row = mysqli_fetch_row($results)) {
                    if($row[2] == null){
                        $id=0;
                        $nb=0;
                    }else{
                        $id=$row[0];
                        $nb=$row[2];
                    }
                    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[0]." name='idModifArtiste'><img class='stockImg' src='assets/edit.svg' alt='Modifier' width='15px' height='15px'></button></td>";
                    if($row[2]==0){
                        echo "<td><button type='submit' value=".$row[0]." name='idDeleteArtiste' ><img class='stockImg' src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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
    <h2><span>Ajouter un(e) Artiste</span></h2>
    <div class="add">
        <form action="Edit_Options.php" method="post">
            <table class="addTab">
                <tr>
                    <td><label for="NewArtistePrenom">Prénom: </label></td>
                    <td><input type="text" id="NewArtistePrenom" name="NewArtistePrenom" placeholder="Prenom Artiste..."/></td>
                </tr>
                <tr>
                    <td><label for="NewArtisteNom">Nom: </label></td>
                    <td><input type="text" id="NewArtisteNom" name="NewArtisteNom" placeholder="Nom Artiste..."/></td>
                </tr>
                <tr>
                    <td><label for="NewArtisteDescription">En quelques mots: </label></td>
                    <td><input type="textarea" id="NewArtisteDescription" rows="6" cols="50" name="NewArtisteDescription" ></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><button class="btn btn-secondary" type="submit">Enregistrer</button></td>
                </tr>
            </table>
        </form>
    </div>

<?php
}
?>