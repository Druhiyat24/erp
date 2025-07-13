<?php include '../header2.php' ?>

    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">AR REPORT</h4>
<div class="box">
    <div class="box header">

        <form id="form-data" action="report-detail.php" method="post">        
        <div class="form-row">
           <div class="col-md-3 mt-1">
            <label for="nama_supp"><b>Customer</b></label>            
              <select class="form-control selectpicker" name="nama_supp" id="nama_supp" data-dropup-auto="false" data-live-search="true" >
                <option value="ALL" selected="true">ALL</option>                                                
                <?php
                $nama_supp ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $nama_supp = isset($_POST['nama_supp']) ? $_POST['nama_supp']: null;
                }                 
                $sql = mysqli_query($conn1,"select DISTINCT alamat,Id_Supplier, UPPER(Supplier) As Supplier FROM mastersupplier WHERE tipe_sup = 'C' and id_supplier != '1006' order by supplier asc");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['Id_Supplier'];
                    $data2 = $row['Supplier'];
                    if($row['Id_Supplier'] == $_POST['nama_supp']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                }?>
                </select>
                </div>  


            <div class="col-md-2 mb-3 mt-1"> 
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
               echo date("Y-m-d");
            } ?>" 
            placeholder="Tanggal Awal" >
            </div>

            <div class="col-md-2 mb-3 mt-1"> 
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
               echo date("Y-m-d");
            } ?>" 
            placeholder="Tanggal Awal" >
            </div>
            <div class="input-group-append col">                                   
            <input type="button" id="submit" value=" Search " style="margin-top: 30px; margin-bottom: 17px;margin-right: 17px;border: 0;
    line-height: 1;
    padding: -2px 8px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 6px;
    background-color: rgb(46, 139, 87);" onclick="getnamabulan()">
            <button type="button" id="reset" value=" Reset " style="margin-top: 30px; margin-bottom: 17px;margin-right: 17px;border: 0;
    line-height: 1;
    padding: -2px 8px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 6px;
    background-color:rgb(250, 69, 1)"><i class="fa fa-repeat" aria-hidden="true"></i> Reset </button>
    <input type="hidden" class="form-control" id="bln1" name="bln1" readonly>
    <input type="hidden" class="form-control" id="bln2" name="bln2" readonly>
    <input type="hidden" class="form-control" id="bln3" name="bln3" readonly>
    <input type="hidden" class="form-control" id="bln4" name="bln4" readonly>
    <input type="hidden" class="form-control" id="bln5" name="bln5" readonly>
    <input type="hidden" class="form-control" id="bln6" name="bln6" readonly>

<!-- <?php
    $nama_bank = '';
    $curren = isset($_POST['curren']) ? $_POST['curren']: null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $bln1 = isset($_POST['bln1']) ? $_POST['bln1'] : null;
    
            echo '<a target="_blank" href="report_idrbank.php?nama_bank='.$bln1.' && start_date='.$start_date.' && end_date='.$end_date.'"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>';
        ?>   -->

        <a onclick="export_data()"><button type="button" class="btn btn-success " style= "margin-top: 30px;"><i class="fa fa-file-excel-o" aria-hidden="true" style="padding-right: 10px; padding-left: 5px;font-size: 1rem;color: #fff;text-shadow: 1px 1px 1px #000"> Excel</i></button></a>
            </div>                                                            
    </div>
<br/>
</div>
</form> 

    </div>
    <div class="box body">
        <div class="row">       
            <div class="col-md-12">
            
