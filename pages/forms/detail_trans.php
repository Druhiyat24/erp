				      <style>
				      	td.text {
				      		mso-number-format: "\@";
				      		/*force text*/
				      	}
				      </style>
				      <?php 
				      if (empty($_SESSION['username'])) { header("location:../../index.php"); }

				      $user=$_SESSION['username'];
				      $mode = $_GET['mode'];
				      $mod = $_GET['mod'];
				      if (isset($_GET['frexc'])) 
				      	{ $excel="Y";
				      header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=detail.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0"); 
} 
else 
	{ $excel="N"; }
if ($excel=="Y")
	{ $from=date('Y-m-d',strtotime($_GET['frexc']));
$to=date('Y-m-d',strtotime($_GET['toexc']));
$tipenya=$_GET['tpexc'];
$tipenya_2=$_GET['tpexc'];
$classnya=$_GET['clexc'];
$suppnya=$_GET['suppexc'];
}
else
	{ if (isset($_POST['txtfrom'])) { $from=date('Y-m-d',strtotime($_POST['txtfrom'])); } else { $from=""; }
if (isset($_POST['txtto'])) { $to=date('Y-m-d',strtotime($_POST['txtto'])); } else { $to=""; }
if (isset($_POST['txttipe'])) { $tipenya=$_POST['txttipe'];$tipenya_2=$_POST['txttipe']; } else { $tipenya=""; }
if (isset($_POST['txtparitem'])) { $classnya=$_POST['txtparitem']; } else { $classnya=""; }
if (isset($_POST['txtid_supplier'])) { $suppnya=$_POST['txtid_supplier']; } else { $suppnya=""; }
}

if ($mode=="Detail_In")
	{ $titlenya="Detail Pemasukan ".$tipenya; }
else if ($mode=="Hist")
	{ $titlenya="Riwayat Aktifitas"; }
else
	{ $titlenya="Detail Pengeluaran ".$tipenya; }

if ($excel=="N") 
	{ echo "<header class='main-header'>"; include ("header.php"); echo "</header>"; }
else
	{ $nm_company=flookup("company","mastercompany","company!=''"); }

echo "<div class='box'>";
echo "<div class='box-body'>";
echo "Periode Dari ".fd_view($from)." s/d ".fd_view($to);
if ($excel=="N") 
	{ echo "<br><a href='?mod=$mod&mode=$mode&frexc=$from&toexc=$to&tpexc=$tipenya&clexc=$classnya&suppexc=$suppnya&dest=xls'>Save To Excel</a></br>"; }
echo "</div>";	
echo "</div>";
echo "<div class='box'>";
echo "<div class='box-body'>";
if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
if ($mode=="Hist")
	{echo "<table id='examplefix3' style='font-size: 12px; width: 100%;' class='display responsive'>";}
else
	{echo "<table id='examplefix2' $tbl_border style='font-size: 12px; width: 100%;' class='display responsive'>";}
echo "<thead>";
echo "<tr>";
echo "<th>No</th>";
if ($mode=="Hist")
	{ echo "<th>Trans Date</th>";
echo "<th>Trans Desc</th>";
echo "<th>Nama User</th>";
}
else
	{ echo "<th>Trans #</th>";
if (($mode=="Detail_Out") and ($tipenya=="Bahan Baku"))
{
	echo "<th>No Req</th>";
}
if (($mode=="Detail_Out") and ($tipenya=="Barang Dalam Proses"))
{
	echo "<th >No Req</th>";
}					
echo "<th>Tgl. Trans</th>";
echo "<th>Profit Center</th>";	
echo "<th>Inv #</th>";
echo "<th>Jenis Dok</th>";
echo "<th>Nomor Aju</th>";
echo "<th>Tgl Aju</th>";
if ($nm_company=="PT. Multi Sarana Plasindo")
	{	if ($tipenya=="Barang Jadi")
{	echo "<th>Nomor PEB</th>";
echo "<th>Tgl PEB</th>";
}
else
	{	echo "<th>Nomor PIB</th>";
echo "<th>Tgl PIB</th>";
}
}
else
	{	echo "<th>Nomor Daftar</th>";
echo "<th>Tgl Daftar</th>";
}
if ($mode=="Detail_In")
	{	echo "<th>Supplier</th>";	}
else
	{	if ($tipenya=="Barang Jadi") 
{	echo "<th>Customer</th>";	}
else
	{	echo "<th>Tujuan</th>";	}
}
if ($mode=="Detail_In")
	{ echo "<th>PO #</th>";
echo "<th>Type #</th>";
echo "<th>Inv/SJ #</th>";
}
echo "<th>Id Item</th>";
if ($tipenya=="Bahan Baku" || $tipenya=="Barang Dalam Proses")
{
	echo "<th>Id Contents</th>";
}	
echo "<th>Kode Barang</th>";
echo "<th>Nama Barang</th>";
if ($tipenya_2=="Item General")
{
	echo "<th>Kategori</th>";
}	

echo "<th>Warna</th>";
echo "<th>Ukuran</th>";
if (($mode=="Detail_Out") and ($tipenya=="Barang Jadi"))
{
	echo "<th>Dest</th>";
}					
if (($mode=="Detail_In") and ($tipenya=="Barang Jadi"))
{
	echo "<th>Dest</th>";
	echo "<th>Grade</th>";
}
echo "<th>Jumlah BPB</th>";

if ($tipenya !="Item General")
{
	echo "<th>Qty BPB Good</th>";
	echo "<th>Qty BPB Reject</th>";
}

echo "<th>Satuan</th>";
echo "<th>Berat Bersih</th>";
echo "<th>Keterangan</th>";
echo "<th>Nama User</th>"; 
$flduser="a.username";
echo "<th>Approve By</th>";
echo "<th>WS #</th>";
echo "<th>Style #</th>";
echo "<th>Curr</th>";
echo "<th>Price</th>";
if (($mode=="Detail_Out") and ($tipenya=="Item General"))
{
	echo "<th>PO</th>";
}					
if (($mode=="Detail_In") and ($tipenya=="Bahan Baku"))
{
	echo "<th>Price Act</th>";
}					
if (($mode=="Detail_In") and ($tipenya=="Barang Jadi"))
{
	echo "<th>Switch In</th>";
	echo "<th>Ref #</th>";
}
if (($mode=="Detail_In") and ($tipenya=="Bahan Baku"))
{
	echo "<th>Jenis Trans</th>";
	echo "<th>Reff No</th>";
	echo "<th>Nomor Rak</th>";
	echo "<th>Panel</th>";
	echo "<th>Color Garment</th>";
}
if (($mode=="Detail_In") and ($tipenya=="Item General"))
{
	echo "<th>Jenis Trans</th>";
	echo "<th>Reff No</th>";
}					 					 					
if (($mode=="Detail_Out") and ($tipenya=="Barang Jadi"))
{
	echo "<th>Invoice</th>";
	echo "<th>Switch Out</th>";
	echo "<th>No. SO</th>";
}
if (($mode=="Detail_Out") and ($tipenya=="Bahan Baku"))
{
	echo "<th>Ws Actual</th>";
	echo "<th>Jenis Trans</th>";
	echo "<th>Panel</th>";
	echo "<th>Color Garment</th>";
}										
}
echo "</tr>";
echo "</thead>";
echo "<tbody>";
if ($tipenya=="Barang Jadi")
	{ $tbl="masterstyle"; $fld="itemname"; 
$where=" left(bpbno,2) in ('FG') ";
$whereout=" mid(bppbno,4,2) in ('FG') "; 
}
else if ($tipenya=="Mesin")
	{ $tbl="masteritem"; $fld="itemdesc"; 
$where=" left(bpbno,1) in ('M') ";
$whereout=" mid(bppbno,4,1) in ('M') "; 
}
else if ($tipenya=="Barang Dalam Proses")
	{ $tbl="masteritem"; $fld="itemdesc"; 
$where=" left(bpbno,1) in ('C') ";
$whereout=" mid(bppbno,4,1) in ('C') "; 
}
else if ($tipenya=="Scrap")
	{ $tbl="masteritem"; $fld="itemdesc"; 
$where=" bpbno_int like 'SCR%' ";
$whereout=" mid(bppbno,4,1) in ('S','L') ";
}
else if ($tipenya=="Sample")
	{ $tbl="masteritem"; $fld="itemdesc"; 
$where=" bpbno_int like 'SMP%' ";
$whereout=" mid(bppbno,4,1) in ('S','L') ";
}
else if ($tipenya=="Item General")
	{ $tbl="masteritem"; $fld="itemdesc"; 
$where=" left(bpbno,1) in ('N') ";
$whereout=" mid(a.bppbno,4,1) in ('N') "; 
}
else
	{ $tbl="masteritem"; $fld="concat(itemdesc,' ',add_info)"; 
$where=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' ";
$whereout=" mid(bppbno,4,1) in ('A','F','B') and mid(bppbno,4,2)!='FG' ";
}
if ($tbl!="masterstyle" AND $classnya!="")
	{	if($nm_company=="PT. Sandrafine Garment")
{$que_cl=" and mattype='$classnya'";}
else
	if ($classnya == '-')
		{$que_cl=" and matclass in ('ACCESORIES PACKING' , 'ACCESORIES SEWING')";}
	else
	{
		{$que_cl=" and matclass= '$classnya'";}
	}
}
else
	{$que_cl="";}
