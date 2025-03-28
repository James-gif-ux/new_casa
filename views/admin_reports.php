<?php  
include 'nav/admin_sidebar.php';
require_once '../model/server.php';
$connector = new Connector();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_month = $_POST['month_pick'];
    $start_date = date('Y-m-01', strtotime($selected_month));
    $end_date = date('Y-m-t', strtotime($selected_month));

    // Get total bookings
    $sql = "SELECT COUNT(*) as total FROM reservations 
            WHERE DATE(checkin) BETWEEN ? AND ?";
    $result = $connector->executeQuery($sql, [$start_date, $end_date]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $totalBookings = $row['total'];

    // Get completed bookings
    $sql = "SELECT COUNT(*) as completed FROM reservations 
            WHERE status = 'approved' AND DATE(checkin) BETWEEN ? AND ?";
    $result = $connector->executeQuery($sql, [$start_date, $end_date]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $completedBookings = $row['completed'];

    // Get total payments
    $sql = "SELECT SUM(amount) as total_payments FROM payments 
            WHERE DATE(date_of_payment) BETWEEN ? AND ?";
    $result = $connector->executeQuery($sql, [$start_date, $end_date]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $totalPayments = $row['total_payments'] ?: 0;

    // Get daily statistics
    $sql = "SELECT 
                DATE(r.checkin) as date,
                COUNT(r.reservation_id) as bookings,
                SUM(CASE WHEN r.status = 'approved' THEN 1 ELSE 0 END) as completed,
                COALESCE(SUM(p.amount), 0) as daily_payments
            FROM reservations r
            LEFT JOIN payments p ON DATE(r.checkin) = DATE(p.date_of_payment)
            WHERE DATE(r.checkin) BETWEEN ? AND ?
            GROUP BY DATE(r.checkin)
            ORDER BY date DESC";
    $results = $connector->executeQuery($sql, [$start_date, $end_date])->fetchAll(PDO::FETCH_ASSOC);
}
?>

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
                <h4>Total Payments</h4>
                <h2>₱<?php echo number_format(isset($totalPayments) ? $totalPayments : 0, 2); ?></h2>
            </div>
        </div>

        <table class="stats-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Bookings</th>
                    <th>Completed</th>
                    <th>Payments</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($results) && !empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo $row['bookings']; ?></td>
                            <td><?php echo $row['completed']; ?></td>
                            <td>₱<?php echo number_format($row['daily_payments'], 2); ?></td>
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

<script>
document.getElementById('reportForm').addEventListener('submit', function(e) {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    `;
    loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
    document.body.appendChild(loadingOverlay);
});
</script>
