<?php include 'nav/admin_sidebar.php'; ?>

<style>
.payment-card {
    margin: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.payment-table {
    width: 100%;
    margin-bottom: 0;
}

.payment-table th {
    background-color: #f8f9fa;
    font-weight: 500;
    padding: 1rem;
    font-size: 0.9rem;
}

.payment-table td {
    padding: 0.75rem;
    vertical-align: middle;
}

.action-btn {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    margin: 0 0.25rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}
</style>

<div class="container py-4">
    <div class="payment-card">
        <div class="card-body">
            <table class="payment-table table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#001</td>
                        <td>2023-10-20</td>
                        <td>John Doe</td>
                        <td>$100</td>
                        <td>Credit Card</td>
                        <td>
                            <span class="status-badge bg-success text-white">Paid</span>
                        </td>
                        <td>
                            <button class="action-btn btn btn-outline-info" onclick="viewPayment()">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn btn-outline-primary" onclick="editPayment()">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>#002</td>
                        <td>2023-10-21</td>
                        <td>Jane Smith</td>
                        <td>$150</td>
                        <td>Bank Transfer</td>
                        <td>
                            <span class="status-badge bg-secondary text-white">Deposit</span>
                        </td>
                        <td>
                            <button class="action-btn btn btn-outline-info" onclick="viewPayment()">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn btn btn-outline-primary" onclick="editPayment()">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
