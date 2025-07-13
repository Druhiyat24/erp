<?php include '../header2.php' ?>

<!-- MAIN -->
<div class="col p-4">
    <h4 class="text-center">MAPPING EXPLANATION</h4>
    <div class="box">
        <div class="box header">

            <form id="form-data" action="mapping-explanation.php" method="post">        
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="kategori"><b>Kategori</b></label>            
                        <select class="form-control selectpicker" name="kategori" id="kategori" data-dropup-auto="false" data-live-search="true">
                            <option value="ALL" selected="true">ALL</option>                                                
                            <?php
                            $kategori ='';
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $kategori = isset($_POST['kategori']) ? $_POST['kategori']: null;
                            }                 
                            $sql = mysqli_query($conn2,"SELECT DISTINCT upper(kategori) kategori,upper(kategori_show) kategori_show FROM sb_kategori_laporan WHERE keterangan = 'EXPLANATION'");
                            while ($row = mysqli_fetch_array($sql)) {
                                $data = $row['kategori_show'];
                                if($row['kategori_show'] == $_POST['kategori']){
                                    $isSelected = ' selected="selected"';
                                }else{
                                    $isSelected = '';
                                }
                                echo '<option value="'.$data.'"'.$isSelected.'">'. $data .'</option>';    
                            }?>
                        </select>
                    </div>



                <!-- <div class="col-md-3">
            <label for="status"><b>Status</b></label>            
              <select class="form-control selectpicker" name="status" id="status" data-dropup-auto="false" data-live-search="true">
                <option value="ALL" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'ALL'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>                
                >ALL</option>
                <option value="Active" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'Active'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Active</option>
                <option value="Deactive" <?php
                $status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $status = isset($_POST['status']) ? $_POST['status']: null;
                }                 
                    if($status == 'Deactive'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Deactive</option>                                                                                                             
                </select>
            </div> -->

            <div class="input-group-append col">                                   
                <button type="submit" id="submit" value=" Search " style="margin-top: 30px; margin-bottom: 5px;margin-right: 15px;border: 0;
                line-height: 1;
                padding: -2px 8px;
                font-size: 1rem;
                text-align: center;
                color: #fff;
                text-shadow: 1px 1px 1px #000;
                border-radius: 6px;
                background-color: rgb(46, 139, 87);"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                <button type="button" id="reset" value=" Reset " style="margin-top: 30px; margin-bottom: 5px;margin-right: 15px;border: 0;
                line-height: 1;
                padding: -2px 8px;
                font-size: 1rem;
                text-align: center;
                color: #fff;
                text-shadow: 1px 1px 1px #000;
                border-radius: 6px;
                background-color:rgb(250, 69, 1)"><i class="fa fa-repeat" aria-hidden="true"></i> Reset </button>

<!--     <?php
        $status = isset($_POST['status']) ? $_POST['status']: null;

        if($status == 'ALL'){
            echo '<a target="_blank" href="ekspor_lp_all.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>';
        }elseif($status == 'draft'){
            echo '<a target="_blank" href="ekspor_lp_draft.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        }elseif($status == 'Approved'){
            echo '<a target="_blank" href="ekspor_lp_app.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>';
        }elseif($status == 'Cancel'){
            echo '<a target="_blank" href="ekspor_lp_cancel.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        }elseif($status == 'Closed'){
            echo '<a target="_blank" href="ekspor_lp_closed.php?nama_supp='.$nama_supp.' && status='.$status.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>'; 
        }else{
            $filterr = ""; 
        }
    ?>  -->
</div>                                                            
</div>
<br/>
</div>
</form> 

<button id="btncreate5" type="button" class="btn-primary btn-xs" style="border-radius: 6%"><span class="fa fa-pencil-square-o"></span>Create</button>
</div>
<div class="box body">
    <div class="row">       
        <div class="col-md-12">


            <table id="datatable" class="table table-striped table-bordered" role="grid" cellspacing="0" width="100%">
                <thead>
                    <tr class="thead-dark">
                        <th style="text-align: center;vertical-align: middle;">Kategori</th>
                        <th style="text-align: center;vertical-align: middle;">Sub Kategori</th>
                        <th style="text-align: center;vertical-align: middle;">Type Report</th>
                        <!-- <th style="text-align: center;vertical-align: middle;">Action</th> -->

                    </tr>
                </thead>

                <tbody>
                    <?php
                    $kategori ='';
                    $status = '';
                    $curren = '';
                    $start_date ='';
                    $end_date ='';
                    $date_now = date("Y-m-d");                
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $kategori = isset($_POST['kategori']) ? $_POST['kategori']: null;              
                    }
                    if($kategori == 'ALL'){
                     $sql = mysqli_query($conn2,"SELECT upper(kategori_show) kategori_show,upper(sub_kategori)sub_kategori,keterangan FROM sb_kategori_laporan WHERE keterangan = 'EXPLANATION' order by id asc");
                 }else{
                    $sql = mysqli_query($conn2,"SELECT upper(kategori_show) kategori_show,upper(sub_kategori)sub_kategori,keterangan FROM sb_kategori_laporan WHERE keterangan = 'EXPLANATION' and kategori = '$kategori ' order by id asc");
                }
                while($row = mysqli_fetch_array($sql)){
                    if (!empty($row)) {

                        echo '<tr style="font-size:12px;text-align:left;">
                        <td value = "'.$row['kategori_show'].'">'.$row['kategori_show'].'</td>
                        <td value = "'.$row['sub_kategori'].'">'.$row['sub_kategori'].'</td>
                        <td value = "'.$row['keterangan'].'">'.$row['keterangan'].'</td>';

                    }
                }?>
            </tbody>                    
        </table>

    </div>
