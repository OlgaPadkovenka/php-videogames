<?php

$errorMessages = [
    2 => 'Form should not have empty fields.',
    22001 => 'Form field value is too long.',
];

$databaseHandler = new PDO('mysql:host=localhost;dbname=videogames', 'root', 'root');

$statement = $databaseHandler->query('SELECT
    `game`.`id`,
    `game`.`title`,
    `game`.`release_date`,
    `game`.`link`,
    `game`.`developer_id`,
    `game`.`platform_id`,
    `developer`.`name` as `developer_name`,
    `developer`.`link` as `developer_link`,
    `platform`.`name` as `platform_name`,
    `platform`.`link` as `platform_link`
FROM `game`
JOIN `developer` ON `game`.`developer_id` = `developer`.`id`
JOIN `platform` ON `game`.`platform_id` = `platform`.`id`');
$games = $statement->fetchAll();

$statement = $databaseHandler->query('SELECT * FROM developer');
$developers = $statement->fetchAll();

$statement = $databaseHandler->query('SELECT * FROM platform');
$platforms = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="card text-center">
            <img src="images/data-original.jpg" class="card-img-top" alt="Retro gaming banner">
            <?php if (isset($_GET['error'])) : ?>
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
            <div class="card-header">
                <h1 class="mt-4 mb-4">My beautiful video games</h1>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col"># <i class="fas fa-sort-down"></i></th>
                        <th scope="col">Title <i class="fas fa-sort-down"></i></th>
                        <th scope="col">Release date <i class="fas fa-sort-down"></i></th>
                        <th scope="col">Developer <i class="fas fa-sort-down"></i></th>
                        <th scope="col">Platform <i class="fas fa-sort-down"></i></th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
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
                                <a href="<?= $game['platform_link'] ?>"><?= $game['platform_name'] ?></a>
                            </td>
                            <td>
                                <form>
                                    <input name="update" type="hidden" value="<?= $game['id'] ?>" />
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>

                        <?php if (isset($_GET['update']) && $_GET['update'] === $game['id']) : ?>

                            <!-- start form update-->
                            <form method="post" action="actions/update.php">
                                <input type="hidden" name="id" value="<?= $game['id'] ?>" />
                                <tr>
                                    <th scope="row"><?= $game['id'] ?></th>
                                    <td>
                                        <input type="text" name="title" placeholder="Title" value="<?= $game['title'] ?>" />
                                        <br />
                                        <input type="text" name="link" placeholder="External link" value="<?= $game['link'] ?>" />
                                    </td>
                                    <td>
                                        <input type="date" name="release_date" value="<?= $game['release_date'] ?>" />
                                    </td>
                                    <td>
                                        <select name="developer">
                                            <?php foreach ($developers as $developer) : ?>
                                                <option value="<?= $developer['id'] ?>" <?php if ($developer['id'] === $game['developer_id']) echo 'selected' ?>><?= $developer['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="platform">
                                            <?php foreach ($platforms as $platform) : ?>
                                                <option value="<?= $platform['id'] ?>" <?php if ($platform['id'] === $game['platform_id']) echo 'selected' ?>><?= $platform['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                    <td></td>
                                </tr>
                            </form>
                            <!-- end form update -->
                        <?php endif ?>
                    <?php endforeach ?>


                    <!--start form create -->
                    <form method="post" action="actions/create.php">
                        <tr>
                            <th scope="row"></th>
                            <td>
                                <input type="text" name="title" placeholder="Title" value="<?= $game['title'] ?>" />
                                <br />
                                <input type="text" name="link" placeholder="External link" value="<?= $game['link'] ?>" />
                            </td>
                            <td>
                                <input type="date" name="release_date" />
                            </td>
                            <td>
                                <select name="developer">
                                    <?php foreach ($developers as $developer) : ?>
                                        <option value="<?= $developer['id'] ?>"><?= $developer['name'] ?> </option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td>
                                <select name="platform">
                                    <?php foreach ($platforms as $platform) : ?>
                                        <option value="<?= $platform['id'] ?>"><?= $platform['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                            <td></td>
                        </tr>
                    </form>
                    <!-- end form create -->
                </tbody>
            </table>
            <div class="card-body">
                <p class="card-text">This interface lets you sort and organize your video games!</p>
                <p class="card-text">Let us know what you think and give us some love! 🥰</p>
            </div>
            <div class="card-footer text-muted">
                Created by <a href="https://github.com/olgapadkovenka">CDA Lyon</a> &copy; 2021
            </div>
        </div>
    </div>
</body>

</html>