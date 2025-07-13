<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$date = date('d F Y -- H:m:s'); 

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=laporan_po.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<h2><b>Late ETA PO Report</b></h2>
<br>
<?php echo "<p><i>  Downloaded at $date </i></p>"; ?> 
<table border="1">
  <thead>
        <tr>
          <th>No</th>
          <th>ETA</th>
          <th># WS</th>
          <th>Style</th>
          <th>Rev</th>
          <th>Jenis</th>
          <th>Status</th>
          <th>ID PO</th>
          <th>PO #</th>
          <th>PO Date</th>
          <th>Supplier</th>
          <th>P. Terms</th>
          <th>Buyer</th>
        </tr>
  </thead>

  <tbody>
    <?php 
    $datefil=date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));
    

    $sql="select a.eta,tmppoit.kpno,tmppoit.styleno,a.revise,if(a.jenis='N','Manufacturing','Material') jenis,if(a.app='A','Approved','Waiting') app,a.id,pono,podate,supplier,
        nama_pterms,tmppoit.buyer from po_header a inner join 
        mastersupplier s on a.id_supplier=s.id_supplier inner join 
        masterpterms d on a.id_terms=d.id
        inner join 
        (select ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer from po_item poit 
        inner join jo_det jod on jod.id_jo=poit.id_jo 
        inner join so on jod.id_so=so.id
        inner join act_costing ac on so.id_cost=ac.id 
        inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
        group by poit.id_po) tmppoit 
        on tmppoit.id_po=a.id 
        where a.eta<='$datefil' and jenis in ('M','P')  
        union all 
        select eta,'','',a.revise,a.jenis,a.app,a.id,pono,podate,supplier,
        nama_pterms,tmppoit.buyer from po_header a inner join 
        mastersupplier s on a.id_supplier=s.id_supplier inner join 
        masterpterms d on a.id_terms=d.id
        inner join 
        (select poit.id_jo,poit.id_po,'' buyer from po_item poit 
        inner join reqnon_header reqnonh on reqnonh.id=poit.id_jo 
        group by poit.id_po) tmppoit 
        on tmppoit.id_po=a.id 
        where a.eta<='$datefil' and jenis='N' order by eta desc";
    //tampil_data_tanpa_nourut($sql,10);
    tampil_data($sql,12);
    ?>
  </tbody>
</table>
<br>