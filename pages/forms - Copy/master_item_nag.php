<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("mnuMasterItem","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$sql="select * from mastercompany";
$rsc=mysql_fetch_array(mysql_query($sql));
$nm_company = $rsc["company"];
$st_company = $rsc["status_company"];
$upload_image = $rsc["upload_image"];
$rkartu_stock = $rsc["kartu_stock"];
$mode = $_GET['mode'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if ($mode=="Bahan_Baku") 
{ if ($st_company=="GB" OR $st_company=="MULTI_WHS")
  { $titlenya="Barang"; }
  else
  { $titlenya=$c5; }
  $filternya="mattype in ('A','F','B')";
}
else if ($mode=="Scrap") 
{ $titlenya="Scrap"; 
  $filternya="mattype in ('S','L')";
}
else if ($mode=="Mesin") 
{ $titlenya=$caption[1]; 
  $filternya="mattype in ('M')";
}
else if ($mode=="WIP") 
{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; }  
  $filternya="mattype in ('C')";
}

# COPAS EDIT
if ($id_item=="")
{	$mattype = "";
	$brand = "";
  $mat_sour="";
  $thn_beli = "";
  $sn = "";
  $matclass = "";
	$goods_code = "";
	$itemdesc = "";
	if ($mode=="Mesin") { $color = "-"; } else { $color = ""; }
  if ($mode=="Mesin") { $size = "-"; } else { $size = ""; }
  $stock_card = "";
  $hscode = "";
  $notes = "";
  $id_gen = "";
  $id_item_odo = "";
}
else
{	$query = mysql_query("SELECT * FROM masteritem where id_item='$id_item' ORDER BY id_item ASC");
	$data = mysql_fetch_array($query);
	$mattype = $data['mattype'];
	$brand = $data['brand'];
  $mat_sour = $data['base_supplier'];
  if($mat_sour=="I") 
    { $mat_sour="IMPORT"; } 
  else if($mat_sour=="L")
    { $mat_sour="LOKAL"; }
  else
    { $mat_sour=""; }
  $thn_beli = $data['thn_beli'];
  $sn = $data['sn'];
  $matclass = $data['matclass'];
	$goods_code = $data['goods_code'];
	$itemdesc = $data['itemdesc'];
	$color = $data['color'];
	$size = $data['size'];
	$stock_card = $data['stock_card'];
  $hscode = $data['hscode'];
  $notes = $data['notes'];
  $id_gen = $data['id_gen'];
  $id_item_odo = $data['id_item_odo'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
		echo "var mattype = document.form.txtmattype.value;";
		echo "var goods_code = document.form.txtgoods_code.value;";
		echo "var itemdesc = document.form.txtitemdesc.value;";
		echo "var color = document.form.txtcolor.value;";
		echo "var size = document.form.txtsize.value;";
    echo "var gen_code = document.form.txtgen_code.value;";

		echo "if (mattype == '') { swal({ title: 'Tipe Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
		echo "else if (gen_code == '') { swal({ title: 'Kode Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
    echo "else if (goods_code == '') { document.form.txtgoods_code.focus(); swal({ title: 'Kode Barang Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
		echo "else if (itemdesc == '') { document.form.txtitemdesc.focus(); swal({ title: 'Nama Barang Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
		echo "else if (color == '') { document.form.txtcolor.focus(); swal({ title: 'Warna Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
		echo "else if (size == '') { document.form.txtsize.focus(); swal({ title: 'Ukuran Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
		echo "else valid = true;";
		echo "return valid;";
		echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getGenCode(cri_item)
  { jQuery.ajax
    ({
      url: "ajax_gen_code.php",
      method: 'POST',
      data: {cri_item: cri_item},
      dataType: 'json',
      success: function(response){
        $('#txtitemdesc').val(response[0]);
        $('#txtcolor').val(response[1]);
        $('#txtmatclass').val(response[2]);
        $('#txtsize').val(response[3]);
        $('#txtgoods_code').val(response[4]);  
      },
      error: function (request, status, error) {
          alert(request.responseText);
      },
    });
  };
  function getItem(cri_item)
  {   
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_group_item.php?mode=<?=$mode?>',
        data: "cri_item=" +cri_item,
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  };
</script>
<?php
# COPAS ADD
if ($mod=="2") {
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='save_data.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
          	echo "<label>$c13 *</label>";
          	$filternya2 = str_replace("mattype", "nama_pilihan", $filternya);
          	$sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Mat Type' 
              and $filternya2 and nama_pilihan!='B' order by nama_pilihan";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtmattype'>";
          	IsiCombo($sql,$mattype,$cpil.' '.$c13);
          	echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>$c14 $titlenya</label>";
          	echo "<input type='text' class='form-control' readonly name='txtmatclass' id='txtmatclass' placeholder='$cmas $c14' value='$matclass'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Pilih Kode *</label>";
            $sql = "SELECT j.id isi,
              concat(a.nama_group,' ',s.nama_sub_group,' ',
              d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
              g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) tampil
              FROM mastergroup a inner join mastersubgroup s on a.id=s.id_group
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              inner join masterwidth f on e.id=f.id_contents 
              inner join masterlength g on f.id=g.id_width
              inner join masterweight h on g.id=h.id_length
              inner join mastercolor i on h.id=i.id_weight
              inner join masterdesc j on i.id=j.id_color
              ORDER BY j.id DESC";
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtgen_code' onchange='getGenCode(this.value)'>";
            IsiCombo($sql,$id_gen,$cpil.' Kode');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Pilih Kode Lama *</label>";
            if($id_item_odo=="")
            {	$sql = "SELECT a.id_item_odo isi,
              concat(a.goods_code,' ',a.itemdesc) tampil
              FROM masteritem_odo a left join masteritem s on a.id_item_odo=s.id_item_odo
              where s.goods_code is null order by a.goods_code";
            }
            else
            {	$sql = "SELECT a.id_item_odo isi,
              concat(a.goods_code,' ',a.itemdesc) tampil
              FROM masteritem_odo a left join masteritem s on a.id_item_odo=s.id_item_odo
              where a.id_item_odo='$id_item_odo' order by a.goods_code";
            }
            echo "<select class='form-control select2' style='width: 100%;' 
              name='txtgen_code_odo'>";
            IsiCombo($sql,$id_item_odo,$cpil.' Kode Lama');
            echo "</select>";
          echo "</div>";
        echo "</div>";
        # PINDAH KOLOM
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c15 $titlenya *</label>";
            echo "<input type='text' class='form-control' readonly name='txtgoods_code' id='txtgoods_code' placeholder='$cmas $c15 $titlenya' value='$goods_code'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>$c16 $titlenya *</label>";
          	echo "<input type='text' class='form-control' readonly name='txtitemdesc' id='txtitemdesc' placeholder='$cmas $c16 $titlenya' value='$itemdesc'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	if ($mode!="Mesin")
            { echo "<label>$c17 *</label>";
              echo "<input type='text' class='form-control' readonly name='txtcolor' id='txtcolor' placeholder='$cmas $c17' value='$color'>";
            }
            else
            { echo "<input type='hidden' class='form-control' name='txtcolor' placeholder='$cmas $c17' value='$color'>"; 
              echo "<label>SN</label>";
              echo "<input type='text' class='form-control' name='txtsn' placeholder='Masukkan SN' value='$sn'>";
              echo "</div>";
              echo "<div class='form-group'>";
                echo "<label>Brand</label>";
                echo "<input type='text' class='form-control' name='txtbrand' placeholder='Masukkan Brand' 
                  value='$brand'>";
            }
          echo "</div>";
          echo "
          <div class='form-group'>
            <label>Material Source</label>
            <select class='form-control select2' style='width: 100%;' name='txtbase_supplier'>";
              $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                masterpilihan where kode_pilihan='Mat_Sour'";
              IsiCombo($sql,$mat_sour,'Pilih Material Source');
            echo "
            </select>
          </div>
          ";
        echo "</div>";
        # PINDAH KOLOM
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c18 *</label>"; 
            echo "<input type='text' class='form-control' readonly name='txtsize' id='txtsize' placeholder='$cmas $c18' value='$size'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>HS # *</label>"; 
            $sql = "select id isi,concat(kode_hs,' ',nama_hs) tampil from masterhs order by kode_hs";
            echo "<select class='form-control select2' style='width: 100%;' name='txthscode'>";
            IsiCombo($sql,$hscode,$cpil.' HS Code');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Notes</label>"; 
            echo "<input type='text' class='form-control' name='txtnotes' placeholder='$cmas Notes' value='$notes'>";
          echo "</div>";
          if ($mode=="Mesin")
          { echo "<div class='form-group'>";
              echo "<label>Tahun Pembelian</label>";
              echo "<input type='text' class='form-control' name='txtthn_beli' placeholder='$cmas Tahun Pembelian' value='$thn_beli'>";
            echo "</div>";
          }
          if ($nm_company!="PT. Nirwana Alabare Garment" and $nm_company!="Signal Bit")
          { echo "
              <div class='form-group'>
                <label>$c19</label>
                <input type='text' class='form-control' name='txtstock_card' placeholder='$cmas $c19' value='$stock_card'>
              </div>";
          }
          if ($upload_image=="1")
          { echo "<div class='form-group'>";
              echo "<label for='exampleInputFile'>Image File</label>";
              echo "<input type='file' name='txtfile' accept='.jpg'>";
            echo "</div>";
          }
          echo "<button type='submit' name='submit' class='btn btn-primary'>$csim</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
} else {
# END COPAS ADD
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
    <?php if($mod!="22L") {?>
    <a href='../forms/?mod=2&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
    <select class='form-control select2' style='width: 30%;' name='txtgrp' onchange='getItem(this.value)'>
      <?php 
        $sql = "select matclass isi,matclass tampil 
          from masteritem where $filternya group by matclass order by matclass ";
        IsiCombo($sql,'','Pilih Group');
      ?>
    </select>
    <?php } ?>  
  </div>
  <div class="box-body">
    <?php 
    if ($mode=="Mesin")
    {include "table_mesin.php";}
    elseif ($mode=="Bahan_Baku")
    {echo "<div id='detail_item'></div>";}
    else
    {include "table_item.php";} 
    ?>  
  </div>
</div>
<?php } ?>
