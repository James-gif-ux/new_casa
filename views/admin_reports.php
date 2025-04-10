<?php  
include 'nav/admin_sidebar.php';
require_once '../model/server.php';
$connector = new Connector();

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_month = $_POST['month_pick'];
    $start_date = date('Y-m-01', strtotime($selected_month));
    $end_date = date('Y-m-t', strtotime($selected_month));

    // Get total checked out and paid bookings
    $sql = "SELECT COUNT(*) as total FROM reservations r
            LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
            WHERE r.status = 'checked out' 
            AND p.status = 'paid'
            AND DATE(r.checkin) BETWEEN ? AND ?";
    $result = $connector->executeQuery($sql, [$start_date, $end_date]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $totalBookings = $row['total'];

    // Get total payments from paid bookings
    $sql = "SELECT COALESCE(SUM(p.amount), 0) as total_payments 
            FROM reservations r
            LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
            WHERE r.status = 'checked out' 
            AND p.status = 'paid'
            AND DATE(r.checkin) BETWEEN ? AND ?";
    $result = $connector->executeQuery($sql, [$start_date, $end_date]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $totalPayments = floatval($row['total_payments']);

    // Update the detailed statistics query
    $sql = "SELECT 
                DATE(r.checkin) as date,
                r.name as guest_name,
                r.email,
                r.phone,
                DATE_FORMAT(r.checkin, '%M %d, %Y') as checkin_date,
                DATE_FORMAT(r.checkout, '%M %d, %Y') as checkout_date,
                TIME_FORMAT(t.time_in, '%h:%i %p') as time_in,
                TIME_FORMAT(t.time_out, '%h:%i %p') as time_out,
                s.services_name,
                COALESCE(s.services_price, 0) as services_price,
                COALESCE(p.amount, 0) as payment_amount,
                p.status as payment_status
            FROM reservations r
            LEFT JOIN time_tb t ON r.reservation_id = t.time_reservation_id
            LEFT JOIN services_tb s ON r.res_services_id = s.services_id
            LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
            WHERE r.status = 'checked out' 
            AND p.status = 'paid'
            AND DATE(r.checkin) BETWEEN ? AND ?
            ORDER BY r.checkin DESC";
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

    @media print {
        body * {
            visibility: hidden;
        }
        .stats-table, .stats-table * {
            visibility: visible;
        }
        .stats-table {
            position: absolute;
            left: 27%;
            top: 0;
            transform: translateX(-50%);
            margin: 0 auto;
            width: 100%;
        }
        .print-button {
            display: none;
        }
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
                <h4>Total Checked Out Bookings</h4>
                <h2><?php echo isset($totalBookings) ? $totalBookings : 0; ?></h2>
            </div>
            <div class="report-card">
                <h4>Total Revenue</h4>
                <h2>₱<?php echo number_format(isset($totalPayments) ? $totalPayments : 0, 2); ?></h2>
            </div>
        </div>

        <table class="stats-table">
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Contact Info</th>
                    <th>Check In</th>
                    <th>Time In</th>
                    <th>Check Out</th>
                    <th>Time Out</th>
                    <th>Service</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($results) && !empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo $row['guest_name']; ?></td>
                            <td>
                                Email: <?php echo $row['email']; ?><br>
                                Phone: <?php echo $row['phone']; ?>
                            </td>
                            <td><?php echo $row['checkin_date']; ?></td>
                            <td><?php echo $row['time_in'] ?? 'Not recorded'; ?></td>
                            <td><?php echo $row['checkout_date']; ?></td>
                            <td><?php echo $row['time_out'] ?? 'Not recorded'; ?></td>
                            <td>
                                <?php echo $row['services_name']; ?><br>
                                ₱<?php echo number_format($row['services_price'], 2); ?>
                            </td>
                            <td>₱<?php echo number_format($row['payment_amount'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No checked out bookings for this period</td>
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

<?php include 'nav/admin_footer.php'; ?>
