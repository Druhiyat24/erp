<?php
include "fungsi.php";
include "../../include/conn.php";

$mode = $_GET['mode'];
session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
$st_company=flookup("status_company","mastercompany","company!=''");
$goods_code_only=flookup("goods_code_only","mastercompany","company!=''");
$bc262out=flookup("262_out","mastercompany","company!=''");
$bc27mskout=flookup("27msk_out","mastercompany","company!=''");
$nm_company=flookup("company","mastercompany","company!=''");

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;

$cek = flookup("id_supplier","mastersupplier","supplier='INHOUSE'");
if ($cek=="")
{	$sql="insert into mastersupplier (supplier,status_kb,area) 
		values ('INHOUSE','','F')";
	insert_log($sql,$user);
}

$sql = "insert into upload_tpb_hist select * from upload_tpb where USERNAME='$user'
	and SESI='$sesi'";
insert_log($sql,$user);
$sql = "delete from upload_tpb where USERNAME='$user'
	and SESI='$sesi'";
insert_log($sql,$user);

$nama_file = $_FILES['txtfile']['name'];
$ukuran_file = $_FILES['txtfile']['size'];
$tipe_file = $_FILES['txtfile']['type'];
$tmp_file = $_FILES['txtfile']['tmp_name'];
$tgl_down = date('Y-m-d H-i-s');
 
$path = "upload_files/".$nama_file;
move_uploaded_file($tmp_file, $path);

