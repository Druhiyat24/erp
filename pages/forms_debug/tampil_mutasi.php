<?php
if (isset($_GET['parfromv']))
{	$toexcel="Y";
	$rpt="mut";
	header("Content-type: application/octet-stream"); 
	header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
	header("Pragma: no-cache"); 
	header("Expires: 0");
}
else
{	$toexcel = "N"; }

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company = $rscomp["company"];
	$st_company = $rscomp["status_company"];
	$logo_company = $rscomp["logo_company"];
// session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
if (isset($_GET['rptid'])) { $rpt = $_GET['rptid']; } else { $rpt = ""; }

if ($nm_company=="PT. Seyang Indonesia")
{$mattypenya = "";}
else
{$mattypenya = "";}

if ($st_company=="KITE" AND $rpt=='bahanbaku') {$header_cap="F. LAPORAN MUTASI BAHAN BAKU";} 
elseif ($rpt=='bahanbaku') {$header_cap="D. LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU DAN BAHAN PENOLONG";}
elseif ($rpt=='mwipdet' OR $rpt=='mwiptot') {$header_cap="C. LAPORAN POSISI BARANG DALAM PROSES (WIP)";}
elseif ($rpt=='hasil') {$header_cap="INPUT HASIL STOCKOPNAME BAHAN BAKU";}
elseif ($rpt=='hasilsl') {$header_cap="INPUT HASIL STOCKOPNAME LIMBAH / SCRAP";}
elseif ($rpt=='hasilwip') {$header_cap="INPUT HASIL STOCKOPNAME BARANG DALAM PROSES";}
elseif ($rpt=='hasilmes') {$header_cap="INPUT HASIL STOCKOPNAME MESIN";}
elseif ($rpt=='hasilfg') {$header_cap="INPUT HASIL STOCKOPNAME FG";}
elseif ($rpt=='bahanbakupo' OR $rpt=='bahanbakupoitem') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BAHAN BAKU";} 
elseif ($rpt=='gb_bahanbaku') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG";} 
elseif ($st_company=="KITE" AND $rpt=='barangjadi') {$header_cap="G. LAPORAN MUTASI HASIL PRODUKSI";}
elseif ($st_company!="KITE" AND $rpt=='barangjadi') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG JADI";}
elseif ($rpt=='barangsisa') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG SISA DAN SCRAP";}
elseif ($rpt=='mesin') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI MESIN DAN PERALATAN PERKANTORAN";}

if (isset($_GET['parfromv']))
{	$tglf = $_GET['parfrom'];
	$perf = date('d F Y', strtotime($tglf));
	$tglt = $_GET['parto'];
	$pert = date('d F Y', strtotime($tglt));
}
else
{	$tglf = fd($_POST['txtfrom']);
	$perf = date('d F Y', strtotime($tglf));
	$tglt = fd($_POST['txtto']);
	$pert = date('d F Y', strtotime($tglt));
}
if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }
$sql="X".$header_cap."-".$rpt." Dari ".$perf." s/d ".$pert;
//insert_log($sql,$user);
?>

