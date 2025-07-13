<?php
include "../../include/conn.php";
include "../forms/fungsi.php";
// include "../forms/func_cek_po_over_allow.php";

$mode="";
$mod="";
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
	$jenis_company=$rscomp["jenis_company"];
	$allow_bpb=$rscomp["allowance_bpb"];
	$whs_input_bc_dok = $rscomp['whs_input_bc_dok'];
  $whs_see_price = $rscomp['whs_see_price'];
  if($whs_see_price=="N")
  { $hidepx = "hidden"; }
  else
  { $hidepx = "text"; }
$modenya = $_GET['modeajax'];
$crinya = $_REQUEST['ponya'];
if($modenya=="get_qty_over")
{
	$ponya = $crinya;
	$sql="select a.pono,mi.id_item from po_header a inner join po_item s on a.id=s.id_po 
		inner join masteritem mi on s.id_gen=mi.id_gen where a.pono='$ponya' 
		and po_over='Y'  
		group by mi.id_item";
	// var_dump($sql);
  $ressql=mysql_query($sql);
	$cekover = 0;
  while($rspo=mysql_fetch_array($ressql))
	{
		$qtyover = flookup("sum(a.qty_over)","bpb a 
      left join (select bpbno,id_po_item from bpb_over group by bpbno,id_po_item) s on 
      a.bpbno=s.bpbno and a.id_po_item=s.id_po_item","a.qty_over>0 and a.id_item='$rspo[id_item]' 
      and a.pono='$rspo[pono]' and s.bpbno is null ");
	  $cekover = $cekover + $qtyover;
  }
	print(round($cekover,2));
}
if($modenya=="view_list_po")
{
  $ponya  = $crinya;
  $sqlbpb = "select group_concat('`',bpbno,'`') bpbno,id_po_item from bpb 
    where pono='$ponya' and qty_over>0 order by bpbno desc";
  $rsbpb = mysqli_fetch_array(mysqli_query($con_new,$sqlbpb));
    $bpbno1 = $rsbpb["bpbno"];
    $bpbno = str_replace("`","'",$bpbno1);
  if($ponya!="")
  { 
    ?>
    <table id="examplefix2" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
        <tr>
          <th>Nomor BPB</th>
          <?php if($jenis_company=="VENDOR LG") { ?>
            <th>Nomor JO</th>
          <?php } else { ?>
            <th>Nomor WS</th>
          <?php } ?> 
          <th>Kode Bahan Baku</th>
          <th>Deskripsi</th>
          <th>Satuan</th>
          <th>Qty PO</th>
          <th>Qty BPB</th>
          <th>Qty Tollerance</th>
          <th>Qty Over</th>
          <th>FOC</th>
          <th>Retur</th>
          <th>Add PO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $jenispo=flookup("jenis","po_header","pono='$ponya'");
        if ($jenis_company=="VENDOR LG" or $jenispo=="N")
        { $sql_join=" s.id_gen=d.id_item "; }
        else
        { $sql_join=" s.id_gen=d.id_gen "; }
        if($jenispo=="N")
        { 
          $sql="select tmpbpb2.nomor_rak,tmpbpb2.berat_bersih,tmpbpb2.berat_kotor,tmpbpb2.remark,
          tmpbpb2.id line_item,d.id_item,'' kpno,d.goods_code,d.itemdesc,s.qty,s.unit, 
          s.id_jo,tmpbpb2.price,tmpbpb2.curr,tmpbpb.qty_bpb,tmpbpb2.qty_bpb qty_bpb2,
          tmpbpb2.id_po_item,tmpbpb2.id_line from po_header a inner join po_item s on a.id=s.id_po 
          inner join masteritem d on $sql_join 
          left join 
          (select id,id_po_item,sum(qty) qty_bpb from bpb where pono='$ponya' and bpbno not in ($bpbno) group by id_po_item) tmpbpb 
          on tmpbpb.id_po_item=s.id  
          inner join 
          (select curr,price,id_po_item,sum(qty) qty_bpb,nomor_rak,berat_bersih,berat_kotor,remark,id id_line from bpb 
            where pono='$ponya' and bpbno in ($bpbno) group by id_po_item) tmpbpb2 
          on tmpbpb2.id_po_item=s.id  
          where a.pono='$ponya' and s.cancel='N' 
          order by s.id ";
        }
        else
        { 
          $sql="select tmpbpb2.id_bpb,a.pono,jo.jo_no,tmpbpb2.nomor_rak,tmpbpb2.berat_bersih,tmpbpb2.berat_kotor,tmpbpb2.remark,
            tmpbpb2.id_line line_item,d.id_item,ac.kpno,d.goods_code,d.itemdesc,s.qty,s.unit, 
            s.id_jo,tmpbpb2.price,tmpbpb2.curr,tmpbpb.qty_bpb,tmpbpb2.qty_bpb qty_bpb2,
            tmpbpb2.id_po_item,tmpbpb2.id_line,sum(tmpbpb2.qty_over) qty_over,tmpbpb2.trxno,tmpbpb2.bpbno 
            from po_header a inner join po_item s on a.id=s.id_po 
            inner join masteritem d on $sql_join inner join jo_det jod on s.id_jo=jod.id_jo 
            inner join jo on jod.id_jo=jo.id 
            inner join so on jod.id_so=so.id
            inner join act_costing ac on so.id_cost=ac.id 
            inner join 
            (select a.id id_bpb,a.bpbno,if(a.bpbno_int!='',a.bpbno_int,a.bpbno) trxno,sum(a.qty_over) qty_over,a.curr,a.price,
              a.id_po_item,sum(a.qty) qty_bpb,a.nomor_rak,a.berat_bersih,a.berat_kotor,a.remark,a.id id_line from bpb a 
              left join (select bpbno,id_po_item from bpb_over group by bpbno,id_po_item) s on 
              a.bpbno=s.bpbno and a.id_po_item=s.id_po_item 
              where a.pono='$ponya' and a.bpbno in ($bpbno) and a.qty_over>0 and s.bpbno is null  
              group by a.id_po_item,a.id) tmpbpb2 
            on tmpbpb2.id_po_item=s.id  
            left join 
            (select id_po_item,sum(qty) qty_bpb from bpb where pono='$ponya' and bpbno not in ($bpbno) group by id_po_item) tmpbpb 
            on tmpbpb.id_po_item=s.id  
            where a.pono='$ponya' and s.cancel='N' 
            group by s.id_gen order by s.id_gen,tmpbpb2.id_bpb";
        }
        #echo $sql;
        $i=1;
        $query=mysql_query($sql);
        while($data=mysql_fetch_array($query))
        { 
          $id=$data['id_item'].":".$data['line_item'];
          $qty_po = flookup("qty","po_item","id='$data[id_po_item]'");
          $qty_po_all = $qty_po + ($qty_po * $allow_bpb/100);
          $qty_po_all = round($qty_po_all,0,PHP_ROUND_HALF_UP);
          $qtybal=$data['qty'] - $data['qty_bpb'];
          $readtxt=" readonly ";
          $qtybpbn2=$data['qty_bpb2'];
          $qty_bal = $data['qty_bpb'] - $qty_po_all;
          $qty_bal = round($qty_bal,0,PHP_ROUND_HALF_UP);
          echo "
          <tr>";
            echo "<td>$data[trxno]</td>";
            echo "<td>
              <select name ='id_jo[$id]' id='id_jo$i' class='id_joclass'>";
              $sql="select a.id_po_item isi,concat(ac.kpno) tampil from 
                bpb a inner join jo_det d on a.id_jo=d.id_jo 
                inner join so on d.id_so=so.id 
                inner join act_costing ac on so.id_cost=ac.id  
                where a.pono='$data[pono]' and a.id_item='$data[id_item]' and a.qty_over>0 
                group by a.id_po_item";
              IsiCombo($sql,'','Pilih WS');
              echo "</select>
            </td>";
            echo "
            <td>$data[goods_code]</td>
            <td>$data[itemdesc]</td>
            <td>
              <input type ='hidden' name ='idline[$id]' value='$data[id_po_item]' id='idline$i'>
              <input type ='hidden' name ='idbpb[$id]' value='$data[bpbno]' id='idbpb$i'>
              <input type ='text' class='form-control' size='4' name ='unitpo[$id]' value='$data[unit]' id='unitpo$i' readonly>
            </td>
            <td>$qty_po</td>
            <td>
              <input type ='text' readonly size='6' $readtxt name ='qtybpb[$id]' 
                class='form-control qtyclass' id='qtybpb$i' value='$qtybpbn2'>
            </td>
            <td>$qty_bal</td>
            <td>
              <input type ='text' size='6' name ='qtyover[$id]' $readtxt 
                class='form-control qtyoverclass' id='qtyover$i' value='$data[qty_over]'>
            </td>
            <td>
              <input type ='text' size='6' name ='qtyfoc[$id]'  
                class='form-control qtyfocclass' id='qtyfoc$i'>
            </td>
            <td>
              <input type ='text' size='6' name ='qtyret[$id]'  
                class='form-control qtyretclass' id='qtyret$i'>
            </td>
            <td>
              <input type ='text' size='6' name ='qtyadd[$id]'  
                class='form-control qtyaddclass' id='qtyadd$i'>
            </td>
          </tr>";
          $i++;
        };
        ?>
      </tbody>
    </table>
  <?php 
  }
}
?>
