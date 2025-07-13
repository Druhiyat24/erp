<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 
# START CEK HAK AKSES KEMBALI
if ($mode=="FG")
{	$akses = flookup("mnuBPBFG","userpassword","username='$user'");	}
else
{	$akses = flookup("mnuBPB","userpassword","username='$user'");	}
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod = $_GET['mod'];
$nm_company = flookup("company","mastercompany","company!=''");
$st_company = flookup("status_company","mastercompany","company!=''");
if ($nm_company=="PT. Gaya Indah Kharisma")
{ $sql="update masteritem set matclass=mattype where matclass='-'"; 
  insert_log($sql,$user);
}
if (isset($_GET['noid'])) {$bpbno = $_GET['noid']; } else {$bpbno = "";}
if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}
if (isset($_GET['kp'])) {$kp = $_GET['kp'];} else {$kp = "";}

if ($mode=="Bahan_Baku" OR $mode=="Bahan Baku (Import)") 
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
{ $titlenya="Mesin"; 
  $filternya="mattype in ('M')";
}
else if ($mode=="WIP") 
{ if ($nm_company=="PT. Youngil Leather Indonesia") { $titlenya="Chemical"; } else { $titlenya="WIP"; } 
  $filternya="mattype in ('C')";
}
elseif ($mode=="FG") 
{ $titlenya="Barang Jadi"; 
  $filternya=" ";
}
else
{ echo "<script>
    alert('Terjadi kesalahan');
    window.location.href='index2.php';
  </script>";
}

