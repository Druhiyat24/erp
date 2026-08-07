<?php 
if (isset($_GET['dest']))
{ $excel = "Y";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=kartu_stock.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $excel = "N"; }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
$mode = $_GET['mode'];
$id_item = $_GET['id'];

if ($mode!="FG")
{ $tblmst="masteritem"; 
  $cribpb="bpbno not like 'FG%'";
  $cribppb="bppbno not like 'SJ-FG%'";
  $fldmst="s.matclass,s.itemdesc";
}
else
{ $tblmst="masterstyle"; 
  $cribpb="bpbno like 'FG%'";
  $cribppb="bppbno like 'SJ-FG%'";
  $fldmst="s.goods_code matclass,s.itemname itemdesc";
}

include "func_gen_kartu_stock.php";
gen_kartu_stock($user,$sesi,$id_item,$cribpb,$cribppb);

echo "<style>
      .ks-wrap {
        font-family: 'Inter','Segoe UI',system-ui,-apple-system,sans-serif;
        color: #1c2333;
      }
      .ks-section-head {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 20px; border-bottom: 1px solid #eef0f4;
      }
      .ks-section-head .ks-title {
        font-size: 15px; font-weight: 700; color: #1c2333; letter-spacing: .2px;
        display: flex; align-items: center; gap: 9px; margin: 0;
      }
      .ks-section-head .ks-title i { color: #6366f1; font-size: 17px; }
      .ks-section-head .ks-sub {
        font-size: 12px; color: #9aa1b4; font-weight: 500; margin-left: auto;
      }
      .ks-export-btn {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg,#10b981,#059669); color: #fff;
        border: 0; padding: 8px 16px; border-radius: 10px;
        font-size: 12.5px; font-weight: 600; box-shadow: 0 6px 16px rgba(16,185,129,.28);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        text-decoration: none;
      }
      .ks-export-btn:hover { filter: brightness(1.05); transform: translateY(-2px); box-shadow: 0 10px 22px rgba(16,185,129,.34); color: #fff; }
      .ks-card-wrap { padding: 18px 20px 4px; }
      .ks-info-grid { display: grid; grid-template-columns: repeat(auto-fill,minmax(185px,1fr)); gap: 14px; }
      .ks-card {
        position: relative; overflow: hidden; background: #fff;
        border: 1px solid #eef0f4; border-radius: 14px; padding: 14px 16px 14px 20px;
        box-shadow: 0 1px 2px rgba(16,24,40,.04), 0 1px 3px rgba(16,24,40,.06);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      }
      .ks-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
        background: linear-gradient(180deg,#6366f1,#8b5cf6); border-radius: 0 4px 4px 0;
      }
      .ks-card:nth-child(even)::before { background: linear-gradient(180deg,#0ea5e9,#6366f1); }
      .ks-card:hover { transform: translateY(-3px); box-shadow: 0 12px 26px rgba(16,24,40,.10); border-color: #dbe1ff; }
      .ks-card label {
        display: block; font-size: 10px; text-transform: uppercase; letter-spacing: .9px;
        font-weight: 700; color: #9aa1b4; margin: 0 0 7px 0;
      }
      .ks-card span { font-size: 14.5px; font-weight: 650; color: #1c2333; word-break: break-word; display: block; }
      .ks-table-scroll {
        overflow-x: auto; max-height: 500px; overflow-y: auto;
        border-top: 1px solid #eef0f4;
      }
      #example.ks-table { border-collapse: separate; border-spacing: 0; width: 100%; font-family: inherit; }
      #example.ks-table thead th {
        background: linear-gradient(135deg,#1f2937,#111827); color: #fff; padding: 12px 10px;
        text-align: center; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px;
        white-space: nowrap; position: sticky; top: 0; z-index: 2; border-bottom: 2px solid #6366f1;
      }
      #example.ks-table tbody td {
        padding: 10px 10px; text-align: center; font-size: 12px; vertical-align: middle;
        border-bottom: 1px solid #f1f3f7; color: #37415a;
      }
      #example.ks-table tbody tr { transition: background .15s ease; }
      #example.ks-table tbody tr:nth-child(even) { background: #fafbfd; }
      #example.ks-table tbody tr:hover { background: #eef0ff; }
      #example.ks-table .bg-negatif { background-color: #fff1f0 !important; }
      #example.ks-table .bg-negatif:hover { background-color: #ffe4e2 !important; }
      #example.ks-table td.rt-negatif { color: #e11d48 !important; font-weight: 700; }
      #example.ks-table .ks-doc { font-weight: 600; color: #1c2333; }
      #example.ks-table .ks-trans { font-family: 'JetBrains Mono','Consolas',monospace; font-size: 11.5px; color: #4f46e5; font-weight: 600; }
      #example.ks-table .ks-num { font-variant-numeric: tabular-nums; font-weight: 600; color: #1c2333; }
      .ks-badge {
        display: inline-block; padding: 3px 10px; border-radius: 999px;
        background: #eef2ff; color: #4f46e5; font-size: 11px; font-weight: 700; letter-spacing: .3px;
      }
      .text-right { text-align: right !important; }
      .text-center { text-align: center !important; }
    </style>";

    $SQL = "select a.*,$fldmst,s.goods_code,
        s.color,s.size 
        from upload_tpb a inner join $tblmst s on a.id_item=s.id_item 
        where a.username='$user' and sesi='$sesi' order by tanggal_aju limit 1";
    $query = mysql_query($SQL);
    $data = mysql_fetch_array($query);
    $kode_lama=flookup("mo.goods_code","masteritem mi inner join masteritem_odo mo", 
      "mi.id_item='$id_item'");

    echo "<div class='box' style='border:1px solid #eef0f4; border-radius:16px; box-shadow:0 4px 18px rgba(16,24,40,.06); overflow:hidden;'>";
      echo "<div class='ks-section-head'>";
        echo "<h3 class='ks-title'><i class='fa fa-barcode'></i> Info Barang</h3>";
        echo "<span class='ks-badge'>Kartu Stok</span>";
        if ($excel=="N") { 
          echo "<a class='ks-export-btn' href='index.php?mod=14&mode=$mode&id=$id_item&dest=xls'><i class='fa fa-file-excel-o'></i> Export Excel</a>";
        }
      echo "</div>";
      echo "<div class='ks-card-wrap'>";
        echo "<div class='ks-info-grid'>";
          echo "<div class='ks-card'><label>Klasifikasi</label><span>".$data['matclass']."</span></div>";
          echo "<div class='ks-card'><label>Kode Barang</label><span>".$data['goods_code']."</span></div>";
          if (!empty($kode_lama)) {
            echo "<div class='ks-card'><label>Kode Lama</label><span>$kode_lama</span></div>";
          }
          echo "<div class='ks-card'><label>Deskripsi</label><span>".$data['itemdesc']."</span></div>";
          echo "<div class='ks-card'><label>Warna</label><span>".$data['color']."</span></div>";
          echo "<div class='ks-card'><label>Ukuran</label><span>".$data['size']."</span></div>";
        echo "</div>";
      echo "</div>";
    echo "</div>";

    echo "<div class='box' style='border:1px solid #eef0f4; border-radius:16px; box-shadow:0 4px 18px rgba(16,24,40,.06); overflow:hidden;'>";
      echo "<div class='ks-section-head'>";
        echo "<h3 class='ks-title'><i class='fa fa-list-alt'></i> Riwayat Transaksi</h3>";
        echo "<span class='ks-sub'>Saldo berjalan &amp; mutasi</span>";
      echo "</div>";
      echo "<div style='padding:0;'>";
        if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
        echo "
        <div class='ks-table-scroll'>
        <table id='example' $tbl_border class='display responsive ks-table' style='width:100%;'>
          <thead>
            <tr>
              <th style='width:40px;'>No</th>
              <th>Jenis Dok</th>
              <th>Trans #</th>
              <th style='width:90px;'>Tgl. Trans</th>
              <th>PO / Style</th>
              <th>Supplier</th>
              <th>Style</th>
              <th>WS</th>
              <th>Rak</th>
              <th class='text-right'>Terima</th>
              <th class='text-right'>Keluar</th>
              <th class='text-right'>Sisa</th>
            </tr>
          </thead>";

      insert_log("SET @runtot:=0",$user);
      $SQL = "SELECT
        q1.URAIAN_DOKUMEN,q1.NOMOR_AJU,q1.TANGGAL_AJU,q1.pono,q1.SUPPLIER,q1.blank2, 
        q1.masuk,q1.keluar,
        (@runtot := @runtot + (q1.masuk-q1.keluar)) AS rt
        ,q1.styleno,q1.kpno,q1.rakno 
        FROM
        ( select URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,
        	pono,SUPPLIER,'' blank2 ,round(HARGA_PENYERAHAN,2) masuk,round(STOCK,2) keluar
          ,a.styleno,a.kpno,a.rakno  
          from upload_tpb a inner join $tblmst s on a.id_item=s.id_item 
          where a.username='$user' and sesi='$sesi' 
          order by TANGGAL_AJU asc, HARGA_PENYERAHAN desc
        ) AS q1 order by q1.TANGGAL_AJU asc, q1.masuk desc, q1.keluar desc";
      $query = mysql_query($SQL);

      if (!$query) { die($SQL. mysql_error()); }
      $no = 1; 
      echo "<tbody>";
        while($data = mysql_fetch_array($query))
        { $bgclass = ($data['rt'] < 0) ? " class='bg-negatif'" : "";
          $rtclass = ($data['rt'] < 0) ? " class='rt-negatif'" : "";
          
          // Format tanggal
          $tgl = $data['TANGGAL_AJU'];
          if (!empty($tgl) && $tgl != '0000-00-00') {
            $tgl = date('d/m/Y', strtotime($tgl));
          }
          
          echo "
          <tr$bgclass>
            <td class='text-center'>$no</td>
            <td class='ks-doc'>$data[URAIAN_DOKUMEN]</td>
            <td class='ks-trans'>$data[NOMOR_AJU]</td>
            <td class='text-center'>$tgl</td>
            <td>$data[pono]</td>
            <td>$data[SUPPLIER]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[rakno]</td>
            <td class='text-right ks-num'>$data[masuk]</td>
            <td class='text-right ks-num'>$data[keluar]</td>
            <td class='text-right ks-num'$rtclass>$data[rt]</td>
          </tr>";
          $no++;
        }
      echo "</tbody>
    </table></div>";
      $SQL = "delete from upload_tpb where username='$user' and sesi='$sesi' ";
      insert_log($SQL,$user);
    echo "</div>";
  echo "</div>";
?>