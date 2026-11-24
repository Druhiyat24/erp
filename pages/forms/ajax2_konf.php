<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rscomp["company"];
$logo_company = $rscomp["logo_company"];
$jenis_company = $rscomp["jenis_company"];
$gr_po_need_app = $rscomp["gr_po_need_app"];
$modenya = $_GET['modeajax'];

if ($modenya == "cari_list_sj") {
	$crinya = $_REQUEST['cri_item'];
	$modnya = $_REQUEST['modnya'];
	if ($crinya != "") {
		$cridtnya = fd($crinya);
		// query daftar (bpb untuk mod 17, bppb untuk mod 17O) ada di fungsi.php, sama dengan yang dipakai konfirmasi.php
		$sql = sql_list_konfirmasi($modnya, $cridtnya);
		#var_dump($sql);
		IsiCombo($sql, '', 'Pilih Nomor Transaksi');
	}
}

if ($modenya == "view_list_sj") {
	$crinya = $_REQUEST['cri_item2'];
	if ($crinya != "") {
		$arrnya = explode(":", $crinya);
		$nm_tbl = $arrnya[0];
		$bppbno = $arrnya[1];
		if ($nm_tbl == "bpb") {
			$nm_fld = "bpbno";
			$add_cri = ",update_dok_pab";
			$nomorinput = "bpbno_int";
			$tgldok = "bpbdate";
		} else {
			$nm_fld = "bppbno";
			$add_cri = "";
			$nomorinput = "bppbno_int";
			$tgldok = "bppbdate";
		}
		if (substr($bppbno, 3, 2) == "FG" or substr($bppbno, 0, 2) == "FG") {
			$nm_mst = "masterstyle";
			$fld_mat = "'FG' mattype";
			$fld_desc = "s.itemname itemdesc";
		} else {
			$nm_mst = "masteritem";
			$fld_mat = "s.mattype";
			$fld_desc = "s.itemdesc";
		}

		$cekri = substr($bppbno, -1, 1);
		if ($nm_tbl == "bpb" && $cekri == "R") {
			$cekdata = flookup("bpbno_int", "bpb", "bpbno='$bppbno' limit 1");
			$cekmat = substr($cekdata, 0, 2);
			if ($cekmat == "GK") {
				$qty_fix = "a.qty_temp";
			} else {
				$qty_fix = "a.qty";
			}
		} else {
			$qty_fix = "a.qty";
		}

		$sql = "select $nm_fld trans_no,$nomorinput no_input,$tgldok tgl_dok,a.id_jo,a.id_item,$fld_mat,s.goods_code,$fld_desc,s.color,$qty_fix qty,
			a.unit,a.confirm $add_cri, invno from $nm_tbl a inner join $nm_mst s 
			on a.id_item=s.id_item where $nm_fld='$bppbno' ";
		$sql1 = "select sum($qty_fix) as total,  $nm_fld trans_no,$nomorinput no_input,$tgldok tgl_dok,a.id_jo,a.id_item,$fld_mat,s.goods_code,$fld_desc,s.color,a.qty,
			a.unit,a.confirm $add_cri, invno from $nm_tbl a inner join $nm_mst s 
			on a.id_item=s.id_item where $nm_fld='$bppbno' ";
		// echo $sql;
		#echo $cekmat;
		// selain INHOUSE wajib sudah ada nomor daftar: data tetap tampil, tapi tombol Konfirmasi dimatikan
		$belum_daftar = belum_nomor_daftar($nm_tbl, $bppbno);
		if ($belum_daftar) {
			echo "<div style='margin-bottom:8px'><small class='text-danger'><i class='fa fa-exclamation-circle'></i>
				Nomor daftar masih kosong, lakukan update dokumen pabean terlebih dahulu sebelum konfirmasi.</small></div>";
		}
		echo "<table style='width: 100%;' id='examplefix2'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>No</th>";
		echo "<th>Nomor Input</th>";
		echo "<th>Tgl $nm_tbl</th>";
		echo "<th>Kode</th>";
		echo "<th>Deskripsi</th>";
		echo "<th>Qty SJ</th>";
		echo "<th>Satuan</th>";
		echo "<th>No SJ</th>";
		if ($nm_tbl == "bpb") {
			echo "<th>Detail Roll</th>";
			echo "<th>Update Dok</th>";
			echo "<th>Konfirmasi</th>";
		}
		if ($nm_tbl == "bppb") {
			echo "<th>Konfirmasi</th>";
		}
		echo "</tr>";
		echo "</thead>";
		///// RMN			
		$query1 = mysql_query($sql1);
		while ($data1 = mysql_fetch_array($query1)) {
			// echo "<tbody>"; echo "</tbody>";
			echo "<tfoot>";
			echo "<tr>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th>Total</th>";
			echo "<th>$data1[total]</th>";
			echo "<th></th>";
			if ($nm_tbl == "bpb") {
				echo "<th></th>";
				echo "<th></th>";
				echo "<th></th>";
			}
			if ($nm_tbl == "bppb") {
				echo "<th></th>";
			}
			echo "<th></th>";
			echo "</tr>";
			echo "</tfoot>";
		}
		// $('#select_all').change(function() {
		//   var checkboxes = $(this).closest('form').find(':checkbox');
		//   checkboxes.prop('checked', $(this).is(':checked'));
		// });
		// <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		////// RMN		
		$i = 1;
		$query = mysql_query($sql);
		while ($data = mysql_fetch_array($query)) {
			$id = $data['id_item'];
			$tgl = date('d-M-Y', strtotime($data['tgl_dok']));
			$no_trans = $data['trans_no'];
			echo "<tr>";
			echo "<td>$i</td>";
			echo "<td>$data[no_input]</td>";
			echo "<td>$tgl</td>";
			echo "<td>$data[goods_code]</td>";
			echo "<td>$data[itemdesc]</td>";
			echo "<td>$data[qty]</td>";
			echo "<td>$data[unit]</td>";
			echo "<td>$data[invno]</td>";
			if ($nm_tbl == "bpb" and $logo_company == "S") {
				$cekroll = flookup("bpbno", "bpb_roll_h", "bpbno='$data[trans_no]' and id_jo='$data[id_jo]' 
									and id_item='$data[id_item]'");
				if ($cekroll == "") {
					$cekroll = "N/A";
				} else {
					$cekroll = "Ok";
				}
				if ($data['update_dok_pab'] == "Y") {
					$upddok = "Ok";
				} else {
					$upddok = "N/A";
				}
				if ($cekroll == "Ok") {
					$status = "checked";
					$status_konf = "";
				} else {
					$status = "";
					if(strpos($data[no_input], 'GK') !== false) {
					$status_konf = "";
					}
					else{
					$status_konf = "disabled";
					}
				}
				echo "<td>$cekroll</td>";
				echo "<td>$upddok</td>";
				if ($data['confirm'] == "Y") {
					echo "<td>Confirmed</td>";
				} else {
					if (strpos($no_trans, 'C') !== false or strpos($no_trans, 'N') !== false or strpos($no_trans, 'M') !== false) {
						$status_konf = "";
						echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>";
					} else {
						echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' $status></td>";
					}
				}
			}
			if ($nm_tbl == "bppb" and $logo_company == "S") {
				echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>";
			}
				// if ($data['confirm']=="Y")
				// { echo "<td>Confirmed</td>"; }
				// else
				// {echo "<td><input type ='checkbox' name ='itemchk[$id]' id='chkajax$i' class='chkclass' checked></td>"; }
			;

			echo "</tr>";
			$i++;
		};
		echo "</table>";
		if ($belum_daftar) {
			$status_konf = "disabled title='Nomor daftar masih kosong'";
		}
		echo "<button type='submit' name='submit' $status_konf class='btn btn-primary'>Konfirmasi</button>";
	}
}
