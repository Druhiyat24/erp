<?php
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_jo = isset($_POST['id_jo']) ? $_POST['id_jo'] : '6295';

header('Content-Type: application/json');

// --- QUERY UTAMA ---
$sql = "
    SELECT 
        k.status,
        s.id idsubgroup,
        k.id_item,
        l.color,
        l.size,
        CONCAT(
            a.nama_group,' ',s.nama_sub_group,' ',
            d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
            g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info
        ) item,
        l.qty qty_gmt,
        k.cons,
        ROUND(SUM(l.qty * k.cons),2) qty_bom,
        k.unit,
        m.supplier,
        m2.supplier supplier2,
        k.notes,
        IF(jo.app='W','Waiting','Approved') status_app,
        k.id_supplier,
        k.id_supplier2,
        a.id nama_group  
    FROM bom_jo_item k 
    INNER JOIN jo ON k.id_jo=jo.id 
    INNER JOIN so_det l ON k.id_so_det=l.id 
    INNER JOIN mastergroup a 
    INNER JOIN mastersubgroup s ON a.id=s.id_group
    INNER JOIN mastertype2 d ON s.id=d.id_sub_group
    INNER JOIN mastercontents e ON d.id=e.id_type
    INNER JOIN masterwidth f ON e.id=f.id_contents 
    INNER JOIN masterlength g ON f.id=g.id_width
    INNER JOIN masterweight h ON g.id=h.id_length
    INNER JOIN mastercolor i ON h.id=i.id_weight
    INNER JOIN masterdesc j ON i.id=j.id_color AND k.id_item=j.id 
    LEFT JOIN mastersupplier m ON k.id_supplier=m.id_supplier
    LEFT JOIN mastersupplier m2 ON k.id_supplier2=m2.id_supplier
    WHERE k.id_jo='$id_jo' 
      AND k.cancel='N' 
      AND k.status='M'
    GROUP BY k.id_item

    UNION ALL 

    SELECT 
        k.status,
        0 idsubgroup,
        k.id_item,
        l.color,
        l.size,
        CONCAT(mi.matclass,' ',mi.goods_code,' ',mi.itemdesc) item,
        l.qty qty_gmt,
        k.cons,
        ROUND(SUM(l.qty*k.cons),2) qty_bom,
        k.unit,
        m.supplier,
        m2.supplier supplier2,
        k.notes,
        IF(jo.app='W','Waiting','Approved') status_app,
        k.id_supplier,
        k.id_supplier2,
        '999' nama_group  
    FROM bom_jo_item k 
    INNER JOIN jo ON k.id_jo=jo.id 
    INNER JOIN so_det l ON k.id_so_det=l.id 
    INNER JOIN masteritem mi ON k.id_item=mi.id_item 
    INNER JOIN mastercf j ON mi.matclass=j.cfdesc 
    LEFT JOIN mastersupplier m ON k.id_supplier=m.id_supplier
    LEFT JOIN mastersupplier m2 ON k.id_supplier2=m2.id_supplier
    WHERE k.id_jo='$id_jo' 
      AND k.cancel='N' 
      AND k.status='P'
    GROUP BY k.id_item 
    ORDER BY nama_group ASC
";

$result = mysql_query($sql, $con);
if (!$result) {
    die(json_encode(["error" => mysql_error()]));
}

// --- PRE-FETCH DATA UNTUK OPTIMASI ---
$allow_all = [];
$q = mysql_query("SELECT * FROM masterallow");
while ($r = mysql_fetch_assoc($q)) {
    $allow_all[$r['id_sub_group']][] = $r;
}

