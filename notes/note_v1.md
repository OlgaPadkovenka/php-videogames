//Affichage

1. Etablir la connection avec la base de données
$databaseHandler = new PDO('mysql:host=localhost;dbname=videogames', 'root', 'root');

2. J'écris une requette qui va chercher la table game.
$statement = $databaseHandler->query('SELECT
    `game`.`id`,
    `game`.`title`,
    `game`.`release_date`,
    `game`.`link`,
    `developer`.`name` as `developer_name`,
    `developer`.`link` as `developer_link`,
    `platform`.`name` as `platform_name`,
    `platform`.`link` as `platform_link`
FROM `game`
JOIN `developer` ON `game`.`developer_id` = `developer`.`id`
JOIN `platform` ON `game`.`platform_id` = `platform`.`id`');
$games = $statement->fetchAll();

3. Je fais un foreach qui cherche tous les games
  <?php foreach ($games as $game) : ?>
                        <tr>
                            <th scope="row">1</th>
                            <td>
                                <a href="https://en.wikipedia.org/wiki/Populous_(video_game)">Populous</a>
                            </td>
                            <td>5 june 1989</td>
                            <td>
                                <a href="https://en.wikipedia.org/wiki/Bullfrog_Productions">Bullfrog Productions</a>
                            </td>
                            <td>
                                <a href="https://en.wikipedia.org/wiki/Amiga">Amiga</a>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>

4. Je cherche l'information.
  <?php foreach ($games as $game) : ?>
                        <tr>
                            <th scope="row"><?= $game['id'] ?></th>
                            <td>
                                <a href="<?= $game['link'] ?>"><?= $game['title'] ?></a>
                            </td>
                            <td><?= $game['release_date'] ?></td>
                            <td>
                                <a href="<?= $game['developer_link'] ?>"><?= $game['developer_name'] ?></a>
                            </td>
                            <td>
                                <a href="<?= $game['platform_link'] ?>"><?php $game['platform_name'] ?></a>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                    <?php endforeach ?>

// Creation de game 
5. Pour la création du jeu, je remplis un formulaire où j'affiche l'option avec les developers et les platforms.

J'affiche developer dans l'option.

Je fais la requette:
$statement = $databaseHandler->query('SELECT * FROM developer');
$developers = $statement->fetchAll();

   <td>
                                <select name="developer">
                                    <?php foreach ($developers as $developer) : ?>
                                        <option value="<?php $developer['id'] ?>"><?= $developer['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>

P.S. Je fais pareil pour les platforms.

6. Je crée un dossier actions où je crée un fichier create.php

7. Dans la <form> à inde.php, j'ajoute une méthode et action pour redirection.
<form method="post" action="actions/create.php">

8. Je remplis la valeur de titre et link de game.

    <td>
                                <input type="text" name="title" placeholder="Title" value="<?= $game['title'] ?>" />
                                <br />
                                <input type="text" name="link" placeholder="External link" value="<?= $game['link'] ?>" />
                            </td>

9. Dans create.php, je fais la requette 
// Configure une connexion au serveur de base de données
 $databaseHandler = new PDO('mysql:host=localhost;dbname=videogames', 'root', 'root');
 // Crée un modèle de requête "à trous" dans lequel on pourra injecter des variables
 $statement = $databaseHandler->prepare('INSERT INTO `game`
     (`title`, `link`, `release_date`, `developer_id`, `platform_id`)
 VALUES (:title, :link, :release_date, :developer_id, :platform_id)');
 // Exécute la requête préparée en remplaçant chaque champ variable par le contenu reçu du champ correspondant dans le formulaire
 $statement->execute([
     ':title' => $_POST['title'],
     ':link' => $_POST['link'],
     ':release_date' => $_POST['release_date'],
     ':developer_id' => $_POST['developer'],
     ':platform_id' => $_POST['platform'],
 ]);


10. Je dis, si la méthode n'est pas la Post, j'affiche une erreur.
try {
    // Si la méthode HTTP utilisée dans cette requête n'est pas POST, c'est donc que l'utilisateur a tenté d'accéder à ce script manuellement
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('This script must be accessed via a POST HTTP request.', 0);
    }
} catch (Exception $exception) {
    // Redirige sur la liste des jeux
    header('Location: /?error=' . $exception->getCode());
}

11. Je vérifie, s'il y a tous les champs.

   // S'il manque un seul des champ présents dans le formulaire, c'est donc que l'utilisateur a contourné le formulaire
    if (
        !isset($_POST['title']) ||
        !isset($_POST['link']) ||
        !isset($_POST['release_date']) ||
        !isset($_POST['developer']) ||
        !isset($_POST['platform'])
    ) {
        throw new Exception('Form field missing in request.', 1);
    }

12. Je vérifie, s'il tous les champs sont remplis.

   // Teste si l'un des champs est vide
    if (
        empty($_POST['title']) ||
        empty($_POST['link']) ||
        empty($_POST['release_date']) ||
        empty($_POST['developer']) ||
        empty($_POST['platform'])
    ) {
        throw new Exception('Form should not have empty fields.', 2);
    }

13. Si la méthode est post, si tous les champs sont remplis, s'il y a tous les champs, j'execute ma requette.

try {
     // Si la méthode HTTP utilisée dans cette requête n'est pas POST, c'est donc que l'utilisateur a tenté d'accéder à ce script manuellement
     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         throw new Exception('This script must be accessed via a POST HTTP request.', 0);
     }

     // Configure une connexion au serveur de base de données
     $databaseHandler = new PDO('mysql:host=localhost;dbname=videogames', 'root', 'root');
     // Crée un modèle de requête "à trous" dans lequel on pourra injecter des variables
     $statement = $databaseHandler->prepare('INSERT INTO `game`
         (`title`, `link`, `release_date`, `developer_id`, `platform_id`)
     VALUES (:title, :link, :release_date, :developer_id, :platform_id)');
     // Exécute la requête préparée en remplaçant chaque champ variable par le contenu reçu du champ correspondant dans le formulaire
     $statement->execute([
         ':title' => $_POST['title'],
         ':link' => $_POST['link'],
         ':release_date' => $_POST['release_date'],
         ':developer_id' => $_POST['developer'],
         ':platform_id' => $_POST['platform'],
     ]);
 }
 catch (Exception $exception) {
     // Redirige sur la liste des jeux
     header('Location: /?error=' . $exception->getCode());
 }

//Affichage des erreurs
14.  J'affiche les erreurs pour l'utilisateur.
Je crée un tableau.
$errorMessages = [
     2 => 'Form should not have empty fields.',
     22001 => 'Form field value is too long.',
 ];

 15. 
          <?php if (isset($_GET['error'])): ?>
                 <!-- Si un code d'erreur a été envoyé dans les query parameters, il faut afficher une alerte -->
                 <div class="alert alert-danger">
                     <?php

                     // Si un message spécifique a été prévu pour ce code d'erreur, l'affiche
                     if (isset($errorMessages[$_GET['error']])) {
                         echo $errorMessages[$_GET['error']];
                     // Sinon, affiche un message d'erreur générique
                     } else {
                         echo 'There was an error processing your form.';
                     }

                     ?>
                 </div>
             <?php endif; ?>

//Update
16. Je crée un fichier update.php

17. C'est la même chose que create, mais j'ajoute un id dans la requette.

18. J'ajoute aussi dans la requette SELECT dans index.php
 `game`.`developer_id`,
`game`.`platform_id`,

19. Je récupère l'id de game quand je clique sur le bouton.
Form par défaut est en get.
  <td>
                                <form>
                                    <input name="update"  type="hidden" value="<?= $game['id'] ?>" />
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                            </td>

20. Si dans les paramètres il y a update, j'affiche le formulaire pour pouvoir faire un update d'un game.

 <?php if (isset($_GET['update']) && $_GET['update'] === $game['id']) : ?>
<form>...</form>
                        <?php endif ?>

21. 
 <option value="<?= $developer['id'] ?>" <?php if ($developer['id'] === $game['developer_id']) echo 'selected' ?>><?= $developer['name'] ?></option>

//Delete
22. Je crée un fichier delete.php. Je fais la requette.
    // Crée un modèle de requête "à trous" dans lequel on pourra injecter des variables
    $statement = $databaseHandler->prepare('DELETE FROM `game` WHERE `id` = :id');
    // Exécute la requête préparée en remplaçant chaque champ variable par le contenu reçu du champ correspondant dans le formulaire
    $statement->execute([
        ':id' => $_POST['id']
    ]);

23. Je crée un formulaire dans index.php
    <form method="post" action="actions/delete.php">
                                    <input type="hidden" name="id" value="<?= $game['id'] ?>" />
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

24. J'affiche un message de confirmation de suppretion d'un game.
  <script>
                                    function confirmDeleteGame() {
                                        return window.confirm("Est-ce que vous êtes sûr de vouloir supprimer le jeu vidéo?");
                                    }
                                </script>

25. J'ajoute un input dans le form delete
<input type="hidden" name="confirmDelete" value="confirmDeleteGame" />

26. J'appelle la fonction confirmDeleteGame.
  <form onsubmit="return confirmDeleteGame()" method="post" action="actions/delete.php">

26. Je fais la condition dans delete.php
  if (isset($_POST['confirmDelete'])) {
        // Configure une connexion au serveur de base de données
        $databaseHandler = new PDO('mysql:host=localhost;dbname=videogames', 'root', 'root');
        // Crée un modèle de requête "à trous" dans lequel on pourra injecter des variables
        $statement = $databaseHandler->prepare('DELETE FROM `game` WHERE `id` = :id');
        // Exécute la requête préparée en remplaçant chaque champ variable par le contenu reçu du champ correspondant dans le formulaire
        $statement->execute([
            ':id' => $_POST['id']
        ]);
        // Redirige sur la liste des jeux
        header('Location: ../');
    }