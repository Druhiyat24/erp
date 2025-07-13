<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("req_mat","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_req=$_GET['id'];} else {$id_req="";}
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $harus_bpb = $rscomp["req_harus_bpb"];
  $logo_company = $rscomp["logo_company"];
$id_item="";
if($mod=="611rvp")
{
  // $nmpdf="pdfPickList.php";
  $nmpdf="pdfPLGDOUT.php";
}
else
{
  $nmpdf="pdfPickListReq.php";
}
# COPAS EDIT
if ($id_req=="")
{ $reqno="";
  $reqdate=date("d M Y");
  $txtbcdate=date("d M Y");
  $bppbno="";
  $sentto="";
  $notes="";
}
else
{ $cekbppb=flookup("count(*)","bppb","bppbno_req='$id_req'");
  if($cekbppb<>"0")
  {
    $_SESSION['msg']="XRequest # Sudah Ada Pengeluaran";
    echo "
    <script>
      window.location.href='?mod=1';
    </script>";
  }          
  $query = mysql_query("SELECT a.* FROM bppb_req a where a.bppbno='$id_req' ");
  $data = mysql_fetch_array($query);
  $reqno=$data['bppbno'];
  $reqdate=fd_view($data['bppbdate']);
  $sentto=$data['id_supplier'];
  $notes=$data['remark'];
}


$frdate=date("d M Y");
$kedate=date("d M Y");

$tglf=date("d M Y");
$tglt=date("d M Y");

$dtf=date("d M Y");
$dtt=date("d M Y");

$perf=date("d M Y");
$pert=date("d M Y");

if (isset($_POST['submit']))
{
  $excel="N";
  $tglf = fd($_POST['frdate']);
  $perf = date('d M Y', strtotime($tglf));
  $tglt = fd($_POST['kedate']);
  $pert = date('d M Y', strtotime($tglt));


}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var id_supplier = document.form.txtid_supplier.value;
    var id_jo = document.form.txtJOItem.value;
    var idwsact = document.form.txtidws_act.value;
    var txtremark = document.form.txtremark.value;


    var pilih = 0;
    var qtykos = 0;
    var qtyover = 0;
    var qtys = document.form.getElementsByClassName('qtybpbclass');
    var qtybtss = document.form.getElementsByClassName('qtysisaclass');
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value !== '')
      { qtykos = qtykos + 1; }
    }
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value != '')
      {
        if (Number(qtys[i].value) > Number(qtybtss[i].value))
        { qtyover = qtyover + 1; }
      }
    }
    if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtyover > 0) { swal({ title: 'Qty Melebihi Stock', <?php echo $img_alert; ?>}); valid = false; }
     else if (idwsact == '') { swal({ title: 'ID WS Actual# Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
?>
<script type="text/javascript">

  function getJO()
  { var id_jo = $('#cboReq').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_req_new.php?modeajax=view_list_stock_req',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item_req").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix1').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });

  jQuery.ajax({

    url: 'ajax.php?modeajax=cari_supp_req',

    method: 'POST',

    data: {cri_item: id_jo},

    dataType: 'json',

    success: function(response)

    { $('#cbosupp').val(response[0]);

    $('#txtjono').val(response[1]);  

  },

  error: function (request, status, error) {

    alert(request.responseText);

  },

});



  };

function getreq(noreq)
{
    $('#detail_item tbody tr').remove();
    var noreq = $('#cboReq').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_req_new.php?modeajax=get_list_barcode',
        data: "noreq=" +noreq,
        async: false
    }).responseText;
    if(html)
    { $("#cbobarcode").html(html); } 
}

function collectdata()
{
  getJO();
  getreq();
}

  function getbarcode()
  { var id_barcode = $('#cbobarcode').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_req_new.php?modeajax=view_list_stock',
        data: {id_barcode: id_barcode },
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };


  function getbarcode2()
  { var id_barcode2 = $('#cbobarcode').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_req_new.php?modeajax=view_list_stock',
        data: "id_barcode2=" +id_barcode2,
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };

  function checkAll(ele) {
       var checkboxes = document.getElementsByClassName('chkclass');
       if (ele.checked) {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox' ) {
                   checkboxes[i].checked = true;
               }
           }
       } else {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox') {
                   checkboxes[i].checked = false;
               }
           }
       }
   }


function calc_chk()
{
  var chkroll = document.getElementsByClassName('chkclass');
  var sum = 0;
  var chkqty = document.getElementsByClassName('txtuseclass');
  for (var i = 0; i < chkroll.length; i++) 
  { 
    if (chkroll[i].checked)
    { 
      sum += Number(chkqty[i].value);
    }
  }
  $('#total_qty_chk').show();
  $('#total_qty_chk').text(sum);
}

