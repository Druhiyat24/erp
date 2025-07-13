<?php
include('../../mpdf57/mpdf.php');
include '../../include/conn.php';
include 'fungsi.php';

ob_start();

$quenya = "Select * from mastercompany Where company!='' ";
$strsql = mysql_query($quenya);
$rs = mysql_fetch_array($strsql);
  $nm_company = $rs['company'];
  $kota_comp = $rs['kota'];
  if ($rs['logo_company']=="Y")
  { $logo = "<img src='../../include/logo.jpg' width='50'>"; }
  else
  { $logo = ""; }

$trx_no=$_GET['noid'];
$mode=$_GET['mode'];
if (substr($trx_no,0,2)=="FG" OR substr($trx_no,3,2)=="FG")
{	$tbl_mst = "masterstyle"; 
	$fld_mst2 = "goods_code";
	if ($nm_company=="PT. Bangun Sarana Alloy")
  { $fld_mst = "concat(itemname,' Size ',size)"; }
  else if ($nm_company=="PT. Tun Hong")
  { $fld_mst = "concat('Style # ',s.styleno,' ',itemname,' Size ',size,' Warna ',color)"; }
  else
  { $fld_mst = "itemname"; }
  $fld_sc="'' stock_card";
}
else
{	$tbl_mst = "masteritem"; 
	$fld_mst2 = "goods_code";
	if ($nm_company=="PT. Bangun Sarana Alloy")
  { $fld_mst = "concat(itemdesc,' Size ',size)"; }
  else if ($nm_company=="PT. Tun Hong")
  { $sql="update bpb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bpbno='$trx_no' "; 
    insert_log($sql,"");
    $sql="update bppb a inner join masterstyle s on a.id_item_fg=s.id_item set a.styleno=s.styleno where bppbno='$trx_no' "; 
    insert_log($sql,"");
    $fld_mst = "concat('Style # ',a.styleno,' ',itemdesc,' Size ',size,' Warna ',color)"; 
  }
  else
  { $fld_mst = "itemdesc"; }
  $fld_sc="s.stock_card";
}
$logo = "";

if ($nm_company=="PT. Bangun Sarana Alloy")
{ $tglcetak=""; 
  $orientasi_kertas = "L"; # L = Landscape P = Portrait
  $ukuran_kertas=array(105,148.5); # Ukuran kertas W/Lebar x H/Tinggi
}
else
{ $tglcetak="Dicetak : ".date('Y-m-d H:i'); 
  $ukuran_kertas="A4"; 
  $orientasi_kertas = "P"; # L = Landscape P = Portrait
}
if ($mode=="In")
{ $head_cap = "Bukti Penerimaan Barang";
  $head_cap2 = "BTB";
  $head_cap3 = "Supplier";
  $quenya = "Select a.bpbno trans_no,a.bpbdate trans_date,a.*,s.supplier,s.alamat,s.alamat2 from bpb a inner join mastersupplier s 
    on a.id_supplier=s.id_supplier
    Where bpbno='$trx_no'";
}
else
{ $head_cap = "Surat Jalan";
  $head_cap2 = "BKB";
  $head_cap3 = "Sent To";
  $quenya = "Select a.bppbno trans_no,a.bppbdate trans_date,a.*,s.supplier,
    s.alamat,s.alamat2 from bppb a inner join mastersupplier s 
    on a.id_supplier=s.id_supplier
    Where bppbno='$trx_no'";
}
if ($mode=="Out")
{	$ttdnya = 
  '<tr>
		<td width="200px" style="margin-right:-5px;border:none;" align="left">
			Prepare By.
		</td>
		<td width="200px" style="margin-left:-5px;border:none;" align="left">
			Approved By.
		</td>
		<td style="margin-left:-5px;border:none;" align="left">
			Received By.
		</td>
	</tr>
	<tr>
		<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>
	</tr>';
}
else
{	$ttdnya = 
  '<tr>
		<td width="300px" style="margin-right:-5px;border:none;" align="left">
			Prepare By.
		</td>
		<td width="300px" style="margin-right:-5px;border:none;" align="left">
      Received By.
    </td>
    <td style="margin-left:-5px;border:none;" align="left">
			Approved By.
		</td>
	</tr>
	<tr>
		<td style="margin-right:-5px;border:none;" align="right" height="80px"></td>
	</tr>';
}
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
  $tglsj=$rs['trans_date'];
  $sentto=$rs['supplier'];
  $trx_date=fd_view($rs['trans_date']);
  if (substr($trx_no,0,2)=="FG" OR substr($trx_no,3,2)=="FG")
  { $trx_po=flookup("buyerno","masterstyle","id_item='$rs[id_item]'"); }
  else
  { $trx_po=$rs['pono']; }
  $trx_bc=$rs['bcno'];
  $trx_inv=$rs['invno'];
  $kota=$kota_comp.", ".fd_view($tglsj);