if ($mode=="In" OR $mode=="Out")
{ 	$txt_file    = file_get_contents($path);
  	$rows        = explode("\n", $txt_file);
  	array_shift($rows);
  	$i=1;
  	foreach($rows as $row => $data)
  	{	if ($data!="")
		{ 	# UNTUK CEK JIKA ADA ERROR 
			# echo $data."<br>";
			$row_data = explode('|', $data);
	 		$jenis_dok = $row_data[0];
		    $nomor_aju = $row_data[1];
		    $tgl_aju = $row_data[2];
		    $nomor_daftar = $row_data[3];
		    $tgl_daftar = $row_data[4];
		    $supplierv = $row_data[5];
		    $supplierori = str_replace("'","",$supplierv);
		    $supplier = preg_replace("/\s+/"," ",$supplierori);
		    $kode_barang_ori = substr(str_replace("'", "", $row_data[6]),0,100);
		    $kode_barang_ori = str_replace("ø","",$kode_barang_ori);
		    $kode_barang = preg_replace("/\s+/"," ",$kode_barang_ori);
		    if ($kode_barang=="") { $kode_barang="-"; }
		    $nama_barang_ori = substr(str_replace("'", "", $row_data[7]),0,200);
		    $nama_barang_ori = str_replace("ø","",$nama_barang_ori);
		    $nama_barang = preg_replace("/\s+/"," ",$nama_barang_ori);
		    $qty = $row_data[9];
		    $satuan = $row_data[8];
		    
		    if (isset($row_data[14])) {	$curr=$row_data[14]; } else { $curr=""; }
		    if (isset($row_data[15])) {	$pono=$row_data[15]; } else { $pono="-"; }
		    if (isset($row_data[16])) {	$invno=$row_data[16]; } else { $invno="-"; }
		    if ($st_company=="GB")
		    {	if (isset($row_data[17])) {	$goods_code2=$row_data[17]; } else { $goods_code2=""; } 
		  		$netto=0;
				}
		    else
				{	$goods_code2=""; 
					if (isset($row_data[17])) {	$netto=$row_data[17]; } else { $netto=0; } 
				}
		    if (isset($row_data[18])) {	$bruto=$row_data[18]; } else { $bruto=0; }
		    if (isset($row_data[19])) {	$seri_barang=$row_data[19]; } else { $seri_barang=$row; }
		    if (isset($row_data[20])) {	$styleno=$row_data[20]; } else { $styleno=""; }
		    if ($styleno!="") 
		    { $merk=explode('~',$styleno);
		 			if (isset($merk[0])) {$val_fg=nb($merk[0]);} else {$val_fg="";}
		 			if (isset($merk[1])) {$val_bb=nb($merk[1]);} else {$val_bb="";}
		    	$sql="select a.id_item_fg,a.id_item_bb from bom a inner join masteritem s 
						on a.id_item_bb=s.id_item inner join masterstyle d
						on a.id_item_fg=d.id_item where 
						d.styleno='$val_fg' and s.goods_code='$val_bb'";
		    	$sql2=mysql_query($sql);
		    	if (!$sql2) { die($sql. mysql_error()); }
		    	$rsbom=mysql_fetch_array($sql2);
		    	$id_item_fg=$rsbom['id_item_fg'];
		    	$id_item_bb=$rsbom['id_item_bb']; 
				}
				else
				{ $id_item_fg="0"; 
					$id_item_bb="0";
				}
		    if ($nm_company=="PT. Youngil Leather Indonesia") { $netto=0; }
		    
		    $xcurr=str_replace(chr(13), "", $curr);
				if ($xcurr=="") { $curr="IDR"; }
			
				$price = $row_data[10];
		    $nilai = $row_data[11];
		  	$tujuan=$row_data[13];
	  		if ($nm_company=="PT. Youngil Leather Indonesia")
	  		{ if ($jenis_dok=="BC 2.7" AND $tujuan=="DIJUAL")
	  			{ $mattype="FG"; }
	  		  else if ($jenis_dok=="BC 2.7" AND $tujuan!="DIJUAL")
	  			{ $mattype="FG"; }
	  		  else
	  		  { $mattype=$row_data[12]; }
	  		}
	  		else
	  		{ $mattype=$row_data[12]; }
	  		
	  		if ($mattype=="") { $mattype="A"; }
	  		if ($mattype!="FG" AND $mattype!="A" AND $mattype!="F" AND $mattype!="S" AND $mattype!="L" AND $mattype!="M" AND $mattype!="P" AND $mattype!="C") { $mattype="A"; }
	  		if ($mode=="Out" AND $nm_company=="PT. Hwaseung IBS Indonesia")
	  		{ if ($qty>0) { $curr='IDR'; $price = $nilai/$qty; } }
	  		else
	  		{	if (($price=="" OR $price=="0") AND $qty>0) { $price = $nilai/$qty; } }
	  			  		
	  		if ($mattype=="FG")
	  		{ $tblmst="masterstyle"; 
	  		  $fldmst="itemname";
	  		  $fldinsert="country,goods_code,itemname,buyerno,goods_code2";
	  		}
	  		else
	  		{ $tblmst="masteritem"; 
	  		  $fldmst="itemdesc";
	  		  $fldinsert="mattype,goods_code,itemdesc,matclass,goods_code2";
	  		  if ($goods_code_only=="N")
	  		  {	$cekmat = flookup("mattype",$tblmst,"goods_code='$kode_barang' and $fldmst='$nama_barang'"); }
	  			else
	  			{	$cekmat = flookup("mattype",$tblmst,"goods_code='$kode_barang'"); }
	  		  if ($cekmat!="") { $mattype=$cekmat; }
	  		}
	  		if ($mode=="In")
	  		{	$cek=flookup("bpbno","bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier",
	  				"nomor_aju='$nomor_aju' and s.supplier='$supplier' "); 
	  		}
	  		else
	  		{	$cek=flookup("bppbno","bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier",
	  				"nomor_aju='$nomor_aju' and s.supplier='$supplier'"); 
	  		}
	  		if ($cek=="")
	  		{	if ($mode=="Out" AND 
		  			($jenis_dok=='BC 4.1' OR $jenis_dok=='BC 2.7' OR $jenis_dok=='BC 2.6.1' 
		  			 OR $jenis_dok=='BC 2.5' OR $jenis_dok=='BC 3.0' OR $jenis_dok=='OUT'
		  			 OR $jenis_dok=='BC 2.4' OR $jenis_dok=='JUAL LOKAL'))
					{	if ($st_company=="GB")
						{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
						else
						{	if ($goods_code_only=="N")
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang' 
									and $fldmst='$nama_barang'");
							}
							else
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
						}
						if ($id_item=="")
						{	$sql="insert into $tblmst ($fldinsert,color,size) 
								values ('$mattype','$kode_barang','$nama_barang','-','$goods_code2','','')";
							insert_log($sql,$user);
							if ($st_company=="GB")
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
							else
							{	if ($goods_code_only=="N")
								{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang' 
										and $fldmst='$nama_barang'");
								}
								else
								{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang' "); }
							}
						}
			  		$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
			  		if ($id_supplier=="")
						{	$sql="insert into mastersupplier (supplier,status_kb,area) 
								values ('$supplier','','L')";
							insert_log($sql,$user);
							$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
			  		}
			  		if ($jenis_dok=="BC 3.0" OR $jenis_dok=="BC 2.3" 
			  			OR $jenis_dok=="BC 2.0") { $tgl_aju=$tgl_daftar; }
			  		$sql = "insert into upload_tpb (URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,NOMOR_DAFTAR,TANGGAL_DAFTAR,
			  			SUPPLIER,KODE_BARANG,URAIAN,KODE_SATUAN,JUMLAH_SATUAN,HARGA_SATUAN,HARGA_PENYERAHAN,ID_ITEM,
			  			ID_SUPPLIER,USERNAME,SESI,TGL_DOWNLOAD,MATTYPE,TUJUAN,KODE_VALUTA,
			  			PONO,INVNO,BERAT_BERSIH,BERAT_KOTOR,SERI_BARANG,
			  			NAMA_FILE,ID_ITEM_FG,ID_ITEM_BB)
							values ('$jenis_dok','$nomor_aju','$tgl_aju','$nomor_daftar','$tgl_daftar','$supplier',
							'$kode_barang','$nama_barang','$satuan','$qty','$price','$nilai','$id_item','$id_supplier',
							'$user','$sesi','$tgl_down','$mattype','$tujuan','$curr',
							'$pono','$invno','$netto','$bruto','$seri_barang',
							'$nama_file','$id_item_fg','$id_item_bb')";
						insert_log($sql,$user); 
					}
		  		else if ($mode=="In" AND 
		  			($jenis_dok=='BC 4.0' OR $jenis_dok=='BC 2.3' OR $jenis_dok=='BC 2.6.2' 
		  			 OR strtoupper($jenis_dok)=='BC 2.7 MSK' OR $jenis_dok=='IN' 
		  			 OR $jenis_dok=='BC 2.0' OR $jenis_dok=='LOKAL'))
					{ if ($st_company=="GB")
						{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
						else
						{	if ($goods_code_only=="N")
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang' 
									and $fldmst='$nama_barang'");
							}
							else
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
						}
						if ($id_item=="")
						{	$sql="insert into $tblmst ($fldinsert,color,size) 
								values ('$mattype','$kode_barang','$nama_barang','-','$goods_code2','','')";
							insert_log($sql,$user);
							if ($st_company=="GB")
							{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
							else
							{	if ($goods_code_only=="N")
								{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang' 
										and $fldmst='$nama_barang'");
								}
								else
								{	$id_item = flookup("id_item",$tblmst,"goods_code='$kode_barang'"); }
							}
						}
			  		$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
			  		if ($id_supplier=="")
						{	if ($jenis_dok=="BC 2.3") {	$area="I"; } else {	$area="L"; }
							$sql="insert into mastersupplier (supplier,status_kb,area) 
								values ('$supplier','','$area')";
							insert_log($sql,$user);
							$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
			  		}
			  		$jenis_doknya=str_replace("BC 2.7 MSK","BC 2.7",$jenis_dok);
			  		if ($jenis_dok=="BC 3.0" OR $jenis_dok=="BC 2.3" 
			  			OR $jenis_dok=="BC 2.0") { $tgl_aju=$tgl_daftar; }
			  		$sql = "insert into upload_tpb (URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,NOMOR_DAFTAR,TANGGAL_DAFTAR,SUPPLIER,
			  			KODE_BARANG,URAIAN,KODE_SATUAN,JUMLAH_SATUAN,HARGA_SATUAN,HARGA_PENYERAHAN,ID_ITEM,ID_SUPPLIER,
			  			USERNAME,SESI,TGL_DOWNLOAD,MATTYPE,TUJUAN,KODE_VALUTA,
			  			PONO,INVNO,BERAT_BERSIH,BERAT_KOTOR,SERI_BARANG,
			  			NAMA_FILE,ID_ITEM_FG,ID_ITEM_BB)
						values ('$jenis_doknya','$nomor_aju','$tgl_aju','$nomor_daftar','$tgl_daftar','$supplier',
						'$kode_barang','$nama_barang','$satuan','$qty','$price','$nilai','$id_item','$id_supplier',
						'$user','$sesi','$tgl_down','$mattype','$tujuan','$curr',
						'$pono','$invno','$netto','$bruto','$seri_barang',
						'$nama_file','$id_item_fg','$id_item_bb')";
					insert_log($sql,$user); 
					}	
			}
		}
	}

  	# ADD NOMOR BPB
  	$query = mysql_query("select nomor_aju,mattype from upload_tpb where username='$user' 
  		and sesi='$sesi' group by nomor_aju,mattype order by tanggal_aju");
  	while($data = mysql_fetch_array($query))
  	{	$trans_no="";
  		$nomor_aju = $data['nomor_aju'];
  		$mattype = $data['mattype'];
  		if ($mode=="Out") 
		{ $cek=flookup("bppbno","bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier",
	  		"nomor_aju='$nomor_aju' and s.supplier='$supplier' ");
		  if ($cek=="") { $trans_no = urutkan('Add_BPPB', $mattype); }
		}
		else
		{ $cek=flookup("bpbno","bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier",
	  		"nomor_aju='$nomor_aju' and s.supplier='$supplier' ");
		  if ($cek=="") { $trans_no = urutkan('Add_BPB',$mattype); }
		}  
  		if ($trans_no!="")
  		{ $sql = "update upload_tpb set trans_no='$trans_no' where username='$user' 
  				and sesi='$sesi' and nomor_aju='$nomor_aju' and mattype='$mattype' ";
  		  insert_log($sql,$user);
  		}
  	}
  	
  	$sql = "delete from upload_tpb where username='$user' and sesi='$sesi' and
			trans_no=''";
		insert_log($sql,$user);
		#$sql = "update upload_tpb set KODE_VALUTA='IDR' where username='$user' and sesi='$sesi' and
		#	trim(KODE_VALUTA)=''";
		#insert_log($sql,$user);

  	#BUAT TRANSAKSI
  	if ($mode=="Out")
  	{	$supplier="INHOUSE";
  		$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
  		# CEK STOCK PER TGL
	  	$query = mysql_query("select mattype,id_item,nomor_aju,tanggal_aju,
	  		seri_barang from upload_tpb where username='$user' and 
	  		sesi='$sesi' group by mattype,id_item,nomor_aju,tanggal_aju,seri_barang order by nomor_aju,tanggal_aju,seri_barang");
	  	while($data = mysql_fetch_array($query))
	  	{	$crimatnya = $data['mattype'];
	  		$criidnya = $data['id_item'];
	  		$cridatenya = $data['tanggal_aju'];
	  		$crinoajunya = $data['nomor_aju'];
	  		$criseribar = $data['seri_barang'];
	  		$sisa = get_stock_tgl("BKB",$crimatnya,$criidnya,$cridatenya,"");
				$out_di_txt = flookup("sum(jumlah_satuan)","upload_tpb","username='$user' 
					and sesi='$sesi' and mattype='$crimatnya' and id_item='$criidnya'
					and cek_stock='Y' AND (tanggal_aju<='$cridatenya' or nomor_aju<>'$crinoajunya') ");
				if ($sisa==0 AND $out_di_txt!=0)
				{	$sisa = $sisa; }
				else
				{	$sisa = $sisa - $out_di_txt; }
				if ($sisa<0) { $sisa=0; }
				$sql = "update upload_tpb set cek_stock='Y',stock='$sisa' where username='$user' 
					and sesi='$sesi' and mattype='$crimatnya' and id_item='$criidnya'
					and tanggal_aju='$cridatenya' and nomor_aju='$crinoajunya'
					and seri_barang='$criseribar'";
				insert_log($sql,$user);
	  	}
  		#$sql="select * from upload_tpb where username='$user' and sesi='$sesi' and jumlah_satuan>stock group by mattype";
  		if ($st_company=="GB")
  		{	$sql="select sum(jumlah_satuan) tot_qty,stock,id_item from upload_tpb where username='$user' and 
  				sesi='$sesi' group by id_item,mattype";
  		}
  		else
  		{	$sql="select * from upload_tpb where username='$user' and sesi='$sesi'  
  				group by mattype";
  		}
  		$query=mysql_query($sql);
  		while($data = mysql_fetch_array($query))
	  	{	if ($st_company=="GB")
	  		{	$total_qty = $data['tot_qty'];
	  			$total_stock = $data['stock'];
	  			$id_item = $data['id_item'];
	  			if ($total_qty>$total_stock)
	  			{	$sql = "insert into bppb_prob (bppbno,bppbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,
		  				price,bcno,bcdate,nomor_aju,tanggal_aju,tujuan,kpno,username,use_kite
		  				,berat_bersih,berat_kotor,styleno)
						select trans_no,tanggal_aju,URAIAN_DOKUMEN,id_item,id_supplier,sum(jumlah_satuan),KODE_SATUAN,KODE_VALUTA,
						HARGA_SATUAN,NOMOR_DAFTAR,TANGGAL_DAFTAR,nomor_aju,tanggal_aju,tujuan,nomor_daftar,
						username,'1',BERAT_BERSIH,BERAT_KOTOR,seri_barang from upload_tpb
						where username='$user' and sesi='$sesi'
						and id_item='$id_item'
						group by nomor_aju,nomor_daftar,id_item,seri_barang ";
					insert_log($sql,$user);
		  			$sql = "delete from upload_tpb where username='$user' and sesi='$sesi'
						and id_item='$id_item'";
					insert_log($sql,$user);
				}
		  	}
	  		else
	  		{	$mattype = $data['MATTYPE'];
		  		$trans_no_bpb = urutkan('Add_BPB',$mattype);
		  		$sql = "insert into bpb (bpbno,bpbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
		  			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,pono,invno,kpno,username,use_kite,
		  			id_item_fg,id_item_bb)
						select '$trans_no_bpb',tanggal_aju,'INHOUSE',id_item,'$id_supplier',sum(jumlah_satuan)-stock,KODE_SATUAN,KODE_VALUTA,
						HARGA_SATUAN,'-','0000-00-00','-','0000-00-00','',PONO,INVNO,nomor_aju,
						username,'1',id_item_fg,id_item_bb from upload_tpb
						where username='$user' and sesi='$sesi'  
						and mattype='$mattype'
						group by nomor_aju,id_item,seri_barang";
					insert_log($sql,$user);
					$sql = "delete from bpb where qty<=0";
					insert_log($sql,$user);
				#echo $sql;
				#$cekaa=flookup("user","pasa","asas='aaa'");
			}
	  	}
	  	#Add invno 040218
		$sql = "insert into bppb (bppbno,bppbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
  			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,kpno,username,use_kite
  			,berat_bersih,berat_kotor,invno,styleno)
			select trans_no,tanggal_aju,URAIAN_DOKUMEN,id_item,id_supplier,sum(jumlah_satuan),KODE_SATUAN,KODE_VALUTA,
			HARGA_SATUAN,NOMOR_DAFTAR,TANGGAL_DAFTAR,nomor_aju,tanggal_aju,tujuan,nomor_daftar,
			username,'1',BERAT_BERSIH,BERAT_KOTOR,invno,seri_barang from upload_tpb
			where username='$user' and sesi='$sesi'
			group by nomor_aju,nomor_daftar,id_item,seri_barang ";
		insert_log($sql,$user);
	}
	else if ($mode=="In")
	{	if ($bc262out=="1")
		{	$supplier="INHOUSE";
  		$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
  		$sql="select * from upload_tpb where username='$user' and sesi='$sesi'  
  			and uraian_dokumen='BC 2.6.2' group by mattype";
  		$query=mysql_query($sql);
  		while($data = mysql_fetch_array($query))
	  	{	$mattype = $data['MATTYPE'];
	  		$trans_no_bpb = urutkan('Add_BPPB',$mattype);
	  		$sql = "insert into bppb (bppbno,bppbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
	  			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,invno,kpno,username,use_kite,styleno)
					select '$trans_no_bpb',tanggal_aju,'INHOUSE',id_item,'$id_supplier',sum(jumlah_satuan)-stock,KODE_SATUAN,KODE_VALUTA,
					HARGA_SATUAN,'-','0000-00-00','-','0000-00-00','',INVNO,right(nomor_aju,6),
					username,'1',seri_barang from upload_tpb
					where username='$user' and sesi='$sesi'  
					and mattype='$mattype' and uraian_dokumen='BC 2.6.2'
					group by id_item,seri_barang";
				insert_log($sql,$user);
			}
		}
		if ($bc27mskout=="1")
		{	$supplier="INHOUSE";
  		$id_supplier = flookup("id_supplier","mastersupplier","supplier='$supplier'");
  		$sql="select * from upload_tpb where username='$user' and sesi='$sesi'  
  			and uraian_dokumen='BC 2.7' group by mattype";
  		$query=mysql_query($sql);
  		while($data = mysql_fetch_array($query))
	  	{	$mattype = $data['MATTYPE'];
	  		$trans_no_bpb = urutkan('Add_BPPB',$mattype);
	  		$sql = "insert into bppb (bppbno,bppbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
	  			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,invno,kpno,username,use_kite,styleno)
					select '$trans_no_bpb',tanggal_aju,'INHOUSE',id_item,'$id_supplier',sum(jumlah_satuan)-stock,KODE_SATUAN,KODE_VALUTA,
					HARGA_SATUAN,'-','0000-00-00','-','0000-00-00','',INVNO,right(nomor_aju,6),
					username,'1',seri_barang from upload_tpb
					where username='$user' and sesi='$sesi'  
					and mattype='$mattype' and uraian_dokumen='BC 2.7'
					group by id_item,seri_barang";
				insert_log($sql,$user);
			}
		}
		$sql = "insert into bpb (bpbno,bpbdate,jenis_dok,id_item,id_supplier,qty,unit,curr,price,
			bcno,bcdate,nomor_aju,tanggal_aju,tujuan,invno,pono,kpno,username,use_kite
			,berat_bersih,berat_kotor,id_item_fg,id_item_bb)
			select trans_no,tanggal_aju,URAIAN_DOKUMEN,id_item,id_supplier,sum(jumlah_satuan),KODE_SATUAN,KODE_VALUTA,
			HARGA_SATUAN,NOMOR_DAFTAR,TANGGAL_DAFTAR,nomor_aju,tanggal_aju,tujuan,INVNO,PONO,nomor_daftar,
			username,'1',BERAT_BERSIH,BERAT_KOTOR,
			id_item_fg,id_item_bb from upload_tpb
			where username='$user' and sesi='$sesi'
			group by nomor_aju,nomor_daftar,id_item,seri_barang";
		insert_log($sql,$user);
	}

	#CALCULATE STOCK
	$query = mysql_query("select id_item,mattype from upload_tpb where username='$user' and sesi='$sesi' 
		group by id_item,mattype");
  	while($data = mysql_fetch_array($query))
  	{	$mattype = $data['mattype'];
  		$id_item = $data['id_item'];
  		calc_stock($mattype,$id_item);
  	}
  	$_SESSION['msg'] = 4;
  	echo "<script>window.location.href='index.php?mod=1';</script>";
}
?>
