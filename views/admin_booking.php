
<?php
require_once '../model/server.php';
$connector = new Connector();

// Remove the duplicate SQL query and update the JOIN query
$sql = "SELECT r.reservation_id, r.name, r.email, r.phone, r.checkin, r.checkout, 
               r.status, r.res_services_id, s.services_price, s.services_name 
        FROM reservations r
        LEFT JOIN services_tb s ON r.res_services_id = s.services_id 
        WHERE r.status IN ('approved', 'checked in', 'checked out')";
$stmt = $connector->getConnection()->prepare($sql);  
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);




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
                              <th>Number</th>
                              <th>Check In</th>
                              <th>Check Out</th>
                              <th>Amount</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tfoot>
                              <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                              </tr>
                          </tfoot>
                          <tbody>
                            <?php foreach ($reservations as $res) : ?>
                              <tr>
                              <td><?php echo $res['name']?></td>
                              <td><?php echo $res['email']?></td>
                              <td><?php echo $res['phone']?></td>
                              <td><?php echo date('M. d, Y', strtotime($res['checkin'])); ?></td>
                              <td><?php echo date('M. d, Y', strtotime($res['checkout'])); ?></td>
                              <td><?php echo $res['services_price']?></td>
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
                                          case 'checked out':
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
                                <button type="button" class="btn p-0 hide-arrow" data-bs-toggle="dropdown">
                                  <i class="bi bi-three-dots-vertical p-2"></i>
                                </button>
                                <div class="dropdown-menu">
                                  <a href="javascript:void(0)" 
                                     class="dropdown-item" 
                                     style="color:rgb(8, 160, 165);"
                                     onclick="updateReservationStatus(<?php echo $res['reservation_id']?>, 'checkin')">
                                      <i class="bi bi-box-arrow-in-left"></i> Check In
                                  </a>
                                  <a href="javascript:void(0)" 
                                     class="dropdown-item"
                                     style="color:rgb(179, 115, 12);" 
                                     onclick="updateReservationStatus(<?php echo $res['reservation_id']?>, 'checkout')">
                                      <i class="bi bi-box-arrow-in-right"></i> Check Out
                                  </a>
                                </div>
                              </td>
                            </tr>
                            <?php endforeach; ?>
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
    </body>
</html>
