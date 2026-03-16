<?php
include("..\db.php");

$query = "
    SELECT 
        c.id AS class_id,
        c.class_name AS class_name,
        COUNT(r.id) AS answered,
        COUNT(s.id) AS total_students
    FROM classes c
    LEFT JOIN students s ON s.class_id = c.id
    LEFT JOIN sociometry_responses r ON r.student_id = s.id
    GROUP BY c.id, c.class_name
    ORDER BY c.class_name
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет по классам</title>
    <link rel="stylesheet" href="../style.css"> <!-- стили проекта -->
</head>
<body>
<div class="container">
    <h2>Отчет по классам</h2>
    <table class="table">
        <tr>
            <th>№</th>
            <th>Класс</th>
            <th>Количество ответов</th>
            <th>Без ответов</th>
            <th>Всего учеников</th>
            <th>% ответивших</th>
        </tr>
        <?php
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $answered = $row['answered'];
            $total = $row['total_students'];
            $not_answered = $total - $answered;
            $percent = $total > 0 ? round(($answered / $total) * 100, 2) : 0;
            echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['class_name']}</td>
                    <td>{$answered}</td>
                    <td>{$not_answered}</td>
                    <td>{$total}</td>
                    <td>{$percent}%</td>
                  </tr>";
            $i++;
        }
        ?>
    </table>

    <!-- Кнопка экспорта -->
    <div style="margin-top:20px; text-align:center;">
        <form action="export.php" method="post">
            <button type="submit" class="btn">Экспортировать в Excel</button>
        </form>
    </div>
</div>
</body>
</html>
