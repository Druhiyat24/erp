<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$rs=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company=$rs['company'];
if(isset($_GET['txtfrom']))
{
  $txtfrom = fd($_GET['txtfrom']);
  $txtto = fd($_GET['txtto']);
  $txtjenis = $_GET['txtjenis'];
}
else
{
  $txtfrom = fd($_POST['txtfrom']);
  $txtto = fd($_POST['txtto']);
  $txtjenis = $_POST['txtjenis'];
}
if($excel=="N") 
{ echo "<a href='?mod=rptrkonf&dest=xls&txtfrom=$txtfrom&txtto=$txtto&txtjenis=$txtjenis'>Save To Excel</a></br>"; }
if(isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=lap_konf.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
if($txtjenis=="Pemasukan")
{
  $nm_tbl = "bpb";
}
else
{
  $nm_tbl = "bppb";
}
$bppbno="A";
if($nm_tbl=="bpb") 
{
  $nm_fld="bpbno"; 
  $nm_fld2="bpbdate";
  $nm_fld3="if(bpbno_int!='',bpbno_int,bpbno)";
  $nm_fld3ori="bpbno";
  $nm_fld4="pono,invno";
  $add_cri=",update_dok_pab"; 
} 
else 
{
  $nm_fld="bppbno"; 
  $nm_fld2="bppbdate";
  $nm_fld3="if(bppbno_int!='',bppbno_int,bppbno)";
  $nm_fld3ori="bppbno";
  $nm_fld4="'' pono,'' invno";
  $add_cri=""; 
}
if(substr($bppbno,3,2)=="FG" or substr($bppbno,0,2)=="FG") 
{ $nm_mst="masterstyle";
  $fld_mat="'FG' mattype"; 
  $fld_desc="s.itemname itemdesc,'' kategori";
}
else
{ $nm_mst="masteritem"; 
  $fld_mat="s.mattype";
  $fld_desc="s.itemdesc,matclass kategori";
}
?>
<div class="box">
  <div class="box-body">
<?php 
echo "<table style='width: 100%;' class='display responsive' id='examplefix3' >";
  echo "<thead>";
    echo "<tr>";
      echo "<th>No</th>";
      echo "<th>Trans #</th>";
      echo "<th>Trans Date</th>";
      echo "<th>Created Date</th>";
      echo "<th>Kategori</th>";
      echo "<th>Kode</th>";
      echo "<th>Deskripsi</th>";
      echo "<th>Qty SJ</th>";
      echo "<th>Satuan</th>";
      echo "<th>WS #</th>";
      if($nm_tbl=="bpb")
      { 
        echo "<th>PO #</th>";
        echo "<th>SJ #</th>";
        echo "<th>Detail Roll</th>";  
        echo "<th>Update Dok</th>";
        echo "<th>Status</th>"; 
      }
      else
      {
        echo "<th>PO #</th>";
        echo "<th>Status</th>";
      }
    echo "</tr>";
  echo "</thead>";
  if($nm_tbl=="bppb")
  {
    $fld_add = ",tmppo.pono";
    $sql_join = " left join (select pono,d.id_item,s.id_jo from po_header a inner join po_item s on a.id=s.id_po 
      inner join masteritem d on s.id_gen=d.id_gen where s.cancel='N' 
      and a.jenis='M' group by d.id_item,s.id_jo) tmppo 
      on tmppo.id_item=a.id_item and tmppo.id_jo=a.id_jo ";
  }
  else
  {
    $fld_add = "";
    $sql_join = "";
  }
  $sql="select $nm_fld3 trans_no,$nm_fld3ori trans_no_ori,$nm_fld2 trans_date,a.id_jo,a.id_item,$fld_mat,s.goods_code,$fld_desc,s.color,a.qty,
    a.unit,a.confirm $add_cri,ac.kpno,$nm_fld4,a.dateinput $fld_add from $nm_tbl a inner join $nm_mst s 
    on a.id_item=s.id_item 
    left join (select id_jo,id_so from jo_det group by id_jo) jod on a.id_jo=jod.id_jo 
    left join so on jod.id_so=so.id 
    left join act_costing ac on so.id_cost=ac.id 
    $sql_join 
    where $nm_fld2 between '$txtfrom' and '$txtto' 
    and cancel='N'";
  #echo $sql;
  $i=1;
  $query=mysql_query($sql);
  while($data=mysql_fetch_array($query))
  { 
    echo "<tr>";
      echo "<td>$i</td>";
      echo "<td>$data[trans_no]</td>";
      echo "<td>$data[trans_date]</td>";
      echo "<td>$data[dateinput]</td>";
      echo "<td>$data[kategori]</td>";
      echo "<td>$data[goods_code]</td>";
      echo "<td>$data[itemdesc]</td>";
      echo "<td>$data[qty]</td>";
      echo "<td>$data[unit]</td>";
      echo "<td>$data[kpno]</td>";
      if($data['confirm']=="Y")
      { 
        if($nm_tbl=="bpb")
        {
          echo "<td>$data[pono]</td>";
          echo "<td>$data[invno]</td>";
          echo "<td>Confirmed</td>"; 
          echo "<td>Confirmed</td>";
          echo "<td>Confirmed</td>";
        }
        else
        {
          echo "<td>$data[pono]</td>";
          echo "<td>Confirmed</td>";
        }
      }
      else
      {
        if($nm_tbl=="bpb")
        { 
          echo "<td>$data[pono]</td>";
          echo "<td>$data[invno]</td>";
          $cekroll=flookup("bpbno","bpb_roll_h","bpbno='$data[trans_no_ori]' and id_jo='$data[id_jo]' 
            and id_item='$data[id_item]'");
          if($cekroll=="") { $cekroll="N/A"; } else { $cekroll="Ok"; }
          if($data['update_dok_pab']=="Y") { $upddok="Ok"; } else { $upddok="N/A"; }
          echo "<td>$cekroll</td>";
          echo "<td>$upddok</td>";
          echo "<td>Not Confirmed</td>";  
        }
        else
        {
          echo "<td>Not Confirmed</td>";
        }
      }
    echo "</tr>";
    $i++;
  };
echo "</table>";
?>
  </div>
</div>