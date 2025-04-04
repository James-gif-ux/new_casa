
<?php
  include 'nav/admin_sidebar.php';
  require_once '../model/server.php';
$connector = new Connector();
// Get booking statistics
$bookingStats = "SELECT 
    COUNT(*) as total_reservations,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_bookings,
    SUM(CASE WHEN status = 'checked in' THEN 1 ELSE 0 END) as checkedin,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
    FROM reservations";

try {
    $statsStmt = $connector->getConnection()->prepare($bookingStats);
    $statsStmt->execute();
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Booking statistics error: " . $e->getMessage());
    $stats = ['total_reservations' => 0, 'approved_bookings' => 0, 'checkedin' => 0, 'pending' => 0];
}
?>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
              <a  class="text-decoration-none">
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
                    <h4 class="card-title"><?php echo $stats['total_reservations']; ?></h4>
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
                    <h4 class="card-title"><?php echo $stats['approved_bookings']; ?></h4>
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
                    <h4 class="card-title"><?php echo $stats['pending']; ?></h4>
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
                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                    <i class="far fa-check-circle"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                    <p class="card-category">Check in</p>
                    <h4 class="card-title"><?php echo $stats['checkedin']; ?></h4>
                    </div>
                  </div>
                  </div>
                </div>
                </div>
              </a>
              </div>
            </div>
<?php
require_once '../model/server.php';
$connector = new Connector();
// Get booking statistics
$bookingStats = "SELECT 
    COUNT(*) as total_reservations,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_bookings,
    SUM(CASE WHEN status = 'checked in' THEN 1 ELSE 0 END) as checkedin
    FROM reservations";

try {
    $statsStmt = $connector->getConnection()->prepare($bookingStats);
    $statsStmt->execute();
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Booking statistics error: " . $e->getMessage());
    $stats = ['total_reservations' => 0, 'approved_bookings' => 0, 'checkedin' => 0];
}
?>

<div class="container-fluid px-4">
  <div class="row g-4">
    <!-- Booking Statistics Card -->
    <div class="col-lg-7">
      <div class="card h-100 shadow-sm">
        <div class="card-header bg-primary bg-gradient text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="fas fa-chart-line me-2"></i>Booking Statistics
            </h5>
          </div>
        </div>
        <div class="card-body p-4">
          <div class="chart-container" style="height: 400px;">
            <canvas id="bookingStatsChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Revenue Analytics Card -->
    <div class="col-lg-5">
      <div class="card h-100 shadow-sm">
        <div class="card-header bg-success bg-gradient text-white py-3">
          <?php
            require_once '../model/server.php';
            $connector = new Connector();

            $sql = "SELECT 
                      SUM(p.amount) as total_revenue,
                      (SELECT SUM(amount) 
                       FROM payments 
                       WHERE MONTH(date_of_payment) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                      ) as last_month_revenue
                    FROM payments p
                    JOIN reservations r ON p.pay_reservation_id = r.reservation_id
                    WHERE r.payment_status IN ('paid', 'partially_paid')";
            
            try {
                $stmt = $connector->getConnection()->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $totalRevenue = $result['total_revenue'] ?? 0;
                $lastMonthRevenue = $result['last_month_revenue'] ?? 0;
                
                $percentChange = 0;
                if ($lastMonthRevenue > 0) {
                    $percentChange = (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
                }
            } catch (PDOException $e) {
                error_log("Revenue calculation error: " . $e->getMessage());
                $totalRevenue = 0;
                $percentChange = 0;
            }
          ?>
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="fas fa-hotel me-2"></i>Accommodation Revenue
            </h5>
            <span class="badge bg-white text-<?php echo $percentChange >= 0 ? 'success' : 'danger'; ?>">
              <?php echo ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 1); ?>%
            </span>
          </div>
          <div class="text-white-50 small">Monthly Performance</div>
        </div>

        <div class="card-body p-4">
          <div class="text-center mb-4">
            <div class="mb-3">
              <i class="fas fa-coins text-success" style="font-size: 2rem;"></i>
            </div>
            <h6 class="text-muted">Total Revenue</h6>
            <h3 class="text-success fw-bold">₱<?php echo number_format($totalRevenue, 2); ?></h3>
            <div class="d-flex justify-content-center align-items-center small">
              <i class="fas fa-<?php echo $percentChange >= 0 ? 'arrow-up text-success' : 'arrow-down text-danger'; ?> me-2"></i>
              <span class="text-<?php echo $percentChange >= 0 ? 'success' : 'danger'; ?> fw-bold">
                <?php echo ($percentChange >= 0 ? '+' : '') . number_format($percentChange, 1); ?>%
              </span> 
              <span class="text-muted ms-1">vs last month</span>
            </div>
          </div>
          
          <div class="chart-container" style="height: 250px;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Charts Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon"></script>

<script>
  <?php
    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
    $customers = [65, 59, 80, 81, 56, 55, 40];
    $bookings = [28, 48, 40, 19, 86, 27, 90];
    $checkin = [20, 35, 30, 15, 70, 25, 80];
  ?>

  // Booking Statistics Chart
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
            padding: 15,
            font: { size: 11 }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 10,
          titleFont: { size: 13 },
          bodyFont: { size: 12 }
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
          grid: { display: false }
        }
      }
    }
  });

  // Revenue Chart
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Monthly Revenue',
        data: [35000, 38000, 40000, 42000, 40000, 45000],
        fill: true,
        borderColor: 'rgba(46, 184, 92, 1)',
        backgroundColor: 'rgba(46, 184, 92, 0.2)',
        tension: 0.4,
        pointRadius: 3,
        pointBackgroundColor: 'rgba(46, 184, 92, 1)',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleFont: { size: 13, weight: 'bold' },
          bodyFont: { size: 12 },
          padding: 12,
          callbacks: {
            label: function(context) {
              return `Revenue: ₱${context.raw.toLocaleString()}`;
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
            font: { size: 10 },
            callback: function(value) {
              return '₱' + (value/1000) + 'k';
            }
          }
        },
        x: {
          grid: { display: false },
          ticks: {
            font: { size: 10 }
          }
        }
      }
    }
  });
</script>
          </div>
        </div>

<?php include 'nav/admin_footer.php'; ?>