// PO data
$po_all = [];
$qpo = mysql_query("
    SELECT a.id_gen, s.jenis, GROUP_CONCAT(DISTINCT CONCAT(pono,' ',podate)) AS cekpo, SUM(a.qty) AS cekqpo
    FROM po_item a
    INNER JOIN po_header s ON a.id_po=s.id
    WHERE a.id_jo='$id_jo' AND a.cancel='N'
    GROUP BY a.id_gen, s.jenis
");
while ($r = mysql_fetch_assoc($qpo)) {
    $po_all[$r['id_gen']][$r['jenis']] = $r;
}

// Transfer_post
$transfer_all = [];
$qtr = mysql_query("
    SELECT id_item, SUM(qty) AS bookqty
    FROM transfer_post
    WHERE status_app='Y' AND status_app_qc='Y' AND id_jo_to='$id_jo'
    GROUP BY id_item
");
while ($r = mysql_fetch_assoc($qtr)) {
    $transfer_all[$r['id_item']] = $r['bookqty'];
}

// Stock
$stock_all = [];
$qst = mysql_query("SELECT id_item, stock FROM stock");
while ($r = mysql_fetch_assoc($qst)) {
    $stock_all[$r['id_item']] = $r['stock'];
}

// Supplier master (sekali fetch)
$supplier_all = [];
$qsup = mysql_query("SELECT id_supplier, supplier FROM mastersupplier WHERE tipe_sup='S' AND non_aktif='0' ORDER BY supplier");
while ($r = mysql_fetch_assoc($qsup)) {
    $supplier_all[$r['id_supplier']] = $r['supplier'];
}

// --- LOOP DATA UTAMA ---
$data = [];
$no = 1;

while ($row = mysql_fetch_assoc($result)) {
    // Allow
    $allow = 0;
    if(isset($allow_all[$row['idsubgroup']])) {
        foreach ($allow_all[$row['idsubgroup']] as $al) {
            if($row['qty_bom'] >= $al['qty1'] && $row['qty_bom'] <= $al['qty2']) {
                $allow = $al['allowance'];
                break;
            }
        }
    }

    $allowq = $row['qty_bom'] * $allow / 100;
    $qtypr = $row['qty_bom'] + $allowq;

    // PO & Booking
    $jenis = ($row['status']=="P") ? "P" : "M";
    $cekpo = isset($po_all[$row['id_item']][$jenis]['cekpo']) ? $po_all[$row['id_item']][$jenis]['cekpo'] : '';
    $cekqpo = isset($po_all[$row['id_item']][$jenis]['cekqpo']) ? $po_all[$row['id_item']][$jenis]['cekqpo'] : 0;
    $bookqty = isset($transfer_all[$row['id_item']]) ? $transfer_all[$row['id_item']] : 0;

    // Stock
    $id_item_bb = flookup("id_item","masteritem","id_gen='".$row['id_item']."'");
    $sisa_stock = isset($stock_all[$id_item_bb]) ? $stock_all[$id_item_bb] : 0;

    // Row style
    if ($cekqpo >= $qtypr) $rowstyle = "background-color:red;color:yellow;";
    elseif ($cekqpo < $qtypr && $cekqpo > 0) $rowstyle = "background-color:gray;color:yellow;";
    else $rowstyle = "";

    // Edit & Booking
    $edit = ($cekqpo > 0) ? "" : "<a href='?mod=$mod&idd=".$row['id_item']."&id=$id_jo&status=".$row['status']."'><i class='fa fa-pencil'></i></a>";
    $booking = ($sisa_stock > 0 && $cekqpo <= 0) ? "<a href='../pur/?mod=5&id=$id_jo'><i class='fa fa-exchange'></i></a>" : "";

    // Supplier dropdown HTML
    $keycri = $row['id_item'].":".$id_jo;
    $supplier_html = "<select class='form-control select2 txtsupplierarr' style='width:120px;' name='txtsupplierarr[$keycri]' id='txtsupplierarr$keycri'>";
    $supplier_html .= "<option value=''>Pilih Supplier</option>";
    foreach ($supplier_all as $id_sup => $sup_name) {
        $selected = ($id_sup == $row['id_supplier']) ? "selected" : "";
        $supplier_html .= "<option value='$id_sup' $selected>$sup_name</option>";
    }
    $supplier_html .= "</select>";

    $supplier2_html = "<select class='form-control select2 txtsupplier2arr' style='width:120px;' name='txtsupplier2arr[$keycri]' id='txtsupplier2arr$keycri'>";
    $supplier2_html .= "<option value=''>Pilih Supplier</option>";
    foreach ($supplier_all as $id_sup => $sup_name) {
        $selected = ($id_sup == $row['id_supplier2']) ? "selected" : "";
        $supplier2_html .= "<option value='$id_sup' $selected>$sup_name</option>";
    }
    $supplier2_html .= "</select>";

    // Push ke array
    $data[] = [
        "DT_RowAttr" => ["style" => $rowstyle],
        "no" => $no++,
        "item" => $row['item'],
        "id_item" => $row['id_item'],
        "idsubgroup" => $row['idsubgroup'],
        "qty_bom" => number_format($row['qty_bom'],2),
        "allow" => number_format($allow,2),
        "qtypr" => number_format($qtypr,2),
        "unit" => $row['unit'],
        "sisa_stock" => $sisa_stock,
        "bookqty" => $bookqty,
        "supplier" => $supplier_html,
        "supplier2" => $supplier2_html,
        "notes" => $row['notes'],
        "status_app" => $row['status_app'],
        "cekpo" => $cekpo,
        "edit" => $edit,
        "booking" => $booking
    ];
}

// JSON response
echo json_encode([
    "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 1,
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
]);
exit;
?>