<div class="card-body table-responsive p-0" style="height: 400px;">
    <table id="table-kartu_ar2" class="table table-bordered table-head-fixed text-nowrap">    <thead>
        <tr class="thead-dark">
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Customer</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;"> No Invoice</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Invoice Date</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Category</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Due Date</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">TOP</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Curr</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Rate</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Beginning Balance</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Addition</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Deduction</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Ending Balance</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;">Equivalent IDR</th>
            <th colspan="9" style="text-align: center;vertical-align: middle;">Receivable Aging Based on Due Date</th>
            <th rowspan="2" style="text-align: center;vertical-align: middle;"></th>
            <th colspan="8" style="text-align: center;vertical-align: middle;">Receivable Due Date Projection</th>
        </tr>
        <tr class="thead-dark">
            <th style="text-align: center;vertical-align: middle;">Not Due</th>
            <th style="text-align: center;vertical-align: middle;">1-30</th>
            <th style="text-align: center;vertical-align: middle;">31-60</th>
            <th style="text-align: center;vertical-align: middle;">61-90</th>
            <th style="text-align: center;vertical-align: middle;">91-120</th>
            <th style="text-align: center;vertical-align: middle;">121-180</th>
            <th style="text-align: center;vertical-align: middle;">181-360</th>
            <th style="text-align: center;vertical-align: middle;">>360</th>
            <th style="text-align: center;vertical-align: middle;">Total</th>
            <th style="text-align: center;vertical-align: middle;">Already Due</th>
            <th style="text-align: center;vertical-align: middle;width: auto;"><input type="text" class="form-control" id="bulan_1" name="bulan_1" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;"><input type="text" class="form-control" id="bulan_2" name="bulan_2" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;"><input type="text" class="form-control" id="bulan_3" name="bulan_3" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;"><input type="text" class="form-control" id="bulan_4" name="bulan_4" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;"><input type="text" class="form-control" id="bulan_5" name="bulan_5" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;"><input type="text" class="form-control" id="bulan_6" name="bulan_6" style="border: none;border-color: transparent;outline: none;text-align: center;background-color: white;font-weight: bold;text-transform: capitalize;color: white;width: 150px;margin-left: 0;margin-right: 0;background: #282828;" placeholder="" readonly></th>
            <th style="text-align: center;vertical-align: middle;">Total</th>
        </tr>
    </thead>
   
    <tbody id="datareport">
    </tbody>                    
</table>
   
    </div>
    </div>
</div>
</div><!-- body-row END -->
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
        <button style="border-radius: 6px" type="button" class="btn btn-danger" onclick="cancel_mj();"><i class="fa fa-trash"aria-hidden="true"> Cancel</i></button>
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
          <div id="txt_top" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
          <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_confirm" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_po" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>                     
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
<script type="text/javascript">

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}

