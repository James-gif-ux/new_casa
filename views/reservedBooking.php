<?php 
require_once '../model/server.php';
$connector = new Connector();

// Get reservation_id from URL if provided
$reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : null;

$sql = "SELECT r.reservation_id, r.name, r.email, r.phone, r.checkin, r.checkout, 
               r.status, r.res_services_id, r.message, s.services_price, s.services_name,
               t.time_in, t.time_out, p.payment_type, p.amount as amount
        FROM reservations r
        LEFT JOIN services_tb s ON r.res_services_id = s.services_id 
        LEFT JOIN time_tb t ON r.reservation_id = t.time_reservation_id
        LEFT JOIN payments p ON r.reservation_id = p.pay_reservation_id
        WHERE r.status IN ('pending') " . 
        ($reservation_id ? "AND r.reservation_id = :reservation_id " : "") .
        "ORDER BY CASE WHEN r.status = 'pending' THEN 0 ELSE 1 END,
         r.checkin ASC, r.checkout ASC";

$stmt = $connector->getConnection()->prepare($sql);
if ($reservation_id) {
    $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
}
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql ="INSERT INTO time_tb (time_in, time_out, time_reservation_id) VALUES (?, ?, ?)";
$stmt = $connector->getConnection()->prepare($sql);
// Validate and sanitize inputs
$timeIn = isset($_POST['time_in']) ? $_POST['time_in'] : '';
$timeOut = isset($_POST['time_out'])? $_POST['time_out'] : '';
if (!empty($timeIn) && !empty($timeOut)) {
    $stmt->bindParam(1, $timeIn, PDO::PARAM_STR);
    $stmt->bindParam(2, $timeOut, PDO::PARAM_STR);
    $stmt->bindParam(3, $reservationId, PDO::PARAM_INT);
    $stmt->execute();
}

include 'nav/admin_sidebar.php'; 
?>


        <div class="container">
          <div class="mb-3">
              <select id="statusFilter" class="select" style="float: right; margin-right: 70px; margin-top: 22px; border:1px solid gray; padding: 9px; border-radius: 5px;" aria-label="Reserved status selection">
                  <option value="">All Status</option>
                  <option value="Pending">Pending</option>
                  <option value="cancelled">Cancelled</option>
              </select>
          </div>
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Reserved Bookings</h3>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                  <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="exampleTable" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Contact</th>
                              <th>Check In</th>
                              <th>Check Out</th>
                              <th>Payment Type</th>
                              <th>Paid Amount</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tfoot>
                              <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Payment Type</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                          </tfoot>
                          <tbody>
                            <?php foreach ($reservations as $res):?>
                              <tr>
                                <td><?php echo $res['name']?></td>
                                <td><?php echo $res['email']?></td>  <!-- Removed the "1" -->
                                <td><?php echo $res['phone']?></td>
                                <td><?php echo date('M. d, Y', strtotime($res['checkin'])); ?></td>
                                <td><?php echo date('M. d, Y', strtotime($res['checkout'])); ?></td>
                                <td><?php echo $res['payment_type'] ?? 'Not paid'; ?></td>
                                <td><?php echo $res['amount'] ? number_format($res['amount'], 2) : '0.00'; ?></td>
                                <td>
                                  <?php 
                                      $status = strtolower($res['status']);
                                      $bgColor = '';
                                      
                                      switch($status) {
                                        case 'pending':
                                          $bgColor = '#a9f0a7';
                                          break;
                                          case 'approved':
                                              $bgColor = '#07e0f9';
                                              break;
                                          case 'cancelled':
                                              $bgColor = '#f5c6b9';
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
                                  <div class="dropdown">
                                      <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                          <i class="bi bi-three-dots-vertical"></i>
                                      </button>
                                      <ul class="dropdown-menu">
                                          <?php if($res['status'] !== 'approved'): ?>
                                              <li>
                                                <a class="dropdown-item text-secondary" href="#"
                                                  onclick="approveReservation(<?php echo $res['reservation_id']?>)">
                                                    <i class="bi bi-check-circle me-2"></i>Approve
                                                </a>
                                              </li>
                                          <?php endif; ?>
                                          <?php if($res['status'] !== 'cancelled'): ?>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                  onclick="cancelReservation(<?php echo $res['reservation_id']?>)">
                                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                                </a>
                                            </li>
                                          <?php endif; ?>
                                      </ul>
                                  </div>
                              </td>
                            </tr> 
                          <?php endforeach;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
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
          // Initialize DataTable
          var reservedTable = $('#exampleTable').DataTable({
              order: [[6, 'desc']], // Status column index
              language: {
                  search: "Search:"
              }
          });
          
          // Status colors for reference
          const STATUS_COLORS = {
              'pending': '#a9f0a7',
              'cancelled': '#f5c6b9',
              'approved': '#07e0f9'
          };

          // Status filter handler
          $('#statusFilter').on('change', function() {
              let selectedStatus = $(this).val();
              
              // If "All Status" is selected, clear the filter
              if (!selectedStatus) {
                  reservedTable.column(6).search('').draw();
                  return;
              }
              
              // Convert status for searching
              selectedStatus = selectedStatus.toLowerCase();
              
              // Apply filter to status column (index 6)
              reservedTable.column(6).search(selectedStatus).draw();
          });
      });
      </script>
       <script>
          function updateReservationStatus(reservationId, status) {
              return new Promise((resolve, reject) => {
                  $.ajax({
                      url: '../pages/reserve.php',
                      type: 'POST',
                      data: {
                          reservation_id: reservationId,
                          action: status
                      },
                      success: function(response) {
                          try {
                              const result = JSON.parse(response);
                              if(result.success) {
                                  resolve();
                              } else {
                                  reject(result.message || 'Failed to update reservation status');
                              }
                          } catch(e) {
                              reject('Error processing response');
                          }
                      },
                      error: function() {
                          reject('Error connecting to server');
                      }
                  });
              });
          }
      </script>
      
<!-- Add this in the head section or before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Include the email modal layout
    <?php include 'sendMail_layout.php'; ?>
</script>

<script>
function approveReservation(reservationId) {
    Swal.fire({
        title: 'Confirm Approval',
        text: 'Are you sure you want to approve this reservation?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show email modal
            $('#sendMailModal').modal('show');
            
            // Handle email form submission
            $('#emailForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('reservation_id', reservationId);
                
                // Send email
                $.ajax({
                    url: '../send_mail.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#sendMailModal').modal('hide');
                        
                        // After successful email, update status
                        updateReservationStatus(reservationId, 'approved')
                            .then(() => {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Email sent and reservation has been approved',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to update status: ' + error,
                                    icon: 'error'
                                });
                            });
                    },
                    error: function(xhr, status, error) {
                        $('#sendMailModal').modal('hide');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to send email: ' + error,
                            icon: 'error'
                        });
                    }
                });
            });
        }
    });
}
</script>
<script>
function cancelReservation(reservationId) {
    Swal.fire({
        title: 'Confirm Cancellation',
        text: 'Are you sure you want to cancel this reservation?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            updateReservationStatus(reservationId, 'cancelled')
                .then(() => {
                    Swal.fire({
                        title: 'Cancelled!',
                        text: 'The reservation has been cancelled',
                        icon: 'success',
                        confirmButtonColor: '#d33'
                    }).then(() => {
                        location.reload();
                    });
                });
        }
    });
}
</script>

  </body>
</html>

<!-- Then include sendMail_layout.php -->
<?php include 'sendMail_layout.php'; ?>