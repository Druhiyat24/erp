<?php
session_start();
include('../../mpdf57/mpdf.php');
include '../../include/conn.php';
include '../forms/fungsi.php';
if(isset($_SESSION['username'])) { $user=$_SESSION['username']; } else { $user=""; }
ob_start();

$quenya = "Select * from mastercompany Where company!='' ";
$strsql = mysql_query($quenya);
$rs = mysql_fetch_array($strsql);
  $nm_company = $rs['company'];
  $add_company = $rs['alamat1'];
  $add2_company = $rs['alamat2'];
  $kota_comp = $rs['kota'];
  $add3_company = "Kec. ".$rs['kec'].' '.$rs['kota'].' '.$rs['propinsi'].' '.$rs['kodepos'];
  $add4_company = "Telp. ".$rs['telp'];
  if ($rs['logo_company']=="Y")
  { $logo = "<img src='../../include/logo.jpg' width='50'>"; }
  else
  { $logo = ""; }

$bppbno=$_GET['id'];
$mode=$_GET['mode'];
$tbl_mst = "masteritem"; 
$fld_mst2 = "goods_code";
$fld_mst = "itemdesc";
$logo = "<img src='../../include/img-01.png' width='50'>";

$tglcetak="Dicetak : ".date('Y-m-d H:i'); 
$ukuran_kertas="A4"; 
$orientasi_kertas = "P"; # L = Landscape P = Portrait
$head_cap = "Purchase Request General";
$quenya = "select a.reqno trans_no,a.reqdate trans_date,reqitem.*,
  '' supplier,'' alamat,'' alamat2 from 
  reqnon_header a inner join reqnon_item reqitem on a.id=reqitem.id_reqno 
  Where a.id='$bppbno' and reqitem.cancel='N' ";
#echo $quenya;
$ttdnya = '';
// $ttdnya = 
//   '<tr>
//   	<td width="300px" style="margin-right:-5px;border:none;" align="left">
//   		Prepare By.
//   	</td>
//   	<td width="300px" style="margin-right:-5px;border:none;" align="left">
//       Received By.
//     </td>
//     <td style="margin-left:-5px;border:none;" align="left">
//   		Approved By.
//   	</td>
//   </tr>
//   <tr>
//   	<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>
//   </tr>';
$footernya = 
	'<table cellpadding=0 cellspacing=0 style="border:none;">
		<tr>
			<td style="margin-right:-5px;border:none;font-size:8px;" align="left">
				Halaman: {PAGENO} / {nb}
			</td>
			<td style="margin-right:-5px;border:none;" align="left">
			'.$tglcetak.'
			</td>
		</tr>
	</table>';
$strsql = mysql_query($quenya);
$rs = mysql_fetch_array($strsql);
  $transno=$rs['trans_no'];
  $tglsj=$rs['trans_date'];
  $sentto=$rs['supplier'];
  $add_sentto=$rs['alamat'];
  $add2_sentto=$rs['alamat2'];
  $kota=$kota_comp.", ".fd_view($tglsj);
