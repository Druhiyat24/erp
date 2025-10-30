<?php
include '../../include/conn.php'; // pastikan koneksi aktif

$id_jo = isset($_GET['id_jo']) ? $_GET['id_jo'] : ''; 
$dest = isset($_GET['dest']) ? $_GET['dest'] : '';

// jika export ke Excel
if ($dest == 'excel') {
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=BOM GLOBAL ITEM $id_jo.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

$query = "SELECT 
    jo.id AS id_jo,
    jo.jo_no,
    ac.styleno,
    ac.kpno,
    ms_buyer.supplier AS buyer,
    ms_supplier.supplier AS supplier_material,
    c.matclass,
    mp.product_group,
    mp.product_item,
    a.id_item,
    c.itemdesc,
    concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) AS nama_contents,
    SUM(a.qty) AS qty_bom,
    ROUND(COALESCE(SUM(bpb.qty),0),2) AS qty_in,
    a.unit,
    a.rule_bom,
    a.username,
    a.dateinput
FROM bom_jo_global_item a
INNER JOIN mastercontents b ON a.id_contents = b.id
INNER JOIN mastertype2 y ON b.id_type = y.id
INNER JOIN mastersubgroup x ON y.id_sub_group = x.id
INNER JOIN mastergroup w ON x.id_group = w.id
INNER JOIN masteritem c ON a.id_item = c.id_item
INNER JOIN mastersupplier ms_supplier ON a.id_supplier = ms_supplier.id_supplier
INNER JOIN jo ON a.id_jo = jo.id
INNER JOIN jo_det jd ON a.id_jo = jd.id_jo
INNER JOIN so ON jd.id_so = so.id
INNER JOIN act_costing ac ON so.id_cost = ac.id
INNER JOIN masterproduct mp ON ac.id_product = mp.id
INNER JOIN mastersupplier ms_buyer ON ac.id_buyer = ms_buyer.id_supplier
LEFT JOIN (
    SELECT SUM(qty) qty,id_jo,id_supplier,id_item 
    FROM bpb 
    GROUP BY id_item, id_jo, id_supplier
) bpb ON a.id_jo = bpb.id_jo AND a.id_item = bpb.id_item AND a.id_supplier = bpb.id_supplier
WHERE a.id_jo = '$id_jo'
GROUP BY a.id_item,a.id_jo,a.id_supplier
ORDER BY 
    CASE 
        WHEN c.matclass = 'Fabric' THEN 1
        WHEN c.matclass = 'Accesories Packing' THEN 2
        WHEN c.matclass = 'Accesories Sewing' THEN 3
        ELSE 4
    END,
    a.dateinput ASC;";
$result = mysql_query($query);

echo "<table border='1'>";
echo "<tr>
<th>JO No</th>
<th>Style No</th>
<th>KP No</th>
<th>Buyer</th>
<th>Jenis Item</th>
<th>Product Group</th>
<th>Product Item</th>
<th>Item Description</th>
<th>Qty</th>
<th>Qty Terima</th>
<th>Unit</th>
<th>Supplier Material</th>
</tr>";

while ($row = mysql_fetch_array($result)) {
    echo "<tr>
    <td>".$row['jo_no']."</td>
    <td>".$row['styleno']."</td>
    <td>".$row['kpno']."</td>
    <td>".$row['buyer']."</td>
    <td>".$row['matclass']."</td>
    <td>".$row['product_group']."</td>
    <td>".$row['product_item']."</td>
    <td>".$row['itemdesc']."</td>
    <td align='right'>".$row['qty_bom']."</td>
    <td align='right'>".$row['qty_in']."</td>
    <td>".$row['unit']."</td>
    <td>".$row['supplier_material']."</td>	
    </tr>";
}
echo "</table>";

?>