# COPAS EDIT
if ($bpbno=="" AND $id_item=="")
{ $id_item = "";
  $qty = "";
  $unit = "";
  $curr = "";
  $price = "";
  $remark = "";
  $jam_masuk = "";
  $berat_bersih = "";
  $berat_kotor = "";
  $nomor_mobil = "";
  $pono = "";
  $kpno = "";
  $id_supplier = "";
  $invno = "";
  $bcno = "";
  $bcdate = date('d M Y');
  $bcaju = "";
  $tglaju = date('d M Y');
  $bpbno = "";
  $bpbdate = date('d M Y');
  $status_kb = "";
  $txttujuan = "";
  $data_oto="N";
}
else if ($bpbno<>"" AND $id_item=="")
{ $id_item = "";
  $qty = "";
  $unit = "";
  $curr = "";
  $price = "";
  $remark = "";
  $jam_masuk = "";
  $berat_bersih = "";
  $berat_kotor = "";
  $nomor_mobil = "";
  
  $query = mysql_query("SELECT * FROM bpb where bpbno='$bpbno' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $pono = $data['pono'];
  $kpno = "";
  $id_supplier = $data['id_supplier'];
  $invno = $data['invno'];
  $bcno = $data['bcno'];
  if ($bcno=="") {  $bcno="-";  }
  if ($data['bcdate']=="0000-00-00")
  { $bcdate = date('d M Y',strtotime($data['bpbdate'])); }
  else
  { $bcdate = date('d M Y',strtotime($data['bcdate'])); }
  $bcaju = $data['nomor_aju'];
  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));
  $bpbno = $data['bpbno'];
  $bpbdate = date('d M Y',strtotime($data['bpbdate']));
  $status_kb = $data['jenis_dok'];
  $txttujuan = $data['tujuan'];
  $data_oto="N";
}
else
{ $query = mysql_query("SELECT * FROM bpb where bpbno='$bpbno' and id_item='$id_item' and kpno='$kp' ORDER BY id_item ASC");
  $data = mysql_fetch_array($query);
  $id_item = $data['id_item'];
  $qty = $data['qty'];
  $unit = $data['unit'];
  $curr = $data['curr'];
  $price = $data['price'];
  $remark = $data['remark'];
  $jam_masuk = $data['jam_masuk'];
  $berat_bersih = $data['berat_bersih'];
  $berat_kotor = $data['berat_kotor'];
  $nomor_mobil = $data['nomor_mobil'];
  $pono = $data['pono'];
  $kpno = $data['kpno'];
  $id_supplier = $data['id_supplier'];
  $invno = $data['invno'];
  $bcno = $data['bcno'];
  if ($bcno=="") {  $bcno="-";  }
  if ($data['bcdate']=="0000-00-00")
  { $bcdate = date('d M Y',strtotime($data['bpbdate'])); }
  else
  { $bcdate = date('d M Y',strtotime($data['bcdate'])); }
  $bcaju = $data['nomor_aju'];
  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));
  $bpbno = $data['bpbno'];
  $bpbdate = date('d M Y',strtotime($data['bpbdate']));
  $status_kb = $data['jenis_dok'];
  $txttujuan = $data['tujuan'];
  if ($data['kpno']!="" AND $data['bcdate']=="0000-00-00" AND $data['jenis_dok']=="INHOUSE")
  { $data_oto="Y"; }
  else
  { $data_oto="N"; }
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "var id_item = document.form.txtid_item.value;";
    echo "var pono = document.form.txtpono.value;";
    echo "var id_supplier = document.form.txtid_supplier.value;";
    echo "var invno = document.form.txtinvno.value;";
    if ($nm_company=="PT. Seyang Indonesia" OR $nm_company=="PT. Sandrafine Garment")
    { echo "var kpno = document.form.txtkpno.value;"; }
    if ($st_company=="MULTI_WHS")
    { echo "var gudang = document.form.txtid_gudang.value;"; }
    else
    { echo "var bcno = document.form.txtbcno.value;";
      echo "var bcdate = document.form.txtbcdate.value;";
      echo "var status_kb = document.form.txtstatus_kb.value;";
    }
    echo "var bpbdate = document.form.txtbpbdate.value;";
    echo "var qty = document.form.txtqty.value;";
    echo "var berat_bersih = document.form.txtberat_bersih.value;";
    echo "var unit = document.form.txtunit.value;";
    echo "var cek_data_oto = '$data_oto';";
    if ($bpbno=="" AND $id_item=="")
    {	$qty_old=0; }
    else
    { $query = mysql_query("SELECT * FROM bpb where bpbno='$bpbno' and 
    		id_item='$id_item' and kpno='$kpno' ORDER BY id_item ASC");
      $data = mysql_fetch_array($query);
      $qty_old=$data['qty'];
    }
    if ($mode!="FG" AND $bpbno!="")
    {	$query = mysql_query("SELECT sum(qty) qty_out FROM bppb where bppbno not like 'SJ-FG%' and 
  		id_item='$id_item' and bppbdate>='$bpbdate' ORDER BY id_item ASC");
      $data = mysql_fetch_array($query);
      $qty_out=$data['qty_out'];
    }
    else if ($bpbno!="")
   	{	$query = mysql_query("SELECT sum(qty) qty_out FROM bppb where bppbno like 'SJ-FG%' and 
  		id_item='$id_item' and bppbdate>='$bpbdate' ORDER BY id_item ASC");
      $data = mysql_fetch_array($query);
      $qty_out=$data['qty_out'];
    }
    else
    { $qty_out=0; }
    if ($qty_out=="0")
    { echo "var selisihqty = $qty_out;"; }
    else
    { echo "var selisihqty = $qty_old - qty;"; }
    echo "var selisihqty = $qty_old - qty;";
    echo "var stock = document.form.txtstock.value;";
    
    $img_alert = "imageUrl: '../../images/error.jpg'";    
    echo "if (id_item == '') 
      { valid = false; 
        swal({ title: 'Item Tidak Boleh Kosong', $img_alert }); 
      }";
    if ($nm_company=="PT. Seyang Indonesia" OR $nm_company=="PT. Sandrafine Garment")
    { echo "else if (kpno == '') { document.form.txtkpno.focus();
        swal({ title: 'Nomor PK Tidak Boleh Kosong', $img_alert });valid = false;}"; 
    }
    echo "else if (berat_bersih == '') 
      { document.form.txtberat_bersih.focus(); valid = false;
        swal({ title: 'Berat Bersih Tidak Boleh Kosong', $img_alert }); 
      }";
    if ($st_company=="MULTI_WHS")
    { echo "else if (gudang == '') 
  					{ valid = false;
  						swal({ title: 'Gudang Tidak Boleh Kosong', $img_alert });
  					}";
    }
    else
    { echo "else if (status_kb == '') 
				{ valid = false;
					swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', $img_alert });
				}";
      echo "else if (bcno == '') 
    		{ document.form.txtbcno.focus();valid = false;
    			swal({ title: 'Nomor Daftar Tidak Boleh Kosong', $img_alert });
    		}";
      echo "else if (bcdate == '') 
        { document.form.txtbcdate.focus();valid = false;
          swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', $img_alert });
        }";
      echo "else if (new Date(bcdate) > new Date()) 
        { document.form.txtbcdate.focus();valid = false;
          swal({ title: 'Tgl. Daftar Tidak Boleh Melebihi Hari Ini', $img_alert });
        }";
    }
    echo "else if (cek_data_oto == 'Y') 
    	{ document.form.txtstatus_kb.focus();valid = false;
    		swal({ title: 'Data Tidak Bisa Diubah. Transaksi Ini Otomatis Dari System', $img_alert });
    	}";
    echo "else if (pono == '') 
    	{ document.form.txtpono.focus();valid = false;
    		swal({ title: 'PO Tidak Boleh Kosong', $img_alert });
    	}";
    echo "else if (id_supplier == '') 
    	{ valid = false;
    		swal({ title: 'Supplier Tidak Boleh Kosong', $img_alert });
    	}";
    echo "else if (invno == '') 
    	{ document.form.txtinvno.focus();valid = false;
    		swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', $img_alert });
    	}";
    echo "else if (bpbdate == '') 
      { document.form.txtbpbdate.focus(); valid = false; 
        swal({ title: 'Tgl. Transaksi Tidak Boleh Kosong', $img_alert }); 
      }";
    echo "else if (new Date(bpbdate) > new Date()) 
    	{ document.form.txtbpbdate.focus();valid = false;
    		swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', $img_alert });
    	}";
    echo "else if (qty == '' || Number(qty)<0) 
    	{ document.form.txtqty.focus();valid = false;
    		swal({ title: 'Jumlah Tidak Boleh Kosong / Lebih Kecil Nol', $img_alert });
    	}";
    if ($bpbno!="" AND $id_item!="")
    { echo "else if (selisihqty>stock || stock<0) { document.form.txtqty.focus();
        swal({ title: 'Jumlah Tidak Mencukupi $qty_out', $img_alert });valid = false;}";  
    }
    echo "else if (unit == '') { swal({ title: 'Satuan Tidak Boleh Kosong', $img_alert });;valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>

<script type="text/javascript">
  function getSat(cri_item)
  {   var bpbdate = document.form.txtbpbdate.value;
      var bpbno = document.form.txtbpbno.value;
      var id_item = document.form.txtid_item.value;
      var html = $.ajax
      ({  type: "POST",
          <?PHP
          if ($mode=="FG")
          { echo "url: 'ajax.php?modeajax=cari_satuan_bpb_fg',"; }
          else
          { echo "url: 'ajax.php?modeajax=cari_satuan_bpb',"; }
          ?>
          data: {cri_item: id_item,cri_date: bpbdate,cri_bppbno: bpbno},
          async: false
      }).responseText;
      if(html)
      {
          $("#cbosat").html(html);
      }

      jQuery.ajax({
          <?PHP
          if ($id_item=="")
          { if ($mode=="FG")
            { echo "url: 'ajax.php?modeajax=cari_sisa_fg_bpb_new',"; }
            else
            { echo "url: 'ajax.php?modeajax=cari_sisa_bpb_new',"; }
          }
          else
          { if ($mode=="FG")
            { echo "url: 'ajax.php?modeajax=cari_sisa_fg_bpb',"; }
            else
            { echo "url: 'ajax.php?modeajax=cari_sisa_bpb',"; }
          }
          ?>
          method: 'POST',
          data: {cri_item: id_item,cri_date: bpbdate,cri_bppbno: bpbno},
          success: function(response){
              jQuery('#txtsisa').val(response);  
          },
          error: function (request, status, error) {
              alert(request.responseText);
          },
      });
      <?php 
      if ($mode=="FG" AND $nm_company=="PT. Sandrafine Garment")
      {?>
        jQuery.ajax({
          url: 'ajax.php?modeajax=cari_kp',
          method: 'POST',
          data: {cri_item: id_item}, 
          success: function(response){
              jQuery('#txtkpno').val(response);  
          },
          error: function (request, status, error) {
              alert(request.responseText);
          },
        });  
      <?php }
      ?>
      //jQuery.ajax({
      //    <?PHP
      //    if ($mode=="FG")
      //    { echo "url: 'ajax.php?modeajax=cari_sisa_nett_fg',"; }
      //    else
      //    { echo "url: 'ajax.php?modeajax=cari_sisa_nett',"; }
      //    ?>
      //    method: 'POST',
      //    data: {cri_item: id_item,cri_date: bpbdate,cri_bppbno: bpbno},
      //    success: function(response){
      //        jQuery('#txtsisa_nett').val(response);  
      //    },
      //    error: function (request, status, error) {
      //        alert(request.responseText);
      //    },
      //});
  }
  function getTujuan(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax.php?modeajax=cari_tujuan',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {
          $("#cbotujuan").html(html);
      }
  }
  function getListItem(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          <?PHP
          if ($mode=="FG") 
          { echo "url: 'ajax.php?modeajax=cari_list_item_fg',"; }
          else if ($mode=="WIP") 
          { echo "url: 'ajax.php?modeajax=cari_list_item_wip',"; }
          else if ($mode=="Mesin") 
          { echo "url: 'ajax.php?modeajax=cari_list_item_mesin',"; }
          else if ($mode=="Scrap") 
          { echo "url: 'ajax.php?modeajax=cari_list_item_scrap',"; }
          else if ($mod=="51a") 
          { echo "url: 'ajax.php?modeajax=cari_list_item_bom',"; }
          else
          { echo "url: 'ajax.php?modeajax=cari_list_item',"; }
          ?>
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      { 
          $("#cboid_item").html(html);
      }
      <?php 
      if ($mode=="FG" AND $nm_company=="PT. Sandrafine Garment")
      {?>
        jQuery.ajax({
          url: 'ajax.php?modeajax=copy_po_buyer',
          method: 'POST',
          data: {cri_item: cri_item}, 
          success: function(response){
              jQuery('#txtpono').val(response);  
          },
          error: function (request, status, error) {
              alert(request.responseText);
          },
        });  
      <?php }
      ?>
  }
