<?php
include '../../conn/conn.php';
ini_set('date.timezone', 'Asia/Jakarta');

$no_pci = $_POST['no_pci'];
$tgl_pci =  date("Y-m-d",strtotime($_POST['tgl_pci']));
$bulan =  date("m",strtotime($_POST['tgl_pci']));
$tahun =  date("Y",strtotime($_POST['tgl_pci']));
$reff = $_POST['reff'];
$reff_doc = $_POST['reff_doc'];
$oth_doc = $_POST['oth_doc'];
$coa_akun = $_POST['coa_akun'];
$curr = $_POST['curr'];
$amount = $_POST['amount'];
$pesan = $_POST['pesan'];
$status = "Draft";
$create_by = $_POST['create_by'];
$create_date = date("Y-m-d H:i:s");



// echo "< -- >";
// echo $no_kbon;
// echo "< -- >";
// echo $tgl_kbon;
// echo "< -- >";
// echo $valuta_ftr;
// echo "< -- >";
// echo $ttl_bayar;
// echo "< -- >";
// echo $cara_bayar;
// echo "< -- >";
// echo $account;
// echo "< -- >";
// echo $bank;
// echo "< -- >";

$sqlnkb = mysqli_query($conn2,"select max(no_pci) from sb_c_petty_cashin_h where coa_akun = '$coa_akun' and YEAR(tgl_pci) = YEAR('$tgl_pci') AND MONTH(tgl_pci) = MONTH('$tgl_pci')");
 $rownkb = mysqli_fetch_array($sqlnkb);
 $kodeBarang = $rownkb['max(no_pci)'];
 $urutan = (int) substr($kodeBarang, 15, 5);
 $urutan++;
 $bln = $bulan;
 $thn = $tahun;
 $huruf = substr($no_pci,0,5);
 $kode = $huruf ."/". $thn."/".$bln ."/". sprintf("%05s", $urutan);

 $sqlx = mysqli_query($conn1,"select if(max(id) is null,'0',max(id)) as id FROM sb_c_report_pettycash where akun = '$coa_akun'");
$rowx = mysqli_fetch_array($sqlx);
$maxid = $rowx['id'];

$sqly = mysqli_query($conn1,"select if(sum(balance) is null,'0',sum(balance)) as balance from sb_c_report_pettycash where id = '$maxid'");
$rowy = mysqli_fetch_array($sqly);
$balance = $rowy['balance'];
$balance2 = $balance + $amount;

$sqlcoa = mysqli_query($conn1,"select nama_coa from mastercoa_v2 where no_coa = '$coa_akun'");
$rowcoa = mysqli_fetch_array($sqlcoa);
$nama_coa = $rowcoa['nama_coa'];


$query = "INSERT INTO sb_c_petty_cashin_h (no_pci,tgl_pci,reff,reff_doc,oth_doc,coa_akun,curr,amount,status, create_by,create_date,deskripsi) 
VALUES 
   ('$kode', '$tgl_pci', '$reff', '$reff_doc', '$oth_doc', '$coa_akun', '$curr', '$amount', '$status', '$create_by', '$create_date', '$pesan')";

   $queryss = "INSERT INTO sb_c_report_pettycash (transaksi_date,no_doc,deskripsi,akun,categori,cf_categori,curr,debit,credit, balance, status) 
VALUES 
   ('$tgl_pci', '$kode', '$pesan', '$coa_akun', '', '', '$curr','$amount', '0', '$balance2', '$status')";

$execute = mysqli_query($conn2,$query);
$executes = mysqli_query($conn2,$queryss);


$queryss2 = "INSERT INTO sb_list_journal (no_journal, tgl_journal, type_journal, no_coa, nama_coa, no_costcenter, nama_costcenter, reff_doc, reff_date, buyer, no_ws, curr, rate, debit, credit, debit_idr, credit_idr, status, keterangan, create_by, create_date, approve_by, approve_date, cancel_by, cancel_date) 
VALUES 
   ('$kode', '$tgl_pci', '$reff', '$coa_akun', '$nama_coa', '-', '-', '-', '', '-', '-', 'IDR', '1', '$amount', '0', '$amount', '0', 'Draft', '$pesan', '$create_by', '$create_date', '', '', '', '')";

$executess2 = mysqli_query($conn2,$queryss2);


if(!$execute){ 
   die('Error: ' . mysqli_error()); 
}else{
echo 'Data Saved Successfully With No Petty Cash In '; echo $kode;
if ($reff != 'Cash Out') {
   
}else{
$sql2 = "update sb_c_cash_out set stat_pci='Y' where no_co = '$reff_doc'";
$query2 = mysqli_query($conn2,$sql2);
}
   
}

mysqli_close($conn2);
?>