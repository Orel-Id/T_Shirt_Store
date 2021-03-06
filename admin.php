
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Super titre</title>
    <meta charset="UTF-8"/>
    <!--<link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;900&display=swap" >
    -->
    <link rel="stylesheet" href="css/reset.css" />
    <link type="text/css" rel="stylesheet" href="css/admin.css" />
    <?php
    require ('fonctions.php');


    if ($connexion = mysqli_connect(HOST, USER, PASS, NOM_DB)) {
    ?>
</head>
<body>
    <aside class="adminMenu">
        <div class="flex f-space-between">
            <img class="profil" src="assets/user.svg" alt="Profil">
            <p class="log">Admin</p>
            <img class="profil" src="assets/logout.svg" alt="Log Out">
        </div>
            <nav>
            <ul class="adminNav"">
                <li id="Bstock">Stock</li>
                    <ul class="gestionStock">
                        <li id="Bajout" class="options">Ajouter un t-shirt</li>
                        <li id="BCategorieEdit" class="options">Gérez les catégories</li>
                        <li id="BCouleurEdit" class="options">Gérez les Couleurs</li>
                        <li id="BGenreEdit" class="options">Gérez les Genres</li>
                        <li id="BTailleEdit" class="options">Gérez les Tailles</li>
                        <li id="BArtistEdit" class="options">Gérez les Artistes</li>
                    </ul>
                <li id="Bcommandes">Commandes</li>
            </ul>
        </nav>
    </aside>
    <div id="Stock" class="stock">
        <h2><span>Stock</span></h2>
            <?php echo showStock($_GET); ?>
    </div>
    <div id="Ajout" class="Ajout">
        <h2><span>Ajouter un t-shirt</span></h2>
        <form action="ajout.php" method="post" enctype="multipart/form-data">>
            <table class="TabAjout">
                <thead>
                <tr>
                    <th colspan="4">Nouveau T-Shirt</th>
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
                            echo selectAdminAjout("SELECT Artiste.ID, CONCAT(Artiste.Nom,' ',Artiste.Prenom) FROM Artiste
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
                    <td colspan="2"><input type="file" name="avatar" accept=".png,.jpg,.jpeg"></td>
                </tr>
               
                <tr>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000">
                </tr>
                <tr>
                    <th colspan="4"><label for="SaveT-shirt"><input class="enregistrer" type="submit" id="SaveT-shirt" value="Enregistrer"></th>
                </tr>
                </tbody>
            </table>

        </form>
    </div>
    <div id="Categories" class="categories">
        <h2><span>Gestion des catégories</span></h2>
        <form action="admin.php" method="post">
            <TABLE>
                <thead>
                    <th>Id</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th colspan="2">Actions</th>
                </thead>
                <tbody>
                <?php
                $sql ="SELECT T_Shirt.ID_Catégorie,Categories.Libelle, SUM(T_Shirt.NB_stock),Categories.ID FROM Categories
                        LEFT JOIN T_Shirt ON T_Shirt.ID_Catégorie = Categories.ID
                        GROUP BY T_Shirt.ID_Catégorie,Categories.Libelle,Categories.ID";
                if ($results = mysqli_query($connexion, $sql)){
                        while ($row = mysqli_fetch_row($results)) {
                            if($row[0]==null AND $row[2] == null){
                                $id=0;
                                $nb=0;
                            }else{
                                $id=$row[0];
                                $nb=$row[2];
                            }
                            echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[3]." name='idModifCategorie'><img src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                            if($row[2]==0){
                                echo "<td><button type='submit' value=".$row[3]." name='idSuppCategorie' ><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                            }else{
                                echo "<td><button type='submit' value=".$row[3]." disabled><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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
        <?php
        print_r($_POST);
            if(isset($_POST["idModifCategorie"])){
                echo MotifCategorie($_POST["idModifCategorie"],true,"nope");
            }elseif(isset($_POST["libelleCatModif"]) AND isset($_POST["IdCatModif"])){
                echo MotifCategorie($_POST["IdCatModif"],false,$_POST["libelleCatModif"]);
            }
        ?>
        <h2><span>Ajouter une catégorie</span></h2>
        <div class="AjoutCat">
            <form action="gestionCat.php" method="post"  target="popup"
                  onSubmit="window.open('gestionCat.php','popup','width=600,height=300,menubar=no,scrollbars=no')">
                <label for="NewCat">Nouvelle Catégorie: </label>
                <input type="text" id="NewCat" name="NewCat" placeholder="Nom Catégorie..."/>
                <button type="submit" class="enregistrer">Enregistrer</button>
            </form>
        </div>

    </div>
    <div id="Genres" class="genre">
        <h2><span>Gestion des Genres</span></h2>
        <form action="gestionGenre.php" method="post">
            <TABLE>
                <thead>
                <th>Id</th>
                <th>Genre</th>
                <th>Stock</th>
                <th colspan="2">Actions</th>
                </thead>
                <tbody>
                <?php
                $sql ="SELECT T_Shirt.ID_Sexe,Genre.Libelle, SUM(T_Shirt.NB_stock),Genre.ID FROM Genre
                    LEFT JOIN T_Shirt ON T_Shirt.ID_Sexe = Genre.ID
                    GROUP BY T_Shirt.ID_Sexe,Genre.Libelle,Genre.ID";
                if ($results = mysqli_query($connexion, $sql)){
                    while ($row = mysqli_fetch_row($results)) {
                        if($row[0]==null AND $row[2] == null){
                            $id=0;
                            $nb=0;
                        }else{
                            $id=$row[0];
                            $nb=$row[2];
                        }
                        echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[3]." name='idModifCategorie'><img src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                        if($row[2]==0){
                            echo "<td><button type='submit' value=".$row[3]." name='idSuppCategorie' ><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                        }else{
                            echo "<td><button type='submit' value=".$row[3]." disabled><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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

        <h2><span>Ajouter un genre</span></h2>
        <div class="AjoutCat">
            <form action="gestionGenre.php" method="post"  target="popup"
                  onSubmit="window.open('gestionGenre.php','popup','width=600,height=300,menubar=no,scrollbars=no')">
                <label for="NewCat">Nouveau Genre: </label>
                <input type="text" id="NewGenre" name="NewGenre" placeholder="Nom Genre..."/>
                <button type="submit" class="enregistrer">Enregistrer</button>
            </form>
        </div>
    </div>
    <div id="Tailles" class="taille">
        <h2><span>Gestion des Tailles</span></h2>
        <form action="gestionTaille.php" method="post">
            <TABLE>
                <thead>
                <th>Id</th>
                <th>Taille</th>
                <th>Stock</th>
                <th colspan="2">Actions</th>
                </thead>
                <tbody>
                <?php
                $sql ="SELECT Tailles.ID,Tailles.Libelle, SUM(T_Shirt.NB_stock),Tailles.ID FROM Tailles
                    LEFT JOIN T_Shirt ON T_Shirt.ID_Taille = Tailles.ID
                    GROUP BY Tailles.ID,Tailles.Libelle";
                if ($results = mysqli_query($connexion, $sql)){
                    while ($row = mysqli_fetch_row($results)) {
                        if($row[0]==null AND $row[2] == null){
                            $id=0;
                            $nb=0;
                        }else{
                            $id=$row[0];
                            $nb=$row[2];
                        }
                        echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[3]." name='idModifCategorie'><img src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                        if($row[2]==0){
                            echo "<td><button type='submit' value=".$row[3]." name='idSuppCategorie' ><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                        }else{
                            echo "<td><button type='submit' value=".$row[3]." disabled><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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

        <h2><span>Ajouter une taille</span></h2>
        <div class="AjoutCat">
            <form action="gestionGenre.php" method="post"  target="popup"
                  onSubmit="window.open('gestionGenre.php','popup','width=600,height=300,menubar=no,scrollbars=no')">
                <label for="NewCat">Nouvelle Taille: </label>
                <input type="text" id="NewTaille" name="NewTaille" placeholder="Taille..."/>
                <button type="submit" class="enregistrer">Enregistrer</button>
            </form>
        </div>
    </div>
    <div id="Couleurs" class="couleur">
        <h2><span>Gestion des Couleurs</span></h2>
        <form action="gestionCouleur.php" method="post">
            <TABLE>
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
                        echo "<tr><td>".$row[3]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[0]." name='idModifCategorie'><img src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                        if($row[2]==0){
                            echo "<td><button type='submit' value=".$row[0]." name='idSuppCategorie' ><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                        }else{
                            echo "<td><button type='submit' value=".$row[0]." disabled><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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

        <h2><span>Ajouter une couleur</span></h2>
        <div class="AjoutCat">
            <form action="gestionCouleur.php" method="post"  target="popup"
                  onSubmit="window.open('gestionCouleur.php','popup','width=600,height=300,menubar=no,scrollbars=no')">
                <label for="NewCat">Nouvelle Couleur: </label>
                <input type="text" id="NewCouleur" name="NewCouleur" placeholder="Nom couleur..."/>
                <button type="submit" class="enregistrer">Enregistrer</button>
            </form>
        </div>
    </div>
    <div id="Artistes" class="artisite">
        <h2><span>Gestion des Artistes</span></h2>
        <form action="gestionArtistes.php" method="post">
            <TABLE>
                <thead>
                <th>Id</th>
                <th>Artiste</th>
                <th>Stock</th>
                <th colspan="2">Actions</th>
                </thead>
                <tbody>
                <?php
                $sql ="SELECT Artiste.ID,CONCAT(Artiste.Prenom,' ',Artiste.Nom), SUM(T_Shirt.NB_stock) FROM Artiste
                        LEFT JOIN T_Shirt ON T_Shirt.ID_Artiste = Artiste.ID
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
                        echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$nb."</td><td><button type='submit' value=".$row[0]." name='idModifCategorie'><img src='assets/edit.svg' alt='Supprimer' width='15px' height='15px'></button></td>";
                        if($row[2]==0){
                            echo "<td><button type='submit' value=".$row[0]." name='idSuppCategorie' ><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
                        }else{
                            echo "<td><button type='submit' value=".$row[0]." disabled><img src='assets/delete.svg' alt='Supprimer' width='15px' height='15px'></button></td></tr>";
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

        <h2><span>Ajouter un(e) Artiste</span></h2>
        <div class="AjoutCat">
            <form action="gestionArtiste.php" method="post"  target="popup"
                  onSubmit="window.open('gestionArtiste.php','popup','width=600,height=300,menubar=no,scrollbars=no')">
                <label for="NewArtistePrenom">Prénom: </label>
                <input type="text" id="NewArtistePrenom" name="NewArtistePrenom" placeholder="Prenom Artiste..."/>
                <br><br><label for="NewArtisteNom">Nom: </label>
                <input type="text" id="NewArtisteNom" name="NewArtisteNom" placeholder="Nom Artiste..."/>
                <br><br><button type="submit" class="enregistrer">Enregistrer</button>
            </form>
        </div>
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

    ?>


    <script type="text/javascript">

      document.getElementById("Stock").style.display = "block";
      document.getElementById("Categories").style.display = "none";
      document.getElementById("Commandes").style.display = "none";
      document.getElementById("Ajout").style.display = "none";
      document.getElementById("Genres").style.display = "none";
      document.getElementById("Couleurs").style.display = "none";
      document.getElementById("Tailles").style.display = "none";
      <?php
          if(isset($_GET["page"])){
              echo 'document.getElementById("Stock").style.display = "none";';
              echo ' document.getElementById("'.$_GET["page"].'").style.display = "block";';
          }
      if(isset($_GET["categorie"])){
          echo 'document.getElementById("mySelectCategorie").options[checkSelect("mySelectCategorie","categorie")].selected = "selected";';
      }
      if(isset($_GET["taille"])){
          echo 'document.getElementById("mySelectTaille").options[checkSelect("mySelectTaille","taille")].selected = "selected";';
      }
      if(isset($_GET["genre"])){
          echo 'document.getElementById("mySelectGenre").options[checkSelect("mySelectGenre","genre")].selected = "selected";';
      }
      if(isset($_GET["genre"])){
          echo 'document.getElementById("mySelectNom").options[checkSelect("mySelectNom","nom")].selected = "selected";';
      }
      if(isset($_GET["artiste"])){
          echo 'document.getElementById("mySelectArtiste").options[checkSelect("mySelectArtiste","artiste")].selected = "selected";';
      }
      if(isset($_GET["couleur"])){
          echo 'document.getElementById("mySelectCouleur").options[checkSelect("mySelectCouleur","couleur")].selected = "selected";';
      }

      if(isset($_POST["idModifCategorie"]) OR isset($_POST["libelleCatModif"]) OR isset($_POST["IdCatModif"])){
          echo 'document.getElementById("Categories").style.display = "block";';
          echo 'document.getElementById("Stock").style.display = "none";';
          unset ($_POST["idModifCategorie"]);
      }
      ?>

      var tabIdDiv = {"Bstock":"Stock","BCategorieEdit":"Categories","Bcommandes":"Commandes","Bajout":"Ajout","BCouleurEdit":"Couleurs","BGenreEdit":"Genres","BTailleEdit":"Tailles","BArtistEdit":"Artistes"};

      for( const key in tabIdDiv){
          document.getElementById(key).addEventListener('click',openDiv);
      }


      function openDiv(){
            for( const key in tabIdDiv){
                if(key == this.getAttribute("id")){
                    document.getElementById(tabIdDiv[key]).style.display = "block";
                }else {
                    document.getElementById(tabIdDiv[key]).style.display = "none";
                }
            }
        }

        function checkSelect(id,NomSection){
          var selectBox = document.getElementById(id);

          switch (NomSection) {
            case "categorie":
                var get = '<?php echo $_GET["categorie"]?>';
                break;
            case "genre":
                  var get = '<?php echo $_GET["genre"]?>';
                  break;
            case "taille":
                  var get = '<?php echo $_GET["taille"]?>';
                  break;
              case "nom":
                  var get = '<?php echo $_GET["nom"]?>';
                  break;
              case "couleur":
                  var get = '<?php echo $_GET["couleur"]?>';
                  break;
              case "artiste":
                  var get = '<?php echo $_GET["artiste"]?>';
                  break;
          }

          for(var i=0 ; i<selectBox.options.length ;i++){
              if(get == selectBox.options[i].value){
                  var nb = i ;
              }

          }
           return nb;
      }
        function filtreCategorie() {
            var x = document.getElementById("mySelectCategorie").value;
            <?php
            $location = filtreUrl("categorie");
            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;
        }
        function filtreArtiste() {
          var x = document.getElementById("mySelectArtiste").value;
          <?php
          $location = filtreUrl("artiste");
          ?>
          var url = '<?php echo $location; ?>';
          document.location.href=url+x;
      }
        function filtreCouleur() {
          var x = document.getElementById("mySelectCouleur").value;
          <?php
          $location = filtreUrl("couleur");
          ?>
          var url = '<?php echo $location; ?>';
          document.location.href=url+x;
      }
        function filtreTaille() {
            var x = document.getElementById("mySelectTaille").value;

            <?php
            $location = filtreUrl("taille");
            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;

        }
        function filtreGenre(){
            var x = document.getElementById("mySelectGenre").value;
            <?php
            $location = filtreUrl("genre");
            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;

        }
        function  filtreNom(){
            var x = document.getElementById("mySelectNom").value;
            <?php
            $location = filtreUrl("nom");
            ?>
            var url = '<?php echo $location; ?>';
            document.location.href=url+x;

        }


    </script>

</body>

