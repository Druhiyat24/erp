<?php
session_start();
include "../../include/conn.php";
include "fungsi.php";

$titlenya="Bahan Baku";
$sql="select * from mastercompany";
$rsc=mysql_fetch_array(mysql_query($sql));
$nm_company = $rsc["company"];
$st_company = $rsc["status_company"];
$upload_image = $rsc["upload_image"];
$rkartu_stock = $rsc["kartu_stock"];

$user=$_SESSION['username'];
$group=$_REQUEST['cri_item'];
$rsuser = mysqli_fetch_array(mysqli_query($con_new,"select non_aktif_master,aktif_master from userpassword where username='$user'"));
$akses_non = $rsuser['non_aktif_master'];
$akses_akt = $rsuser['aktif_master'];

$mod="2L";
$cl_hapus="";
$tt_hapus="data-toggle='tooltip' title='Hapus'";
$tt_hapus2="<i class='fa fa-trash-o'></i>";
$cl_ubah="";
$tt_ubah="data-toggle='tooltip' title='Edit'><i class='fa fa-pencil'></i>";
$cl_hist="";
$tt_hist="data-toggle='tooltip' title='History'><i class='fa fa-history'></i>";
$cl_attach="";
$tt_attach="data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i>";

$mode=$_GET['mode'];
if($mode=="Bahan_Baku")
{
  $filternya="mi.mattype in ('A','F','B')";
}
else if($mode=="Scrap")
{
  $filternya="mi.mattype in ('S','L')";
}
else if($mode=="WIP")
{
  $filternya="mi.mattype in ('C')";
}
else if($mode=="Mesin")
{
  $filternya="mi.mattype in ('M')";
}
else
{
  $filternya="mi.mattype in ('A','F','B')";
}
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
      <th>Kode <?PHP echo $titlenya;?></th>
      <th><?php echo 'Deskripsi '.$titlenya; ?></th>
      <th>Color</th>
      <th>Size</th>
      <th>Add Info</th>
      <th>HS Code</th>
      <th>Mat Source</th>
      <th>Kode Lama</th>
      <th>Desc Lama</th>
      <?php if ($nm_company=="PT. Sandrafine Garment") { ?>
      <th>Stock Card</th>
      <th>Klasifikasi</th>
      <?php } ?>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
  </tr>
  </thead>
    <tbody>
      <?php
      # QUERY TABLE
      $sql="SELECT mi.*,mhs.kode_hs,odo.goods_code kode_odo,
	      odo.itemdesc desc_odo FROM masteritem mi left join masterhs mhs on mi.hscode=mhs.id 
	      left join masteritem_odo odo on mi.id_item_odo=odo.id_item_odo  
	     	where $filternya and mi.matclass='$group' ORDER BY mi.id_item DESC";
      #echo $sql;
      $query = mysql_query($sql);
      $no = 1; 
      while($data = mysql_fetch_array($query))
      { echo "
        <tr>
          <td>$no</td>
          <td>$data[goods_code]</td>
          <td>$data[itemdesc]</td>"; 
        if ($mode!="Mesin")
        { echo "
          <td>$data[color]</td>
          <td>$data[size]</td>
          <td>$data[add_info]</td>"; 
        }
        echo "<td>$data[kode_hs]</td>";
        if($data['base_supplier']=="I")
          { $m_sour="IMPORT"; }
        else if($data['base_supplier']=="L")
          { $m_sour="LOKAL"; }
        else
          { $m_sour=""; }
        echo "<td>$m_sour</td>";
        echo "<td>$data[kode_odo]</td>";
        echo "<td>$data[desc_odo]</td>";
        if ($nm_company=="PT. Sandrafine Garment")
        { echo "<td>$data[stock_card]</td>";
          echo "<td>$data[matclass]</td>";
        }
        if($mod!="22L") 
        { 
          if($data['non_aktif']=="N")
          { 
            echo "
            <td>
              <a $cl_ubah href='../forms/?mod=2&mode=$mode&id=$data[id_item]'
                $tt_ubah
              </a>
            </td>";
            echo "
            <td>
              <a $cl_hapus href='del_data.php?mode=$mode&id=$data[id_item]'
                $tt_hapus";?> 
                onclick="return confirm('Apakah anda yakin akan menghapus ?')">
                <?php echo $tt_hapus2."</a>
            </td>";
            if ($rkartu_stock=="1")
            { echo "
              <td>
                <a $cl_hist href='index.php?mod=14&mode=$mode&id=$data[id_item]'
                  $tt_hist
                </a>
              </td>";
            }
            else
            { echo "<td></td>"; }
            if ($upload_image=="1")
            { echo "
              <td>
                <a href='#' class='img-prev $cl_attach' data-id=$data[id_item]
                data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i>
                </a>
              </td>";
            }
            else
            { echo "<td></td>"; }
            if($akses_non=="1")
            {
              echo "
              <td><a href='../forms/non_akt.php?mod=$mod&mode=$mode&id=$data[id_item]'
                data-toggle='tooltip' title='Non Aktif' ";?> 
                onclick="return confirm('Apakah Anda Yakin Akan Meng-Non Aktifkan ?')"><?php echo "<i class='fa fa-eye-slash'></i></a>
              </td>";
            }
            else
            {
              echo "
              <td></td>";
            }
          }
          else
          { 
            echo "
            <td>Non Aktif</td>";
            if($akses_akt=="1")
            {
              echo "
              <td><a href='../forms/akt.php?mod=$mod&mode=$mode&id=$data[id_item]'
                data-toggle='tooltip' title='Aktifkan' ";?> 
                onclick="return confirm('Apakah Anda Yakin Akan Meng-Aktifkan Kembali ?')"><?php echo "<i class='fa fa-eye'></i></a>
              </td>";
            }
            else
            {
              echo "
              <td></td>";
            }
            echo "
            <td></td>
            <td></td>
            <td></td>";
          }
        }
        else
        { echo "
          <td></td>
          <td></td>
          <td></td>
          <td></td>"; 
        }
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
