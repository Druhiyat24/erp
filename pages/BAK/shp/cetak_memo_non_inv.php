<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

include 'fungsi.php';



ob_start();



$quenya = "Select * from mastercompany Where company!='' ";

$strsql = mysql_query($quenya);

$rs = mysql_fetch_array($strsql);

$nm_company = $rs['company'];

$add_company = $rs['alamat1'];

$add2_company = $rs['alamat2'];

$kota_comp = $rs['kota'];

$add3_company = "Kec. " . $rs['kec'] . ' ' . $rs['kota'] . ' ' . $rs['propinsi'] . ' ' . $rs['kodepos'];

$add4_company = "Telp. " . $rs['telp'];

if ($rs['logo_company'] == "Y") {
  $logo = "<img src='../../include/logo.jpg' width='50'>";
} else {
  $logo = "";
}



$id_h = $_GET['id_h'];

$sqlcek = "select jns_inv from memo_h where id_h = '$id_h'";
$ceksql = mysql_query($sqlcek);
$cekdata = mysql_fetch_array($ceksql);
$jns_inv = $cekdata['jns_inv'];

$sqltotal = "select concat(IF(curr = 'IDR', 'Rp. ', '$ '),format(round(sum(biaya),2),2)) total_biaya from memo_det det
inner join memo_h h on det.id_h = h.id_h
where det.id_h = '$id_h' and det.cancel = 'N'";

$tolsql = mysql_query($sqltotal);
$total = mysql_fetch_array($tolsql);
$totalbiaya = $total['total_biaya'];


$tglcetak = "Dicetak : " . date('Y-m-d H:i');
$ukuran_kertas = "A4";
$orientasi_kertas = "P"; # L = Landscape P = Portrait


$head_cap = "MEMO PERMINTAAN PEMBAYARAN EXIM";

$sql = "select a.*, ms.supplier supplier, mb.supplier buyer,IF(ditagihkan = 'Y','YA','TIDAK') ditagihkan_fix,b.inv_vendor,IF(jatuh_tempo is null,jatuh_tempo_new,jatuh_tempo) jatuh_tempo_new from memo_h a
    inner join (SELECT id_h,GROUP_CONCAT(DISTINCT(inv_vendor) SEPARATOR ' , ') inv_vendor FROM memo_det where id_h = '$id_h' and cancel != 'Y') b on a.id_h = b.id_h    
    inner join mastersupplier ms on a.id_supplier = ms.id_supplier
    inner join mastersupplier mb on a.id_buyer = mb.id_supplier where a.id_h = '$id_h'";
$rsh = mysql_fetch_array(mysql_query($sql));
$notes   = $rsh['notes'];
$head_data = '
  <table width="100%" style="border:none; font-size:9pt">
    <tr>
      <td  width = "20%"></td>
      <td  width = "35%"></td>
      <td width = "20%" >Tanggal</td>
      <td width = "25%"> : ' . fd_view($rsh['tgl_memo']) . '</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>Kepada</td>
      <td> : ' . $rsh['kepada'] . '</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Mohon dibayarkan,</td>
      <td></td>
      <td></td>
      <td></td>
    </tr> 
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>No. Memo</td>
      <td> : ' . $rsh['nm_memo'] . '</td>
      <td>No. Invoice Vendor </td>
      <td> : ' . $rsh['inv_vendor'] . '</td>
    </tr>           
    <tr>
      <td>Nama Penerima</td>
      <td> : ' . $rsh['supplier'] . '</td>
      <td>No. Aju </td>
      <td> : ' . $rsh['no_aju'] . '</td>
    </tr>    
    <tr>
    <td>Jenis Transaksi</td>
    <td> : ' . $rsh['jns_trans'] . '</td>
    <td></td>
    <td></td>
  </tr>    
  <tr>
  <td>Jalur Pengiriman</td>
  <td> : ' . $rsh['jns_pengiriman'] . '</td>
  <td></td>
  <td></td>
  </tr>    
  <tr>
  <td>Keperluan Buyer</td>
  <td> : ' . $rsh['buyer'] . '</td>
  <td>Ditagihkan ke buyer</td>
  <td> : ' . $rsh['ditagihkan_fix'] . '</td>
  </tr>
  <tr>
  <td>Sejumlah</td>
  <td> : ' . $totalbiaya . '</td>
  <td>Jatuh Tempo</td>
  <td> : ' . $rsh['jatuh_tempo_new'] . '</td>
  </tr>    
  <tr>
  <td>Dok Pendukung</td>
  <td> : ' . $rsh['dok_pendukung'] . '</td>
  <td></td>
  <td></td>
  </tr>      ';

