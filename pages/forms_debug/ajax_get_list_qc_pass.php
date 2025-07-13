<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";
include "../forms/id.php";

$user=$_SESSION['username'];
$cub="Ubah";
$mod=$_REQUEST['modnya'];
$mode=$_REQUEST['modenya'];
$rsc=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rsc["company"];
  $st_company = $rsc["status_company"];
  $jenis_company = $rsc["jenis_company"];
  $pr_need_app = $rsc["pr_need_app"];
  $print_sj = $rsc['print_sj'];
  $logo_company = $rsc['logo_company'];
$peri=$_REQUEST['perinya'];
if ($mode=="FG")
{ $fldnyacri=" left(bpbno,2)='FG' and ifnull(a.id_so_det,'')='' "; $mod2="55"; }
else if ($mode=="Mesin")
{ $fldnyacri=" left(bpbno,1) in ('M','N') "; $mod2=53; }
else if ($mode=="Scrap")
{ $fldnyacri=" left(bpbno,1) in ('S','L') "; $mod2=52; }
else if ($mode=="WIP")
{ $fldnyacri=" left(bpbno,1)='C' "; $mod2=54; }
else 
{ $fldnyacri=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' "; $mod2=51; }
  
$first_day=date("Y-m-01",strtotime($peri));
$last_day=date("Y-m-t",strtotime($peri));
?>
<table id="examplefix3" class="display responsive" style="width:100%">
  <thead>
    <tr>
      <th>Nomor BPB</th>
      <th>Tanggal BPB</th>
      <th>Pemasok</th>
      <th>No. Invoice</th>
      <th>No. Dokumen</th>
      <th>Jenis BC</th>
      <th>Check Qty</th>
      <th>Check By</th>
      <th>Check Date</th>
      <th>Check Status</th>
      <th>Defect</th>
      <!-- <th></th>
      <th></th> -->
    </tr>
  </thead>
  <tbody>
    <?php
    # QUERY TABLE
    if ($mode=="FG") { $tbl_mst="masterstyle"; $fld_desc="s.itemname"; } else { $tbl_mst="masteritem"; $fld_desc="s.itemdesc"; }
    $sql="SELECT a.*,s.goods_code,$fld_desc itemdesc,supplier,mdef.nama_defect 
      FROM bpb a inner join $tbl_mst s on a.id_item=s.id_item 
      inner join po_header poh on a.pono=poh.pono 
      inner join mastersupplier ms on a.id_supplier=ms.id_supplier  
      left join master_defect mdef on a.id_defect=mdef.id_defect 
      where $fldnyacri and a.id_po_item!='' and a.id_jo!='' and dicekqc!='N' 
      and bpbdate between '$first_day' and '$last_day'   
      order by bpbdate desc";
    #echo $sql;
    $query = mysql_query($sql);
    while($data = mysql_fetch_array($query))
    { echo "<tr>";
      if($data['bpbno_int']!="")
      { echo "<td>$data[bpbno_int]</td>"; }
      else
      { echo "<td>$data[bpbno]</td>"; }
      echo "
        <td>$data[bpbdate]</td>
        <td>$data[supplier]</td>
        <td>$data[invno]</td>
        <td>$data[bcno]</td>
        <td>$data[jenis_dok]</td>
        <td>$data[dicekqc_qty]</td>
        <td>$data[dicekqc_by]</td>
        <td>$data[dicekqc_date]</td>";
        if($data['dicekqc']=="Y")
        { echo "<td>Pass</td>"; }
        elseif($data['dicekqc']=="R")
        { echo "<td>Reject</td>"; }
        elseif($data['dicekqc']=="C")
        { echo "<td>Pass With Condition</td>"; }
        else
        { echo "<td>Not Check</td>"; }
        echo "<td>$data[nama_defect]</td>";
        // <td>
        //   <a href='?mod=$mod2&mode=$mode&noid=$data[bpbno]'
        //     data-toggle='tooltip' title='$cub'><i class='fa fa-pencil'></i>
        //   </a>
        // </td>"; 
        // if ($print_sj=="1")
        // { echo "
        //   <td>
        //     <a href='cetaksj.php?mode=In&noid=$data[bpbno]' 
        //       data-toggle='tooltip' title='Cetak'><i class='fa fa-print'></i>
        //     </a>
        //   </td>"; 
        // }
        // else
        // { echo "<td></td>"; }
      echo "</tr>";
    }
    ?>
  </tbody>
</table>