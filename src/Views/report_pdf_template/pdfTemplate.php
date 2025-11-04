<?php
// Ensure variables are defined to avoid errors if they are empty
$deletedBooks = !empty($deletedBooks) ? $deletedBooks[0] : ['today' => '-', 'week' => '-', 'month' => '-', 'year' => '-'];
$circulatedBooks = $circulatedBooks ?? [];
$topVisitors = $topVisitors ?? [];
$libraryVisits = $libraryVisits ?? [];
$dateRange = $dateRange ?? [null, null];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        h1 { text-align: center; color: #444; margin-bottom: 10px;}
        h2 { font-size: 16px; font-weight: bold; margin-top: 20px; margin-bottom: 8px; color: #555; text-align: center; background-color: #f2f2f2; padding: 5px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 9px; }
        th, td { border: 1px solid #e0e0e0; padding: 5px 8px; text-align: left; }
        th { background-color: #f8f8f8; font-weight: bold; }
        .date-range { text-align: center; font-size: 11px; color: #555; margin-bottom: 20px; font-style: italic; }
        .total-row td { font-weight: bold; background-color: #f8f8f8; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Library Report</h1>
    <div class="date-range">
        <?php if ($dateRange[0] && $dateRange[1]): ?>
            Report for the period: <?= htmlspecialchars(date('F j, Y', strtotime($dateRange[0]))) ?> to <?= htmlspecialchars(date('F j, Y', strtotime($dateRange[1]))) ?>
        <?php endif; ?>
    </div>

    <h2>Deleted Books</h2>
    <table>
        <thead><tr><th class="text-center">Today</th><th class="text-center">This Week</th><th class="text-center">This Month</th><th class="text-center">Total in Range</th></tr></thead>
        <tbody>
            <tr>
                <td class="text-center"><?= htmlspecialchars($deletedBooks['today']) ?></td>
                <td class="text-center"><?= htmlspecialchars($deletedBooks['week']) ?></td>
                <td class="text-center"><?= htmlspecialchars($deletedBooks['month']) ?></td>
                <td class="text-center"><?= htmlspecialchars($deletedBooks['year']) ?></td>
            </tr>
        </tbody>
    </table>

    <h2>Circulated Books</h2>
    <table>
        <thead><tr><th>Category</th><th class="text-center">Today</th><th class="text-center">This Week</th><th class="text-center">This Month</th><th class="text-center">Total in Range</th></tr></thead>
        <tbody>
            <?php foreach ($circulatedBooks as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['today']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['week']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['month']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['year']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Top 10 Visitors</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Student ID</th>
                <th>Course</th>
                <th class="text-center">Visits</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($topVisitors)): ?>
                <?php foreach ($topVisitors as $index => $visitor): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($visitor['full_name']) ?></td>
                    <td><?= htmlspecialchars($visitor['student_number']) ?></td>
                    <td><?= htmlspecialchars($visitor['course']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($visitor['visits']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">-</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Library Visit (by Department)</h2>
    <table>
        <thead><tr><th>Department</th><th class="text-center">Today</th><th class="text-center">This Week</th><th class="text-center">This Month</th><th class="text-center">Total Visits in Range</th></tr></thead>
        <tbody>
            <?php foreach ($libraryVisits as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['today']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['week']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['month']) ?></td>
                <td class="text-center"><?= htmlspecialchars($row['year']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