</script>

<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_bpb.php?mod=$mod&mode=$mode&noid=$bpbno&id=$id_item&kp=$kp' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            if ($id_item!="") 
            { if ($mode=="FG")
              { $query = mysql_query("SELECT * FROM masterstyle where id_item='$id_item' ORDER BY id_item ASC");
                $data = mysql_fetch_array($query);
                $matlcassnya = $data['itemname'];
              }
              else
              { $query = mysql_query("SELECT * FROM masteritem where id_item='$id_item' ORDER BY id_item ASC");
                $data = mysql_fetch_array($query);
                $matlcassnya = $data['matclass'];
              }
              $sqlwhere="where id_item='$id_item'"; $sqlwhere2=" and id_item='$id_item'"; 
            } 
            else 
            { $matlcassnya="";
              $sqlwhere=""; $sqlwhere2=""; 
            }
            if ($mode=="FG")
            { if ($nm_company=="PT. Bangun Sarana Alloy")
              { $sql = "select itemname isi,concat(itemname,'|',color) tampil from masterstyle $sqlwhere 
                  where non_aktif='N' group by itemname order by itemname "; 
              }
              else if ($nm_company=="PT. Tun Hong")
              { $sql = "select kpno isi,concat(kpno,'|',itemname) tampil from masterstyle $sqlwhere 
                  where non_aktif='N' group by kpno order by kpno "; 
              }
              else if ($nm_company=="PT. Sandrafine Garment")
              { $sql = "select buyerno isi,buyerno tampil from masterstyle $sqlwhere 
                  where non_aktif='N' group by buyerno order by buyerno "; 
              }
              else
              { $sql = "select itemname isi,concat(goods_code,'|',itemname,'|',color,'|',size) tampil from 
                  masterstyle $sqlwhere 
                  where non_aktif='N' group by goods_code,itemname order by goods_code,itemname "; 
              }
              if ($nm_company=="PT. Sandrafine Garment")
              { echo "<label>Buyer PO *</label>"; }
              else
              { echo "<label>Deskripsi $titlenya *</label>"; }
              echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";
              if ($nm_company=="PT. Sandrafine Garment")
              { IsiCombo($sql,$matlcassnya,'Pilih Buyer PO'); }
              else
              { IsiCombo($sql,$matlcassnya,'Pilih Deskripsi '.$titlenya); }
              echo "</select>";
            }
            else
            { if ($st_company=="GB")
              { $sql = "select itemdesc isi,itemdesc tampil from masteritem where $filternya $sqlwhere2 
                  group by itemdesc order by itemdesc "; 
                echo "<label>Deskripsi $titlenya *</label>";
                echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";
                IsiCombo($sql,$matlcassnya,'Pilih Deskripsi '.$titlenya);
                echo "</select>";
              }
              else
              { if ($mod=="51a")
                { echo "<label>Nomor Order *</label>"; 
                  echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";
                  $sql="select a.id_item isi,concat(a.kpno,'|',a.styleno,'|',a.buyerno) 
                    tampil from masterstyle a inner join bom s on a.id_item=s.id_item_fg
                    group by kpno order by kpno";
                  IsiCombo($sql,$matlcassnya,'Pilih Nomor Order');
                }
                else
                { echo "<label>$c14 $titlenya *</label>"; 
                  echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";
                  $sql = "select matclass isi,matclass tampil from masteritem where $filternya $sqlwhere2 
                    group by matclass order by matclass "; 
                  IsiCombo($sql,$matlcassnya,$cpil.' '.$c14.' '.$titlenya);
                }
                echo "</select>";
              }
            }
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$titlenya *</label>";
            echo "<select class='form-control select2' style='width: 100%;' id='cboid_item' onchange='getSat(this.value)' name='txtid_item'>";
            if ($id_item!="")
            { if ($mode=="FG")
              { $sqlwhere="where id_item='$id_item'";
                if ($nm_company=="PT. Geumcheon Indo")
                { $sql = "select id_item isi,concat(buyerno) tampil from masterstyle $sqlwhere "; }
                else if ($nm_company=="PT. Youngil Leather Indonesia")
                  { $sql = "select id_item isi,concat(goods_code,'|',color,'|',size) tampil from masterstyle $sqlwhere "; }
                  else
                { $sql = "select id_item isi,concat(goods_code) tampil from masterstyle $sqlwhere "; }
              }
              else
              { if ($nm_company=="PT. Youngil Leather Indonesia")
                { $sql = "select id_item isi,concat(goods_code,'|',itemdesc,'|',color,'|',size) tampil from masteritem where id_item='$id_item' "; }
                else
                { $sql = "select id_item isi,concat(goods_code,'|',itemdesc) tampil from masteritem where id_item='$id_item' "; }
              }
              IsiCombo($sql,$id_item,'Pilih Klasifikasi '.$titlenya);
            }
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c31 *</label>";
            echo "<input type='text' class='form-control' name='txtqty' placeholder='$cmas $c31' value='$qty'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c32 *</label>";
            echo "<select id='cbosat' class='form-control select2' style='width: 100%;' name='txtunit'>";
            if ($bpbno!="" AND $id_item!="")
            { $sql = "select unit isi,unit tampil 
                from bpb where id_item='$id_item' and bpbno='$bpbno' group by unit";
              IsiCombo($sql,$unit,'Pilih Satuan');
            }
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c33</label>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan 
                  where kode_pilihan='Curr'";
            echo "<select class='form-control select2' style='width: 100%;' name='txtcurr'>";
            IsiCombo($sql,$curr,$cpil.' '.$c33);
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c34</label>";
            echo "<input type='text' class='form-control' name='txtprice' placeholder='$cmas $c34' value='$price'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c40 *</label>";
            if ($mode=="FG" AND $nm_company=="PT. Sandrafine Garment")
            { $read_only = "readonly"; } else { $read_only = ""; }
            echo "<input type='text' id='txtpono' $read_only class='form-control' name='txtpono' placeholder='$cmas $c40' value='$pono'>";
          echo "</div>";
          if ($nm_company=="PT. Seyang Indonesia" OR 
            $nm_company=="PT. Sandrafine Garment")
          { echo "<div class='form-group'>";
              echo "<label>Nomor PK *</label>";
              echo "<input type='text' id='txtkpno' class='form-control' $read_only name='txtkpno' placeholder='$cmas Nomor PK' value='$kpno'>";
            echo "</div>";
          }
          echo "<div class='form-group'>";
            if ($nm_company=="PT. Multi Sarana Plasindo")
            { echo "<label>Gudang</label>";
              echo "<input type='text' class='form-control' name='txtremark' placeholder='Masukan Gudang' value='$remark'>";  
            }
            else
            { echo "<label>$c35</label>";
              echo "<input type='text' class='form-control' name='txtremark' placeholder='$cmas $c35' value='$remark'>";  
            }
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c36</label>";
            echo "<input type='text' class='form-control' name='txtjam_masuk' placeholder='$cmas $c36' value='$jam_masuk'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c37 (Kg) *</label>";
            echo "<input type='text' class='form-control' name='txtberat_bersih' placeholder='$cmas $c37 (Kg)' value='$berat_bersih'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c38 (Kg)</label>";
            echo "<input type='text' class='form-control' name='txtberat_kotor' placeholder='$cmas $c38 (Kg)' value='$berat_kotor'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c39</label>";
            echo "<input type='text' class='form-control' name='txtnomor_mobil' placeholder='$cmas $c39' value='$nomor_mobil'>";
          echo "</div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>Supplier *</label>";
            $sql = "select id_supplier isi,supplier tampil from mastersupplier";
            echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>";
            IsiCombo($sql,$id_supplier,$cpil.' Supplier');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c41 *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' placeholder='$cmas $c41' value='$invno'>";
          echo "</div>";
          if ($st_company=="MULTI_WHS")
          { echo "<div class='form-group'>";
              echo "<label>Gudang *</label>";
              $sql = "select id_supplier isi,supplier tampil from mastersupplier 
                where tipe_sup='G' ";
              echo "<select class='form-control select2' style='width: 100%;' name='txtid_gudang'>";
              IsiCombo($sql,$id_whs,$cpil.' Gudang');
              echo "</select>";
            echo "</div>";
          }
          else
          { echo "<div class='form-group'>";
              echo "<label>Nomor Aju</label>";
              echo "<input type='text' class='form-control' name='txtbcaju' placeholder='Masukan Nomor Aju' value='$bcaju'>";
            echo "</div>";
            echo "<div class='form-group'>";
              echo "<label>Tanggal Aju</label>";
              echo "<input type='text' class='form-control' id='datepicker3' name='txttglaju' placeholder='Masukan Tanggal Aju' value='$tglaju'>";
            echo "</div>";
            echo "<div class='form-group'>";
              echo "<label>$c42 *</label>";
              echo "<input type='text' class='form-control' name='txtbcno' placeholder='$cmas $c42' value='$bcno'>";
            echo "</div>";
            echo "<div class='form-group'>";
              echo "<label>$c43 *</label>";
              echo "<input type='text' class='form-control' id='datepicker1' name='txtbcdate' placeholder='$cmas $c43' value='$bcdate'>";
            echo "</div>";
          }
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c44</label>";
            echo "<input type='text' class='form-control' name='txtbpbno' placeholder='$cmas $c44' readonly value='$bpbno'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c45 *</label>";
            echo "<input type='text' class='form-control' name='txtbpbdate' id='datepicker2' onchange='getSat(this.value)' placeholder='$cmas $c45' value='$bpbdate'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c31 Stock</label>";
            if ($bpbno!="" AND $id_item!="")
            { if (substr($bpbno,0,2)=="FG")
              { $sisa = get_stock_tgl("BPB","FG",$id_item,fd($bpbdate),$bpbno); }
              else
              { $sisa = get_stock_tgl("BPB",substr($bpbno,0,1),$id_item,fd($bpbdate),$bpbno); }
              echo "<input type='text' class='form-control' id='txtsisa' name='txtstock' value='$sisa' readonly>";
            }
            else
            { echo "<input type='text' class='form-control' id='txtsisa' name='txtstock' readonly>";  }
          echo "</div>";
          #echo "<div class='form-group'>";
          #  echo "<label>Stock $c37</label>";
          #  if ($bpbno!="" AND $id_item!="")
          #  { if (substr($bpbno,0,2)=="FG")
          #    { $sisa = flookup("stock_nett","stock","id_item='$id_item' and mattype='FG'"); }
          #    else
          #    { $sisa = flookup("stock_nett","stock","id_item='$id_item' and mattype!='FG'"); }
          #    echo "<input type='text' class='form-control' id='txtsisa_nett' name='txtstock_nett' value='$sisa' readonly>";
          #  }
          #  else
          #  { echo "<input type='text' class='form-control' id='txtsisa_nett' name='txtstock_nett' readonly>";  }
          #echo "</div>";
          if ($st_company!="MULTI_WHS")
          { echo "<div class='form-group'>";
              echo "<label>$c46 *</label>";
              if ($st_company=="KITE") 
              { $status_kb_cri="Status KITE In"; }
              else
              { $status_kb_cri="Status KB In"; }
              $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 
                    kode_pilihan='$status_kb_cri' order by nama_pilihan";
              echo "<select class='form-control select2' style='width: 100%;' onchange='getTujuan(this.value)' name='txtstatus_kb'>";
              IsiCombo($sql,$status_kb,$cpil.' '.$c46);
              echo "</select>";
            echo "</div>";
            echo "<div class='form-group'>";
              echo "<label>$c47</label>";
              echo "<select class='form-control select2' style='width: 100%;' id='cbotujuan' name='txttujuan'>";
              if ($bpbno!="")
              { $sql = "select nama_pilihan isi,nama_pilihan tampil 
              from masterpilihan where kode_pilihan='$status_kb'";
              IsiCombo($sql,trim($txttujuan),$cpil.' '.$c47);
              }
              echo "</select>";
            echo "</div>";
          }
          echo "<button type='submit' name='submit' class='btn btn-primary'>$csim</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>