function calc_chk_rak()
{
  var chkroll_rak = document.getElementsByClassName('chkclass_rak');
  var sum_rak = 0;
  var chkqty_rak = document.getElementsByClassName('txtuseclass_rak');
  for (var i = 0; i < chkroll_rak.length; i++) 
  { 
    if (chkroll_rak[i].checked)
    { 
      sum_rak += Number(chkqty_rak[i].value);
    }
  }
  $('#total_qty_chk_rak').show();
  $('#total_qty_chk_rak').text(sum_rak);
}


function choose_rak(id_jo,id_item)
{ 
  var html = $.ajax
  ({  
    type: "POST",
    url: 'ajax_req_new.php?modeajax=view_list_rak_loc',
    data: {id_jo: id_jo,id_item: id_item},
    async: false
  }).responseText;
  if(html)
  {  
    $("#detail_rak").html(html);
  }
  $(document).ready(function() {
    var table = $('#examplefix').DataTable 
    ({  
footerCallback: function (row, data, start, end, display) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
 
            // Total over all pages
            total = api
                .column(6)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer
            $(api.column(6).footer()).html(pageTotal);
        },
    });
});
};

    // $("input:checkbox").on("change", function () {
    //   var sum = 0;
    //   $.each($("input[id='chkajax']:checked"), function(){            
    //       // sum += Number($(this).closest('tr').find('td:nth-child(4)').text());
    //       sum += Number(parseFloat($(this).attr('txtuse')) || 0);
    //   });
    //   $('#total_qty_chk').show();
    //   $('#total_qty_chk').text(sum);
    // });
// function calc() {
//   document.getElementById('hasil').innerHTML = 'Hello World';
// }

// function autoinput() {
//       var txtFirstNumberValue = document.getElementById('qtyout_roll[$id]').value;
//       var result = parseInt(txtFirstNumberValue);

//          document.getElementById('hasil').value = result;

// }


// function autoinput(){ 
// var table = document.getElementById("examplefix2");
// var tota = 0;

// for (var i = 1; i < (table.rows.length); i++) {

// var price = document.getElementById("examplefix2").rows[i].cells[6].children[0].val();
// tota = price;
// document.getElementById("examplefix2").rows[i].cells[7].children[0].val() = tota;

// }
// }

