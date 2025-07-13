<?php
session_start();
include "../../include/conn.php";

$titlenya="Data Master Grade";

$user=$_SESSION['username'];
$group=$_REQUEST['val'];
$mod="37L";

$filternya="me.bagian in ('GENERAL AFFAIR','MARKETING - TEAM A','PURCHASING','MARKETING - TEAM C','RESEARCH & DEVELOPMENT','HUMAN RESOURCE & DEVELOPM','HEAT SEAL','INFORMATION TECHNOLOGY','MANAGEMENT - OFFICE','SAMPLE ROOM','FINISHING','MECHANIC','STEAM','COMPLIANCE','SEWING','CUTTING','DISTRIBUTION','QUALITY CONTROL','PACKING','BARTEX','PPIC','EXPEDITION','EXPORT & IMPORT','MARKETING - TEAM B','LABORATORY','FINANCE, ACCOUNTING & TAX','INDUSTRIAL ENGINEERING','MANAGEMENT - FACTORY','ACCESSORIES WAREHOUSE','FABRICS WAREHOUSE','SPOT CLEANING','MARKETING','DISTRIBUSI')";
$mode="Data Master Grade";

echo "
<head>
  <link rel='stylesheet' href='../../plugins/datatables_responsive/responsive.dataTables.min.css'>
  <link rel='stylesheet' href='../../plugins/datatables_responsive/jquery.dataTables.min.css'>";
echo "</head>";
?>
<table id="examplefix" width="100%" class="display responsive" style="font-size:10px;">
  <thead>
  <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Bagian</th>
      
  </tr>
  </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $query = mysql_query("SELECT a.nama, a.bagian, b.kdgrade FROM hr_masteremployee a INNER JOIN hr_mastergrade b WHERE a.bagian=b.bagian  ORDER BY bagian DESC");
      $no = 1; 
      while($data=mysql_fetch_array($query))
      { echo " 
        <tr>
          <td>$no</td>
          <td>$data[nama]</td>
          <td>$data[bagian]</td>";
        echo "</tr>";
        $no++; // menambah nilai nomor urut
      }
      ?>
    </tbody>
</table>
<script src="../../plugins/datatables_responsive/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables_responsive/dataTables.responsive.min.js"></script>
<script>
  $(document).ready(function() {
    var table = $('#examplefix').DataTable
    ({  scrollY: "300px",
        scrollCollapse: true,
        paging: true,
        sorting: false,
        pageLength: 20,
        fixedColumns:   
        { leftColumns: 1,
          rightColumns: 1
        }
    });
  });
</script>
