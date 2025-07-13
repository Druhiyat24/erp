<?PHP
include "../../include/conn.php";
include "../forms/fungsi.php";
include "get_sisa_budget.php";

$rscomp = mysql_fetch_array(mysql_query("select * from mastercompany"));
$nm_company = $rscomp["company"];
$st_company = $rscomp["status_company"];
$pr_need_app = $rscomp["pr_need_app"];
$jenis_company = $rscomp["jenis_company"];
$modenya = $_GET['modeajax'];

if ($modenya == "get_list_jo") {
  $id_supp = $_REQUEST['id_supp'];
  $jenis_item = $_REQUEST['jenis_item'];
  if ($jenis_item == "Material") {
    $filjen = " and s.jenis!='M' ";
  } else {
    $filjen = " and s.jenis='N' ";
  }
  if ($pr_need_app == "Y") {
    $sql_app = " and a.app='A' ";
  } else {
    $sql_app = "";
  }
  if ($jenis_company == "VENDOR LG") {
    $tmplfld = "concat(jo_no,' - ',ms.supplier,' - ',ac.styleno,' - ',buyerno)";
  } else {
    $tmplfld = "concat(jo_no,' - ',ms.supplier,' - ',ac.styleno)";
  }
  $sql = "select tmppr.id isi,tmppr.vtampil tampil from  
    (select a.id,$tmplfld vtampil,
    sum(sod.qty*s.cons) qtybom,tmppo.qty_po from 
    jo a inner join bom_jo_item s on a.id=s.id_jo
    inner join jo_det jod on a.id=jod.id_jo
    inner join so on jod.id_so=so.id
    inner join act_costing ac on so.id_cost=ac.id 
    inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
    inner join so_det sod on s.id_so_det=sod.id  
    left join 
	(select a.id_jo,a.id_gen,sum(a.qty) qty_po from po_item a inner join po_header s on a.id_po=s.id 
      where cancel='N' $filjen group by id_jo,id_gen) tmppo 
    on tmppo.id_jo=s.id_jo and tmppo.id_gen=s.id_item
    where (s.id_supplier='$id_supp' or s.id_supplier2='$id_supp') 
    and s.status='$jenis_item' and s.cancel='N' $sql_app group by jo_no,s.id_item) tmppr 
    where qtybom>qty_po or qty_po is null group by tmppr.id";
  #var_dump($sql);
  IsiCombo($sql, '-', '');
}


if ($modenya == "get_list_jo_global") {
  $id_supp = $_REQUEST['id_supp'];
  $jenis_item = $_REQUEST['jenis_item'];
  $sql = "select k.id_jo isi, concat(jo_no,' - ',m.supplier,' - ',ac.styleno, ' - ', ac.kpno ) tampil
from bom_jo_global_item k
inner join masteritem mi on k.id_item = mi.id_item
inner join jo on k.id_jo = jo.id
inner join jo_det jd on jo.id = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
inner join mastersupplier m on k.id_supplier=m.id_supplier
where k.cancel = 'N' and if(mi.mattype = 'F' or mi.mattype = 'A','M','P') = '$jenis_item' and k.id_supplier = '$id_supp'
group by k.id_jo";
  #var_dump($sql);
  IsiCombo($sql, '-', '');
}



if ($modenya == "get_list_req") {
  $kode_dept = json_encode($_REQUEST['kode_dept']);
  $kode_dept = str_replace("[", "", $kode_dept);
  $kode_dept = str_replace("]", "", $kode_dept);
  // $kode_dept = str_replace('"','|',$kode_dept);
  // $kode_dept = str_replace("|","\'",$kode_dept);
  // $kode_dept = str_replace("\\","",$kode_dept);
  $sql = "select a.id isi,reqno tampil from 
    reqnon_header a inner join userpassword s on a.username=s.username 
    inner join (select id_reqno,sum(qty) qty_req from reqnon_item group by id_reqno) treq on treq.id_reqno=a.id 
    left join 
    (select a.id_jo,sum(a.qty) qty_po from po_item a inner join po_header s on a.id_po=s.id 
      where jenis='N' and cancel='N' group by a.id_jo) tpo on tpo.id_jo=treq.id_reqno   
    where s.kode_mkt in ($kode_dept) and a.app='A' and 
    (
      (a.app_by2!='' and a.app2='A') 
      or (a.app_by2='' and a.app2='W')
      or (a.app_by2 is null and a.app2='W')
    ) 
    group by reqno";
  #var_dump($sql);
  IsiCombo($sql, '-', '');
}

