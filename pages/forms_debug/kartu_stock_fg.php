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

$tblmst="masteritem"; 
$cribpb="bpbno not like 'FG%'";
$cribppb="bppbno not like 'SJ-FG%'";
$fldmst="s.matclass,s.itemdesc";
$stylenya=flookup("styleno","masterstyle","id_item='$id_item'");

$SQL = "delete from upload_tpb where userNAme='$user' and sesi='$sesi'";
insert_log($SQL,$user);

$SQL = "insert into upload_tpb (username,sesi,id_item,tanggal_aju,nomor_aju,supplier,
  jumlah_satuan,id_supplier,kode_barang,
  harga_penyerahan,stock,URAIAN_DOKUMEN) select '$user','$sesi',a.id_item,bppbdate,bppbno,
  s.supplier,0,'',sent_to,qty,0,concat(jenis_dok,' (',bcno,')') 
  from bppb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
  inner join $tblmst d on a.id_item=d.id_item
  where goods_code='$stylenya' and $cribppb order by bppbdate";
insert_log($SQL,$user);

$SQL = "insert into upload_tpb (username,sesi,id_item,tanggal_aju,nomor_aju,supplier,jumlah_satuan,id_supplier
  ,kode_barang,harga_penyerahan,stock,URAIAN_DOKUMEN) select '$user','$sesi',a.id_item,bpbdate,bpbno,s.supplier,0,s.supplier,
  '' kode_barang,qty,0,concat(jenis_dok,' (',bcno,')') 
  from bpb a inner join mastersupplier s on a.id_supplier=s.id_supplier 
  inner join $tblmst d on a.id_item=d.id_item
  where goods_code='$stylenya' and $cribpb order by bpbdate";
insert_log($SQL,$user);
echo "<div class='box'>";
  echo "<div class='box-body'>";
    if ($excel=="N") { echo "<a href='index.php?mod=14&mode=$mode&id=$id_item&dest=xls'>Save To Excel</a></br>"; }
    $SQL = "select * from masterstyle where id_item='$id_item' ";
    $query = mysql_query($SQL);
    $data = mysql_fetch_array($query);
    echo "Style # : ".$data['Styleno']; 
    echo "<br>Kode Barang : ".$data['goods_code']; 
    echo "<br>Deskripsi : ".$data['itemname']; 
    echo "<br>Warna : ".$data['Color']; 
    echo "<br>Ukuran : ".$data['Size']; 
  echo "</div>";
echo "</div>";
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<table border='1' class='table table-bordered'>";
    	echo "<thead>";
        echo "<tr>";
          echo "<th>No.</th>";
          echo "<th>Jenis Dok</th>";
          echo "<th>Trans #</th>";
          echo "<th>Tgl. Trans</th>";
          echo "<th>PO #</th>";
          echo "<th>Supplier</th>";
          echo "<th>Remark</th>";
          echo "<th>Jumlah</th>";
        echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      
      $SQL = "SELECT
        q1.URAIAN_DOKUMEN,q1.NOMOR_AJU,q1.TANGGAL_AJU,q1.blank1,q1.SUPPLIER,q1.blank2, 
        q1.masuk
        FROM
        ( select URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,'' blank1,SUPPLIER,
          '' blank2 ,round(HARGA_PENYERAHAN,2) masuk 
          from upload_tpb a inner join $tblmst s on a.id_item=s.id_item 
          where a.username='$user' and sesi='$sesi' 
          order by TANGGAL_AJU asc, HARGA_PENYERAHAN desc
        ) AS q1 order by q1.TANGGAL_AJU asc, q1.masuk desc";
      tampil_data($SQL,7);

      #insert_log("set @sbal=0",$user);
    	#$SQL = "aselect URAIAN_DOKUMEN,NOMOR_AJU,TANGGAL_AJU,'' blank1,SUPPLIER,'' blank2
      #  ,round(HARGA_PENYERAHAN,2),round(STOCK,2),
      #  round(@sbal:=@sbal+(HARGA_PENYERAHAN-STOCK),2) sbal 
    	#  from upload_tpb a inner join $tblmst s on a.id_item=s.id_item 
    	#  where a.username='$user' and sesi='$sesi' order by tanggal_aju asc ";
    	#tampil_data($SQL,9);

      #$SQL = "delete from upload_tpb where username='$user' and sesi='$sesi2' ";
      #insert_log($SQL,$user);
      $SQL = "delete from upload_tpb where username='$user' and sesi='$sesi' ";
      insert_log($SQL,$user);
      
      echo "</tbody>";
    echo "</table>";
  echo "</div>";
echo "</div>";
?>  