</div>
</div>
</div><!-- body-row END -->
</div>
</div>

<div class="form-row">
    <div class="modal fade" id="mymodal5" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div style="width:450px;" class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
                    <h4 class="modal-title" id="Heading">FORM ADD KATEGORI</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <form id="modal-form5" method="post">
                        <div class="form-row">
                            <div class="col-md-12 mb-3"> 
                                <label for="nama_supp"><b>Kategori</b></label> 
                                <select class="form-control selectpicker" name="m_kategori" id="m_kategori" data-dropup-auto="false" data-live-search="true">
                                        <option value="" disabled selected="true">Select Kategori</option>                                                
                                        <?php
                                        $m_kategori ='';
                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            $m_kategori = isset($_POST['m_kategori']) ? $_POST['m_kategori']: null;
                                        }                 
                                        $sql = mysqli_query($conn2,"SELECT DISTINCT kategori,upper(kategori_show) kategori_show FROM sb_kategori_laporan WHERE keterangan = 'EXPLANATION'");
                                        while ($row = mysqli_fetch_array($sql)) {
                                            $data = $row['kategori'];
                                            $data2 = $row['kategori_show'];
                                            if($row['kategori'] == $_POST['m_kategori']){
                                                $isSelected = ' selected="selected"';
                                            }else{
                                                $isSelected = '';
                                            }
                                            echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                                        }?>
                                    </select>                                               
                            </div>
                        </div>
                        <div class="form-row">
                         <div class="col-md-12 mb-3"> 
                            <label for="nama_supp"><b>Sub Kategori</b></label> 
                            <input type="text" style="font-size: 14px;font-weight: bold;" class="form-control" name="m_subkategori" id="m_subkategori" autocomplete="off" >
                        </div>
                        <div class="col-md-12 mb-3"> 
                            <label for="nama_supp"><b>Type Report</b></label> 
                            <input type="text" style="font-size: 14px;font-weight: bold;" class="form-control" name="type_report" id="type_report" value="EXPLANATION" autocomplete="off" readonly>
                        </div>
                    </br>
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4">
                        <div class="modal-footer">
                            <button type="submit" id="send5" name="send5" class="btn btn-success btn-md" style="width: 100%;"><span class="fa fa-check"></span>
                                Save
                            </button>
                        </div>
                    </div>
                </div>           
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>       

<div class="modal fade" id="mymodallistpayment" data-target="#mymodallistpayment" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
                <h4 class="modal-title" id="txt_list_payment"></h4>
            </div>
            <div class="container">
                <div class="row">
                  <div id="txt_tgl_list_payment" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_nama_supp" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>        
                  <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_create_user" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_status" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_keterangan" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>                                                    
                  <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div>          
              </div>
          </div>
      </div>
    <!-- /.modal-content 
  </div>
      /.modal-dialog 
  </div> -->         

</div><!-- body-row END -->
</div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script language="JavaScript" src="../css/4.1.1/datatables.min.js"></script>
<script language="JavaScript" src="../css/4.1.1/bootstrap-datepicker.js"></script>
<script language="JavaScript" src="../css/4.1.1/bootstrap-select.min.js"></script>
<script>
  // Hide submenus
  $('#body-row .collapse').collapse('hide'); 

