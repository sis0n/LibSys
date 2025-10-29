<!DOCTYPE html>
<html>

<head>
    <title>Library Report</title>
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 20px;
        color: #333;
    }

    h1,
    p {
        text-align: center;
        color: #444;
    }

    h2 {
        font-size: 20px;
        font-weight: bold;
        margin-top: 30px;
        margin-bottom: 15px;
        color: #555;
        text-align: center;
    }

    table {
        width: 50%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: #fff;
        margin-left: auto;
        margin-right: auto;
    }

    th,
    td {
        border: 1px solid #e0e0e0;
        padding: 8px 12px;
        text-align: center;
    }

    th {
        background-color: #fff346ff;
        /* Soft gray/beige */
        font-weight: bold;
        color: #666;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }
    </style>
</head>

<body>
    <h1>Library Report</h1>
    <p style="font-size: 14px; margin-bottom: 15px;">
        This is your generated report based on the selected date range:
    </p>


    <div style="display: flex; justify-content: space-between; gap: 20px;">
        <!-- Library Resources -->
        <div style="width: 48%;">
            <h2>Library Resources</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th style="border: 1px solid #ccc; padding: 6px;">YEAR</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">TITLE</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">VOLUME</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">PROCESSED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">2025</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">2026</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">2027</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Circulated Books -->
        <div style="width: 48%;">
            <h2>Circulated Books</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th style="border: 1px solid #ccc; padding: 6px;">Category</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Today</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Week</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Month</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">Student</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">Faculty</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">Staff</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">TOTAL</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div style="display: flex; justify-content:center; gap: 20px; margin-top: 20px;">

        <!-- Library Visit (by Department) -->
        <div style="width: 48%;">
            <h2>Library Visit (by Department)</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th style="border: 1px solid #ccc; padding: 6px;">Department</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Today</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Week</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Month</th>
                        <th style="border: 1px solid #ccc; padding: 6px;">Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">CBA</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">CCJE</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">CLAS</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">COE</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">COEngr</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">LAW</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">GS</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">FACULTY</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">STAFF</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>

                    <!-- PHP dynamic generation for future scalability -->
                    <?php
            /*
            foreach ($departments as $dept) {
                echo "<tr>";
                echo "<td style='border: 1px solid #ccc; padding: 6px;'>" . htmlspecialchars($dept['name']) . "</td>";
                echo "<td style='border: 1px solid #ccc; padding: 6px;'>" . htmlspecialchars($dept['today']) . "</td>";
                echo "<td style='border: 1px solid #ccc; padding: 6px;'>" . htmlspecialchars($dept['week']) . "</td>";
                echo "<td style='border: 1px solid #ccc; padding: 6px;'>" . htmlspecialchars($dept['month']) . "</td>";
                echo "<td style='border: 1px solid #ccc; padding: 6px;'>" . htmlspecialchars($dept['year']) . "</td>";
                echo "</tr>";
            }
            */
            ?>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 6px;">TOTAL</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                        <td style="border: 1px solid #ccc; padding: 6px;">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



</body>

</html>