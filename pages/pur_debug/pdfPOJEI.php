<style type="text/css">
  #po td{
    padding: 3px;
  }

  #table_det td
  {
    padding: 5px;
  }
</style>
<?php
  // include_once '../../include/conn.php';
  // include_once '../forms/fungsi.php';

  // $images = '../../include/img-01.png';
  // $jenis_company=flookup("jenis_company","mastercompany","company!=''");

  // //$PONO = flookup("pono","po_header","id='$_GET[id]'");
  // $rs=mysqli_fetch_assoc(mysqli_query($con_new,"select po.revise,po.jenis status,po.pono,ac.kpno,po.notes,
  //   count(distinct poit.id_jo) totjo,count(distinct mp.product_group) totprodgroup,mpt.nama_pterms 
  //   from po_header po inner join po_item poit on po.id=poit.id_po 
  //   inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
  //   act_costing ac on ac.id=so.id_cost 
  //   inner join masterproduct mp on ac.id_product=mp.id 
  //   inner join masterpterms mpt on po.id_terms=mpt.id     
  //   where po.id='$_GET[id]' and poit.cancel='N' group by po.pono"));
  // $PONO = $rs['pono'];
  // $revise = $rs['revise'];
  // if($revise=="0") { $revise=""; } else { $revise="Rev ".$revise; }
  // if ($rs['totjo']>1)
  // {   $wsno = "(Combine)";
  //     $wsdet = "";
  //     $sql=mysqli_query($con_new, "select distinct ac.kpno from po_header po inner join po_item poit 
  //       on po.id=poit.id_po inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
  //       act_costing ac on ac.id=so.id_cost  
  //       where po.id='$_GET[id]' and poit.cancel='N'");
  //     while($data = mysqli_fetch_assoc($sql))
  //     { if ($wsdet=="") {$wsdet=$data['kpno'];} else {$wsdet=$wsdet.", ".$data['kpno'];} }
  // }
  // else
  // { $wsno = $rs['kpno'];  
  //   $wsdet = $wsno;
  // }
  // if($jenis_company=="VENDOR LG")
  // { $partnodet = "";
  //   $sql=mysqli_query($con_new, "select distinct mp.product_group from po_header po inner join po_item poit 
  //     on po.id=poit.id_po inner join jo_det jod on poit.id_jo=jod.id_jo inner join so on jod.id_so=so.id inner join 
  //     act_costing ac on ac.id=so.id_cost inner join masterproduct mp on ac.id_product=mp.id   
  //     where po.id='$_GET[id]' and poit.cancel='N'");
  //   while($data = mysqli_fetch_assoc($sql))
  //   { if ($partnodet=="") 
  //     {$partnodet=$data['product_group'];} 
  //     else 
  //     {$partnodet=$partnodet.", ".$data['product_group'];} 
  //   }
  //   if($partnodet!="") { $partnodet="Part # ".$partnodet; }
  // }
  // else
  // { $partnodet="";  }
  // $notes = $rs['notes'];
  // $termofpay = $rs['nama_pterms'];
  // $status = $rs['status'];
  // $sqlcompany = "select company, alamat1, kec, kota, kodepos, telp, fax from mastercompany";
  // if ($status=="P")
  // { $sqldatatable = 'select po_header.app_by,po_header.tax,po_header.podate, po_item.price, 
  //     sum(po_item.qty) qty, 
  //   sum(po_item.qty * po_item.price)as totalin, po_item.curr, po_item.unit,
  //   masteritem.itemdesc,po_header.eta from po_header INNER JOIN po_item 
  //   on po_header.id=po_item.id_po inner join 
  //   masteritem ON po_item.id_gen = masteritem.id_item
  //   WHERE po_header.pono = "'.$PONO.'" and po_item.cancel="N" group by masteritem.id_item';
  // }
  // else
  // { if ($jenis_company=="VENDOR LG") 
  //   {$sql_join="masteritem.id_item";
  //    $flddesc="concat(masteritem.goods_code,' ',masteritem.itemdesc)";  
  //   }
  //   else
  //   {$sql_join="masteritem.id_gen";
  //    $flddesc="masteritem.itemdesc";
  //   }
  //   $sqldatatable = 'select po_header.app_by,po_header.tax,po_header.podate, po_item.price, 
  //     sum(po_item.qty) qty, 
  //     sum(po_item.qty * po_item.price)as totalin, po_item.curr, po_item.unit,
  //     '.$flddesc.' itemdesc,po_header.eta from po_header INNER JOIN po_item 
  //     on po_header.id=po_item.id_po inner join 
  //     masteritem ON po_item.id_gen = '.$sql_join.' 
  //     WHERE po_header.pono = "'.$PONO.'" and po_item.cancel="N" group by '.$sql_join.'';
  //   #echo $sqldatatable;
  // }
  // $sqldataAlamatSP = 'select mastersupplier.supplier, 
  //                 mastersupplier.alamat, mastersupplier.fax, mastersupplier.phone,
  //                 mastersupplier.product_name, mastersupplier.terms_of_pay
  //                 from po_header po INNER JOIN mastersupplier ON
  //                 po.id_supplier = mastersupplier.id_supplier
  //                 WHERE po.pono = "'.$PONO.'"';
  // $shipper = $con_new->query($sqlcompany);
  // $result = $con_new->query($sqldatatable);
  // $supps = $con_new->query($sqldataAlamatSP);

  // $alamat ='';
  // $company='';
  // $kec='';
  // $kota='';
  // $kode_pos='';
  // $telp='';
  // $fax='';

  // $alamatsp = '';
  // $supplier = '';
  // $faxsp = '';
  // $phone = '';
  // $namaproduk='';
  // #$notes='';
  // #$termofpay='';
  // $cicil='';

  // $jumlah = 0;
  // $quanty = 0;
  // $currency='';
  // $tanggal = '';
  // $valby = '';
  // while($row = $shipper->fetch_assoc()) {
  //     $company = $row["company"];
  //     $alamat= $row["alamat1"];
  //     $kec = $row["kec"];
  //     $kota = $row["kota"];
  //     $kode_pos = $row["kodepos"];
  //     $telp = $row["telp"];
  //     $fax = $row["fax"];
  // }

  // while($row = $supps->fetch_assoc()) {
  //     $supplier = $row["supplier"];
  //     $alamatsp= $row["alamat"];
  //     $faxsp = $row["fax"];
  //     $phone = $row["phone"];
  //     #$namaproduk=$row["product_name"];
  //     $namaproduk="";
  //     $cicil=$row["terms_of_pay"];
  // }

  // $tableatas= '';

  // if (mysqli_num_rows($result)) {
  //     while($row = $result->fetch_assoc()){
  //     $tableatas .='
  //     <tr>
  //         <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:34%">'.$row["itemdesc"].'</td>
  //         <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:14%">'.fd_view($row["eta"]).'</td>
  //         <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4% ">'.fn($row["qty"],2).' '.$row["unit"].'</td>
  //         <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:4%">'.$row["curr"].'</td>
  //         <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:10% "><p style="text-align:right;">'.number_format($row["price"],2).'</p></td>
  //         <td nowrap style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; padding:5px; width:20% "><p style="text-align:right;">'.$row["curr"].' '.number_format($row["totalin"], 2).'</p></td>
  //     </tr>';
  //     $jumlah += $row["totalin"];
  //     $taxnya = $row["tax"];
  //     $quanty += $row["qty"]; 
  //     $currency = $row["curr"];
  //     $tanggal = fd_view($row["podate"]);
  //     if ($row["app_by"]!="")
  //     { $valby=flookup("fullname","userpassword","username='".$row["app_by"]."'"); }
  // } 
  // }else{
  //     echo "results 0";
  // }
  // $con_new->close();

  // $bunga=($jumlah*$taxnya/100);
  // $nambah = $jumlah + $bunga;
  // $tanggalecho = $tanggal;

  // $tanggalin = $tanggalecho;
  // $hari = date_format(new DateTime($tanggalecho), "D");