function getnamabulan() {

    var id_cus = $('#customer').val();
    var tgl_akhir = document.getElementById('end_date').value;  
    var date_end = formatDate(tgl_akhir);
    const currentMonth = new Date(tgl_akhir);
        var tahun  = currentMonth.getFullYear();
        var bulan1 = currentMonth.getMonth();
        var bulan2 = currentMonth.getMonth() + 1;
        var bulan3 = currentMonth.getMonth() + 2;
        var bulan4 = currentMonth.getMonth() + 3;
        var bulan5 = currentMonth.getMonth() + 4;
        var bulan6 = currentMonth.getMonth() + 5;
        if (bulan1 > 11) {
            var tahun_1 = tahun +1;
        }else{
            var tahun_1 = tahun;
        }
        if (bulan2 > 11) {
            var tahun_2 = tahun +1;
        }else{
            var tahun_2 = tahun;
        }
        if (bulan3 > 11) {
            var tahun_3 = tahun +1;
        }else{
            var tahun_3 = tahun;
        }
        if (bulan4 > 11) {
            var tahun_4 = tahun +1;
        }else{
            var tahun_4 = tahun;
        }
        if (bulan5 > 11) {
            var tahun_5 = tahun +1;
        }else{
            var tahun_5 = tahun;
        }
        if (bulan6 > 11) {
            var tahun_6 = tahun +1;
        }else{
            var tahun_6 = tahun;
        }
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var bulan_1 =  months[bulan1]; 
        var bulan_2 =  months[bulan2]; 
        var bulan_3 =  months[bulan3]; 
        var bulan_4 =  months[bulan4]; 
        var bulan_5 =  months[bulan5]; 
        var bulan_6 =  months[bulan6]; 

        $('#bulan_1').val(bulan_1 + ' ' + tahun_1);
        $('#bulan_2').val(bulan_2 + ' ' + tahun_2);
        $('#bulan_3').val(bulan_3 + ' ' + tahun_3);
        $('#bulan_4').val(bulan_4 + ' ' + tahun_4);
        $('#bulan_5').val(bulan_5 + ' ' + tahun_5);
        $('#bulan_6').val(bulan_6 + ' ' + tahun_6);
        $('#bln1').val(bulan_1);
        $('#bln2').val(bulan_2);
        $('#bln3').val(bulan_3);
        $('#bln4').val(bulan_4);
        $('#bln5').val(bulan_5);
        $('#bln6').val(bulan_6);


        var customer = document.getElementById('nama_supp').value;
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;      
             
        $.ajax({
            type:'POST',
            url:'cari_data_report.php',
            data: {'customer':customer, 'start_date':start_date, 'end_date':end_date},
            cache: 'false',
            close: function(e){
                e.preventDefault();
                return false; 
            },
            success: function(data){
                $('#datareport').html(data);
                // alert(data);  
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });

    }


    function export_data() {

    var id_cus = $('#customer').val();
    var tgl_akhir = document.getElementById('end_date').value;  
    var date_end = formatDate(tgl_akhir);
    const currentMonth = new Date(tgl_akhir);
        var tahun  = currentMonth.getFullYear();
        var bulan1 = currentMonth.getMonth();
        var bulan2 = currentMonth.getMonth() + 1;
        var bulan3 = currentMonth.getMonth() + 2;
        var bulan4 = currentMonth.getMonth() + 3;
        var bulan5 = currentMonth.getMonth() + 4;
        var bulan6 = currentMonth.getMonth() + 5;
        if (bulan1 > 11) {
            var tahun_1 = tahun +1;
        }else{
            var tahun_1 = tahun;
        }
        if (bulan2 > 11) {
            var tahun_2 = tahun +1;
        }else{
            var tahun_2 = tahun;
        }
        if (bulan3 > 11) {
            var tahun_3 = tahun +1;
        }else{
            var tahun_3 = tahun;
        }
        if (bulan4 > 11) {
            var tahun_4 = tahun +1;
        }else{
            var tahun_4 = tahun;
        }
        if (bulan5 > 11) {
            var tahun_5 = tahun +1;
        }else{
            var tahun_5 = tahun;
        }
        if (bulan6 > 11) {
            var tahun_6 = tahun +1;
        }else{
            var tahun_6 = tahun;
        }
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var bulan_1 =  months[bulan1]; 
        var bulan_2 =  months[bulan2]; 
        var bulan_3 =  months[bulan3]; 
        var bulan_4 =  months[bulan4]; 
        var bulan_5 =  months[bulan5]; 
        var bulan_6 =  months[bulan6]; 

        $('#bulan_1').val(bulan_1 + ' ' + tahun_1);
        $('#bulan_2').val(bulan_2 + ' ' + tahun_2);
        $('#bulan_3').val(bulan_3 + ' ' + tahun_3);
        $('#bulan_4').val(bulan_4 + ' ' + tahun_4);
        $('#bulan_5').val(bulan_5 + ' ' + tahun_5);
        $('#bulan_6').val(bulan_6 + ' ' + tahun_6);
        $('#bln1').val(bulan_1);
        $('#bln2').val(bulan_2);
        $('#bln3').val(bulan_3);
        $('#bln4').val(bulan_4);
        $('#bln5').val(bulan_5);
        $('#bln6').val(bulan_6);


        var nama_supp = document.getElementById('nama_supp').value;
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value; 
        
        window.open('ekspor_ar_detail.php?nama_supp='+nama_supp+'&start_date='+start_date+'&end_date='+end_date+'&bln1='+bulan_1+'&bln2='+bulan_2+'&bln3='+bulan_3+'&bln4='+bulan_4+'&bln5='+bulan_5+'&bln6='+bulan_6+'&thn1='+tahun_1+'&thn2='+tahun_2+'&thn3='+tahun_3+'&thn4='+tahun_4+'&thn5='+tahun_5+'&thn6='+tahun_6+'&dest=xls', '_blank');

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
        format: "yyyy-mm-dd",
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
    $('table tbody tr').on('click', 'td:eq(0)', function(){                
    $('#mymodal').modal('show');
    var no_ib = $(this).closest('tr').find('td:eq(0)').attr('value');
    var date = $(this).closest('tr').find('td:eq(1)').text();
    var reff = $(this).closest('tr').find('td:eq(2)').attr('value');
    var reff_doc = $(this).closest('tr').find('td:eq(3)').attr('value');
    var oth_doc = $(this).closest('tr').find('td:eq(4)').attr('value');
    var curr = "IDR";
    var akun = $(this).closest('tr').find('td:eq(5)').attr('value');
    var desk = $(this).closest('tr').find('td:eq(7)').text();

    $.ajax({
    type : 'post',
    url : 'ajaxpettyin.php',
    data : {'no_ib': no_ib, 'refdoc': reff},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_bpb').html(no_ib);
    $('#txt_tglbpb').html('Date : ' + date + '');
    $('#txt_no_po').html('Refference : ' + reff + '');
    $('#txt_supp').html('Refference Document : ' + reff_doc + '');
    $('#txt_top').html('Other Document : ' + oth_doc + '');
    $('#txt_curr').html('Kas Account : ' + akun + '');        
    $('#txt_confirm').html('Currency : ' + curr + '');
    $('#txt_tgl_po').html('Description : ' + desk + '');                    
});

</script>

<script type="text/javascript">
    document.getElementById('reset').onclick = function () {
    location.href = "report-detail.php";
};
</script>


<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
  
</body>

</html>