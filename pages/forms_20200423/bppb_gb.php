<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$mode = $_GET['mode'];
$mod = $_GET['mod'];
if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if ($mode=="Bahan_Baku") 
{ if ($st_company=="GB")
  { $titlenya="Barang"; }
  else
  { $titlenya="Bahan Baku"; }
  $filternya="a.mattype in ('A','F','B')";
}
else if ($mode=="Scrap") 
{ $titlenya="Scrap"; 
  $filternya="a.mattype in ('S','L')";
}
else if ($mode=="Mesin") 
{ $titlenya="Mesin"; 
  $filternya="a.mattype in ('M')";
}
else if ($mode=="WIP") 
{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; } 
  $filternya="a.mattype in ('C')";
}
else if ($mode=="FG") 
{ $titlenya="Barang Jadi"; 
  $filternya=" ";
}
else
{ echo "<script>
    alert('Terjadi kesalahan');
    window.location.href='index.php?mod=1';
  </script>";
}
# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var id_supplier = document.form.txtid_supplier.value;";
    echo "var invno = document.form.txtinvno.value;";
    echo "var bcno = document.form.txtbcno.value;";
    echo "var bcdate = document.form.txtbcdate.value;";
    echo "var bppbdate = document.form.txtbppbdate.value;";
    echo "var status_kb = document.form.txtstatus_kb.value;";

    echo "if (id_supplier == '') { alert('Dikirim Ke tidak boleh kosong'); document.form.txtid_supplier.focus();valid = false;}";
    echo "else if (invno == '') { alert('Nomor Inv/SJ tidak  boleh kosong'); document.form.txtinvno.focus();valid = false;}";
    echo "else if (status_kb == '') { alert('Jenis Dokumen tidak boleh kosong'); document.form.txtstatus_kb.focus();valid = false;}";
    echo "else if (bcno == '') { alert('Nomor Daftar tidak boleh kosong'); document.form.txtbcno.focus();valid = false;}";
    echo "else if (bcdate == '') { alert('Tgl. Daftar tidak boleh kosong'); document.form.txtbcdate.focus();valid = false;}";
    echo "else if (bppbdate == '') { alert('Tgl. BKB tidak boleh kosong'); document.form.txtbppbdate.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";

  	echo "var elem = document.getElementsByClassName('itemclass');";
  	echo "var names = [];";
		echo "for (var i = 0; i < elem.length; ++i) {
		  if (typeof elem[i].attributes.id !== 'undfined') 
	  	{	if(elem[i].attributes.id.value === 'itemajax') 
	  		{	var stock=document.getElementById('stockajax'.concat(i+1)).value;
          var qtynya=elem[i].value;
	  			if (Number(qtynya)>Number(stock))
          { alert('Stock tidak mencukupi Qty '.concat(qtynya).concat(' Sisa Stock ').concat(stock));
            return false;exit;
          }
        }
	  	}
		}";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListBC23(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          <?PHP
          if ($mode=="FG") 
          { echo "url: 'ajax.php?modeajax=cari_list_bc23_fg',"; }
          else
          { echo "url: 'ajax.php?modeajax=cari_list_bc23',"; }
          ?>
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbobc23no").html(html);
      }
  }
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          <?PHP
          if ($mode=="FG") 
          { echo "url: 'ajax.php?modeajax=view_list_bc23_fg',"; }
          else
          { echo "url: 'ajax.php?modeajax=view_list_bc23',"; }
          ?>
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
  }
</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_gb.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Filter Tgl *</label>";
            echo "<input type='text' class='form-control' name='txttglcut' id='datepicker1' 
            placeholder='Masukkan Filter Tgl' onchange='getListBC23(this.value)'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>BC23 No. *</label>";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtbcno23' id='cbobc23no' onchange='getListData(this.value)'>";
          	echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Keterangan</label>";
            echo "<input type='text' class='form-control' name='txtremark' placeholder='Masukkan Keterangan' value=''>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Dikirim Ke *</label>";
            $sql = "select id_supplier isi,supplier tampil from mastersupplier";
            echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>";
              IsiCombo($sql,'','Pilih Dikirim Ke');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Nomor Inv/SJ *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' placeholder='Masukkan Nomor Inv/SJ' value=''>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Nomor Mobil</label>";
            echo "<input type='text' class='form-control' name='txtnomor_mobil' placeholder='Masukkan Nomor Mobil' value=''>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Nomor Daftar *</label>";
            echo "<input type='text' class='form-control' name='txtbcno' placeholder='Masukkan Nomor Daftar' value=''>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tgl. Daftar *</label>";
            $bcdate=date('d M Y');
            echo "<input type='text' class='form-control' name='txtbcdate' id='datepicker3' placeholder='Masukkan Tgl. Daftar' value='$bcdate'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Jenis Dokumen *</label>";
            if ($st_company=="KITE") 
            { $status_kb_cri="Status KITE Out"; }
            else
            { $status_kb_cri="Status KB Out"; }
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                kode_pilihan='$status_kb_cri' order by nama_pilihan";
            #echo "<select class='form-control select2' style='width: 100%;' onchange='getTujuan(this.value)' name='txtstatus_kb'>";
            echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_kb'>";
            IsiCombo($sql,$status_kb,'Pilih Jenis Dokumen');
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Nomor BKB *</label>";
            echo "<input type='text' class='form-control' name='txtbppbno' readonly placeholder='Masukkan Nomor BKB' value=''>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tgl. BKB *</label>";
            echo "<input type='text' class='form-control' name='txtbppbdate' id='datepicker2' placeholder='Masukkan Tgl. BKB' value='$bcdate'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='box-body'>";
         echo "<div id='detail_item'></div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>