<?php
if (isset($_GET['parfromv']))
{ $toexcel="Y";
  $rpt="mut";
  header("Content-type: application/octet-stream"); 
  header("Content-Disposition: attachment; filename=$rpt.xls");//ganti nama sesuai keperluan 
  header("Pragma: no-cache"); 
  header("Expires: 0");
}
else
{ $toexcel = "N"; }

$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $nm_company = $rscomp["company"];
  $st_company = $rscomp["status_company"];
  $logo_company = $rscomp["logo_company"];
// session_start();
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user = $_SESSION['username']; #mendapatkan data id  dari method get
$sesi = $_SESSION['sesi'];;
if (isset($_GET['rptid'])) { $rpt = $_GET['rptid']; } else { $rpt = ""; }

if ($nm_company=="PT. Seyang Indonesia")
{$mattypenya = "";}
else
{$mattypenya = "";}


if  ($st_company!="KITE" AND $rpt=='fg_out_invoice') {$header_cap="LAPORAN PERTANGGUNGJAWABAN MUTASI BARANG JADI";}


if (isset($_GET['parfromv']))
{ $tglf = $_GET['parfrom'];
  $perf = date('d F Y', strtotime($tglf));
  $tglt = $_GET['parto'];
  $pert = date('d F Y', strtotime($tglt));
}
else
{ $tglf = fd($_POST['txtfrom']);
  $perf = date('d F Y', strtotime($tglf));
  $tglt = fd($_POST['txtto']);
  $pert = date('d F Y', strtotime($tglt));

}

   if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }


//----------------------------------------------------------------------------------------------------------------

//if (isset($_POST['txtparitem'])) { $f_class=" and matclass='".$_POST['txtparitem']."'"; } else { $f_class=""; }
$sql="X".$header_cap."-".$rpt." Dari ".$perf." s/d ".$pert;

insert_log($sql,$user);
?>

