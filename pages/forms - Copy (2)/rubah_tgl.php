<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$user=$_SESSION['username'];

$datenya=date('d M Y');
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
		echo "var stglnya = document.form.txtdate.value;";
  	echo "var stgloldnya = document.form.txtdateold.value;";
  	echo "var tglnya = new Date(stglnya);";
  	echo "var tgloldnya = new Date(stgloldnya);";
  	echo "var from = document.form.txtfrom.value;";
  	echo "var tahun = document.form.txttahun.value;";
  	echo "if (tgloldnya != '' || tglnya != '')"; 
  		echo "{ var seldate = parseInt((tglnya - tgloldnya) / (1000 * 60 * 60 * 24)); }";
  	echo "else var seldate = 0;";
  	echo "var seldateok = Math.abs(seldate);";
  	echo "if (tahun == '') { alert('Tahun Transaksi tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
  	echo "else if (tglnya == '') { alert('Tanggal Baru tidak boleh kosong'); document.form.txtdate.focus();valid = false;}";
  	echo "else if (from == '') { alert('Nomor Transaksi tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
  	echo "else if (seldateok >= 7) { alert('Selisih Tanggal tidak boleh lebih dari 7 hari'); document.form.txtdate.focus();valid = false;}";
  	echo "else valid = true;";
  	echo "return valid;";
  	echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI
?>

<script type="text/javascript">
  function getAju(cri_item)
  {   var jenis_trans = document.form.txttrans.value;
      var crithn = document.form.txttahun.value;
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_aju2',
          data: {cri_item: crithn,cri_trans: jenis_trans},
          async: false
      }).responseText;
      if(html)
      { $("#cboaju").html(html);
      }
  }
  function getYear(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_tahun',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      { $("#cbothn").html(html);
      }
  }
  function getTgl(cri_item)
  { var jenis_trans = document.form.txttrans.value;
    jQuery.ajax
    ({  url: 'ajax.php?modeajax=cari_tgl',
        method: 'POST',
        data: {cri_item: cri_item,cri_trans: jenis_trans},
        success: function(response)
        { jQuery('#datepicker1').val(response);
        	jQuery('#dateold').val(response);  
        },
        error: function (request, status, error) 
        { alert(request.responseText);
        },
    });
  }
</script>
  
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='rubah_tgl_act.php' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Jenis Transaksi *</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
              where kode_pilihan='J_Tr' ";
            echo "<select class='form-control select2' style='width: 100%;' name='txttrans' onchange='getYear(this.value)'>";
            IsiCombo($sql,'','Pilih Jenis Transaksi');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tahun Transaksi *</label>";
            echo "<select class='form-control select2' style='width: 100%;' id='cbothn' name='txttahun' 
              onchange='getAju(this.value)'>";
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>No. Transaksi / No. Daftar *</label>";
            echo "<select class='form-control select2' style='width: 100%;' id='cboaju' name='txtfrom' onchange='getTgl(this.value)'>";
            echo "</select>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Tanggal Transaksi Lama</label>";
            echo "<input type='text' class='form-control' name='txtdateold' id='dateold' readonly>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Tanggal Transaksi Baru *</label>";
            echo "<input type='text' class='form-control' name='txtdate' id='datepicker1' value='$datenya'>";
          echo "</div>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Rubah</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>