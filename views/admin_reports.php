<?php  include 'nav/admin_sidebar.php';?>

<style>
    .dashboard-container {
        padding: 20px;
        padding-top: 100px;
        background: #f8f9fa;
        min-height: 100vh;
    }
    .report-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .report-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .report-card:hover {
        transform: translateY(-5px);
    }
    .report-card h4 {
        color: #6c757d;
        font-size: 16px;
        margin-bottom: 10px;
    }
    .report-card h2 {
        color: #212529;
        font-size: 24px;
    }
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .stats-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .stats-table th {
        background: #1f283e;
        color: white;
        padding: 15px;
    }
    .stats-table td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
    }
    .print-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #1f283e;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .print-button:hover {
        background: #2a3652;
    }
    #reportResults {
        display: none;
    }
    #reportResults.show {
        display: block;
    }
</style>

<div class="dashboard-container">
    <div class="report-header">
        <div class="date-selector">
            <form method="POST" id="reportForm" class="d-flex">
                <input type="month" class="form-control" name="month_pick" 
                       value="<?php echo date('Y-m'); ?>">
                <button type="submit" class="btn btn-primary ms-2">Generate</button>
            </form>
        </div>
    </div>

    <div id="reportResults" <?php echo ($_SERVER["REQUEST_METHOD"] == "POST") ? 'class="show"' : ''; ?>>
        <div class="report-grid">
            <div class="report-card">
                <h4>Total Bookings</h4>
                <h2><?php echo isset($totalBookings) ? $totalBookings : 0; ?></h2>
            </div>
            <div class="report-card">
                <h4>Completed Bookings</h4>
                <h2><?php echo isset($completedBookings) ? $completedBookings : 0; ?></h2>
            </div>
            <div class="report-card">
                <h4>Total Revenue</h4>
                <h2>₱<?php echo isset($totalRevenue) ? number_format($totalRevenue, 2) : '0.00'; ?></h2>
            </div>
        </div>

        <table class="stats-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Bookings</th>
                    <th>Completed</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($results) && !empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['bookings']; ?></td>
                            <td><?php echo $row['completed']; ?></td>
                            <td>₱<?php echo number_format($row['revenue'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No data available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <button onclick="window.print()" class="print-button">
            <i class="fas fa-print"></i>
        </button>
    </div>
</div>
