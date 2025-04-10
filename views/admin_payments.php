<?php
require_once '../model/server.php';
$connector = new Connector();

// Database query for payments
$sql = "SELECT p.payment_id, p.name, p.amount, p.date_of_payment, p.status, 
    p.reference_number, p.proof_of_payment, p.pay_method_id,
    pm.payment_method
    FROM payments p
    LEFT JOIN pay_method pm ON p.pay_method_id = pm.method_id";
$payments = $connector->getConnection()->prepare($sql);
$payments->execute();
$payments = $payments->fetchAll(PDO::FETCH_ASSOC);

include 'nav/admin_sidebar.php'; 
?>

<!-- CSS Styles -->
<style>
    /* Card Styles */
    .modern-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    background: #ffffff;
    margin: 1rem;
    padding: 4.5rem;
    max-width: 1600px;
    padding-bottom: 5rem;
    }

    /* Table Styles */
    .modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
    }

    .modern-table th {
    background: #f8fafc;
    color: #1e293b;
    font-weight: 600;
    padding: 1rem;
    text-transform: uppercase;
    font-size: 0.85rem;
    border-bottom: 2px solid #e2e8f0;
    }

    .modern-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    }

    /* Status Badge Styles */
    .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    }

    .status-paid { background: #10b981; color: white; }
    .status-downpayment { background: #f59e0b; color: white; }

    /* Modal Styles */
    .payment-modal .modal-content {
    border-radius: 15px;
    border: none;
    }

    .payment-modal .modal-header {
    background: #f8fafc;
    padding: 1.5rem;
    }

    .payment-modal .modal-body {
    padding: 2rem;
    }

    .payment-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
    }

    .proof-of-payment img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>

<!-- Main Content -->
<div class="content-wrapper">
    <div class="container-fluid">
    <div class="modern-card">
        
        
        <div class="table-responsive">
        <table class="modern-table" id="paymentsTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?php echo $payment['payment_id']; ?></td>
                <td><?php echo date('M. d, Y', strtotime($payment['date_of_payment'])); ?></td>
                <td><?php echo $payment['name']; ?></td>
                <td>₱<?php echo number_format((float)$payment['amount'], 2); ?></td>
                <td><?php echo $payment['payment_method']; ?></td>
                <td>
                <span class="status-badge <?php echo strtolower($payment['status']) === 'paid' ? 'status-paid' : 'status-downpayment'; ?>">
                    <?php echo $payment['status']; ?>
                </span>
                </td>
                <td>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#paymentModal<?php echo $payment['payment_id']; ?>">
                    <i class="fas fa-eye"></i> View
                </button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</div>

<!-- Payment Modals -->
<?php foreach ($payments as $payment): ?>
<div class="modal fade payment-modal" id="paymentModal<?php echo $payment['payment_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Payment Details #<?php echo $payment['payment_id']; ?></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
        <div class="payment-details">
            <div class="details-info">
            <h6 class="mb-4">Payment Information</h6>
            <p><strong>Customer:</strong> <?php echo $payment['name']; ?></p>
            <p><strong>Amount:</strong> ₱<?php echo number_format((float)$payment['amount'], 2); ?></p>
            <p><strong>Method:</strong> <?php echo $payment['payment_method']; ?></p>
            <p><strong>Date:</strong> <?php echo $payment['date_of_payment']; ?></p>
            <p><strong>Status:</strong> <?php echo $payment['status']; ?></p>
            <p><strong>Reference:</strong> <?php echo $payment['reference_number']; ?></p>
            </div>
            <div class="proof-of-payment">
            <h6 class="mb-4">Proof of Payment</h6>
            <img src="../images/<?php echo $payment['proof_of_payment']; ?>" alt="Proof of Payment">
            </div>
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
    $('#paymentsTable').DataTable({
        responsive: true,
        order: [[0, 'desc']]
    });
    });
</script>
