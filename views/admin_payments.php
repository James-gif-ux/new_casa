<?php include 'nav/admin_sidebar.php'; ?>

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
</style>

<div class="content-wrapper">
    <section class="content pt-5">
        <div class="container-fluid pt-5">
            <div class="modern-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="modern-table">
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
                                <tr class="table-row-hover">
                                    <td width="5%">1</td>
                                    <td width="15%">2023-10-20</td>
                                    <td width="20%">John Doe</td>
                                    <td width="15%">$100</td>
                                    <td width="15%">Cash</td>
                                    <td width="20%"><span class="status-badge status-paid">Paid</span></td>
                                    <td width="10%">
                                        <div class="action-buttons">
                                            <button class="modern-btn" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="table-row-hover">
                                    <td width="5%">1</td>
                                    <td width="15%">2023-10-20</td>
                                    <td width="20%">John Doe</td>
                                    <td width="15%">$100</td>
                                    <td width="15%">Cash</td>
                                    <td width="20%"><span class="status-badge status-downpayment">Downpayment</span></td>
                                    <td width="10%">
                                        <div class="action-buttons">
                                            <button class="modern-btn" title="View">
                                                <i class="fas fa-eye"></i>
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
    </section>
</div>
