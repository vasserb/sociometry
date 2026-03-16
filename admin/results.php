<?php
require '..\db.php';

$class_code = $_GET['class'] ?? null;

// Получаем список всех классов
$classes_stmt = $pdo->query("SELECT * FROM classes ORDER BY class_name");
$all_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$class_code && count($all_classes) > 0) {
    $class_code = $all_classes[0]['class_code']; // по умолчанию первый класс
}

// Получаем выбранный класс
$stmt = $pdo->prepare("SELECT * FROM classes WHERE class_code = ?");
$stmt->execute([$class_code]);
$class = $stmt->fetch();

if (!$class) {
    die("Класс не найден");
}

// Получаем список учеников
$students = $pdo->prepare("SELECT * FROM students WHERE class_id = ?");
$students->execute([$class['id']]);
$students = $students->fetchAll(PDO::FETCH_ASSOC);
$total_students = count($students);

// Получаем все ответы
$responses = $pdo->prepare("SELECT * FROM sociometry_responses WHERE class_id = ?");
$responses->execute([$class['id']]);
$responses = $responses->fetchAll(PDO::FETCH_ASSOC);
$total_responses = count($responses);

// Список тех, кто прошёл анкетирование
$responded_ids = array_column($responses, 'student_id');

// Подсчёт статистики
$stats = [];
foreach ($students as $s) {
    $stats[$s['id']] = [
        'name' => $s['full_name'],
        'q2' => 0,
        'q3' => 0,
        'q4' => 0,
        'responded' => in_array($s['id'], $responded_ids)
    ];
}

foreach ($responses as $r) {
    foreach (['q2_choices','q3_choices','q4_choices'] as $q) {
        $choices = json_decode($r[$q], true);
        if ($choices) {
            foreach ($choices as $c) {
                if (isset($stats[$c])) {
                    $stats[$c][substr($q,0,2)]++;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты социометрии <?= htmlspecialchars($class['class_name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\style.css">
    <style>
        .stats-container {
            max-width: 800px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(13, 59, 102, 0.15);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #d0e3f7;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #0d6efd;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f1f7ff;
        }
        .responded {
            color: green;
            font-weight: bold;
        }
        .controls {
            margin-bottom: 20px;
            text-align: center;
        }
        select {
            padding: 8px;
            font-size: 1rem;
        }
        .links {
            margin-top: 20px;
            text-align: center;
        }
        .links a {
            display: inline-block;
            margin: 5px;
            padding: 10px 15px;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
        }
        .links a:hover {
            background-color: #0b5ed7;
        }
        .qr-code {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="stats-container">
    <h2>Результаты по классу <?= htmlspecialchars($class['class_name']) ?></h2>

    <!-- Переключение между классами -->
    <div class="controls">
        <form method="GET" action="results.php">
            <label for="class">Выберите класс:</label>
            <select name="class" id="class" onchange="this.form.submit()">
                <?php foreach ($all_classes as $c): ?>
                    <option value="<?= $c['class_code'] ?>" <?= $c['class_code']==$class_code ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['class_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <p><strong>Всего учеников:</strong> <?= $total_students ?></p>
    <p><strong>Прошли анкетирование:</strong> <?= $total_responses ?></p>

    <table>
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Вопрос 2<br>(учиться вместе)</th>
                <th>Вопрос 3<br>(день рождения)</th>
                <th>Вопрос 4<br>(поход)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($stats as $s): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($s['name']) ?>
                        <?php if ($s['responded']): ?>
                            <span class="responded">✅</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $s['q2'] ?></td>
                    <td><?= $s['q3'] ?></td>
                    <td><?= $s['q4'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Дополнительные ссылки -->
<div class="links">
    <p>Ссылка на анкету для класса:</p>
    <?php 
        $formUrl = 'https://apache.school1298.ru/sociometry/form.php?class='.$class_code;
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=600x600&data=" . urlencode($formUrl);
    ?>
    <input type="text" id="formLink" value="<?= htmlspecialchars($formUrl) ?>" readonly style="width:80%; padding:8px; text-align:center;">
    <br>
    <button class="action-btn" onclick="copyLink()">📋 Скопировать ссылку</button>
    <button class="action-btn" onclick="downloadQR()">📥 Скачать QR‑код</button>
    <div id="notify" style="margin-top:10px; font-weight:bold;"></div>
</div>

<script>
function copyLink() {
    const linkInput = document.getElementById("formLink");
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // для мобильных
    navigator.clipboard.writeText(linkInput.value).then(() => {
        showNotify("Ссылка скопирована!", "green");
    });
}

function downloadQR() {
    const qrUrl = "<?= $qrUrl ?>";
    const link = document.createElement("a");
    link.href = qrUrl;
    link.download = "qr_class_<?= $class_code ?>.png";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showNotify("QR‑код скачан!", "green");
}

function showNotify(message, color) {
    const notify = document.getElementById("notify");
    notify.textContent = message;
    notify.style.color = color;
    setTimeout(() => { notify.textContent = ""; }, 3000);
}
</script>


</div>

</body>
</html>