<html>
<head>
<title><?PHP echo $header_cap;?></title>
</head>
<body>
	<?PHP
		if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
		{	echo "<form method='post' name='form' action='save_hasil_opname.php?tgl=$tglt&mode=$rpt'>"; }
		if ($rpt=='gb_bahanbaku')
		{ echo "GUDANG BERIKAT "; echo strtoupper($nm_company); }
		elseif ($st_company=="KITE")
		{ echo $header_cap; echo "<br>"; echo strtoupper($nm_company); }
		elseif ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg")
		{ echo "KAWASAN BERIKAT "; echo strtoupper($nm_company); }
		if ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg") { echo "<br>"; echo $header_cap; }
		if ($rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg") 
		{ echo "<br>";
			echo "PERIODE "; echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
			echo "<br>";
		}
		else
		{ if ($rpt=="hasil")
			{	echo "INPUT HASIL STOCK OPNAME BAHAN BAKU DAN PENOLONG PERIODE "; }
			else if ($rpt=="hasilsl")
			{	echo "INPUT HASIL STOCK OPNAME SCRAP / LIMBAH PERIODE "; }
			else if ($rpt=="hasilwip")
			{	echo "INPUT HASIL STOCK OPNAME BARANG DALAM PROSES PERIODE "; }
			else if ($rpt=="hasilmes")
			{	echo "INPUT HASIL STOCK OPNAME MESIN PERIODE "; }
			else if ($rpt=="hasilfg")
			{	echo "INPUT HASIL STOCK OPNAME BARANG JADI PERIODE "; }
			echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
		}
	?>
<?PHP 
	if ($toexcel!="Y" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg")
	{	echo "<a class='btn btn-info btn-sm' href='?mod=view_mut&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>";
		echo "<br>";
		echo "-";
	}
	$vNama_View = Array("vmut_in_before", "vmut_out_before", "vmut_ri_before", "vmut_ro_before");
  $vNama_Tbl = Array("bpb", "bppb", "hreturn", "hreturn");
  $vNama_Alias = Array("QtyBPB", "QtyBPPB", "QtyRI", "QtyRO");
  $vWherey = Array("a.bpbdate", "a.bppbdate", "a.returndate", "a.returndate");
  if ($rpt=='bahanbaku' or $rpt=='bahanbakupo' or $rpt=='bahanbakupoitem' or $rpt=='gb_bahanbaku')
	{	$tbl_master = "masteritem";
		if ($nm_company=="PT. Youngil Leather Indonesia")
		{ $cri_mat = "where a.mattype not in ('M','S','L')"; } # Type C tetap dimunculkan
		else
		{ $cri_mat = "where a.mattype not in ('M','S','L','C','N') $f_class "; }
		$fld_mat = "z.mattype";
		if ($nm_company=="PT. Jinwoo Engineering Indonesia")
		{	$fld_item = "if (a.matclass='' OR a.matclass='-',a.itemdesc,concat(a.matclass,' (',a.itemdesc,')'))"; }
		else if ($nm_company=="PT. Hwaseung IBS Indonesia")
		{	$fld_item = "if (a.goods_code2='',a.itemdesc,concat(a.itemdesc,' (',a.goods_code2,')'))"; }
		else if ($nm_company=="PT. Bangun Sarana Alloy")
		{	$fld_item = "concat(a.itemdesc,' ',a.color,' ',a.size)"; }
		else
		{	$fld_item = "a.itemdesc"; }
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) not in ('M','S','L')", 
				"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) not in ('M','S','L')",
				"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) not in ('RI-M','RI-S','RI-L')", 
				"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) not in ('RO-M','RO-S','RO-L')");
	}
	elseif ($rpt=='barangjadi')
	{	$tbl_master = "masterstyle";
		$cri_mat = "";
		$fld_mat = "'FG'";
		if ($nm_company=="PT. Bangun Sarana Alloy")
		{	$fld_item = "a.size";	}
		else if ($nm_company=="PT. Bintang Mandiri Hanafindo")
		{	$fld_item = "concat(a.kpno,' ',a.styleno,' ',a.itemname)";	}
		else
		{	$fld_item = "a.itemname";	}
		$vWherex = Array("left(a.bpbno,2)='FG'", "mid(a.bppbno,4,2)='FG'", "left(a.returnno,5)='RI-FG'", "left(a.returnno,5)='RO-FG'");
	}
	elseif ($rpt=='barangsisa')
	{	$tbl_master = "masteritem";
		$cri_mat = "where a.mattype in ('S','L')";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ('S','L')", 
		"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ('S','L')",
		"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-S','RI-L')", 
		"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-S','RO-L')");
	}
	elseif ($rpt=='mesin')
	{	$tbl_master = "masteritem";
		$cri_mat = "where (a.mattype in ('M') or a.tipe_mut='Mesin')";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array(
			"left(a.bpbno,2)<>'FG' and (left(a.bpbno,1) in ('M') or s.tipe_mut='Mesin')",
			"mid(a.bppbno,4,2)<>'FG' and (mid(a.bppbno,4,1) in ('M') or s.tipe_mut='Mesin')",
			"left(a.returnno,5)<>'RI-FG' and (left(a.returnno,4) in ('RI-M') or s.tipe_mut='Mesin')",
			"left(a.returnno,5)<>'RO-FG' and (left(a.returnno,4) in ('RO-M') or s.tipe_mut='Mesin')");
	}
  elseif ($rpt=='mwiptot' OR $rpt=='mwipdet')
	{	$tbl_master = "masteritem";
		$cri_mat = "where a.mattype in ('C')";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ('C')",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ('C')",
			"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-C')",
			"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-C')");
	}
	elseif ($rpt=='hasil')
	{	$tbl_master = "masteritem";
		$cekmutwip=flookup("username","userpassword","mutasi_wip='1' limit 1");
		$strmatnya = "'A','F','P','B'";
		$cri_mat = "where (a.mattype in ($strmatnya) or it_inv='Y') ";
		$fld_mat = "z.mattype";
		if ($nm_company=="PT. Hwaseung IBS Indonesia")
		{ $fld_item = "if (a.goods_code2='',a.itemdesc,concat(a.itemdesc,' (',a.goods_code2,')'))"; } 
		else
		{ $fld_item = "a.itemdesc"; }
			$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ($strmatnya)",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ($strmatnya) ",
			"left(a.returnno,5)<>'RI-FG' and mid(a.returnno,4,1) in ($strmatnya)",
			"left(a.returnno,5)<>'RO-FG' and mid(a.returnno,4,1) in ($strmatnya)");
	}
	elseif ($rpt=='hasilsl')
	{	$tbl_master = "masteritem";
		$strmatnya = "'S','L'";
		$cri_mat = "where a.mattype in ($strmatnya)";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ($strmatnya)",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ($strmatnya)",
			"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-C')",
			"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-C')");
	}
	elseif ($rpt=='hasilwip')
	{	$tbl_master = "masteritem";
		$strmatnya = "'C'";
		$cri_mat = "where a.mattype in ($strmatnya)";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ($strmatnya)",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ($strmatnya)",
			"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-C')",
			"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-C')");
	}
	elseif ($rpt=='hasilmes')
	{	$tbl_master = "masteritem";
		$strmatnya = "'M'";
		$cri_mat = "where a.mattype in ($strmatnya)";
		$fld_mat = "z.mattype";
		$fld_item = "a.itemdesc";
		$vWherex = Array("left(a.bpbno,2)<>'FG' and left(a.bpbno,1) in ($strmatnya)",
			"mid(a.bppbno,4,2)<>'FG' and mid(a.bppbno,4,1) in ($strmatnya)",
			"left(a.returnno,5)<>'RI-FG' and left(a.returnno,4) in ('RI-C')",
			"left(a.returnno,5)<>'RO-FG' and left(a.returnno,4) in ('RO-C')");
	}
	elseif ($rpt=='hasilfg')
	{	$tbl_master = "masterstyle";
		$strmatnya = "'FG'";
		$cri_mat = "";
		$fld_mat = "'FG'";
		$fld_item = "a.itemname";
		$vWherex = Array("left(a.bpbno,2)='FG' ",
			"mid(a.bppbno,4,2)='FG' ",
			"left(a.returnno,5)='RI-FG' ",
			"left(a.returnno,5)='RO-FG' ");
	}

  For($aa=0;$aa<=3;$aa++)
  {	$nama_view = $vNama_View[$aa];
		if ($nama_view=='vmut_ri_before') 
		{ $sql2 = " and returnno like 'RI%'"; }
	  elseif ($nama_view=='vmut_ro_before') 
		{ $sql2 = " and returnno like 'RO%'"; }
		else
	  { $sql2 = " "; }
    
    mysql_query ("drop table if exists $vNama_View[$aa]");
    if ($rpt=='bahanbakupo' or $rpt=='bahanbakupoitem')
    { if ($nama_view=='vmut_ri_before' OR $nama_view=='vmut_ro_before')
 	  	{ $vfldpo="a.returnno";}
	 	  else if ($nama_view=='vmut_out_before')
	 	  { $vfldpo="a.kpno";}
	 	  else
	 	  { $vfldpo="a.pono";}
	   	if ($rpt=="bahanbakupoitem")
	   	{ mysql_query ("create table $vNama_View[$aa] select 'FG',$vfldpo pono,a.id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
					$tbl_master s on a.id_item=s.id_item where a.qty>0 
					and $vWherex[$aa] and $vWherey[$aa] <'$tglf' $sql2 group by $vfldpo,a.id_item" );
		  }
	   	else
	   	{ mysql_query ("create table $vNama_View[$aa] select 'FG',$vfldpo id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
					$tbl_master s on a.id_item=s.id_item where a.qty>0 
					and $vWherex[$aa] and $vWherey[$aa] <'$tglf' $sql2 group by $vfldpo" );
		  }
	   	mysql_query ("alter table $vNama_View[$aa] add index id_idx(id_item)");
		}
		else
	  { mysql_query ("create table $vNama_View[$aa] select 'FG',a.id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
				$tbl_master s on a.id_item=s.id_item where a.qty>0 
				and $vWherex[$aa] and $vWherey[$aa] <'$tglf' $sql2 group by s.id_item" );
		  mysql_query ("alter table $vNama_View[$aa] add index id_idx(id_item)");
		}
  }
	
	$vNama_View = Array("vmut_in_curr", "vmut_out_curr", "vmut_ri_curr", "vmut_ro_curr");
  For($aa=0;$aa<=3;$aa++)
  {	$nama_view = $vNama_View[$aa];
		if ($nama_view=='vmut_ri_curr') 
		{ $sql2 = " and returnno like 'RI%'"; }
    elseif ($nama_view=='vmut_ro_curr') 
		{ $sql2 = " and returnno like 'RO%'"; }
		else
    { $sql2 = " "; }
    
    mysql_query ("drop table if exists $vNama_View[$aa]");
    if ($rpt=="bahanbakupo" or $rpt=="bahanbakupoitem")
    { if ($nama_view=='vmut_ri_curr' OR $nama_view=='vmut_ro_curr')
	 	  { $vfldpo="a.returnno";}
	 	  else if ($nama_view=='vmut_out_curr')
	 	  { $vfldpo="a.kpno";}
	 	  else
	 	  { $vfldpo="a.pono";}
	 	  if ($rpt=="bahanbakupoitem")
	 	  { mysql_query ("create table $vNama_View[$aa] select 'FG',$vfldpo pono,a.id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
					$tbl_master s on a.id_item=s.id_item where a.qty>0 $sql2 and $vWherex[$aa] and $vWherey[$aa]>='$tglf' and $vWherey[$aa]<='$tglt' 
					group by $vfldpo,a.id_item");
	  	}
	 	  else
	 	  { mysql_query ("create table $vNama_View[$aa] select 'FG',$vfldpo id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
					$tbl_master s on a.id_item=s.id_item where a.qty>0 $sql2 and $vWherex[$aa] and $vWherey[$aa]>='$tglf' and $vWherey[$aa]<='$tglt' 
					group by $vfldpo");
	  	}  
	    mysql_query ("alter table $vNama_View[$aa] add index id_idx(id_item)");
		}
		else
    { mysql_query ("create table $vNama_View[$aa] select 'FG',a.id_item,sum(a.qty) $vNama_Alias[$aa],a.unit from $vNama_Tbl[$aa] a inner join 
				$tbl_master s on a.id_item=s.id_item where a.qty>0 $sql2 and $vWherex[$aa] and $vWherey[$aa]>='$tglf' and $vWherey[$aa]<='$tglt' 
				group by s.id_item");
		  mysql_query ("alter table $vNama_View[$aa] add index id_idx(id_item)");
		}
  }
	mysql_query ("drop table if exists vmut_gab_before");
	if ($rpt=="bahanbakupo" or $rpt=="bahanbakupoitem")
	{ if ($rpt=="bahanbakupoitem")
 	  { $fromnya = "from vmut_in_before s left join vmut_out_before d on s.pono=d.pono and s.id_item=d.id_item 
				left join vmut_ri_before f on s.pono=f.pono and s.id_item=f.id_item left join vmut_ro_before g on s.pono=g.pono and s.id_item=g.id_item";
	    $fldidnya = "s.pono,s.id_item";
	  }
 	  else
 	  { $fromnya = "from vmut_in_before s left join vmut_out_before d on s.id_item=d.id_item 
		left join vmut_ri_before f on s.id_item=f.id_item left join vmut_ro_before g on s.id_item=g.id_item";
	    $fldidnya = "s.id_item";
	  }
	  $fld_mat = "''";
	}
	else
	{ $fromnya = "from $tbl_master z left join vmut_in_before s on z.id_item=s.id_item left join vmut_out_before d on z.id_item=d.id_item left join 
		vmut_ri_before f on z.id_item=f.id_item left join vmut_ro_before g on z.id_item=g.id_item";
	  $fldidnya = "z.id_item";
	}
	$sql = "create table vmut_gab_before select $fld_mat vMat,$fldidnya,
		if(isnull(s.QtyBPB),0,s.QtyBPB) QtyBPB_Before,
		if(isnull(d.QtyBPPB),0,d.QtyBPPB) QtyBPPB_Before,
		if(isnull(f.QtyRI),0,f.QtyRI) QtyRI_Before,
		if(isnull(g.QtyRO),0,g.QtyRO) QtyRO_Before,
		(if(isnull(s.QtyBPB),0,s.QtyBPB)+if(isnull(f.QtyRI),0,f.QtyRI))  - (if(isnull(d.QtyBPPB),0,d.QtyBPPB)+if(isnull(g.QtyRO),0,g.QtyRO)) Saldo_Akhir,
		s.unit $fromnya  group by $fldidnya";
	mysql_query ($sql);
	mysql_query ("drop table if exists vmut_gab_curr");
	if ($rpt=="bahanbakupo" or $rpt=="bahanbakupoitem")
	{ if ($rpt=="bahanbakupoitem")
	  { $fromnya = "from vmut_in_curr s left join vmut_out_curr d on s.pono=d.pono and s.id_item=d.id_item 
		left join vmut_ri_curr f on s.pono=f.pono and s.id_item=f.id_item left join vmut_ro_curr g on s.pono=g.pono and s.id_item=g.id_item";
	    $fldidnya = "s.pono,s.id_item";
	  }
	  else
	  { $fromnya = "from vmut_in_curr s left join vmut_out_curr d on s.id_item=d.id_item 
		left join vmut_ri_curr f on s.id_item=f.id_item left join vmut_ro_curr g on s.id_item=g.id_item";
	    $fldidnya = "s.id_item";
	  }
	  $fld_mat = "''";
	}
	else
	{ $fromnya = "from $tbl_master z left join vmut_in_curr s on z.id_item=s.id_item left join vmut_out_curr d on z.id_item=d.id_item 
		left join vmut_ri_curr f on z.id_item=f.id_item left join vmut_ro_curr g on z.id_item=g.id_item";
	  $fldidnya = "z.id_item";
	}
	$sql = "create table vmut_gab_curr select $fld_mat vMat,$fldidnya,
		if(isnull(s.QtyBPB),0,s.QtyBPB) QtyBPB_curr,
		if(isnull(d.QtyBPPB),0,d.QtyBPPB) QtyBPPB_curr,
		if(isnull(f.QtyRI),0,f.QtyRI) QtyRI_curr,
		if(isnull(g.QtyRO),0,g.QtyRO) QtyRO_curr,
		(if(isnull(s.QtyBPB),0,s.QtyBPB)+if(isnull(f.QtyRI),0,f.QtyRI))  - (if(isnull(d.QtyBPPB),0,d.QtyBPPB)+if(isnull(g.QtyRO),0,g.QtyRO)) Saldo_Akhir,
		if(isnull(s.unit),d.unit,s.unit) unit $fromnya  group by $fldidnya ";
    mysql_query ($sql);
	mysql_query ("delete from vmut_gab_curr where qtybpb_curr=0 and qtyri_curr=0 and qtybppb_curr=0 and qtyro_curr=0");
	mysql_query ("delete from vmut_gab_before where (qtybpb_before+qtyri_before)-(qtybppb_before+qtyro_before)=0");
	$cek=flookup("goods_code_only","mastercompany","goods_code_only='Y'");
	if ($nm_company=="PT. Bangun Sarana Alloy" AND $rpt=="bahanbaku") 
	{	$grnya = "a.id_item"; } 
	else if ($nm_company=="PT. Bangun Sarana Alloy" AND $rpt=='barangjadi') 
	{	$grnya = "a.size"; } 
	else if ($nm_company=="PT. Jinwoo Engineering Indonesia" AND $rpt=='barangjadi') 
	{	$grnya = "a.goods_code"; } 
	else if ($logo_company=="S" AND $rpt=='barangjadi') 
	{	$grnya = "a.goods_code,a.itemname"; } 
	else if ($cek=="Y") 
	{	$grnya = "a.goods_code";	}
	else 
	{	$grnya = "a.id_item";	}

	if ($nm_company=="PT. Seyang Indonesia" AND $rpt!='barangjadi') 
	{	$kdnya = "if(goods_code<>'',goods_code,concat(a.mattype,' ',a.id_item))";	} 
	else if ($nm_company=="PT. Sinar Gaya Busana" AND $rpt!='barangjadi') 
	{	$kdnya = " goods_code ";	} 
	else if ($nm_company=="PT. Bangun Sarana Alloy" AND $rpt=='barangjadi') 
	{	$kdnya = "if(a.itemname like 'WIRE%',itemname,styleno)";	} 
	else
	{	if ($rpt=='barangjadi' OR $rpt=="hasilfg")
		{ $kdnya = "if(a.goods_code<>'' AND a.goods_code<>'-' AND a.goods_code<>'0'
				,a.goods_code,concat('FG ',a.id_item))"; 
		}
		else 
		{ $kdnya = "if(a.goods_code<>'' AND a.goods_code<>'-' AND a.goods_code<>'0'
				,a.goods_code,concat(a.mattype,' ',a.id_item))"; 
		}
	}
	mysql_query ("alter table vmut_gab_curr add index id_idx(id_item)");
	mysql_query ("alter table vmut_gab_before add index id_idx(id_item)");
	if ($rpt=="bahanbakupo")
	{ mysql_query ("drop table if exists master_po");
	  mysql_query ("CREATE TABLE master_po (id_item varchar(50) NULL, mattype varchar(1) NULL)");
	  mysql_query ("insert into master_po select a.id_item,'A' from vmut_gab_before a left join master_po s on a.id_item=s.id_item where s.id_item is null group by a.id_item");
	  mysql_query ("insert into master_po select a.id_item,'A' from vmut_gab_curr a left join master_po s on a.id_item=s.id_item where s.id_item is null group by a.id_item");
	  $tbl_master = "master_po";
	  $kdnya = "a.id_item";
	  $fld_item = "''";
	}
	else if ($rpt=="bahanbakupoitem")
	{ $kdnya = "d.pono";
	  $grnya = "d.pono,a.id_item";
	}
	if ($nm_company=="PT. Gaya Indah Kharisma" OR $nm_company=="PT. Sinar Gaya Busana")
	{ $ordernya="a.goods_code"; }
	else
	{ $ordernya=$grnya; }
	
	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<table id='examplefix3' class='table table-bordered table-striped' style='font-size:11px;'>"; }
	else
	{	echo "<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>"; }
	?>
	<thead>
	<tr>
		<th>NO.</th>
		<?php
		if ($rpt=="bahanbakupo")
		{ echo "<th>NO. PO</th>";
		  echo "<th>&nbsp </th>";
		}
		else if ($rpt=="barangjadi" AND $nm_company=="PT. Bangun Sarana Alloy")
		{ echo "<th>NO. ORDER</th>"; 
			echo "<th>KODE BARANG</th>";
			echo "<th>SIZE</th>";
			echo "<th>NAMA BARANG</th>";
			echo "<th>WARNA</th>";
		}
		else if ($rpt=="bahanbakupoitem")
		{ echo "<th>NO. PO</th>";
		  echo "<th>JENIS BENANG</th>";
		}
		else
		{ if ($rpt=="hasil")
			{	echo "<th>KLASIFIKASI</th>"; }
			echo "<th>KODE BARANG</th>";
			echo "<th>NAMA BARANG </th>";
		}
		?>
		<th>SAT</th>
		<?php 
		if ($rpt!="mwiptot")
		{	echo "
				<th>SALDO AWAL</th>
				<th>PEMASUKAN</th>
				<th>PENGELUARAN</th>
				<th>PENYESUAIAN</th>
				<th>SALDO AKHIR</th>
				<th>STOCK OPNAME</th>
				<th>SELISIH</th>
				<th>KETERANGAN</th>";
		}
		else
		{	echo "
				<th>JUMLAH</th>
				<th>KETERANGAN</th>";
		}
		?>
	</tr>
	</thead>
	<tbody>
	<?php
	if ($tbl_master=="masterstyle") 
	{ $sql_add=",kpno,itemname,color"; }
	else if ($tbl_master=="masteritem") 
	{ $sql_add=",matclass"; } 
	else 
	{ $sql_add=""; }
	$sqlk = "select  $kdnya kode_brg,$fld_item nama_brg,
		sum(ifnull(s.saldo_akhir,0)) saldo_awal,
		sum(ifnull((d.qtybpb_curr+d.qtyri_curr),0)) qtyrcv,
		sum(ifnull((d.qtybppb_curr+d.qtyro_curr),0)) qtyout,
		if(max(d.unit)='' or isnull(max(d.unit)),max(s.unit),max(d.unit)) unit,
		a.id_item $sql_add 
		from $tbl_master a left join vmut_gab_curr d on a.id_item=d.id_item 
		left join vmut_gab_before s on a.id_item=s.id_item $cri_mat group by $grnya order by $ordernya";
	$sql = mysql_query ($sqlk);
	$tdata=mysql_num_rows($sql);
	echo $sqlk;
	#CETAK
	$no = 1; #nomor awal
	while($data = mysql_fetch_array($sql))
	{ #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
		if ($tbl_master=="masteritem")
		{ $matclass=$data['matclass']; }
		else
		{ $matclass=""; }
		$id_itemnya=$data['id_item'];
		$kode_barang=$data['kode_brg'];
		$id=$data['id_item'];
		$i=$no;
		$nama_barang=$data['nama_brg'];
		$sat=$data['unit'];
		$sawal=round($data['saldo_awal'],2);
		$tot_ter=round($data['qtyrcv'],2);
		$tot_kel=round($data['qtyout'],2);
		$filter=$sawal+$tot_ter+$tot_kel;
		$sakhir=($sawal+$tot_ter)-$tot_kel;
		$sakhir=round($sakhir,2);
		if ($rpt=="mwiptot")
		{	$ada_trans=$sakhir; }
		else
		{	$ada_trans=$sawal + $tot_ter + $tot_kel; }
		if ($ada_trans>0)
		{	if ($sakhir<0)
			{	$change_bgcolor="style='background-color: red;'";	}
			else
			{	$change_bgcolor="";	}
			echo "
			<tr>
				<td align='center' $change_bgcolor>$no</td>";
				if ($rpt=="barangjadi" AND $nm_company=="PT. Bangun Sarana Alloy")
				{	echo "<td $change_bgcolor>".$data['kpno']."</td>
						<td $change_bgcolor>$kode_barang</td>
						<td $change_bgcolor>$nama_barang</td>
						<td $change_bgcolor>".$data['itemname']."</td>
						<td $change_bgcolor>".$data['color']."</td>
						<td $change_bgcolor>$sat</td>";
				}
				else
				{	if ($rpt=="hasil")
					{	echo "<th>$matclass</th>"; }
					echo "<td $change_bgcolor>$kode_barang</td>
						<td $change_bgcolor>$nama_barang</td>
						<td $change_bgcolor>$sat</td>";
				}
				if ($rpt!="mwiptot")
				{	echo "
						<td align='right'>".fn($sawal,2)."</td>
						<td align='right'>".fn($tot_ter,2)."</td>
						<td align='right'>".fn($tot_kel,2)."</td>";
					if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
					{	echo "<td align='right'>$sakhir</td>
							<td align='right'><input type ='text' size='4' name ='itemstock[$id]' 
							value='$sakhir' id='stockajax$i' readonly></td>"; 
					}
					else
					{	echo "<td align='right'>0</td>
							<td align='right'>".fn($sakhir,2)."</td>"; 
					}
				}
				else
				{	#STOCK OPNAME
					if($nm_company=="PT. Sinar Gaya Busana")
					{	echo "<td align='right'></td>"; }
					else
					{	echo "<td align='right'>".fn($sakhir,2)."</td>"; }
				}
				if ($nm_company=="PT. Multi Sarana Plasindo")
				{	echo "<td align='right'>0</td>"; }
				elseif ($rpt!="mwiptot")
				{	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
					{	echo "<td align='right'><input type ='text' size='4' name ='item[$id]' 
							id='itemajax' class='itemclass'></td>"; 
					}
					else
					{	if($nm_company=="PT. Sinar Gaya Busana")
						{	echo "<td align='right'></td>"; }
						else
						{	echo "<td align='right'>".fn($sakhir,2)."</td>"; }
					}
				}
				if ($rpt!="mwiptot")
				{	
					$rolldet=flookup("group_concat(kode_rak,' (',qty_rak,')')","
						(Select kode_rak,sum((roll_qty-ifnull(roll_qty_used,0)) + (roll_foc-ifnull(roll_qty_foc_used,0))) qty_rak 
							from bpb_roll_h a inner join bpb_roll s on a.id=s.id_h left join master_rak mr on s.id_rak_loc=mr.id 
							Where id_item='$id_itemnya' and (roll_qty-ifnull(roll_qty_used,0)) + (roll_foc-ifnull(roll_qty_foc_used,0))>0 
							group by kode_rak
						) tmp_rak","qty_rak>0");
					echo "
					<td align='right'>0</td>
					<td align='right'>$id_itemnya</td>";
				}
				else
				{	echo "
					<td align='right'></td>";
				}
			echo "
			</tr>
			";
			$no++;
		}
	}; #$no bertambah 1
	?>
	</tbody>
</table>
<?php 
	if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
	{	echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>"; }
?>
<p>&nbsp;</p>
<?php 
if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
{	echo "</form>"; }
?>
</body>
</html>