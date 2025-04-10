<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>CASA MARCOS ADMIN</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>

    <!-- Fonts and icons -->
    <script src="../assets/js/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["../assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css");
    </style>
  </head>
<body>
   <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar sidebar-style-2" data-background-color="dark">
        <div class="sidebar-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
                <a href="../pages/admin.php?sub_page=admin" class="logo">
                    <img src="../images/casa.png" 
                         alt="navbar brand" 
                         class="navbar-brand"
                         style="width: 100%; top: 0px; left: 0px; height: auto; position: relative;" />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
            <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin' ? 'active' : ''; ?>">
                        <a href="../pages/admin.php?sub_page=admin">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo (isset($_GET['sub_page']) && ($_GET['sub_page'] == 'admin_booking' || $_GET['sub_page'] == 'reservedBooking')) ? 'active' : ''; ?>">
                        <a class="collapsed" 
                           data-bs-toggle="collapse" 
                           data-bs-target="#sidebarLayouts" 
                           aria-expanded="false">
                            <i class="fas fa-th-list"></i>
                            <p>Guest</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse" id="sidebarLayouts">
                            <ul class="nav nav-collapse">
                                <li class="<?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_booking' ? 'active' : ''; ?>">
                                    <a href="../pages/admin.php?sub_page=admin_booking">
                                        <span class="sub-item">Approved Bookings</span>
                                    </a>
                                </li>
                                <li class="<?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'reservedBooking' ? 'active' : ''; ?>">
                                    <a href="../pages/admin.php?sub_page=reservedBooking">
                                        <span class="sub-item">Reserved Bookings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_rooms' ? 'active' : ''; ?>">
                        <a href="../pages/admin.php?sub_page=admin_rooms">
                            <i class="fas fa-door-open"></i>
                            <p>Rooms</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_food' ? 'active' : ''; ?>">
                        <a href="../pages/admin.php?sub_page=admin_food">
                            <i class="bi bi-card-checklist"></i>
                            <p>Food Menu</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_reports' ? 'active' : ''; ?>">
                        <a href="../pages/admin.php?sub_page=admin_reports">
                            <i class="bi bi-calendar3"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_payments' ? 'active' : ''; ?>">
                        <a href="../pages/admin.php?sub_page=admin_payments">
                            <i class="bi bi-wallet"></i>
                            <p>Payments</p>
                        </a>
                    </li>
                    <li class="nav-item <?php echo isset($_GET['sub_page']) && $_GET['sub_page'] == 'admin_inquires' ? 'active' : ''; ?>">
                      <a href="../pages/admin.php?sub_page=admin_inquires">
                        <i class="bi bi-envelope-arrow-up"></i>
                        <p>Inquires 
                          <span class="badge badge-danger" style="<?php 
                            require_once('../model/server.php');
                            $connector = new Connector();
                            // Get count of unread messages
                            $sql = "SELECT COUNT(*) as count FROM messages WHERE status = 0";
                            $stmt = $connector->getConnection()->prepare($sql);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo ($row['count'] > 0 ? 'display:inline-block' : 'display:none');
                          ?>">
                            <span>new</span>
                          </span>
                          </span>
                        </p>
                      </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
      <!-- End Sidebar -->

      

<div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="../pages/admin.php?sub_page=admin" class="logo">
                <img
                  src="../images/casa.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  style="width: 100%; top: 0px; left: 0px; height: auto; position: relative;"
                />
              </a>
              <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Search ..."
                    class="form-control"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="messageDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-envelope"></i>
                  </a>
                  <ul
                    class="dropdown-menu messages-notif-box animated fadeIn"
                    aria-labelledby="messageDropdown"
                  >
                    <li>
                      <div
                        class="dropdown-title d-flex justify-content-between align-items-center"
                      >
                        Messages
                        <a href="#" class="small">Mark all as read</a>
                      </div>
                    </li>
                    <li>
                      <div class="message-notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="assets/img/jm_denis.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jimmy Denis</span>
                              <span class="block"> How are you ? </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="assets/img/chadengle.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Chad</span>
                              <span class="block"> Ok, Thanks ! </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="assets/img/mlane.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jhon Doe</span>
                              <span class="block">
                                Ready for the meeting today...
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="assets/img/talha.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Talha</span>
                              <span class="block"> Hi, Apa Kabar ? </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all messages<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Quick Actions</span>
                      <span class="subtitle op-7">Shortcuts</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="far fa-calendar-alt"></i>
                              </div>
                              <span class="text">Calendar</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-map"></i>
                              </div>
                              <span class="text">Maps</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-file-excel"></i>
                              </div>
                              <span class="text">Reports</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-envelope"></i>
                              </div>
                              <span class="text">Emails</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Invoice</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Payments</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret submenu">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#dropdown-item" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="../images/logo.jpg" alt="..." class="avatar-img rounded-circle">
                    </div>
                    <span class="profile-username">
                      <span class="fw-bold">Admin</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" style="border-radius: 10px; box-shadow: 0px 0px 30px 50px rgba(207, 74, 74, 0.5);">
                    <li>
                      <a class="dropdown-item" href="../pages/authentication.php?sub_page=login">
                        <i class="bi bi-power"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>
