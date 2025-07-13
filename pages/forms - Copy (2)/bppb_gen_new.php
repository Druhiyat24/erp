<?php 

if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$readonly = '';

$mode = $_GET['mode'];

if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 

$img_err = "'../../images/error.jpg'";

# START CEK HAK AKSES KEMBALI
if ($mode=="FG")

{ $akses = flookup("mnuBPPBFG","userpassword","username='$user'");  }

else if ($mode=="Scrap")

{ $akses = flookup("mnuBPPBScrap","userpassword","username='$user'"); 
	$readonly = "readonly = 'readonly'";


 }

else if ($mode=="WIP")

{ $akses = flookup("mnuBPPBWIP","userpassword","username='$user'");  }

else if ($mode=="General")

{ $akses = flookup("bppb_gen","userpassword","username='$user'");  }

else

{ $akses = flookup("mnuBPPB","userpassword","username='$user'");  }

$akses_date = flookup("original_date","userpassword","username='$user'");

if ($akses=="0") 

{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }

# END CEK HAK AKSES KEMBALI



$mod = $_GET['mod'];

if ($mod=="61r") { $nm_tbl="bppb_req"; } else { $nm_tbl="bppb"; }

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));

  $nm_company = $rscomp["company"];

  $st_company = $rscomp["status_company"];

  $logo_company = $rscomp["logo_company"];

  

$c_nom_order=$capt_no_ord;

if ($nm_company=="PT. Gaya Indah Kharisma")

{ $sql="update masteritem set matclass=mattype where matclass='-'"; 

  insert_log($sql,$user);

}

if (isset($_GET['noid'])) {$bppbno = $_GET['noid']; } else {$bppbno = "";}

if (isset($_GET['id'])) {$id_item = $_GET['id']; } else {$id_item = "";}

if (isset($_GET['id'])) {$id_line = $_GET['id']; } else {$id_line = "";}

if (isset($_GET['kp'])) {$kpno = $_GET['kp']; } else {$kpno = "";}



if ($bppbno=="")

{ $st_txt_tgl_h = "id='datepicker2'"; $st_txt_tgl_h1 = "id='datepicker1'"; $st_txt_h = ""; }

else if ($bppbno!="" AND $id_item=="")

{ $st_txt_tgl_h = "readonly"; $st_txt_tgl_h1 = "readonly"; $st_txt_h = "readonly"; }

else

{ $st_txt_tgl_h = "id='datepicker2'"; $st_txt_tgl_h1 = "id='datepicker1'"; $st_txt_h = ""; }

          

if ($mode=="Bahan_Baku") 

{ 
	$readonly = "readonly = 'readonly'";
	
	if ($st_company=="GB" OR $st_company=="MULTI_WHS")

  { $titlenya="Barang"; }

  else

  { $titlenya=$c5; }

  $filternya="a.mattype in ('A','F','B')";

}

else if ($mode=="Scrap") 

{ $titlenya="Scrap"; 

  $filternya="a.mattype in ('S','L')";

}

else if ($mode=="General") 

{ $titlenya="Item General"; 

  $filternya="a.mattype in ('N')";

}

else if ($mode=="Mesin") 

{ $titlenya=$caption[1]; 

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

    window.location.href='index.php';

  </script>";

}

# COPAS EDIT

if ($bppbno=="" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $kpno = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $nomor_mobil = "";

  $id_supplier = "";

  $invno = "";

  $bcno = "";

  $bcdate = date('d M Y');

  $bcaju = "";

  $tglaju = date('d M Y');

  $bppbdate = date('d M Y');

  $status_kb = "";

  $txttujuan = "";

  $txtsubtujuan = "";

}

else if ($bppbno<>"" AND $id_item=="")

