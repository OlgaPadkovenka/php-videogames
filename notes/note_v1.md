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
5. J'affiche developer.

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

6. 

