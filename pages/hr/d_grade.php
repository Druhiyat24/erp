<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$mode="";
$titlenya="Data Master Grade";


$carikode = mysql_query("SELECT kdgrade from hr_mastergrade");
    $datakode = mysql_fetch_array($carikode);
    $jumlah_data = mysql_num_rows($carikode);

    $nilaikode = substr($jumlah_data[0], 4);
    $kode = (int) $nilaikode;
    $kode = $jumlah_data + 1;
    $kode_otomatis = "GE-".str_pad($kode, 4, "0", STR_PAD_LEFT);

if (isset($_GET['id'])) { $idgrade=$_GET['id']; } else { $idgrade=""; }
if (isset($_GET['pro'])) { $pro=$_GET['pro']; } else { $pro=""; }
# COPAS EDIT
if ($idgrade=="")
{ $kdgrade = "";
  $dep   ="";
  $bagian="";
  $line  ="";
  $grade ="";
  $nama = "";
}
else
{ $query = mysql_query("SELECT * FROM hr_mastergrade 
    where kdgrade='$idgrade'");
  $data   = mysql_fetch_array($query);
  $kdgrade = $data['kdgrade'];
  $dep    = $data['dep'];
  $bagian = $data['bagian'];
  $line   = $data['line'];
  $grade  = $data['grade'];
  
  if ($pro=="Copy") { $n=""; }
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "
  <script type='text/javascript'>
  function validasi()
  { var kdgrade = document.form.txtkdgrade.value;
    var dep     = document.form.txtdep.value;
    var bagian  = document.form.txtbagian.value;
    var line    = document.form.txtline.value;
    var grade   = document.form.txtgrade.value;
  
    if (bagian == '') 
    { alert('Bagian tidak boleh kosong'); 
      document.form.txtbagian.focus();valid = false;
    }
    else if (grade == '') 
    { alert('grade tidak boleh kosong'); 
      document.form.txtgrade.focus();valid = false;
    }";
      echo "else valid = true;";
      echo "return valid;";
      echo "exit;";
  echo "}";
echo "</script>";
# END COPAS VALIDASI
if ($mod=="37") {
?>
<div class="box box">
  <?PHP
  # COPAS ADD
  echo "<div class='box-body'>";
  echo "<div class='row'>";
  echo "<form method='post' name='form' action='s_grade.php?id=$idgrade' onsubmit='return validasi()'>";
  echo "<div class='col-md-3'>";
  echo "<div class='form-group'>";
    echo "<label>Kode Grade </label>";
    echo "<input type='text' class='form-control' name='txtkdgrade'  value='$kode_otomatis' readonly>";
  echo "</div>";

  echo "
    <div class='form-group'>
      <label>Kode Bagian *</label>";
  echo "<select class='form-control select2' style='width: 100%;' onchange='changeValue(this.value)'>";
  echo "<option >Pilih Kode Bagian</option>";
      $query = mysql_query("SELECT * from hr_masteremployee GROUP BY bagian ORDER BY bagian ASC");
      $jsArray = "var prdName = new Array();\n";
            while ($row = mysql_fetch_assoc($query)) {
        
  echo "<option value=$row[nik] >$row[bagian]</option>";
      $jsArray .= "prdName['" .$row['nik']. "'] = {
          nama:'".addslashes($row['nama'])."',
          bagian:'".addslashes($row['bagian'])."',
          line:'".addslashes($row['line'])."',
          dept:'".addslashes($row['department'])."',};\n";
        }
  echo "</select></div>";

   echo "<div class='form-group'>";
    echo "<label>Bagian </label>";
  echo "<input type='text' class='form-control'   name='txtbagian'  id='bagian' value='$bagian' >";
  echo "</div>";

  //  echo "<div class='form-group'>";
  //   echo "<label>Nama Karyawan </label>";
  //   echo "<select multiple class='form-control' id='nama'>";
  //   echo "<option id='nama'></option>";
  //   echo "</select>";
  // echo "</div>";

  echo "</div>";

  echo "<div class='col-md-6'>";


  echo "<div class='form-group'>";
    echo "<label>Department </label>";
    echo "<input type='text' class='form-control' id='dept' name='txtdep' value='$dep' readonly>";
  echo "</div>";

  
  echo "<div class='form-group'>";
    echo "<label>Grade </label>";
    echo "<input type='text' class='form-control' name='txtgrade' value='$grade' >";
  echo "</div>";
  
 echo "<div class='form-group'>";
    echo "<label>Line </label>";
    echo "<input type='text' class='form-control' name='txtline' id='line' value='$line' readonly>";
  echo "</div>";


   echo "<div class='box-footer'>";
  echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
  echo "</div>"; 
  echo "</form>";
  echo "</div>";

 
 
  echo "</div>";

   echo "<div id='detail_item'></div>";
// echo "<div class='box-body'>
//       <table id='example1' class='display responsive' style='font-size:11px;'>
//           <thead>
//           <tr>
//               <th>No</th>
//               <th>Nama Karyawan</th>
//               <th>Bagian</th>
//               <!-- <th width='10%'>Aksi</th> -->
//           </tr>
//           </thead>
//           <tbody>";
            
//             # QUERY TABLE
//             $query = mysql_query("SELECT nama, bagian from hr_masteremployee ORDER BY nama ASC");
//             $no = 1; 
//             while($data = mysql_fetch_array($query))
//             { echo "<tr>";
//                 echo "<td>$no</td>"; 
//                 echo "<td>$data[nama]</td>";
//                 echo "<td>$data[bagian]</td>";
              
//               echo "</tr>";
//               $no++; // menambah nilai nomor urut
//             }
            
//           echo "</tbody>";
//       echo "</table>";
//       echo "</div>";

} else {
  # END COPAS ADD
  ?>
  <div class="box">
  <div class="box-header">
      <h3 class="box-title">List <?php echo $titlenya; ?></h3>
      <a href='../hr/?mod=37' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
  </div>
  <div class="box-body">
      <table id="example1" class="display responsive" style="font-size:11px;">
          <thead>
          <tr>
              <th>No</th>
              <th>Nama Karyawan</th>
              <th>Department</th>
              <th>Bagian</th>
              <th>Line</th>
              <th>Grade</th>
              <th width='10%'>Aksi</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            $query = mysql_query("SELECT a.nama, a.department, a.bagian, a.line, b.grade, b.kdgrade FROM hr_masteremployee a INNER JOIN hr_mastergrade b where a.bagian=b.bagian ORDER BY bagian DESC");
            $no = 1; 
            while($data = mysql_fetch_array($query))
            { echo "<tr>";
                echo "<td>$no</td>"; 
                echo "<td>$data[nama]</td>";
                echo "<td>$data[department]</td>";
                echo "<td>$data[bagian]</td>";
                echo "<td>$data[line]</td>";
                echo "<td>$data[grade]</td>"; 
              echo "<td>
               <a href='?mod=37&id=$data[kdgrade]'$tt_ubah</a>
                </td>";
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
      </table>
      </div>
      <?php } ?>
<script type="text/javascript"> 
<?php echo $jsArray; 
   // var_dump($jsArray);
   //         die();
?>


function changeValue(val)
{
  // console.log(id);
 var html = $.ajax({    
        type:"POST",
        url:"ajax_group_nm_kyr.php", 
        data : "val=" +val,
        async:false
    }).responseText;
    if(html)
    {
      $("#detail_item").html(html);

    }
 
  // document.getElementById('nama').value = prdName[val].nama;
  document.getElementById('bagian').value = prdName[val].bagian;
  document.getElementById('dept').value = prdName[val].dept;
  document.getElementById('line').value = prdName[val].line;

  
};
</script>
