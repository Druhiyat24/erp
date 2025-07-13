<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
if(isset($_GET['mod'])) { $mod=$_GET['mod']; } else { $mode=""; }
# START CEK HAK AKSES KEMBALI
if($mod=="9ei")
{ $akses = flookup("purch_ord_gen","userpassword","username='$user'");  }
else
{ $akses = flookup("purch_ord","userpassword","username='$user'");  }
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='../pur/?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$id=$_GET['id'];
$jenis_mat=flookup("jenis","po_header a inner join po_item s on a.id=s.id_po","s.id='$id'");
# COPAS EDIT
if($mod=="9ei")
{ $sql="select po.pono,l.id,reqno jo_no,concat(a.goods_code,' ',a.itemdesc) item,l.qty,l.unit,l.curr,l.price 
  from po_header po inner join po_item l on po.id=l.id_po 
  inner join reqnon_header m on l.id_jo=m.id 
  inner join masteritem a on a.id_item=l.id_gen where l.id='$id'";
}
else if ($jenis_mat=="P")
{ $sql="select po.pono,l.id,jo_no,concat(mi.itemdesc) item,l.qty,l.unit,l.curr,l.price 
  from po_header po inner join po_item l on po.id=l.id_po 
  inner join jo m on l.id_jo=m.id 
  inner join masteritem mi on l.id_gen=mi.id_item  
  where l.id='$id'";
}
else
{ $sql="select po.pono,l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',
  d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
  g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,l.qty,l.unit,l.curr,l.price 
  from po_header po inner join po_item l on po.id=l.id_po 
  inner join jo m on l.id_jo=m.id 
  inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
  inner join mastertype2 d on s.id=d.id_sub_group
  inner join mastercontents e on d.id=e.id_type
  inner join masterwidth f on e.id=f.id_contents 
  inner join masterlength g on f.id=g.id_width
  inner join masterweight h on g.id=h.id_length
  inner join mastercolor i on h.id=i.id_weight
  inner join masterdesc j on i.id=j.id_color 
  and l.id_gen=j.id where l.id='$id'";
}
$rs=mysql_fetch_array(mysql_query($sql));
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>";
	echo "function validasi()";
	echo "{";
    echo "var filenya = document.form.txtfile.value;";

    echo "if (filenya == '') { alert('Nama file belum dipilih'); document.form.txtfile.focus();valid = false;}";
    echo "else valid = true;";
    echo "return valid;";
    echo "exit;";
	echo "}";
echo "</script>";
# END COPAS VALIDASI

# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_po_it_ed.php?mod=$mod&id=$id' onsubmit='return validasi()'>";
        ?>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>PO #</label>
            <input type='text' readonly class='form-control' value='<?php echo $rs['pono'];?>' >
          </div>
          <div class='form-group'>
            <label><?php if($mod=="9ei") { echo "Request #"; } else { echo "JO #"; } ?></label>
            <input type='text' readonly class='form-control' value='<?php echo $rs['jo_no'];?>' >
          </div>
          <div class='form-group'>
            <label>Item</label>
            <input type='text' readonly class='form-control' value='<?php echo $rs['item'];?>' >
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Qty</label>
            <input type='text' name='txtqty' class='form-control' value='<?php echo $rs['qty'];?>' >
          </div>
          <div class='form-group'>
            <label>Unit</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtunit' id='txtunit'>
            <?php 
            $sql="select nama_pilihan isi,nama_pilihan tampil from 
              masterpilihan where kode_pilihan='Satuan'";
            IsiCombo($sql,$rs['unit'],'Pilih Satuan');
            ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Curr</label>
            <input type='text' readonly class='form-control' value='<?php echo $rs['curr'];?>' >
          </div>
          <div class='form-group'>
            <label>Price</label>
            <input type='text' name='txtprice' class='form-control' value='<?php echo $rs['price'];?>' >
          </div>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
        <?php
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
?>