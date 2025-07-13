<?php include '../header2.php' ?>

    <!-- MAIN -->
    <div class="col p-4">
        <h4 class="text-center">FORM BOOK INVOICE</h4>
<div class="box">
    <div class="box header">

<div class="box footer">   
        <form id="form-simpan">
            </br> 

            <div class="form-row col ml-2">
                <label for="pajak" class="col-form-label" style="width: 150px;"><b>Invoice Number</b></label>
            <div class="col-md-4 mb-3">                              
                <?php
            $sql = mysqli_query($conn2,"select no_invoice, MAX(LEFT(no_invoice, 4)) AS kd_max FROM tbl_book_invoice WHERE YEAR(tgl_book_inv) = YEAR(CURRENT_DATE()) AND MONTH(tgl_book_inv) = MONTH(CURRENT_DATE())");
            $row = mysqli_fetch_array($sql);
            $kodepay = $row['kd_max'];
            $urutan = (int) substr($kodepay,1,4);
            $urutan ++;
            $bln = date("m");
            $thn = date("y");
            $huruf = "//NAG/$bln$thn";
            $kodepay = sprintf("%04s", $urutan) . $huruf;

            echo'<input type="text" readonly style="font-size: 14px;text-transform:uppercase" class="form-control-plaintext" id="no_doc" name="no_doc" value="'.$kodepay.'">
            <input type="text" readonly style="font-size: 14px;" class="form-control-plaintext" id="no_urut" name="no_urut" value="'.sprintf("%04s", $urutan).'" hidden>
            <input type="text" readonly style="font-size: 14px;" class="form-control-plaintext" id="bulan" name="bulan" value="'.$bln.'" hidden>
            <input type="text" readonly style="font-size: 14px;" class="form-control-plaintext" id="tahun" name="tahun" value="'.$thn.'" hidden>';
            ?>
            </div>
            </div>    

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Invoice Date</b></label>
            <div class="col-md-4 mb-3">                              
                <input type="text" style="font-size: 15px;" name="inv_date" id="inv_date" class="form-control tanggal" 
            value="<?php echo date("d-m-Y"); ?>" autocomplete='off'>
            </div>
            </div>     

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Customer</b></label>
            <div class="col-md-4 mb-3">                              
                <select class="form-control select2bs4" name="customer" id="customer" data-dropup-auto="false" data-live-search="true" >
                <option value="" disabled selected="true">Select Customer</option>                                                
                <?php
                $customer ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $customer = isset($_POST['customer']) ? $_POST['customer']: null;
                }                 
                $sql = mysqli_query($conn1,"select DISTINCT alamat,Id_Supplier, UPPER(Supplier) As Supplier FROM mastersupplier WHERE tipe_sup = 'C' and id_supplier != '1006' order by supplier asc");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['Id_Supplier'];
                    $data2 = $row['Supplier'];
                    if($row['Id_Supplier'] == $_POST['customer']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                }?>
                </select>
            </div>
            </div>    

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Shipp</b></label>
            <div class="col-md-4 mb-3">                              
                <select class="form-control select2bs4" name="shipp" id="shipp" data-dropup-auto="false" data-live-search="true" onchange="change_invnum()">
                    <option value="" disabled selected="true">Select Shipp</option>
                    <option value="L">Local</option>  
                    <option value="E">Export</option>
                </select>
            </div>
            </div>   

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Dokumen Type</b></label>
            <div class="col-md-4 mb-3">                              
                <select class="form-control select2bs4" name="dok_type" id="dok_type" data-dropup-auto="false" data-live-search="true" >
                    <option value="" disabled selected="true">Select Dok type</option>
                </select>
            </div>
            </div>   

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Dokumen Number</b></label>
            <div class="col-md-4 mb-3">                              
                <input type="text" style="font-size: 15px;" name="dok_number" id="dok_number" class="form-control input-sm" 
            value="" autocomplete='off'>
            </div>
            </div>   

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Value</b></label>
            <div class="col-md-4 mb-3">                              
                <input type="number" style="font-size: 15px;" name="jml_value" id="jml_value" class="form-control input-sm" 
            value="" autocomplete='off'>
            </div>
            </div>

            <div class="form-row col ml-2">
                <label for="total" class="col-form-label" style="width: 150px;"><b>Type</b></label>
            <div class="col-md-4 mb-3">                              
                <select class="form-control select2bs4" name="txt_type" id="txt_type" data-dropup-auto="false" data-live-search="true" >
                <option value="" disabled selected="true">Select Type</option>                                                
                <?php
                $txt_type ='';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $txt_type = isset($_POST['txt_type']) ? $_POST['txt_type']: null;
                }                 
                $sql = mysqli_query($conn1,"select id_type,type from tbl_type");
                while ($row = mysqli_fetch_array($sql)) {
                    $data = $row['id_type'];
                    $data2 = $row['type'];
                    if($row['id_type'] == $_POST['txt_type']){
                        $isSelected = ' selected="selected"';
                    }else{
                        $isSelected = '';
                    }
                    echo '<option value="'.$data.'"'.$isSelected.'">'. $data2 .'</option>';    
                }?>
                </select>
            </div>
            </div>            
        
            </br>

           <div class="form-row col">
            <div class="col-md-3 mb-3">                              
            <button type="button" style="border-radius: 6px" class="btn-outline-primary btn-sm" name="simpan" id="simpan"><span class="fa fa-floppy-o"></span> Save</button>                
            <button type="button" style="border-radius: 6px" class="btn-outline-danger btn-sm" name="batal" id="batal" onclick="location.href='book-invoice.php'"><span class="fa fa-angle-double-left"></span> Back</button>           
            </div>
            </div>                                    
        </form>
        </div>

