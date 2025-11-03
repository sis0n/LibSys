<?php
// Ensure variables are defined to avoid errors if they are empty
$libraryResources = $libraryResources ?? [];
$deletedBooks = $deletedBooks[0] ?? ['today' => 0, 'week' => 0, 'month' => 0, 'year' => 0];
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

    <h2>Library Resources</h2>
    <table>
        <thead><tr><th>YEAR</th><th>TITLE</th><th>VOLUME</th><th>PROCESSED</th></tr></thead>
        <tbody>
            <?php foreach ($libraryResources as $row): ?>
            <tr><td><?= htmlspecialchars($row['year']) ?></td><td><?= htmlspecialchars($row['title']) ?></td><td><?= htmlspecialchars($row['volume']) ?></td><td><?= htmlspecialchars($row['processed']) ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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
