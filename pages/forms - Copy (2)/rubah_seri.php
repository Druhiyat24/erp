<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];

# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";

  echo "var seri_baru = document.form.txtbaru.value;";
  echo "var seri_lama = document.form.txtfrom.value;";
  echo "var seri_lama_split = seri_lama.split('|');";
  echo "var seri_baru_split = seri_baru.split('|');";

  echo "if (seri_lama == '') { alert('Seri lama tidak boleh kosong'); document.form.txtfrom.focus();valid = false;}";
  echo "else if (seri_baru == '') { alert('Seri baru tidak boleh kosong'); document.form.txtbaru.focus();valid = false;}";
  echo "else if (Number(seri_lama_split['2']) != Number(seri_baru_split['1'])) { alert('Qty seri baru dan lama tidak sesuai'); document.form.txtbaru.focus();valid = false;}";
  echo "else valid = true;";
  echo "return valid;";
  echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getAju(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_seri_lama',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cboaju").html(html);
      }
  }
  function getSeriBaru(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_seri_baru',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cbobaru").html(html);
      }
  }
</script>

<?PHP
# COPAS ADD
echo "<div class='box'>";
echo "<div class='box-body'>";
echo "<div class='row'>";
echo "<form method='post' name='form' action='rubah_seri_act.php' onsubmit='return validasi()'>";
echo "<div class='col-md-3'>";
echo "<div class='form-group'>";
  echo "<label>Nomor BC 2.5 *</label>";
  $sql = "select a.bcno isi,a.bcno tampil from bppb a inner join bpb s on a.id_item=s.id_item inner join masteritem d on 
  	a.id_item=d.id_item where s.bcno='-' and a.qty>0 group by a.bcno order by a.bcdate desc";
  echo "<select class='form-control select2' style='width: 100%;' name='txttahun' onchange='getAju(this.value)'>";
  IsiCombo($sql,'','Pilih Nomor BC 2.5');
  echo "</select>";
echo "</div>";
echo "<div class='form-group'>";
  echo "<label>Seri Barang Lama *</label>";
  echo "<select class='form-control select2' style='width: 100%;' id='cboaju' name='txtfrom' onchange='getSeriBaru(this.value)'>";
  echo "</select>";
echo "</div>";
echo "<div class='form-group'>";
  echo "<label>Seri Barang Baru *</label>";
  echo "<select class='form-control select2' style='width: 100%;' id='cbobaru' name='txtbaru'>";
  echo "</select>";
echo "</div>";
echo "<div class='box-footer'>";
	echo "<button type='submit' name='submit' class='btn btn-primary'>Rubah</button>";
echo "</div>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
# END COPAS ADD
?>