<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';

ob_start();

$bpbno=$_GET['id'];
$mode=$_GET['mode'];

//paging--------------------------

if ($jdata>=1 and $jdata<8)

{ $space_head=52; }

else

{ $space_head=55; }
//end paging------------------------------

if (substr($bppbno,0,2)=="FG" OR substr($bppbno,3,2)=="FG")

{ 

  $tbl_mst = "masterstyle"; 

  $fld_mst2 = "goods_code";

  if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(itemname,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $fld_mst = "concat('Style # ',s.styleno,' ',itemname,' Size ',size,' Warna ',color)"; }

  else

  { $fld_mst = "concat(goods_code,' - ',itemname)"; }

  $sql_join_fg = "so_det sod on sod.id=a.id_so_det 

    left join so on so.id=sod.id_so  

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3=",ifnull(ac.styleno,s.styleno)";

  $fld_add_4="ifnull(ac.kpno,s.kpno)";

  $fld_add_fg2=",ifnull(so.buyerno,s.buyerno),ifnull(sod.dest,'-'),ifnull(sod.color,s.color),ifnull(sod.size,s.size)";

  $fld_add_fg31="

  <td align='center'>Style #</td>";

  $fld_add_fg="

  <td align='center'>PO #</td>

  <td align='center'>Dest</td>

  <td align='center'>Color</td>

  <td align='center'>Size</td>";

  $jml_fld = 10;

  $jml_fld_sp = 8;

}

else

{ $tbl_mst = "masteritem"; 

  $fld_mst2 = "goods_code";

  if ($nm_company=="PT. Bangun Sarana Alloy")

  { $fld_mst = "concat(goods_code,' ',itemdesc,' Size ',size)"; }

  else if ($nm_company=="PT. Tun Hong")

  { $sql="update bpb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bpbno='$bppbno' "; 

    insert_log($sql,"");

    $sql="update bppb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bppbno='$bppbno' "; 

    insert_log($sql,"");

    $fld_mst = "concat('Style # ',a.styleno,' ',itemdesc,' Size ',size,' Warna ',color)"; 

  }

  else

  { if(substr($bppbno,0,1)=="N")

    {
      $fld_mst = "concat(goods_code,' ',itemdesc)";
    }
    else
    {
      $fld_mst = "concat(itemdesc,' ',add_info)";
    }  
  }

  $sql_join_fg = "(select id_jo,id_so from jo_det group by id_jo) jod on a.id_jo=jod.id_jo 

    left join so on jod.id_so=so.id 

    left join act_costing ac on so.id_cost=ac.id";

  $fld_add_fg3="";

  $fld_add_4="ifnull(ac.kpno,'-')";

  $fld_add_fg31="";

  $fld_add_fg="";

  $fld_add_fg2="";

  $jml_fld = 5;

  $jml_fld_sp = 3;

}


 //$tglcetak="Dicetak : ".date('Y-m-d H:i'); 
 $space_head=35;

 $ukuran_kertas="A4"; 
 $orientasi_kertas = "P"; # L = Landscape P = Portrait
 
if ($mode=="barcode")

