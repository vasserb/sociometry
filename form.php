<?php
require 'db.php';

$class_code = $_GET['class'] ?? null;
if (!$class_code) {
    die("Не указан код класса");
}

$class = $pdo->prepare("SELECT * FROM classes WHERE class_code = ?");
$class->execute([$class_code]);
$class = $class->fetch();

if (!$class) {
    die("Класс не найден");
}

$students = $pdo->prepare("SELECT * FROM students WHERE class_id = ?");
$students->execute([$class['id']]);
$students = $students->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Социометрия <?= htmlspecialchars($class['class_name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- адаптация под телефон -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Твоё самочувствие в классе (<?= htmlspecialchars($class['class_name']) ?>)</h2>

    <form method="POST" action="submit.php?class=<?= $class['class_code'] ?>">
        
        <div class="question-block">
            <h3>1. Выбери свою фамилию:</h3>
            <select name="q1_self" required>
                <option value="" disabled selected>-- выберите фамилию --</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="question-block">
            <h3>2. Если класс расформируют, с кем бы ты хотел продолжить учиться?</h3>
			    <small class="hint">Можно выбрать от 0 до 3 вариантов</small> 
            <?php foreach ($students as $s): ?>
                <label><input type="checkbox" name="q2_choices[]" value="<?= $s['id'] ?>"> <?= htmlspecialchars($s['full_name']) ?></label>
            <?php endforeach; ?>
        </div>

        <div class="question-block">
            <h3>3. Кого пригласил бы на день рождения?</h3>
			    <small class="hint">Можно выбрать от 0 до 3 вариантов</small>
            <?php foreach ($students as $s): ?>
                <label><input type="checkbox" name="q3_choices[]" value="<?= $s['id'] ?>"> <?= htmlspecialchars($s['full_name']) ?></label>
            <?php endforeach; ?>
        </div>

        <div class="question-block">
            <h3>4. С кем пошёл бы в поход?</h3>
			    <small class="hint">Можно выбрать от 0 до 3 вариантов</small>
            <?php foreach ($students as $s): ?>
                <label><input type="checkbox" name="q4_choices[]" value="<?= $s['id'] ?>"> <?= htmlspecialchars($s['full_name']) ?></label>
            <?php endforeach; ?>
        </div>

        <button type="submit">Отправить</button>
    </form>
</div>

<script src="val.js"></script>
</body>
</html>
