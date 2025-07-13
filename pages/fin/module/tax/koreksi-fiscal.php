<?php include '../header2.php' ?>
<style >
    .modal {
      text-align: center;
      padding: 0!important;
  }

  .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
  }

  .modal-dialog {
      display: inline-table;
      width: 700px;
      text-align: left;
      vertical-align: middle;
  }
</style>
<!-- MAIN -->
<div class="col p-4">
    <h4 class="text-center">LIST KOREKSI FISCAL</h4>
    <div class="box">
        <div class="box header">

            <form id="form-data" action="koreksi-fiscal.php" method="post">        
                <div class="form-row">
                   <div class="col-md-3">
                    <label for="nama_supp"><b>COA</b></label>            
                    <select class="form-control selectpicker" name="nama_type" id="nama_type" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                        <option value="ALL" <?php
                        $nama_type = '';
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null;
                        }                 
                        if($nama_type == 'ALL'){
                            $isSelected = ' selected="selected"';
                        }else{
                            $isSelected = '';
                        }
                        echo $isSelected;
                        ?>                
                        >ALL</option>                                 
                        <?php
                        $nama_type ='';
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null;
                        }                 
                        $sql = mysqli_query($conn1,"select id_cmj,CONCAT(id_cmj,'-',nama_cmj) as type,nama_cmj from master_category_mj");
                        while ($row = mysqli_fetch_array($sql)) {
                            $data = $row['id_cmj'];
                            $data2 = $row['nama_cmj'];
                            if($row['id_cmj'] == $_POST['nama_type']){
                                $isSelected = ' selected="selected"';
                            }else{
                                $isSelected = '';

                            }
                            echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                        }?>
                    </select>
                </div>  

                <!-- <div class="col-md-3">
            <label for="nama_supp"><b>Status</b></label>            
              <select class="form-control selectpicker" name="Status" id="Status" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['Status']) ? $_POST['Status']: null;
                }                 
                    if($Status == 'ALL'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>                
                >ALL</option>
                <option value="Draft" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['Status']) ? $_POST['Status']: null;
                }                 
                    if($Status == 'Draft'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Draft</option>
                <option value="Post" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['Status']) ? $_POST['Status']: null;
                }                 
                    if($Status == 'Post'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Post</option>
                <option value="Cancel" <?php
                $Status = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $Status = isset($_POST['Status']) ? $_POST['Status']: null;
                }                 
                    if($Status == 'Cancel'){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo $isSelected;
                ?>
                >Cancel</option>
                </select>
            </div>  -->

            <div class="col-md-2 mb-3"> 
                <label for="start_date"><b>From</b></label>          
                <input type="text" style="font-size: 12px;" class="form-control tanggal" id="start_date" name="start_date" 
                value="<?php
                $start_date ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                 $start_date = date("Y-m-d",strtotime($_POST['start_date']));
             }
             if(!empty($_POST['start_date'])) {
                 echo $_POST['start_date'];
             }
             else{
                 echo date("d-m-Y");
             } ?>" 
             placeholder="Tanggal Awal">
         </div>

         <div class="col-md-2 mb-3"> 
            <label for="end_date"><b>To</b></label>          
            <input type="text" style="font-size: 12px;" class="form-control tanggal" id="end_date" name="end_date" 
            value="<?php
            $end_date ='';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             $end_date = date("Y-m-d",strtotime($_POST['end_date']));
         }
         if(!empty($_POST['end_date'])) {
             echo $_POST['end_date'];
         }
         else{
             echo date("d-m-Y");
         } ?>" 
         placeholder="Tanggal Awal">
     </div>
     <div class="input-group-append col">                                   
        <button  type="submit" id="submit" value=" Search " style="margin-top: 30px; margin-bottom: 15px;margin-right: 15px;border: 0;
        line-height: 1;
        padding: -2px 8px;
        font-size: 1rem;
        text-align: center;
        color: #fff;
        text-shadow: 1px 1px 1px #000;
        border-radius: 6px;
        background-color: rgb(46, 139, 87);"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
        <button type="button" id="reset" value=" Reset " style="margin-top: 30px; margin-bottom: 15px;margin-right: 15px;border: 0;
        line-height: 1;
        padding: -2px 8px;
        font-size: 1rem;
        text-align: center;
        color: #fff;
        text-shadow: 1px 1px 1px #000;
        border-radius: 6px;
        background-color:rgb(250, 69, 1)"><i class="fa fa-repeat" aria-hidden="true"></i> Reset </button>

        <?php
        $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $tanggal_awal = date("Y-m-d",strtotime($start_date));
        $tanggal_akhir = date("Y-m-d",strtotime($end_date)); 
        $tanggal1 = isset($tanggal_awal) ? $tanggal_awal : 0;
        $tanggal2 = isset($tanggal_akhir) ? $tanggal_akhir : 0;
        $kata_awal = date("M",strtotime($start_date));
        $tengah = '_';
        $kata_akhir = date("Y",strtotime($start_date));
        $kata_filter = $kata_awal . $tengah . $kata_akhir;

        if($nama_type == 'ALL'){
            $where = "where tgl_dok BETWEEN '$tanggal_awal' and '$tanggal_akhir' GROUP BY no_dok";
        }else{
           $where = "where tgl_dok BETWEEN '$tanggal_awal' and '$tanggal_akhir' and type_value = '$nama_type' GROUP BY no_dok";  
       }

       if($tanggal2 < $tanggal1){
        echo "";
    }
    else{

        echo '
        <a style="margin-top: 30px;padding-right: 10px;" target="_blank" href="ekspor_koreksi_fiskal.php?where='.$where.'"><button type="button" class="btn btn-success btn-xs" ><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 14px;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>

        ';
    }
    ?> 
