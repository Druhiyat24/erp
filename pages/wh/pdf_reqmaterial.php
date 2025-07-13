<?php

include('../../mpdf57/mpdf.php');

include '../../include/conn.php';

require_once '../wh/phpqrcode-master/qrlib.php';

include 'fungsi.php';

ob_start();

$penyimpanan = "temp/";
if (!file_exists($penyimpanan))
  mkdir($penyimpanan);


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


$head_cap = "REQUEST MATERIAL";

$sql = "select *,REPLACE(no_req,'/','') simpan from req_material where no_req = '$no_dok'";
$rsh = mysql_fetch_array(mysql_query($sql));
$dokumen   = $rsh['no_req'];
$simpan = $rsh['simpan'];
QRcode::png($dokumen, $penyimpanan . $simpan . ".png", QR_ECLEVEL_L, 3, 3);
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
      <td width = "20%" >Nomor Request</td>
      <td width = "25%"><b> : ' . $rsh['no_req'] . '</b></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>Tanggal Request</td>
      <td><b> : ' . fd_view($rsh['tgl_req']) . '</b></td>
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
      <td rowspan = "3" style="text-align: right;"> <img src ="' . $penyimpanan  . $simpan . '.png" alt = "" height="70" width = "70" ></td>
      <td></td>
    </tr>           
    <tr>
    <td>Tipe Pengeluaran</td>
    <td> : ' . $rsh['tipe_pengeluaran'] . '</td>
    <td></td>
  </tr>    
  <tr>  
  <td>Keterangan</td>
  <td> : ' . $rsh['keterangan'] . '</td>
  <td></td>
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

      <td width='5%' align='center' height="20">No.</td>
      <td width='25%' align='center'>Kode Barang</td>
      <td width='25%' align='center'>Nama Barang</td>
      <td width='15%' align='center'>Job Order</td>
      <td width='15%' align='center'>Rak</td>
      <td width='10%' align='center'>Qty Req</td>
      <td width='10%' align='center'>Unit</td>

    </tr>

  </thead>

  <tbody>

    <?php

    tampil_data("select a.kode_barang,a.nama_barang,a.job_order, b.kode_rak_sisa, a.qty qty_req,a.unit  from
    (select * from req_material
    where no_req = '$no_dok')a 
    inner join 
    (select kode_rak, job_order,kode_barang, nama_barang, concat(kode_rak,'(',sum(roll_qty),')') kode_rak_sisa, unit 
    from in_material_det a
    inner join in_material b on a.id_in_material = b.id
    where a.cancel = 'N' and b.cancel = 'N'
    group by kode_rak, b.kode_barang, b.nama_barang, b.unit) b ON
    a.kode_barang = b.kode_barang and a.nama_barang = b.nama_barang and a.unit = b.unit and a.job_order = b.job_order", 6);
    $sqlsum = "select sum(qty) jml_qty from req_material where no_req = '$no_dok'";
    $resultsum = mysql_fetch_array(mysql_query($sqlsum));
    $jml_qty   = $resultsum['jml_qty'];
    echo '<tr>
      <td colspan="5" align="center"><b>Total</b></td>
      <td align="right"><b>' . $jml_qty . '</b></td>
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

      <td align='center'>Dibuat Oleh :</td>

      <td align='center'>Disetujui Oleh :</td>

      <td align='center'>Diketahui Oleh :</td>

    </tr>

  </thead>

  <tbody>
    <tr>
      <td height="50"></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td align='center'>Staff Cutting</td>
      <td align='center'>Manager Cutting</td>
      <td align='center'>Manager Gudang</td>
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