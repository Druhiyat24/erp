<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';

ob_start();


$id=$_GET['id'];
$mode=$_GET['mode'];

//paging--------------------------

// if ($jdata>=1 and $jdata<8)

// { $space_head=52; }

// else

// { $space_head=55; }
//end paging------------------------------

 $space_head=38;

 $ukuran_kertas="A7"; 

 $orientasi_kertas = "L"; # L = Landscape P = Portrait
 
# $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan,  pace_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

//$mpdf=new mPDF('utf-8',array(110,80), 9 ,'Tahoma', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);
$mpdf=new mPDF('utf-8',array(80,100), 6 ,'Times New Roman', 3, 2, $space_head, 2, 2, 0, $orientasi_kertas);
// $mpdf=new mPDF('utf-8',array(68,110), 6 ,'Times New Roman', 1, 1, $space_head, 2, 2, 0, $orientasi_kertas);

if ($mode=="barcode")

{ $head_cap = "barcode";}  
 
?>

    <?php
    
        $sqlquedet="select a.*,CONCAT(a.no_roll) roll,IF(no_mut = '',dok_num,concat(dok_num,' ',(nomut))) no_dok,IF(a.supp is null,'-',a.supp) supplier from (select * from (select a.no_sj,a.no_style styleno,a.no_barcode id,b.itemdesc item_desc,b.goods_code kode_item,a.id_jo,a.id_item,if(a.no_bpb ='-' ,'-',concat(a.no_bpb,' | ',a.tgl_bpb)) dok_num, concat(' | ',coalesce(no_mut,'')) nomut,coalesce(no_mut,'') no_mut,a.no_bpb,no_po,a.no_ws,no_roll,'' no_roll_buyer,no_lot,ROUND(qty,2) qty,unit satuan,'-' grouping,kode_lok from whs_sa_fabric a inner join masteritem b on b.id_item = a.id_item where a.qty != 0 order by a.no_lot asc) a left join (select a.bpbno_int,b.supplier supp from bpb a inner join mastersupplier b on b.id_supplier = a.id_supplier WHERE bpbdate >= '2021-10-01' and LEFT(bpbno_int,2) = 'GK' GROUP BY bpbno_int) b on b.bpbno_int = a.no_bpb) a where kode_lok = '$id' order by no_mut,a.no_lot asc";

        $qdet=mysql_query($sqlquedet);
        $ix = 1;
        while($rshder=mysql_fetch_array($qdet))                    
        {

$product_code_40 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=15&text=".$rshder['id_item']."&print=false' />";            
          $head_data = ' 
            <table width="100%" style="border:none; font: size 5px;font-weight:bold">
              <tr> <td>Product <td>:<td></tr>
              <td>'.$rshder['item_desc'].'</td>
              <tr> <td>Kode Barang <td>:<td></tr>
              <td>'.$rshder['kode_item'].'</td>
              <tr> <td>ID Item <td>:<td></tr>
              <td>'.$rshder['id_item'].'</td>           
              <tr> <td>Supplier <td>:<td></tr>
              <td>'.$rshder['supplier'].'</td>
              <tr> <td>No BPB <td>:<td></tr>
              <td>'.$rshder['no_dok'].'</td> 
              <tr> <td>No SJ <td>:<td></tr>
              <td>'.$rshder['no_sj'].'</td>
              <tr> <td>No PO <td>:<td></tr>
              <td>'.$rshder['no_po'].'</td>
              <tr> <td>No WS <td>:<td></tr>
              <td>'.$rshder['no_ws'].'</td>
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
                 <td align="center" style="width: 12%">No Roll</td>
                        <td align="center" style="width: 18%">No Roll Buyer</td>
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
                            <td align="center">'.$rshder['roll'].'</td>
                            <td align="center">-</td>
                                <td align="center">'.$rshder['no_lot'].'</td>
                                <td align="center">'.number_format($rshder['qty'],2).'</td>
                                <td align="center">'.$rshder['kode_lok'].'</td>
                                <td align="center">'.$rshder['satuan'].'</td>
								<td align="center"></td>
                                </tr>
                    </tbody>
            </table>           
            <br>
          ';

          $mpdf->WriteHTML($isinya);
        
          $product_code_39 = "<img alt='code 128 bar code' src='barcode.php?codetype=Code128&size=40&text=".$rshder['id']."&print=true' />";      
                   
          $footer=
            '
 <div> 
          
          <table align="center" width="100%" border="1" cellspacing="0" 
                 style="border-collapse: collapse; font: size 6px;font-weight:bold">
            <tbody>
            <tr class="head"  width="35%">
                 <td rowspan="4" align="center" style="padding-top: 15px;">'.$product_code_39.'</td>    
                 <td colspan="4" align="center">Relaxation</td>                  
            </tr>
            <tr>
            
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