if($suppnya!="")
	{	$que_supp=" and d.id_supplier='$suppnya'"; }
else
	{	$que_supp=" "; }
if ($mode=="Detail_Out")
{ 
	if ($tbl=="masterstyle") 
	{
		$fldsty="s.styleno"; $fldsc="buyerno,'' stock_card,whs_code";
		$joinjo = "left join (select sod.id_so,sod.id id_so_det from so_det sod  group by sod.id) tmpjod on tmpjod.id_so_det=a.id_so_det";
		$join_mc ="";
		$id_contents ="'' as id_contents";

	} 
	else 
	{
		$fldsty="a.styleno"; $fldsc="'' buyerno,stock_card,'' whs_code";
		$joinjo = "left join (select id_jo,id_so from jo_det group by id_jo ) tmpjod on tmpjod.id_jo=a.id_jo";
		$join_mc ="			left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen";
$id_contents ="id_contents";								
	}

	if($tipenya_2 == "Item General")
	{
		tampil_data("select if(a.bppbno_int!='',a.bppbno_int,k.bppbno_int) bppbno,
			a.bppbdate,ifnull(mp.nama_pc, 'NIRWANA ALABARE GARMENT') profit_center,a.invno,a.jenis_dok,right(a.nomor_aju,6),a.tanggal_aju,
			lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.id_item,s.goods_code,
			$fld itemdesc,m.description,s.color,s.size,a.qty,
			a.unit,a.berat_bersih,a.remark,$flduser,a.confirm_by,'' item_bom,'' style,a.curr,a.price, bpb.pono
			from bppb a inner join $tbl s on a.id_item=s.id_item
			LEFT join master_pc mp on mp.kode_pc = a.profit_center
			inner join mastersupplier d on a.id_supplier=d.id_supplier 
			left outer join (SELECT * FROM bppb WHERE bppbno_int <>'' group by bppbno_int) AS k ON a.bppbno=k.bppbno
			LEFT OUTER JOIN mapping_category AS m ON s.n_code_category=m.n_id
			left join bpb on a.bpbno_ro =  bpb.bpbno and a.id_item = bpb.id_item and a.id_jo = bpb.id_jo
			where $whereout and a.bppbdate between '$from' and '$to' 
			$que_cl $que_supp order by a.bppbdate",27);
	}
	else if ($tipenya == "Barang Jadi" && $classnya != "BARANG JADI STOK")
	{
						// tampil_data("select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
						// lpad(bcno,6,'0') bcno,bcdate,supplier,a.id_item,goods_code,$fld itemdesc,s.color,s.size,s.country,
						// a.qty,0 as qty_good,0 as qty_reject,
						// a.unit,berat_bersih,remark,$flduser,a.confirm_by,ac.kpno ws,ac.styleno,a.curr,a.price,inv.v_noinvoicecommercial,a.switch_out,so.so_no  
						// from bppb a inner join $tbl s on a.id_item=s.id_item
						// inner join mastersupplier d on a.id_supplier=d.id_supplier 
						// $joinjo  
						// left join so on tmpjod.id_so=so.id 
						// left join act_costing ac on so.id_cost=ac.id
						// left join (select a.id_inv,a.bppbno as bppbout,c.v_noinvoicecommercial,c.v_userpost  from invoice_detail a
						// inner join bppb b on a.bppbno = b.bppbno_int
						// inner join invoice_commercial c on a.id_inv = c.n_idinvoiceheader where c.v_userpost != 'null'
						// group by a.id_inv) inv on a.bppbno_int = inv.bppbout						 
						// where $whereout and bppbdate between '$from' and '$to'
						// $que_cl $que_supp order by bppbdate",30);

		$sql = "select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,ifnull(mp.nama_pc, 'NIRWANA ALABARE GARMENT') profit_center,invno,jenis_dok,right(nomor_aju,6) aju,tanggal_aju,
		lpad(bcno,6,'0') bcno,bcdate,supplier,a.id_item,goods_code,$fld itemdesc,s.color,s.size,s.country,
		a.qty,0 as qty_good,0 as qty_reject,
		a.unit,berat_bersih,remark,$flduser,a.confirm_by,ac.kpno ws,ac.styleno,a.curr,a.price,inv.v_noinvoicecommercial,a.switch_out,so.so_no  
		from bppb a inner join $tbl s on a.id_item=s.id_item
		LEFT join master_pc mp on mp.kode_pc = a.profit_center
		inner join mastersupplier d on a.id_supplier=d.id_supplier 
		$joinjo  
		left join so on tmpjod.id_so=so.id 
		left join act_costing ac on so.id_cost=ac.id
		left join (select a.id_inv,a.bppbno as bppbout,c.v_noinvoicecommercial,c.v_userpost  from invoice_detail a
		inner join bppb b on a.bppbno = b.bppbno_int
		inner join invoice_commercial c on a.id_inv = c.n_idinvoiceheader where c.v_userpost != 'null'
		group by a.id_inv) inv on a.bppbno_int = inv.bppbout						 
		where $whereout and bppbdate between '$from' and '$to'
		$que_cl $que_supp order by bppbdate ";
						#echo $sql;
		$query = mysql_query($sql);
		$no = 1;
		while ($data = mysql_fetch_array($query)) {

			echo "<tr";
			echo "
			<td>$no</td>
			<td>$data[bppbno]</td>
			<td>$data[bppbdate]</td>
			<td>$data[profit_center]</td>
			<td>$data[invno]</td>
			<td>$data[jenis_dok]</td>
			<td>$data[aju]</td>
			<td>$data[tanggal_aju]</td>
			<td>$data[bcno]</td>
			<td>$data[bcdate]</td>
			<td>$data[supplier]</td>
			<td>$data[id_item]</td>
			<td>$data[goods_code]</td>
			<td>$data[itemdesc]</td>
			<td>$data[color]</td>
			<td class='text'>$data[size]</td>
			<td>$data[country]</td>
			<td>$data[qty]</td>
			<td>$data[qty_good]</td>
			<td>$data[qty_reject]</td>
			<td>$data[unit]</td>
			<td>$data[berat_bersih]</td>
			<td>$data[remark]</td>
			<td>$data[username]</td>
			<td>$data[confirm_by]</td>
			<td>$data[ws]</td>
			<td class='text'>$data[styleno]</td>
			<td>$data[curr]</td>
			<td>$data[price]</td>
			<td>$data[v_noinvoicecommercial]</td>
			<td>$data[switch_out]</td>
			<td>$data[so_no]</td>

			";
			echo "</tr>";
          $no++; // menambah nilai nomor urut
        }														


      } else if ($tipenya == "Barang Jadi" && $classnya = "BARANG JADI STOK"){
		$sql = "select
				no_trans_out as bppbno,
				tgl_pengeluaran as bppbdate,
				'NIRWANA ALABARE GARMENT' as profit_center,
				'' as invno,
				no_dok as jenis_dok,
				'-' as aju,
				'' as tanggal_aju, 
				'' bcno,
				'' bcdate,
				tujuan as supplier,
				s.id_item,
				goods_code,
				itemname itemdesc,
				s.color,
				s.size,
				s.country, 
				sum(a.qty_out) as qty,
				0 as qty_good,
				0 as qty_reject,
				'PCS' unit,
				'' berat_bersih,
				'' remark,
				a.created_by as username,
				'' confirm_by,
				ac.kpno ws,
				ac.styleno,
				so.curr,
				sd.price,
				'' as v_noinvoicecommercial,
				'' switch_out,
				so.so_no
        from laravel_nds.fg_stok_bppb a
        inner join so_det sd on a.id_so_det = sd.id
				inner join so on sd.id_so = so.id
				inner join act_costing ac on so.id_cost = ac.id
				inner join mastersupplier ms on ac.id_buyer = ms.Id_Supplier
				inner join masterproduct mp on ac.id_product = mp.id
				left join masterstyle s on a.id_so_det = s.id_so_det
        where sd.cancel = 'N' and so.cancel_h = 'N' and ac.aktif = 'Y' and a.cancel = 'N'
        and tgl_pengeluaran >= '$from' and tgl_pengeluaran <= '$to'
        group by no_trans_out, s.id_item, price
        order by tgl_pengeluaran desc,substr(no_trans_out,14) desc ";
						#echo $sql;
		$query = mysql_query($sql);
		$no = 1;
		while ($data = mysql_fetch_array($query)) {

			echo "<tr";
			echo "
			<td>$no</td>
			<td>$data[bppbno]</td>
			<td>$data[bppbdate]</td>
			<td>$data[profit_center]</td>
			<td>$data[invno]</td>
			<td>$data[jenis_dok]</td>
			<td>$data[aju]</td>
			<td>$data[tanggal_aju]</td>
			<td>$data[bcno]</td>
			<td>$data[bcdate]</td>
			<td>$data[supplier]</td>
			<td>$data[id_item]</td>
			<td>$data[goods_code]</td>
			<td>$data[itemdesc]</td>
			<td>$data[color]</td>
			<td class='text'>$data[size]</td>
			<td>$data[country]</td>
			<td>$data[qty]</td>
			<td>$data[qty_good]</td>
			<td>$data[qty_reject]</td>
			<td>$data[unit]</td>
			<td>$data[berat_bersih]</td>
			<td>$data[remark]</td>
			<td>$data[username]</td>
			<td>$data[confirm_by]</td>
			<td>$data[ws]</td>
			<td class='text'>$data[styleno]</td>
			<td>$data[curr]</td>
			<td>$data[price]</td>
			<td>$data[v_noinvoicecommercial]</td>
			<td>$data[switch_out]</td>
			<td>$data[so_no]</td>

			";
			echo "</tr>";
          $no++; // menambah nilai nomor urut
        }	


      }
      else
      {

      		if ($classnya == 'FABRIC' && $from >= '2025-01-01') {
      			tampil_data("(select a.*, b.styleno style_aktual from ((select a.no_bppb bppbno,a.no_req bppbno_req,a.tgl_bppb bppbdate,'NIRWANA ALABARE GARMENT' profit_center, no_invoice invno,a.dok_bc jenis_dok,right(no_aju,6) no_aju,tgl_aju tanggal_aju, lpad(no_daftar,6,'0') bcno,tgl_daftar bcdate,a.tujuan supplier,b.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, sum(b.qty_out) qty,0 as qty_good,0 as qty_reject, b.satuan unit,'' berat_bersih,a.catatan remark,CONCAT(a.created_by,' (',a.created_at, ') ') username,CONCAT(a.approved_by,' (',a.approved_date, ') ') confirm_by,ac.kpno ws,ac.styleno,b.curr,b.price,br.idws_act,'' jenis_trans,cp.nama_panel, cc.color_gmt,IF(a.jenis_pengeluaran is null,'-',a.jenis_pengeluaran) jenis_pengeluaran, b.id_jo
        from whs_bppb_h a
        inner join whs_bppb_det b on b.no_bppb = a.no_bppb
        inner join masteritem s on b.id_item=s.id_item
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen
        left join (select id_jo,id_so from jo_det group by id_jo ) tmpjod on tmpjod.id_jo=b.id_jo
        left join (select bppbno as no_req,idws_act from bppb_req group by no_req) br on a.no_req = br.no_req
        left join so on tmpjod.id_so=so.id
        left join act_costing ac on so.id_cost=ac.id
        left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item inner join masterpanel mp on bom_jo_item.id_panel = mp.id where id_panel != '0' group by id_item, id_jo) cp on s.id_gen = cp.id_item and b.id_jo = cp.id_jo
        left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on s.id_gen = cc.id_item and b.id_jo = cc.id_jo
        where LEFT(a.no_bppb,2) = 'GK' and b.status != 'N' and a.status != 'cancel' and a.tgl_bppb BETWEEN  '$from' and '$to' GROUP BY b.id_jo,b.id_item,b.satuan,b.no_bppb order by a.no_bppb)
        UNION
        (select a.no_mut bpbno,'' bppbno_req,a.tgl_mut bpbdate,'NIRWANA ALABARE GARMENT' profit_center,a.no_invoice invno,a.type_bc jenis_dok,right(a.no_aju,6) no_aju,a.tgl_aju, lpad(a.no_daftar,6,'0') bcno,a.tgl_daftar bcdate,'Mutasi Lokasi' supplier,a.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, qty,'0' qty_good, qty_reject, a.unit,'' berat_bersih,a.deskripsi remark,a.username,a.confirm_by,a.no_ws ws,tmpjo.styleno,a.curr,if(z.tipe_com !='Regular','0',a.price)price, a.no_ws idws_act,'' jenis_trans,cp.nama_panel, cc.color_gmt, 'Mutasi Lokasi', a.id_jo from (select mut.no_ws,a.no_mut,a.tgl_mut,c.type_bc,c.no_aju,c.tgl_aju, c.no_daftar,c.tgl_daftar,c.supplier,c.no_po,c.no_invoice,b.id_item, sum(qty_mutasi) qty,sum(qty_mutasi) as qty_good,'0' as qty_reject, a.unit,mut.deskripsi,CONCAT(mut.created_by,' (',mut.created_at, ') ') username,CONCAT(mut.approved_by,' (',mut.approved_date, ') ') confirm_by,b.curr,b.price, c.type_pch,b.id_jo from whs_mut_lokasi a
        inner join whs_mut_lokasi_h mut on mut.no_mut = a.no_mut
        left join whs_inmaterial_fabric c on c.no_dok = a.no_bpb
        left join (select no_dok,id_jo,id_item,'-' curr, '0' price,satuan unit FROM whs_lokasi_inmaterial GROUP BY no_dok,id_item
        UNION
        select no_bpb,id_jo,id_item,'-' curr, '0' price,unit FROM whs_sa_fabric GROUP BY no_bpb,id_item) b on b.no_dok = a.no_mut and a.id_item = b.id_item where a.status = 'Y' GROUP BY a.no_mut,id_item,unit) a
        inner join masteritem s on a.id_item=s.id_item
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen
        left join (select no_dok no_mut,id_jo,id_item, GROUP_CONCAT(DISTINCT CONCAT(kode_lok,' FABRIC WAREHOUSE RACK')) rak from whs_lokasi_inmaterial  where status = 'Y' and no_mut is not null group by no_dok) lr on a.no_mut = lr.no_mut
        left join po_header po on po.pono = a.no_po
        left join po_header_draft z on z.id = po.id_draft
        left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) tmpjo on tmpjo.id_jo=a.id_jo
        left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item inner join masterpanel mp on bom_jo_item.id_panel = mp.id where id_panel != '0' group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo
        left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo
        where a.tgl_mut BETWEEN  '$from' and '$to')) a left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) b on b.id_jo=a.id_jo)",33);
      		}elseif ($classnya == 'FABRIC FINISHED') {
      		tampil_data("select if(a.bppbno_int!='',a.bppbno_int,a.bppbno) bppbno, IF(a.bppbno_req != '', a.bppbno_req, '-') bppbno_req, a.bppbdate, 'NIRWANA ALABARE GARMENT' profit_center, a.invno, a.jenis_dok, a.nomor_aju no_aju, a.tanggal_aju, a.bcno, a.bcdate, ms.supplier, a.id_item,id_contents, mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, 0 qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, '-' idws_act, a.jenis_trans, '-' nama_panel, '-' color_gmt, a.jenis_trans jenis_pengeluaran, a.id_jo, '-' style_aktual from bppb a INNER JOIN masteritem mi on mi.id_item = a.id_item 
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen
      			INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bPpbdate BETWEEN '$from' and '$to' and a.bppbno_int like '%OFC%'",33);
      	}elseif ($classnya == 'FABRIC GREIGE') {
      		tampil_data("select if(a.bppbno_int!='',a.bppbno_int,a.bppbno) bppbno, IF(a.bppbno_req != '', a.bppbno_req, '-') bppbno_req, a.bppbdate, 'NIRWANA ALABARE GARMENT' profit_center, a.invno, a.jenis_dok, a.nomor_aju no_aju, a.tanggal_aju, a.bcno, a.bcdate, ms.supplier, a.id_item, id_contents,mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, 0 qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, '-' idws_act, a.jenis_trans, '-' nama_panel, '-' color_gmt, a.jenis_trans jenis_pengeluaran, a.id_jo, '-' style_aktual from bppb a INNER JOIN masteritem mi 
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen
      			on mi.id_item = a.id_item INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bppbdate BETWEEN '$from' and '$to' and (a.bppbno_int like '%GG/%' OR a.bppbno_int like '%SGG/%')",33);
      	}elseif ($classnya == 'BENANG') {
      		tampil_data("select if(a.bppbno_int!='',a.bppbno_int,a.bppbno) bppbno, IF(a.bppbno_req != '', a.bppbno_req, '-') bppbno_req, a.bppbdate, 'NIRWANA ALABARE GARMENT' profit_center, a.invno, a.jenis_dok, a.nomor_aju no_aju, a.tanggal_aju, a.bcno, a.bcdate, ms.supplier, a.id_item,id_contents, mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, 0 qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, '-' idws_act, a.jenis_trans, '-' nama_panel, '-' color_gmt, a.jenis_trans jenis_pengeluaran, a.id_jo, '-' style_aktual from bppb a INNER JOIN masteritem mi on mi.id_item = a.id_item 
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen
      			INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bPpbdate BETWEEN '$from' and '$to' and a.bppbno_int like '%GB/%'",33);
      	}elseif ($classnya == 'FABRIC' && $from < '2025-01-01') {
      		tampil_data("select if(bppbno_int!='',bppbno_int,bppbno) bppbno,a.bppbno_req,bppbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
      			lpad(bcno,6,'0') bcno,bcdate,supplier,a.id_item,$id_contents,goods_code,$fld itemdesc,s.color,s.size,
      			a.qty,0 as qty_good,0 as qty_reject,
      			a.unit,berat_bersih,remark,$flduser,a.confirm_by,ac.kpno ws,ac.styleno,a.curr,a.price,br.idws_act,a.jenis_trans,cp.nama_panel, cc.color_gmt 
      			from bppb a inner join $tbl s on a.id_item=s.id_item
      			inner join mastersupplier d on a.id_supplier=d.id_supplier
      			$joinjo
      			$join_mc
      			left join (select bppbno as no_req,idws_act from bppb_req group by no_req) br  on a.bppbno_req = br.no_req   
      			left join so on tmpjod.id_so=so.id 
      			left join act_costing ac on so.id_cost=ac.id
      			left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item
      			inner join masterpanel mp on bom_jo_item.id_panel = mp.id
      			where id_panel != '0' 
      			group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo	
      			left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k
      			inner join so_det sd on k.id_so_det = sd.id
      			where status = 'M' and k.cancel = 'N'
      			group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo				 
      			where $whereout and bppbdate between '$from' and '$to'
      			$que_cl $que_supp
      			UNION
      			(select bpbno, bppbno_req, bpbdate, profit_center, invno, jenis_dok, no_aju, tgl_aju, bcno, bcdate, supplier, id_item, id_contents, goods_code, itemdesc, color, size, qty, qty_good, qty_reject, unit, berat_bersih, remark, username, confirm_by, ws, a.styleno, curr, price, idws_act, jenis_trans, nama_panel, color_gmt from (
        (select a.no_mut bpbno,'' bppbno_req,a.tgl_mut bpbdate,'NIRWANA ALABARE GARMENT' profit_center,a.no_invoice invno,a.type_bc jenis_dok,right(a.no_aju,6) no_aju,a.tgl_aju, lpad(a.no_daftar,6,'0') bcno,a.tgl_daftar bcdate,'Mutasi Lokasi' supplier,a.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, qty,'0' qty_good, qty_reject, a.unit,'' berat_bersih,a.deskripsi remark,a.username,a.confirm_by,a.no_ws ws,tmpjo.styleno,a.curr,if(z.tipe_com !='Regular','0',a.price)price, a.no_ws idws_act,'' jenis_trans,cp.nama_panel, cc.color_gmt, 'Mutasi Lokasi', a.id_jo from (select mut.no_ws,a.no_mut,a.tgl_mut,c.type_bc,c.no_aju,c.tgl_aju, c.no_daftar,c.tgl_daftar,c.supplier,c.no_po,c.no_invoice,b.id_item, sum(qty_mutasi) qty,sum(qty_mutasi) as qty_good,'0' as qty_reject, a.unit,mut.deskripsi,CONCAT(mut.created_by,' (',mut.created_at, ') ') username,CONCAT(mut.approved_by,' (',mut.approved_date, ') ') confirm_by,b.curr,b.price, c.type_pch,b.id_jo from whs_mut_lokasi a
        inner join whs_mut_lokasi_h mut on mut.no_mut = a.no_mut
        left join whs_inmaterial_fabric c on c.no_dok = a.no_bpb
        left join (select no_dok,id_jo,id_item,'-' curr, '0' price,satuan unit FROM whs_lokasi_inmaterial GROUP BY no_dok,id_item
        UNION
        select no_bpb,id_jo,id_item,'-' curr, '0' price,unit FROM whs_sa_fabric GROUP BY no_bpb,id_item) b on b.no_dok = a.no_mut and a.id_item = b.id_item where a.status = 'Y' GROUP BY a.no_mut,id_item,unit) a
        inner join masteritem s on a.id_item=s.id_item
                				left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen
        left join (select no_dok no_mut,id_jo,id_item, GROUP_CONCAT(DISTINCT CONCAT(kode_lok,' FABRIC WAREHOUSE RACK')) rak from whs_lokasi_inmaterial  where status = 'Y' and no_mut is not null group by no_dok) lr on a.no_mut = lr.no_mut
        left join po_header po on po.pono = a.no_po
        left join po_header_draft z on z.id = po.id_draft
        left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) tmpjo on tmpjo.id_jo=a.id_jo
        left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item inner join masterpanel mp on bom_jo_item.id_panel = mp.id where id_panel != '0' group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo
        left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo
        where a.tgl_mut BETWEEN  '$from' and '$to')) a left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) b on b.id_jo=a.id_jo)",33);
      	}else{
      		tampil_data("select if(bppbno_int!='',bppbno_int,bppbno) bppbno,a.bppbno_req,bppbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
      			lpad(bcno,6,'0') bcno,bcdate,supplier,a.id_item,$id_contents,goods_code,$fld itemdesc,s.color,s.size,
      			a.qty,0 as qty_good,0 as qty_reject,
      			a.unit,berat_bersih,remark,$flduser,a.confirm_by,ac.kpno ws,ac.styleno,a.curr,a.price,br.idws_act,a.jenis_trans,cp.nama_panel, cc.color_gmt 
      			from bppb a inner join $tbl s on a.id_item=s.id_item
      			inner join mastersupplier d on a.id_supplier=d.id_supplier
      			$joinjo
      			$join_mc
      			left join (select bppbno as no_req,idws_act from bppb_req group by no_req) br  on a.bppbno_req = br.no_req   
      			left join so on tmpjod.id_so=so.id 
      			left join act_costing ac on so.id_cost=ac.id
      			left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item
      			inner join masterpanel mp on bom_jo_item.id_panel = mp.id
      			where id_panel != '0' 
      			group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo	
      			left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k
      			inner join so_det sd on k.id_so_det = sd.id
      			where status = 'M' and k.cancel = 'N'
      			group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo				 
      			where $whereout and bppbdate between '$from' and '$to'
      			$que_cl $que_supp order by bppbdate",33);
      	}

      		// echo $classnya;

      }					

    }
    else if ($mode=="Hist")
    { 
    	if ($nm_company=="PT. Seyang Indonesia")
    		{	tampil_data("select trans_date,trans_desc,trans_host 
    			from act_hist where trans_date between '$from 00:00:00' 
    			and '$to 23:59:59' and ucase(trans_host) not regexp 'SEYANG' 
    			order by trans_date",3);
    }
    else
    	{	tampil_data("select trans_date,trans_desc,trans_host 
    		from act_hist where trans_date between '$from 00:00:00' 
    		and '$to 23:59:59' 
    		order by trans_date",3);
  }
}
else
{ 
	if($tipenya_2 == "Item General")
	{
						// tampil_data("SELECT if(a.bpbno_int!='',a.bpbno_int,a.bpbno) bpbno,a.bpbdate,a.invno,a.jenis_dok,right(a.nomor_aju,6),a.tanggal_aju,
						// 	lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.pono,a.invno,s.goods_code,CONCAT(s.itemdesc,' ',s.add_info) itemdesc,s.color,s.size,
						// 	a.qty,a.unit,a.berat_bersih,a.remark,username,stock_card,'' whs_code,a.curr,a.price 
						// 	from bpb a inner join masteritem s on a.id_item=s.id_item
						// 	LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
						// 	where  bpbno_int LIKE '%GEN%' $que_supp AND a.bpbdate >= '$from'
						// 	AND a.bpbdate <= '$to'",29);	
		tampil_data("SELECT 
			if(a.bpbno_int!='',a.bpbno_int,a.bpbno) bpbno,a.bpbdate, ifnull(mp.nama_pc, 'NIRWANA ALABARE GARMENT') profit_center, a.invno,a.jenis_dok,right(a.nomor_aju,6),a.tanggal_aju,
				lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.pono,z.tipe_com,a.invno,a.id_item,s.goods_code,
			CONCAT(s.itemdesc,' ',coalesce(s.add_info,'')) itemdesc,m.description,s.color,s.size,
			a.qty,a.unit,a.berat_bersih,a.remark,a.username,CONCAT(a.confirm_by, ' (', a.confirm_date, ')') AS confirm_by,r.reqno,'' whs_code,a.curr,a.price,a.jenis_trans,a.reffno 
			from bpb a inner join masteritem s on a.id_item=s.id_item
			LEFT join (select pono,tipe_com from po_header_draft inner join po_header on po_header_draft.id = po_header.id_draft) z  on a.pono = z.pono											
			LEFT join mastersupplier d on a.id_supplier=d.id_supplier
			LEFT join master_pc mp on mp.kode_pc = a.profit_center
			LEFT OUTER JOIN mapping_category AS m ON s.n_code_category=m.n_id
			left join reqnon_header r on a.id_jo = r.id
			where  bpbno_int LIKE '%GEN%' $que_supp AND a.bpbdate >= '$from'
			AND a.bpbdate <= '$to'",31);					
	}
	else
	{
		if ($tbl=="masterstyle") {$fldsty="s.styleno"; $fldsc="buyerno,'' stock_card,whs_code";} else {$fldsty="tmpjo.styleno"; $fldsc="'' buyerno,stock_card,'' whs_code";}

		if($tipenya_2 == "Barang Jadi" && $classnya != "BARANG JADI STOK")
		{

									// tampil_data("select if(a.bpbno_int!='',a.bpbno_int,a.bpbno)  bpbno,a.bpbdate,a.invno,a.jenis_dok,right(a.nomor_aju,6),a.tanggal_aju,
									// 					lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.pono,z.tipe_com,
									// 					if(a.invno !='--',a.invno,tmpout.bppbno_int) as invno,a.id_item,
									// 					goods_code,$fld itemdesc,s.color,s.size,s.country,a.grade,
									// 					a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
									// 					a.unit,a.berat_bersih,a.remark,$flduser,a.confirm_by,tmpjo.kpno ws,tmpjo.styleno,a.curr,a.price,a.switch_in,a.reffno 
									// 					from bpb a inner join $tbl s on a.id_item=s.id_item
									// 					inner join mastersupplier d on a.id_supplier=d.id_supplier
									// 		LEFT join (select pono,tipe_com from po_header_draft inner join po_header on po_header_draft.id = po_header.id_draft) z  on a.pono = z.pono														 
									// 					left join 
									// 					(SELECT sod.id,ac.kpno,ac.styleno FROM act_costing ac INNER JOIN so ON ac.id=so.id_cost INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY sod.id) tmpjo ON tmpjo.id=a.id_so_det 
									// 					left join
									// 					(select distinct bppbno,bppbno_int from bppb) as tmpout on a.bppbno_ri=tmpout.bppbno  
									// 					where $where and bpbdate between '$from' and '$to' 
									// 					$que_cl $que_supp order by bpbdate",33);
			$sql = "select if(a.bpbno_int!='',a.bpbno_int,a.bpbno)  bpbno,a.bpbdate,'NIRWANA ALABARE GARMENT' profit_center, a.invno,a.jenis_dok,right(a.nomor_aju,6) aju,a.tanggal_aju,
			lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.pono,z.tipe_com,
			if(a.invno !='--',a.invno,tmpout.bppbno_int) as invno,a.id_item,
			goods_code,$fld itemdesc,s.color,s.size,s.country,a.grade,
			a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
			a.unit,a.berat_bersih,a.remark,$flduser,a.confirm_by,tmpjo.kpno ws,tmpjo.styleno,a.curr,a.price,a.switch_in,a.reffno 
			from bpb a inner join $tbl s on a.id_item=s.id_item
			inner join mastersupplier d on a.id_supplier=d.id_supplier
			LEFT join (select pono,tipe_com from po_header_draft inner join po_header on po_header_draft.id = po_header.id_draft) z  on a.pono = z.pono														 
			left join 
			(SELECT sod.id,ac.kpno,ac.styleno FROM act_costing ac INNER JOIN so ON ac.id=so.id_cost INNER JOIN so_det sod ON so.id=sod.id_so GROUP BY sod.id) tmpjo ON tmpjo.id=a.id_so_det 
			left join
			(select distinct bppbno,bppbno_int from bppb) as tmpout on a.bppbno_ri=tmpout.bppbno  
			where $where and bpbdate between '$from' and '$to' 
			$que_cl $que_supp order by bpbdate";
			$query = mysql_query($sql);
			$no = 1;
														#echo $sql;
			while ($data = mysql_fetch_array($query)) {

				echo "<tr";
				echo "
				<td>$no</td>
				<td>$data[bpbno]</td>
				<td>$data[bpbdate]</td>
				<td>$data[profit_center]</td>
				<td>$data[invno]</td>
				<td>$data[jenis_dok]</td>
				<td>$data[aju]</td>
				<td>$data[tanggal_aju]</td>
				<td>$data[bcno]</td>
				<td>$data[bcdate]</td>
				<td>$data[supplier]</td>
				<td>$data[pono]</td>
				<td>$data[tipe_com]</td>
				<td>$data[invno]</td>
				<td>$data[id_item]</td>
				<td>$data[goods_code]</td>
				<td>$data[itemdesc]</td>
				<td>$data[color]</td>
				<td class='text'>$data[size]</td>
				<td>$data[country]</td>
				<td>$data[grade]</td>
				<td>$data[qty]</td>
				<td>$data[qty_good]</td>
				<td>$data[qty_reject]</td>
				<td>$data[unit]</td>
				<td>$data[berat_bersih]</td>
				<td>$data[remark]</td>
				<td>$data[username]</td>
				<td>$data[confirm_by]</td>
				<td>$data[ws]</td>
				<td class='text'>$data[styleno]</td>
				<td>$data[curr]</td>
				<td>$data[price]</td>
				<td>$data[switch_in]</td>
				<td>$data[reffno]</td>

				";
				echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      } else if($tipenya_2 == "Barang Jadi" && $classnya == "BARANG JADI STOK"){

			$sql = " select
				no_trans as bpbno,
				tgl_terima as bpbdate,
				'NIRWANA ALABARE GARMENT' profit_center, 
				'-' as invno,
				'INHOUSE' as jenis_dok,
				'-' aju,
				tgl_terima as tanggal_aju,
				'' as bcno,
				'' as bcdate,
				sumber_pemasukan as supplier,
				'' as pono,
				'' as tipe_com,
				s.id_item,
				s.goods_code,
				itemname itemdesc,
				s.color,
				s.size,
				s.country,
				a.grade,
				sum(a.qty) as qty,
				sum(a.qty) as qty_good,
				'0' as qty_reject,
				'PCS' unit,
				'' berat_bersih,
				'' remark,
				a.created_by as username,
				'' confirm_by,
				ac.kpno ws,
				ac.styleno styleno,
				so.curr,
				sd.price,
				'' switch_in,
				sd.reff_no 
        from laravel_nds.fg_stok_bpb a
				inner join so_det sd on a.id_so_det = sd.id
				inner join so on sd.id_so = so.id
				inner join act_costing ac on so.id_cost = ac.id
				inner join mastersupplier ms on ac.id_buyer = ms.Id_Supplier
				inner join masterproduct mp on ac.id_product = mp.id
				left join masterstyle s on a.id_so_det = s.id_so_det
        where sd.cancel = 'N' and so.cancel_h = 'N' and ac.aktif = 'Y' and a.cancel = 'N'
				and tgl_terima >= '$from' and tgl_terima <= '$to'
				group by no_trans, s.id_item, price
        order by tgl_terima desc,substr(no_trans,13) desc";
			$query = mysql_query($sql);
			$no = 1;
														#echo $sql;
			while ($data = mysql_fetch_array($query)) {

				echo "<tr";
				echo "
				<td>$no</td>
				<td>$data[bpbno]</td>
				<td>$data[bpbdate]</td>
				<td>$data[profit_center]</td>
				<td>$data[invno]</td>
				<td>$data[jenis_dok]</td>
				<td>$data[aju]</td>
				<td>$data[tanggal_aju]</td>
				<td>$data[bcno]</td>
				<td>$data[bcdate]</td>
				<td>$data[supplier]</td>
				<td>$data[pono]</td>
				<td>$data[tipe_com]</td>
				<td>$data[invno]</td>
				<td>$data[id_item]</td>
				<td>$data[goods_code]</td>
				<td>$data[itemdesc]</td>
				<td>$data[color]</td>
				<td class='text'>$data[size]</td>
				<td>$data[country]</td>
				<td>$data[grade]</td>
				<td>$data[qty]</td>
				<td>$data[qty_good]</td>
				<td>$data[qty_reject]</td>
				<td>$data[unit]</td>
				<td>$data[berat_bersih]</td>
				<td>$data[remark]</td>
				<td>$data[username]</td>
				<td>$data[confirm_by]</td>
				<td>$data[ws]</td>
				<td class='text'>$data[styleno]</td>
				<td>$data[curr]</td>
				<td>$data[price]</td>
				<td>$data[switch_in]</td>
				<td>$data[reffno]</td>

				";
				echo "</tr>";
          $no++; // menambah nilai nomor urut
        }

      } 
      else
      {
      	if ($classnya == 'FABRIC' && $from >= '2025-01-01') {
      		tampil_data("select bpbno,bpbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,no_aju,tgl_aju, bcno, bcdate,supplier,pono,tipe_com,invno,a.id_item,id_contents,goods_code,itemdesc,color,size, qty, qty_good,qty_reject, unit,berat_bersih, remark,username,confirm_by, if(bpbno like '%MT%',a.no_ws,tmpjo.kpno) ws,tmpjo.styleno,curr,price, price price_act, jenis_trans,reffno,rak,cp.nama_panel,cc.color_gmt 
      			from (select s.id_gen,a.no_dok bpbno,a.tgl_dok bpbdate,type_bc jenis_dok,right(no_aju,6) no_aju,tgl_aju, lpad(no_daftar,6,'0') bcno,tgl_daftar bcdate,a.supplier,a.no_po pono,z.tipe_com,no_invoice invno,b.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, (b.qty_good + coalesce(b.qty_reject,0)) qty,b.qty_good as qty_good,coalesce(b.qty_reject,0) as qty_reject, b.unit,'' berat_bersih,a.deskripsi remark,CONCAT(a.created_by,' (',a.created_at, ') ') username,CONCAT(a.approved_by,' (',a.approved_date, ') ') confirm_by,b.curr,if(z.tipe_com ='FOC','0',b.price)price, a.type_pch jenis_trans,'' reffno,lr.rak,b.id_jo,'' no_ws, a.type_pch from whs_inmaterial_fabric a
        inner join whs_inmaterial_fabric_det b on b.no_dok = a.no_dok
        inner join masteritem s on b.id_item=s.id_item
        left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen	
        left join (select no_dok,id_jo,id_item, GROUP_CONCAT(DISTINCT CONCAT(kode_lok,' FABRIC WAREHOUSE RACK')) rak from whs_lokasi_inmaterial  where status = 'Y' group by no_dok,id_jo,id_item) lr on b.no_dok = lr.no_dok and b.id_item = lr.id_item and b.id_jo = lr.id_jo
        left join po_header po on po.pono = a.no_po
        left join po_header_draft z on z.id = po.id_draft
        where a.tgl_dok BETWEEN  '$from' and '$to' and b.status != 'N' and a.status != 'cancel'
        UNION
        select s.id_gen,a.no_mut bpbno,a.tgl_mut bpbdate,a.type_bc jenis_dok,right(a.no_aju,6) no_aju,a.tgl_aju, lpad(a.no_daftar,6,'0') bcno,a.tgl_daftar bcdate,a.supplier,a.no_po pono,z.tipe_com,a.no_invoice invno,a.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, qty,qty_good, qty_reject, a.unit,'' berat_bersih,a.deskripsi remark,a.username,a.confirm_by,a.curr,if(z.tipe_com !='Regular','0',a.price)price, a.type_pch jenis_trans,'' reffno,lr.rak,a.id_jo,a.no_ws, 'Mutasi Lokasi' from (select mut.no_ws,a.no_mut,a.tgl_mut,c.type_bc,c.no_aju,c.tgl_aju, c.no_daftar,c.tgl_daftar,c.supplier,c.no_po,c.no_invoice,b.id_item, sum(qty_mutasi) qty,sum(qty_mutasi) as qty_good,'0' as qty_reject, a.unit,mut.deskripsi,CONCAT(mut.created_by,' (',mut.created_at, ') ') username,CONCAT(mut.approved_by,' (',mut.approved_date, ') ') confirm_by,b.curr,b.price, c.type_pch,b.id_jo from whs_mut_lokasi a
        inner join whs_mut_lokasi_h mut on mut.no_mut = a.no_mut
        left join whs_inmaterial_fabric c on c.no_dok = a.no_bpb
        left join (select no_dok,id_jo,id_item,'-' curr, '0' price,satuan unit FROM whs_lokasi_inmaterial GROUP BY no_dok,id_item
        UNION
        select no_bpb,id_jo,id_item,'-' curr, '0' price,unit FROM whs_sa_fabric GROUP BY no_bpb,id_item) b on b.no_dok = a.no_mut and a.id_item = b.id_item where a.status = 'Y' GROUP BY a.no_mut,id_item,unit) a
        inner join masteritem s on a.id_item=s.id_item
                left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen	
        left join (select no_dok no_mut,id_jo,id_item, GROUP_CONCAT(DISTINCT CONCAT(kode_lok,' FABRIC WAREHOUSE RACK')) rak from whs_lokasi_inmaterial  where status = 'Y' and no_mut is not null group by no_dok) lr on a.no_mut = lr.no_mut
        left join po_header po on po.pono = a.no_po
        left join po_header_draft z on z.id = po.id_draft
        where a.tgl_mut BETWEEN  '$from' and '$to') a
        left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) tmpjo on tmpjo.id_jo=a.id_jo
        left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item inner join masterpanel mp on bom_jo_item.id_panel = mp.id where id_panel != '0' group by id_item, id_jo) cp on a.id_gen = cp.id_item and a.id_jo = cp.id_jo
        left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on a.id_gen = cc.id_item and a.id_jo = cc.id_jo",37);
      	}elseif ($classnya == 'FABRIC FINISHED') {
      		tampil_data("select bpbno_int, bpbdate, 'NIRWANA ALABARE GARMENT' profit_center, invno, jenis_dok, nomor_aju no_aju, tanggal_aju, bcno, bcdate, ms.supplier, a.pono, '-' tipe_com, invno, a.id_item,id_contents, mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, '0' qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, a.price price_act, jenis_trans, '-' reffno, '-' rak, '-' nama_panel, '-' color_gmt from bpb a INNER JOIN masteritem mi on mi.id_item = a.id_item 
        left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen	
      			INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bpbdate BETWEEN '$from' and '$to' and a.bpbno_int like '%GM/%'",37);
      	}elseif ($classnya == 'FABRIC GREIGE') {
      		tampil_data("select bpbno_int, bpbdate, 'NIRWANA ALABARE GARMENT' profit_center, invno, jenis_dok, nomor_aju no_aju, tanggal_aju, bcno, bcdate, ms.supplier, a.pono, '-' tipe_com, invno, a.id_item,id_contents, mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, '0' qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, a.price price_act, jenis_trans, '-' reffno, '-' rak, '-' nama_panel, '-' color_gmt from bpb a INNER JOIN masteritem mi on mi.id_item = a.id_item 
        left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen	
      			INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bpbdate BETWEEN '$from' and '$to' and (a.bpbno_int like '%GG/%' OR a.bpbno_int like '%SGG/%')",37);
      	}elseif ($classnya == 'BENANG') {
      		tampil_data("select bpbno_int, bpbdate, 'NIRWANA ALABARE GARMENT' profit_center, invno, jenis_dok, nomor_aju no_aju, tanggal_aju, bcno, bcdate, ms.supplier, a.pono, '-' tipe_com, invno, a.id_item, id_contents, mi.goods_code, mi.itemdesc, mi.color, mi.size, a.qty, a.qty qty_good, '0' qty_reject, a.unit, a.berat_bersih, a.remark, a.username, a.confirm_by, '-' ws, '-' styleno, a.curr, a.price, a.price price_act, jenis_trans, '-' reffno, '-' rak, '-' nama_panel, '-' color_gmt from bpb a INNER JOIN masteritem mi on mi.id_item = a.id_item
        left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on mi.id_gen = mc.id_gen	
      		 INNER JOIN mastersupplier ms on ms.id_supplier = a.id_supplier where bpbdate BETWEEN '$from' and '$to' and (a.bpbno_int like '%GB/%' OR a.bpbno_int like '%BPB/NAK%') ",37);
      	}elseif ($classnya == 'FABRIC' && $from < '2025-01-01') {
      		tampil_data("select if(bpbno_int!='',bpbno_int,bpbno) bpbno,bpbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
      		lpad(bcno,6,'0') bcno,bcdate,supplier,a.pono,z.tipe_com,invno,a.id_item,id_contents,goods_code,$fld itemdesc,s.color,s.size,
      		a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
      		a.unit,berat_bersih,remark,$flduser,a.confirm_by,tmpjo.kpno ws,tmpjo.styleno,a.curr,if(z.tipe_com !='Regular','0',a.price)price,a.price, a.jenis_trans,a.reffno,lr.rak,cp.nama_panel,cc.color_gmt 
      		from bpb a inner join $tbl s on a.id_item=s.id_item
      		left join 
									(
									select a.id as id_gen ,e.id_contents from masterdesc a 
									left join mastercolor b on a.id_color = b.id
									left join masterweight c on b.id_weight = c.id
									left join masterlength d on c.id_length = d.id
									left join masterwidth e on d.id_width = e.id
									) mc on s.id_gen = mc.id_gen
      		left join (select brh.bpbno as nomorbpb, id_jo, id_item, id_rak_loc, group_concat(distinct kode_rak , ' ', nama_rak) rak from bpb_roll_h brh 
      		inner join bpb_roll br on brh.id = br.id_h
      		inner join master_rak mr on br.id_rak_loc = mr.id
      		group by brh.bpbno, id_jo, id_item) lr on a.bpbno = lr.nomorbpb and a.id_item = lr.id_item and a.id_jo = lr.id_jo
      		inner join mastersupplier d on a.id_supplier=d.id_supplier
      		LEFT join (select pono,tipe_com from po_header_draft inner join po_header on po_header_draft.id = po_header.id_draft where po_header.app = 'A') z  on a.pono = z.pono													 
      		left join 
      		(select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join 
      		jo_det jod on so.id=jod.id_so group by id_jo) tmpjo 
      		on tmpjo.id_jo=a.id_jo
      		left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item
      		inner join masterpanel mp on bom_jo_item.id_panel = mp.id
      		where id_panel != '0' 
      		group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo
      		left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo    
      		where $where and bpbdate between '$from' and '$to'
      		$que_cl $que_supp 

      		UNION
      		select bpbno,bpbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,no_aju,tgl_aju, bcno, bcdate,supplier,pono,tipe_com,invno,a.id_item,id_contents,goods_code,itemdesc,color,size, qty, qty_good,qty_reject, unit,berat_bersih, remark,username,confirm_by, if(bpbno like '%MT%',a.no_ws,tmpjo.kpno) ws,tmpjo.styleno,curr,price, price price_act, jenis_trans,reffno,rak,cp.nama_panel,cc.color_gmt 
      			from (
        select s.id_gen,a.no_mut bpbno,a.tgl_mut bpbdate,a.type_bc jenis_dok,right(a.no_aju,6) no_aju,a.tgl_aju, lpad(a.no_daftar,6,'0') bcno,a.tgl_daftar bcdate,a.supplier,a.no_po pono,z.tipe_com,a.no_invoice invno,a.id_item,id_contents,goods_code,concat(itemdesc,' ',add_info) itemdesc,s.color,s.size, qty,qty_good, qty_reject, a.unit,'' berat_bersih,a.deskripsi remark,a.username,a.confirm_by,a.curr,if(z.tipe_com !='Regular','0',a.price)price, a.type_pch jenis_trans,'' reffno,lr.rak,a.id_jo,a.no_ws, 'Mutasi Lokasi' from (select mut.no_ws,a.no_mut,a.tgl_mut,c.type_bc,c.no_aju,c.tgl_aju, c.no_daftar,c.tgl_daftar,c.supplier,c.no_po,c.no_invoice,b.id_item, sum(qty_mutasi) qty,sum(qty_mutasi) as qty_good,'0' as qty_reject, a.unit,mut.deskripsi,CONCAT(mut.created_by,' (',mut.created_at, ') ') username,CONCAT(mut.approved_by,' (',mut.approved_date, ') ') confirm_by,b.curr,b.price, c.type_pch,b.id_jo from whs_mut_lokasi a
        inner join whs_mut_lokasi_h mut on mut.no_mut = a.no_mut
        left join whs_inmaterial_fabric c on c.no_dok = a.no_bpb
        left join (select no_dok,id_jo,id_item,'-' curr, '0' price,satuan unit FROM whs_lokasi_inmaterial GROUP BY no_dok,id_item
        UNION
        select no_bpb,id_jo,id_item,'-' curr, '0' price,unit FROM whs_sa_fabric GROUP BY no_bpb,id_item) b on b.no_dok = a.no_mut and a.id_item = b.id_item where a.status = 'Y' GROUP BY a.no_mut,id_item,unit) a
        inner join masteritem s on a.id_item=s.id_item
                left join 
								(
								select a.id as id_gen ,e.id_contents from masterdesc a 
								left join mastercolor b on a.id_color = b.id
								left join masterweight c on b.id_weight = c.id
								left join masterlength d on c.id_length = d.id
								left join masterwidth e on d.id_width = e.id
								) mc on s.id_gen = mc.id_gen	
        left join (select no_dok no_mut,id_jo,id_item, GROUP_CONCAT(DISTINCT CONCAT(kode_lok,' FABRIC WAREHOUSE RACK')) rak from whs_lokasi_inmaterial  where status = 'Y' and no_mut is not null group by no_dok) lr on a.no_mut = lr.no_mut
        left join po_header po on po.pono = a.no_po
        left join po_header_draft z on z.id = po.id_draft
        where a.tgl_mut BETWEEN  '$from' and '$to') a
        left join (select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join jo_det jod on so.id=jod.id_so group by id_jo) tmpjo on tmpjo.id_jo=a.id_jo
        left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item inner join masterpanel mp on bom_jo_item.id_panel = mp.id where id_panel != '0' group by id_item, id_jo) cp on a.id_gen = cp.id_item and a.id_jo = cp.id_jo
        left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on a.id_gen = cc.id_item and a.id_jo = cc.id_jo",37);
      	}else{

      	tampil_data("select if(bpbno_int!='',bpbno_int,bpbno) bpbno,bpbdate,'NIRWANA ALABARE GARMENT' profit_center, invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
      		lpad(bcno,6,'0') bcno,bcdate,supplier,a.pono,z.tipe_com,invno,a.id_item,id_contents,goods_code,$fld itemdesc,s.color,s.size,
      		a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
      		a.unit,berat_bersih,remark,$flduser,a.confirm_by,tmpjo.kpno ws,tmpjo.styleno,a.curr,if(z.tipe_com !='Regular','0',a.price)price,a.price, a.jenis_trans,a.reffno,lr.rak,cp.nama_panel,cc.color_gmt 
      		from bpb a inner join $tbl s on a.id_item=s.id_item
      		left join 
									(
									select a.id as id_gen ,e.id_contents from masterdesc a 
									left join mastercolor b on a.id_color = b.id
									left join masterweight c on b.id_weight = c.id
									left join masterlength d on c.id_length = d.id
									left join masterwidth e on d.id_width = e.id
									) mc on s.id_gen = mc.id_gen
      		left join (select brh.bpbno as nomorbpb, id_jo, id_item, id_rak_loc, group_concat(distinct kode_rak , ' ', nama_rak) rak from bpb_roll_h brh 
      		inner join bpb_roll br on brh.id = br.id_h
      		inner join master_rak mr on br.id_rak_loc = mr.id
      		group by brh.bpbno, id_jo, id_item) lr on a.bpbno = lr.nomorbpb and a.id_item = lr.id_item and a.id_jo = lr.id_jo
      		inner join mastersupplier d on a.id_supplier=d.id_supplier
      		LEFT join (select pono,tipe_com from po_header_draft inner join po_header on po_header_draft.id = po_header.id_draft where po_header.app = 'A') z  on a.pono = z.pono													 
      		left join 
      		(select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join 
      		jo_det jod on so.id=jod.id_so group by id_jo) tmpjo 
      		on tmpjo.id_jo=a.id_jo
      		left join (select id_jo,bom_jo_item.id_item,group_concat(distinct(nama_panel)) nama_panel from bom_jo_item
      		inner join masterpanel mp on bom_jo_item.id_panel = mp.id
      		where id_panel != '0' 
      		group by id_item, id_jo) cp on s.id_gen = cp.id_item and a.id_jo = cp.id_jo
      		left join (select id_item, id_jo, group_concat(distinct(color)) color_gmt from bom_jo_item k inner join so_det sd on k.id_so_det = sd.id where status = 'M' and k.cancel = 'N' group by id_item, id_jo) cc on s.id_gen = cc.id_item and a.id_jo = cc.id_jo    
      		where $where and bpbdate between '$from' and '$to'
      		$que_cl $que_supp order by bpbdate",37);
      }
      	// echo $classnya;

      }

    }
  }
  echo "</tbody>";
  echo "</table>";
  echo "</div>";
  echo "</div>";
?>