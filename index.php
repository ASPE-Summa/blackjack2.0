<?php
require 'functions.php';
session_start();
init();
handlePost();
?>
<!DOCTYPE html>
<html>

<body>
    <?php
    if(isset($_POST['stand'])){
        echo calculateWinner();
    }
    ?>
    <div>
        <h2>Your hand</h2>
        <div>
            <?php
            foreach ($_SESSION['playerHand'] as $cardInHand) {
                echo sprintf('<img src="%s" style="height:100px;">', $GLOBALS['cards'][$cardInHand]);
            }
            ?>
        </div>
        <p>total: <?= calculateTotal($_SESSION['playerHand']); ?></p>
    </div>
    <div>
        <h2>Computer Hand</h2>
        <div>
            <?php
            foreach ($_SESSION['computerHand'] as $cardInHand) {
                echo sprintf('<img src="%s" style="height:100px;">', $GLOBALS['cards'][$cardInHand]);
            }
            ?>
        </div>
        <p>total: <?= calculateTotal($_SESSION['computerHand']); ?></p>
    </div>
    <form action="index.php" method="POST">
        <input type="submit" name="hit" value="Hit" <?= calculateTotal($_SESSION['playerHand']) >= 21 ? 'disabled' : '' ?>/>
        <input type="submit" name="stand" value="Stand" />
        <input type="submit" name="reset" value="Reset" />
    </form>
</body>

</html>