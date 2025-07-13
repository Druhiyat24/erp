<?PHP
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if (isset($_GET['mod'])) { $mod=$_GET['mod']; } else { $mod=""; }
if (isset($_GET['mode'])) { $mode=$_GET['mode']; } else { $mode=""; }
if (isset($_GET['noid'])) { $id_item=$_GET['noid']; } else { $id_item=""; }

if ($mode=="AP") { $tbl="acc_pay"; $cap_supcus="Supplier"; $fldid="id_ap"; $tbl_trx="bpb"; $fld_trx="bpbno"; }
else if ($mode=="AR") { $tbl="acc_rec"; $cap_supcus="Buyer"; $fldid="id_ar"; $tbl_trx="bppb"; $fld_trx="bppbno";}

if ($mode=="AP") { $title="Account Payable"; }
else if ($mode=="AR") { $title="Account Receivable"; }
else { echo "<script>alert('Terjadi kesalahan mode');</script>"; }
$c_save="Confirm";
# COPAS EDIT
if ($id_item=="")
{ $inv_date = "";
  $inv_no = "";
  $trx_no = "";
  $no_faktur = "";
  $id_supplier = "";
  $curr = "";
  $amount = "";
  $qty = "";
  $tt_date = "";
  $due_date = "";
  $pay_date = "";
  $pay_bank = "";
  $byrke = "";
}
else
{ $query = mysql_query("SELECT a.*,ms.supplier FROM $tbl a inner join mastersupplier ms on a.id_supplier=ms.id_supplier 
    where $fldid='$id_item'");
  $data = mysql_fetch_array($query);
  $inv_date = fd_view($data['inv_date']);
  $inv_no = $data['inv_no'];
  if($mode=="AP")
  { $trx_no = $data['bpbno'];
    $qty = flookup("sum(qty)","bpb","bpbno='$trx_no'"); 
  }
  else
  { $trx_no = $data['bppbno'];
    $qty = flookup("sum(qty)","bppb","bppbno='$trx_no'");
  }
  $no_faktur = $data['no_faktur'];
  $id_supplier = $data['supplier'];
  $curr = $data['curr'];
  $amount = $data['amount'];
  $tt_date = fd_view($data['tt_date']);
  $due_date = fd_view($data['due_date']);
  if ($data['pay_date']=="0000-00-00")
  { $pay_date = ""; }
  else
  { $pay_date = fd_view($data['pay_date']); }
  $pay_bank = $data['pay_bank'];
  $byrke = $data['byr_ke'];
}
# END COPAS EDIT
# COPAS VALIDASI
?>
<script type='text/javascript'>
  function validasi()
  { var inv_date = document.form.txtinv_date.value;
    var vinv_date = new Date(document.form.txtinv_date.value);
    var inv_no = document.form.txtinv_no.value;
    var id_supplier = document.form.txtid_supplier.value;
    var curr = document.form.txtcurr.value;
    var amount = document.form.txtamount.value;
    var tt_date = document.form.txttt_date.value;
    var vtt_date = new Date(document.form.txttt_date.value);
    var due_date = document.form.txtdue_date.value;
    var vdue_date = new Date(document.form.txtdue_date.value);
    <?php if($id_item!="") { ?>
      var pay_date = document.form.txtpay_date.value;
      var vpay_date = new Date(document.form.txtpay_date.value);
      var pay_bank = document.form.txtpay_bank.value;
    <?php } ?>
    if (inv_date == '') { alert('Tgl. Inv tidak boleh kosong'); valid = false;}
    else if (inv_no == '') { alert('No. Inv tidak boleh kosong'); valid = false;}
    else if (id_supplier == '') { alert('Supplier tidak boleh kosong'); valid = false;}
    else if (curr == '') { alert('Mata Uang tidak boleh kosong'); valid = false;}
    else if (amount == '') { alert('Amount tidak boleh kosong'); document.form.txtamount.focus();valid = false;}
    else if (tt_date == '') { alert('Tgl. Tanda Terima tidak boleh kosong'); document.form.txttt_date.focus();valid = false;}
    else if (vtt_date < vinv_date) { alert('Tgl. Tanda Terima tidak boleh lebih kecil dari Tgl. Inv'); document.form.txttt_date.focus();valid = false;}
    else if (due_date == '') { alert('Tgl. Jatuh Tempo tidak boleh kosong'); document.form.txtdue_date.focus();valid = false;}
    else if (vdue_date < vtt_date) { alert('Tgl. Jatuh Tempo tidak boleh lebih kecil dari Tgl. Tanda Terima'); document.form.txtdue_date.focus();valid = false;}
    <?php if($id_item!="") { ?>
      else if (pay_date!='' && pay_bank=='') { alert('Nama Bank tidak boleh kosong'); document.form.txtpay_bank.focus();valid = false;}
      else if (pay_date=='' && pay_bank!='') { alert('Tgl. Bayar tidak boleh kosong'); document.form.txtpay_bank.focus();valid = false;}
    <?php } ?>
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getBank(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_bank',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cbobank").html(html);
      }
  };
  function getDataTrans()
  { var transno = document.form.txtinv_no.value;
    jQuery.ajax
    ({  
      url: "ajax.php?modeajax=get_data_trans",
      method: 'POST',
      data: {transno: transno},
      dataType: 'json',
      success: function(response)
      { $('#cbosupplier').val(response[0]);
        $('#txtno_faktur').val(response[1]);
        $('#txtamount').val(response[2]);
        $('#datepicker1').val(response[3]);
        $('#datepicker2').val(response[3]);
        var $newOption = $("<option selected='selected'></option>").val(response[4]).text(response[4])
        $("#cbocurr").append($newOption).trigger('change');
        $('#txtqty').val(response[5]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
  function getListTrx()
  { var tglfr = document.form.txtfrom.value;
    var tglto = document.form.txtto.value;
    <?php 
    echo "var trx = '".$mode."';";
    ?>
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax.php?modeajax=cari_list_trx',
        data: {trx: trx,tglfr: tglfr,tglto: tglto},
        async: false
    }).responseText;
    if(html)
    { $("#txtinv_no").html(html); }
  };
</script>

<div class="box">
  <?PHP
    # COPAS ADD 3 HAPUS /DIV TERAKHIR
    echo "<div class='box-body'>";
    echo "<div class='row'>";
    echo "<form method='post' name='form' action='save_data.php?mod=$mod&mode=$mode&noid=$id_item' onsubmit='return validasi()'>";
    echo "<div class='col-md-3'>";
    echo "
    <div class='col-md-6'>
      <div class='form-group'>
        <label>Dari Tanggal *</label>
        <input type='text' class='form-control' id='datepicker5' name='txtfrom'
          onchange='getListTrx()'>
      </div>
    </div>";
    echo "
    <div class='col-md-6'>
      <div class='form-group'>
        <label>Sampai *</label>
        <input type='text' class='form-control' id='datepicker6' name='txtto'
          onchange='getListTrx()'>
      </div>
    </div>";
    echo "<div class='form-group'>";
      echo "<label>$c_ninv *</label>";
      echo "<select class='form-control select2' style='width: 100%;' 
        name='txtinv_no' id='txtinv_no' onchange='getDataTrans()'>";
      echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>$c_dinv *</label>";
      echo "<input type='text' class='form-control' id='datepicker1' name='txtinv_date' placeholder='$c_mskan $c_dinv' value='$inv_date'>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>$c_nofak</label>";
      echo "<input type='text' class='form-control' 
        name='txtno_faktur' id='txtno_faktur' 
        placeholder='$c_mskan $c_nofak' value='$no_faktur'>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>$cap_supcus *</label>";
      echo "<input type='text' class='form-control' name='txtid_supplier' id='cbosupplier' value='$id_supplier' readonly>";
      #$sql = "select id_supplier isi, supplier tampil from mastersupplier";
      #echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>";
      #IsiCombo($sql,$id_supplier,$c_pil.' '.$cap_supcus);
      #echo "</select>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>$c_curr *</label>";
      $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
          where kode_pilihan='CURR' order by nama_pilihan";
      echo "<select class='form-control select2' style='width: 100%;' name='txtcurr' id='cbocurr' onchange='getBank(this.value)'>";
      IsiCombo($sql,$curr,$c_pil.' '.$c_curr);
      echo "</select>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>Qty *</label>";
      echo "<input type='text' class='form-control' 
        name='txtqty' id='txtqty' 
        value='$qty' readonly>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>$c_amt *</label>";
      echo "<input type='text' class='form-control' 
        name='txtamount' id='txtamount' 
        placeholder='$c_mskan $c_amt' value='$amount'>";
    echo "</div>";
    echo "<div class='form-group'>";
      echo "<label>$c_dtt *</label>";
      echo "<input type='text' id='datepicker2' class='form-control' name='txttt_date' placeholder='$c_mskan $c_dtt' value='$tt_date'>";
    echo "</div>";
    // echo "<div class='form-group'>";
    //   echo "<label>$c_byrke *</label>";
    //   $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
    //       where kode_pilihan='BYR_KE' order by nama_pilihan";
    //   echo "<select class='form-control select2' style='width: 100%;' name='txtbyrke'>";
    //   IsiCombo($sql,$byrke,$c_pil.' '.$c_byrke);
    //   echo "</select>";
    // echo "</div>";
    echo "</div>";
    echo "<div class='col-md-3'>";
    echo "<div class='form-group'>";
      echo "<label>$c_djt *</label>";
      echo "<input type='text' id='datepicker3' class='form-control' name='txtdue_date' placeholder='$c_mskan $c_djt' value='$due_date'>";
    echo "</div>";
    if($id_item!="")
    { echo "<div class='form-group'>";
        echo "<label>$c_dbyr</label>";
        echo "<input type='text' id='datepicker4' class='form-control' name='txtpay_date' placeholder='$c_mskan $c_dbyr' value='$pay_date'>";
      echo "</div>";
      echo "<div class='form-group'>";
        echo "<label>$c_nmbank</label>";
        echo "<select class='form-control select2' style='width: 100%;' id='cbobank' name='txtpay_bank'>";
        if ($id_item!="")
        { $sql = "select id isi,concat(nama_bank,' ',no_rek) tampil from masterbank 
            where curr='$curr' order by nama_bank";
          IsiCombo($sql,$pay_bank,'Pilih Nama Bank');
        }
        echo "</select>";
      echo "</div>";
    }
    echo "<button type='submit' name='submit' class='btn btn-primary'>$c_save</button>";
    echo "</form>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    # END COPAS ADD 3 HAPUS /DIV TERAKHIR
  ?>  
</div>
<!-- /.box -->
<!-- Default box -->
<div class="box">
  <div class="box-header with-border">
    <?php 
    echo "<h3 class='box-title'>$c_list $title</h3><br>";
    echo "<h3 class='box-title'>.</h3><br>";
    echo "<table id='example1' class='table table-bordered table-striped'>";
      echo "
      <thead>
        <tr>
          <th>$c_ninv</th>
          <th>$c_dinv</th>
          <th>No. Faktur Pajak</th>
          <th>Jenis Dok</th>
          <th>No. BC</th>
          <th>Tgl. BC</th>
          <th>$cap_supcus</th>
          <th>$c_curr</th>
          <th>$c_amt</th>
          <th>$c_dtt</th>
          <th>$c_djt</th>
          <th>$c_dbyr</th>
        </tr>
      </thead>";

      echo "<tbody>";
        $sql="SELECT a.*,s.supplier,jenis_dok,bcdate 
            FROM $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier 
            left join 
              (select $fld_trx,jenis_dok,bcno,bcdate from $tbl_trx group by $fld_trx) tbl_trx1
            on a.$fld_trx=tbl_trx1.$fld_trx ";
        #echo $sql;
        $query = mysql_query($sql);
        while($data = mysql_fetch_array($query))
        {   $due_date=fd_view($data['due_date']);
            $inv_date=fd_view($data['inv_date']);
            $tt_date=fd_view($data['tt_date']);
            if ($data['pay_date']!="0000-00-00") { $pay_date=fd_view($data['pay_date']); } else { $pay_date=""; }
            $amt=fn($data['amount'],0);
              
            echo "<tr>";
              if ($pay_date=="")
              { echo "<td><a href='../fin/?mod=2&mode=$mode&noid=$data[$fldid]'>$data[inv_no]</a></td>"; }
              else
              { echo "<td>$data[inv_no]</td>"; }
            echo "
              <td>$inv_date</td>
              <td>$data[no_faktur]</td>
              <td>$data[jenis_dok]</td>
              <td>$data[bcno]</td>
              <td>$data[bcdate]</td>
              <td>$data[supplier]</td>
              <td>$data[curr]</td>
              <td>$amt</td>
              <td>$tt_date</td>
              <td>$due_date</td>
              <td>$pay_date</td>
            </tr>";
        }
      echo "</tbody>";
    echo "</table>";
    ?>
  </div>
</div>