// Collapse/Expand icon
$('#collapse-icon').addClass('fa-angle-double-left'); 

// Collapse click
$('[data-toggle=sidebar-colapse]').click(function() {
    SidebarCollapse();
});

function SidebarCollapse () {
    $('.menu-collapsed').toggleClass('d-none');
    $('.sidebar-submenu').toggleClass('d-none');
    $('.submenu-icon').toggleClass('d-none');
    $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');
    
    // Treating d-flex/d-none on separators with title
    var SeparatorTitle = $('.sidebar-separator-title');
    if ( SeparatorTitle.hasClass('d-flex') ) {
        SeparatorTitle.removeClass('d-flex');
    } else {
        SeparatorTitle.addClass('d-flex');
    }
    
    // Collapse/Expand icon
    $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
}
</script>
<script>
    $(document).ready(function() {
        $('#datatable').dataTable();

        $("[data-toggle=tooltip]").tooltip();

    } );
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.tanggal').datepicker({
            format: "dd-mm-yyyy",
            autoclose:true
        });
    });
</script>

<script type="text/javascript">     
    $("#btncreate5").on("click", function(){          
        $('#mymodal5').modal('show');
    });

</script>


<script>
    $(function() {
        $('.selectpicker').selectpicker();
    });
</script>

<script type="text/javascript">
    $("table tbody tr").on("click", "#active", function(){                 
        var doc_number = $(this).closest('tr').find('td:eq(0)').attr('value');
        var active_user = '<?php echo $user ?>';

        $.ajax({
            type:'POST',
            url:'activebank.php',
            data: {'doc_number':doc_number, 'active_user':active_user},
            close: function(e){
                e.preventDefault();
            },
            success: function(data){                
                // console.log(data);
                window.location.reload();
                // alert("Active");                                              
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
           }
       });
    });
</script>

<script type="text/javascript">
    $("table tbody tr").on("click", "#deactive", function(){                 
        var doc_number = $(this).closest('tr').find('td:eq(0)').attr('value');
        var active_user = '<?php echo $user ?>';

        $.ajax({
            type:'POST',
            url:'deactivebank.php',
            data: {'doc_number':doc_number, 'active_user':active_user},
            close: function(e){
                e.preventDefault();
            },
            success: function(data){                
                // console.log(data);
                window.location.reload();
                // alert("Deactive");                                              
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
           }
       });
    });
</script>


<!-- <script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(0)', function(){                
    $('#mymodallistpayment').modal('show');
    var no_payment = $(this).closest('tr').find('td:eq(0)').attr('value');
    var tgl_list_payment = $(this).closest('tr').find('td:eq(1)').text();
    var supp = $(this).closest('tr').find('td:eq(2)').attr('value');
    var tgl_tempo = $(this).closest('tr').find('td:eq(10)').text();
    var curr = $(this).closest('tr').find('td:eq(6)').attr('value');
    var create_user = $(this).closest('tr').find('td:eq(7)').attr('value');
    var status = $(this).closest('tr').find('td:eq(8)').attr('value');
    var top = $(this).closest('tr').find('td:eq(9)').attr('value');
    var keterangan = $(this).closest('tr').find('td:eq(16)').attr('value');               

    $.ajax({
    type : 'post',
    url : 'ajaxlistpayment.php',
    data : {'no_payment': no_payment},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_list_payment').html(no_payment);
    $('#txt_tgl_list_payment').html('Tgl List Payment : ' + tgl_list_payment + '');
    $('#txt_nama_supp').html('Supplier : ' + supp + '');
    $('#txt_curr').html('Currency : ' + curr + '');        
    $('#txt_create_user').html('Create By : ' + create_user + '');
    $('#txt_status').html('Status : ' + status + '');
    $('#txt_keterangan').html('Keterangan : ' + keterangan + '');                                        
});

</script> -->

<script type="text/javascript">
    document.getElementById('btncreate').onclick = function () {
        location.href = "create-mapping-explanation.php";
    };
</script>
<script type="text/javascript">
    document.getElementById('reset').onclick = function () {
        location.href = "mapping-explanation.php";
    };
</script>

<script>
    function alert_cancel() {
      alert("Master Bank Deactive");
      location.reload();
  }
  function alert_approve() {
      alert("Master Bank Active");
      location.reload();
  }
</script>

<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->

</body>

</html>