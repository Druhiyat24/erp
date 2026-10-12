<?php
include "../../include/conn.php";
include "fungsi.php";
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$modenya = $_GET['modeajax'];
	$user=$_SESSION['username'];
#echo $modenya;


	if ($modenya=="view_item")
	{ 
		$id_rak_loc = $_REQUEST['id_rak_loc'];	
		// $tgl_bpb = date("Y-m-d",strtotime($_REQUEST['tgl_bpb']));;	
		// join bpb/jo_det dibuang karena tidak dipakai untuk daftar item; CAST karena id_bpb_det varchar vs id bigint (biar key bisa dipakai)
		$sql_bpb = "select '' isi, 'Select Item' tampil union
          select x.id_item isi, CONCAT(x.id_item,' - ',x.itemdesc) tampil from (
            select bd.id_item, mi.itemdesc,
              SUM(COALESCE(bd.roll_qty,0) - COALESCE(b.qty_out,0) - COALESCE(bd.qty_mutasi,0)) qty
            from bpb_det bd
            inner join masteritem mi on bd.id_item = mi.id_item
            left join (select id_bpb_det, SUM(roll_qty) qty_out from bppb_det
                       where cancel = 'N' and id_rak_loc = '$id_rak_loc' group by id_bpb_det) b
              on b.id_bpb_det = CAST(bd.id AS CHAR)
            where bd.id_rak_loc = '$id_rak_loc'
            group by bd.id_item
          ) x where x.qty > 0";
		IsiCombo($sql_bpb,'','');
	
	}


	if ($modenya=="viewdetail" && $_REQUEST['id_item']=="")
	{
		// tanpa filter item query-nya bisa sampai menit-an di rak besar, jadi tabel baru dimuat setelah item dipilih
		echo "<div class='mutacc-empty'>Pilih Item terlebih dahulu untuk menampilkan data.</div>";
	}
	else if ($modenya=="viewdetail")
	{
		?>
		<div class="mutacc-table-wrap">
		<table id="data_mut" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
        <tr>
          <th style="width: 4%"><input type="checkbox" onchange="checkAll(this)" name="chk[]" ></th>
          <th style="width: 6%">ID</th>
          <th style="width: 6%">ID Item</th>
          <th style="width: 7%">Kode Item</th>
          <th style="width: 9%">Nama Item</th>
          <th style="width: 8%">Supplier</th>
          <th style="width: 8%">WS</th>
          <th hidden>Style</th>
          <th hidden>No JO</th>
          <th hidden>No PO</th>
          <th style="width: 8%">No BPB</th>
          <th style="width: 7%">Tgl BPB</th>
          <th style="width: 7%">Qty</th>
          <th style="width: 7%">Qty Mutasi</th>
          <th style="width: 7%">Unit</th>
          <th style="width: 8%">Rak </th>
          <th style="width: 8%">Rak Tujuan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $id_item = $_REQUEST['id_item'];
		$id_rak_loc = $_REQUEST['id_rak_loc'];
        $sql="select * from (select * from (select a.*,b.*,(COALESCE(a.roll_qty,0) - COALESCE(b.qty_out,0) - COALESCE(a.qty_mutasi,0)) qty_sisa from (select bd.*,
          bpb.bpbdate tgl_bpb, 
          jd.kpno, 
          mi.itemdesc, 
          mi.goods_code,
          mr.kode_rak, 
          concat(mr.kode_rak, ' ', mr.nama_rak)rak, 
          supplier,
          pono,
          invno,
          buyer,
          styleno
          from bpb_det bd
         left join (select bpbno, pono, id_supplier,bpbno_int, bpbdate,invno from bpb 
         group by bpbno) bpb on bd.bpbno = bpb.bpbno
         inner join masteritem mi on bd.id_item = mi.id_item
				 left join (select ac.id_buyer, supplier buyer,ac.styleno,jd.id_jo, ac.kpno from jo_det jd
         inner join so on jd.id_so = so.id
         inner join act_costing ac on so.id_cost = ac.id
				 inner join mastersupplier mb on ac.id_buyer = mb.id_supplier
         where jd.cancel = 'N'
         group by id_cost order by id_jo asc) jd on bd.id_jo = jd.id_jo
         inner join master_rak mr on bd.id_rak_loc = mr.id
         left join mastersupplier ms on bpb.id_supplier = ms.id_supplier 
         where bd.id_rak_loc = '$id_rak_loc' and bd.id_item = '$id_item'
         order by bpb.bpbdate asc) a left join
				 (select id_bpb_det,SUM(roll_qty) qty_out from bppb_det where cancel = 'N' GROUP BY id_bpb_det) b on b.id_bpb_det = a.id) a ) a where qty_sisa > 0 and id_item = '$id_item' ";
        // echo $sql;
        $opsi_rak = "";
        $sql_rak = mysql_query("select id, CONCAT(kode_rak,' ',nama_rak) nama_rak from master_rak where nama_rak like '%ACCESSORIES%' and aktif = 'Y'");
        while ($row = mysql_fetch_array($sql_rak)) {
          $opsi_rak .= '<option value="'.$row['id'].'">'.$row['nama_rak'].'</option>';
        }
        $i=1;
        $query=mysqli_query($con_new,$sql);
        while($data=mysqli_fetch_array($query))
        { 
          $id=$data['id'];
          // $sqlbpb="select supplier,unit from bpb a inner join mastersupplier s 
          //   on a.id_supplier=s.id_supplier where bpbno='$data[bpbno]' 
          //   and a.id_item='$data[id_item]' and a.id_jo='$data[id_jo]'";
          // $rsbpb=mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));
        //  <td><input type = 'number' style='width:50px;' max = '$data[sisa]' name ='itemqty[$id]' value = '$data[sisa]'></td>
          echo "
          <tr>
            <td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$id' class='chkclass'></td>
            <td>$data[id]</td>
            <td>$data[id_item]</td>
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>$data[supplier]</td>
            <td>$data[kpno]</td>
            <td hidden>$data[styleno]</td>
            <td hidden>$data[no_jo]</td>
            <td hidden>$data[pono]</td>
            <td>$data[bpbno_int]</td>
            <td>$data[tgl_bpb]</td>
            <td>$data[qty_sisa]</td>
            <td><input  class='form-control' type='number' step='any' min='0.01' max = '$data[qty_sisa]' name ='qty_mutasi[$id]' value = '$data[qty_sisa]' required></td>
            <td>$data[unit]</td>
            <td>$data[kode_rak]</td>
            <td> <select class='form-control select2' name='namarak[$id]' data-dropup-auto='false' data-live-search='true'>
                <option value='' disabled selected='true'>Select Rak</option>".$opsi_rak."</select></td>
          </tr>";
          $i++;
        };
        if ($i==1)
        { echo "<tr><td colspan='16' class='mutacc-empty'>Tidak ada stok tersedia untuk kombinasi rak &amp; item ini.</td></tr>"; }
        ?>
      </tbody>
    </table>
    </div>
    <?php

	}


?>