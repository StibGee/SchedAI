<?php
include '../database/config.php'; // Include the database connection


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Timetable</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            height: 40px;
            position: relative;
        }
        .occupied {
            border: none; /* Remove the border for occupied cells */
            position: relative;
        }
        .occupied::before {
            content: attr(data-subject);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 2px 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            z-index: 1;
            color: #000;
            white-space: nowrap; /* Prevent wrapping of subject names */
        }
    </style>
</head>
<body>
    <h1>Weekly Timetable</h1>
    <table>
        <thead>
            <tr>
                <th>Interval</th>
                <?php foreach ($days as $day): ?>
                    <th><?php echo htmlspecialchars($day); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($intervals as $interval): ?>
                <tr>
                    <td><?php echo htmlspecialchars($interval); ?></td>
                    <?php foreach ($days as $day): ?>
                        <td 
                            <?php
                            // Apply background color and subject name based on the schedule
                            if (isset($schedule[$day][$interval])) {
                                $subjectData = $schedule[$day][$interval];
                                $colors = array_column($subjectData, 'color');
                                $subjectNames = array_column($subjectData, 'subjectname');
                                $isCenters = array_column($subjectData, 'is_center');
                                $color = $colors[0]; // Use the first color if multiple subjects
                                echo 'style="background-color: ' . htmlspecialchars($color) . ';"';
                                echo ' class="occupied"';
                                echo ' data-subject="' . htmlspecialchars(implode(' and ', array_unique($subjectNames))) . '"';
                            }
                            ?>
                        >
                            <?php
                            // Display subject name only in the center cell
                            if (isset($schedule[$day][$interval])) {
                                foreach ($schedule[$day][$interval] as $data) {
                                    if ($data['is_center']) {
                                        echo htmlspecialchars($data['subjectname']);
                                    }
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
