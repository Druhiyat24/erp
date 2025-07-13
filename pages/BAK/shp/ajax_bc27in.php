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

if ($modenya=="view_list_invoice")
{ 
	$id_buyer = $_REQUEST['id_buyer'];	
	$sql = "select id isi, no_invoice tampil from tbl_book_invoice where id_customer = '$id_buyer' 
	and status != 'cancel' order by tgl_book_inv desc";
	IsiCombo($sql,'','');

}

if ($modenya=="view_list_invoice_data")
	{	$id_inv = json_encode($_REQUEST['id_inv']); }
if ($id_inv!="")
{	
	echo "<table id='examplefix2' style='width: 100%;'>";

	echo " 
	<thead>
	<tr>
	<th colspan = '11'>Rincian List Invoice</th>
	</tr>
	<tr>";
	echo "
	<th>No Invoice</th>
	<th>No SO</th>
	<th>No BPPB</th>
	<th>Shipment</th>
	<th>Reff No</th>
	<th>Style</th>
	<th>Style No Prod</th>
	<th>PO</th>
	<th>Season</th>
	<th>WS</th>
	<th>Qty</th>
	<th>No Dok</th>";
	echo "
	</tr>
	</thead>
	<tbody>"; 

	$id_inv = str_replace("[","",$id_inv);
	$id_inv = str_replace("]","",$id_inv);
	$sql="select book.id, no_invoice, so_number, shipp_number, shipp, bppb.qty, ac.styleno, sd.styleno_prod, ws , doc_number, reff_no, buyerno, season 
	from tbl_book_invoice book
	left join tbl_invoice_detail det on book.id = det.id_book_invoice
	left join bppb on det.id_bppb = bppb.id
	left join so_det sd on bppb.id_so_det = sd.id
	left join so on sd.id_so = so.id
	left join masterseason ms on so.id_season = ms.id_season
	left join act_costing ac on so.id_cost = ac.id
	where book.id in ($id_inv)";
				#echo $sql;
	$i=1;
	$query=mysql_query($sql);
	if (!$query) { die($sql. mysql_error()); }
	while($data=mysql_fetch_array($query))
	{
		$id = $data[id];	
		echo "
		<tr>";
		echo "
		</td>";
		echo "								  
		<td>
		<input type = 'checkbox'  style='display:none;' checked id = 'id_cek' name = 'id_cek[$id]' value = '$data[id]'> 
		<input type ='hidden' name ='no_invoice[$id]' value='$data[no_invoice]'>
		$data[no_invoice]
		</td>
		<td>$data[so_number]</td>
		<td>$data[shipp_number]</td>
		<td>$data[shipp]</td>
		<td>$data[reff_no]</td>
		<td>$data[styleno]</td>
		<td>$data[styleno_prod]</td>
		<td>$data[buyerno]</td>
		<td>$data[season]</td>
		<td>$data[ws]</td>
		<td>$data[qty]</td>	
		<td>$data[doc_number]</td>";
		echo "
		</td>
		</tr>";
		$i++;
	};

}

if ($modenya=="view_sub_category")
{ 
	$id_kat = $_REQUEST['id_kat'];	
	$sql_sub = "select id_sub_ctg isi, nm_sub_ctg tampil from master_memo_subctg where id_ctg = '$id_kat'";
	IsiCombo($sql_sub,'','');
	
}



if ($modenya == "get_alamat") { 
	$id_supplier = $_REQUEST['id_supplier'];	
	$sql = "SELECT alamat FROM mastersupplier WHERE id_supplier = '$id_supplier'";
	$res = mysql_query($sql);
	if ($row = mysql_fetch_assoc($res)) {
        echo $row['alamat']; // Ini yang dikembalikan ke JS
    } else {
        echo ''; // Jika tidak ditemukan
    }
    exit;
}

if ($modenya == 'simpan_temp')
{
	$item = $_REQUEST['item'];
	$satuan = $_REQUEST['satuan'];
	$qty = $_REQUEST['qty'];
	$price = $_REQUEST['price'];
	if ($item !="")
	{	

		$sql = "insert into exim_ceisa_temp (nama_item,satuan,qty,price,user) 
		values ('$item','$satuan','$qty','$price','$user')";
		insert_log($sql,$user);	
	}

	$sqlsaldo = mysql_query("select sum(qty * price) total from exim_ceisa_temp where user = '$user'");
	echo $sqlsaldo;
	$datasaldo = mysql_fetch_array($sqlsaldo);
	$saldo_sisa = $datasaldo['total'];

	echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
	echo "
	<thead>
	<tr>";
	echo "
	<th>No #</th>
	<th>Nama Barang</th>
	<th>Satuan</th>
	<th>Qty</th>
	<th>Price</th>
	<th>Total</th>
	<th>Act</th>";
	echo "
	</tr>
	</thead>
	<tbody>";
	$sql="select nama_item itemdesc, satuan, qty, price, round(qty * price,2) total, id from exim_ceisa_temp where user = '$user'";

	#echo $sql;
	$i=1;
	$query=mysql_query($sql);
	while($data=mysql_fetch_array($query))
		{	echo "
	<tr>";
	echo "
	<td>$i</td>
	<td>$data[itemdesc]</td>
	<td>$data[satuan]</td>
	<td>$data[qty]</td>
	<td>$data[price]</td>
	<td>$data[total]</td>
	<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
	echo "
	</tr>";
	$i++;
};
echo "</tbody>
<tfoot>
<tr>
<th colspan = '5' style='font-size: 12px' >Total : </th>
<td> <input type = 'text' readonly size='7' value = '$saldo_sisa'> </td>
<td></td>
</tr>
</tfoot>			
</table>";
}	