if ($modenya == "get_list_item_prorata") {
  $id_po = $_REQUEST['id_po'];
  $sql = "select a.id_gen isi,concat(itemdesc,' ',color,' ',size) tampil from po_item a 
    inner join masteritem s on a.id_gen=s.id_gen where id_po='$id_po' group by a.id_gen,id_po";
  echo "<script>alert('" . $kode_dept . "');</script>";
  IsiCombo($sql, '-', 'Pilih Item #');
}

if ($modenya == "get_qty_po") {
  $id_po = $_REQUEST['id_po'];
  $id_item_po = $_REQUEST['id_item_po'];
  $sql = "select round(sum(qty),2) qtypo from po_item where id_po='$id_po' and id_Gen='$id_item_po'";
  #echo "<script>alert('".$kode_dept."');</script>";
  $rs = mysql_fetch_array(mysql_query($sql));
  echo json_encode(array($rs['qtypo']));
  exit;
}

if ($modenya == "get_list_supp_global") {
  $jenis_item = $_REQUEST['jenis_item'];
  $sql = "select ms.id_supplier isi, ms.supplier tampil 
  from bom_jo_global_item a
  inner join masteritem mi on a.id_item = mi.id_item 
  inner join mastersupplier ms on a.id_supplier = ms.id_supplier
  where if(mi.mattype = 'F' or mi.mattype = 'A','M','P') = '$jenis_item' and a.cancel = 'N'
  group by ms.id_supplier
  order by ms.supplier asc";
  IsiCombo($sql, '', 'Pilih Supplier');
}


if ($modenya == "get_list_supp") {
  $jenis_item = $_REQUEST['jenis_item'];
  if ($pr_need_app == "Y") {
    $sql_app = " and a.app='A' ";
  } else {
    $sql_app = "";
  }
  $sql = "select isi,tampil from  
	(
	  select d.id_supplier isi,supplier tampil from 
	  jo a inner join bom_jo_item s on a.id=s.id_jo
	  inner join mastersupplier d on s.id_supplier=d.id_supplier 
	  where tipe_sup='S' and s.status='$jenis_item' $sql_app group by supplier 
	  union all 
	  select d.id_supplier isi,supplier tampil from 
	  jo a inner join bom_jo_item s on a.id=s.id_jo
	  inner join mastersupplier d on s.id_supplier2=d.id_supplier 
	  where tipe_sup='S' and s.status='$jenis_item' $sql_app group by supplier
	) 
	as tmptbl group by isi";
  IsiCombo($sql, '', 'Pilih Supplier');
}