<div class="box">
  <div class="box-header">
      <h3 class="box-title">Detil <?PHP echo $titlenya; ?></h3>
  </div>
  <div class="box-body">
    <table id="example1" class="table table-bordered table-striped">
      <thead>
        <tr>
  		      <th>No</th>
            <th>Kode <?PHP echo $titlenya;?></th>
            <th><?php echo $c48; ?></th>
            <th><?php echo $c31; ?></th>
            <th><?php echo $c32; ?></th>
            <th><?php echo $c33; ?></th>
            <th><?php echo $c34; ?></th>
            <th><?php echo $c49; ?></th>
            <th><?php echo $c35; ?></th>
            <th width="105px"><?php echo "Aksi"; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
		    $query = mysql_query("SELECT a.kpno,a.curr,a.price,a.id_item,s.goods_code,$fld_desc itemdesc,a.qty,a.unit 
              FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item where bpbno='$bpbno' ORDER BY a.id_item ASC");
			  $no = 1; 
				while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				  echo "<td>$no</td>"; 
				  echo "<td>$data[goods_code]</td>"; 
				  echo "<td>$data[itemdesc]</td>"; 
				  echo "<td>$data[qty]</td>";
          echo "<td>$data[unit]</td>"; 
				  echo "<td>$data[curr]</td>"; 
          echo "<td>$data[price]</td>";
          $total = $data['qty']*$data['price'];
          echo "<td>$total</td>"; 
          echo "<td>$data[kpno]</td>";
          #<i class='glyphicon glyphicon-pencil'></i> Edit</a>";
          echo "<td><a class='btn btn-success btn-s' href='?mod=$mod&mode=$mode&noid=$bpbno&id=$data[id_item]&kp=$data[kpno]'>$cub</a>";
				  echo " <a class='btn btn-danger btn-s' href='del_data.php?mod=$mod&mode=$mode&noid=$bpbno&id=$data[id_item]&pro=In'";?> 
          onclick="return confirm('Apakah anda yakin akan menghapus ?')"><?PHP echo $chap."</a></td>";
				  echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
      <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>