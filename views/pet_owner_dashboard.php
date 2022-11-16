<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../functions/pet_owner_analytics.php');
require_once('../partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../public/img/logo.png" alt="Logo" height="60" width="60">
        </div>

        <!-- Navbar -->
        <?php require_once('../partials/nav_bar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('../partials/pet_owner_aside.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Pet Owner Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Pet Owner Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo $pets; ?></h3>
                                    <p>Registered Pets</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cat"></i>
                                </div>
                                <a href="pet_owner_pets" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo $pet_adoptions; ?></h3>
                                    <p>Successful Adoptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-paw"></i>
                                </div>
                                <a href="pet_owner_adoptions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo $available_pets; ?></h3>
                                    <p>Available Pets</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dog"></i>
                                </div>
                                <a href="pet_owner_pets" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Ksh <?php echo number_format($payment_amount, 2); ?></h3>
                                    <p>Payments</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <a href="pet_owner_payments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <?php
    require_once("../partials/scripts.php");
    ?>
</body>

</html>