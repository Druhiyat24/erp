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
    <h4 class="text-center">UPDATE JOURNAL</h4>
    <div class="box">
        <div class="box header">

            <form id="form-data" action="edit-journal.php" method="post">        
                <div class="form-row">
                 <div class="col-md-3">
                    <label for="nama_supp"><b>Type Journal</b></label>            
                    <select class="form-control selectpicker" name="nama_type" id="nama_type" data-dropup-auto="false" data-live-search="true" onchange="this.form.submit()">
                        <option value="ALL" selected="selected">ALL</option>                                 
                        <?php
                        $nama_type ='';
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null;
                        }   

                        $sql = mysqli_query($conn1,"select DISTINCT type_journal from sb_list_journal where type_journal != ''");
                        while ($row = mysqli_fetch_array($sql)) {
                            $data = $row['type_journal'];
                            $data2 = strtoupper($row['type_journal']);
                            if($row['type_journal'] == $_POST['nama_type']){
                                $isSelected = ' selected="selected"';
                            }else{
                                $isSelected = '';

                            }
                            echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                        }?>
                    </select>
                </div>  

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

</div>                                                            
</div>
<br/>
</div>
</form> 

</div>
<div class="box body">
    <div class="row">       
        <div class="col-md-12">

            <table id="datatable" class="table table-striped table-bordered" role="grid" cellspacing="0" width="100%">
                <thead>
                    <tr class="thead-dark">
                        <th style="text-align: center;vertical-align: middle;width: 12%;">No Journal</th>
                        <th style="text-align: center;vertical-align: middle;width: 10%;">Date</th>
                        <th style="text-align: center;vertical-align: middle;width: 12%;">Type</th>
                        <th style="text-align: center;vertical-align: middle;width: 6%;">curr</th>
                        <th style="text-align: center;vertical-align: middle;width: 12%;">Debit</th>
                        <th style="text-align: center;vertical-align: middle;width: 12%;">Credit</th>
                        <th style="text-align: center;vertical-align: middle;width: 26%;">Description</th>
                        <th style="text-align: center;vertical-align: middle;width: 10%;">Action</th>

                    </tr>
                </thead>

                <tbody>
                    <?php
                    $nama_type ='';
                    $Status = '';
                    $start_date ='';
                    $end_date ='';
                    $date_now = date("Y-m-d");                
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $nama_type = isset($_POST['nama_type']) ? $_POST['nama_type']: null; 
                        $start_date = date("Y-m-d",strtotime($_POST['start_date']));
                        $end_date = date("Y-m-d",strtotime($_POST['end_date']));               
                    }
                    if ($nama_type == 'ALL') {
                       $sql = mysqli_query($conn1,"select no_journal, tgl_journal, type_journal, curr, sum(debit) debit, sum(credit) credit, keterangan from sb_list_journal where tgl_journal between '$start_date' and '$end_date' and tgl_journal != '0000-00-00' GROUP BY no_journal order by tgl_journal asc");
                   }else{
                    $sql = mysqli_query($conn1,"select no_journal, tgl_journal, type_journal, curr, sum(debit) debit, sum(credit) credit, keterangan from sb_list_journal where type_journal = '$nama_type' and tgl_journal between '$start_date' and '$end_date' and tgl_journal != '0000-00-00' GROUP BY no_journal order by tgl_journal asc ");
                }

                while($row = mysqli_fetch_array($sql)){
                  echo '<tr style="font-size:12px;text-align:left;">
                  <td style="" value = "'.$row['no_journal'].'">'.$row['no_journal'].'</td>
                  <td style="" value = "'.$row['tgl_journal'].'">'.date("d-M-Y",strtotime($row['tgl_journal'])).'</td>
                  <td style="" value = "'.$row['type_journal'].'">'.$row['type_journal'].'</td>
                  <td style="" value = "'.$row['curr'].'">'.$row['curr'].'</td>
                  <td style=" text-align : right;" value="'.$row['debit'].'">'.number_format($row['debit'],2).'</td>
                  <td style=" text-align : right;" value="'.$row['credit'].'">'.number_format($row['credit'],2).'</td>
                  <td style="" value = "'.$row['keterangan'].'">'.$row['keterangan'].'</td>';

                  echo '<td style="text-align: center;">
          <div style="display: flex; justify-content: center; gap: 5px;">
              <a id="delete">
                  <button style="border-radius: 6px;" type="button" class="btn-xs btn-danger">
                      <i class="fa fa-trash" aria-hidden="true"></i> Delete
                  </button>
              </a>
              <a href="edit-list-journal.php?no_mj='.base64_encode($row['no_journal']).'">
                  <button style="border-radius: 6px;" type="button" class="btn-xs btn-warning">
                      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                  </button>
              </a>
          </div>
      </td>';


             echo '</tr>';
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


<!--  <div class="form-row">
    <div class="modal fade" id="modalcancel" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div style="width:450px;" class="modal-dialog modal-md">
        <div style="height: 225px" class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="Heading" style="text-align: center;"><b>UPLOAD</b></h4>
        </div>
          <div class="modal-body">
          <div class="form-group">
            <form method="post">
                <button class="btn btn-sm btn-info" type="submit">Submit</button>
                <a target="_blank" href="format_upload_mj.xls"><button type="button" class="btn btn-warning "><i class="fa fa-file-excel-o" aria-hidden="true"> Format Upload</i></button></a>
            </form>
        </div>
      </div>
    </div>
  </div>
</div> -->


<!-- Modal -->
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
    <button style="border-radius: 6px" type="button" class="btn btn-danger" onclick="cancel_mj();"><i class="fa fa-trash"aria-hidden="true"> Delete</i></button>
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
        var no_mj = $(this).closest('tr').find('td:eq(0)').attr('value');
        $('#text_cancel').html('Sure Delete Journal Number <b>' + no_mj + '</b> ?');
        $('#txt_nomj').val(no_mj);
        $('#modalcancel').modal('show');
    });
</script>

<script type="text/javascript">
    function cancel_mj(){
      var no_mj = document.getElementById('txt_nomj').value;
      var cancel_user = '<?php echo $user ?>';
      $.ajax({
        type:'POST',
        url:'cancel_memorialjournal.php',
        data: {'no_mj':no_mj, 'cancel_user':cancel_user},
            // close: function(e){
            //     e.preventDefault();
            // },
            success: function(data){                
                console.log(data);
                window.location.reload();
                // alert(data);

            },
            error:  function (xhr, ajaxOptions, thrownError) {
             alert(xhr);
         }
     });
  }
</script>

<!-- <script type="text/javascript">
    $("table tbody tr").on("click", "#delete", function(){                 
        var no_mj = $(this).closest('tr').find('td:eq(0)').attr('value');
        var cancel_user = '<?php echo $user ?>';
        $.ajax({
            type:'POST',
            url:'cancel_memorialjournal.php',
            data: {'no_mj':no_mj, 'cancel_user':cancel_user},
            // close: function(e){
            //     e.preventDefault();
            // },
            success: function(data){                
                console.log(data);
                window.location.reload();
                // alert(data);
                                                                            
            },
            error:  function (xhr, ajaxOptions, thrownError) {
               alert(xhr);
            }
        });
        });
    </script> -->

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
        location.href = "create-edit-journal.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('btnupload').onclick = function () {
        location.href = "upload-edit-journal.php";
    };
</script>

<script type="text/javascript">
    document.getElementById('reset').onclick = function () {
        location.href = "edit-journal.php";
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