?>
<table style="width:100%;  border-collapse: collapse;" >
  <tr style="">
    <td style="border-bottom:2px solid black; width:50% border-collapse: collapse;"> <img src="'.$images.'" width="100px" height="70px"> </td>
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
<p style="width:87%;font-size:14px;text-align: center; font-weight: bold;text-decoration: underline;">Purchase Order</p>
<table id="po" style="width:100%;font-size:12px;">
    <tr>
        <td>To</td>
        <td> : </td>
        <td>PT. TEKARINDO SEJAHTERA</td>

        <td>No Po</td>
        <td> : </td>
        <td>001/PO-RA-SA/JEI-KB/11/18</td>
    </tr>
    <tr>
        <td></td>
        <td> : </td>
        <td>Marketing</td>

        <td>Date Po</td>
        <td> : </td>
        <td>001/PO-RA-SA/JEI-KB/11/18</td>
    </tr>
    <tr>
        <td>Telp</td>
        <td> : </td>
        <td>(021)</td>

        <td>Dept/req</td>
        <td> : </td>
        <td>001/PO-RA-SA/JEI-KB/11/18</td>
    </tr>
    <tr>
        <td>Fax</td>
        <td> : </td>
        <td>(021)</td>
    </tr> 
</table>

<table style="width:100%; margin-top:25px; border-collapse: collapse;font-size:11px; border:1px solid black;">
  <tr>
    <td style="padding: 2px;">Please supply the following items in accordence with the berns and condition expressed here in.</td>
  </tr>
