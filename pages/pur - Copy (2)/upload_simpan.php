<!-- import excel ke mysql -->
<!-- www.malasngoding.com -->

<?php
// menghubungkan dengan koneksi
include "../../include/conn.php";
// menghubungkan dengan library excel reader
include "excel_reader.php";
session_start();
$dateinput        = date('Y-m-d H:i:s');
$id = $_GET['id_po'];
$user = $_SESSION['username'];
?>

<?php

mysql_query("INSERT INTO po_item_draft (id_po_draft, id_jo, id_gen, qty, unit, curr, price, cancel)
SELECT  
a.id_po_draft,
jo.id id_jo,
mi.id_gen,
a.qty,
a.unit,
pr.curr,
a.price,
'N'
FROM po_item_draft_upload a
left join masteritem mi on a.itemname = mi.nm_bom
left join jo on a.jo = jo.jo_no
left join (
select id_po_draft, curr from po_item_draft where id_po_draft= '$id' and cancel = 'N' limit 1 
) pr on a.id_po_draft = pr.id_po_draft
left join (
select k.id,k.id_jo, k.id_item from bom_jo_item k
inner join jo on k.id_jo = jo.id
where jo_date >= '2023-01-01'
group by id_jo, id_item
) k on jo.id = k.id_jo and mi.id_gen = k.id_item
where a.id_po_draft = '$id' and if(k.id is null,'Check BOM','Ok') = 'Ok'
");

mysql_query("DELETE a FROM po_item_draft_upload a
left join masteritem mi on a.itemname = mi.nm_bom
left join jo on a.jo = jo.jo_no
left join (
select id_po_draft, curr from po_item_draft where id_po_draft= '$id' and cancel = 'N' limit 1 
) pr on a.id_po_draft = pr.id_po_draft
left join (
select k.id,k.id_jo, k.id_item from bom_jo_item k
inner join jo on k.id_jo = jo.id
where jo_date >= '2023-01-01'
group by id_jo, id_item
) k on jo.id = k.id_jo and mi.id_gen = k.id_item
where a.id_po_draft = '$id' and if(k.id is null,'Check BOM','Ok') = 'Ok'
");


// alihkan halaman ke index.php
$_SESSION['msg'] = "Data Berhasil di Simpan";
echo "<script>window.location.href='../pur/?mod=33z&id=$id';</script>";
?>