<?php
require_once '../model/server.php';
$connector = new Connector();

// Update the SQL query to get all payment information with payment method
$sql = "SELECT p.payment_id, p.name, p.amount, p.date_of_payment, p.status, 
        p.reference_number, p.proof_of_payment, p.pay_method_id,
        pm.payment_method
        FROM payments p
        LEFT JOIN pay_method pm ON p.pay_method_id = pm.method_id";
$payments = $connector->getConnection()->prepare($sql);
$payments->execute();
$payments = $payments->fetchAll(PDO::FETCH_ASSOC);

// Handle proof of payment upload
if (isset($_FILES['proof_of_payment'])) {
    $proof = $_FILES['proof_of_payment']['name'];
    $target = "../images/" . basename($proof);
    
    // Create directory if it doesn't exist
    if (!file_exists("../images/")) {
        mkdir("../images/", true);
    }
    
    // Delete old proof if exists
    if (!empty($current_proof) && file_exists("../images/" . $current_proof)) {
        unlink("../images/" . $current_proof);
    }
    
    // Move uploaded file
    if (move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $target)) {
        $current_proof = $proof;
    }
}

include 'nav/admin_sidebar.php'; 
?>

<style>
    .modern-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        background: #ffffff;
        overflow: hidden;
        max-width: 1600px;
        margin: 20px auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 9px;
        margin: 0;
    }

    .modern-table thead tr {
        background: #f8fafc;
    }

    .modern-table th {
        color: #1e293b;
        font-weight: 600;
        padding: 1.5rem;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        text-align: left;
    }

    .modern-table td {
        padding: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .modern-table tr:first-child td {
        border-top: 1px solid #e2e8f0;
    }

    .table-row-hover:hover td {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .status-badge {
        padding: 0.5rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-paid {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
    }

    .status-downpayment {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: white;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 0.8rem;
    }

    .modern-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: linear-gradient(45deg, #3b82f6, #2563eb);
        color: white;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        filter: brightness(110%);
    }
    .modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        border-radius: 0 0 15px 15px;
        padding: 1.5rem;
    }

    .modal-body img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .img-fluid{
        max-height: 250px;
        width: 200px;
    }

</style>

<div class="content-wrapper">
    <section class="content pt-5">
        <div class="container-fluid pt-5">
            <div class="modern-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Add these in the head section -->
                        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
                        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
                        
                        <!-- Modify the table by adding an ID -->
                        <table class="modern-table" id="paymentsTable">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="15%">Date</th>
                                    <th width="25%">Customer</th>
                                    <th width="15%">Amount</th>
                                    <th width="15%">Method</th>
                                    <th width="15%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment):?>
                                    <tr class="table-row-hover">
                                        <td width="5%"><?php echo $payment['payment_id']; ?></td>
                                        <td width="15%"><?php echo date('M. d, Y', strtotime($payment['date_of_payment'])); ?></td>
                                        <td width="20%"><?php echo $payment['name']; ?></td>
                                        <td width="15%">₱<?php echo number_format((float)$payment['amount'], 2); ?></td>
                                        <td width="15%"><?php echo $payment['payment_method']; ?></td>
                                        <td width="20%"><span class="status-badge <?php echo strtolower($payment['status']) === 'paid' ? 'status-paid' : 'status-downpayment'; ?>"><?php echo $payment['status']; ?></span></td>
                                        <td width="10%">
                                            <div class="action-buttons">
                                                <button type="button" class="modern-btn" title="View" data-toggle="modal" data-target="#paymentModal<?php echo $payment['payment_id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        <!-- After the table section -->
                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Modals Section -->
<?php foreach ($payments as $payment): ?>
<div class="modal fade" id="paymentModal<?php echo $payment['payment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel<?php echo $payment['payment_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel<?php echo $payment['payment_id']; ?>">Payment Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Payment ID:</strong> <?php echo $payment['payment_id']; ?></p>
                        <p><strong>Customer Name:</strong> <?php echo $payment['name']; ?></p>
                        <p><strong>Amount:</strong> ₱<?php echo number_format((float)$payment['amount'], 2); ?></p>
                        <p><strong>Payment Method:</strong> <?php echo $payment['payment_method']; ?></p>
                        <p><strong>Date of Payment:</strong> <?php echo $payment['date_of_payment']; ?></p>
                        <p><strong>Status:</strong> <?php echo $payment['status']; ?></p>
                        <p><strong>Reference Number:</strong> <?php echo $payment['reference_number']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Proof of Payment</h6>
                        <img src="../images/<?php echo $payment['proof_of_payment']; ?>" class="img-fluid" alt="Proof of Payment">
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

<!-- Add this style for the modal -->
<style>

</style>

<!-- Make sure these scripts are included at the end of your file -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Add these scripts before the closing body tag, after jQuery and Bootstrap -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            responsive: true,
            "order": [[0, "desc"]], // Sort by ID descending
            "pageLength": 10, // Records per page
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Search:",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            },
            "columnDefs": [
                {
                    "targets": -1, // Last column (Action)
                    "orderable": false // Disable sorting for action column
                }
            ]
        });
    });
</script>

<!-- Add this style -->
<style>
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(45deg, #3b82f6, #2563eb);
        border: none;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
</style>