{ $head_cap = "barcode";

  $rsh=mysql_fetch_array(mysql_query("SELECT DISTINCT brh.bpbno,bpb.bpbno_int,bpb.dateinput,mi.goods_code,mi.itemdesc,ac.kpno,ms.Supplier,bpb.invno
  FROM bpb_roll_h brh INNER JOIN bpb ON bpb.bpbno=brh.bpbno
  INNER JOIN masteritem AS mi ON brh.id_item=mi.id_item
  INNER JOIN jo_det AS jd ON jd.id_jo=brh.id_jo
  INNER JOIN so  ON so.id=jd.id_so
  INNER JOIN act_costing AS ac ON ac.id=so.id_cost
  INNER JOIN mastersupplier AS ms ON ms.Id_Supplier=ac.id_buyer
  WHERE brh.id='$bpbno'"));
  $head_data = '';
  
}
 	 
  $head_data = ' 
  <table width="40%" style="border:none; font-size:8pt">
      <tr> <td>Product</td> <td> : '.$rsh['itemdesc'].'</td></tr>
      <tr> <td>Item Code</td> <td> : '.$rsh['goods_code'].'</td></tr>
      <tr> <td>Supplier</td> <td> : '.$rsh['Supplier'].'</td>
      <tr> <td>No. BPB</td> <td> : '.$rsh['bpbno_int'].' ('.$rsh['invno'].') </td>
      <tr> <td>Tgl. BPB</td> <td> : '.fd_view($rsh['dateinput']).'</td>
      <tr> <td>No. WS</td> <td> : '.$rsh['kpno'].'</td>

  </table>';

  $header = $head_data;
?>

<table class='main' repeat_header="1" border="1" cellspacing="0" width="40%" 
  style="border-collapse: collapse; width:40%; font-size:8pt;">
  <thead>

    <tr class="head">

      <td align='center'>No</td>
      <td align='center'>Lot #</td>
      <td align='center'>Qty</td>
      <td align='center'>Qty FOC</td>
      <td align='center'>Total QTY</td>
      <td align='center'>Unit</td>

      
    </tr>

  </thead>

  <tbody>

    <?php

    echo($bpbno);
      if ($mode=="barcode")
      { 
        $sqlquedet="select DISTINCT br.id,br.id_h,brh.id_item,brh.id_jo,roll_no,lot_no,roll_qty,roll_foc,br.unit,
        concat(mr.kode_rak,' ',mr.nama_rak) raknya,concat(mrold.kode_rak,' ',mrold.nama_rak) raknyaold,
        br.barcode,roll_qty_used , COUNT(br.id) AS jroll
        from bpb_roll br inner join 
        bpb_roll_h brh on br.id_h=brh.id 
        left join master_rak mr on br.id_rak_loc=mr.id 
        left join master_rak mrold on br.id_rak=mrold.id
        where 
        brh.id='$bpbno' 
        order by br.id";

        $qdet=mysql_query($sqlquedet);
        while($rshder=mysql_fetch_array($qdet))
        {
        echo "
        <tr>
          <td align='center'>".$rshder['roll_no']." of ".$rshder['jroll']."</td>
          <td align='center'>".$rshder['lot_no']."</td>
          <td align='center'>".number_format($rshder['roll_qty'],2)."</td>
          <td align='center'>".number_format($rshder['roll_foc'],2)."</td>
          <td align='center'>".number_format($rshder['roll_qty']+$rshder['roll_foc'],2)."</td>
          <td align='center'>".$rshder['unit']."</td>
          </tr>
        ";     
        //$footer= $rshder['id'];
        $product_code_39 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=60&text=".$rshder['id']."&print=true' />";
       // $footer=  $product_code_39 .
       //           'Lokasi = '. $rshder['raknya'];
        
       $footer= '<table cellpadding=0 cellspacing=0 style="border:none;">

       <tr>
   
         <td style="margin-right:-5px;border:none;font-size:8px;" align="left">
   
           '.$product_code_39.'
   
         </td>
   
         <td style="margin-right:-5px;border:none;" align="center";font size="10">
         <strong style="font-size: 18px;">
   
         RAK : <br>'.str_replace('WAREHOUSE RACK','',$rshder['raknya']).'
        </strong>
         </td>
   
       </tr>
   
     </table>';
        }  
    }   
    ?>

  </tbody>
</table>
<br>

<?php



$header = $head_data;

$content = ob_get_clean();
  
//$footer='1233456789';


try {

    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

   $mpdf=new mPDF('utf-8', $ukuran_kertas, 8 ,'Tahoma', 2, 2, $space_head, 3, 3, 0, $orientasi_kertas);

  //  $mpdf->SetTitle("Laporan");

    $mpdf->setHTMLHeader($header);
    $mpdf->WriteHTML($content);
    $mpdf->WriteHTML($footer);
    $mpdf->Output("BarcodeBPB.pdf","I"); 

} catch(Exception $e) {

    echo $e;

    exit;

}


?>