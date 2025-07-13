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



$no_dok = $_GET['no_dok'];

// $sqlcek = "select jns_inv from memo_h where id_h = '$id_h'";
// $ceksql = mysql_query($sqlcek);
// $cekdata = mysql_fetch_array($ceksql);
// $jns_inv = $cekdata['jns_inv'];

// $sqltotal = "select concat(IF(curr = 'IDR', 'Rp. ', '$ '),format(round(sum(biaya),2),2)) total_biaya from memo_det det
// inner join memo_h h on det.id_h = h.id_h
// where det.id_h = '$id_h' and det.cancel = 'N'";

// $tolsql = mysql_query($sqltotal);
// $total = mysql_fetch_array($tolsql);
// $totalbiaya = $total['total_biaya'];


$tglcetak = "Dicetak : " . date('Y-m-d H:i');
$ukuran_kertas = "A4";
$orientasi_kertas = "P"; # L = Landscape P = Portrait


$head_cap = "BUKTI RETUR BARANG";

$sql = "select a.*, c.supplier from retur_material a
inner join out_material b on a.no_out = b.no_out
inner join req_material c on a.no_req = c.no_req
where no_retur = '$no_dok' and a.cancel = 'N' limit 1";
$rsh = mysql_fetch_array(mysql_query($sql));
$notes   = $rsh['notes'];
$head_data = '
  <table width="100%" style="border:none; font-size:9pt">
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr> 
    <tr>
      <td  width = "20%"></td>
      <td  width = "35%"></td>
    </tr>
    <tr>
      <td></td>
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
      <td></td>
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
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Nama Supplier</td>
      <td> : ' . $rsh['supplier'] . '</td>
      <td>Tanggal Dokumen</td>
      <td> : ' . fd_view($rsh['tgl_retur']) . '</td>
    </tr>           
    <tr>
      <td>Nomor Pengeluaran</td>
      <td> : ' . $rsh['no_out'] . '</td>
  <td>Nomor Request</td>
  <td> : ' . $rsh['no_req'] . '</td>
    </tr>    
    <tr>
    <td>Tipe Pembelian</td>
    <td> : ' . $rsh['tipe_pembelian'] . '</td>
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
  <td>Keterangan</td>
  <td colspan = "3"> : ' . $rsh['keterangan'] . '</td>
  </tr>';

$head_data = $head_data . '
  </table>';

$footernyaX = "";
$footernya = "";

?>
<br>
<br>
<br>
<table width="100%" style="border:none; font-size:9pt">
  <tr>
    <td width="20%">Detail</td>
    <td width="35%">:</td>
    <td width="20%"></td>
    <td width="25%"></td>
  </tr>
</table>

<table class='main' border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:10pt;">

  <thead>
    <!-- <tr>
<th align='center' colspan = "5">test</th>
</tr>  -->


    <tr class="head">

      <td align='center' height="20">No.</td>

      <td align='center'>Kode Barang</td>

      <td align='center'>Nama Barang</td>

      <td align='center'>Job Order</td>

      <td align='center'>Qty</td>

      <td align='center'>Unit</td>

    </tr>

  </thead>

  <tbody>

    <?php

    tampil_data("select kode_barang,nama_barang,job_order,sum(qty) qty,unit from retur_material where no_retur = '$no_dok' and cancel = 'N' group by kode_barang, nama_barang, job_order, unit", 5);
    $sqlsum = "select sum(qty) jml_qty from retur_material where no_retur = '$no_dok'";
    $resultsum = mysql_fetch_array(mysql_query($sqlsum));
    $jml_qty   = $resultsum['jml_qty'];
    echo '<tr>
      <td colspan="4" align="center"><b>Total</b></td>
      <td align="right">' . $jml_qty . '</td>
      <td></td>
    </tr>';

    // echo "

    // <tr>

    //   <td colspan='$jml_fld_sp' align='right'>Total </td>

    //   <td align='right'>".round($tqty,2)."</td>

    // </tr>

    // ";



    ?>

  </tbody>

</table>
<br>
<br>
<table class='main' repeat_header="1" border="1" cellspacing="0" width="100%" style="border-collapse: collapse; width:100%; font-size:9pt;">
  <thead>
    <!-- <tr>
<th align='center' colspan = "5">test</th>
</tr>  -->


    <tr class="head">

      <td align='center'>Dibuat :</td>

      <td align='center'>Diperiksa :</td>

      <td align='center'>Disetujui :</td>

      <td align='center'>Diketahui :</td>

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
      <td align='center'>Admin Gudang</td>
      <td align='center'>Manager Gudang</td>
      <td align='center'></td>
      <td align='center'></td>
    </tr>
  </tfoot>


</table>

<?php

$header = $footernya . '<table cellpadding=0 cellspacing=0 style="border:none;">

            <tr>

              <td style="border:none;" align="left">' . $logo . '</td>

              <td width="100%" style="border:none;"><h4>' . $nm_company . '</h4></td>

              <td width="100%" style="border:none; font-size:7pt;">' . $kota . '</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add_company . '</td>

              <td width="100%" style="border:none; font-size:7pt;">' . $sentto . '</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add2_company . '</td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add_sentto . '</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add3_company . '</td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add2_sentto . '</td>

            </tr>

            <tr>

              <td></td>

              <td width="100%" style="border:none; font-size:7pt;">' . $add4_company . '</td>

            </tr>

          </table>

          <table width="100%" style="border:none;">

            <tr>
              <td align="center" style="border:none;"><h2>' . $head_cap . '</h2></td>
            </tr>
                        <tr>
              <td align="center" style="border:none;"><h2>' . $rsh['no_retur'] . '</h2></td>
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