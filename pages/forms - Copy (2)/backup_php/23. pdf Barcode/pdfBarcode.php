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

 $space_head=40;

 $ukuran_kertas="A4"; 

 $orientasi_kertas = "L"; # L = Landscape P = Portrait
 
# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf=new mPDF('utf-8',array(80,50), 6 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

if ($mode=="barcode")

{ $head_cap = "barcode";}  
 
?>

    <?php
    
      if ($mode=="barcode")
      { 

        $sqlquedet="SELECT DISTINCT br.id,br.id_h,brh.id_item,brh.id_jo, roll_no,lot_no,roll_qty, roll_qty_act,roll_qty_act-roll_qty AS selisih, roll_foc,br.unit, 
                    CONCAT(mr.kode_rak,' ',mr.nama_rak) raknya,CONCAT(mrold.kode_rak,' ',mrold.nama_rak) raknyaold, br.barcode,roll_qty_used 
                    FROM bpb_roll br INNER JOIN bpb_roll_h brh ON br.id_h=brh.id LEFT JOIN master_rak mr ON br.id_rak_loc=mr.id 
                    LEFT JOIN master_rak mrold ON br.id_rak=mrold.id 
                    WHERE brh.id='$bpbno' ORDER BY br.id";

        $qdet=mysql_query($sqlquedet);
        $ix = 1;
        while($rshder=mysql_fetch_array($qdet))
        {
        
          $rsh=mysql_fetch_array(mysql_query("SELECT DISTINCT brh.bpbno,bpb.bpbno_int,bpb.dateinput,mi.goods_code,mi.itemdesc,ac.kpno,ms.Supplier,bpb.invno,bpb.pono
          FROM bpb_roll_h brh INNER JOIN bpb ON bpb.bpbno=brh.bpbno
          INNER JOIN masteritem AS mi ON brh.id_item=mi.id_item
          INNER JOIN jo_det AS jd ON jd.id_jo=brh.id_jo
          INNER JOIN so  ON so.id=jd.id_so
          INNER JOIN act_costing AS ac ON ac.id=so.id_cost
          INNER JOIN mastersupplier AS ms ON ms.Id_Supplier=ac.id_buyer
          WHERE brh.id='$bpbno'"));
   
          $head_data = ' 
            <table width="100%" style="border:none; font: size 6px;">
              <tr> <td>Product</td> <td> : '.$rsh['itemdesc'].'</td></tr>
              <tr> <td>Item Code</td> <td> : '.$rsh['goods_code'].'</td></tr>
              <tr> <td>Supplier</td> <td> : '.$rsh['Supplier'].'</td></tr>
              <tr> <td>No. BPB</td> <td> : '.$rsh['bpbno_int'].' ('.$rsh['invno'].') </td></tr>
              <tr> <td>Tgl. BPB</td> <td> : '.fd_view($rsh['dateinput']).'</td></tr>
              <tr> <td>No. PO</td> <td> : '.$rsh['pono'].'</td></tr>
              <tr> <td>No. WS</td> <td> : '.$rsh['kpno'].'</td></tr>
            </table>  ';
          $header = $head_data;
          $mpdf->setHTMLHeader($header);  
          $mpdf->AddPage();
          
          $isinya ='
          <table class="main" repeat_header="1" border="1" cellspacing="0" width="100%" 
                 style="border-collapse: collapse; width:100%; font: size 6px;">
           <thead>
              <tr class="head">
                 <td align="center">No</td>
                        <td align="center">Lot #</td>
                        <td align="center">Qty</td>
                        <td align="center">Qty FOC</td>
                        <td align="center">Total QTY</td>
                        <td align="center">Unit</td>               
                      </tr>
                    </thead>

                   <tbody>             
                        echo "
                          <tr>
                            <td align="center">'.$rshder['roll_no'].' of '.$rshder['jroll'].'</td>
                            <td align="center">'.$rshder['lot_no'].'</td>
                                <td align="center">'.number_format($rshder['roll_qty'],2).'</td>
                                <td align="center">'.number_format($rshder['roll_foc'],2).'</td>
                                <td align="center">'.number_format($rshder['roll_qty']+$rshder['roll_foc'],2).'</td>
                                <td align="center">'.$rshder['unit'].'</td>
                                </tr>
                    </tbody>
            </table>
            <br>
          ';

          $mpdf->WriteHTML($isinya);
       
          $product_code_39 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=30&text=".$rshder['id']."&print=true' />";      
           
          $footer=
            '<table cellpadding=0 cellspacing=0 style="border:none;" width="100%">
                <tr>
                  <td style="margin-left:-5px;border:none;" align="center">'.$product_code_39.'</td>
                 </tr>
                 <tr> 
                  <td style="margin-right:-5px;border:none;" align="center">
                  <strong style="font-size: 8px;">RAK : <br>'.str_replace('WAREHOUSE RACK','',$rshder['raknya']).'</strong>
                </td>
              </tr>   
            </table>';
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