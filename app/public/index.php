<?php
declare(strict_types=1);

use Aspe\Blackjack\GameManager;

require __DIR__ . '/../vendor/autoload.php';

session_start();

$gm = new GameManager();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $gm->handlePost();
}
?>
<!DOCTYPE html>
<html>

<body>
    <?php
    if(isset($_POST['stand'])){
        echo $gm->calculateWinner();
    }
    ?>
    <div>
        <h2>Your hand</h2>
        <div>
            <?php
            foreach ($_SESSION['playerHand'] as $cardInHand) {
                echo sprintf('<img src="%s" style="height:100px;">', $gm->cards[$cardInHand]);
            }
            ?>
        </div>
        <p>total: <?= $gm->playerTotal; ?></p>
    </div>
    <div>
        <h2>Computer Hand</h2>
        <div>
            <?php
            foreach ($_SESSION['computerHand'] as $cardInHand) {
                echo sprintf('<img src="%s" style="height:100px;">', $gm->cards[$cardInHand]);
            }
            ?>
        </div>
        <p>total: <?= $gm->cpuTotal ?></p>
    </div>
    <form action="index.php" method="POST">
        <input type="submit" name="hit" value="Hit" <?= $gm->playerTotal >= 21 ? 'disabled' : '' ?>/>
        <input type="submit" name="stand" value="Stand" />
        <input type="submit" name="reset" value="Reset" />
    </form>
</body>

</html>