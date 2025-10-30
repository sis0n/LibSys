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
            font-weight: bold;
            color: #666;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .date-range {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-top: -5px;
            margin-bottom: 20px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1>Library Report</h1>
    
    <!-- Date Range Display -->
    <p style="font-size: 14px; margin-bottom: 5px;">
        This is your generated report based on the selected date range:
    </p>
    <br>
    <div class="date-range">30/10/2025 - 05/11/2025</div>

    <!-- FIRST ROW -->
    <div style="display: flex; justify-content: space-between; gap: 20px;">
        <!-- Library Resources -->
        <div style="width: 48%;">
            <h2>Library Resources</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th>YEAR</th>
                        <th>TITLE</th>
                        <th>VOLUME</th>
                        <th>PROCESSED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>2026</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>2027</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deleted Books -->
        <div style="width: 48%;">
            <h2>Deleted Books</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th>Count</th>
                        <th>Today</th>
                        <th>Month</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SECOND ROW -->
    <div style="display: flex; justify-content: space-between; gap: 20px; margin-top: 20px;">
        <!-- Circulated Books -->
        <div style="width: 48%;">
            <h2>Circulated Books</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th>Category</th>
                        <th>Today</th>
                        <th>Week</th>
                        <th>Month</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Student</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Faculty</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Staff</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Library Visit (by Department) -->
        <div style="width: 48%;">
            <h2>Library Visit (by Department)</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <thead>
                    <tr style="background-color: #f5f5f5;">
                        <th>Department</th>
                        <th>Today</th>
                        <th>Week</th>
                        <th>Month</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CBA</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>CCJE</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>CLAS</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>COE</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>COEngr</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>LAW</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>GS</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>FACULTY</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>STAFF</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