<html>
<head>
<title><?PHP echo $header_cap;?></title>
</head>
<body>
  <?PHP
     

    if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
    { echo "<form method='post' name='form' action='save_hasil_opname.php?tgl=$tglt&mode=$rpt'>"; }
    if ($rpt=='gb_bahanbaku')
    { echo "GUDANG BERIKAT "; echo strtoupper($nm_company); }
    elseif ($st_company=="KITE")
    { echo $header_cap; echo "<br>"; echo strtoupper($nm_company); }
    elseif ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg_out_invoice")
    { echo "KAWASAN BERIKAT "; echo strtoupper($nm_company); }
    echo "<br>";
    echo "MUTASI BARANG JADI STOK ";
    echo "<br>";
    if ($st_company!="KITE" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg_out_invoice") { echo "<br>"; echo $header_cap; }
    if ($rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg_out_invoice") 
    { echo "PERIODE "; echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
      echo "<br>";
    }
    else
    { if ($rpt=="hasil")
      { echo "INPUT HASIL STOCK OPNAME BAHAN BAKU DAN PENOLONG PERIODE "; }
      else if ($rpt=="hasilsl")
      { echo "INPUT HASIL STOCK OPNAME SCRAP / LIMBAH PERIODE "; }
      else if ($rpt=="hasilwip")
      { echo "INPUT HASIL STOCK OPNAME BARANG DALAM PROSES PERIODE "; }
      else if ($rpt=="hasilmes")
      { echo "INPUT HASIL STOCK OPNAME MESIN PERIODE "; }
      else if ($rpt=="hasilfg")
      { echo "INPUT HASIL STOCK OPNAME BARANG JADI PERIODE "; }
      echo strtoupper($perf); echo " S/D "; echo strtoupper($pert); 
    }
  ?>
<?PHP 
  if ($toexcel!="Y" AND $rpt!="hasil" AND $rpt!="hasilsl" AND $rpt!="hasilwip" AND $rpt!="hasilmes" AND $rpt!="hasilfg")
  { 
      echo "<a class='btn btn-info btn-sm' href='?mod=view_mut_fg_out_invoice&parfrom=$tglf&parto=$tglt&parfromv=$perf&partov=$pert&rptid=$rpt&dest=xls'>Save To Excel</a>"; 
      echo "<br>";
      echo "-";
    
  //---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
  }


  if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg_out_invoice")
  { echo "<table id='examplefix3' class='table table-bordered table-striped' style='font-size:11px;'>"; }
  else
  { echo "<table id='examplefix3' width='100%' border='1' style='font-size:12px;' class='table table-bordered table-striped'>"; }
  ?>
  <thead>
  <tr>
    <th>NO.</th>
    <?php
     if ($rpt=="hasil")
      { echo "<th>KLASIFIKASI</th>"; }
      echo "<th>KODE BARANG</th>";
      echo "<th>NAMA BARANG </th>";
      echo "<th>NO. STYLE</th>";
      echo "<th>NO WS</th>";
      echo "<th>COLOR</th>";
      echo "<th>SIZE</th>";
      echo "<th>GRADE</th>";
      echo "
        <th>SALDO AWAL</th>
        <th>PENERIMAAN (IN)</th>
        <th>PENGELUARAN (OUT)</th>
        <th>SALDO AKHIR</th>";

    ?>
  </tr>
  </thead>
  <tbody>
  <?php
  if ($tbl_master=="masterstyle") 
  { $sql_add=",kpno,itemname,color"; }
  else if ($tbl_master=="masteritem") 
  { $sql_add=",matclass"; } 
  else 
  { $sql_add=""; }

  // $sqlk = "select  $kdnya kode_brg,$fld_item nama_brg,
  //  sum(ifnull(s.saldo_akhir,0)) saldo_awal,
  //  sum(ifnull((d.qtybpb_curr+d.qtyri_curr),0)) qtyrcv,
  //  sum(ifnull((d.qtybppb_curr+d.qtyro_curr),0)) qtyout,
  //  if(max(d.unit)='' or isnull(max(d.unit)),max(s.unit),max(d.unit)) unit,
  //  a.id_item $sql_add 
  //  from $tbl_master a left join vmut_gab_curr d on a.id_item=d.id_item 
  //  left join vmut_gab_before s on a.id_item=s.id_item $cri_mat group by $grnya order by $ordernya";
  //echo( $tbl_master);
  // $sqlk = "SELECT MGB.goods_code as kode_brg,MGB.itemname as nama_brg,MGA.no_ws,MGA.styleno,MGA.color,MGA.size,'PCS' AS unit,
  //      SUM(MGA.QTYSAWAL) AS saldoawal,SUM(MGA.QTYIN) AS qtyin,SUM(MGA.QTYRI) AS qtyri, SUM(MGA.QTYOUT) AS qtyout, SUM(MGA.QTYRO) AS qtyro 
  //      FROM
  //      (SELECT distinct A.NO_WS,b.styleno,A.COLOR,A.SIZE,A.QTY AS QTYSAWAL,0 AS QTYIN, 0 AS QTYRI,0 AS QTYOUT, 0 AS QTYRO
  //      FROM saldoawal_gd AS a
  //      INNER JOIN act_costing AS B ON A.NO_WS =B.kpno
  //      WHERE a.PERIODE='$tglf' AND A.KAT='FG' 
  //      GROUP BY A.NO_WS,b.styleno,A.COLOR,A.SIZE
  //      UNION 
  //      SELECT TZ.kpno AS no_ws,TY.Styleno,ty.Color,ty.Size,0 AS QTYSAWAL, SUM(TX.QTYIN) AS QTYIN, SUM(TX.QTYRI) AS QTYRI, SUM(TX.QTYOUT) AS QTYOUT, SUM(TX.QTYRO) AS QTYRO
  //      FROM 
  //      (SELECT a.id_item, SUM(a.qty) qtyin,0 AS qtyri,0 AS QTYOUT, 0 AS QTYRO
  //      FROM bpb a INNER JOIN masterstyle s ON a.id_item=s.id_item 
  //      WHERE a.qty>0 AND LEFT(a.bpbno,2)='FG' AND A.bpbno_int LIKE '%IN%' 
  //      AND a.bpbdate>='$tglf' AND a.bpbdate<='$tglt' 
  //      GROUP BY s.id_item
  //      UNION
  //      SELECT a.id_item,0 AS qtyin, SUM(a.qty) qtyri,0 AS QTYOUT, 0 AS QTYRO
  //      FROM bpb a INNER JOIN masterstyle s ON a.id_item=s.id_item 
  //      WHERE a.qty>0 AND LEFT(a.bpbno,2)='FG' AND A.bpbno_int LIKE '%RI%' 
  //      AND a.bpbdate>='$tglf' AND a.bpbdate<='$tglt'  
  //      GROUP BY s.id_item
  //      UNION
  //      SELECT a.id_item,0 AS QTYIN, 0 AS QTYRI,SUM(a.qty) QtyOUT,0 AS QTYRO  
  //      FROM bppb a INNER JOIN masterstyle s ON a.id_item=s.id_item 
  //      WHERE a.qty>0 AND MID(a.bppbno,4,2)='FG' AND a.bppbno_int LIKE 'FG/OUT%' AND a.bppbdate>='$tglf' AND a.bppbdate<='$tglt'  
  //      GROUP BY s.id_item
  //      UNION
  //      SELECT a.id_item,0 AS QTYIN, 0 AS QTYRI,SUM(a.qty) QtyOUT,0 AS QTYRO
  //      FROM bppb a INNER JOIN masterstyle s ON a.id_item=s.id_item 
  //      WHERE a.qty>0 AND MID(a.bppbno,4,2)='FG' AND a.bppbno_int LIKE 'FG/RO%' AND a.bppbdate>='$tglf' AND a.bppbdate<='$tglt' 
  //      GROUP BY s.id_item) AS TX
  //      INNER JOIN masterstyle AS TY ON TX.ID_ITEM=TY.id_item
  //      INNER JOIN act_costing AS TZ ON TY.Styleno=TZ.styleno
  //      WHERE TY.Styleno<>''
  //      GROUP BY TZ.kpno,TY.Styleno,ty.Color,ty.Size) AS MGA
  //      INNER JOIN masterstyle AS MGB ON MGA.STYLENO = MGB.Styleno
  //      GROUP BY MGB.goods_code,MGB.itemname,MGA.NO_WS,MGA.STYLENO,MGA.COLOR,MGA.SIZE
  //      ";

  $sqlk ="select if(bppbno_int!='',bppbno_int,bppbno) bppbno,bppbdate,invno,jenis_dok,right(nomor_aju,6),tanggal_aju, lpad(bcno,6,'0') bcno,bcdate,supplier,a.id_item,goods_code,itemname itemdesc,s.color,s.size, a.qty,0 as qty_good,0 as qty_reject, a.unit,berat_bersih,remark,a.username,ac.kpno ws,ac.styleno,a.curr,a.price,inv.v_noinvoicecommercial,a.switch_out,so.so_no, a.stat_inv 
from bppb a inner join masterstyle s on a.id_item=s.id_item inner join mastersupplier d on a.id_supplier=d.id_supplier left join (select sod.id_so,sod.id id_so_det from so_det sod group by sod.id) tmpjod on tmpjod.id_so_det=a.id_so_det left join so on tmpjod.id_so=so.id left join act_costing ac on so.id_cost=ac.id left join (select a.id_inv,a.bppbno as bppbout,c.v_noinvoicecommercial,c.v_userpost from invoice_detail a inner join bppb b on a.bppbno = b.bppbno_int inner join invoice_commercial c on a.id_inv = c.n_idinvoiceheader where c.v_userpost != 'null' group by a.id_inv) inv on a.bppbno_int = inv.bppbout where mid(bppbno,4,2) in ('FG') and bppbdate between '2022-01-01' and '2022-01-18' order by bppbdate";

  //MSGBOX
  echo ($sqlk);
  // echo mysql_error($sqlk);
  //MSGBOX
  $sql = mysql_query ($sqlk);
  $tdata=mysql_num_rows($sql);
  #echo $sqlk;
  #CETAK
  $no = 1; #nomor awal
  while($data = mysql_fetch_array($sql))
  { #ketika data tabel mahasiswa di array kan maka lakukan perulangan hingga mahasiswa terakhir
    $i=$no;
    #$id=$data['id_item'];

    $kode_barang=$data['bppbno'];
    $nama_barang=$data['bppbno'];
    $nostyle=$data['bppbno'];
    
    $idws=$data['bppbno'];
    $color=$data['bppbno'];
    $size=$data['bppbno'];
    // $sat=$data['WS_ID'];
    $grade=$data['bppbno'];
    
    $sawal=round($data['qty'],2);
    
    $qtyin=round($data['qty'],2);

    $qtyout=round($data['qty'],2);

    $sakhir=($sawal+$qtyin)-$qtyout;

    // $filter=$sawal+$qtyTerima+$qtyKeluar;

    // $sakhir=($sawal+$qtyTerima)-$qtyKeluar;
    // $penyesuain =0;

    // $sakhir=round($sakhir,2);


    { 
      echo "
      <tr>
        <td align='center'>$no</td>";
        { 
          echo "<td>$kode_barang</td>
            <td>$nama_barang</td>
            <td>$nostyle</td>
            <td>$idws</td>
            <td>$color</td>
            <td>$size</td>
            <td>$grade</td>";
          }
        if ($rpt!="mwiptot")
        { echo "
            <td align='right'>$grade</td>
            <td align='right'>$grade</td>
            <td align='right'>$grade</td>
            <td align='right'>$grade</td>"; 
          }
        }
                
      echo "
      </tr>
      ";
      $no++;
    }
  ; #$no bertambah 1
  ?>
  </tbody>
</table>
<?php 
  if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
  { echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>"; }
?>
<p>&nbsp;</p>
<?php 
if ($rpt=="hasil" OR $rpt=="hasilsl" OR $rpt=="hasilwip" OR $rpt=="hasilmes" OR $rpt=="hasilfg")
{ echo "</form>"; }
?>
</body>
</html>