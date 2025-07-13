<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';

ob_start();


$bpbno=$_GET['id'];
$mode=$_GET['mode'];

//paging--------------------------

// if ($jdata>=1 and $jdata<8)

// { $space_head=52; }

// else

// { $space_head=55; }
//end paging------------------------------

 $space_head=35;

 $ukuran_kertas="A4"; 

 $orientasi_kertas = "L"; # L = Landscape P = Portrait
 
# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf=new mPDF('utf-8',array(68,110), 6 ,'Times New Roman', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
// $mpdf=new mPDF('utf-8',array(68,102), 6 ,'Times New Roman', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

if ($mode=="barcode")

{ $head_cap = "barcode";}  
 
?>

    <?php
    
      if ($mode=="barcode")
      {         

        $sqlquedet="select br.id,mi.itemdesc, mi.id_item, goods_code, supplier, bpbno_int,pono,invno,ac.kpno,roll_no, a.juml_roll, roll_qty, lot_no, bpb.unit, kode_rak, ac.styleno
from bpb_roll br
inner join bpb_roll_h brh on br.id_h = brh.id
inner join masteritem mi on brh.id_item = mi.id_item
inner join bpb on brh.bpbno = bpb.bpbno and brh.id_jo = bpb.id_jo and brh.id_item = bpb.id_item
inner join mastersupplier ms on bpb.id_supplier = ms.Id_Supplier
inner join jo_det jd on brh.id_jo = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join (select max(cast(roll_no as int)) juml_roll, id_h from bpb_roll where id_h = '$bpbno') a on brh.id = a.id_h
inner join master_rak mr on br.id_rak_loc = mr.id
where brh.id = '$bpbno'
group by br.id
order by br.id";

        $qdet=mysql_query($sqlquedet);
        $ix = 1;
        while($rshder=mysql_fetch_array($qdet))                    
        {

$product_code_40 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=15&text=".$rshder['id_item']."&print=false' />";            
          $head_data = ' 
            <table width="100%" style="border:none; font: size 5px;font-weight:bold">
              <tr> <td>Product <td>:<td></tr>
              <td>'.$rshder['itemdesc'].'</td>
              <tr> <td>Kode Barang <td>:<td></tr>
              <td>'.$rshder['goods_code'].'</td>
              <tr> <td>ID Item <td>:<td></tr>
              <td>'.$rshder['id_item'].'</td>           
              <tr> <td>Supplier <td>:<td></tr>
              <td>'.$rshder['supplier'].'</td>
              <tr> <td>No BPB <td>:<td></tr>
              <td>'.$rshder['bpbno_int'].'</td> 
              <tr> <td>No SJ <td>:<td></tr>
              <td>'.$rshder['invno'].'</td>
              <tr> <td>No PO <td>:<td></tr>
              <td>'.$rshder['pono'].' | No WS : '.$rshder['kpno'].'</td>
              <tr> <td>Style <td>:<td></tr>
              <td>'.$rshder['styleno'].'</td>              
            </table>             
            ';
              //             <tr> <td>No WS <td>:<td></tr>
              // <td>'.$rshder['kpno'].'</td> 
// <td>'.$rshder['id_item'].''.$product_code_40.'</td>  
          $header = $head_data;
          $mpdf->setHTMLHeader($header);  
          $mpdf->AddPage();        
          $isinya ='
          <table class="main" repeat_header="1" border="1" cellspacing="0" width="100%" 
                 style="border-collapse: collapse; width:100%; font: size 6px;font-weight:bold">
           <thead>
              <tr class="head">
                 <td align="center">No Roll</td>
                        <td align="center">Lot</td>
                        <td align="center">Qty</td>
                        <td align="center">Nama Rak</td> 
                        <td align="center">Unit</td>
						<td align="center">Grouping</td>					
                    </tr>
                    </thead>

                   <tbody>             
                        echo "
                          <tr>
                            <td align="center">'.$rshder['roll_no'].' of '.$rshder['juml_roll'].'</td>
                                <td align="center">'.$rshder['lot_no'].'</td>
                                <td align="center">'.number_format($rshder['roll_qty'],2).'</td>
                                <td align="center">'.$rshder['kode_rak'].'</td>
                                <td align="center">'.$rshder['unit'].'</td>
								<td align="center"></td>
                                </tr>
                    </tbody>
            </table>           
            <br>
          ';

          $mpdf->WriteHTML($isinya);
        
          $product_code_39 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=30&text=".$rshder['id']."&print=true' />";      
                   
          $footer=
            '
 <div> 
          
          <table align="center" width="100%" border="1" cellspacing="0" 
                 style="border-collapse: collapse; font: size 6px;font-weight:bold">
            <tbody>
            <tr class="head"  width="35%">
                 <td align="center" style="border-top:0;border-left:0;border-bottom:0"></td>    
                 <td colspan="4" align="center">Relaxation</td>                  
            </tr>
            <tr>
            <td rowspan="3" align="center" style="border-top:0;border-left:0;border-bottom:0">'.$product_code_39.'</td>
                <td colspan="2" align="center">Start</td>
                <td colspan="2" align="center">Finish</td>
            </tr>
            <tr>
                <td align="center">Date</td>
                <td align="center">Time</td>
                <td align="center">Date</td>
                <td align="center">Time</td>                
            </tr>
            <tr>
                <td height="20px" align="center">&nbsp;</td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>                                              
            </tr>           
            </tbody>            
            </table>
 <div>           
            ';
            $mpdf->WriteHTML($footer);

           
            $ix ++;

        }  
      }

    else 

    if ($mode=="barcode_bppb")
    {         

      $sqlquedet="select br.id,mi.itemdesc, mi.id_item, goods_code, supplier, bpbno_int,pono,invno,ac.kpno,roll_no, a.juml_roll, roll_qty - roll_qty_used sisa, lot_no, bpb.unit, kode_rak
      from bppb_barcode_det bbd
      inner join bpb_roll br on bbd.id_roll = br.id
      inner join bpb_roll_h brh on br.id_h = brh.id
      inner join masteritem mi on brh.id_item = mi.id_item
      inner join bpb on brh.bpbno = bpb.bpbno and brh.id_jo = bpb.id_jo and brh.id_item = bpb.id_item
      inner join mastersupplier ms on bpb.id_supplier = ms.Id_Supplier
      inner join jo_det jd on brh.id_jo = jd.id_jo
      inner join so on jd.id_so = so.id
      inner join act_costing ac on so.id_cost = ac.id
      inner join (select max(cast(roll_no as int)) juml_roll, id_h from bpb_roll group by id_h) a on brh.id = a.id_h
      inner join master_rak mr on br.id_rak_loc = mr.id
      where bbd.bppbno = '$bpbno' and roll_qty - roll_qty_used != '0'
      group by br.id
      order by br.id";

      $qdet=mysql_query($sqlquedet);
      $ix = 1;
      while($rshder=mysql_fetch_array($qdet))                    
      {

$product_code_40 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=15&text=".$rshder['id_item']."&print=false' />";            
        $head_data = ' 
          <table width="100%" style="border:none; font: size 5px;font-weight:bold">
            <tr> <td>Product <td>:<td></tr>
            <td>'.$rshder['itemdesc'].'</td>
            <tr> <td>Kode Barang <td>:<td></tr>
            <td>'.$rshder['goods_code'].'</td>
            <tr> <td>ID Item <td>:<td></tr>
            <td>'.$rshder['id_item'].''.$product_code_40.'</td>            
            <tr> <td>Supplier <td>:<td></tr>
            <td>'.$rshder['supplier'].'</td>
            <tr> <td>No BPB <td>:<td></tr>
            <td>'.$rshder['bpbno_int'].'</td> 
            <tr> <td>No SJ <td>:<td></tr>
            <td>'.$rshder['invno'].'</td>
            <tr> <td>No PO <td>:<td></tr>
            <td>'.$rshder['pono'].'</td>
            <tr> <td>No WS <td>:<td></tr>
            <td>'.$rshder['kpno'].'</td> 
          </table>             
          ';
        $header = $head_data;
        $mpdf->setHTMLHeader($header);  
        $mpdf->AddPage();        
        $isinya ='
        <table class="main" repeat_header="1" border="1" cellspacing="0" width="100%" 
               style="border-collapse: collapse; width:100%; font: size 6px;font-weight:bold">
         <thead>
            <tr class="head">
               <td align="center">No Roll</td>
                      <td align="center">Lot</td>
                      <td align="center">Qty</td>
                      <td align="center">Nama Rak</td> 
                      <td align="center">Unit</td>
          <td align="center">Grouping</td>					
                  </tr>
                  </thead>

                 <tbody>             
                      echo "
                        <tr>
                          <td align="center">'.$rshder['roll_no'].' of '.$rshder['juml_roll'].'</td>
                              <td align="center">'.$rshder['lot_no'].'</td>
                              <td align="center">'.number_format($rshder['sisa'],2).'</td>
                              <td align="center">'.$rshder['kode_rak'].'</td>
                              <td align="center">'.$rshder['unit'].'</td>
              <td align="center"></td>
                              </tr>
                  </tbody>
          </table>           
          <br>
        ';

        $mpdf->WriteHTML($isinya);
      
        $product_code_39 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=30&text=".$rshder['id']."&print=true' />";      
                 
        $footer=
          '
<div> 
        
        <table align="center" width="100%" border="1" cellspacing="0" 
               style="border-collapse: collapse; font: size 6px;font-weight:bold">
          <tbody>
          <tr class="head"  width="35%">
               <td align="center" style="border-top:0;border-left:0;border-bottom:0"></td>    
               <td colspan="4" align="center">Relaxation</td>                  
          </tr>
          <tr>
          <td rowspan="3" align="center" style="border-top:0;border-left:0;border-bottom:0">'.$product_code_39.'</td>
              <td colspan="2" align="center">Start</td>
              <td colspan="2" align="center">Finish</td>
          </tr>
          <tr>
              <td align="center">Date</td>
              <td align="center">Time</td>
              <td align="center">Date</td>
              <td align="center">Time</td>                
          </tr>
          <tr>
              <td height="20px" align="center">&nbsp;</td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>                                              
          </tr>           
          </tbody>            
          </table>
<div>           
          ';
          $mpdf->WriteHTML($footer);

         
          $ix ++;

       
    }
      }
         
    ?>

<?php
  

try 
{
  $content = ob_get_clean();

 
 
 // $mpdf->WriteHTML($content);
 // $mpdf->WriteHTML($footer);

 
  $mpdf->Output("BarcodeBPB.pdf","I");   

} catch(Exception $e) {

    echo $e;
    exit;

}


?>