<div class="modal fade" id="mymodalkbon" data-target="#mymodalkbon" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-times"></span></button>
        <h4 class="modal-title" id="txt_kbon"></h4>
        </div>
        <div class="container">
        <div class="row">
          <div id="txt_tgl_kbon" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_nama_supp" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_tempo" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>         
<!--           <div id="txt_curr" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div> -->
<!--           <div id="txt_create_user" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_status" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_no_faktur" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_supp_inv" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>
          <div id="txt_tgl_inv" class="modal-body col-6" style="font-size: 12px; padding: 0.5rem;"></div>  -->                                          
          <div id="details" class="modal-body col-12" style="font-size: 12px; padding: 0.5rem;"></div>          
        </div>
        </div>
        </div>
    <!-- /.modal-content --> 
  </div>
      <!-- /.modal-dialog --> 
    </div>         
                                
</div><!-- body-row END -->
</div>
</div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/datatables.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-datepicker.js"></script>
  <script language="JavaScript" src="../css/4.1.1/bootstrap-select.min.js"></script>
  <script language="JavaScript" src="../css/4.1.1/select2.full.min.js"></script>


<script>
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()
      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });
  </script>
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
    $('#mytable').dataTable();
    
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

<!--<script type="text/javascript"> 
    $("#mytable").on("click", "#delbutton", function() {
    var sub = $(this).closest('tr').find('td:eq(4)').attr('data-subtotal');
    var pajak = $(this).closest('tr').find('td:eq(5)').attr('data-tax');
    var total = $(this).closest('tr').find('td:eq(6)').attr('data-total');        
    var sub_val = document.getElementById("subtotal").value.replace(/[^0-9.]/g, '');
    var sub_tax = document.getElementById("pajak").value.replace(/[^0-9.]/g, '');
    var sub_total = document.getElementById("total").value.replace(/[^0-9.]/g, '');
    var min_sub = 0;
    var min_tax = 0;
    var min_total = 0;
    min_sub = sub_val - sub;
    min_tax = sub_tax - pajak;
    min_total = sub_total - total;
    $('#subtotal').val(formatMoney(min_sub));
    $('#pajak').val(formatMoney(min_tax));
    $('#total').val(formatMoney(min_total));                      
    $(this).closest("tr").remove();

});
</script>-->