</div>                                                            
</div>
<br/>
</div>
</form> 

<?php
$querys = mysqli_query($conn1,"select useraccess.menu as menu,useraccess.username as username, useraccess.fullname as fullname, menurole.id as id from useraccess inner join menurole on menurole.menu = useraccess.menu where username = '$user' and useraccess.menu = 'Acct - Create Memorial Journal'");
$rs = mysqli_fetch_array($querys);
$id = isset($rs['id']) ? $rs['id'] : 0;

if($id == '54'){
    echo '<button id="btncreate" type="button" class="btn-primary btn-xs" style="border-radius: 6%"><span class="fa fa-pencil-square-o"></span> Create</button>&nbsp';
}else{
    echo '';
}


?>
</div>
<div class="box body">
    <div class="row">       
        <div class="col-md-12">


            <table id="datatable" class="table table-striped table-bordered" role="grid" cellspacing="0" width="100%">
                <thead>
                    <tr class="thead-dark">
                        <th style="text-align: center;vertical-align: middle;display: none;"></th>
                        <th style="text-align: center;vertical-align: middle;">Doc Number</th>
                        <th style="text-align: center;vertical-align: middle;">Date</th>
                        <th style="text-align: center;vertical-align: middle;">Type</th>
                        <th style="text-align: center;vertical-align: middle;">COA</th>
                        <th style="text-align: center;vertical-align: middle;">Value</th>
                        <th style="text-align: center;vertical-align: middle;">Description</th>
                        <th style="text-align: center;vertical-align: middle;">Created User</th>
                        <th style="text-align: center;vertical-align: middle;">Action</th>

                    </tr>
                </thead>

                <tbody>
                    <?php
                    $nama_type ='';
                    $start_date ='';
                    $end_date ='';
                    $date_now = date("Y-m-d");                
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
                        $start_date = date("Y-m-d",strtotime($_POST['start_date']));
                        $end_date = date("Y-m-d",strtotime($_POST['end_date']));               
                    }


                    if ($nama_type == 'ALL' ) {
                       $sql = mysqli_query($conn1,"select id,no_dok,tgl_dok,type_value,CONCAT(no_coa,' ',nama_coa) coa , IF(type_value = 'Negatif',val_min,val_plus) amount,upper(deskripsi) deskripsi,status,CONCAT(created_by, ' (', created_date,')') created_user from sb_journal_fiscal where tgl_dok BETWEEN '$start_date' and '$end_date' GROUP BY id");
                   }
                   else{
                    $sql = mysqli_query($conn1,"select id,no_dok,tgl_dok,type_value,CONCAT(no_coa,' ',nama_coa) coa , IF(type_value = 'Negatif',val_min,val_plus) amount,upper(deskripsi) deskripsi,status,CONCAT(created_by, ' (', created_date,')') created_user from sb_journal_fiscal where tgl_dok BETWEEN '$start_date' and '$end_date' and type_value = '$nama_type' GROUP BY id");
                }

                while($row = mysqli_fetch_array($sql)){
                  $status = $row['status'];     
                  if ($row['type_value'] == 'Positif') {
                    $total = number_format($row['amount'],2);
                }else{
                    $total = '('.number_format(abs($row['amount']),2).')';
                }            
                  echo '<tr style="font-size:12px;">
                  <td hidden value = "'.$row['id'].'">'.$row['id'].'</td>
                  <td style="" value = "'.$row['no_dok'].'">'.$row['no_dok'].'</td>
                  <td style="" value = "'.$row['tgl_dok'].'">'.date("d-M-Y",strtotime($row['tgl_dok'])).'</td>
                  <td style="" value = "'.$row['type_value'].'">'.$row['type_value'].'</td>
                  <td style="" value = "'.$row['coa'].'">'.$row['coa'].'</td>
                  <td style=" text-align : right;" value="'.$total.'">'.$total.'</td>
                  <td style="" value = "'.$row['deskripsi'].'">'.$row['deskripsi'].'</td>
                  <td style="" value = "'.$row['created_user'].'">'.$row['created_user'].'</td>
                  <td width="80px;">
                  ';
                  if ($status != 'Cancel') {
                  echo '<a id="delete"><button style="border-radius: 6px" type="button" class="btn-xs btn-danger"><i class="fa fa-undo"aria-hidden="true" style="padding-right: 10px; padding-left: 5px;"> Cancel</i></button></a>';
                  }else{
                    echo '-';
                  }
                  echo '</td>
                  </tr>';
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
    <div class="modal fade" id="mymodal2" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div style="width:450px;" class="modal-dialog modal-md">
            <div style="height: 225px" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
                    <h4 class="modal-title" id="Heading" style="text-align: center;"><b>UPLOAD</b></h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <form method="post" enctype="multipart/form-data" action="proses_upload.php">
                        Pilih File:
                        <input class="form-control" name="fileexcel" type="file" required="required">
                        <br>
                        <button class="btn btn-sm btn-info" type="submit">Submit</button>
                        <a target="_blank" href="format_upload_mj.xls"><button type="button" class="btn btn-warning "><i class="fa fa-file-excel-o" aria-hidden="true"> Format Upload</i></button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalcancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div style="width:550px;" class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" style="color:white;">Confirm Dialog</h4>
        </button>
      </div>
      <div class="modal-body">
        <p id="text_cancel" style="font-size:18px;"></p>
        <input type="hidden" id="txt_nomj" name="txt_nomj">
      </div>
      <div class="modal-footer">
        <button style="border-radius: 6px" type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"> Close </i></button>
        <button style="border-radius: 6px" type="button" class="btn btn-danger" onclick="cancel_mj();"><i class="fa fa-undo"aria-hidden="true"> Cancel</i></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mymodal" data-target="#mymodal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
                <h4 class="modal-title" id="txt_bpb"></h4>
            </div>
            <div class="container">
                <div class="row">
                  <div id="txt_tglbpb" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_no_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
                  <div id="txt_supp" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
<!--           <div id="txt_top" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
  <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div> -->
  <div id="txt_confirm" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
  <!--         <div id="txt_tgl_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>  -->                    
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

<script>
    $(function() {
        $('.selectpicker').selectpicker();
    });
</script>

<script type="text/javascript">
    $("table tbody tr").on("click", "#approve", function(){                 
        var no_mj = $(this).closest('tr').find('td:eq(0)').attr('value');
        var post_user = '<?php echo $user ?>';

        $.ajax({
            type:'POST',
            url:'post_memorialjournal.php',
            data: {'no_mj':no_mj, 'post_user':post_user},
            // close: function(e){
            //     e.preventDefault();
            // },
            success: function(data){                
                alert(data);
                window.location.reload();
                // alert(data);                                              
            },
            error:  function (xhr, ajaxOptions, thrownError) {
             alert(xhr);
         }
     });
    });
</script>

<script type="text/javascript">
    $("table tbody tr").on("click", "#delete", function(){                 
        var id_mj = $(this).closest('tr').find('td:eq(0)').attr('value');
        var no_mj = $(this).closest('tr').find('td:eq(1)').attr('value');
        var no_coa = $(this).closest('tr').find('td:eq(4)').attr('value');
        var total = $(this).closest('tr').find('td:eq(5)').attr('value');
        $('#text_cancel').html('Sure Cancel Fiskal COA Number <b>' + no_coa + ',</b> With Value IDR <b>' + total + ' </b> ?');
        $('#txt_nomj').val(id_mj);
        $('#modalcancel').modal('show');
        });
</script>

<script type="text/javascript">
    function cancel_mj(){
      var no_fiskal = document.getElementById('txt_nomj').value;
      var cancel_user = '<?php echo $user ?>';
        $.ajax({
            type:'POST',
            url:'cancel_koreksi_fiskal.php',
            data: {'no_fiskal':no_fiskal, 'cancel_user':cancel_user},
            // close: function(e){
            //     e.preventDefault();
            // },
            success: function(data){                
                console.log(data);
                alert("Data Berhasil Dicancel");
                window.location.reload();
                // alert(data);
                                                                            
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
            }
        });
    }
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


<script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(0)', function(){                
        $('#mymodal').modal('show');
        var no_mj = $(this).closest('tr').find('td:eq(0)').attr('value');
        var date = $(this).closest('tr').find('td:eq(1)').text();
        var reff = $(this).closest('tr').find('td:eq(2)').attr('value');
        var status = $(this).closest('tr').find('td:eq(6)').attr('value');


        $.ajax({
            type : 'post',
            url : 'ajax_mj.php',
            data : {'no_mj': no_mj},
            success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
}
});         
        //make your ajax call populate items or what even you need
        $('#txt_bpb').html(no_mj);
        $('#txt_tglbpb').html('Date : ' + date + '');
        $('#txt_no_po').html('Type : ' + reff + '');
        $('#txt_supp').html('Status : ' + status + '');
    // $('#txt_top').html('Other Document : ' + oth_doc + '');
    // $('#txt_curr').html('Kas Account : ' + akun + '');        
    // $('#txt_confirm').html('Currency : ' + curr + '');
    // $('#txt_tgl_po').html('Description : ' + desk + '');                    
});

</script>

<script type="text/javascript">
    document.getElementById('btncreate').onclick = function () {
        location.href = "create-koreksi-fiscal.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('btnupload').onclick = function () {
        location.href = "upload-koreksi-fiscal.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('reset').onclick = function () {
        location.href = "koreksi-fiscal.php";
    };
</script>

<!-- <script type="text/javascript">     
    document.getElementById('btnupload').onclick = function (){ 
    // var txt_type = $(this).closest('tr').find('td:eq(4)').attr('value'); 
    // var txt_id = $(this).closest('tr').find('td:eq(0)').attr('value');           
    $('#mymodal2').modal('show');
    // $('#txt_type').val(txt_type);
    // $('#txt_id').val(txt_id);

};

</script> -->

<script>
    function alert_cancel() {
      alert("Memorial Journal Cancel successfully");
      location.reload();
  }
  function alert_approve() {
      alert("Memorial Journal Post successfully");
      location.reload();
  }
</script>

<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->

</body>

</html>