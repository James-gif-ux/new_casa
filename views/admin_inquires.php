<?php include 'nav/admin_sidebar.php'; ?>

<style>
    .inquiry-container {
        width: 95%;
        margin: 2rem auto;
        padding-top: 70px;
    }
    .inquiry-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .inquiry-header {
        background: linear-gradient(45deg, #2196F3, #1976D2);
        color: white;
        padding: 1.5rem;
        border-radius: 8px 8px 0 0;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center; /* Center align header text */
    }
    .table td {
        vertical-align: middle;
        font-size: 0.9rem;
        text-align: center; /* Center align cell content */
    }
    .action-btn {
        padding: 0.35rem 0.6rem; /* Slightly larger padding */
        margin: 0 0.3rem;
    }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    .action-btn i {
        font-size: 1.1rem; /* Larger icons */
    }
</style>

<div class="inquiry-container">
    <div class="inquiry-card">
        <div class="p-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>James</td>
                            <td>Support</td>
                            <td class="text-truncate" style="max-width: 200px;">Message content</td>
                            <td>2023-10-20</td>
                            <td>
                                <span class="status-badge bg-warning text-dark">
                                    Pending
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger action-btn" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-success action-btn" title="Reply">
                                    <i class="fas fa-reply"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
