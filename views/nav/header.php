<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CASA MARCOS</title>
    <!-- for icons  -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- bootstrap  -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- for swiper slider  -->
    <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">

    <!-- fancy box  -->
    <link rel="stylesheet" href="../assets/css/jquery.fancybox.min.css">
    <!-- custom css  -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- FullCalendar CSS and JS -->
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="body-fixed">
    <!-- start of header  -->
    <header class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header-logo">
                        <a>
                            <img src="../images/logo.jpg" width="50" height="50" alt="Logo" style="border-radius: 50%;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="main-navigation">
                        <button class="menu-toggle"><span></span><span></span></button>
                        <nav class="header-menu">
                            <ul class="menu food-nav-menu">
                                <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                                <li><a href="home.php" class="<?php echo ($current_page == 'home.php') ? 'active-menu' : ''; ?>">Home</a></li>
                                <li><a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active-menu' : ''; ?>">About</a></li>
                                <li><a href="menu.php" class="<?php echo ($current_page == 'menu.php') ? 'active-menu' : ''; ?>">Food Menu</a></li>
                                <li><a href="rooms.php" class="<?php echo ($current_page == 'rooms.php') ? 'active-menu' : ''; ?>">Our Rooms</a></li>
                                <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active-menu' : ''; ?>">Contact</a></li>
                            </ul>
                        </nav>
                        <div class="header-right">
                            <a href="authentication.php" class="header-btn">
                                <i class="uil uil-user-md"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header ends  -->