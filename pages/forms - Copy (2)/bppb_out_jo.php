<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode = $_GET['mode'];
if (isset($_GET['mod'])) { $mod = $_GET['mod']; } else { $mod = ""; } 
$img_err = "'../../images/error.jpg'";
# START CEK HAK AKSES KEMBALI
$akses = flookup("bppb_req","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI

$mod = $_GET['mod'];
$nm_tbl="bppb";
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
{ if ($st_company=="GB" OR $st_company=="MULTI_WHS")
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
  $jono = "";
  $bcno = "";
  $bcdate = date('d M Y');
  $bcaju = "";
  $tglaju = date('d M Y');
  $bppbdate = date('d M Y');
  $status_kb = "";
  $txttujuan = "";
  $txtsubtujuan = "";
  $noreq = "";
  $id_jo = "";
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
  $id_supplier = flookup("supplier","mastersupplier","id_supplier='$data[id_supplier]'");
  $invno = $data['invno'];
  $jono = flookup("jo_no","jo","id='$data[id_jo]'");
  $bcno = $data['bcno'];
  $bcdate = date('d M Y',strtotime($data['bcdate']));
  $bcaju = $data['nomor_aju'];
  $tglaju = date('d M Y',strtotime($data['tanggal_aju']));
  $bppbno = $data['bppbno'];
  $bppbdate = date('d M Y',strtotime($data['bppbdate']));
  $status_kb = $data['jenis_dok'];
  $txttujuan = $data['tujuan'];
  $txtsubtujuan = $data['subtujuan'];
  $noreq = $data['bppbno_req'];
  $area = flookup("area","mastersupplier","id_supplier='$data[id_supplier]'");

  //group by breq.id_bpb
  $group_by =($area =='LINE' ? "":"group by breq.id_bpb");
  $id_jo = $data['id_jo'];
}
$id_jo_gab = flookup("group_concat(distinct id_jo)","bppb","bppbno='$bppbno'");

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
  echo "function validasi()";
  echo "{";
    echo "
    	var id_supplier = document.form.txtid_supplier.value;
    	var invno = document.form.txtinvno.value;
    	var bcno = document.form.txtbcno.value;
    	var bcdate = document.form.txtbcdate.value;
    	var status_kb = document.form.txtstatus_kb.value;
    	var bppbdate = document.form.txtbppbdate.value;
    	var qtykos = 0;
    	var qtyover = 0;
    	var qtys = document.form.getElementsByClassName('qtyoutclass');
    	var qtybals = document.form.getElementsByClassName('qtysisaclass');

    	for (var i = 0; i < qtys.length; i++) 
	    { if (qtys[i].value !== '')
	      { qtykos = qtykos + 1; }
	      if (qtys[i].value !== '' && Number(qtys[i].value) > Number(qtybals[i].value))
	      { qtyover = qtyover + 1; }
	    }
    ";
    
    $img_alert = "imageUrl: '../../images/error.jpg'";    
    echo "if (qtykos == 0) { swal({ title: 'Tidak Ada Data', $img_alert }); valid = false; }";
    echo "else if (qtyover > 0) { swal({ title: 'Qty Tidak Mencukupi', $img_alert }); valid = false; }";
    echo "else if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (invno == '') { document.form.txtinvno.focus();swal({ title: 'Nomor Inv/SJ Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (status_kb == '') { swal({ title: 'Jenis Dokumen Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (status_kb !== 'INHOUSE' && bcno == '') { document.form.txtbcno.focus();swal({ title: 'Nomor Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (bcdate == '') { document.form.txtbcdate.focus();swal({ title: 'Tgl. Daftar Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (bppbdate == '') { document.form.txtbppbdate.focus();swal({ title: 'Tgl. BKB Tidak Boleh Kosong', imageUrl: $img_err });valid = false;}";
    echo "else if (new Date(bppbdate) > new Date()) 
      { document.form.txtbppbdate.focus();valid = false;
        swal({ title: 'Tgl. Transaksi Tidak Boleh Melebihi Hari Ini', $img_alert });
      }";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getJO()
  { var id_jo = $('#cboReq').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax3.php?modeajax=view_list_stock_req',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
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
</script>
<?PHP
# COPAS ADD
if ($mod=="37e")
{
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_bppb_out_jo.php?mod=$mod&mode=$mode&noid=$bppbno&id=$id_line' onsubmit='return validasi()'>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>$c35</label>";
            echo "<input type='text' class='form-control' name='txtremark' placeholder='$cmas $c35' value='$remark'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c50</label>";
            echo "<input type='text' class='form-control' name='txtnomor_mobil' placeholder='$cmas $c50' value='$nomor_mobil'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c51 *</label>";
            echo "<input type='text' class='form-control' name='txtid_supplier' id='cbosupp' value='$id_supplier' readonly >";
          echo "</div>";
          echo "
            <button type='submit' name='submit' class='btn btn-primary'>$csim</button>
            <a href='?mod=$mod&mode=$mode'>Baru</a>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
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
            echo "<select class='form-control select2' style='width: 100%;' name='txtstatus_kb'>";
            IsiCombo($sql,$status_kb,$cpil.' '.$c46);
            echo "</select>";
          echo "</div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Nomor Aju</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcaju' 
                placeholder='Masukan Nomor Aju' value='$bcaju'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>Tanggal Aju</label>
              <input type='text' class='form-control' name='txttglaju' id='datepicker3' 
                placeholder='Masukkan Tgl. Aju' value='$tglaju'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c42 *</label>
              <input type='text' maxlength='6' class='form-control' name='txtbcno' $st_txt_h 
                placeholder='$cmas $c42' value='$bcno'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c43 *</label>
              <input type='text' class='form-control' name='txtbcdate' $st_txt_tgl_h1 
                placeholder='Masukkan Tgl. Daftar' value='$bcdate'>
            </div>
        </div>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<div class='form-group'>";
            echo "<label>JO #</label>";
            echo "<input type='text' class='form-control' name='txtjono' id='txtjono' readonly value='$jono'>";
          echo "</div>";
          echo "<div class='form-group'>";
            echo "<label>$c41 *</label>";
            echo "<input type='text' class='form-control' name='txtinvno' $st_txt_h placeholder='$cmas $c41' value='$invno'>";
          echo "</div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>";
              if ($mod=="61r")
              { echo "<label>Nomor Request *</label>"; }
              else
              { echo "<label>$c52 *</label>"; }
            echo "<input type='text' class='form-control' name='txtbppbno' readonly 
              placeholder='$cmas $c52' value='$bppbno'>
            </div>
          </div>";
          echo "
          <div class='col-md-6'>
            <div class='form-group'>
              <label>$c53 *</label>
              <input type='text' class='form-control' name='txtbppbdate' onchange='getSat(this.value)' $st_txt_tgl_h 
                placeholder='$cmas $c53' value='$bppbdate'>
            </div>
          </div>";
        echo "</div>";
        ?>
        <div class='box-body'>
          <?php if($mod=="37e") 
          {	echo "<table id='examplefix2' style='width: 100%;'>";
						echo "
							<thead>
								<tr>
									<th>WS #</th>
                  <th>Style #</th>
                  <th>Buyer</th>
                  <th>Kode $mode</th>
									<th>Deskripsi</th>
									<th>Nomor Rak</th>
                  <th>Stock</th>
									<th>Unit</th>
									<th>Qty Out</th>
                  <th>Curr</th>
                  <th>Price</th>
								</tr>
							</thead>
							<tbody>";
							$sql="select breq.id_bpb,breq.price,breq.curr,breq.nomor_rak,breq.id line_item,breq.id_supplier,breq.qty qtyout,mi.goods_code,mi.itemdesc,tbl_in.id_item,tbl_in.id_jo,tbl_in.qty_in,
  							if(tbl_out.qty_out is null,0,tbl_out.qty_out) qty_out,
  							tbl_in.unit,
                ac.kpno,ac.styleno,mbuyer.supplier buyer
  							from masteritem mi inner join 
                (select id_item,id_jo,sum(qty) qty_in,unit from bpb where id_jo in ($id_jo_gab) group by id_item,id_jo) as tbl_in 
  							on mi.id_item=tbl_in.id_item
  							left join (select id_item,id_jo,sum(qty) qty_out from bppb where id_jo in ($id_jo_gab) group by id_item,id_jo) as tbl_out
  							on tbl_in.id_item=tbl_out.id_item and tbl_in.id_jo=tbl_out.id_jo
  							inner join bppb breq on mi.id_item=breq.id_item 
                INNER JOIN (select id_so,id_jo from jo_det group by id_jo)  jod on breq.id_jo=jod.id_jo 
                inner join (select so.id,id_cost,min(sod.deldate_det) mindeldate from so inner join so_det sod on so.id=sod.id_so group by so.id) so on jod.id_so=so.id 
                inner join act_costing ac on so.id_cost=ac.id
                inner join mastersupplier mbuyer on ac.id_buyer=mbuyer.id_supplier
                where breq.bppbno='$bppbno' $group_by";
							//echo $sql;
							$i=1;
							$query=mysql_query($sql);
							while($data=mysql_fetch_array($query))
							{	$id=$data['id_item'];
								$sisa = ($data['qty_in'] - $data['qty_out']) + $data['qtyout'];
								echo "
									<tr>
										<td>$data[kpno]</td>
                    <td>$data[styleno]</td>
                    <td>$data[buyer]</td>
                    <td>$data[goods_code]</td>
										<td>$data[itemdesc]</td>
										<td>$data[nomor_rak]</td>
                    <td><input type ='text' size='4' name ='qtysisa[$id]' value='$sisa' id='qtysisa$i' class='qtysisaclass' readonly></td>
										<td>
											<input type ='text' size='4' name ='unitsisa[$id]' value='$data[unit]' id='unitsisa$i' readonly>
										</td>
										<td>
											<input type ='text' size='4' name ='qtyout[$id]' id='qtyout$i' class='qtyoutclass' value='$data[qtyout]'>
											<input type ='hidden' name ='jono[$id]' id='jono$i' class='jonoclass' value='$id_jo'>
											<input type ='hidden' name ='id_supp[$id]' id='id_supp$i' class='id_suppclass' value='$data[id_supplier]'>
											<input type ='hidden' name ='line_item[$id]' id='line_item$i' class='line_itemclass' value='$data[line_item]'>
										</td>
                    <td>
                      <select style='width:70px; height: 26px;' name ='curr[$id]' 
                        class='curr' id='curr$i'>";
                      $sql="select nama_pilihan isi,nama_pilihan tampil
                        from masterpilihan where kode_pilihan='Curr' ";
                      IsiCombo($sql,$data['curr'],'Pilih Curr');
                      echo "</select>
                    </td>
                    <td><input type ='text' style='width:70px;' name ='price[$id]' id='price$i' class='price' value='$data[price]'></td>
									</tr>";
								$i++;
							};
					echo "</tbody></table>"; 
          } 
          else 
          { ?>
            <div id='detail_item'></div>
          <?php } ?>
          </div>
        <?php
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
}
# END COPAS ADD
?>