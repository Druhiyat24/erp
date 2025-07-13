<?php
include '../../include/conn.php';
include 'fungsi.php';
include 'journal_interface.php';
session_start();
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}
$user = $_SESSION['username'];
$mod = $_GET['mod'];

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$gen_nomor_int = $rscomp["gen_nomor_int"];
$auto_jurnal = $rscomp["auto_jurnal"];

$cridt = $_POST['txttglcut'];

$arrnya = explode(":", $_POST['txtsjno']);
$txtbppbno = $arrnya[1];
$nm_tbl = $arrnya[0];
if ($nm_tbl == "bpb") {
	$nm_fld = "bpbno";
} else {
	$nm_fld = "bppbno";
}

$cekri = substr($txtbppbno, -1, 1);
if ($nm_tbl == "bpb" && $cekri == "R") {
	$cekdata = flookup("bpbno_int", "bpb", "bpbno='$txtbppbno' limit 1");
	$cekmat = substr($cekdata, 0, 2);
	if ($cekmat == "GK") {
		$qty_temp = ",qty=qty_temp";
	} else {
		$qty_temp = "";
	}
} else {
	$qty_temp = "";
}
$tgl_cfm = date('Y-m-d H-i-s');


// {	
// 	{	
// 		{	$sql = "update $nm_tbl set confirm_by='$user',confirm='Y',confirm_date='$tgl_cfm' 
// 				where $nm_fld='$txtbppbno'";
// 			insert_log($sql,$user);
// 		}
// 	}
// 	if($nm_tbl=="bpb" and $auto_jurnal=="Y")
// 	{	$bpbno_int=flookup("bpbno_int","bpb","bpbno='$txtbppbno'");	
// 		insert_bpb_gr($bpbno_int);
// 	}
// 	$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
// 	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode';</script>";
// }




