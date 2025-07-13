<?php
include "../../include/conn.php";
include "fungsi.php";

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$nm_company=$rscomp["company"];
	$st_company=$rscomp["status_company"];
	$status_company=$rscomp["jenis_company"];
	$jenis_company=$rscomp["jenis_company"];
	$logo_company=$rscomp["logo_company"];
	$modenya = $_GET['modeajax'];
#echo $modenya;

if ($modenya=="view_list_tipe")
{ 
  $tipe_data 	= $_REQUEST['tipe_data'];
  if ($tipe_data == 'PENERIMAAN DETAIL')
  {
  $sql = "
	select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
				  when 'A' then 'ACCESSORIES'
				  end tampil
				  from masteritem where mattype in ('A')							
	";
  }
  else if ($tipe_data == 'PENGELUARAN DETAIL')
  {
  $sql = "
	select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
				  when 'A' then 'ACCESSORIES'
				  end tampil
				  from masteritem where mattype in ('A')							
	";
  }
  else if ($tipe_data == 'MUTASI DETAIL')
  {
  $sql = "
	select distinct(mattype) isi, case mattype when 'F' then 'FABRIC'
				  when 'A' then 'ACCESSORIES'
				  end tampil
				  from masteritem where mattype in ('A')							
	";
  }	  	  	
  else
  {
	$sql = "";
  }
  IsiCombo($sql,'','Pilih Tipe');
}
?>

  