if ($modenya == 'delete_temp')
{
	$id_temp = $_REQUEST['id_temp'];
	if ($id_temp!="")
	{	
		$sql = "delete from exim_ceisa_temp where id = '$id_temp'";
		insert_log($sql,$user);

		$sqlsaldo = mysql_query("select sum(qty * price) total from exim_ceisa_temp where user = '$user'");
		echo $sqlsaldo;
		$datasaldo = mysql_fetch_array($sqlsaldo);
		$saldo_sisa = $datasaldo['total'];

		echo "<table id='examplefix1' class='display responsive' style='width: 100%;font-size:14px;'>";
		echo "
		<thead>
		<tr>";
		echo "
		<th>No #</th>
		<th>Nama Barang</th>
		<th>Satuan</th>
		<th>Qty</th>
		<th>Price</th>
		<th>Total</th>
		<th>Act</th>";
		echo "
		</tr>
		</thead>
		<tbody>";
		$sql="select nama_item itemdesc, satuan, qty, price, round(qty * price,2) total, id from exim_ceisa_temp where user = '$user'";

	#echo $sql;
		$i=1;
		$query=mysql_query($sql);
		while($data=mysql_fetch_array($query))
			{	echo "
		<tr>";
		echo "
		<td>$i</td>
		<td>$data[itemdesc]</td>
		<td>$data[satuan]</td>
		<td>$data[qty]</td>
		<td>$data[price]</td>
		<td>$data[total]</td>
		<td><button type='button' name='delete_temp' class='btn btn-primary' onclick='del_item($data[id])'>Hapus</button></td>";
		echo "
		</tr>";
		$i++;
	};
	echo "</tbody>
	<tfoot>
	<tr>
	<th colspan = '5' style='font-size: 12px' >Total : </th>
	<td> <input type = 'text' readonly size='7' value = '$saldo_sisa'> </td>
	<td></td>
	</tr>
	</tfoot>			
	</table>";
}					
}	


if ($modenya == 'delete_upload') {
	$no_dok = $_POST['no_dokumen'];

	if (strpos($no_dok, 'UPL') !== false) {
        // Daftar tabel yang perlu di-backup
		$tables = [
			'exim_bahanbaku', 'exim_bahanbakudokumen', 'exim_bahanbakutarif', 'exim_bankdevisa',
			'exim_barang', 'exim_barangdokumen', 'exim_barangentitas', 'exim_barangspekkhusus',
			'exim_barangtarif', 'exim_barangvd', 'exim_dokumen', 'exim_entitas', 'exim_header',
			'exim_jaminan', 'exim_kemasan', 'exim_kontainer', 'exim_pengangkut', 'exim_pungutan', 'exim_versi'
		];

		$backup_success = true;

		foreach ($tables as $table) {
			$cancel_table = $table . '_cancel';

            // Ambil daftar kolom selain 'id'
			$cols_res = mysql_query("SHOW COLUMNS FROM $table");
			$columns = [];
			while ($row = mysql_fetch_assoc($cols_res)) {
				if ($row['Field'] != 'id') {
					$columns[] = "`" . $row['Field'] . "`";
				}
			}

			$column_list = implode(', ', $columns);

            // Insert ke tabel _cancel
			$sql_insert = "INSERT INTO $cancel_table ($column_list)
			SELECT $column_list FROM $table WHERE no_dokumen = '$no_dok'";
			if (!mysql_query($sql_insert)) {
				echo "Gagal backup dari tabel: $table<br>";
				$backup_success = false;
				break;
			}
		}

		if ($backup_success) {
            // Hapus data dari semua tabel asal
			foreach ($tables as $table) {
				$sql_delete = "DELETE FROM $table WHERE no_dokumen = '$no_dok'";
				mysql_query($sql_delete);
			}
			echo "Dokumen ".$no_dok." berhasil dihapus setelah backup.";
		} else {
			echo "Gagal membackup semua data. Penghapusan dibatalkan.";
		}

	} elseif (strpos($no_dok, 'INS') !== false) {
		$sql = "UPDATE exim_ceisa_manual SET status = 'CANCEL' WHERE no_dok = '$no_dok'";
		if (mysql_query($sql)) {
			echo "Dokumen ".$no_dok." berhasil dibatalkan.";
		} else {
			echo "Gagal membatalkan dokumen INS.";
		}

	} else {
		echo "Format no_dok tidak dikenali.";
		exit;
	}
}



?>