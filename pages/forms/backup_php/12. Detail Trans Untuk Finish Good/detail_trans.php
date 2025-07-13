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
					echo "<th>Tgl. Trans</th>";
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
						echo "<th>Inv/SJ #</th>";
					}
					echo "<th>Kode Barang</th>";
					echo "<th>Nama Barang</th>";
					echo "<th>Warna</th>";
					echo "<th>Ukuran</th>";
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
					echo "<th>WS #</th>";
					echo "<th>Style #</th>";
					echo "<th>Curr</th>";
					echo "<th>Price</th>";
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
					$where=" left(bpbno,1) in ('S','L') ";
					$whereout=" mid(bppbno,4,1) in ('S','L') ";
				}
				else if ($tipenya=="Item General")
				{ $tbl="masteritem"; $fld="itemdesc"; 
					$where=" left(bpbno,1) in ('N') ";
					$whereout=" mid(bppbno,4,1) in ('N') "; 
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
					{$que_cl=" and matclass='$classnya'";}
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
					} 
					else 
					{
						$fldsty="a.styleno"; $fldsc="'' buyerno,stock_card,'' whs_code";
						$joinjo = "left join (select id_jo,id_so from jo_det group by id_so) tmpjod on tmpjod.id_jo=a.id_jo";
					}
    			tampil_data("select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
						lpad(bcno,6,'0') bcno,bcdate,supplier,goods_code,$fld itemdesc,s.color,s.size,
						a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
						a.unit,berat_bersih,remark,$flduser,ac.kpno ws,ac.styleno,a.curr,a.price 
						from bppb a inner join $tbl s on a.id_item=s.id_item
						inner join mastersupplier d on a.id_supplier=d.id_supplier 
						$joinjo  
						left join so on tmpjod.id_so=so.id 
						left join act_costing ac on so.id_cost=ac.id 
						where $whereout and bppbdate between '$from' and '$to' 
						$que_cl $que_supp order by bppbdate",24);
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
						tampil_data("SELECT if(a.bpbno_int!='',a.bpbno_int,a.bpbno) bpbno,a.bpbdate,a.invno,a.jenis_dok,right(a.nomor_aju,6),a.tanggal_aju,
							lpad(a.bcno,6,'0') bcno,a.bcdate,d.supplier,a.pono,a.invno,s.goods_code,CONCAT(s.itemdesc,' ',s.add_info) itemdesc,s.color,s.size,
							a.qty,a.unit,a.berat_bersih,a.remark,username,stock_card,'' whs_code,a.curr,a.price 
							from bpb a inner join masteritem s on a.id_item=s.id_item
							LEFT join mastersupplier d on a.id_supplier=d.id_supplier 
							where  bpbno_int LIKE '%GEN%' $que_supp AND a.bpbdate >= '$from'
							AND a.bpbdate <= '$to'",29);						
					}
					else
					{
						if ($tbl=="masterstyle") {$fldsty="s.styleno"; $fldsc="buyerno,'' stock_card,whs_code";} else {$fldsty="tmpjo.styleno"; $fldsc="'' buyerno,stock_card,'' whs_code";}
						tampil_data("select if(bpbno_int!='',bpbno_int,bpbno) bpbno,bpbdate,invno,jenis_dok,right(nomor_aju,6),tanggal_aju,
							lpad(bcno,6,'0') bcno,bcdate,supplier,pono,invno,goods_code,$fld itemdesc,s.color,s.size,
							a.qty,(a.qty-coalesce(a.qty_reject,0)) as qty_good,coalesce(a.qty_reject,0) as qty_reject,
							a.unit,berat_bersih,remark,$flduser,tmpjo.kpno ws,tmpjo.styleno,a.curr,a.price 
							from bpb a inner join $tbl s on a.id_item=s.id_item
							inner join mastersupplier d on a.id_supplier=d.id_supplier 
							left join 
							(select id_jo,kpno,styleno from act_costing ac inner join so on ac.id=so.id_cost inner join 
								jo_det jod on so.id=jod.id_so group by id_jo) tmpjo 
							on tmpjo.id_jo=a.id_jo   
							where $where and bpbdate between '$from' and '$to' 
							$que_cl $que_supp order by bpbdate",26);
					}
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
echo "</div>";
?>