</script>
<?php if ($mod=="new_out_material") { ?>


<div class="modal fade" id="myRak"  tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" style="overflow-y:auto;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Detail</h4> 
      </div>
      <div class="modal-body" style="overflow-y:auto; height:500px;">
        <div id='detail_rak'></div>    
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>




<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='save_bppb_req_barcode_new.php?mod=$mod&mode=$mode'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Request #</label>
  <select class='form-control select2' style='width: 100%;' 
  name='txtreqno' id='cboReq' onchange='collectdata()'>
              <?php 
              if($noreq!="") {$fldcri=" where bppbno='$noreq'";} else {$fldcri="";}
              $sql="select a.bppbno isi,concat(a.bppbno,'|',ac.kpno,'|',ac.styleno,'|',mb.supplier) tampil 
    from bppb_req a inner join jo_det s on a.id_jo=s.id_jo 
    inner join so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
    inner join mastersupplier mb on ac.id_buyer=mb.id_supplier  
    $fldcri and a.cancel='N' and bppbdate >= '2022-01-01' where bppbno like 'RQ-F%' group by bppbno
    order by bppbdate desc";
            IsiCombo($sql,$noreq,'Pilih Request #');
            ?>
          </select>
          </div>        
          <div class='form-group'>
            <label>Tgl BPPB *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtbppbdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate;?>' >
          </div>
          <div class='form-group'>
            <label>Jenis Pengeluaran *</label>
            <select class='form-control select2' style='width: 100%;' name='txtjns_out' required >
            <?php
              $sqljns_out = "select nama_trans isi,nama_trans tampil from mastertransaksi where 
                            jenis_trans='OUT' and jns_gudang = 'FACC' order by id";
                    IsiCombo($sqljns_out,'','Pilih Jenis Pengeluaran');    
            ?>
               </select>           
          </div>
                    
          <div class='form-group'>
            <label>Dikirim Ke *</label>
              <input type='text' class='form-control' name='txtid_supplier' id='cbosupp'  readonly >
          </div>         
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Jenis Dokumen *</label>
            <select class='form-control select2' style='width: 100%;' name='txtstatus_kb' required >
            <?php
              $sqljns_kb = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='Status KB Out' order by nama_pilihan";
                    IsiCombo($sqljns_kb,'','Pilih Jenis Dokumen');    
            ?>
            </select>           
          </div>
          <div class='form-group'>
          <label>Nomor Daftar *</label>
            <input type='text' maxlength='6' class='form-control' name='txtbcno' id ='txtbcno' placeholder='Masukan No Daftar'>
          </div>  
          <div class='form-group'>
          <label>Tgl Daftar *</label>
            <input type='text' class='form-control' id='datepicker2' name='txtbcdate' value='<?php echo $txtbcdate;?>' placeholder='Masukkan Tgl. Daftar'>
          </div>
          <div class='form-group'>
            <label>Nomor BPPB *</label>
            <input type='text' class='form-control' name='txtbppbno' readonly
  placeholder='Masukkan Nomor BPPB' value='<?php echo $bppbno;?>'>

  </div>              
        </div>  
        <div class='col-md-6'>
          <div class='form-group'>
            <label>Barcode # *</label>
            <select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtbarcode' id='cbobarcode' onchange='getbarcode()'>
            </select>
          </div>
          <div class='form-group'>
            <label>Keterangan</label>
            <textarea row='5' class='form-control' name='txtremark' id='txtremark' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
          <div class='form-group'>
            <div style='font-size:18px;'>Total : <p id='total_qty_chk' style='display:none' value=''></p> </div>
          </div>          
        <div class='form-group'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>                     
        </div>
        <div class='box-body'>
            <div id='detail_item'></div>
        </div>
         <div class='box-body'>
            <div id='detail_item_req'></div>
        </div>         
      </form>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
#if ($id_req=="") {
if ($mod=="out_material") {
?>
<div class="box">
  <div class="box-header">
    <?php if($mod=="611rv") { ?>
      <!-- <h3 class="box-title">Permintaan Bahan Baku</h3> -->
    <?php } else { ?>
      <!-- <h3 class="box-title">Picklist Bahan Baku</h3> -->
    <?php } ?>
    <?php if($mod=="out_material") { ?>
  <a href='../wh/?mod=new_out_material&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New 
    </a>
  <?php } ?>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>From Date (BPPB Req) : </label>
        <input type='text' class='form-control' id='datepicker1' name='frdate' placeholder='Masukkan From Date' value='<?php echo $perf;?>' >
             
      </div>
      <div class='col-md-2'>
        <label>To Date (BPPB Req) : </label>
        <input type='text' class='form-control' id='datepicker2' name='kedate' placeholder='Masukkan To Date' value='<?php echo $pert;?>' >
      </div> 
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submit' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>


  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Nomor BPPB</th>
        <th>Tgl BPPB</th>
        <th>Nomor Req</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>WS</th>
        <th>Penerima</th>
        <th>No Dokumen</th>
        <th>Tgl Dokumen</th>
        <th>Jenis BC</th>
        <th>Jenis Trans</th>
        <th>Dibuat</th>
        <th>Status</th>
        <th>Print BPPB</th>
        <th>Print Barcode</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select bppb.bppbno,bppb.bppbno_int, bppb.bppbdate, bppb.bppbno_req, ms.supplier buyer, ac.styleno, ac.kpno, msa.supplier penerima, bppb.bcno, bppb.bcdate, bppb.jenis_dok,bppb.jenis_trans,bppb.username,bppb.dateinput, bppb.confirm, bppb.confirm_by, bppb.confirm_date, bppb.cancel, bppb.cancel_by, bppb.cancel_date from bppb 
inner join bppb_barcode_det bbd on bppb.bppbno = bbd.bppbno
inner join bppb_req br on bppb.bppbno_req = br.bppbno
inner join jo_det jd on bppb.id_jo = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join mastersupplier ms on ac.id_buyer = ms.id_supplier
inner join mastersupplier msa on br.id_supplier = msa.id_supplier
where bppb.bppbdate >= '$tglf' and bppb.bppbdate <= '$tglt'
group by bppbno order by bppbdate desc ");
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { 
          echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[bppbno_int]</td>";
            echo"
            <td>".fd_view($data[bppbdate])."</td>";
            echo"
            <td>$data[bppbno_req]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[penerima]</td>
            <td>$data[bcno]</td>";
            echo"
            <td>".fd_view($data[bcdate])."</td>";
            echo"
            <td>$data[jenis_dok]</td>
            <td>$data[jenis_trans]</td>
            <td>$data[username] ($data[dateinput])</td>";
            echo "<td></td>";
            echo "
            <td align = 'center'>
            <a 
              href='pdfDO_.php?mode=Out&noid=$data[bppbno]'
              data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i>
            </a>              
            </td>
            <td align = 'center'>
            <a 
              href='pdfBarcode.php?mode=barcode_bppb&id=$data[bppbno]'
              data-toggle='tooltip' title='Preview'><i class='fa fa-barcode'></i>
            </a> 
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php } ?>