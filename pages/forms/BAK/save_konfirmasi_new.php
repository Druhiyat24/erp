<?php
include '../../include/conn.php';
include 'fungsi.php';
session_start();
if (empty($_SESSION['username'])) {
	header("location:../../index.php");
}

$user = $_SESSION['username'];
$sesi = $_SESSION['sesi'];
$mod = $_GET['mod'];

if ($mod == 'simpan') {
	$tipe_konf	= nb($_POST['tipe_konf']);

	if ($tipe_konf == 'PENERIMAAN') {

		$cbotipe	= nb($_POST['cbotipe']);
		$dateinput	= date('Y-m-d H:i:s');

		if (!isset($_POST['itemchk'])) {
			$_SESSION['msg'] = "XTidak Ada Data Yang Harus Dikonfirmasi";
			echo "<script>window.location.href='index.php?mod=konfirmasi_new';</script>";
		} else {
			$ItemArray = $_POST['itemchk'];
			$BpbnoArray = $_POST['bpbno'];

			foreach ($ItemArray as $key => $value) {
				if ($value != "") {
					$txtbpbno 	= $BpbnoArray[$key];
					$sql = "update bpb set confirm_by='$user',confirm='Y',confirm_date='$dateinput' 
			where bpbno='$txtbpbno'";
					insert_log($sql, $user);

					$cekdata = mysql_query("select bpbno, bpbno_int, bpb.bpbdate, bpb.id_supplier, supplier, mattype, n_code_category, 
		if(matclass like '%ACCESORIES%','ACCESORIES',mi.matclass) matclass, bpb.curr, COALESCE(tax,0) tax,bpb.username, bpb.dateinput, 
		SUM(((qty - COALESCE(qty_reject,0)) * price) + (((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(tax,0) /100))) as total,SUM(((qty - COALESCE(qty_reject,0)) * price)) as dpp,SUM((((qty - COALESCE(qty_reject,0)) * price) * (COALESCE(tax,0) /100))) as ppn
		from bpb 
		inner join masteritem mi on bpb.id_item = mi.id_item
		inner join mastersupplier ms on bpb.id_supplier = ms.id_supplier
		left join po_header ph on bpb.pono = ph.pono
		where bpbno  = '$txtbpbno' and ph.app = 'A' group by bpbno");
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
					$dateinput_ = $databpb['dateinput'];

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
				('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_cre', '$nama_coa_cre', '-', '-', '-', '', '-', '-', '$curr', '$rate', '0', '$total', '0', '$idr_total', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";
					insert_log($queryss2, $user);

					$sqlcoa2 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where cus_ctg like '%$cust_ctg%' and mattype like '%$mattype%' and matclass like '%$matclass%' and n_code_category like '%$n_code_category%' and inv_type like '%bpb_debit%' Limit 1");
					$rowcoa2 = mysqli_fetch_array($sqlcoa2);
					$no_coa_deb = $rowcoa2['no_coa'];
					$nama_coa_deb = $rowcoa2['nama_coa'];

					$queryss3 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
				 VALUES 
					('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_deb', '$nama_coa_deb', '-', '-', '-', '', '-', '-', '$curr', '$rate', '$dpp', '0', '$idr_dpp', '0', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";
					insert_log($queryss3, $user);

					if ($tax >= 1) {
						$sqlcoa3 = mysqli_query($conn_li, "SELECT no_coa, nama_coa from mastercoa_v2 where inv_type like '%PPN MASUKAN%' Limit 1");
						$rowcoa3 = mysqli_fetch_array($sqlcoa3);
						$no_coa_ppn = $rowcoa3['no_coa'];
						$nama_coa_ppn = $rowcoa3['nama_coa'];


						$queryss4 = "INSERT INTO tbl_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
				VALUES 
				   ('$no_bpb', '$tgl_bpb', 'AP - BPB', '$no_coa_ppn', '$nama_coa_ppn', '-', '-', '-', '', '-', '-', '$curr', '$rate', '$ppn', '0', '$idr_ppn', '0', 'Approved', '$description', '$username', '$dateinput_', '$user', '$dateinput', '', '')";

						insert_log($queryss4, $user);
					} else {
					}
				}
				$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
				echo "<script>window.location.href='index.php?mod=konfirmasi_new';</script>";
			}
		}
	} else if ($tipe_konf == 'PENGELUARAN') {
		$cbotipe	= nb($_POST['cbotipe']);
		$dateinput	= date('Y-m-d H:i:s');
		if (!isset($_POST['itemchk'])) {
			$_SESSION['msg'] = "XTidak Ada Data Yang Harus Dikonfirmasi";
			echo "<script>window.location.href='index.php?mod=konfirmasi_new';</script>";
		} else {
			$ItemArray = $_POST['itemchk'];
			$BppbnoArray = $_POST['bppbno'];

			foreach ($ItemArray as $key => $value) {
				if ($value != "") {
					$txtbppbno 	= $BppbnoArray[$key];
					$sql = "update bppb set confirm_by='$user',confirm='Y',confirm_date='$dateinput' 
			where bppbno='$txtbppbno'";
					insert_log($sql, $user);
				}
				$_SESSION['msg'] = "Data Berhasil Dikonfirmasi";
				echo "<script>window.location.href='index.php?mod=konfirmasi_new';</script>";
			}
		}
	}
}
