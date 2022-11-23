<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../functions/pet_adopter_analytics.php');
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
        <?php require_once('../partials/pet_adopter_aside.php'); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <?php
                            $pet_adopter_login_id = mysqli_real_escape_string($mysqli, $_SESSION['login_id']);
                            $ret = "SELECT * FROM login l
                            INNER JOIN pet_adopter pa ON pa.pet_adopter_login_id  = l.login_id
                            WHERE login_id = '{$pet_adopter_login_id}'";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->execute(); //ok
                            $res = $stmt->get_result();
                            while ($user = $res->fetch_object()) {
                            ?>
                                <h1 class="m-0">Hey, <?php echo $user->pet_adopter_name; ?></h1>
                            <?php } ?>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Pet Adopter Dashboard </li>
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
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php echo $available_pets; ?></h3>
                                    <p>Available Pets</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cat"></i>
                                </div>
                                <a href="pet_adopter_pets" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php echo $adopted_pets; ?></h3>
                                    <p> Adoptions</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-paw"></i>
                                </div>
                                <a href="pet_adopter_adoptions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        

                        <div class="col-lg-4 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Ksh <?php echo number_format($expenditure, 2); ?></h3>
                                    <p>Expenditure</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                                <a href="pet_adopter_payments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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