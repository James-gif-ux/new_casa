usePointStyle: true,
<?php
  include 'nav/admin_sidebar.php';
  
?>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
              <a href="customers.php" class="text-decoration-none">
                <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-users"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                    <p class="card-category">Customers</p>
                    <h4 class="card-title">0</h4>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
              </a>
              </div>
              <div class="col-sm-6 col-md-3">
              <a href="../pages/admin.php?sub_page=admin_booking" class="text-decoration-none">
                <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-info bubble-shadow-small">
                    <i class="fas fa-user-check"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                    <p class="card-category">Total Bookings</p>
                    <h4 class="card-title">0</h4>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
              </a>
              </div>
              <div class="col-sm-6 col-md-3">
              <a href="../pages/admin.php?sub_page=reservedBooking" class="text-decoration-none">
                <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-success bubble-shadow-small">
                    <i class="fas fa-clock"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                    <p class="card-category">Pending Bookings</p>
                    <h4 class="card-title">0</h4>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
              </a>
              </div>
              <div class="col-sm-6 col-md-3">
              <a href="checkin.php" class="text-decoration-none">
                <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                    <i class="far fa-check-circle"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                    <p class="card-category">Check in</p>
                    <h4 class="card-title">0</h4>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
              </a>
              </div>
            </div>
<div class="row">
  <!-- Booking Statistics Card -->
  <div class="col-md-8">
    <div class="card card-round shadow-lg hover-shadow-xl transition-shadow">
      <div class="card-header bg-gradient-to-r from-blue-500 to-blue-700 text-black">
        <div class="d-flex justify-content-between align-items-center">
          <div class="card-title h5">
            <i class="fas fa-chart-line me-2"></i>Booking Trends
          </div>
          <div class="dropdown">
            <button class="btn btn-link text-black" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown">
              <i class="fas fa-calendar-alt"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
              <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
              <li><a class="dropdown-item" href="#">This Year</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-container" style="min-height: 400px; padding: 20px;">
          <canvas id="bookingStatsChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Revenue Analytics Card -->
  <div class="col-md-4">
    <div class="card card-round shadow-lg hover-shadow-xl transition-shadow">
      <div class="card-header bg-gradient-to-r from-green-500 to-green-700 text-white">
        <div class="d-flex justify-content-between align-items-center">
          <div class="card-title h5">
            <i class="fas fa-chart-bar me-2"></i>Revenue Analytics
          </div>
          <span class="badge bg-white text-success">+12.5%</span>
        </div>
        <div class="card-category text-white-50">Monthly Overview</div>
      </div>
      <div class="card-body">
        <div class="mb-4 mt-3 text-center">
          <h5 class="text-muted">Total Revenue</h5>
          <h2 class="display-4 text-success fw-bold">₱150,000.00</h2>
          <p class="text-muted">vs ₱133,500.00 last month</p>
        </div>
        <div class="revenue-chart-container" style="height: 250px;">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include required libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon"></script>

<script>
  <?php
    // Get your data from database here
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
    $customers = [65, 59, 80, 81, 56, 55, 40]; // Replace with actual customer data
    $bookings = [28, 48, 40, 19, 86, 27, 90];  // Replace with actual booking data
    $checkin = [20, 35, 30, 15, 70, 25, 80];  // Replace with actual check-in data
  ?>

  // Booking Statistics Chart - Line Chart
  new Chart(document.getElementById('bookingStatsChart'), {
    type: 'line',
    data: {
      labels: <?php echo json_encode($months); ?>,
      datasets: [{
        label: 'Total Customers',
        data: <?php echo json_encode($customers); ?>,
        borderColor: 'rgba(21, 114, 232, 1)',
        backgroundColor: 'rgba(21, 114, 232, 0.1)',
        fill: true,
        tension: 0.4
      }, {
        label: 'Total Bookings',
        data: <?php echo json_encode($bookings); ?>,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.1)',
        fill: true,
        tension: 0.4
      }, {
        label: 'Check-in',
        data: <?php echo json_encode($checkin); ?>,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.1)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: {
        intersect: false,
        mode: 'index'
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            padding: 20,
            font: {
              size: 12
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: {
            size: 14
          },
          bodyFont: {
            size: 13
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            color: 'rgba(0, 0, 0, 0.1)'
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });

  // Revenue Chart remains the same
  new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
      labels: ['Room Service', 'Accommodation', 'Restaurant', 'Events'],
      datasets: [{
        label: 'Revenue',
        data: [12500, 45000, 25000, 15000],
        backgroundColor: [
          'rgba(21, 114, 232, 0.8)',
          'rgba(46, 184, 92, 0.8)',
          'rgba(255, 169, 31, 0.8)',
          'rgba(153, 102, 255, 0.8)'
        ],
        borderColor: '#ffffff',
        borderWidth: 2,
        borderRadius: 5,
        barThickness: 25
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          callbacks: {
            label: function(context) {
              return `₱${context.raw.toLocaleString()}`;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            drawBorder: false,
            color: 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            callback: function(value) {
              return '₱' + value.toLocaleString();
            }
          }
        },
        x: {
          grid: {
            display: false
          }
        }
      }
    }
  });
</script>
          </div>
        </div>

<?php include 'nav/admin_footer.php'; ?>