?>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:10px;">
	<thead>
		<tr class="head">
			<td align='center' height="20">No.</td>
			<td align='center'>PK #</td>
			<td align='center'>Style #</td>
			<td align='center'>Desc. Of Goods</td>
			<td align='center'>Color</td>
			<td align='center'>Size</td>
			<td align='center'>Qty</td>
			<td align='center'>Unit</td>
			<td align='center'>Stock Card</td>
			<td align='center'>Remark</td>
		</tr>
	</thead>
	<tbody>
		<?php
      if ($mode=="In")
      { tampil_data("select a.kpno,a.styleno,$fld_mst,s.color,s.size,a.qty,a.unit,$fld_sc,a.remark 
          from bpb a inner join $tbl_mst s on a.id_item=s.id_item 
          where bpbno='$trx_no'",9);
      }
      else
      { tampil_data("select a.kpno,a.styleno,$fld_mst,s.color,s.size,a.qty,a.unit,$fld_sc,a.remark 
          from bppb a inner join $tbl_mst s on a.id_item=s.id_item 
          where bppbno='$trx_no'",9);
      }
    ?>
	</tbody>
</table>
<table style="font-size:12px;">
	<?php 
	echo $ttdnya;
	?>
</table>
<?php
$header = '<table width="100%" cellpadding=0 cellspacing=0 style="border:none;">
            <tr>
              <td width="100%" style="border:none;text-align:center;">'.$nm_company.'</td>
            </tr>
            <tr>
            	<td width="100%" style="border:none;text-align:center;font-size:7pt;">'.$head_cap.'</td>
            </tr>
          </table>
          <br><br>
          <table width="100%" style="border:none;">
            <tr>
              <td width="7%" style="border:none;font-size:5pt;"><h2>'.$head_cap2.' #</h2></td>
              <td style="border:none;font-size:7pt;">'.$trx_no.'</td>
              <td width="6%" style="border:none;font-size:5pt;"><h2>'.$head_cap3.'</h2></td>
              <td style="border:none;font-size:7pt;">'.$sentto.'</td>
              <td width="10%" style="border:none;font-size:5pt;"><h2>Inv # / SJ #</h2></td>
              <td style="border:none;font-size:7pt;">'.$trx_inv.'</td>
            </tr>
            <tr>
              <td style="border:none;font-size:5pt;"><h2>'.$head_cap2.' Date</h2></td>
              <td style="border:none;font-size:7pt;">'.$trx_date.'</td>
            	<td style="border:none;font-size:5pt;"><h2>PO #</h2></td>
              <td style="border:none;font-size:7pt;">'.$trx_po.'</td>
            	<td style="border:none;font-size:5pt;"><h2>BC #</h2></td>
              <td style="border:none;font-size:7pt;">'.$trx_bc.'</td>
            </tr>
          </table>';
$content = ob_get_clean();
$footer =  $footernya;           
try {
    # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);
    $mpdf=new mPDF('utf-8', $ukuran_kertas, 7 ,'Arial', 5, 5, 30, 5, 5, 1, $orientasi_kertas);
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