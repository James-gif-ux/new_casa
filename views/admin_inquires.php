<?php include 'nav/admin_sidebar.php'; ?>
<style>
    .container{
        border: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        margin: 0 auto;
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
<div class="container mt-6 d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded-3" style="width: 90%;">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-headset me-2 fa-bounce"></i>
                    Customer Support Inquiries
                </h4>
                <span class="badge bg-light text-primary">Total: 10</span>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="text-center">#ID</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Message</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">a</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    james
                                    <span></span>
                                </div>
                            </td>
                            <td>a</td>
                            <td class="text-truncate" style="max-width: 200px;">a</td>
                            <td><small class="text-muted">2023-10-20</small></td>
                            <td>
                                <span class="badge bg-warning text-dark rounded-pill px-3">
                                    <i class="fas fa-clock me-1"></i> Pending
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-primary" onclick="viewInquiry()" data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" onclick="replyInquiry()" data-bs-toggle="tooltip" title="Reply">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
