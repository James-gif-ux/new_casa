<?php include 'nav/admin_sidebar.php'; ?>

<style>
.card {
    border: none;
    margin-top: 50px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 60px;
}



.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.table td {
    font-size: 0.95rem;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.badge {
    padding: 8px 12px;
    font-size: 0.85rem;
}
</style>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Payment Records</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">Payment ID</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Payment Method</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-middle">
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">$</td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success">
                                    Paid
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm rounded-pill shadow-sm mx-1" onclick="viewPayment()">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-primary btn-sm rounded-pill shadow-sm mx-1" onclick="editPayment()">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        <tr class="align-middle">
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">$</td>
                            <td class="text-center"></td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-secondary">
                                   Deposit
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm rounded-pill shadow-sm mx-1" onclick="viewPayment()">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-primary btn-sm rounded-pill shadow-sm mx-1" onclick="editPayment()">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
