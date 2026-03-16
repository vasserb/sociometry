<?php
require 'db.php';

$class_code = $_GET['class'] ?? null;
if (!$class_code) die("Не указан код класса");

// Получаем класс
$stmt = $pdo->prepare("SELECT * FROM classes WHERE class_code = ?");
$stmt->execute([$class_code]);
$class = $stmt->fetch();

if (!$class) die("Класс не найден");

// Получаем список учеников
$students = $pdo->prepare("SELECT * FROM students WHERE class_id = ?");
$students->execute([$class['id']]);
$students = $students->fetchAll(PDO::FETCH_ASSOC);

// Получаем все ответы
$responses = $pdo->prepare("SELECT * FROM sociometry_responses WHERE class_id = ?");
$responses->execute([$class['id']]);
$responses = $responses->fetchAll(PDO::FETCH_ASSOC);

$names = array_column($students, 'full_name', 'id');

// Вопросы
$questions = [
    'q2_choices' => 'Учиться вместе',
    'q3_choices' => 'День рождения',
    'q4_choices' => 'Поход'
];

// Заголовки для Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"sociometry_{$class['class_code']}.xls\"");
header("Cache-Control: max-age=0");

echo "<html><head><meta charset='UTF-8'></head><body>";

foreach ($questions as $q => $title) {
    echo "<h3>{$title}</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>ФИО</th>";
    foreach ($names as $fio) {
        echo "<th>".htmlspecialchars($fio)."</th>";
    }
    echo "</tr>";

    foreach ($names as $student_id => $fio) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($fio)."</td>";

        foreach ($names as $target_id => $target_fio) {
            $mark = '';
            foreach ($responses as $r) {
                if ($r['student_id'] == $student_id) {
                    $choices = json_decode($r[$q], true) ?? [];
                    if (in_array($target_id, $choices)) {
                        $mark = 'Х';
                    }
                }
            }
            echo "<td style='text-align:center;'>$mark</td>";
        }
        echo "</tr>";
    }
    echo "</table><br><br>";
}

echo "</body></html>";
exit;
