<?php 
    include '/config/database.php';
    $db = new database();

    $rak = $_GET['rak'];

    $latestUpdate = $db->getLatestUpdateDate($rak);

    error_reporting(0);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <title>RAK <?= $rak ?></title>
        
        <!-- plugins:css -->
        <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="assets/datatables/datatables.min.css" />
        <!-- End plugin css for this page -->
        
        <!-- inject:css -->
        <!-- endinject -->
        
        <!-- Layout styles -->
        <link rel="stylesheet" href="assets/css/style.css">
        <!-- End layout styles -->
        
        <link rel="stylesheet" href="assets/css/custom.css">

        <link rel="shortcut icon" href="assets/images/nds-icon.png" />
    </head>
    <body>
        <!-- container-scroller -->
        <div class="container-scroller">
            <!-- container-wrapper -->
            <div class="content-wrapper">
                <div class="page-header">
                    <h1 class="page-title">
                        <img src="assets/images/nds-icon.png" width="50" alt="">
                        RAK <span class="text-light ms-1 px-3 py-1 rounded" style="background: rgb(84, 106, 253);"><?= $rak ?></span>
                    </h1>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h2 class="font-weight-bold mb-3">
                                    Kapasitas <i class="mdi mdi-view-dashboard mdi-36px float-right"></i>
                                </h2>
                                <h1 class="mb-5"><?php echo $db->getKapasitas($rak)['kapasitas']; ?></h1>
                                <h6 class="card-text">Terakhir ubah : <?= $latestUpdate ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h2 class="font-weight-bold mb-3">
                                    Jumlah Barang <i class="mdi mdi-package-variant-closed mdi-36px float-right"></i>
                                </h2>
                                <h1 class="mb-5"><?php echo $db->getTotalBarang($rak); ?></h1>
                                <h6 class="card-text">Terakhir ubah : <?= $latestUpdate ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image" />
                                <h2 class="font-weight-bold mb-3">
                                    Ruang Kosong <i class="mdi mdi-package-variant mdi-36px float-right"></i>
                                </h2>
                                <h1 class="mb-5"><?php echo $db->getSpaceKosong($rak); ?></h1>
                                <h6 class="card-text">Terakhir ubah : <?= $latestUpdate ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <h3 class="card-title">LIST BARANG</h3>
                        <div class="table-responsive">
                            <table class="table table-light table-bordered datatable pt-3" id="rak-table">
                                <thead>
                                    <tr>
                                        <th class="text-light" style="background:#546afd"> No. </th>
                                        <th class="text-light" style="background:#546afd"> Supplier </th>
                                        <th class="text-light" style="background:#546afd"> Kode Barang </th>
                                        <th class="text-light" style="background:#546afd"> Nama Barang </th>
                                        <th class="text-light" style="background:#546afd"> Job Order </th>
                                        <th class="text-light" style="background:#546afd"> No. Roll </th>
                                        <th class="text-light" style="background:#546afd"> No. Lot </th>
                                        <th class="text-light" style="background:#546afd"> Qty </th>
                                        <th class="text-light" style="background:#546afd"> Unit </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        $progressColor = 'bg-success';
                                        $isiRak = $db->getIsiRak($rak);

                                        foreach($isiRak as $itemRak) :
                                    ?>
                                        <tr>
                                            <td>
                                                <?= $i ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['supplier'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['kode_barang'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['nama_barang'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['job_order'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['roll_no'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['lot_no'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['sisa'] ?>
                                            </td>
                                            <td>
                                                <?= $itemRak['unit'] ?>
                                            </td>
                                        </tr>
                                    <?php 
                                        $i++;
                                        endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-5 d-none">
                    <form action="">
                        <input type="text" class="form-control" name="search" id="search">
                    </form>
                    <?php
                        $i = 1;
                        $isiRak = $db->getIsiRak($rak);

                        foreach($isiRak as $itemRak) :
                    ?>
                        <div class="col-md-4 rak-item">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-borderless" id="rak-table">
                                        <tr>
                                            <td>Supplier</td>
                                            <td>:</td>
                                            <th><?= $itemRak['supplier'] ?></th>
                                        </tr>
                                        <tr>
                                            <td>Kode Barang</td>
                                            <td>:</td>
                                            <th><?= $itemRak['kode_barang'] ?></th>
                                        </tr>
                                        <tr>
                                            <td>Nama Barang</td>
                                            <td>:</td>
                                            <th><?= $itemRak['kode_barang'] ?></th>
                                        </tr>
                                        <tr>
                                            <td>Job Order</td>
                                            <td>:</td>
                                            <th><?= $itemRak['job_order'] ?></th>
                                        </tr>
                                        <tr>
                                            <td>No. Roll</td>
                                            <td>:</td>
                                            <th><?= $itemRak['roll_no'] ?></th>
                                        </tr>
                                        <tr>
                                            <td>No. Lot</td>
                                            <td>:</td>
                                            <th><?= $itemRak['lot_no'] ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php
                        $i++;
                        endforeach;
                    ?>
                </div>
                <!-- content-wrapper ends -->

                <!-- partial:partials/_footer.html -->
                <footer class="footer mt-5">
                    <div class="container-fluid d-flex justify-content-center">
                        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © Nirwana Digital Solution <?=date('Y')?></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
        </div>
        <!-- container-scroller ends -->
        
        <script src="assets/jquery/jquery-3.6.0.min.js"></script>

        <!-- plugins:js -->
        <script src="assets/vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->

        <!-- Plugin js for this page -->
        <script src="assets/vendors/chart.js/Chart.min.js"></script>
        <script src="assets/datatables/datatables.min.js"></script>
        <!-- End plugin js for this page -->
        
        <!-- inject:js -->
        <script src="assets/js/off-canvas.js"></script>
        <script src="assets/js/hoverable-collapse.js"></script>
        <script src="assets/js/misc.js"></script>
        <!-- endinject -->
        
        <!-- Custom js for this page -->
        <script src="assets/js/dashboard.js"></script>
        <script src="assets/js/todolist.js"></script>
        <script>
            $(document).ready( function () {
                $('#rak-table').DataTable();
            } );
        </script>
        <!-- End custom js for this page -->
    </body>
</html>