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

echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='index.php?mod=14&mode=$mode&id=$id_item&dest=xls'>Save To Excel</a></br>"; }
    $SQL = "select a.*,$fldmst,s.goods_code,
        s.color,s.size 
        from upload_tpb a inner join $tblmst s on a.id_item=s.id_item 
        where a.username='$user' and sesi='$sesi' order by tanggal_aju ";
    $query = mysql_query($SQL);
    $data = mysql_fetch_array($query);
    echo "Klasifikasi : ".$data['matclass']; 
    echo "<br>Kode Barang : ".$data['goods_code']; 
    $kode_lama=flookup("mo.goods_code","masteritem mi inner join masteritem_odo mo", 
      "mi.id_item='$id_item'");
    echo "<br>Kode Lama : ".$kode_lama; 
    echo "<br>Deskripsi : ".$data['itemdesc']; 
    echo "<br>Warna : ".$data['color']; 
    echo "<br>Ukuran : ".$data['size']; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="Y") {$tbl_border="border='1'";} else {$tbl_border="";}
    echo "
    <table id='examplefix' $tbl_border class='display responsive' style='width:100%;font-size:10px;'>
      <thead>
        <tr>
          <th>No</th>
          <th>Jenis Dok</th>
          <th>Trans #</th>
          <th>Tgl. Trans</th>
          <th>PO # / Style #</th>
          <th>Supplier</th>
          <th>Style #</th>
          <th>WS #</th>
          <th>Rak #</th>
          <th>Terima</th>
          <th>Keluar</th>
          <th>Sisa</th>
        </tr>
      </thead>";
      
      insert_log("SET @runtot:=0",$user);
      $SQL = "SELECT
        q1.URAIAN_DOKUMEN,q1.NOMOR_AJU,q1.TANGGAL_AJU,q1.pono,q1.SUPPLIER,q1.blank2, 
        q1.masuk,q1.keluar,
        if(q1.SUPPLIER = 'OPENING BALANCE',(@runtot := 0 + (q1.masuk)), (@runtot := @runtot + (q1.masuk-q1.keluar)) ) AS rt
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
        { if ($data['rt']<0) {$bgcol=" style='background-color: red;color:yellow;'";} else {$bgcol="";}
          echo "
          <tr $bgcol>
            <td>$no</td>
            <td>$data[URAIAN_DOKUMEN]</td>
            <td>$data[NOMOR_AJU]</td>
            <td>$data[TANGGAL_AJU]</td>
            <td>$data[pono]</td>
            <td>$data[SUPPLIER]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[rakno]</td>
            <td>$data[masuk]</td>
            <td>$data[keluar]</td>
            <td>$data[rt]</td>
          </tr>";
        }
      echo "</tbody>
    </table>";
    $SQL = "delete from upload_tpb where username='$user' and sesi='$sesi' ";
    insert_log($SQL,$user);
  echo "</div>";
echo "</div>";
?>  