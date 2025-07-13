<?php
session_start();

include "../../include/conn.php";
include "../forms/fungsi.php";

$user = $_SESSION['username'];
$bpbnya = $_REQUEST['bpbnya'];
?>
<table id="examplefix3" class="display responsive" style="width:100%">
  <thead>
  <tr>
    <th>WS #</th>
    <th>Item</th>
    <th>Deskripsi</th>
    <th>Qty BPB</th>
    <th>Unit</th>
    <th>Total Masuk WS</th>
    <th>Total Keluar WS</th>
    <th>Trx Keluar WS</th>
    <th>Sisa WS</th>
    <th>Total Masuk</th>
    <th>Total Keluar</th>
    <th>Sisa</th>
  </tr>
  </thead>
  <tbody>
    <?php
    # QUERY TABLE
    if(substr($bpbnya,0,2)=="FG")
    {
      $query = mysqli_query($con_new,"select mi.id_so_det id_jo,a.id_item,ac.kpno,mi.goods_code,
        mi.itemname itemdesc,a.qty,a.unit,a.qty_over,mi.id_so_det id_gen      
        from bpb a inner join masterstyle mi on a.id_item=mi.id_item 
        left join so_det s on a.id_so_det=s.id  
        left join so on s.id_so=so.id 
        left join act_costing ac on so.id_cost=ac.id 
        where a.bpbno='$bpbnya' ");
    }
    else
    {
      $query = mysqli_query($con_new,"select a.id_jo,a.id_item,ac.kpno,mi.goods_code,
        mi.itemdesc,a.qty,a.unit,a.qty_over,mi.id_gen     
        from bpb a inner join masteritem mi on a.id_item=mi.id_item 
        left join jo_det s on a.id_jo=s.id_jo 
        left join so on s.id_so=so.id 
        left join act_costing ac on so.id_cost=ac.id 
        where a.bpbno='$bpbnya' "); 
    }
    $no = 1;
    while($data = mysqli_fetch_array($query))
    { 
      if(substr($bpbnya,0,2)=="FG")
      {
        $fil_bpb = "regexp 'FG'";
        $fld_jo = "id_so_det";
      }
      else
      {
        $fil_bpb = "not regexp 'FG'";
        $fld_jo = "id_jo";
      }
      $inall = flookup("sum(qty)","bpb","id_item='$data[id_item]' and bpbno $fil_bpb ");
      $outall = flookup("sum(qty)","bppb","id_item='$data[id_item]' and bppbno $fil_bpb ");
      $sisaall = $inall - $outall;
      $sisaall = round($sisaall,2);
      
      $rsbpb = mysqli_fetch_array(mysqli_query($con_new,"select 
        group_concat(distinct if(bpbno_int!='',bpbno_int,bpbno) separator ', ') det_in,sum(qty) tot_in from 
        bpb where cancel='N' and id_item='$data[id_item]' and $fld_jo='$data[id_jo]' and bpbno $fil_bpb "));
      $injo = $rsbpb['tot_in'];
      $injotrx = $rsbpb['det_in'];

      $rsbppb = mysqli_fetch_array(mysqli_query($con_new,"select 
        group_concat(distinct if(bppbno_int!='',bppbno_int,bppbno) separator ', ') det_out,sum(qty) tot_out from 
        bppb where cancel='N' and id_item='$data[id_item]' and $fld_jo='$data[id_jo]' and bppbno $fil_bpb "));
      $outjo = $rsbppb['tot_out'];
      $outjotrx = $rsbppb['det_out'];
      
      $sisajo = $injo - $outjo;
      $sisajo = round($sisajo,2);
      $cekro = flookup("count(*)","bppb","bpbno_ro='$bpbnya' and cancel='N'");
      $cekovertoll = flookup("qty_add","bpb_over","bpbno='$bpbnya'");
      if($cekovertoll>0)
      {
        $po_add = flookup("concat(pono,' ',podate)","po_header a inner join po_item s on a.id=s.id_po","pono regexp 'ADD' 
                and s.id_gen='$data[id_gen]' and s.id_jo='$data[id_jo]' and s.cancel='N'");  
      }
      if($data['qty']>$sisajo)
      {
        $ket="X";
      }
      else if($data['qty']>$sisaall)
      {
        $ket="X";
      }
      else if($cekro>0)
      {
        $ket="RO";
      }
      else if($po_add!="")
      {
        $ket="ADD";
      }
      else
      {
        $ket="";
      }   
      echo "
      <tr>
        <td>$data[kpno]</td>
        <td>$data[goods_code]</td>
        <td>$data[itemdesc]</td>
        <td>$data[qty]</td>
        <td>$data[unit]</td>
        <td>$injo</td>
        <td>$outjo</td>
        <td>$outjotrx $po_add</td>
        <td>$sisajo</td>
        <td>$inall</td>
        <td>$outall</td>
        <td>
          $sisaall
          <input type='hidden' name ='ket[$no]' class='ketcl' id='ket$no' value='$ket' readonly>
        </td>
      </tr>";
      $no++;
    }
    ?>
  </tbody>
</table>