<script type="text/javascript">
function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
  try {
    decimalCount = Math.abs(decimalCount);
    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

    const negativeSign = amount < 0 ? "-" : "";

    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
    let j = (i.length > 3) ? i.length % 3 : 0;

    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
  } catch (e) {
    console.log(e)
  }
};
    $("input[type=checkbox]").change(function(){
    var no_pay = '';
    var tgl_pay = '';
    var no_kbon = '';
    var tgl_kbon = '';
    var valuta = '';
    var total = 0;
    var dibayar = 0;
    var sisa = 0;
    var buka = '';

    $("input[type=checkbox]:checked").each(function () {     
    var select_rate = document.getElementById('rate');   
    var pay = $(this).closest('tr').find('td:eq(1)').attr('dataout1');
    var tglpay = $(this).closest('tr').find('td:eq(2)').attr('dataout2');
    var kbon = $(this).closest('tr').find('td:eq(3)').attr('dataout3');
    var tglkbon = $(this).closest('tr').find('td:eq(4)').attr('dataout4');
    var val = $(this).closest('tr').find('td:eq(8)').attr('dataout6');
    var ttl = parseFloat($(this).closest('tr').find('td:eq(6)').attr('dataout5'),10) || 0;
    var dbayar = parseFloat($(this).closest('tr').find('td:eq(9)').attr('dataout7'),10) || 0; 
    //  select_rate.prop('disabled', false);  
    // buka = select_rate.prop('disabled', false);     
              
    no_pay = pay;
    tgl_pay = tglpay; 
    no_kbon = kbon;
    tgl_kbon = tglkbon;
    valuta = val;
    total = ttl;
    dibayar += dbayar;

    if(valuta == 'IDR'){
            select_rate.disabled = true;
        }else{
            select_rate.disabled = false;
        }
    });
    // $("#no_payment").val(no_pay);
    // $("#tgl_payment").val(tgl_pay);
    // $("#no_kbon").val(no_kbon);
    // $("#tgl_kbon").val(tgl_kbon);
    $("#valuta1").val(valuta);
    $("#total").val(formatMoney(total));
    $("#dibayar").val(formatMoney(dibayar));
    $("#nominal_h").val(dibayar);
    // $("#outstanding").val(formatMoney(sisa));
    $("#nominal").val(formatMoney(dibayar));
    // $("#select").val("1");                    
});        
</script>

<script type="text/javascript">
    function change_invnum(){
        var valu = '';
        var sob = document.getElementById('shipp').value;
        var urut = document.getElementById('no_urut').value;
        var bulan = document.getElementById('bulan').value;
        var tahun = document.getElementById('tahun').value;
        valu = urut+'/'+sob+'/'+'NAG'+'/'+bulan+tahun;

        $("#no_doc").val(valu);

        $.ajax({
            type: 'POST', 
            url: 'ubah_doktype.php', 
            data: {'sob':sob},
            success: function(response) { 
                $('#dok_type').html(response); 
            }
        });

    }
</script>

<script type="text/javascript">
    $("input[name=sob]").keyup(function(){
    var ttl_jml = '';
    var valu = '';
    $("input[type=text]").each(function () {         
    var sob = document.getElementById('sob').value;
    var urut = document.getElementById('no_urut').value;
    var bulan = document.getElementById('bulan').value;
    var tahun = document.getElementById('tahun').value;
    valu = 'MB'+'/'+sob+'/'+'NAG'+'/'+bulan+tahun+'/'+urut;

    
    });
   $("#no_doc").val(valu);


    });
</script>

<script type="text/javascript">
    $("input[name=rate]").keyup(function(){
    var ttl_jml = 0;
    var rat = 0;
    var valu = '';
    $("input[type=text]").each(function () {         
    var rate = parseFloat(document.getElementById('rate').value,10) || 1;
    var ttl_h = parseFloat(document.getElementById('nominal_h').value,10) || 0;
    var val = document.getElementById('valuta1').value;
    valu = val;
    rat = rate;
    if (valu == 'IDR') {
    ttl_jml = ttl_h / rate;  
    }else{
    ttl_jml = ttl_h * rate;    
    }
    });
   $("#nomrate").val(formatMoney(ttl_jml));
   $("#nomrate_h").val(ttl_jml);
   $("#rate_h").val(formatMoney(rat));

    });
</script>


<script type="text/javascript">
// get all number fields
var numInputs = document.querySelectorAll('input[type="number"]');

// Loop through the collection and call addListener on each element
Array.prototype.forEach.call(numInputs, addListener); 


function addListener(elm,index){
  elm.setAttribute('min', 1);  // set the min attribute on each field
  
  elm.addEventListener('keypress', function(e){  // add listener to each field 
     var key = !isNaN(e.charCode) ? e.charCode : e.keyCode;
     str = String.fromCharCode(key); 
    if (str.localeCompare('-') === 0){
       event.preventDefault();
    }
    
  });
  
}
</script>


