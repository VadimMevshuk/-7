<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Style2.css">
    <title>Вгадай число</title>
</head>
<body>
    <div class="game-container">
        <h1>Гра "Вгадай число"</h1>
        <form method="post">
            <div class="message">
                <?php
                session_start();

                if (!isset($_SESSION['targetNumber'])) {
                    // Ініціалізація гри
                    $_SESSION['targetNumber'] = rand(1, 100);
                    $_SESSION['attempts'] = 7;
                    echo "Комп'ютер загадав число від 1 до 100. У вас є 7 спроб!";
                } else {
                    // Обробка спроб
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $userInput = $_POST['guess'] ?? null;

                        if (is_numeric($userInput) && $userInput >= 1 && $userInput <= 100) {
                            $userGuess = (int)$userInput;
                            $_SESSION['attempts']--;

                            if ($userGuess === $_SESSION['targetNumber']) {
                                echo "<span style='color:green;'>Вітаємо! Ви вгадали число {$_SESSION['targetNumber']}!</span>";
                                session_destroy();
                                exit;
                            } elseif ($_SESSION['attempts'] <= 0) {
                                echo "<span style='color:red;'>На жаль, спроби закінчились! Загадане число: {$_SESSION['targetNumber']}.</span>";
                                session_destroy();
                                exit;
                            } elseif ($userGuess < $_SESSION['targetNumber']) {
                                echo "Спробуй більше. Залишилось {$_SESSION['attempts']} спроб.";
                            } else {
                                echo "Спробуй менше. Залишилось {$_SESSION['attempts']} спроб.";
                            }
                        } else {
                            echo "<span style='color:red;'>Будь ласка, введіть ціле число від 1 до 100!</span>";
                        }
                    }
                }
                ?>
            </div>
            <div class="input-container">
                <input type="number" name="guess" placeholder="Введіть число" required>
            </div>
            <button type="submit">Відправити</button>
        </form>
        <form method="post" action="">
            <button type="submit" name="restart" class="restart-btn">Перезапустити</button>
            <?php
            if (isset($_POST['restart'])) {
                session_destroy();
                header("Location: " . $_SERVER['PHP_SELF']);
            }
            ?>
        </form>
    </div>
</body>
</html>
