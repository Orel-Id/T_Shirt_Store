<?php
require "header.php";
?>

<body>
    <nav class="adminNav">
        <ul class="flex space-between">
            <li id="Bstock">Stock</li>
            <li id="Bcategorie">Catégories</li>
            <li id="Bcommandes">Commandes</li>
        </ul>
    </nav>

    <div id="Stock" class="stock">
        <?php
            if($connexion = mysqli_connect(HOST,USER,PASS,NOM_DB)) {
        ?>
        Stock
        <div id="Ajout" class="Ajout">

            <form action="ajout.php" method="post">
                <table>
                    <thead>
                    <tr>
                        <th colspan="4">Ajouter un t-shirt</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2"><label for="description">Nom</label></td><td colspan="2"><input type="text" id="name" name="nom"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label for="description">Description</label></td><td colspan="2"><input type="text" id="description" name="description"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label for="categorie">Catégorie</label></td>
                        <td colspan="2">
                            <select name="categorie" id="categorie-select">
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
                            <select name="couleur" id="couleur-select">
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
                            <select name="artistes" id="artistes-select">
                                <option value="">--Choix Artiste--</option>
                                <?php
                                echo selectAdminAjout("SELECT Artiste.ID, CONCAT(Artiste.Nom,' ',Artiste.Preom) FROM Artiste
                                                WHERE Artiste.Date_Supression IS NULL");
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><label for="genre">Genre</label></td>
                        <td colspan="2">
                            <select name="genre" id="genre-select">
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
                                echo '<td><input type="number" name="tab['.$row[0].']" id="taille'.$row[1].'"></td>';
                                if($i%2 != 0){echo "</tr>";}
                                $i++;
                            }
                            mysqli_free_result($results);
                        } else {
                            $error = true;
                            $message = "Erreur requête";
                        }
                    ?>
                    <tr>
                        <th colspan="4"><input type="submit" value="Enregistrer"></th>
                    </tr>
                    </tbody>
                </table>

            </form>
        </div>
        <div id="VoirStock" class="StockView">
            <?php
            if(isset($_GET["categorie"]) AND isset($_GET["taille"]) AND isset($_GET["genre"])){
                $tab['categorie'] = $_GET["categorie"];
                $tab['taille'] = $_GET["taille"];
                $tab['genre'] = $_GET["genre"];
                echo voirStock($tab);
            }elseif(isset($_GET["categorie"]) AND isset($_GET["taille"])){
                $tab['categorie'] = $_GET["categorie"];
                $tab['taille'] = $_GET["taille"];
                $tab['genre'] = "none";
                echo voirStock($tab);
            }elseif(isset($_GET["genre"]) AND isset($_GET["taille"])){
                $tab['categorie'] = "none";
                $tab['taille'] = $_GET["taille"];
                $tab['genre'] = $_GET["genre"];
                echo voirStock($tab);
            }elseif(isset($_GET["genre"]) AND isset($_GET["categorie"])){
                $tab['categorie'] = $_GET["categorie"];
                $tab['taille'] = "none";
                $tab['genre'] = $_GET["genre"];
                echo voirStock($tab);
            }elseif (isset($_GET["taille"])){
                $tab['categorie'] = "none";
                $tab['taille'] = $_GET["taille"];
                echo voirStock($tab);
            }elseif (isset($_GET["categorie"])){
                $tab['categorie'] = $_GET["categorie"];
                $tab['taille'] = "none";
                $tab['genre'] = "none";
                echo voirStock($tab);
            }elseif (isset($_GET["genre"])){
                $tab['genre'] = $_GET["genre"];
                $tab['taille'] = "none";
                $tab['categorie'] = "none";
                echo voirStock($tab);
            }else{
                echo voirStock();
            }

            ?>
        </div>
    </div>
    <div id="Categories" class="categories">
        Catégories
    </div>

    <div id="Commandes" class="commandes">
        Commandes
    </div>
    <?php
    }else{
        $error = true;
        $message = "Echec connexion DB";
    }

    echo "<br>Message: " . $message;

    mysqli_close($connexion);
    ?>

    <p id="selection"><p>

    <script type="text/javascript">
      /*  document.getElementById("Stock").style.display = "none";*/
      document.getElementById("Stock").style.display = "block";
      document.getElementById("Categories").style.display = "none";
      document.getElementById("Commandes").style.display = "none";


        document.getElementById("Bstock").addEventListener('click',function(){
            document.getElementById("Stock").style.display = "block";
            document.getElementById("Categories").style.display = "none";
            document.getElementById("Commandes").style.display = "none";
        })
        document.getElementById("Bcategorie").addEventListener('click',function(){
            document.getElementById("Stock").style.display = "none";
            document.getElementById("Categories").style.display = "block";
            document.getElementById("Commandes").style.display = "none";
        })
        document.getElementById("Bcommandes").addEventListener('click',function(){
            document.getElementById("Stock").style.display = "none";
            document.getElementById("Categories").style.display = "none";
            document.getElementById("Commandes").style.display = "block";
        })
        function filtreCategorie() {
            var x = document.getElementById("mySelect").value;
            document.getElementById("selection").innerHTML = "You selected: " + x;
            <?php
            if(isset($_GET["taille"]) AND isset($_GET["genre"])){
                $location = "admin.php?taille=".$_GET["taille"]."&genre=".$_GET["genre"]."&categorie=";
            }elseif(isset($_GET["genre"])){
                $location = "admin.php?genre=".$_GET["genre"]."&categorie=";
            }elseif (isset($_GET["taille"])){
                $location = "admin.php?taille=".$_GET["taille"]."&categorie=";
            }else{
                $location = "admin.php?categorie=";
            }

            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;
        }
        function filtreTaille() {
            var x = document.getElementById("mySelectTaille").value;
            <?php
            if(isset($_GET["categorie"]) AND isset($_GET["genre"])){
                $location = "admin.php?categorie=".$_GET["categorie"]."&genre=".$_GET["genre"]."&taille=";
            }elseif(isset($_GET["categorie"])){
                $location = "admin.php?categorie=".$_GET["categorie"]."&genre=";
            }elseif (isset($_GET["taille"])){
                $location = "admin.php?taille=".$_GET["taille"]."&genre=";
            }else{
                $location = "admin.php?taille=";
            }

            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;
        }
        function filtreGenre(){
            var x = document.getElementById("mySelectGenre").value;
            <?php
            if(isset($_GET["categorie"]) AND isset($_GET["taille"])){
                $location = "admin.php?categorie=".$_GET["categorie"]."&taille=".$_GET["taille"]."&genre=";
            }elseif(isset($_GET["categorie"])){
                     $location = "admin.php?categorie=".$_GET["categorie"]."&genre=";
                }elseif (isset($_GET["taille"])){
                     $location = "admin.php?taille=".$_GET["taille"]."&genre=";
                }else{
                    $location = "admin.php?genre=";
                }
            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;
        }


    </script>

</body>