{ $id_item = "";

  $qty = "";

  $unit = "";

  $curr = "";

  $price = "";

  $remark = "";

  $nomor_rak = "";

  $kpno = "";

  $berat_bersih = "";

  $berat_kotor = "";

  $query = mysql_query("SELECT * FROM $nm_tbl where bppbno='$bppbno' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $nomor_mobil = $data['nomor_mobil'];

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  $bcdate = date('d M Y',strtotime($data['bcdate']));

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $bppbno = $data['bppbno'];

  $bppbdate = date('d M Y',strtotime($data['bppbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  $txtsubtujuan = $data['subtujuan'];

}

else

{ $query = mysql_query("SELECT * FROM $nm_tbl where bppbno='$bppbno' 

    and id='$id_item' ORDER BY id_item ASC");

  $data = mysql_fetch_array($query);

  $id_item = $data['id_item'];

  $qty = $data['qty'];

  $unit = $data['unit'];

  $curr = $data['curr'];

  $price = $data['price'];

  $remark = $data['remark'];

  $nomor_rak = $data['nomor_rak'];

  $kpno = $data['kpno'];

  $berat_bersih = $data['berat_bersih'];

  $berat_kotor = $data['berat_kotor'];

  $nomor_mobil = $data['nomor_mobil'];

  $id_supplier = $data['id_supplier'];

  $invno = $data['invno'];

  $bcno = $data['bcno'];

  $bcdate = date('d M Y',strtotime($data['bcdate']));

  $bcaju = $data['nomor_aju'];

  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));

  $bppbno = $data['bppbno'];

  $bppbdate = date('d M Y',strtotime($data['bppbdate']));

  $status_kb = $data['jenis_dok'];

  $txttujuan = $data['tujuan'];

  $txtsubtujuan = $data['subtujuan'];

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

echo "<script type='text/javascript'>";

  echo "function validasi()";

  echo "{";

    echo "var id_item = document.form.txtid_item.value;";

    echo "var qty = document.form.txtqty.value;";

    echo "var unit = document.form.txtunit.value;";

    if($mode!="Mesin")

    { echo "var kpno = document.form.txtkpno.value;"; }

    $qty_old=flookup("qty","bppb","bppbno='$bppbno' and id_item='$id_item'");

    echo "var selisihqty = $sisa - qty;";

    // RMN echo "var selisihqty = $qty_old - qty;";

    echo "var stock = document.form.txtstock.value;";

    echo "var cekstockedit = Number(selisihqty)+Number(stock);";

    echo "var sisa = document.form.txtstock.value;";

    echo "var id_supplier = document.form.txtid_supplier.value;";

    echo "var invno = document.form.txtinvno.value;";

    if ($st_company=="MULTI_WHS")

    {	echo "var id_gudang = document.form.txtid_gudang.value;";	}

    else

    {	echo "var bcno = document.form.txtbcno.value;";

    	echo "var bcdate = document.form.txtbcdate.value;";

    	echo "var status_kb = document.form.txtstatus_kb.value;";

    }

    echo "var bppbdate = document.form.txtbppbdate.value;";

    

    $img_alert = "imageUrl: '../../images/error.jpg'";    

    echo "if (id_item == '') 

      { swal({ title: '$titlenya Tidak Boleh Kosong', $img_alert });

        valid = false;

      }";

    $akses=flookup("m_kpno","mastercompany","company!=''");

    if ($akses=="1" and $mode!="Mesin")

    { echo "else if (kpno == '') { document.form.txtkpno.focus();swal({ title: '$c_nom_order Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}"; }

    echo "else if (qty == '' || Number(qty)<0) { document.form.txtqty.focus();swal({ title: 'Jumlah Tidak Boleh Kosong / Lebih Kecil Nol', imageUrl: $img_err });valid = false;}";

    echo "else if (unit == '') { swal({ title: 'Satuan Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    #if ($id_item=="")

    #{ echo "else if (Number(qty) > Number(sisa)) { alert('Jumlah tidak boleh melebihi stock'); document.form.txtqty.focus();valid = false;}"; }

    #echo "else if (Number(qty) > Number(sisa)) { document.form.txtqty.focus();swal({ title: 'Jumlah Tidak Mencukupi', imageUrl: $img_err });valid = false;}";

    echo "else if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    if ($id_item=="")

     // RMN CEK DULU { echo "else if (cekstockedit<0) { document.form.txtqty.focus();swal({ title: 'Jumlah Tidak Mencukupi', imageUrl: $img_err });valid = false;}"; }

    if ($st_company=="MULTI_WHS")

    {	echo "else if (id_gudang == '') { swal({ title: 'Gudang Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    }

  	else

  	{	echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

	    $akses=flookup("m_dok_pabean","mastercompany","company!=''");

      if ($akses=="1")

      { echo "else if (status_kb !=='INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

        echo "else if (bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

      }

    }

    echo "else if (bppbdate == '') { document.form.txtbppbdate.focus();swal({ title: 'Tgl. BKB Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";

    echo "else if (new Date(bppbdate) > new Date()) 

      { document.form.txtbppbdate.focus();valid = false;

        swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', $img_alert });

      }";

    echo "else if (Number(jtbppb) > 1) { valid = false;swal({ title: 'Ada Lebih Dari 1 Type Material, Silahkan Cek Kembali', imageUrl: $img_err });}";

    echo "else valid = true;";

    echo "return valid;";

    echo "exit;";

  echo "}";

echo "</script>";

# END COPAS VALIDASI

?>

<script type="text/javascript">

  function getSat(cri_item)

  {   var bppbdate = document.form.txtbppbdate.value;

  		var bppbno = document.form.txtbppbno.value;

  		var id_item = document.form.txtid_item.value;

      var html = $.ajax

      ({  type: "POST",

          <?php

          if ($mode=="FG")

          { echo "url: 'ajax.php?modeajax=cari_satuan_fg',"; }

          else

          { echo "url: 'ajax_gen_new.php?modeajax=cari_satuan_gen_new',"; }

          ?>

          data: {cri_item: id_item,cri_date: bppbdate,cri_bppbno: bppbno},

          async: false

      }).responseText;

      if(html)

      { 

          $("#cbosat").html(html);

      }

      <?php

      if ($mode=="FG")

      { ?>

      var html = $.ajax

      ({ type: "POST",

         url: 'ajax.php?modeajax=cari_kp_fg',

         data: {cri_item: id_item},

         async: false

      }).responseText;

      if(html)

      { 

          $("#cbokp").html(html);

      }

      <?php } ?>

      jQuery.ajax({

          <?PHP

          if ($mode=="FG")

          { echo "url: 'ajax.php?modeajax=cari_sisa_fg',"; }

          else

          { echo "url: 'ajax_gen_new.php?modeajax=cari_sisa_gen_new',"; }

          ?>

          method: 'POST',

          data: {cri_item: id_item},

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

      //    data: {cri_item: id_item,cri_date: bppbdate,cri_bppbno: bppbno},

      //    success: function(response){

      //        jQuery('#txtsisa_nett').val(response);  

      //    },

      //    error: function (request, status, error) {

      //        alert(request.responseText);

      //    },

      //});

  }

  <?php if ($nm_company!="PT. Sinar Gaya Busana") { ?>

    function getTujuan(cri_item)

    {   var html = $.ajax

        ({  type: "POST",

            url: 'ajax.php?modeajax=cari_tujuan',

            data: "cri_item=" +cri_item,

            async: false

        }).responseText;

        if(html)

        { $("#txttujuan").html(html); }

    }

  <?php } ?>

  function getListItem(cri_item)

  {   var res_cri_item = cri_item.replace(/[+]/g,"X");

      var html = $.ajax

      ({  type: "POST",

          <?php

          if ($mode=="FG") 

          { echo "url: 'ajax.php?modeajax=cari_list_item_fg_out',"; }

          else if ($mode=="WIP") 

          { echo "url: 'ajax.php?modeajax=cari_list_item_wip_out',"; }

          else if ($mode=="General") 

          { echo "url: 'ajax_gen_new.php?modeajax=cari_list_item_gen_out_new',"; }

          else if ($mode=="Mesin") 

          { echo "url: 'ajax.php?modeajax=cari_list_item_mesin_out',"; }

          else if ($mode=="Scrap") 

          { echo "url: 'ajax.php?modeajax=cari_list_item_scrap_out',"; }

          else if ($mod=="61a" OR $mod=="61r") 

          { echo "url: 'ajax.php?modeajax=cari_list_item_out_bom',"; }

          else

          { echo "url: 'ajax.php?modeajax=cari_list_item_out',"; }

          ?>

          data: "cri_item=" +res_cri_item,

          async: false

      }).responseText;

      if(html)

      { 

          $("#cboid_item").html(html);

      }

  };

  function getPriceSat(tot_nilai)

  { var qtynya = document.form.txtqty.value;

    jQuery.ajax

    ({  

      url: "ajax3.php?modeajax=calc_price_satuan",

      method: 'POST',

      data: {nilainya: tot_nilai,qtynya: qtynya},

      dataType: 'json',

      success: function(response)

      { $('#txtprice').val(response[0]); },

      error: function (request, status, error) 

      { alert(request.responseText); },

    });

  };

  function getTotNil(price_sat)

  { var qtynya = document.form.txtqty.value;

    jQuery.ajax

    ({  

      url: "ajax3.php?modeajax=calc_total_nilai",

      method: 'POST',

      data: {nilainya: price_sat,qtynya: qtynya},

      dataType: 'json',

      success: function(response)

      { $('#txttotal').val(response[0]); },

      error: function (request, status, error) 

      { alert(request.responseText); },

    });

  };

</script>

<?PHP

# COPAS ADD

if ($mod=="61" or $mod=="61a" or $mod=="62" or $mod=="63"

  or $mod=="64" or $mod=="65" or $mod=="633" )

{

echo "<div class='box'>";

  echo "<div class='box-body'>";

    echo "<div class='row'>";

      echo "<form method='post' name='form' action='save_data_bppb.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";

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

              if ($nm_company=="PT. Gaya Indah Kharisma")

              {	$sqlwhere="where a.id_item='$id_item'"; $sqlwhere2=" and a.id_item='$id_item'";	}

            	else

          		{	$sqlwhere="where id_item='$id_item'"; $sqlwhere2=" and id_item='$id_item'";	}

            } 

            else 

            { $matlcassnya="";

              $sqlwhere=""; $sqlwhere2=""; 

            }

            if ($mode=="FG")

            { if ($nm_company=="PT. Tun Hong")

              { $sql = "select kpno isi,concat(kpno,'|',itemname) tampil from masterstyle $sqlwhere 

                  group by kpno order by kpno "; 

              }

              else if ($nm_company=="PT. Gaya Indah Kharisma")

              { $sql = "select a.itemname isi,a.itemname tampil from masterstyle a 

            			inner join stock s on a.id_item=s.id_item and 'FG'=s.mattype $sqlwhere 

            			and stock>0

                  and non_aktif='N' group by itemname order by itemname "; 

              }

              else if ($nm_company=="PT. Sandrafine Garment")

              { if ($sqlwhere=="") {$sql_act="where non_aktif='N' ";} else {$sql_act="and non_aktif='N' ";}

                $sql = "select buyerno isi,buyerno tampil from masterstyle a $sqlwhere 

                  $sql_act group by buyerno order by buyerno "; 

              }

              else

              { if ($sqlwhere=="") {$sql_act="where non_aktif='N' ";} else {$sql_act="and non_aktif='N' ";}

                $sql = "select itemname isi,itemname tampil from masterstyle a $sqlwhere 

                   $sql_act and id_so_det is null group by itemname order by itemname "; 

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

            	{ $sql = "select itemdesc isi,id_item tampil from masteritem a where $filternya $sqlwhere2 

                    group by itemdesc  order by itemdesc "; 

                echo "<label>Deskripsi $titlenya *</label>";

                echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";

                IsiCombo($sql,$matlcassnya,'Pilih Deskripsi '.$titlenya);

                echo "</select>";   

              }

              else

            	{ if ($nm_company=="PT. Gaya Indah Kharisma")

          			{	$sql = "select matclass isi,matclass tampil from masteritem a 

          					inner join stock s on a.id_item=s.id_item and a.mattype=s.mattype 

          					where $filternya $sqlwhere2 and stock>0 group by matclass order by matclass "; 

          			}

                else if ($mod=="61a" OR $mod=="61r")

                { $sql="select s.id_item_fg isi,concat(a.kpno,'|',a.styleno,'|',a.buyerno,'|',a.deldate,'|',a.id_item) 

                    tampil from masterstyle a inner join bom s on a.id_item=s.id_item_fg

                    group by kpno,buyerno,deldate,a.id_item order by kpno,styleno";

                }

            		else

          			{	if ($nm_company=="PT. Sandrafine Garment") {$fldclass="stock_card";} else {$fldclass="matclass";}

                  $sql = "select $fldclass isi,$fldclass tampil from masteritem a 

          					where $filternya $sqlwhere2 group by $fldclass order by $fldclass "; 

          			}

            		if ($mod=="61a" OR $mod=="61r")

                { echo "<label>$c_nom_order *</label>";

                  echo "<select class='form-control select2' style='width: 100%;' onchange='getListItem(this.value)' name='txtparitem'>";

                  IsiCombo($sql,$matlcassnya,'Pilih '.$c_nom_order);

                  echo "</select>";

                }

                else

                { echo "<label>$c14 $titlenya *</label>";

                  echo "<select class='form-control select2' style='width: 100%;' 

                    onchange='getListItem(this.value)' name='txtparitem'>";

                  IsiCombo($sql,$matlcassnya,$cpil.' '.$c14.' '.$titlenya);

                  echo "</select>";

                }

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

          echo "<label>Jumlah Qty Keluar *</label>";

          echo "<input type='text' class='form-control' name='txtqty' placeholder='$cmas $c31' value='$qty' autocomplete='off'>";

          echo "</div>";

          echo "<div class='form-group'>";

          echo "<label>Jumlah Stok per Satuan *</label>";

          echo "<select class='form-control select2' style='width: 100%;' id='cbosat' name='txtunit'>";

          if ($bppbno!="" AND $id_item!="")

          { $sql = "select unit isi,unit tampil 

              from $nm_tbl where id_item='$id_item' and bppbno='$bppbno' group by unit";

            IsiCombo($sql,$unit,$cpil.' '.$c32);

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

          echo "

            <div class='form-group'>

              <label>$c34</label>

              <input ".$readonly." type='text' style='background-color:gainsboro' class='form-control' name='txtprice' id='txtprice' 

                placeholder='$cmas $c34'  value='$price' onchange='getTotNil(this.value)'>

            </div>

            <div class='form-group'>

              <label>Total Nilai</label>

              <input ".$readonly." type='text' style='background-color:gainsboro' class='form-control' name='txttotal' id='txttotal' 

                placeholder='$cmas Total Nilai' onchange='getPriceSat(this.value)'>

            </div>";

        echo "</div>";

        echo "<div class='col-md-3'>";

          if ($mode!="Mesin")

          { echo "<div class='form-group'>";

              echo "<label >$c_nom_order </label>";

              echo "<select class='form-control select2' style='width: 100%;' 

                name='txtkpno' id='cbokp'>";

              $cek=flookup("username","userpassword","bpb_bom='1' 

                or bppb_bom='1' limit 1");

              if ($cek=="")

              { $sql="select id_item isi,concat(kpno,'|',styleno,'|',buyerno) 

                  tampil from masterstyle where id_so_det is null 

                  group by kpno order by kpno";

                IsiCombo($sql,$kpno,'Pilih '.$c_nom_order);

              }

              else

              { 
				$sql="select jod.id_jo isi,ac.kpno  
                        tampil from act_costing ac inner join 
                        so on ac.id=so.id_cost inner join jo_det jod 
                        on jod.id_so=so.id 
                        where ac.id_buyer='498' group by ac.kpno";
                IsiCombo($sql,$kpno,'Pilih '.$c_nom_order);

              }

              echo "</select>";

            echo "</div>";

          }

          echo "<div class='form-group'>";

            echo "<label>$c35</label>";

            echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtremark' placeholder='$cmas $c35' value='$remark'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>Nomor Rak</label>";

            echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtnomor_rak' placeholder='$cmas Nomor Rak' value='$nomor_rak'>";

          echo "</div>";

          echo "<div class='form-group'>";

          echo "<label>$c37 (Kg)</label>";

          echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtberat_bersih' placeholder='$cmas $c37 (Kg)' value='$berat_bersih'>";

          echo "</div>";

          echo "<div class='form-group'>";

          echo "<label>$c38 (Kg)</label>";

          echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtberat_kotor' placeholder='$cmas $c38 (Kg)' value='$berat_kotor'>";

          echo "</div>";

          echo "<div class='form-group'>";

          echo "<label>$c50</label>";

          echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtnomor_mobil' placeholder='$cmas $c50' value='$nomor_mobil'>";

          echo "</div>";

        echo "</div>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

          if ($mod=="67")

          {	echo "<label>Dari Gudang *</label>";

	          $sql = "select id_supplier isi,supplier tampil from mastersupplier where 

	          	tipe_sup='G'";

	          echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>";

	          IsiCombo($sql,$id_supplier,$cpil.' Dari Gudang');

          }

          else

        	{	echo "<label>$c51 *</label>";

	          if($logo_company=="S" and $mode=="General") 

            { $sql="insert into mastersupplier (supplier,area,tipe_sup) 

                select nm_cost_sub_dept,'F','D' from mastercostsubdept a left join mastersupplier s 

                on a.nm_cost_sub_dept=s.supplier where supplier is null";

              insert_log($sql,$user);

              $fil_sup=" and tipe_sup='D' "; 

            } 

            else 

            { $fil_sup=""; }

            $sql = "select id_supplier isi,supplier tampil from mastersupplier where 

	          	tipe_sup<>'G' $fil_sup ";

	          if ($bppbno=="")

            { echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>"; 

              IsiCombo($sql,$id_supplier,$cpil.' '.$c51);

            }

            else if ($bppbno!="" AND $id_item=="")

            { echo "<select disabled class='form-control select2' style='width: 100%;' name='txtid_supplier_disabled'>"; 

              IsiCombo($sql,$id_supplier,$cpil.' '.$c51);

              echo "<input type='hidden' name='txtid_supplier' value='$id_supplier'>";

            }

            else

            { echo "<select class='form-control select2' style='width: 100%;' name='txtid_supplier'>"; 

              IsiCombo($sql,$id_supplier,$cpil.' '.$c51);

            }

          }

          echo "</select>";

          echo "</div>";

          echo "<div class='form-group'>";

          	echo "<label>$c41 *</label>";

          	echo "<input type='text' class='form-control' name='txtinvno' $st_txt_h placeholder='$cmas $c41' value='$invno'>";

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

          {	echo "<div class='form-group'>";

	            echo "<label>Nomor Aju</label>";

	            echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtbcaju' placeholder='Masukan Nomor Aju' value='$bcaju'>";

	          echo "</div>";

	          echo "<div class='form-group'>";

	            echo "<label>Tanggal Aju</label>";

	            echo "<input type='text' class='form-control' name='txttglaju' id='datepicker3' placeholder='Masukkan Tgl. Aju' value='$tglaju'>";

	          echo "</div>";

	          echo "<div class='form-group'>";

	            echo "<label>$c42 *</label>";

	            echo "<input type='text' style='background-color:gainsboro' class='form-control' name='txtbcno' $st_txt_h placeholder='$cmas $c42' value='$bcno'>";

	          echo "</div>";

	          echo "<div class='form-group'>";

	            echo "<label>$c43 *</label>";

	            echo "<input type='text' class='form-control' name='txtbcdate' $st_txt_tgl_h1 placeholder='Masukkan Tgl. Daftar' value='$bcdate'>";

	          echo "</div>";

        	}

        echo "</div>";

        echo "<div class='col-md-3'>";

          echo "<div class='form-group'>";

            if ($mod=="61r")

            { echo "<label>Nomor Request *</label>"; }

            else

            { echo "<label>Nomor BPPB *</label>"; }

            echo "<input type='text' class='form-control' name='txtbppbno' readonly placeholder='$cmas Nomor BPPB' value='$bppbno'>";

          echo "</div>";

          echo "<div class='form-group'>";

            if ($mod=="61r")

            { echo "<label>Tgl. Request *</label>"; }

            else

            { echo "<label>Tanggal BPPB *</label>"; }

            echo "<input type='text' class='form-control' name='txtbppbdate' readonly onchange='getSat(this.value)' $st_txt_tgl_h placeholder='$cmas Tanggal BPPB' value='$bppbdate'>";

          echo "</div>";

          echo "<div class='form-group'>";

            echo "<label>Stock Global</label>";

            if ($bppbno!="" AND $id_item!="")

            { if (substr($bppbno,3,2)=="FG")

              { $sisa = flookup("stock","stock","id_item='$id_item' and mattype='FG'"); }

              else

              { $sisa = flookup("stock","stock","id_item='$id_item' and mattype!='FG'"); }

              echo "<input type='text' class='form-control' id='txtsisa' name='txtstock' value='$sisa' readonly>";

            }

            else

            { echo "<input type='text' class='form-control' id='txtsisa' name='txtstock' readonly>"; }

            echo "</div>";

          if ($st_company!="MULTI_WHS")

          {	echo "<div class='form-group'>";

		          echo "<label>$c46 *</label>";

		          if ($st_company=="KITE") 

		          { $status_kb_cri="Status KITE Out"; }

		          else if ($st_company=="PLB") 

              { $status_kb_cri="Status PLB Out"; }

              else

		          { $status_kb_cri="Status KB Out"; }

		          $sql = "select nama_pilihan isi,nama_pilihan tampil from masterpilihan where 

		                kode_pilihan='$status_kb_cri' order by nama_pilihan";

		          if ($nm_company=="PT. Sinar Gaya Busana") { $callajax=""; } else { $callajax="onchange='getTujuan(this.value)'"; }

              echo "<select class='form-control select2' style='width: 100%;' $callajax name='txtstatus_kb'>";

		          IsiCombo($sql,$status_kb,$cpil.' '.$c46);

		          echo "</select>";

	          echo "</div>";

	          if ($nm_company=="PT. Sinar Gaya Busana")

            { echo "<div class='form-group'>";

                echo "<label>$c54</label>";

                echo "<select class='form-control select2' style='width: 100%;' name='txttujuan'>";

                $sql = "select nama_pilihan isi,nama_pilihan tampil 

                  from masterpilihan where kode_pilihan='TUJUAN_OUT'";

                IsiCombo($sql,trim($txttujuan),$cpil.' '.$c54);

                echo "</select>";

              echo "</div>";

              echo "<div class='form-group'>";

                echo "<label>Sub $c54</label>";

                echo "<select class='form-control select2' style='width: 100%;' name='txtsubtujuan'>";

                $sql = "select nama_pilihan isi,nama_pilihan tampil 

                  from masterpilihan where kode_pilihan='TUJUAN_SUB_OUT'";

                IsiCombo($sql,trim($txtsubtujuan),$cpil.' '.$c54);

                echo "</select>";

              echo "</div>";

            }

            else

            { echo "<div class='form-group'>";

                echo "<label>$c54</label>";

                echo "<select class='form-control select2' style='width: 100%;' id='txttujuan' name='txttujuan'>";

                if ($bppbno!="")

                { $sql = "select nama_pilihan isi,nama_pilihan tampil 

                    from masterpilihan where kode_pilihan='$status_kb'";

                  IsiCombo($sql,trim($txttujuan),$cpil.' '.$c54);

                }

                echo "</select>";

              echo "</div>";

            }

          }

          echo "

            <div class='form-group'>

              <input type='checkbox' id='chkret' name='chkret' value='Y'>

              <label for='chkret'>Retur</label>

            </div>";

          echo "

            <button type='submit' name='submit' class='btn btn-primary'>$csim</button>

            <a href='?mod=$mod&mode=$mode'>Baru</a>";

        echo "</div>";

      echo "</form>";

    echo "</div>";

  echo "</div>";

echo "</div>";

}

# END COPAS ADD

if ($mod=="61v" or $mod=="62v" or $mod=="63v"

  or $mod=="64v" or $mod=="65v" or $mod=="61z")

{

?>

<div class="box">

  <?php 

  if ($mode=="FG")

  { $fldnyacri=" mid(bppbno,4,2)='FG' "; $mod2=65; }

  else if ($mode=="Mesin")

  { $fldnyacri=" mid(bppbno,4,1)='M' "; $mod2=63; }

  else if ($mode=="General")

  { $fldnyacri=" mid(bppbno,4,1)='N' "; $mod2=633; }

  else if ($mode=="Scrap")

  { $fldnyacri=" mid(bppbno,4,1) in ('S','L') "; $mod2=62; }

  else if ($mode=="WIP")

  { $fldnyacri=" mid(bppbno,4,1)='C' "; $mod2=64; }

  else 

  { $fldnyacri=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' "; $mod2=61; }

  ?>

  <div class="box-header">

    <h3 class="box-title">List Pengeluaran <?PHP echo $titlenya; ?></h3>

    <a href='../forms/?mod=<?php echo $mod2; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>

      <i class='fa fa-plus'></i> New

    </a>

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

          <th>Nomor BPPB</th>

          <th>Tanggal BPPB</th>

          <?php if($akses_date=="1") { ?>

          <th>Original BPPB Date</th>

          <?php } ?>     

          <th>Penerima</th>

          <th>No. Invoice</th>

          <th>No. Dokumen</th>

          <th>Jenis BC</th>

          <th>Created By</th>

          <th>Status</th>

          <th></th>

          <th></th>

        </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

        if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
		
/* 		if ($mod=="61v") {

			$kondisi_nya = " AND ms.area='F' AND substr(a.bppbno,4,1)!='C' AND ( bppbno_int NOT LIKE '%RO%' AND bppbno_int NOT LIKE '%RI%' AND SUBSTR(bppbno_int,8,1) !='R' AND a.id_jo is not null) 
		OR(	 $fldnyacri and  ifnull(a.id_jo,0)='0' ";
			
			
		}else{ */
		if($mod = "62v"){
			$kondisi = "AND $fldnyacri AND a.bppbno_int NOT LIKE '%RO%' AND substr(a.bppbno,11,1)!='R' and ifnull(a.id_so_det,0)='0' and ifnull(a.id_jo,0)='0'";			
		}else{
			$kondisi = "AND $fldnyacri  AND a.bppbno_req ='' AND a.bppbno_int NOT LIKE '%RO%' AND substr(a.bppbno,11,1)!='R' and ifnull(a.id_so_det,0)='0' and ifnull(a.id_jo,0)='0'";			
		}

		//}

        $query = mysql_query("SELECT max(a.bppbno_int) bppbno_int_n,a.*,s.goods_code,$fld_desc itemdesc,supplier , a.last_date_bppb,ms.area

          FROM bppb a inner join $tbl_mst s on a.id_item=s.id_item

          inner join mastersupplier ms on a.id_supplier=ms.id_supplier

          where 1=1 $kondisi and a.bppbdate >='$tglf' and a.bppbdate <='$tglt' 

          GROUP BY a.bppbno ASC order by bppbdate desc limit 1000");

        while($data = mysql_fetch_array($query))

        { echo "<tr>";

          if($data['bppbno_int_n']!="")

          { echo "<td>$data[bppbno_int_n]</td>"; }

          else

          { echo "<td>$data[bppbno]</td>"; }

          echo "

            <td>$data[bppbdate]</td>";

          if($akses_date == "1") {

              echo "<td>".fd_view($data['last_date_bpb'])."</td>";

              }

            echo "<td>$data[supplier]</td>

            <td>$data[invno]</td>

            <td>$data[bcno]</td>

            <td>$data[jenis_dok]</td>

            <td>$data[username] ($data[dateinput])</td>";
			
            
			if($data['confirm']=='N') {
            echo " <td></td>
			<td>

            	<a href='?mod=31e&mode=$mode&noid=$data[bpbno]'

              	data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>

              </a>

            </td>"; } else { echo "<td>Confirmed by $data[confirm_by] $data[confirm_date]</td><td></td>"; }

            if ($print_sj=="1")

            { echo "

              <td>

                <a href='cetaksj.php?mode=Out&noid=$data[bppbno]' 

                  data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>

                </a>

              </td>"; 

            }

            else

            { echo "<td></td>"; }

          echo "</tr>";

        }

        ?>

      </tbody>

    </table>

  </div>

</div>

<?php } else { ?>

<div class="box">

  <div class="box-header">

    <h3 class="box-title">Detil <?PHP echo $titlenya; ?></h3>

  </div>

  <div class="box-body">

    <table id="examplefix3" class="display responsive" width="100%">

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

            <th></th>

            <th></th>

        </tr>

      </thead>

      <tbody>

        <?php

        # QUERY TABLE

		    if ($mode=="FG")

        { $query = mysql_query("SELECT a.curr,a.price,a.id_item,s.goods_code,s.itemname itemdesc,

              a.qty,a.unit,a.kpno,a.id 

              FROM $nm_tbl a inner join masterstyle s on a.id_item=s.id_item where bppbno='$bppbno' ORDER BY a.id_item ASC");

        }

        else

        { $query = mysql_query("SELECT a.kpno,a.curr,a.price,a.id_item,s.goods_code,s.itemdesc,

              a.qty,a.unit,a.kpno,a.id 

              FROM $nm_tbl a inner join masteritem s on a.id_item=s.id_item where bppbno='$bppbno' ORDER BY a.id_item ASC");

        }

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

          echo "

          <td>

            <a href='index.php?mod=$mod&mode=$mode&noid=$bppbno&id=$data[id]'

              $tt_ubah

            </a>

          </td>";

				  echo "

          <td>

            <a $cl_hapus href='del_data.php?mod=$mod&mode=$mode&noid=$bppbno&id=$data[id]&pro=Out'

              $tt_hapus";?> 

              onclick="return confirm('Apakah anda yakin akan menghapus ?')">

              <?php echo $tt_hapus2."</a>

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