?>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:12px;">
	<thead>
		<tr class="head">
			<td align='center' height="20">No.</td>
			<td align='center'>Item Code</td>
			<td align='center'>Description</td>
			<td align='center'>Color</td>
      <td align='center'>Size</td>
      <td align='center'>Supplier</td>
      <td align='center'>Qty</td>
			<td align='center'>Unit</td>
      <td align='center'>Curr</td>
      <td align='center'>Price</td>
      <td align='center'>Amount</td>
    </tr>
	</thead>
	<tbody>
		<?php 
      $fldprice="reqitem.curr,reqitem.price,round(reqitem.qty*reqitem.price) amt"; 
      $sql="select $fld_mst2,$fld_mst,s.color,s.size,ms.supplier,reqitem.qty,reqitem.unit,
        $fldprice,a.notes remark
        ,tmppo.username userpo,tmppo.podate,a.username userreq,a.reqdate,
        a.app,a.app_by,a.app_date,a.app_notes,a.app2,a.app_by2,a.app_date2,a.app_notes2 
        from reqnon_header a inner join reqnon_item reqitem on a.id=reqitem.id_reqno
        inner join $tbl_mst s on reqitem.id_item=s.id_item 
        left join mastersupplier ms on reqitem.id_supplier=ms.id_supplier 
        left join (select s.id_jo,a.username,a.podate from po_header a inner join po_item s on a.id=s.id_po 
            where jenis='N' and s.id_jo='$bppbno' group by s.id_jo) tmppo on tmppo.id_jo=a.id
        where a.id='$bppbno' and reqitem.cancel='N' ";
      #echo $sql;
      $query=mysql_query($sql);
      $no = 1; 
      $tamt = 0;
      $tqty = 0;
      while($data = mysql_fetch_array($query))
      { $remark=$data['remark'];
        $crtby=$data['userreq']." (".fd_view($data['reqdate']).")";
        if($data['app']=="A")
        { $ap1="Approved By"; }
        elseif($data['app']=="R")
        { $ap1="Rejected By"; }
        else
        { $ap1="Waiting"; }
        if($data['app2']=="A")
        { $ap2="Approved By"; }
        elseif($data['app2']=="R")
        { $ap2="Rejected By"; }
        else
        { $ap2="Waiting"; }
        $ap1by=$data['app_by']." (".fd_view($data['app_date']).") ".$data['app_notes'];
        $ap2by=$data['app_by2']." (".fd_view($data['app_date2']).") ".$data['app_notes2'];
        $rcvby=$data['userpo']." (".fd_view($data['podate']).")";
        echo "
        <tr>
          <td>$no</td>
          <td>$data[goods_code]</td>
          <td>$data[itemdesc]</td>
          <td>$data[color]</td>
          <td>$data[size]</td>
          <td>$data[supplier]</td>
          <td align='right'>".fn($data["qty"],2)."</td>
          <td>$data[unit]</td>
          <td>$data[curr]</td>
          <td align='right'>".fn($data["price"],2)."</td>
          <td align='right'>".fn($data["amt"],2)."</td>
        </tr>";
        $tamt = $tamt + $data["amt"];
        $tqty = $tqty + $data['qty'];
        $no++;
      }
      echo "
      <tr>
        <td colspan='6' style='text-align:center;'>Total</td>
        <td align='right'>".fn($tqty,0)."</td>
        <td colspan='3' style='text-align:center;'></td>
        <td align='right'>".fn($tamt,0)."</td>
      </tr>";
    ?>
	</tbody>
</table>
Notes : <?php echo $remark; ?>
<br>
<table>
  <tr><td>Created By</td><td> : </td><td><?php echo $crtby; ?></td></tr>
  <tr><td><?php echo $ap1;?></td><td> : </td><td><?php echo $ap1by; ?></td></tr>
  <tr><td><?php echo $ap2;?> 2</td><td> : </td><td><?php echo $ap2by; ?></td></tr>
  <tr><td>Received By</td><td> : </td><td><?php echo $rcvby; ?></td></tr>
</table>
<br><br><br>
<table style="font-size:12px;">
	<?php 
	echo $ttdnya;
	?>
</table>
<?php
$header = '<table cellpadding=0 cellspacing=0 style="border:none;">
            <tr>
              <td style="border:none;" align="left">'.$logo.'</td>
              <td width="100%" style="border:none;"><h4>'.$nm_company.'</h4></td>
              <td width="100%" style="border:none; font-size:7pt;">'.$kota.'</td>
            </tr>
            <tr>
              <td></td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add_company.'</td>
              <td width="100%" style="border:none; font-size:7pt;">'.$sentto.'</td>
            </tr>
            <tr>
              <td></td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add2_company.'</td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add_sentto.'</td>
            </tr>
            <tr>
              <td></td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add3_company.'</td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add2_sentto.'</td>
            </tr>
            <tr>
              <td></td>
              <td width="100%" style="border:none; font-size:7pt;">'.$add4_company.'</td>
            </tr>
          </table>
          <table width="100%" style="border:none;">
            <tr>
              <td align="center" style="border:none;"><h2>'.$head_cap.'</h2></td>
            </tr>
            <tr>
              <td align="center" style="border:none; font-size:14pt;">'.$transno.'</td>
            </tr>
          </table>';
$content = ob_get_clean();
$footer =  $footernya;           
try {
    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);
    $mpdf=new mPDF('utf-8', $ukuran_kertas, 9 ,'Arial', 5, 5, 47, 5, 5, 1, $orientasi_kertas);
    $mpdf->SetTitle("Laporan");
    $mpdf->setHTMLHeader($header);
    $mpdf->setHTMLFooter($footer);
    $mpdf->WriteHTML($content);
    $mpdf->Output("laporan.pdf","I");
} catch(Exception $e) {
    echo $e;
    exit;
}
?>