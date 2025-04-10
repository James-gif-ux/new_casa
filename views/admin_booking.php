
<?php
require_once '../model/server.php';
$connector = new Connector();

// Remove the duplicate SQL query and update the JOIN query
// Update the SQL query to include payment information
$sql = "SELECT r.reservation_id, r.name, r.email, r.phone, r.checkin, r.checkout, 
               r.status, r.res_services_id, r.message, s.services_price, s.services_name,
               t.time_in, t.time_out, 
               COALESCE(p.status, 'partial') as payment_status,
               p.amount as paid_amount
        FROM reservations r
        LEFT JOIN services_tb s ON r.res_services_id = s.services_id 
        LEFT JOIN time_tb t ON r.reservation_id = t.time_reservation_id
        LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
        WHERE r.status IN ('approved', 'checked in')      
       ORDER BY 
          CASE 
              WHEN r.status = 'approved' THEN 1
              WHEN r.status = 'checked in' THEN 2
              ELSE 3
          END,
            r.checkin ASC,
            r.reservation_id DESC";
$stmt = $connector->getConnection()->prepare($sql);  
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_in'])) {
  $timeIn = $_POST['time_in'];
  $reservationId = $_POST['reservation_id'];
  
  try {
      // First check if record exists
      $checkSql = "SELECT * FROM time_tb WHERE time_reservation_id = ?";
      $checkStmt = $connector->getConnection()->prepare($checkSql);
      $checkStmt->execute([$reservationId]);
      
      if ($checkStmt->rowCount() > 0) {
          // Update existing record
          $updateSql = "UPDATE time_tb SET time_in = ? WHERE time_reservation_id = ?";
          $stmt = $connector->getConnection()->prepare($updateSql);
          $stmt->execute([$timeIn, $reservationId]);
      } else {
          // Insert new record
          $insertSql = "INSERT INTO time_tb (time_in, time_reservation_id) VALUES (?, ?)";
          $stmt = $connector->getConnection()->prepare($insertSql);
          $stmt->execute([$timeIn, $reservationId]);
      }
      
      // Update reservation status
      $statusSql = "UPDATE reservations SET status = 'checked in' WHERE reservation_id = ?";
      $statusStmt = $connector->getConnection()->prepare($statusSql);
      $statusStmt->execute([$reservationId]);
      
      echo json_encode(['success' => true]);
      exit;
  } catch (PDOException $e) {
      echo json_encode(['success' => false, 'message' => $e->getMessage()]);
      exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_out'])) {
    $timeOut = $_POST['time_out'];
    $reservationId = $_POST['reservation_id'];
    
    try {
        // Check for unpaid balance with SUM of all payments
        $balanceCheckSql = "SELECT s.services_price, COALESCE(SUM(p.amount), 0) as total_paid 
                           FROM reservations r
                           LEFT JOIN services_tb s ON r.res_services_id = s.services_id
                           LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
                           WHERE r.reservation_id = ?
                           GROUP BY r.reservation_id, s.services_price";
        $balanceStmt = $connector->getConnection()->prepare($balanceCheckSql);
        $balanceStmt->execute([$reservationId]);
        $paymentInfo = $balanceStmt->fetch(PDO::FETCH_ASSOC);
        
        $totalAmount = $paymentInfo['services_price'];
        $paidAmount = $paymentInfo['total_paid'];
        $balance = $totalAmount - $paidAmount;

        if ($balance > 0) {
            echo json_encode([
                'success' => false, 
                'needsPayment' => true,
                'totalAmount' => $totalAmount,
                'paidAmount' => $paidAmount,
                'balance' => $balance,
                'message' => 'Cannot proceed with check-out. Outstanding balance: ₱' . number_format($balance, 2)
            ]);
            exit;
        }

        // If no balance, proceed with check-out
        $updateSql = "UPDATE time_tb SET time_out = ? WHERE time_reservation_id = ?";
        $stmt = $connector->getConnection()->prepare($updateSql);
        $stmt->execute([$timeOut, $reservationId]);
        
        $statusSql = "UPDATE reservations SET status = 'checked out' WHERE reservation_id = ?";
        $statusStmt = $connector->getConnection()->prepare($statusSql);
        $statusStmt->execute([$reservationId]);
        
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}


  include 'nav/admin_sidebar.php';
?>
     
        <div class="container">
          <div class="mb-3">
              <select id="statusFilter" class="select" style="float: right; margin-right: 70px; margin-top: 22px; border:1px solid gray; padding: 9px; border-radius: 5px;" aria-label="Booking status selection">
                  <option value="">All Status</option>
                  <option value="Approved">Approved</option>
                  <option value="Check In">Check In</option>
                  <option value="Check Out">Check Out</option>
              </select>
          </div>
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Approved Bookings</h3>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Check In</th>
                              <th>Time In</th>
                              <th>Check Out</th>
                              <th>Amount</th>
                              <th>Payment Status</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tfoot>
                              <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Check In</th>
                                <th>Time In</th>
                                <th>Check Out</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                          </tfoot>
                          <tbody>
                            <?php foreach ($reservations as $res) : ?>
                              <tr>
                              <td><?php echo $res['name']?></td>
                              <td><?php echo $res['email']?></td>
                              <td><?php echo date('M. d, Y', strtotime($res['checkin'])); ?></td>
                              <td>
                                  <?php 
                                      if (!empty($res['time_in'])) {
                                          echo date('h:i A', strtotime($res['time_in']));
                                      } else {
                                          echo '<span class="text-muted">Not set</span>';
                                      }
                                  ?>
                              </td>
                              <td><?php echo date('M. d, Y', strtotime($res['checkout'])); ?></td>
                              <td><?php echo $res['paid_amount'] ? number_format($res['paid_amount'], 2) : '0.00'; ?></td>
                              <td><?php echo $res['payment_status']?></td>
                              <td>
                                  <?php 
                                      $status = strtolower($res['status']);
                                      $bgColor = '';
                                      
                                      switch($status) {
                                          case 'approved':
                                              $bgColor = '#cfb9f6';
                                              break;
                                          case 'checked in':
                                              $bgColor = '#93edf1';
                                              break;
                                          case 'cancelled':
                                              $bgColor = '#f5cc8b';
                                              break;
                                          default:
                                              $bgColor = '#cfb9f6';
                                      }
                                  ?>
                                  <span class="badge me-1 px-2" style="color:#16132a; font-weight:bold; font-size:15px; background-color:<?php echo $bgColor; ?>;">
                                      <?php echo $res['status']?>
                                  </span>
                              </td>
                                <td>
                                    <?php if ($res['status'] !== 'cancelled'): ?>
                                        <button type="button" class="btn p-0 hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical p-2"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <?php if ($res['status'] !== 'checked in'): ?>
                                                <a href="javascript:void(0)" 
                                                    class="dropdown-item" 
                                                    style="color:rgb(8, 160, 165);"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#checkInModal<?php echo $res['reservation_id']?>">
                                                    <i class="bi bi-box-arrow-in-left"></i> Check In
                                                </a>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="cancelReservation(<?php echo $res['reservation_id']?>)">
                                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($res['status'] === 'checked in'): ?>
                                                <a href="javascript:void(0)" 
                                                    class="dropdown-item"
                                                    style="color:rgb(179, 115, 12);" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#checkOutModal<?php echo $res['reservation_id']?>">
                                                    <i class="bi bi-box-arrow-in-right"></i> Check Out
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No actions available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- Check In Modal start -->
                <?php foreach ($reservations as $res) : ?>
                  <div class="modal fade" id="checkInModal<?php echo $res['reservation_id']?>" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="checkInModalLabel">Check In Details</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <form id="checkInForm<?php echo $res['reservation_id']?>">
                                      <input type="hidden" name="reservation_id" value="<?php echo $res['reservation_id']?>">
                                      <div class="mb-3">
                                          <label for="checkInTime" class="form-label">Check In Time</label>
                                          <input type="time" class="form-control" id="checkInTime<?php echo $res['reservation_id']?>" name="time_in" required>
                                      </div>
                                  </form>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-primary" onclick="submitCheckIn(<?php echo $res['reservation_id']?>)">Submit</button>
                              </div>
                          </div>
                      </div>
                  </div>
                <?php endforeach; ?>
                <!-- Check In Modal End -->
                <!------check out modal start------>
                <?php foreach ($reservations as $res) : ?>
                  <div class="modal fade" id="checkOutModal<?php echo $res['reservation_id']?>" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="checkOutModalLabel">Check Out Details</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <!-- In the checkout modal -->
                              <div class="modal-body">
                                  <form id="checkOutForm<?php echo $res['reservation_id']?>">
                                      <input type="hidden" name="reservation_id" value="<?php echo $res['reservation_id']?>">
                                      <div class="mb-3">
                                          <label for="checkOutTime<?php echo $res['reservation_id']?>" class="form-label">Check Out Time</label>
                                          <input type="time" class="form-control" id="checkOutTime<?php echo $res['reservation_id']?>" name="time_out" required>
                                      </div>
                                      <div class="mb-3">
                                          <label for="paymentAmount<?php echo $res['reservation_id']?>" class="form-label">Payment Amount</label>
                                          <input type="number" step="0.01" class="form-control" id="paymentAmount<?php echo $res['reservation_id']?>" name="amount" >
                                      </div>
                                      <div class="mb-3">
                                          <label for="paymentType<?php echo $res['reservation_id']?>" class="form-label">Payment Type</label>
                                          <select class="form-select" id="paymentType<?php echo $res['reservation_id']?>" name="payment_type" >
                                              <option value="">Select payment type</option>
                                              <option value="cash">Cash</option>
                                              <option value="gcash">GCash</option>
                                          </select>
                                      </div>
                                  </form>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-primary" onclick="submitCheckOut(<?php echo $res['reservation_id']?>)">Submit</button>
                              </div>
                          </div>
                      </div>
                  </div>
                <?php endforeach; ?>
                <!-----check out modal end------>
              </div>
            </div>
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2024, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="http://www.themekita.com">ThemeKita</a>
            </div>
            <div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
            </div>
          </div>
        </footer>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <div class="custom-template">
        <div class="title">Settings</div>
        <div class="custom-content">
          <div class="switcher">
            <div class="switch-block">
              <h4>Logo Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="selected changeLogoHeaderColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="selected changeLogoHeaderColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Navbar Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="selected changeTopBarColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Sidebar</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="selected changeSideBarColor"
                  data-color="white"
                ></button>
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="dark2"
                ></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>
    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
    </script>
    <script>
      $(document).ready(function() {
          // Initialize DataTable with correct ID
          var bookingTable = $('#basic-datatables').DataTable();
          
          // Status colors
          const STATUS_COLORS = {
              'approved': '#cfb9f6',
              'check_in': '#93edf1',
              'check_out': '#f5cc8b'
          };

          // Status update handler
          $('.btn-success, .dropdown-item').on('click', function(e) {
              e.preventDefault();
              
              let newStatus;
              let reservationId = $(this).closest('tr').data('reservation-id');
              
              if ($(this).hasClass('btn-success')) {
                  newStatus = 'approved';
              } else {
                  newStatus = $(this).text().trim().toLowerCase().replace(' ', '_');
              }
              
              $.ajax({
                  url: '../ajax/update_booking_status.php',
                  method: 'POST',
                  data: {
                      reservation_id: reservationId,
                      status: newStatus
                  },
                  success: function(response) {
                      response = JSON.parse(response);
                      if (response.success) {
                          // Update status badge
                          const statusBadge = $(`tr[data-booking-id="${reservationId}"] .badge`);
                          statusBadge.css('background-color', STATUS_COLORS[newStatus]);
                          statusBadge.text(newStatus.replace('_', ' ').toUpperCase());
                          
                          // Refresh table
                          bookingTable.draw(false);
                      } else {
                          alert('Error updating status');
                      }
                  }
              });
          });

          // Status filter
          $('#statusFilter').on('change', function() {
              let selectedStatus = $(this).val().toLowerCase();
              bookingTable.column(6).search(selectedStatus).draw();
          });
      });
      </script>
      <script>
          function updateReservationStatus(reservationId, status) {
              if(!confirm('Are you sure you want to ' + status.replace('_', ' ') + ' this booking?')) {
                  return;
              }
              
              $.ajax({
                  url: '../pages/approved.php',
                  type: 'POST',
                  data: {
                    reservation_id: reservationId,
                      action: status
                  },
                  success: function(response) {
                      try {
                          const result = JSON.parse(response);
                          if(result.success) {
                              location.reload();
                          } else {
                              alert(result.message || 'Failed to update booking status');
                          }
                      } catch(e) {
                          alert('Error processing response');
                      }
                  },
                  error: function() {
                      alert('Error connecting to server');
                  }
              });
          }
      </script>
      <script>
        function submitCheckIn(reservationId) {
            const timeIn = document.getElementById('checkInTime' + reservationId).value;
            
            if (!timeIn) {
                alert('Please enter check-in time');
                return;
            }
        
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    reservation_id: reservationId,
                    time_in: timeIn
                },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);
                        if(result.success) {
                            location.reload();
                        } else {
                            alert(result.message || 'Failed to update check-in time');
                        }
                    } catch(e) {
                        alert('Error processing response');
                    }
                },
                error: function() {
                    alert('Error connecting to server');
                }
            });
        }
        </script>
       <script>
        
          function submitCheckOut(reservationId) {
              const timeOut = document.getElementById('checkOutTime' + reservationId).value;
              const amount = document.getElementById('paymentAmount' + reservationId).value;
              const paymentType = document.getElementById('paymentType' + reservationId).value;
              
              if (!timeOut) {
                  Swal.fire({
                      title: 'Error!',
                      text: 'Please enter check-out time',
                      icon: 'error'
                  });
                  return;
              }

              // if (!amount) {
              //     Swal.fire({
              //         title: 'Error!',
              //         text: 'Please enter payment amount',
              //         icon: 'error'
              //     });
              //     return;
              // }

              if (!paymentType) {
                  Swal.fire({
                      title: 'Error!',
                      text: 'Please select payment type',
                      icon: 'error'
                  });
                  return;
              }

              Swal.fire({
                  title: 'Processing...',
                  text: 'Please wait while we process the check-out',
                  allowOutsideClick: false,
                  didOpen: () => {
                      Swal.showLoading();
                  }
              });

              $.ajax({
                  url: '../pages/process_checkout_payment.php',
                  type: 'POST',
                  data: {
                      reservation_id: reservationId,
                      time_out: timeOut,
                      amount: amount,
                      payment_type: paymentType
                  },
                  success: function(response) {
                      try {
                          const result = JSON.parse(response);
                          if (result.success) {
                              Swal.fire({
                                  title: 'Success!',
                                  text: 'Check-out and payment processed successfully',
                                  icon: 'success'
                              }).then(() => {
                                  $('#checkOutModal' + reservationId).modal('hide');
                                  location.reload();
                              });
                          } else {
                              Swal.fire({
                                  title: 'Error!',
                                  text: result.message || 'Failed to process check-out and payment',
                                  icon: 'error'
                              });
                          }
                      } catch(e) {
                          console.error('Parse error:', e);
                          Swal.fire({
                              title: 'Error!',
                              text: 'Error processing response',
                              icon: 'error'
                          });
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('Ajax error:', error);
                      Swal.fire({
                          title: 'Error!',
                          text: 'Error connecting to server',
                          icon: 'error'
                      });
                  }
              });
          }
       </script>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>