if ($modenya == "view_list_jo") {
  $id_jo = json_encode($_REQUEST['id_jo']);
  $id_supplier = $_REQUEST['id_supp'];
  $jenis_item = $_REQUEST['jenis_item'];
?>
  <table id="examplefix2" class="display responsive" style="width:100%">
    <thead>
      <tr>
        <th>..</th>
        <?php if ($jenis_company == "VENDOR LG") { ?>
          <th width='15%'>JO #</th>
        <?php } else { ?>
          <th width='15%'>WS #</th>
        <?php } ?>
        <th width='30%'>Item</th>
        <th width='15%'>Qty BOM</th>
        <th width='15%'>Unit</th>
        <th width='5%'>Stock</th>
        <th width='5%'>Qty PO</th>
        <th width='5%'>Unit</th>
        <th width='10%'>Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($jenis_item == "M") {
        $filjen = " and s.jenis!='N' ";
      } else {
        $filjen = " and s.jenis='N' ";
      }
      $id_jo = str_replace("[", "", $id_jo);
      $id_jo = str_replace("]", "", $id_jo);
      # QUERY TABLE
      if ($jenis_item == "P" and $id_jo != '""') {
        $sql = "select ac.id id_cs,k.id_jo,0 idsubgroup,k.id_item,l.color,l.size,
        concat(j.matclass,' ',j.itemdesc) item,
        l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
        k.unit,m.supplier,ac.kpno,jo_no 
        from bom_jo_item k inner join so_det l on k.id_so_det=l.id
        inner join so on so.id=l.id_so inner join act_costing ac on ac.id=so.id_cost 
        inner join masteritem j on k.id_item=j.id_item  
        inner join jo on k.id_jo=jo.id 
        left join mastersupplier m on k.id_supplier=m.id_supplier
        where k.id_jo in ($id_jo) 
        and (k.id_supplier='$id_supplier' or k.id_supplier2='$id_supplier') 
        and k.cancel='N' group by k.id_jo,k.id_item";
        echo $sql;
      } elseif ($id_jo != '""') {
        if ($jenis_company == "VENDOR LG") {
          $sql = "select ac.id id_cs,k.id_jo,0 idsubgroup,k.id_item,l.color,l.size,
            concat(ifnull(a.goods_code,''),' ',ifnull(a.itemdesc,''),' ',ifnull(a.color,''),' ',ifnull(a.size,''),' ',ifnull(a.add_info,'')) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,ac.kpno,jo_no 
            from bom_jo_item k inner join so_det l on k.id_so_det=l.id
            inner join so on so.id=l.id_so inner join act_costing ac on ac.id=so.id_cost 
            inner join masteritem a on a.id_item=k.id_item 
            inner join jo on k.id_jo=jo.id
            left join mastersupplier m on k.id_supplier=m.id_supplier
            where k.id_jo in ($id_jo) and 
            (k.id_supplier='$id_supplier' or k.id_supplier2='$id_supplier') 
            and k.cancel='N' group by k.id_jo,k.id_item";
        } else {
          $sql = "select ac.id id_cs,k.id_jo,s.id idsubgroup,k.id_item,l.color,l.size,
            concat(a.nama_group,' ',s.nama_sub_group,' ',
            d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
            g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,
            l.qty qty_gmt,k.cons,round(sum(l.qty*k.cons),2) qty_bom,
            k.unit,m.supplier,ac.kpno,jo_no  
            from bom_jo_item k inner join so_det l on k.id_so_det=l.id
            inner join so on so.id=l.id_so inner join act_costing ac on ac.id=so.id_cost 
            inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
            inner join mastertype2 d on s.id=d.id_sub_group
            inner join mastercontents e on d.id=e.id_type
            inner join masterwidth f on e.id=f.id_contents 
            inner join masterlength g on f.id=g.id_width
            inner join masterweight h on g.id=h.id_length
            inner join mastercolor i on h.id=i.id_weight
            inner join masterdesc j on i.id=j.id_color and k.id_item=j.id 
            inner join jo on k.id_jo=jo.id
            left join mastersupplier m on k.id_supplier=m.id_supplier
            where k.id_jo in ($id_jo) and 
            (k.id_supplier='$id_supplier' or k.id_supplier2='$id_supplier') 
            and k.cancel='N' group by k.id_jo,k.id_item";
        }
      }
      #echo $sql;
      if ($id_jo != '""') {
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $id2 = $data['id_item'];
          $idjo2 = $data['id_jo'];

          $allow = flookup("allowance", "masterallow", "id_sub_group='$data[idsubgroup]'
            and qty1<=$data[qty_bom] and qty2>=$data[qty_bom]");
          if ($allow == null) {
            $allow = 0;
          }
          $allowq = $data['qty_bom'] * $allow / 100;
          $qtySdhPO = flookup("sum(qty)", "po_item a inner join po_header s on a.id_po=s.id", "id_jo='$idjo2' and id_gen='$id2' and cancel='N' $filjen group by id_gen");
          #$qtyPO=($allowq+$data['qty_bom']) - $sisa_stock;
          $qtyPO = ($allowq + $data['qty_bom']);
          $qtyPO = $qtyPO - $qtySdhPO;
          if ($qtyPO > 0) {
            $id = $data['id_item'] . ":" . $data['id_jo'];
            $sisacost = get_sisa_budget($data['id_jo'], $data['id_item'], $jenis_item);
            $id_item_bb = flookup("id_item", "masteritem", "id_gen='$data[id_item]'");
            echo "<tr>";
            echo "<td><input type ='checkbox' name ='itemchk[$id]' 
                class='chkclass'></td>";
            if ($jenis_company == "VENDOR LG") {
              echo "<td>$data[jo_no]</td>";
            } else {
              echo "<td>$data[kpno]</td>";
            }
            echo "<td>$data[item]</td>";
            echo "<td>$data[qty_bom]</td>";
            echo "<td>$data[unit]</td>";
            if ($id_item_bb != "") {
              $sisa_stock = flookup("stock", "stock", "id_item='$id_item_bb'");
            } else {
              $sisa_stock = 0;
            }
            echo "<td>$sisa_stock</td>";
            if ($jenis_company == "VENDOR LG") {
              $price_cs = flookup("price", "act_costing_mat", "id_item='$data[id_item]' 
                  and id_act_cost='$data[id_cs]'");
            } else {
              $price_cs = "";
            }
            echo "<td>
                <input type ='text' style='width:70px;' name ='itemqty[$id]' class='form-control qtyclass'value='$qtyPO'>
                <input type ='hidden' style='width:70px;' name ='itemqtybts[$id]' class='qtybtsclass'value='$qtyPO'>
                <input type ='hidden' style='width:70px;' name ='itemcostbts[$id]' class='costbtsclass'value='$sisacost'>
                </td>";
            echo "<td><select style='width:70px; height: 26px;' name ='itemunit[$id]' class='select2 unitclass'>";
            $sql = "select nama_pilihan isi,nama_pilihan tampil
                from masterpilihan where kode_pilihan='Satuan'";
            IsiCombo($sql, $data['unit'], 'Pilih Unit');
            echo "</select></td>";
            echo "
              <td>
                <input type ='text' style='width:70px;' name ='itemprice[$id]' class='form-control priceclass' 
                  value='$price_cs' onchange='calc_amt_po(this)'>
                <input type ='hidden' style='width:70px;' name ='itemtotamt[$id]' class='totamtclass' readonly>
                <input type ='hidden' name ='idjo[$id]' class='idjoclass' value='$data[id_jo]'>
                <input type ='hidden' name ='itembb[$id]' value='$data[id_item]' class='itembbclass'>
              </td>";
            echo "</tr>";
            $no++; // menambah nilai nomor urut
          }
        }
      }
      ?>
    </tbody>
  </table>
<?php
}

if ($modenya == "view_list_jo_global") {
  $id_jo = json_encode($_REQUEST['id_jo']);
  $id_supplier = $_REQUEST['id_supp'];
  $jenis_item = $_REQUEST['jenis_item'];
?>
  <table id="examplefix2" class="display responsive" style="width:100%">
    <thead>
      <tr>
        <th>..</th>
        <th width='20%'>WS #</th>
        <th width='30%'>Item</th>
        <th width='15%'>Qty BOM</th>
        <th width='15%'>Unit</th>
        <th width='5%'>Qty PO</th>
        <th width='5%'>Unit</th>
        <th width='10%'>Price</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $id_jo = str_replace("[", "", $id_jo);
      $id_jo = str_replace("]", "", $id_jo);
      # QUERY TABLE
      $sql = "select 
jo.jo_no,
ac.kpno,
mi.itemdesc,
k.qty,
k.unit,
mi.id_gen,
k.id_jo,
k.id_supplier
from bom_jo_global_item k
inner join masteritem mi on k.id_item = mi.id_item
inner join jo on k.id_jo = jo.id
inner join jo_det jd on jo.id = jd.id_jo
inner join so on jd.id_so = so.id
inner join act_costing ac on so.id_cost = ac.id
left join mastersupplier m on k.id_supplier=m.id_supplier   
where k.id_jo in ($id_jo) and k.id_supplier = '$id_supplier' 
and k.cancel = 'N'
group by k.id_jo, k.id_item";
      #echo $sql;
      if ($id_jo != '""') {
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          $id2 = $data['id_gen'];
          $idjo2 = $data['id_jo'];

          $id = $data['id_gen'] . ":" . $data['id_jo'];
          echo "<tr>";
          echo "<td><input type ='checkbox' name ='itemchk[$id]' 
                class='chkclass'></td>";
          echo "<td>$data[kpno]</td>";
          echo "<td>$data[itemdesc]</td>";
          echo "<td style='text-align: center; vertical-align: middle;'>$data[qty]</td>";
          echo "<td style='text-align: center; vertical-align: middle;'>$data[unit]</td>";
          echo "<td>
                <input type ='text' style='width:70px;' name ='itemqty[$id]' class='form-control qtyclass'value='$qtyPO'>
                <input type ='hidden' style='width:70px;' name ='itemqtybts[$id]' class='qtybtsclass'value='$qtyPO'>
                <input type ='hidden' style='width:70px;' name ='itemcostbts[$id]' class='costbtsclass'value='0'>
                </td>";
          echo "<td><select style='width:70px; height: 26px;' name ='itemunit[$id]' class='select2 unitclass'>";
          $sql = "select nama_pilihan isi,nama_pilihan tampil
                from masterpilihan where kode_pilihan='Satuan'";
          IsiCombo($sql, $data['unit'], 'Pilih Unit');
          echo "</select></td>";
          echo "
              <td>
                <input type ='text' style='width:70px;' name ='itemprice[$id]' class='form-control priceclass' 
                  value='' onchange='calc_amt_po(this)'>
                <input type ='hidden' style='width:70px;' name ='itemtotamt[$id]' class='totamtclass' readonly>
                <input type ='hidden' name ='idjo[$id]' class='idjoclass' value='$data[id_jo]'>
                <input type ='hidden' name ='itembb[$id]' value='$data[id_gen]' class='itembbclass'>
              </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut

        }
      }
      ?>
    </tbody>
  </table>
<?php
}

if ($modenya == "view_list_req") {
  $id_req = json_encode($_REQUEST['id_jo']);
?>
  <table id="examplefix2" class="display responsive" style="width:100%">
    <thead>
      <tr>
        <th>..</th>
        <th width='30%'>Item</th>
        <th width='15%'>Color</th>
        <th width='15%'>Size</th>
        <th width='15%'>Notes Req</th>
        <th width='15%'>Qty Req</th>
        <th width='15%'>Unit</th>
        <th width='5%'>Stock</th>
        <th width='5%'>Qty PO</th>
        <th width='5%'>Unit</th>
        <th width='10%'>Price</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $id_req = str_replace("[", "", $id_req);
      $id_req = str_replace("]", "", $id_req);
      # QUERY TABLE
      $sql = "select rh.notes,k.id_reqno,0 idsubgroup,k.id_item,j.color,j.size,
        j.itemdesc item,0 qty_gmt,0 cons,round(sum(k.qty),2) qty_bom,
        k.unit,'' supplier,'' kpno,k.price 
        from reqnon_header rh inner join reqnon_item k on rh.id=k.id_reqno inner join masteritem j on k.id_item=j.id_item  
        where k.id_reqno in ($id_req) and k.cancel='N' group by k.id_reqno,k.id_item";
      #echo $sql;
      if ($id_req != '""') {
        $query = mysql_query($sql);
        $no = 1;
        while ($data = mysql_fetch_array($query)) {
          echo "<tr>";
          $id = $data['id_item'] . ":" . $data['id_reqno'];
          $id2 = $data['id_item'];
          $idjo2 = $data['id_reqno'];
          echo "<td><input type ='checkbox' name ='itemchk[$id]' 
              class='chkclass'></td>";
          echo "
            <td>$data[item]</td>
            <td>$data[color]</td>
            <td>$data[size]</td>
            <td>$data[notes]</td>
            <td>$data[qty_bom]</td>
            <td>$data[unit]</td>";
          $id_item_bb = flookup("id_item", "masteritem", "id_gen='$data[id_item]'");
          if ($id_item_bb != "") {
            $sisa_stock = flookup("stock", "stock", "id_item='$id_item_bb'");
          } else {
            $sisa_stock = 0;
          }
          echo "<td>$sisa_stock</td>";
          $allow = flookup("allowance", "masterallow", "id_sub_group='$data[idsubgroup]'
              and qty1<=$data[qty_bom] and qty2>=$data[qty_bom]");
          if ($allow == null) {
            $allow = 0;
          }
          $allowq = $data['qty_bom'] * $allow / 100;
          $qtySdhPO = flookup("sum(qty)", "po_item", "id_jo='$idjo2' and id_gen='$id2' group by id_gen");
          $qtyPO = ($allowq + $data['qty_bom']) - $sisa_stock;
          $qtyPO = $qtyPO - $qtySdhPO;
          $pricereq = $data['price'];
          echo "<td>
              <input type ='text' style='width:70px;' name ='itemqty[$id]' class='qtyclass' value='$qtyPO'>
              <input type ='hidden' style='width:70px;' name ='itemqtybts[$id]' class='qtybtsclass'value='$qtyPO'>
              </td>";
          echo "<td><select style='width:70px; height: 26px;' name ='itemunit[$id]' class='unitclass'>";
          $sql = "select nama_pilihan isi,nama_pilihan tampil
              from masterpilihan where kode_pilihan='Satuan'";
          IsiCombo($sql, $data['unit'], 'Pilih Unit');
          echo "</select></td>";
          echo "
            <td>
              <input type ='text' style='width:70px;' name ='itemprice[$id]' class='priceclass' value='$pricereq'>
              <input type ='hidden' name ='idjo[$id]' class='idjoclass' value='$data[id_reqno]'>
              <input type ='hidden' name ='itembb[$id]' value='$data[id_item]' class='itembbclass'>
            </td>";
          echo "
            <td><a href='#' class='img-prev' data-id=$data[id_item]
                data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'>
                </i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
      }
      ?>
    </tbody>
  </table>
<?php
}
?>
<?php
?>