<?php
/*
 *   Crafted On Sun Oct 02 2022
 *
 * 
 *   www.devlan.co.ke
 *   hello@devlan.co.ke
 *
 *
 *   The Devlan Solutions LTD End User License Agreement
 *   Copyright (c) 2022 Devlan Solutions LTD
 *
 *
 *   1. GRANT OF LICENSE 
 *   Devlan Solutions LTD hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 *   install and activate this system on two separated computers solely for your personal and non-commercial use,
 *   unless you have purchased a commercial license from Devlan Solutions LTD. Sharing this Software with other individuals, 
 *   or allowing other individuals to view the contents of this Software, is in violation of this license.
 *   You may not make the Software available on a network, or in any way provide the Software to multiple users
 *   unless you have first purchased at least a multi-user license from Devlan Solutions LTD.
 *
 *   2. COPYRIGHT 
 *   The Software is owned by Devlan Solutions LTD and protected by copyright law and international copyright treaties. 
 *   You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 *
 *   3. RESTRICTIONS ON USE
 *   You may not, and you may not permit others to
 *   (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 *   (b) modify, distribute, or create derivative works of the Software;
 *   (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 *   otherwise exploit the Software. 
 *
 *
 *   4. TERM
 *   This License is effective until terminated. 
 *   You may terminate it at any time by destroying the Software, together with all copies thereof.
 *   This License will also terminate if you fail to comply with any term or condition of this Agreement.
 *   Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 *
 *   5. NO OTHER WARRANTIES. 
 *   DEVLAN SOLUTIONS LTD  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 *   DEVLAN SOLUTIONS LTD SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 *   EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 *   SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 *   ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 *   INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 *   SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 *   THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 *   HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 *
 *   6. SEVERABILITY
 *   In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 *   affect the validity of the remaining portions of this license.
 *
 *
 *   7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN SOLUTIONS LTD OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 *   CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 *   USE OF THE SOFTWARE, EVEN IF DEVLAN SOLUTIONS LTD HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 *   IN NO EVENT WILL DEVLAN SOLUTIONS LTD  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 *   TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 *
 */
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../helpers/pets.php');
require_once('../partials/head.php');
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once('../partials/nav_bar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once('../partials/aside.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Pets</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Pets</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    <hr>
                    <div class="text-right">
                        <button type="button" data-toggle="modal" data-target="#add_modal" class="btn btn-warning"> Add Pet</button>
                    </div>
                </div><!-- /.container-fluid -->
                <!-- Add Pet -->
                <div class="modal fade fixed-right" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog  modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header align-items-center">
                                <div class="text-center">
                                    <h6 class="mb-0 text-bold"> Add Pet</h6>
                                </div>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" role="form">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="">Pet Owner</label>
                                            <select type="text" required name="pet_owner_id" class="form-control">
                                                <option>Select Pet Owner</option>
                                                <?php
                                                $ret = "SELECT * FROM login l
                                                INNER JOIN pet_owner po ON po.pet_owner_login_id  = l.login_id";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
                                                while ($user = $res->fetch_object()) {
                                                ?>
                                                    <option value="<?php echo $user->pet_owner_id; ?>"><?php echo $user->pet_owner_email; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Pet Type</label>
                                            <input type="text" required name="pet_type" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Breed</label>
                                            <input type="text" required name="pet_breed" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Age</label>
                                            <input type="text" required name="pet_age" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Health Status</label>
                                            <input type="text" required name="pet_health_status" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="">Pet Image</label>
                                            <input type="file" required name="pet_image" class="form-control">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="">Pet description</label>
                                            <textarea type="text" required name="pet_description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" name="add_pet" class="btn btn-warning">Add Pet </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Pet owner -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-center">
                        <form class="form-inline">
                            <input type="text" class="form-control mb-4 mr-sm-2" placeholder="Search Pet" id="Pet_Search" onkeyup="FilterFunction()">
                        </form>
                    </div>

                    <!-- Info boxes -->
                    <div class="row">
                        <?php
                        $ret = "SELECT * FROM pet p INNER JOIN pet_owner po ON p.pet_owner_id = po.pet_owner_id";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->execute(); //ok
                        $res = $stmt->get_result();
                        while ($pet = $res->fetch_object()) {
                        ?>
                            <div class="col-3 Pet_details">
                                <div class="card ">
                                    <img src="../public/img/pets/<?php echo $pet->pet_image; ?>" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title">Type: <?php echo $pet->pet_type; ?> </h5><br>
                                        <h5 class="card-title">Breed: <?php echo $pet->pet_breed; ?></h5><br>
                                        <h5 class="card-title">Age: <?php echo $pet->pet_age; ?></h5><br>
                                        <h5 class="card-title">Health Status: <?php echo $pet->pet_health_status; ?></h5>

                                        <p class="card-text">
                                            <?php echo $pet->pet_description; ?>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <?php if ($pet->pet_adoption_status != 'Adopted') { ?>
                                                <a data-toggle="modal" href="#update_<?php echo $pet->pet_id; ?>" class="badge badge-success"><i class="fas fa-hand-holding-heart"></i> Adopt</a>
                                            <?php } ?>
                                            <a data-toggle="modal" href="#update_<?php echo $pet->pet_id; ?>" class="badge badge-primary"><i class="fas fa-edit"></i> Edit</a>
                                            <a data-toggle="modal" href="#update_image_<?php echo $pet->pet_id; ?>" class="badge badge-warning"><i class="fas fa-image"></i> Edit Image</a>
                                            <a data-toggle="modal" href="#delete_<?php echo $pet->pet_id; ?>" class="badge badge-danger"><i class="fas fa-trash"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php require_once('../partials/footer.php'); ?>

    </div>
    <!-- ./wrapper -->

    <?php require_once('../partials/scripts.php'); ?>
</body>

</html>