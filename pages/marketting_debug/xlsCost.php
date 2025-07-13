<?php 
include '../../include/conn.php';
include '../forms/fungsi.php';

$id_cost=$_GET['id'];

header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=lap_costing.xls");//ganti nama sesuai keperluan 
header("Pragma: no-cache"); 
header("Expires: 0");
?>
<table>
  <thead>
    <th>Costing #</th>
    <th>Costing Date</th>
    <th>WS #</th>
    <th>Style #</th>
    <th>Item Description</th>
    <th>Price</th>
    <th>Cons</th>
    <th>Unit</th>
    <th>Allowance</th>
    <th>Material Source</th>
  </thead>
  <tbody>
    <?php 
    $sqloth="SELECT a.cost_no,a.cost_date,a.kpno,a.styleno,
      concat(otherscode,' ',othersdesc) itemdesc,
      price,cons,s.unit,allowance,material_source  
      from act_costing a inner join act_costing_oth s on 
      a.id=s.id_act_cost 
      inner join masterothers h on s.id_item=h.id
      where a.id='$id_cost' ";
    $sqlcmpl="SELECT a.cost_no,a.cost_date,a.kpno,a.styleno,
      concat(cfcode,' ',cfdesc) itemdesc,
      price,cons,s.unit,allowance,material_source  
      from act_costing a inner join act_costing_mfg s on 
      a.id=s.id_act_cost 
      inner join mastercf h on s.id_item=h.id
      where a.id='$id_cost' ";
    $sql="SELECT a.cost_no,a.cost_date,a.kpno,a.styleno,
      concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) itemdesc,
      price,cons,s.unit,allowance,material_source  
      from act_costing a inner join act_costing_mat s on 
      a.id=s.id_act_cost inner join mastergroup d inner join mastersubgroup f on 
      d.id=f.id_group 
      inner join mastertype2 g on f.id=g.id_sub_group
      inner join mastercontents h on g.id=h.id_type and s.id_item=h.id
      where a.id='$id_cost'  
      union all 
      $sqlcmpl 
      union all 
      $sqloth ";
    tampil_data_tanpa_nourut($sql,10);
    ?>
  </tbody>
</table>  