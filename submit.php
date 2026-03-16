<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отправка анкеты</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- адаптация под телефон -->
    <link rel="stylesheet" href="style.css">
    <style>
        .message-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(13, 59, 102, 0.15);
            text-align: center;
        }
        .success {
            color: #0d6efd;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 1.1rem;
            font-weight: bold;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }
        a:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
<div class="message-container">
<?php
try {
    $class_code = $_GET['class'] ?? null;
    if (!$class_code) {
        throw new Exception("Не указан код класса");
    }

    // Получаем класс по коду
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE class_code = ?");
    $stmt->execute([$class_code]);
    $class = $stmt->fetch();

    if (!$class) {
        throw new Exception("Класс не найден");
    }

    $q1_self = $_POST['q1_self'] ?? null;
    if (!$q1_self) {
        throw new Exception("Не выбрана фамилия");
    }

    // Проверка: ученик уже отправлял ответ?
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sociometry_responses WHERE student_id = ? AND class_id = ?");
    $stmt->execute([$q1_self, $class['id']]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("Вы уже отправили ответ. Повторная отправка невозможна.");
    }

    // Ограничиваем выбор до 3 вариантов
    $q2_choices = $_POST['q2_choices'] ?? [];
    $q3_choices = $_POST['q3_choices'] ?? [];
    $q4_choices = $_POST['q4_choices'] ?? [];

    if (count($q2_choices) > 3 || count($q3_choices) > 3 || count($q4_choices) > 3) {
        throw new Exception("Можно выбрать максимум 3 варианта в каждом вопросе.");
    }

    $q2 = json_encode($q2_choices, JSON_UNESCAPED_UNICODE);
    $q3 = json_encode($q3_choices, JSON_UNESCAPED_UNICODE);
    $q4 = json_encode($q4_choices, JSON_UNESCAPED_UNICODE);

    $stmt = $pdo->prepare("
        INSERT INTO sociometry_responses (student_id, class_id, q1_self, q2_choices, q3_choices, q4_choices) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([$q1_self, $class['id'], $q1_self, $q2, $q3, $q4]);

    echo "<p class='success'>✅ Спасибо! Ответы сохранены.</p>";
    echo "<a href='form.php?class={$class_code}'>Вернуться к форме</a>";
} catch (Exception $e) {
    echo "<p class='error'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<a href='form.php?class={$class_code}'>Вернуться к форме</a>";
}
?>
</div>
</body>
</html>