$head_data = $head_data . '
  </table>';

$footernyaX = "";
$footernya = "";

?>
<br>

<table width="100%" style="border:none; font-size:9pt">
  <tr>
    <td width="20%">List Biaya</td>
    <td width="20%">:</td>
    <td width="20%"></td>
    <td width="25%"></td>
    <td width="25%"></td>
  </tr>
</table>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:10pt;">

  <thead>
    <!-- <tr>
<th align='center' colspan = "5">test</th>
</tr>  -->


    <tr class="head">

      <td width="5%" align='center' height="20">No.</td>

      <td width="25%" align='center'>Kategori</td>

      <td width="20%" align='center'>Sub Kategori</td>

      <td width="15%" align='center'>Invoice Vendor</td>
      <td width="20%" align='center'>Faktur pajak</td>

      <td width="15%" align='center'>Biaya</td>

    </tr>

  </thead>

  <tbody>

    <?php

    $querylain = mysql_query("select nm_ctg,nm_sub_ctg,concat(IF(curr = 'IDR', 'Rp. ', '$ '),format(round(biaya,2),2)) biaya, inv_vendor,faktur_pajak from memo_det det 
inner join memo_h h on det.id_h = h.id_h
where det.id_h = '$id_h'
and det.cancel = 'N'");
    $no = 1;
    while ($datalain = mysql_fetch_array($querylain)) {
      echo "
  <tr>
  <th align = 'center'>$no</th>
  <td>$datalain[nm_ctg]</td>
  <td>$datalain[nm_sub_ctg]</td>
  <td>$datalain[inv_vendor]</td>
   <td>$datalain[faktur_pajak]</td>
  <td align='right'>$datalain[biaya]</td>
  </tr>";
      $no++;
    }

    // tampil_data("select nm_ctg,nm_sub_ctg,concat(IF(curr = 'IDR', 'Rp. ', '$ '),round(biaya,2)) from memo_det det 
    // inner join memo_h h on det.id_h = h.id_h
    // where det.id_h = '$id_h'",3);

    echo "

        <tr>

          <td colspan='5' align='right'>Total </td>

          <td align='right'>" . $totalbiaya . "</td>

        </tr>

        ";



    ?>

  </tbody>

</table>

<table width="100%" style="border:none; font-size:9pt">
  <tr>
    <td width="20%">Notes</td>
    <td width="35%">: <?php echo $notes; ?></td>
    <td width="20%"></td>
    <td width="25%"></td>
  </tr>
</table>

<br>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:9pt;">
  <thead>
    <!-- <tr>
<th align='center' colspan = "5">test</th>
</tr>  -->


    <tr class="head">

      <td align='center'>Dibuat Oleh :</td>

      <td align='center'>Diperiksa Oleh :</td>

      <td align='center'>Disetujui Oleh :</td>

      <td align='center'>Diketahui Oleh :</td>

    </tr>

  </thead>

  <tbody>
    <tr>
      <td height="50"></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td align='center'>Staff Exim</td>
      <td align='center'>Manager Exim</td>
      <td align='center'>Manager MD</td>
      <td align='center'>Direksi</td>
    </tr>
  </tfoot>


</table>

<?php

$header = $footernya . '<table cellpadding=0 cellspacing=0 style="border:none;">


          </table>

          <table width="100%" style="border:none;">

            <tr>

              <td align="center" style="border:none;"><h2>' . $head_cap . '</h2></td>

            </tr>
          </table>

          ' . $head_data . '';

$content = ob_get_clean();

$footer =  $footernyaX;

try {

  # $mpdf=new mPDF('utf-8', "A4", Ukuran_Font ,'Arial', Margin_Kiri, Margin_Kanan, Space_Header_Dengan_Contents, 5, Space_Atas_Dengan_Header, Space_Bawah_Dengan_Footer, Orientation);

  $mpdf = new mPDF('utf-8', $ukuran_kertas, 9, 'Arial', 5, 5, 65, 5, 5, 1, $orientasi_kertas);

  $mpdf->SetTitle("Laporan");

  $mpdf->setHTMLHeader($header);

  $mpdf->setHTMLFooter($footer);

  $mpdf->WriteHTML($content);

  $mpdf->Output("laporan.pdf", "I");
} catch (Exception $e) {

  echo $e;

  exit;
}

?>