if (!isset($_POST['itemchk'])) {
	$_SESSION['msg'] = "XTidak Ada Data Yang Harus Dikonfirmasi";
	echo "<script>window.location.href='index.php?mod=$mod';</script>";
} else {
	$ItemArray = $_POST['itemchk'];
	foreach ($ItemArray as $key => $value) {
		$chk = $value;
		$id_item = $key;
		if ($chk == "on") {
			$sql = "update $nm_tbl set confirm_by='$user',confirm='Y',confirm_date='$tgl_cfm' $qty_temp
			where $nm_fld='$txtbppbno' and id_item='$id_item'";
			insert_log($sql, $user);
		}
	}
	if ($nm_tbl == "bpb") {
		$bpbno_int = flookup("bpbno_int", "bpb", "bpbno='$txtbppbno'");
		// $cekdata = mysql_query("select bpbno, bpbno_int, bpb.bpbdate, bpb.id_supplier, supplier, mattype, n_code_category, 
		// 	if(matclass like '%ACCESORIES%','ACCESORIES',mi.matclass) matclass, bpb.curr, COALESCE(tax,0) tax,bpb.username, bpb.dateinput, 
		// 	SUM(((qty - COALESCE(qty_reject,0)) * price) + (((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(tax,0) /100))) as total,SUM(((qty - COALESCE(qty_reject,0)) * price)) as dpp,SUM((((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(tax,0) /100))) as ppn
		// 	from bpb 
		// 	inner join masteritem mi on bpb.id_item = mi.id_item
		// 	inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
		// 	left join po_header ph on bpb.pono = ph.pono
		// 	where bpbno  = '$txtbppbno' group by bpbno");

		$cekdata = mysql_query("select SUBSTR(bpbno_int,1,3) fil_wip, phd.tipe_com, mi.itemdesc,bpb.confirm,bpbno, bpbno_int, bpb.bpbdate, bpb.id_supplier, supplier, mattype, n_code_category, 
			if(matclass like '%ACCESORIES%','ACCESORIES',mi.matclass) matclass, bpb.curr, COALESCE(ph.tax,0) tax,bpb.username, bpb.dateinput, 
			round(SUM(((qty - COALESCE(qty_reject,0)) * price) + (((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(ph.tax,0) /100))),2) as total,round(SUM(((qty - COALESCE(qty_reject,0)) * price)),2) as dpp,round(SUM((((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(ph.tax,0) /100))),2) as ppn
			from bpb 
			inner join masteritem mi on bpb.id_item = mi.id_item
			inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
			left join po_header ph on bpb.pono = ph.pono
			left join po_header_draft phd on phd.id = ph.id_draft
			where bpbno = '$txtbppbno'  group by bpbno,mattype, n_code_category order by supplier");
		$databpb    = mysql_fetch_array($cekdata);
		$no_bpb		= $databpb['bpbno_int'];
		$supp		= $databpb['supplier'];
		$id_supplier = $databpb['id_supplier'];
		$mattype	= $databpb['mattype'];
		$matclass1	= $databpb['matclass'];
		$n_code_category	= $databpb['n_code_category'];
		$tax	= $databpb['tax'];
		$curr	= $databpb['curr'];
		$username	= $databpb['username'];
		$curr	= $databpb['curr'];
		$total	= $databpb['total'];
		$dpp	= $databpb['dpp'];
		$ppn	= $databpb['ppn'];
		$tgl_bpb = $databpb['bpbdate'];
		$dateinput = $databpb['dateinput'];
		$tipe_com = $databpb['tipe_com'];
		$fil_wip = $databpb['fil_wip'];

		if ($fil_wip == 'WIP') {
		 	$no_costcenter		= 'DEP04SUB004';
		 	$nama_costcenter	= 'DISTRIBUTION CENTER';
		}else{
			$no_costcenter		= '';
		 	$nama_costcenter	= '';
		}

		if ($mattype == 'C') {
			if ($matclass1 == 'CMT' || $matclass1 == 'PRINTING' || $matclass1 == 'EMBRODEIRY' || $matclass1 == 'WASHING' || $matclass1 == 'PAINTING' || $matclass1 == 'HEATSEAL') {
				$matclass = $matclass1;
			} else {
				$matclass = 'OTHER';
			}
		} else {
			$matclass = $matclass1;
		}

		if ($curr != 'IDR') {
			$sqlx = mysqli_query($conn_li, "select ROUND(rate,2) as rate , tanggal  FROM masterrate where tanggal = '$tgl_bpb' and v_codecurr = 'PAJAK'");
			$rowx = mysqli_fetch_array($sqlx);
			$h_rate = isset($rowx['rate']) ? $rowx['rate'] : 0;

			if ($h_rate == 0) {
				$sqly = mysqli_query($conn_li, "select ROUND(rate,2) as rate , tanggal  FROM masterrate where id = (select max(id) as id FROM masterrate where v_codecurr = 'PAJAK') and v_codecurr = 'PAJAK'");
				$rowy = mysqli_fetch_array($sqly);
				$rate = $rowy['rate'];
				$tglrate = $rowy['tanggal'];
			} else {
				$rate = $h_rate;
			}
		} else {
			$rate = 1;
		}

		$idr_dpp = $dpp * $rate;
		$idr_ppn = $ppn * $rate;
		$idr_total = $total * $rate;

		if ($id_supplier == '342' || $id_supplier == '20' || $id_supplier == '19' || $id_supplier == '692' || $id_supplier == '17' || $id_supplier == '18') {
			$cust_ctg = 'Related';
		} else {
			$cust_ctg = 'Third';
		}

		if ($mattype != 'N') {
			if ($matclass == 'FABRIC') {
				$kata1 = "PEMBELIAN KAIN";
			} elseif ($matclass == 'ACCESORIES') {
				$kata1 = "PEMBELIAN AKSESORIS";
			} elseif ($matclass == 'CMT') {
				$kata1 = "BIAYA MAKLOON PAKAIAN JADI";
			} elseif ($matclass == 'PRINTING') {
				$kata1 = "BIAYA MAKLOON PRINTING";
			} elseif ($matclass == 'EMBRODEIRY') {
				$kata1 = "BIAYA MAKLOON EMBRODEIRY";
			} elseif ($matclass == 'WASHING') {
				$kata1 = "BIAYA MAKLOON WASHING";
			} elseif ($matclass == 'PAINTING') {
				$kata1 = "BIAYA MAKLOON PAINTING";
			} elseif ($matclass == 'HEATSEAL') {
				$kata1 = "BIAYA MAKLOON HEATSEAL";
			} else {
				$kata1 = "BIAYA MAKLOON LAINNYA";
			}
		} else {
			if ($n_code_category == '1') {
				$kata1 = "PEMBELIAN PERSEDIAAN ATK";
			} elseif ($n_code_category == '2') {
				$kata1 = "PEMBELIAN PERSEDIAAN UMUM";
			} elseif ($n_code_category == '3') {
				$kata1 = "BIAYA PERSEDIAAN SPAREPARTS";
			} elseif ($n_code_category == '4') {
				$kata1 = "BIAYA MESIN";
			} else {
				$kata1 = "";
			}
		}

		$kata2 = "DARI";

		$description = $kata1 . " " . $no_bpb . " " . $kata2 . " " . $supp;

		$sqlcoa = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_credit%' Limit 1");
		$rowcoa = mysqli_fetch_array($sqlcoa);
		$no_coa_cre = $rowcoa['no_coa'];
		$nama_coa_cre = $rowcoa['nama_coa'];

		$queryss2 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
		VALUES 
		('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_cre', '$nama_coa_cre', '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '0', '$total', '0', '$idr_total', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";
		insert_log($queryss2, $user);

		$sqlcoa2 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_debit%' Limit 1");
		$rowcoa2 = mysqli_fetch_array($sqlcoa2);
		$no_coa_deb = $rowcoa2['no_coa'];
		$nama_coa_deb = $rowcoa2['nama_coa'];

		$queryss3 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
		VALUES 
		('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_deb', '$nama_coa_deb',  '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '$dpp', '0', '$idr_dpp', '0', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";
		insert_log($queryss3, $user);

		if ($tax >= 1) {
			$sqlcoa3 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where inv_type like '%PPN MASUKAN%' Limit 1");
			$rowcoa3 = mysqli_fetch_array($sqlcoa3);
			$no_coa_ppn = $rowcoa3['no_coa'];
			$nama_coa_ppn = $rowcoa3['nama_coa'];


			$queryss4 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
			VALUES 
			('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_ppn', '$nama_coa_ppn',  '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '$ppn', '0', '$idr_ppn', '0', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";

			insert_log($queryss4, $user);
		} else {
		}

		if ($tipe_com == 'BUYER') {
			$sqlcoa = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_debit%' Limit 1");
			$rowcoa = mysqli_fetch_array($sqlcoa);
			$no_coa_cre = $rowcoa['no_coa'];
			$nama_coa_cre = $rowcoa['nama_coa'];

			$queryss2 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
			VALUES 
			('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_cre', '$nama_coa_cre',  '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '0', '$total', '0', '$idr_total', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";
			insert_log($queryss2, $user);


			$queryss3 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
			VALUES 
			('$no_bpb', '$tgl_bpb', 'AP - BPB', '1.34.05', 'PIUTANG LAIN-LAIN PIHAK KETIGA - BAHAN BAKU / BAHAN PEMBANTU',  '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '$dpp', '0', '$idr_dpp', '0', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";
			insert_log($queryss3, $user);

			if ($tax >= 1) {
				$sqlcoa3 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where inv_type like '%PPN MASUKAN%' Limit 1");
				$rowcoa3 = mysqli_fetch_array($sqlcoa3);
				$no_coa_ppn = $rowcoa3['no_coa'];
				$nama_coa_ppn = $rowcoa3['nama_coa'];


				$queryss4 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
				VALUES 
				('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_ppn', '$nama_coa_ppn',  '$no_costcenter', '$nama_costcenter', '-', '', '-', '-', '$curr', '$rate', '$ppn', '0', '$idr_ppn', '0', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";

				insert_log($queryss4, $user);
			}
		}

		// echo $description;	
	} else {

		$bppbno_int = flookup("bppbno_int", "bppb", "bppbno='$txtbppbno'");
		$cekdata = mysql_query("select bppbno,bppbno_int,bppbdate,curr,id_supplier,supplier,mattype,n_code_category,matclass,curr,tax,username,dateinput,(dpp + (dpp * (tax/100))) total,dpp,(dpp * (tax/100)) ppn from (select bppbno, bppbno_int, bppb.bppbdate, bppb.id_supplier, supplier, mattype, n_code_category, 
			if(matclass like '%ACCESORIES%','ACCESORIES',mi.matclass) matclass, bppb.curr,bppb.username, bppb.dateinput, 
			SUM(((qty) * price)) as dpp,bpbno_ro
			from bppb 
			inner join masteritem mi on bppb.id_item = mi.id_item
			inner join mastersupplier ms on bppb.id_supplier = ms.id_supplier
			where bppbno = '$txtbppbno' group by bppbno) a inner join

			(select bpbno,pono from bpb GROUP BY bpbno) b on b.bpbno = a.bpbno_ro
			inner JOIN
			(select pono,tax from po_header GROUP BY pono) c on c.pono = b.pono");
		$databpb    = mysql_fetch_array($cekdata);
		$no_bpb		= $databpb['bppbno_int'];
		$supp		= $databpb['supplier'];
		$id_supplier = $databpb['id_supplier'];
		$mattype	= $databpb['mattype'];
		$matclass1	= $databpb['matclass'];
		$n_code_category	= $databpb['n_code_category'];
		$tax	= $databpb['tax'];
		$curr	= $databpb['curr'];
		$username	= $databpb['username'];
		$curr	= $databpb['curr'];
		$total	= $databpb['total'];
		$dpp	= $databpb['dpp'];
		$ppn	= $databpb['ppn'];
		$tgl_bpb = $databpb['bppbdate'];
		$dateinput = $databpb['dateinput'];

		if ($mattype == 'C') {
			if ($matclass1 == 'CMT' || $matclass1 == 'PRINTING' || $matclass1 == 'EMBRODEIRY' || $matclass1 == 'WASHING' || $matclass1 == 'PAINTING' || $matclass1 == 'HEATSEAL') {
				$matclass = $matclass1;
			} else {
				$matclass = 'OTHER';
			}
		} else {
			$matclass = $matclass1;
		}

		if ($curr != 'IDR') {
			$sqlx = mysqli_query($conn_li, "select ROUND(rate,2) as rate , tanggal  FROM masterrate where tanggal = '$tgl_bpb' and v_codecurr = 'PAJAK'");
			$rowx = mysqli_fetch_array($sqlx);
			$h_rate = isset($rowx['rate']) ? $rowx['rate'] : 0;

			if ($h_rate == 0) {
				$sqly = mysqli_query($conn_li, "select ROUND(rate,2) as rate , tanggal  FROM masterrate where id = (select max(id) as id FROM masterrate where v_codecurr = 'PAJAK') and v_codecurr = 'PAJAK'");
				$rowy = mysqli_fetch_array($sqly);
				$rate = $rowy['rate'];
				$tglrate = $rowy['tanggal'];
			} else {
				$rate = $h_rate;
			}
		} else {
			$rate = 1;
		}

		$idr_dpp = $dpp * $rate;
		$idr_ppn = $ppn * $rate;
		$idr_total = $total * $rate;

		if ($id_supplier == '342' || $id_supplier == '20' || $id_supplier == '19' || $id_supplier == '692' || $id_supplier == '17' || $id_supplier == '18') {
			$cust_ctg = 'Related';
		} else {
			$cust_ctg = 'Third';
		}

		if ($mattype != 'N') {
			if ($matclass == 'FABRIC') {
				$kata1 = "RETURN PEMBELIAN KAIN";
			} elseif ($matclass == 'ACCESORIES') {
				$kata1 = "RETURN PEMBELIAN AKSESORIS";
			} elseif ($matclass == 'CMT') {
				$kata1 = "RETURN BIAYA MAKLOON PAKAIAN JADI";
			} elseif ($matclass == 'PRINTING') {
				$kata1 = "RETURN BIAYA MAKLOON PRINTING";
			} elseif ($matclass == 'EMBRODEIRY') {
				$kata1 = "RETURN BIAYA MAKLOON EMBRODEIRY";
			} elseif ($matclass == 'WASHING') {
				$kata1 = "RETURN BIAYA MAKLOON WASHING";
			} elseif ($matclass == 'PAINTING') {
				$kata1 = "RETURN BIAYA MAKLOON PAINTING";
			} elseif ($matclass == 'HEATSEAL') {
				$kata1 = "RETURN BIAYA MAKLOON HEATSEAL";
			} else {
				$kata1 = "RETURN BIAYA MAKLOON LAINNYA";
			}
		} else {
			if ($n_code_category == '1') {
				$kata1 = "RETURN PEMBELIAN PERSEDIAAN ATK";
			} elseif ($n_code_category == '2') {
				$kata1 = "RETURN PEMBELIAN PERSEDIAAN UMUM";
			} elseif ($n_code_category == '3') {
				$kata1 = "RETURN BIAYA PERSEDIAAN SPAREPARTS";
			} elseif ($n_code_category == '4') {
				$kata1 = "RETURN BIAYA MESIN";
			} else {
				$kata1 = "";
			}
		}

		$kata2 = "DARI";

		$description = $kata1 . " " . $no_bpb . " " . $kata2 . " " . $supp;

		$sqlcoa = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_credit%' Limit 1");
		$rowcoa = mysqli_fetch_array($sqlcoa);
		$no_coa_cre = $rowcoa['no_coa'];
		$nama_coa_cre = $rowcoa['nama_coa'];

		$queryss2 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
		VALUES 
		('$no_bpb', '$tgl_bpb', 'AP - BPB RETURN', '$no_coa_cre', '$nama_coa_cre', '-', '-', '-', '', '-', '-', '$curr', '$rate', '$total', '0', '$idr_total', '0', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";
		insert_log($queryss2, $user);

		$sqlcoa2 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_debit%' Limit 1");
		$rowcoa2 = mysqli_fetch_array($sqlcoa2);
		$no_coa_deb = $rowcoa2['no_coa'];
		$nama_coa_deb = $rowcoa2['nama_coa'];

		$queryss3 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
		VALUES 
		('$no_bpb', '$tgl_bpb', 'AP - BPB RETURN', '$no_coa_deb', '$nama_coa_deb', '-', '-', '-', '', '-', '-', '$curr', '$rate', '0', '$dpp', '0', '$idr_dpp', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";
		insert_log($queryss3, $user);

		if ($tax >= 1) {
			$sqlcoa3 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where inv_type like '%PPN MASUKAN%' Limit 1");
			$rowcoa3 = mysqli_fetch_array($sqlcoa3);
			$no_coa_ppn = $rowcoa3['no_coa'];
			$nama_coa_ppn = $rowcoa3['nama_coa'];


			$queryss4 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
			VALUES 
			('$no_bpb', '$tgl_bpb', 'AP - BPB RETURN', '$no_coa_ppn', '$nama_coa_ppn', '-', '-', '-', '', '-', '-', '$curr', '$rate', '0', '$ppn', '0', '$idr_ppn', 'Approved', '$description', '$username', '$dateinput', '', '', '', '')";

			insert_log($queryss4, $user);
		}

	}

	$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
	echo "<script>window.location.href='index.php?mod=$mod&mode=$mode&tgldt=$cridt';</script>";
}
