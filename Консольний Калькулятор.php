<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Style3.css">
    <title>Консольний калькулятор</title>
</head>
<body>
    <div class="calculator-container">
        <h1>Консольний Калькулятор</h1>
        <form method="post">
            <div class="input-group">
                <input type="text" name="expression" placeholder="Введіть вираз (наприклад, 5 + 3)" required>
            </div>
            <button type="submit">Обчислити</button>
        </form>
        <div class="result">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $expression = $_POST['expression'] ?? '';

                function calculate($num1, $operator, $num2)
                {
                    $operations = [
                        '+' => fn($a, $b) => $a + $b,
                        '-' => fn($a, $b) => $a - $b,
                        '*' => fn($a, $b) => $a * $b,
                        '/' => fn($a, $b) => $b != 0 ? $a / $b : 'Ділення на нуль',
                        '**' => fn($a, $b) => $a ** $b,
                        '%' => fn($a, $b) => $b != 0 ? $a % $b : 'Залишок від ділення на нуль'
                    ];

                    if (!array_key_exists($operator, $operations)) {
                        return 'Невідомий оператор';
                    }

                    return $operations[$operator]($num1, $num2);
                }

                if (preg_match('/^\s*(\d+)\s*([\+\-\*\/\%\*]{1,2})\s*(\d+)\s*$/', $expression, $matches)) {
                    $num1 = (float)$matches[1];
                    $operator = $matches[2];
                    $num2 = (float)$matches[3];

                    $result = calculate($num1, $operator, $num2);
                    echo "<strong>Результат:</strong> $result";
                } else {
                    echo "<span class='error'>Неправильний формат виразу! Використовуйте формат: число1 оператор число2.</span>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
