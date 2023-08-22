<?php
const PLAYER = 'player';
const CPU = 'computer';
global $deck;
global $playerHand;
global $computerHand;
global $cards;
$cards = [
    '2c' => 'cards/2_of_clubs.png',
    '2d' => 'cards/2_of_diamonds.png',
    '2h' => 'cards/2_of_hearts.png',
    '2s' => 'cards/2_of_spades.png',
    '3c' => 'cards/3_of_clubs.png',
    '3d' => 'cards/3_of_diamonds.png',
    '3h' => 'cards/3_of_hearts.png',
    '3s' => 'cards/3_of_spades.png',
    '4c' => 'cards/4_of_clubs.png',
    '4d' => 'cards/4_of_diamonds.png',
    '4h' => 'cards/4_of_hearts.png',
    '4s' => 'cards/4_of_spades.png',
    '5c' => 'cards/5_of_clubs.png',
    '5d' => 'cards/5_of_diamonds.png',
    '5h' => 'cards/5_of_hearts.png',
    '5s' => 'cards/5_of_spades.png',
    '6c' => 'cards/6_of_clubs.png',
    '6d' => 'cards/6_of_diamonds.png',
    '6h' => 'cards/6_of_hearts.png',
    '6s' => 'cards/6_of_spades.png',
    '7c' => 'cards/7_of_clubs.png',
    '7d' => 'cards/7_of_diamonds.png',
    '7h' => 'cards/7_of_hearts.png',
    '7s' => 'cards/7_of_spades.png',
    '8c' => 'cards/8_of_clubs.png',
    '8d' => 'cards/8_of_diamonds.png',
    '8h' => 'cards/8_of_hearts.png',
    '8s' => 'cards/8_of_spades.png',
    '9c' => 'cards/9_of_clubs.png',
    '9d' => 'cards/9_of_diamonds.png',
    '9h' => 'cards/9_of_hearts.png',
    '9s' => 'cards/9_of_spades.png',
    'tc' => 'cards/10_of_clubs.png',
    'td' => 'cards/10_of_diamonds.png',
    'th' => 'cards/10_of_hearts.png',
    'ts' => 'cards/10_of_spades.png',
    'ac' => 'cards/ace_of_clubs.png',
    'ad' => 'cards/ace_of_diamonds.png',
    'ah' => 'cards/ace_of_hearts.png',
    'as' => 'cards/ace_of_spades.png',
    'jc' => 'cards/jack_of_clubs.png',
    'jd' => 'cards/jack_of_diamonds.png',
    'jh' => 'cards/jack_of_hearts.png',
    'js' => 'cards/jack_of_spades.png',
    'qc' => 'cards/queen_of_clubs.png',
    'qd' => 'cards/queen_of_diamonds.png',
    'qh' => 'cards/queen_of_hearts.png',
    'qs' => 'cards/queen_of_spades.png',
    'kc' => 'cards/king_of_clubs.png',
    'kd' => 'cards/king_of_diamonds.png',
    'kh' => 'cards/king_of_hearts.png',
    'ks' => 'cards/king_of_spades.png',
];

/**
 * init
 *
 * @return void
 */
function init()
{
    if (!isset($_SESSION['computerHand'])) {
        $_SESSION['computerHand'] = [];
    }
    if (!isset($_SESSION['playerHand'])) {
        $_SESSION['playerHand'] = [];
    }
    $GLOBALS['deck'] = remainingDeck();
}


/**
 * handlePost
 *
 * @return void
 */
function handlePost()
{
    if (isset($_POST['hit'])) {
        hit(PLAYER);
        if (calculateTotal($_SESSION['computerHand']) < 21) {
            hit(CPU);
        }
    } else if (isset($_POST['reset'])) {
        session_destroy();
        return header("Location: index.php");
    } else if (isset($_POST['stand'])) {
        stand();
    }
}

/**
 * hit
 *
 * @param  mixed $player
 * @return void
 */
function hit(string $player = PLAYER): void
{
    $drawnCard = array_rand($GLOBALS['deck']);
    if ($player == PLAYER) {
        $_SESSION['playerHand'][] = $drawnCard;
    } elseif ($player == CPU) {
        $_SESSION['computerHand'][] = $drawnCard;
    }
    unset($GLOBALS['deck'][$drawnCard]);
}

/**
 * calculateTotal
 *
 * @param  mixed $hand
 * @return int
 */
function calculateTotal(array $hand): int
{
    $total = 0;
    $aces = 0;
    foreach ($hand as $card) {
        $firstCharacter = substr($card, 0, 1);
        if (is_numeric($firstCharacter)) {
            $total += $firstCharacter;
        } 
        else if (in_array($firstCharacter, ['t', 'j', 'q', 'k'])) {
            $total += 10;
        }
        else if($firstCharacter == 'a'){
            $aces++;
        }
    }

    for($i = 0; $i < $aces; $i++){
        if(($total + 11) <= 21){
            $total += 11;
        }
        else{
            $total += 1;
        }
    }
    return $total;
}

/**
 * stand
 *
 * @return void
 */
function stand(): void
{
    if(calculateTotal($_SESSION['playerHand']) >= 21){
        while (calculateTotal($_SESSION['computerHand']) < 21) {
            hit(CPU);
        }
    }
}

/**
 * calculateWinner
 *
 * @return string
 */
function calculateWinner(): string
{
    $playerTotal = calculateTotal($_SESSION['playerHand']);
    $cpuTotal = calculateTotal($_SESSION['computerHand']);

    if (($cpuTotal > 21 && $playerTotal > 21) || $playerTotal == $cpuTotal) {
        return '<h1>It\'s a tie!</h1>';
    } elseif (($playerTotal > $cpuTotal || $cpuTotal > 21) && $playerTotal <= 21) {
        if ($playerTotal == 21) {
            return '<h1>BLACKJACK! YOU WIN!</h1>';
        }
        return '<h1>Congratulations, you are the winner!</h1>';
    } else {
        if ($playerTotal > 21) {
            return '<h1>It\'s a bust, you lose!</h1>';
        }
        return '<h1>CPU was closer to 21, you have lost.</h1>';
    }
}

/**
 * remainingDeck
 *
 * @return array
 */
function remainingDeck(): array
{
    $remainingDeck = $GLOBALS['cards'];
    foreach ($_SESSION['computerHand'] as $card) {
        unset($remainingDeck[$card]);
    }
    foreach ($_SESSION['playerHand'] as $card) {
        unset($remainingDeck[$card]);
    }
    return $remainingDeck;
}
