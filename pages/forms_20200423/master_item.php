<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("mnuMasterItem","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$nm_company = flookup("company","mastercompany","company!=''");
$st_company = flookup("status_company","mastercompany","company!=''");
$upload_image = flookup("upload_image","mastercompany","company!=''");
$rkartu_stock = flookup("kartu_stock","mastercompany","company!=''");
$mode = $_GET['mode'];
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if ($mode=="Bahan_Baku") 
{ if ($st_company=="GB" OR $st_company=="MULTI_WHS")
  { $titlenya="Barang"; }
  else
  { $titlenya=$c5; }
  $filternya="a.mattype in ('A','F','B')";
  $mattype="";
}
else if ($mode=="Scrap") 
{ $titlenya="Scrap"; 
  $filternya="a.mattype in ('S','L')";
  $mattype="";
}
else if ($mode=="Mesin") 
{ $titlenya=$caption[1]; 
  $filternya="a.mattype in ('M')";
  $mattype="M";
}
else if ($mode=="WIP") 
{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; }  
  $filternya="a.mattype in ('C')";
  $mattype="C";
}

# COPAS EDIT
if ($id_item=="")
{	if($mattype!="") { $mattype = $mattype; } else { $mattype = ""; }
	$brand = "";
  $thn_beli = "";
  $sn = "";
  $matclass = "";
	$goods_code = "";
	$id_item_odo = "";
  $itemdesc = "";
	if ($mode=="Mesin") { $color = "-"; } else { $color = ""; }
  if ($mode=="Mesin") { $size = "-"; } else { $size = ""; }
  $stock_card = "";
  $moq = 0;
  $minstock=0;
  $base_curr = "";
  $base_price = "";
  $base_supplier = "";
}
else
{	$query = mysql_query("SELECT * FROM masteritem where id_item='$id_item' ORDER BY id_item ASC");
	$data = mysql_fetch_array($query);
	$mattype = $data['mattype'];
	$brand = $data['brand'];
  $thn_beli = $data['thn_beli'];
  $sn = $data['sn'];
  $matclass = $data['matclass'];
	$goods_code = $data['goods_code'];
	$id_item_odo = $data['id_item_odo'];
  $itemdesc = $data['itemdesc'];
	$color = $data['color'];
	if ($mode=="Mesin" AND $color=="") {$color="-";}
  $size = $data['size'];
	if ($mode=="Mesin" AND $size=="") {$size="-";}
  $stock_card = $data['stock_card'];
  $moq = $data['moq'];
  $minstock = $data['min_stock'];
  $base_curr = $data['base_curr'];
  $base_price = $data['base_price'];
  $base_supplier = $data['base_supplier'];
  if($base_supplier=="L")
  { $base_supplier="LOKAL"; }
  else if($base_supplier=="I")
  { $base_supplier="IMPORT"; }
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

		echo "if (mattype == '') { swal({ title: 'Tipe Tidak Boleh Kosong', imageUrl: '../../images/error.jpg' }); valid = false;}";
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
<script type='text/javascript'>
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
if ($mod=="2") {
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' enctype='multipart/form-data' action='save_data.php?mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
          	echo "<label>$c13 *</label>";
          	$filternya2 = str_replace("a.mattype", "nama_pilihan", $filternya);
          	$sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where kode_pilihan='Mat Type' and $filternya2 order by nama_pilihan";
          	echo "<select class='form-control select2' style='width: 100%;' name='txtmattype'>";
          	IsiCombo($sql,$mattype,$cpil.' '.$c13);
          	echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>$c14 $titlenya</label>";
            if($mode=="WIP")
          	{$cekmstcf=flookup("count(*)","mastercf","cfcode!=''");}
            else if($mode=="Mesin")
            {$cekmstcf="1";}
            else
            {$cekmstcf="0";}
            if($cekmstcf=="0")
            { echo "<input type='text' class='form-control' name='txtmatclass' placeholder='$cmas $c14' 
                value='$matclass'>";
            }
            else if ($mode=="Mesin")
            { $sql="select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
                where kode_pilihan='J_Modal' order by nama_pilihan";
              echo "<select class='form-control select2' style='width: 100%;' name='txtmatclass'>";
              IsiCombo($sql,$matclass,$cpil.' '.$c14);
              echo "</select>";
            }
            else
            { $sql="select cfdesc isi,cfdesc tampil from mastercf group by cfdesc order by cfdesc";
              echo "<select class='form-control select2' style='width: 100%;' name='txtmatclass'>";
              IsiCombo($sql,$matclass,$cpil.' '.$c14);
              echo "</select>";
            }
          echo "</div>";
          echo "<div class='form-group'>";
          	echo "<label>$c15 $titlenya *</label>";
          	echo "<input type='text' class='form-control' name='txtgoods_code' placeholder='$cmas $c15 $titlenya' value='$goods_code'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>Pilih Kode Lama *</label>";
            if($id_item_odo=="")
            { $sql = "SELECT a.id_item_odo isi,
              concat(a.goods_code,' ',a.itemdesc) tampil
              FROM masteritem_odo a left join masteritem s on a.id_item_odo=s.id_item_odo
              where s.goods_code is null order by a.goods_code";
            }
            else
            { $sql = "SELECT a.id_item_odo isi,
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
          	echo "<label>$c16 $titlenya *</label>";
          	echo "<input type='text' class='form-control' name='txtitemdesc' placeholder='$cmas $c16 $titlenya' value='$itemdesc'>";
          echo "</div>";
          echo "<div class='form-group'>";
          	if ($mode!="Mesin")
            { echo "<label>$c17 *</label>";
              echo "<input type='text' class='form-control' name='txtcolor' placeholder='$cmas $c17' value='$color'>";
            }
            else
            { echo "<input type='hidden' class='form-control' name='txtcolor' placeholder='$cmas $c17' value='$color'>"; 
              echo "<label>SN</label>";
              echo "<input type='text' class='form-control' name='txtsn' placeholder='Masukkan SN' value='$sn'>";
              echo "</div>";
              echo "<div class='form-group'>";
                echo "<label>Minimum Stock</label>";
                echo "<input type='number' class='form-control' name='txtminst' placeholder='Masukan Minimum Stock' value='$minstock'>";
              echo "</div>";
              echo "<div class='form-group'>";
                echo "<label>Brand</label>";
                echo "<input type='text' class='form-control' name='txtbrand' placeholder='Masukkan Brand' value='$brand'>";
            }
          echo "</div>";
          echo "<div class='form-group'>";
          	if ($mode!="Mesin" or $nm_company=="PT. Bangun Sarana Alloy")
            { echo "<label>$c18 *</label>"; 
              echo "<input type='text' class='form-control' name='txtsize' placeholder='$cmas $c18' value='$size'>";
            }
            else
            { echo "<input type='hidden' class='form-control' name='txtsize' placeholder='$cmas $c18' value='$size'>"; }
          echo "</div>";        echo "</div>";
        # PINDAH KOLOM
        echo "<div class='col-md-3'>";
          if ($mode=="Mesin")
          { echo "<div class='form'''>";
              echo "<label>Tahun Pembelian</label>";
              echo "<input type='text' class='form-control' name='txtthn_beli' placeholder='$cmas Tahun Pembelian' value='$thn_beli'>";
            echo "</div>";
          }
          if ($nm_company!="PT. Nirwana Alabare Garment")
          { echo "
              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>$c19</label>
                    <input type='text' class='form-control' name='txtstock_card' placeholder='$cmas $c19' value='$stock_card'>
                  </div>
                </div>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>MOQ</label>
                    <input type='text' class='form-control' name='txtmoq' placeholder='$cmas MOQ' value='$moq'>
                  </div>
                </div>
              </div>
              <div class='form-group'>
                <label>Material Source</label>
                <select class='form-control select2' style='width: 100%;' name='txtbase_supplier'>";
                  $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Mat_Sour'";
                  IsiCombo($sql,$base_supplier,'Pilih Material Source');
                echo "
                </select>
              </div>
              <div class='row'>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Base Curr</label>
                    <input type='text' class='form-control' name='txtbase_curr' placeholder='$cmas Base Curr' 
                      value='$base_curr'>
                  </div>
                </div>
                <div class='col-md-6'>
                  <div class='form-group'>
                    <label>Base Price</label>
                    <input type='text' class='form-control' name='txtbase_price' placeholder='$cmas Base Price' 
                      value='$base_price'>
                  </div>
                </div>
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
# END COPAS ADD
} else { 
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Data <?PHP echo $titlenya; ?></h3>
    <a href='../forms/?mod=2&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New
    </a>
    <?php if($mode!="Mesin") { ?>
      <select class='form-control select2' style='width: 30%;' name='txtgrp' onchange='getItem(this.value)'>
        <?php 
          $sql = "select matclass isi,matclass tampil 
            from masteritem a where $filternya group by matclass order by matclass ";
          IsiCombo($sql,'','Pilih Group');
        ?>
      </select>
    <?php } ?>
  </div>
  <div class="box-body">
    <?php 
    if ($mode=="Mesin")
    {echo include "table_mesin.php";}
    else
    {echo "<div id='detail_item'></div>";} 
    ?>  
  </div>
</div>
<?php } ?>