<?PHP
include "../../include/conn.php";
include "fungsi.php";

$nm_company=flookup("company","mastercompany","company!=''");
$st_company=flookup("status_company","mastercompany","company!=''");
$modenya = $_GET['modeajax'];

if ($modenya=="view_list_bc23" OR $modenya=="view_list_bc23_fg")
{	echo "<head>";
		echo "<link rel='stylesheet' href='../../bootstrap/css/fixed-table-header.css'>";
	echo "</head>";
}

if ($modenya=="copy_po_buyer")
{	$crinya = $_REQUEST['cri_item'];
	print($crinya);	
}
if ($modenya=="cari_supp_req" or $modenya=="cari_supp_sc")
{	$crinya = $_REQUEST['cri_item'];
	if($modenya=="cari_supp_req") {$tbl="bppb_req";} else {$tbl="bppb";}
	$sql="select supplier,jo_no from $tbl a inner join mastersupplier s on a.id_supplier=s.id_supplier 
		inner join jo on a.id_jo=jo.id where bppbno='$crinya' ";
	$rsjo=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rsjo['supplier'],$rsjo['jo_no']));	
}
if ($modenya=="cari_kp")
{	$crinya = $_REQUEST['cri_item'];
	$cek = flookup("kpno","masterstyle","id_item='$crinya'");
	print($cek);	
}
if ($modenya=="cari_kp_fg")
{	$crinya = $_REQUEST['cri_item'];
	$defpo=flookup("buyerno","masterstyle","id_item='$crinya'");
	$sql = "select buyerno isi,concat(kpno,' ',buyerno) tampil from masterstyle a 
		where id_item='$crinya'";
	#echo "<script>alert('sasasa')</script>";
	IsiCombo($sql,$defpo,'Pilih');
}
if ($modenya=="cari_satuan" OR $modenya=="cari_satuan_fg")
{	$crinya = $_REQUEST['cri_item'];
	$cridatenya = fd($_REQUEST['cri_date']);
	if ($crinya!="")
	{	if ($modenya=="cari_satuan_fg")
		{ $matnya=" and bpbno like 'FG%' "; $matonya=" and bppbno like 'SJ-FG%' "; }
		else
		{ $matnya=" and bpbno not like 'FG%' "; $matonya=" and bppbno not like 'SJ-FG%' "; }
		$jtbpb=flookup("count(distinct left(bpbno,1))","bpb","id_item='$crinya' $matnya ");
		$jtbpbval=flookup("distinct left(bpbno,1)","bpb","id_item='$crinya' $matnya ");
		$jtbppb=flookup("count(distinct mid(bppbno,4,1))","bppb","id_item='$crinya' $matonya ");
		$jtbppbval=flookup("distinct mid(bppbno,4,1)","bppb","id_item='$crinya' $matonya ");
		if ($jtbpb>1 OR $jtbppb>1) 
		{ echo "<script> alert('Ada lebih dari 1 type bahan baku. Silahkan dicek kembali');</script>"; }
		else if (($jtbpbval!=$jtbppbval) AND $jtbpbval!="" AND $jtbppbval!="") 
		{ echo "<script> alert('Ada lebih dari 1 type bahan baku. Silahkan dicek kembali');</script>"; }
		else
		{ $cek=flookup("count(*)","bpb","id_item='$crinya' $matnya ");
		  if ($cek=="0" OR $cek=="")
		  {	$sql = "select nama_pilihan isi,nama_pilihan tampil 
		   		from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
		  }
		  else
		  {	$sql = "select unit isi,unit tampil 
		  		from bpb where id_item='$crinya' $matnya group by unit";
		  }
		  IsiCombo($sql,'','Pilih Satuan');
		}
	}
}
///// RMN
if ($modenya=="cari_satuan_gen_new" OR $modenya=="cari_satuan_fg_new")
{	$crinya = $_REQUEST['cri_item'];
	$cridatenya = fd($_REQUEST['cri_date']);
	if ($crinya!="")
	{	if ($modenya=="cari_satuan_fg_new")
		{ $matnya=" and bpbno like 'FG%' "; $matonya=" and bppbno like 'SJ-FG%' "; }
		else
		{ $matnya=" and bpbno not like 'FG%' "; $matonya=" and bppbno not like 'SJ-FG%' "; }
		$jtbpb=flookup("count(distinct left(bpbno,1))","bpb","id_item='$crinya' $matnya ");
		$jtbpbval=flookup("distinct left(bpbno,1)","bpb","id_item='$crinya' $matnya ");
		$jtbppb=flookup("count(distinct mid(bppbno,4,1))","bppb","id_item='$crinya' $matonya ");
		$jtbppbval=flookup("distinct mid(bppbno,4,1)","bppb","id_item='$crinya' $matonya ");
// RMN
		// if ($jtbpb>1 OR $jtbppb>1) 
		// { echo "<script> alert('Ada lebih dari 1 type bahan baku. Silahkan dicek kembali');</script>"; }
		// else if (($jtbpbval!=$jtbppbval) AND $jtbpbval!="" AND $jtbppbval!="") 
		// { echo "<script> alert('Ada lebih dari 1 type bahan baku. Silahkan dicek kembali');</script>"; }
		// else
		{ $cek=flookup("count(*)","bpb","id_item='$crinya' $matnya ");
		  if ($cek=="0" OR $cek=="")
		  {	$sql = "";
		  }
		  else
		  {	$sql = "select mast_satuan.nama_pilihan isi, concat(coalesce(sa.qty,0) + coalesce(st.total_st,0) - coalesce(sot.total_sot,0), '  ', mast_satuan.nama_pilihan) tampil from
(select * from masterpilihan where kode_pilihan = 'satuan') mast_satuan
left join
(select sa.*, mi.id_item from saldoawal_gd sa
inner join masteritem mi on sa.kd_barang = mi.goods_code
where id_item = '$crinya' and sa.periode = (select tgl1 from tptglperiode where gudang = 'general') group by unit) sa on
mast_satuan.nama_pilihan = sa.unit
left join 
(select bpb.*,sum(qty) total_st from bpb 
where id_item = '$crinya' and bpbdate >= (select tgl1 from tptglperiode where gudang = 'general') group by unit) st on
mast_satuan.nama_pilihan = st.unit
left join
(select bppb.*,sum(qty) total_sot from bppb where id_item = '$crinya' and bppbdate >= (select tgl1 from tptglperiode where gudang = 'general') group by unit) sot on
mast_satuan.nama_pilihan = sot.unit
where coalesce(sa.qty,0) + coalesce(st.total_st,0) - coalesce(sot.qty,0) != '0'";
		  }
		  IsiCombo($sql,'','Pilih Satuan');
		}
	}
}
////// RMN
else if ($modenya=="view_list_bc23" OR $modenya=="view_list_bc23_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$bcno=substr($crinya,0,6);
		$bcdate=substr($crinya,7,10);
		$sql="select s.goods_code,s.itemdesc,a.qty,a.id_item,a.curr,a.price from bpb a inner join masteritem s 
			on a.id_item=s.id_item where bcno='$bcno' and bcdate='$bcdate' order by s.goods_code ";
		echo "<table style='width: 100%;' class='table table-fixed'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='col-xs-1'>No</th>";
					echo "<th class='col-xs-3'>Kode Barang</th>";
					echo "<th class='col-xs-4'>Nama Barang</th>";
					echo "<th class='col-xs-1'>Stock</th>";
					echo "<th class='col-xs-1'>Qty</th>";
					echo "<th class='col-xs-1'>Curr</th>";
					echo "<th class='col-xs-1'>Price</th>";
				echo "</tr>";
				echo "</thead>";
				$i=1;
				$query=mysql_query($sql);
				while($data=mysql_fetch_array($query))
				{	$id=$data['id_item'];
					$curr=$data['curr'];
					$price=$data['price'];
					$stock=flookup("stock","stock","mattype='A' and id_item='$id'");
					if ($stock>0)
					{	echo "<tr>";
							echo "<td class='col-xs-1'>$i</td>";
							echo "<td class='col-xs-3'>$data[goods_code]</td>";
							echo "<td class='col-xs-4'>$data[itemdesc]</td>";
							echo "<td class='col-xs-1'><input type ='text' size='4' name ='itemstock[$id]' value='$stock' id='stockajax$i' readonly></td>";
							echo "<td class='col-xs-1'><input type ='text' size='4' name ='item[$id]' id='itemajax' class='itemclass'></td>";
							echo "<td class='col-xs-1'><input type ='text' size='3' value='$curr' name ='curr[$id]' id='currajax' class='currclass'></td>";
							echo "<td class='col-xs-1'><input type ='text' size='4' value='$price' name ='price[$id]' id='currajax' class='priceclass'></td>";
						echo "</tr>";
						$i++;
					}
				};
		echo "</table>";
	}
}
else if ($modenya=="cari_list_bc23" OR $modenya=="cari_list_bc23_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$cridtnya=fd($crinya);
		$sql="select concat(bcno,'|',bcdate) isi,concat(bcno,'|',bcdate) tampil from bpb a inner join stock s 
			on a.id_item=s.id_item where bpbdate>='$cridtnya' 
			and stock>0 group by bcno,bcdate order by bcdate,bcno";
		IsiCombo($sql,'','Pilih BC23 No.');
	}
}
else if ($modenya=="cari_list_item" OR $modenya=="cari_list_item_bom" OR $modenya=="cari_list_item_wip" 
	OR $modenya=="cari_list_item_mesin" OR $modenya=="cari_list_item_scrap"
	OR $modenya=="cari_list_item_out" OR $modenya=="cari_list_item_out_bom" 
	OR $modenya=="cari_list_item_wip_out" OR $modenya=="cari_list_item_gen_out" OR $modenya=="cari_list_item_gen_out_new" 
	OR $modenya=="cari_list_item_mesin_out" OR $modenya=="cari_list_item_scrap_out")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	if ($modenya=="cari_list_item" OR $modenya=="cari_list_item_out" 
		OR $modenya=="cari_list_item_bom" OR $modenya=="cari_list_item_out_bom")
		{	$matnya = "'A','F','B'"; 
			$pronya = "Bahan Baku";
		}
		else if ($modenya=="cari_list_item_wip" OR $modenya=="cari_list_item_wip_out")
		{	$matnya = "'C'"; 
			$pronya = "WIP";
		}
		else if ($modenya=="cari_list_item_gen" OR $modenya=="cari_list_item_gen_out" OR $modenya=="cari_list_item_gen_out_new")
		{	$matnya = "'N'"; 
			$pronya = "General";
		}
		else if ($modenya=="cari_list_item_mesin" OR $modenya=="cari_list_item_mesin_out")
		{	$matnya = "'M'"; 
			$pronya = "Mesin";
		}
		else if ($modenya=="cari_list_item_scrap" OR $modenya=="cari_list_item_scrap_out")
		{	$matnya = "'S','L'"; 
			$pronya = "Scrap";
		}
		if ($modenya=="cari_list_item_out" OR $modenya=="cari_list_item_wip_out" 
			OR $modenya=="cari_list_item_mesin_out" OR $modenya=="cari_list_item_scrap_out")
		{ $cri_stock="stock>0"; }
		else
		{ $cri_stock=" (stock>=0 or stock is null) "; }
		if (($nm_company=="PT. Youngil Leather Indonesia" 
			OR $nm_company=="PT. Bangun Sarana Alloy"
			OR $nm_company=="PT. Tun Hong" OR $nm_company=="PT. Sinar Gaya Busana") 
			AND $modenya!="cari_list_item_bom" AND $modenya!="cari_list_item_out_bom")
    { $sql = "select id_item isi,concat(id_item,'|',goods_code,'|',itemdesc,'|',color,'|',size) tampil 
  			from masteritem where matclass='$crinya' 
  			and mattype in ($matnya) "; 
  	}
    else if ($st_company=="GB")
    { if ($nm_company=="PT. Hwaseung IBS Indonesia")
  		{ $kodenya = "concat(goods_code,'|',itemdesc,'|',goods_code2)"; }
  		else
  		{ $kodenya = "concat(goods_code,'|',itemdesc)"; }
  		$sql = "select id_item isi,$kodenya tampil 
  			from masteritem where itemdesc='$crinya' 
  			and mattype in ($matnya) "; 
  		
  	}
    else if ($nm_company=="PT. Gaya Indah Kharisma")
    { $sql = "select a.id_item isi,concat(goods_code,'|',itemdesc) tampil from masteritem a 
  			left join stock s on a.id_item=s.id_item and a.mattype=s.mattype where matclass='$crinya' 
  			and $cri_stock 
  			and a.mattype in ($matnya)"; 
  	}
		else if ($modenya=="cari_list_item_out_bom")
		{	$sql="select f.id_item isi,
				concat(d.goods_code,'|',itemdesc) tampil 
				from masterstyle a inner join bom s on a.id_item=s.id_item_fg
        inner join bpb f on s.id_item_fg=f.id_item_fg
        inner join masteritem d on d.id_item=f.id_item_bb
        and s.id_item_bb=f.id_item_bb
        where a.id_item='$crinya' group by f.id_item";
    }
		else if ($modenya=="cari_list_item_bom")
		{	$sql="select d.id_item isi,
				concat(d.goods_code,'|',itemdesc) tampil 
				from masterstyle a inner join bom s on a.id_item=s.id_item_fg
        inner join masteritem d on d.id_item=s.id_item_bb
        where a.id_item='$crinya' group by d.id_item";
    }
		else
    { if ($nm_company=="PT. Sandrafine Garment")
  		{ if ($modenya=="cari_list_item_wip_out" OR $modenya=="cari_list_item_wip")
  			{ $kodenya = "concat(itemdesc,'|',color,'|',size)"; }
  			else
  			{ $kodenya = "concat(itemdesc,'|',color,'|',size)"; }
  		}
  		else  			
  		{ $kodenya = "concat(id_item,' | ',goods_code,' | ',itemdesc,' | ',color,' | ',size)"; }
  		if ($nm_company=="PT. Sandrafine Garment") {$fldclass="stock_card";} else {$fldclass="matclass";}
  		if ($modenya=="cari_list_item_gen_out_new")
  {			
  		$sql = "select id_item isi,$kodenya tampil 
  			from masteritem where $fldclass='$crinya' 
  			and mattype in ($matnya) and n_code_category != '2'"; 
  }
  else
  {
  		$sql = "select id_item isi,$kodenya tampil 
  			from masteritem where $fldclass='$crinya' 
  			and mattype in ($matnya) and n_code_category = '2'"; 	
  }
  	}
		IsiCombo($sql,'','Pilih '.$pronya);
	}
}
else if ($modenya=="cari_list_item_fg" OR $modenya=="cari_list_item_fg_out")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	if ($nm_company=="PT. Tun Hong")
		{	$sqlwhere=" where kpno='$crinya'";	}
		else if ($nm_company=="PT. Sandrafine Garment")
		{	$sqlwhere=" where buyerno='$crinya'";	}
		else
		{	$sqlwhere=" where replace(itemname,'+','X')='$crinya'";	}

		if ($nm_company=="PT. Geumcheon Indo")
  	{ $sql = "select id_item isi,concat(buyerno) tampil from masterstyle $sqlwhere "; }
  	else if ($nm_company=="PT. Tun Hong")
  	{ $sql = "select id_item isi,concat(buyerno,'|',color,'|',size) tampil from masterstyle $sqlwhere "; }
  	else if ($nm_company=="PT. Youngil Leather Indonesia" OR 
  		$nm_company=="PT. Bangun Sarana Alloy")
		{ $sql = "select id_item isi,concat(goods_code,'|',color,'|',size,'|',kpno) tampil from masterstyle $sqlwhere "; }
		else if ($nm_company=="PT. Gaya Indah Kharisma")
  	{ if ($modenya=="cari_list_item_fg_out")
  		{$cri_stock="stock>0";}
  		else
  		{$cri_stock="stock>=0";}
  		$sql = "select a.id_item isi,concat(goods_code,'|',itemname) tampil from masterstyle a 
  			inner join stock s on a.id_item=s.id_item and 'FG'=s.mattype $sqlwhere 
  			and $cri_stock and a.id_so_det is null "; 
  	}
  	else if ($nm_company=="PT. Sandrafine Garment")
  	{ $sql = "select id_item isi,concat(goods_code,'|',itemname,'|',color,'|',size,'|',country,'|',qty) tampil from masterstyle $sqlwhere "; }
		else
  	{ $sql = "select id_item isi,concat(goods_code,'|',itemname) tampil from masterstyle $sqlwhere and id_so_det is null "; }
		IsiCombo($sql,'','Pilih Item');
	}
}
else if ($modenya=="cari_seri_lama")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql="delete from upload_standard where JENIS_DOKUMEN='ID_Lama'";
		mysql_query($sql);
		$sql = "insert into upload_standard (JENIS_DOKUMEN,BCNO) select 'ID_Lama',d.id_item 
			from bppb a inner join bpb s on a.id_item=s.id_item 
			inner join masteritem d on a.id_item=d.id_item where a.bcno='$crinya' 
			and s.bcno='-' and a.qty>0 
			group by a.id_item ";
		mysql_query($sql);
		$sql = "select concat(d.id_item,'|',a.bppbno,'|',a.qty) isi,concat(a.bcno,'|',a.bppbno,'|',d.goods_code,'|',d.itemdesc,'|',a.qty) tampil 
			from bppb a inner join masteritem d on a.id_item=d.id_item inner join upload_standard f 
			on a.id_item=f.BCNO where f.JENIS_DOKUMEN='ID_Lama'";
		IsiCombo($sql,'','Pilih Seri Barang Lama');
	}

}
else if ($modenya=="cari_seri_baru")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$good_code=flookup("left(goods_code,11)","masteritem","id_item='$crinya'");
		$sql = "select concat(d.id_item,'|',a.stock) isi,concat(d.goods_code,'|',d.itemdesc,'|',a.stock) tampil from stock a inner join masteritem d on 
			a.id_item=d.id_item where d.goods_code regexp '$good_code' and a.stock<>0 group by a.id_item ";
		IsiCombo($sql,'','Pilih Seri Barang Baru');
	}
}
else if ($modenya=="cari_tgl")
{	$crinya = $_REQUEST['cri_item'];
	$critransnya = $_REQUEST['cri_trans'];
	if ($crinya!="")
	{	if ($critransnya=="Pemasukan")
		{	$tglnya=flookup("bpbdate","bpb","bpbno='$crinya'"); }
		else
		{	$tglnya=flookup("bppbdate","bppb","bppbno='$crinya'"); }
		echo date('d M Y',strtotime($tglnya));
	}
}
else if ($modenya=="cari_aju")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql="delete from upload_standard where JENIS_DOKUMEN='Nomor_Aju'";
		mysql_query($sql);
		$sql="insert into upload_standard (JENIS_DOKUMEN,ITEMDESC,BCNO) 
			select 'Nomor_Aju',concat(right(nomor_aju,6),'|',lpad(bcno,6,'0'),'|',bpbno),nomor_aju 
			from bpb 
			where nomor_aju!='' and year(bpbdate)='$crinya' group by nomor_aju";
		mysql_query($sql);
		$sql="insert into upload_standard (JENIS_DOKUMEN,ITEMDESC,BCNO) 
			select 'Nomor_Aju',concat(right(nomor_aju,6),'|',lpad(bcno,6,'0'),'|',bppbno),nomor_aju 
			from bppb 
			where nomor_aju!='' and year(bppbdate)='$crinya' group by nomor_aju";
		mysql_query($sql);
		$sql = "select BCNO isi,ITEMDESC tampil 
			from upload_standard where JENIS_DOKUMEN='Nomor_Aju' group by BCNO";
		IsiCombo($sql,'','Pilih Nomor Aju');
	}
}
else if ($modenya=="cari_aju2")
{	$crinya = $_REQUEST['cri_item'];
	$critransnya = $_REQUEST['cri_trans'];
	if ($crinya!="")
	{	$sql="delete from upload_standard where JENIS_DOKUMEN='Nomor_Aju'";
		mysql_query($sql);
		if ($critransnya=="Pemasukan") 
		{ $tr="bpb"; $trdate="bpbdate"; $trno="bpbno"; } 
		else 
		{ $tr="bppb"; $trdate="bppbdate"; $trno="bppbno"; }
		$sql = "select $trno isi,concat($trno,'|',lpad(bcno,6,'0')) tampil 
			from $tr where year($trdate)='$crinya' group by $trno";
		IsiCombo($sql,'','Pilih Nomor Aju / Nomor Daftar');
	}
}
else if ($modenya=="cari_tahun")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	if ($crinya=="Pemasukan")
		{	$sql = "select year(bpbdate) isi,year(bpbdate) tampil from bpb 
     		group by year(bpbdate) order by year(bpbdate)";
    }
		else
		{	$sql = "select year(bppbdate) isi,year(bppbdate) tampil from bppb 
     		group by year(bppbdate) order by year(bppbdate)";
    }
		IsiCombo($sql,'','Pilih Tahun');
	}
}
else if ($modenya=="cari_item")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya=="Barang Jadi")
	{	$sql = "select id_item isi,concat(goods_code,'|',itemname) tampil 
			from masterstyle";
		IsiCombo($sql,'','Pilih Barang Jadi');
	}
	else if ($crinya=="Non Barang Jadi")
	{	$sql = "select id_item isi,concat(goods_code,'|',itemdesc) tampil 
			from masteritem";
		IsiCombo($sql,'','Pilih Barang');
	}
}
else if ($modenya=="cari_tujuan")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	$sql = "select nama_pilihan isi,nama_pilihan tampil 
			from masterpilihan where kode_pilihan='$crinya'";
		IsiCombo($sql,'','Pilih Tujuan');
	}
}
else if ($modenya=="cari_satuan_bpb" OR $modenya=="cari_satuan_bpb_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($crinya!="")
	{	if ($modenya=="cari_satuan_bpb_fg")
		{ $matnya="and left(bpbno,2)='FG'"; }
		else
		{ $matnya="and left(bpbno,2)!='FG'"; }
		$cek=flookup("count(*)","bpb","id_item='$crinya' $matnya ");
		if ($cek>0)
		{	$sql = "select unit isi,unit tampil 
				from bpb where id_item='$crinya' $matnya group by unit";
		}
		else
		{	$sql = "select nama_pilihan isi,nama_pilihan tampil 
				from masterpilihan where kode_pilihan='Satuan' order by nama_pilihan";
		}
		IsiCombo($sql,'','Pilih Satuan');
	}
}
else if ($modenya=="cari_sisa" OR $modenya=="cari_sisa_fg" 
	OR $modenya=="cari_sisa_bpb" OR $modenya=="cari_sisa_fg_bpb"
	OR $modenya=="cari_sisa_bpb_new" OR $modenya=="cari_sisa_fg_bpb_new")
{	$crinya = $_REQUEST['cri_item'];
	$cridatenya = fd($_REQUEST['cri_date']);
	$cribppbenya = $_REQUEST['cri_bppbno'];
	if ($modenya=="cari_sisa_fg")
	{	$sisa = get_stock_tgl("BKB","FG",$crinya,$cridatenya,$cribppbenya);	}
	else if ($modenya=="cari_sisa_fg_bpb")
	{	$sisa = get_stock_tgl("BPB","FG",$crinya,$cridatenya,$cribppbenya);	}
	else if ($modenya=="cari_sisa_fg_bpb_new")
	{	$sisa = flookup("stock","stock","mattype='FG' AND id_item='$crinya'");	}
	else if ($modenya=="cari_sisa_bpb")
	{	$matnya=flookup("mattype","masteritem","id_item='$crinya'");
		$sisa = get_stock_tgl("BPB",$matnya,$crinya,$cridatenya,$cribppbenya);	
	}
	else if ($modenya=="cari_sisa_bpb_new")
	{	$matnya=flookup("mattype","masteritem","id_item='$crinya'");
		$sisa = flookup("stock","stock","mattype='$matnya' AND id_item='$crinya'");	
	}
	else
	{	$matnya=flookup("mattype","masteritem","id_item='$crinya'");
		#$sisa = get_stock_tgl("BKB",$matnya,$crinya,$cridatenya,$cribppbenya);
		calc_stock($matnya,$crinya);
		$sisa = flookup("stock","stock","mattype='$matnya' and id_item='$crinya'");	
	}
	if ($sisa=="") { $sisa=0; }
	print($sisa);	
}
// RMN
else if ($modenya=="cari_sisa_gen_new")
{	$crinya = $_REQUEST['cri_item'];
	$sql = "select mi.id_item,mi.goods_code,(coalesce(sa.sa_total,0) + coalesce (st.st_total,0) - coalesce(sot.sot_total,0)) total_qty from
(select * from masteritem mi where mattype = 'N') mi
left join
(select sa.*,sum(qty) sa_total from saldoawal_gd sa
inner join masteritem mi on sa.kd_barang = mi.goods_code
where id_item = '$crinya' and sa.periode = (select tgl1 from tptglperiode where gudang = 'general')) sa on
mi.goods_code = sa.kd_barang
left join 
(select bpb.*, sum(qty) st_total from bpb 
where id_item = '$crinya' and bpbdate >= (select tgl1 from tptglperiode where gudang = 'general') group by id_item) st on
mi.id_item = st.id_item
left join 
(select bppb.*, sum(bppb.qty) sot_total from bppb 
where id_item = '$crinya' and bppbdate >= (select tgl1 from tptglperiode where gudang = 'general') group by id_item) sot on
mi.id_item = sot.id_item
where mi.id_item = '$crinya'";
  $headerQry = mysql_query($sql)  or die ("Query pembelian salah : ".mysql_error());
  $headerRow = mysql_fetch_array($headerQry); 
  $sisa = $headerRow['total_qty'];
  print($sisa);
	// if ($sisa=="") { $sisa=0; }
	// print($sisa);	
}
// RMN
else if ($modenya=="cari_sisa_nett" OR $modenya=="cari_sisa_nett_fg")
{	$crinya = $_REQUEST['cri_item'];
	if ($modenya=="cari_sisa_nett_fg")
	{	$sisa = flookup("stock_nett","stock","id_item='$crinya' and mattype='FG'");	}
	else
	{	$sisa = flookup("stock_nett","stock","id_item='$crinya' and mattype!='FG'");	}
	if ($sisa=="") { $sisa=0; }
	print($sisa);	
}
else if ($modenya=="cari_sisa_fg")
{	$crinya = $_REQUEST['cri_item'];
	$sisa = flookup("stock","stock","id_item='$crinya' and mattype='FG'");
	print($sisa);	
}

?>
