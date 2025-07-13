<!DOCTYPE html>
<?php
include_once '../../include/conn.php';
include_once '../forms/fungsi.php';

$nm_company=flookup("company","mastercompany","company!=''");
$images = '../../include/img-01.png';
if($nm_company=="PT. Jinwoo Engineering Indonesia")
{ $tptlogo = '<td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="'.$images.'" width="200px" height="70px"> </td>';  }
else
{ $tptlogo = '<td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="'.$images.'" width="100px" height="70px"> </td>';  }
$jenis_company=flookup("jenis_company","mastercompany","company!=''");

//$PONO = flookup("draftno","po_header_draft","id='$_GET[id]'");
$rs=mysqli_fetch_assoc(mysqli_query($con_new,"select up.fullname,po.revise,po.jenis status,po.draftno,ac.kpno,po.notes,
	count(distinct poit.id_jo) totjo,count(distinct mp.product_group) totprodgroup,
  if(mpt.nama_pterms='',mpt.kode_pterms,mpt.nama_pterms) nama_pterms,
  poit.curr  
  from po_header_draft po inner join po_item_draft poit on po.id=poit.id_po_draft 
  inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
  act_costing ac on ac.id=so.id_cost 
  inner join masterproduct mp on ac.id_product=mp.id 
  inner join masterpterms mpt on po.id_terms=mpt.id 
  left join userpassword up on po.username=up.username     
  where po.id='$_GET[id]' and poit.cancel='N' group by po.draftno"));
$PONO = $rs['draftno'];
$tipe_com =flookup("tipe_com","po_header_draft","id='$_GET[id]'");
$cekidgen=flookup("count(*)","po_item_draft","id_gen=0 and id_po_draft='$_GET[id]'");
$revise = $rs['revise'];
$createby = $rs['fullname'];
if($rs['curr']=="USD")
{
  $decim=4;
}
else
{
  $decim=2;
}
if($revise=="0") { $revise=""; } else { $revise="Rev ".$revise; }
if($rs['totjo']>1 or substr($rs['draftno'],0,2)=="C/")
{   
    // $wsno = "(Combine)";
    $wsno = "";
    if($jenis_company=="VENDOR LG")
    { $wsdet = flookup("group_concat(distinct concat(' ',jod.jo_no))","po_header_draft po inner join po_item_draft poit 
      on po.id=poit.id_po_draft inner join jo jod on poit.id_jo=jod.id","po.id='$_GET[id]' and poit.cancel='N' order by jod.jo_no");
    }
    else
    { $wsdet = flookup("group_concat(distinct concat(' ',ac.kpno))","po_header_draft po inner join po_item_draft poit 
      on po.id=poit.id_po_draft inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
      act_costing ac on ac.id=so.id_cost","po.id='$_GET[id]' and poit.cancel='N' order by ac.kpno");
    }
}
else
{ 
  // $wsno = $rs['kpno'];  
  // $wsdet = $wsno;
  $wsno = "";  
  $wsdet = "";
}
if($jenis_company=="VENDOR LG")
{	$partnodet = "";
  $sql=mysqli_query($con_new, "select distinct mp.product_group from po_header_draft po inner join po_item_draft poit 
    on po.id=poit.id_po_draft inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
    act_costing ac on ac.id=so.id_cost inner join masterproduct mp on ac.id_product=mp.id   
    where po.id='$_GET[id]' and poit.cancel='N'");
  while($data = mysqli_fetch_assoc($sql))
  { if ($partnodet=="") 
		{$partnodet=$data['product_group'];} 
		else 
		{$partnodet=$partnodet.", ".$data['product_group'];} 
	}
	if($partnodet!="") { $partnodet="Part # ".$partnodet; }
}
else
{	$partnodet="";	}
$notes = $rs['notes'];
$termofpay = $rs['nama_pterms'];
$ttd = 
"<table>
      <td>JAJAL</td>
    </table>";
$status = $rs['status'];
$sqlcompany = "select company, alamat1, kec, kota, kodepos, telp, fax from mastercompany";
if ($status=="P")
{ $sqldatatable = 'select po_header_draft.discount,po_header_draft.app_by,po_header_draft.tax,po_header_draft.draftdate, po_item_draft.price, 
    sum(po_item_draft.qty) qty, 
  sum(po_item_draft.qty * po_item_draft.price)as totalin, po_item_draft.curr, po_item_draft.unit,
  concat(masteritem.matclass," ",masteritem.itemdesc) itemdesc,po_header_draft.eta from po_header_draft INNER JOIN po_item_draft 
  on po_header_draft.id=po_item_draft.id_po_draft inner join 
  masteritem ON po_item_draft.id_gen = masteritem.id_item
  WHERE po_header_draft.draftno = "'.$PONO.'" and po_item_draft.cancel="N" group by masteritem.id_item';
}
else
{ if ($jenis_company=="VENDOR LG") 
  {$sql_join="masteritem.id_item";
   $flddesc="concat(masteritem.goods_code,' ',masteritem.itemdesc)";  
  }
  else
  {if($status=="P")
   {$flddesc="concat(masteritem.matclass,' ',masteritem.itemdesc,' ',masteritem.add_info)";
	
	$sql_join="masteritem.id_item";
    $field ="";
    $order = "";
   }
   else 
   {$flddesc="concat(masteritem.itemdesc,' ',masteritem.add_info)";
	$sql_join="masteritem.id_gen";
    $field =", masteritem.size, masteritem.color";
    $order = "order by color asc, size asc";
   }
  }
  $sqldatatable = 'select po_header_draft.discount,po_header_draft.app_by,po_header_draft.tax,po_header_draft.draftdate, po_item_draft.price, 
    sum(po_item_draft.qty) qty, 
    sum(po_item_draft.qty * po_item_draft.price)as totalin, po_item_draft.curr, po_item_draft.unit,
    '.$flddesc.' itemdesc,po_header_draft.eta '.$field.' from po_header_draft INNER JOIN po_item_draft 
    on po_header_draft.id=po_item_draft.id_po_draft inner join 
    masteritem ON po_item_draft.id_gen = '.$sql_join.' 
    WHERE po_header_draft.draftno = "'.$PONO.'" and po_item_draft.cancel="N" group by po_item_draft.price,po_item_draft.unit,'.$sql_join.'
    '.$order.'';
  echo $sqldatatable;
}
$sqldataAlamatSP = 'select mastersupplier.supplier, 
                mastersupplier.alamat, mastersupplier.fax, mastersupplier.phone,
                mastersupplier.product_name, mastersupplier.terms_of_pay
                from po_header_draft po INNER JOIN mastersupplier ON
                po.id_supplier = mastersupplier.id_supplier
                WHERE po.draftno = "'.$PONO.'"';
$shipper = $con_new->query($sqlcompany);
$result = $con_new->query($sqldatatable);
$supps = $con_new->query($sqldataAlamatSP);

$alamat ='';
$company='';
$kec='';
$kota='';
$kode_pos='';
$telp='';
$fax='';

$alamatsp = '';
$supplier = '';
$faxsp = '';
$phone = '';
$namaproduk='';
#$notes='';
#$termofpay='';
$cicil='';

$jumlah = 0;
$disc = 0;
$quanty = 0;
$currency='';
$tanggal = '';
$valby = '';
while($row = $shipper->fetch_assoc()) {
    $company = $row["company"];
    $alamat= $row["alamat1"];
    $kec = $row["kec"];
    $kota = $row["kota"];
    $kode_pos = $row["kodepos"];
    $telp = $row["telp"];
    $fax = $row["fax"];
}

while($row = $supps->fetch_assoc()) {
    $supplier = $row["supplier"];
    $alamatsp= $row["alamat"];
    $faxsp = $row["fax"];
    $phone = $row["phone"];
    #$namaproduk=$row["product_name"];
    $namaproduk="";
    $cicil=$row["terms_of_pay"];
}

$tableatas= '';

if (mysqli_num_rows($result)) {
    while($row = $result->fetch_assoc()){
    $tableatas .='
    <tr>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:34%">'.$row["itemdesc"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:14%">'.fd_view($row["eta"]).'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%;text-align: right; ">'.fn($row["qty"],2).' '.$row["unit"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%; text-align: right;">'.$row["curr"].'</td>
        <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:10%; text-align: right; "><p style="text-align:right;">'.number_format($row["price"],$decim).'</p></td>
        <td nowrap style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:20%; text-align: right; "><p style="text-align:right;">'.$row["curr"].' '.number_format($row["totalin"], $decim).'</p></td>
    </tr>';
    $disc = $row["discount"];
    $jumlah += $row["totalin"];
    $taxnya = $row["tax"];
    $quanty += $row["qty"]; 
    $currency = $row["curr"];
    $tanggal = fd_view($row["draftdate"]);
    if ($row["app_by"]!="")
    { $valby=flookup("fullname","userpassword","username='".$row["app_by"]."'"); }
} 
}else{
    echo "results 0";
}
$con_new->close();

$bunga=(($jumlah-$disc)*$taxnya/100);
$nambah = $jumlah + $bunga;
$tanggalecho = $tanggal;

$tanggalin = $tanggalecho;
$hari = date_format(new DateTime($tanggalecho), "D");
$html= '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            font-family:Helvetica;
        }

        .hairline{
        width:100%;
        background-color:#939aa5;
        height: 2px;
    }
    </style>
    <title>Purchase Order</title>
</head>
<body >
    <table style="width:100%;  border-collapse: collapse;" >
        <tr style="">
        "'.$tptlogo.'"
        <td style="border-bottom:2px solid black; width:50%; border-collapse: collapse; vertical-align:bottom;" ><p style="color:black; font-weight:bold; font-size:10px; text-align:right; text-transform:uppercase;">
                 '.$company.'
            </p>
        </td>
        </tr>
        <tr>
        <td style="width:60%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:10px; padding-top:5px;">
            Shipping address:<br/>
                '.$company.'<br/>'
                .$alamat.' Kec. '.$kec.'<br/>'
                .$kota.' '.$kode_pos.'<br/>
                Indonesia<br/>
                Telp. '.$telp.' Fax. '.$fax.'
            </p>
        </td>
        <td style="width:40%; border-collapse: collapse;">
            <p style="color:black; font-weight:bold; font-size:12px; padding-top:5px;">
            '.$supplier.'<br/>'
            .$alamatsp.'<br/>'
            .$fax.'<br/>'
            .$phone.'
        </p>
        </td>
        </tr>
    </table>
    <p style="font-size:14px">'.$PONO.' '.$revise.' '.$wsno.'</p>

    <table style="width:70%;font-size:12px;">
        <tr>
            <td style="width:40%"><p style="font-weight:bold;">Our Order Reference:</p></td>
            <td style="width:35% "><p style="font-weight:bold;">Order Date:</p></td>            
            <td style="width:25% "><p style="font-weight:bold;">Validate By:</p></td>
            <td style="width:5% "><p style="font-weight:bold;">Tipe PO:</p></td>
        </tr>
        <tr>
            <td style=" ">'.$PONO.'</td>
            <td style=" ">'.$tanggalin.'</td>            
            <td style=" ">'.$valby.'</td>
            <td style=" ">'.$tipe_com.'</td>
        </tr>
    </table>
    <table style="width:100%; margin-top:25px; border-collapse: collapse;font-size:11px;">
          <tr>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:35%"><p style="font-weight:bold; ">Description</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Date Req.</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:8% "colspan="2"><p style="font-weight:bold; text-align:center;">Qty</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:12%"><p style="font-weight:bold; text-align:center;">Unit Price</p></td>
              <td style="border-collapse: collapse; border-bottom:solid 2px black; width:15%"><p style="font-weight:bold; text-align:center;">Net Price</p></td>
          </tr>
            '.$tableatas.'
            <tr>
                <td style="padding:5px; width:35%"></td>
                <td style="padding:5px; width:15%"></td>
                <td style="padding:5px; width:15%"><p style="font-weight:bold">'.number_format($quanty,2).'</p></td>
                <td style="padding:5px; width:4% "></td>
                <td style="padding:5px; width:4%"></td>
                <td style="padding:5px; width:12% "></p></td>
            </tr>
    </table>
    <table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;font-size:12px;">
            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="font-weight:bold">Total Without PPN</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;text-align:right"><p style="">'.$currency.' '.number_format($jumlah,$decim).'</p></td>
            </tr>';

             $sqladdbiaya = mysql_query("select nama_kategori,if(kategori = 'Plus',sum(total),(sum(total) * -1)) total from po_add_biaya a inner join po_master_pilihan b on b.id = a.id_kategori where id_po_draft = '$_GET[id]' GROUP BY a.id_kategori");
            // echo $sqlsaldo;
            $total_add = 0;
            while($databiaya= mysql_fetch_array($sqladdbiaya)){
            $nama_kategori = $databiaya['nama_kategori'];
            $total = $databiaya['total'];
            $total_add += $databiaya['total'];
            $html .= '<tr>
                <td style="border-collapse: collapse; padding:5px;"><p style="font-weight:bold">'.$nama_kategori.'</p></td>
                <td style="border-collapse: collapse; padding:5px;text-align:right"><p style="text-align:right;">'.$currency.' '.number_format($total,$decim).'</p></td>
            </tr>';
            }

            $sqlppnbiaya = mysql_query("select round(sum(total * if(kategori = 'Min',ppn,(ppn * -1))/100),2) tot_ppn from po_add_biaya a inner join po_master_pilihan b on b.id = a.id_kategori where id_po_draft = '$_GET[id]'");
            // echo $sqlsaldo;
            $tot_ppn = 0;
            $datappn= mysql_fetch_array($sqlppnbiaya);
            $tot_ppn = $datappn['tot_ppn'];

            $subnya = $jumlah + $total_add;

            $html .= '<tr>
                <td style="padding:5px;border-top:solid 2px black;"><p style="font-weight:bold">Subtotal</p></td>
                <td style="padding:5px;border-top:solid 2px black;text-align:right"><p style="text-align:right; font-size:12px; font-weight:bold">'.$currency.' '.number_format($subnya,$decim).'</p></td>
            </tr>

            <tr>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">PPN</p></td>
                <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;text-align:right;"><p style="text-align:right;">'.$currency.' '.number_format(($bunga - $tot_ppn),$decim).'</p></td>
            </tr>';

            $html .= '<tr>
                <td style="padding:5px; "><p style="font-weight:bold">Total</p></td>
                <td style="padding:5px;text-align:right "><p style="text-align:right; font-size:12px; font-weight:bold">'.$currency.' '.number_format(($subnya + ($bunga - $tot_ppn)),$decim).'</p></td>
            </tr>';
    $html .= '</table>
    <div style="width:100%; margin-top:20px;font-size:12px;">
    <p>Created By : '.$createby.'</p>
    <p>Notes : '.$notes.'</p>';
    if($jenis_company=="VENDOR LG")
    { $html = $html.'<p>JO : <b>'.$wsdet.'</b></p>'; }
    else
    { $html = $html.'<p>WS : <b>'.$wsdet.'</b></p>'; }
    $html = $html.'<p>'.$partnodet.'</p>
    <p>Payment Terms : '.$termofpay.'</p>'.$ttd.'
    </div>'
?>

<?php
include("../../mpdf57/mpdf.php");

$mpdf=new mPDF(); 
if($cekidgen>0)
{
  $html="Missing Id";
}
$mpdf->WriteHTML($html);
ob_clean();
$mpdf->Output();
exit;
?>