<script type="text/javascript">
    $("#form-simpan").on("click", "#simpan", function(){
        var no_inv = document.getElementById('no_doc').value;        
        var tgl_inv = document.getElementById('inv_date').value;
        var customer = document.getElementById('customer').value;        
        var shipp = document.getElementById('shipp').value;
        var dok_type = document.getElementById('dok_type').value;        
        var dok_number = document.getElementById('dok_number').value;
        var jml_value = document.getElementById('jml_value').value;
        var txt_type = document.getElementById('txt_type').value;        
        var create_user = '<?php echo $user ?>';

 
        if( shipp != '' && jml_value != ''){  
        $.ajax({
            type:'POST',
            url:'insert-book-invoice.php',
            data: {'no_inv':no_inv, 'tgl_inv':tgl_inv, 'customer':customer,'shipp':shipp, 'dok_type':dok_type, 'dok_number':dok_number,'jml_value':jml_value, 'txt_type':txt_type, 'create_user':create_user},
            cache: 'false',
            close: function(e){
                e.preventDefault();
            },
            success: function(response){
                console.log(response);
                // alert(response);
                window.location = 'book-invoice.php';
                },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr);
                alert(xhr);
            }
        });
        }
                  
        if(document.getElementById('shipp').value == ''){
        document.getElementById('shipp').focus();
        alert("Please Input Shipp");
        } else if(document.getElementById('jml_value').value == ''){
        document.getElementById('jml_value').focus();
        alert("please Input Value");
        } else{
            alert("Successful Save");
        }           
    });
</script>

<script type="text/javascript">
$("#select_all").click(function() {
  var c = this.checked;
  $(':checkbox').prop('checked', c);
});  
</script>

<script type="text/javascript">     
    $('table tbody tr').on('click', 'td:eq(1)', function(){                
    $('#mymodalkbon').modal('show');
    var no_kbon = $(this).closest('tr').find('td:eq(1)').attr('dataout1');
    var tgl_kbon = $(this).closest('tr').find('td:eq(2)').text();
    var supp = $(this).closest('tr').find('td:eq(5)').attr('value');
    var tgl_tempo = $(this).closest('tr').find('td:eq(8)').text();
    var curr = $(this).closest('tr').find('td:eq(8)').attr('value');
    var create_user = $(this).closest('tr').find('td:eq(11)').attr('value');
    var status = $(this).closest('tr').find('td:eq(10)').attr('value');
    var no_faktur = $(this).closest('tr').find('td:eq(12)').attr('value');
    var supp_inv = $(this).closest('tr').find('td:eq(13)').attr('value');
    var tgl_inv = $(this).closest('tr').find('td:eq(14)').text();                

    $.ajax({
    type : 'post',
    url : 'ajaxf_kbon.php',
    data : {'no_kbon': no_kbon},
    success : function(data){
    $('#details').html(data); //menampilkan data ke dalam modal
        }
    });         
        //make your ajax call populate items or what even you need
    $('#txt_kbon').html(no_kbon);
    $('#txt_tgl_kbon').html('List Payment Date : ' + tgl_kbon + '');
    $('#txt_nama_supp').html('Supplier : ' + supp + '');
    $('#txt_tgl_tempo').html('Currency : ' + tgl_tempo + '');
    $('#txt_curr').html('Currency : ' + curr + '');        
    $('#txt_create_user').html('Create By : ' + create_user + '');
    $('#txt_status').html('Status : ' + status + '');
    $('#txt_no_faktur').html('No Faktur : ' + no_faktur + '');
    $('#txt_supp_inv').html('No Supplier Invoice : ' + supp_inv + '');
    $('#txt_tgl_inv').html('Tgl Supplier Invoice : ' + tgl_inv + '');                               
});

</script>
<script type="text/javascript"> 
<?php echo $jsArray; ?>
function changeValueACC(id){
    document.getElementById('nama_bank').value = prdName[id].nama_bank;
    document.getElementById('valuta').value = prdName[id].valuta;
};
</script>

<script type="text/javascript">
    $(document).ready(function(){
        // Format mata uang.
        $( '.uang' ).mask('0.000.000.000', {reverse: true});
    })
</script>

<!--<script>
    $(document).ready(){
        $('#mybpb').click(function){
            $('#mymodal').modal('show');
        }
    }
</script>-->
<!--<script>
$(document).ready(function() {   
    $("#send").click(function(e) {
        e.preventDefault();
        var datas= $(this).children("option:selected").val();
        $.ajax({
            type:"post",
            url:"cek.php",
            dataType: "json",
            data: {datas:datas},
            success: function(data){
                alert("Success: " + data);
            }
        });               
    });
</script>-->
<!--<script>
$(document).ready(function (){
    $("select.selectpicker").change(function(){
        var selectedbpb = $(this).children("option:selected").val();
        document.getElementById("bpbvalue").value = selectedbpb;             
    });
});
</script>-->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
  
</body>

</html>
