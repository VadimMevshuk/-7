<?php
session_start();

// Функція для валідації введених даних
function validate($inputParam) {
    $validChoices = ['rock', 'paper', 'scissors'];
    return in_array($inputParam, $validChoices);
}

// Функція для визначення переможця раунду
function playRound($userChoice, $computerChoice) {
    if ($userChoice === $computerChoice) {
        return 'draw';
    }

    if (
        ($userChoice === 'rock' && $computerChoice === 'scissors') ||
        ($userChoice === 'scissors' && $computerChoice === 'paper') ||
        ($userChoice === 'paper' && $computerChoice === 'rock')
    ) {
        return 'user';
    }

    return 'computer';
}

// Словник для перекладу
function translateChoice($choice) {
    $translations = [
        'rock' => 'Камінь',
        'paper' => 'Папір',
        'scissors' => 'Ножиці'
    ];
    return $translations[$choice] ?? $choice;
}

// Ініціалізація гри
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = ['user' => 0, 'computer' => 0, 'round' => 0];
}

// Обробка вибору користувача
if (isset($_POST['choice'])) {
    $userChoice = $_POST['choice'];

    if (validate($userChoice)) {
        $computerChoice = ['камінь', 'папір', 'ножниці'][rand(0, 2)];
        $result = playRound($userChoice, $computerChoice);

        if ($result === 'user') {
            $_SESSION['score']['user']++;
        } elseif ($result === 'computer') {
            $_SESSION['score']['computer']++;
        }

        $_SESSION['score']['round']++;

        $_SESSION['last_round'] = [
            'user_choice' => $userChoice,
            'computer_choice' => $computerChoice,
            'result' => $result
        ];
    }
}

// Скидання гри
if (isset($_POST['reset'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Style.css">

    <title>Камінь, Ножиці, Папір</title>
</head>
<body>
<div class="game-container">
    <h1>Камінь, Ножиці, Папір</h1>

    <div class="scoreboard">
        <div class="score user-score">
            <p>Ваш рахунок</p>
            <span><?= $_SESSION['score']['user'] ?></span>
        </div>
        <p class="round-info">Раунд: <?= $_SESSION['score']['round'] ?>/3</p>
        <div class="score computer-score">
            <p>Рахунок комп'ютера</p>
            <span><?= $_SESSION['score']['computer'] ?></span>
        </div>
    </div>

    <?php if ($_SESSION['score']['round'] < 3): ?>
        <form method="POST" class="choices">
            <button class="btn-rock" name="choice" value="rock">Камінь</button>
            <button class="btn-paper" name="choice" value="paper">Папір</button>
            <button class="btn-scissors" name="choice" value="scissors">Ножиці</button>
        </form>
    <?php else: ?>
        <div class="result">
            <?php
            if ($_SESSION['score']['user'] > $_SESSION['score']['computer']) {
                echo "Ви перемогли!";
            } elseif ($_SESSION['score']['user'] < $_SESSION['score']['computer']) {
                echo "Комп'ютер переміг!";
            } else {
                echo "Нічия!";
            }
            ?>
        </div>
        <form method="POST">
            <button class="btn-reset" name="reset">Рестарт</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_SESSION['last_round'])): ?>
        <div class="result">
            <p>Ваш вибір: <?= translateChoice($_SESSION['last_round']['user_choice']) ?></p>
            <p>Вибір комп'ютера: <?= translateChoice($_SESSION['last_round']['computer_choice']) ?></p>
            <p>Результат: 
                <?php
                if ($_SESSION['last_round']['result'] === 'user') {
                    echo "Ви виграли раунд!";
                } elseif ($_SESSION['last_round']['result'] === 'computer') {
                    echo "Комп'ютер виграв раунд!";
                } else {
                    echo "Нічия!";
                }
                ?>
            </p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
