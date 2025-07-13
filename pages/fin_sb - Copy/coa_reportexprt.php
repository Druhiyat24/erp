<?php
// Skrip berikut ini adalah skrip yang bertugas untuk meng-export data tadi ke excell
$thisday=date('Y-m-d');
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=BAGAN AKUN $thisday.xls");

include_once '../../include/conn.php';
// Assets class (image, css, etc..)


/** Include PHPExcel */
#require_once '../../include/PHPExcel/Classes/PHPExcel.php';
$styleborder="border: 1px solid black;";
$images = '../../include/img-01.png';
      
$sql = mysql_query("SELECT id_coa,nm_coa,nm_map from mastercoa,mastercoacategory where mastercoa.map_category=mastercoacategory.id_map and fg_active='1' and fg_posting='0'  ")or die(mysql_error());


?>



<h3>Bagan Akun COA </h3>
<h5> </h5>
            
<table border="1" cellpadding="5">
	<thead>
        <tr style='background-color: yellow; text-align:left; color:Black; font-weight:bold;'>
            <th >NO</th>
            <th >COA ID</th>
            <th >ACCOUNT</th>
            <th >CATEGORY</th>
        </tr>
        
    </thead>

      <?php
      

      // Buat query untuk menampilkan semua data 
           
      $no=1; // Untuk penomoran tabel, di awal set dengan 1
      while ($row_coa = mysql_fetch_array($sql)) { // Ambil semua data dari hasil eksekusi $sql

            echo "<tr>";
            echo "<td width='40' style='text-align:center'>".$no."</td>";
            echo "<td width='70'> ".$row_coa['id_coa']." </td>";
            echo "<td width='450'> ".$row_coa['nm_coa']." </td>";
            echo "<td width='400' > ".$row_coa['nm_map']." </td>";
            echo "</tr>";

            
            $no++; // Tambah 1 setiap kali looping
      }
      ?>
</table>