</table>
<table style="width:100%; margin-top:5px;font-size:11px; border:1px solid black;" id="table_det" >
      <tr>
          <td style="border:1px solid black; border-bottom: 2px solid black;">NO</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">QTY</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">UNIT</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">DESCRIPTION</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">SPEC</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">UNIT PRICE</td>
          <td style="border:1px solid black; border-bottom: 2px solid black;">TOTAL</td>
      </tr>
        '.$tableatas.'
      <tr>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td style="border-right:1px solid black;border-left:1px solid black;">dummy</td>
          <td></td>
      </tr>
      <!-- End Loop -->  
</table>

<table align="right" style="width:50%; margin-top:15px; border-collapse: collapse;font-size:12px;">
  <tr>
      <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="font-weight:bold">Total Without Taxes</p></td>
      <td style="border-collapse: collapse; border-bottom:solid 2px #f5f5f5; border-top:solid 2px black; padding:5px;"><p style="text-align:right;">'.$currency.' '.number_format($jumlah,2).'</p></td>
  </tr>
  <tr>
      <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="font-weight:bold">Taxes</p></td>
      <td style="border-collapse: collapse; border-bottom:solid 2px black; padding:5px;"><p style="text-align:right;">'.$currency.' '.number_format($bunga,2).'</p></td>
  </tr>
  <tr>
      <td style="padding:5px;"><p style="font-weight:bold">Total</p></td>
      <td style="padding:5px;"><p style="text-align:right; font-size:10px; font-weight:bold">'.$currency.' '.number_format($nambah,2).'</p></td>
  </tr>
</table>

<table align="right" style="width:70%; margin-top:15px;font-size:12px; border:1px solid black;">
  <tr>
    <th style="padding: 5px; text-align: center; border:1px solid black;">IN CHA</th>
    <th style="padding: 5px; text-align: center; border:1px solid black;">MANAGER</th>
    <th style="padding: 5px; text-align: center;border:1px solid black;">G.MANAGER</th>
    <th style="padding: 5px; text-align: center;border:1px solid black;">PRESDIR</th>
  </tr>
  <?php
    for($i=0;$i<=5;$i++)
    {
  ?>
      <tr>
        <td style="border-left:1px solid black;border-right: :1px solid black; color: white;">1</td>
        <td style="border-left:1px solid black;border-right: :1px solid black;color: white;">1</td>
        <td style="border-left:1px solid black;border-right: :1px solid black;color: white;">1</td>
        <td style="border-left:1px solid black;border-right: :1px solid black;color: white;">1</td>
      </tr>
  <?php
    }
  ?>
</table>

<div style="width:100%; margin-top:20px;font-size:12px; border-top: 1px solid black;">
  <p style="text-decoration: underline;">NOTE : </p>
  <p>1 Tanggal Pengiriman : </p>
  <p>2 Syarat Pembayaran  : 3 bulan setelah invoice diterima</p>
  <p>3 Sebelum pengiriman (PO,Packing list,Surat jalan,invoice & Faktur pajak) harap email ke Jinwoo_customs@yahoo.com</p>
  <p>4 Pengiriman barang harap disertakan PO, Packing list, Surat jalan, Invoice dan faktur pajak</p>
  <p>5 Barang akan kami kembalikan apabila tidak sesuai dengan pesanan</p>
  <p>6 Harap lampirkan No Purchase order (PO) pada Surat jalan &  invoice</p>
</div>
<?php
  // include("../../mpdf57/mpdf.php");

  // $mpdf=new mPDF(); 

  // $mpdf->WriteHTML($html);
  // $mpdf->Output();
  // exit;
?>
