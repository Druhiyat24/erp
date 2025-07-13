<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$id_item=$_POST['txtid_pre_cost'];
if ($id_item == '')
{
  $id_item = $_GET['id_item'];
}
else
{
  $id_item = $_POST['txtid_pre_cost'];
}
// $id_item=$_GET['id_item'];

# COPAS EDIT

# END COPAS EDIT
?>
<!--COPAS VALIDASI BUANG ELSE di IF pertama-->
<script type='text/javascript'>
  function validasi()
  { var rulebom = document.form.txtroll.value;
    var itemcontents = document.form.txtItemCS.value;
    var dest = document.form.txtkat.value;
    var supp = document.form.txtcons.value;

    if (itemcontents == '') 
    { swal({ title: 'Item Contents Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (rulebom == '') 
    { document.form.txtcons.focus();
      swal({ title: 'Rule BOM Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (dest == '') 
    { swal({ title: 'Dest Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }
    else if (supp == '') 
    { swal({ title: 'Supplier Tidak Boleh Kosong', <?php echo $img_alert; ?>});
      valid = false;
    }    
    else {valid = true;}
    return valid;
    exit;
  }
</script>
<!--END COPAS VALIDASI-->
<script type="text/javascript">

  function getRule()
  { 
    var cri_item = document.form.txtItemCS.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo_global.php?modeajax=view_rule',
        data: {cri_item: cri_item },
        async: false
    }).responseText;
    if(html)
    { $("#cborule").html(html); }
  }  


  function getListData()
  { 
    var id_contents = document.form.txtItemCS.value;
    var id_jo = <?php echo $id_item; ?>; 
    var rulebom = document.form.txtroll.value;
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_bom_jo_global.php?modeajax=view_list_size',
        data: {rulebom: rulebom,id_contents: id_contents,id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
    $(".select2").select2();
    
  };

</script>
<?PHP
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='s_bom_jo_global.php?mod=simpan&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='col-md-4'>";
        echo "<div class='form-group'>";
        echo "<label>Job Order $id_item *</label>";
        echo "<select class='form-control select2' style='width: 100%;'>";
          $sql = "select jo.id isi,concat(jo.jo_no,' - ',supplier,' - ',ac.styleno,' - ',ac.kpno) tampil from 
          jo inner join jo_det jod on jo.id=jod.id_jo
          inner join so on jod.id_so=so.id
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
          left join (select id_jo from bom_jo_item group by id_jo) bji on jo.id=bji.id_jo
          where jo.id='$id_item' and ac.type_ws = 'global'
          group by jo.id";
          IsiCombo($sql,$id_item,'');
        echo "</select>";
      echo "</div>";        
          echo "<div class='form-group'>";
            echo "<label>Item Contents *</label>";
            echo "<select class='form-control select2' required style='width: 100%;' 
              name='txtItemCS' onchange='getRule()'>";
              $sql = "select e.id isi,concat(e.id,' ',nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) 
              tampil from mastergroup a inner join mastersubgroup s on 
              a.id=s.id_group 
              inner join mastertype2 d on s.id=d.id_sub_group
              inner join mastercontents e on d.id=e.id_type
              order by a.id asc, concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) asc";
              IsiCombo($sql,'','Pilih Item Contents');
            echo "</select>";
          echo "</div>";
          echo "<div class='form-group'>";
          echo "<label>Rule BOM *</label>";
          echo "<select class='form-control select2' required style='width: 100%;' name='txtroll' id='cborule' onchange='getListData(this.value)'>";
          echo "</select>";
        echo "</div>";                                                   
        echo "</div>";
        echo "<div class='col-md-4'>";
        // echo "<div class='form-group'>";
        // echo "<label>Destination *</label>";
        // echo "<select class='form-control select2' style='width: 100%;' name='txtdest[]' id='cbodest'  multiple onchange='getListData()' >";
        // echo "</select>"; 
        // echo "</div>";   
        echo "
        <div class='form-group'>
        <label>Supplier *</label>
        <select class='form-control select2' required style='width: 100%;' 
          name='txtsupp'>";
        $sql = "select id_supplier isi, supplier tampil from mastersupplier 	where tipe_sup = 'S' order by supplier asc";
        IsiCombo($sql,'','Pilih Supplier');
        echo "
        </select>
    </div>";
    echo "<div class='form-group'>";
    echo "<label>Notes *</label>";
    echo " <textarea row='10' class='form-control' name='txtnotes' placeholder='Masukkan Notes'></textarea>";
  echo "</div>"; 
          echo "</div>";

          
      echo "<div class='box-body'>";
      echo "<div id='detail_item'></div>";
     echo "</div>";  
     
    echo "</div>";
    echo "
<div class='row'>
<div class='col-md-2'>
<div class='form-group'>";
echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
echo "				<a href='../marketting/?mod=144&id=$id_item' class='btn btn-success btn-s'>
              Kembali </a>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</form>";
echo "</div>";


echo "
<div class='box'>
<div class='box-header'>
<h3 class='box-title'>BOM Global Detail</h3>

</div>
";
echo"
<div class='box-body'>
<form method='post' name='form1' action='s_bom_jo_global.php?mod=edit&id=$id_item'>
<button type='submit' name='submit' class='btn btn-primary'>Update</button>
<table id='examplefix2' class='display responsive' style='width:100%;font-size:11px;'>
<thead>
<tr>
  <th>No</th>
  <th>Jenis Item</th>
  <th>Product Group</th>
  <th>Product Type</th>
  <th>Supplier</th>
  <th>ID Contents</th>
  <th>Nama Contents</th>
  <th>ID Item</th>
  <th>Nama Item</th>
  <th>Qty</th>
  <th>Unit</th>
  <th>Notes</th>  
  <th>Created Date</th>
  <th>Status</th>
  <th>Aksi</th>
</tr>
</thead>
";
echo "<tbody>";
?>
<?php
        # QUERY TABLE
        $sql="select a.id, jo.jo_no,ac.kpno,c.matclass,mp.product_group,mp.product_item, a.id_contents, concat(nama_group,' ',nama_sub_group,' ',nama_type,' ',nama_contents) nama_contents,
        a.id_item,c.itemdesc, a.qty, a.unit, a.rule_bom, ms.id_supplier,ms.supplier, a.notes, a.username, a.dateinput, a.cancel , a.notes
        from bom_jo_global_item a
        inner join mastercontents b on a.id_contents = b.id
        inner join mastertype2 y on b.id_type = y.id
        inner join mastersubgroup x on y.id_sub_group = x.id
        inner join mastergroup w on x.id_group = w.id
        inner join masteritem c on a.id_item = c.id_item
        inner join mastersupplier ms on a.id_supplier = ms.id_supplier
        inner join jo on a.id_jo = jo.id
        inner join jo_det jd on a.id_jo = jd.id_jo
        inner join so on jd.id_so = so.id
        inner join act_costing ac on so.id_cost = ac.id
        inner join masterproduct mp on ac.id_product = mp.id
        where a.id_jo = '$id_item'
        order by 
				case 
				when c.matclass = 'Fabric' then '1'
				when c.matclass = 'Accesories Packing' then '2'
				when c.matclass = 'Accesories Sewing' then '3'
				else '4'
				end asc,a.dateinput asc

        ";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { 
          $idcri=$data['id'];     
          if($data['cancel']=="Y")
           {
             $bgcol="style='color:red;'";
             $status ='Cancel'; 
            } 
          else 
            {
               $bgcol=""; 
               $status ='None';
            }
          echo "
          <tr $bgcol>
          	<td>
                <input type ='hidden' name='chkhide[$data[id]]' value='$data[id]'>
                <input type='hidden' id='id_cek' checked  name='id_cek[$id]' value='$data[id]'>
                $no</td>

            <td>$data[matclass]</td>
            <td>$data[product_group]</td>
            <td>$data[product_item]</td>
          	<td style='width:120px;'>
                  <select class='form-control select2' name ='supplier[$id]'>";
                  $sql = "select id_supplier isi, supplier tampil from mastersupplier where tipe_sup = 'S' order by supplier asc";
                  IsiCombo($sql,$data[id_supplier],'');
                  echo"       
                  </select>
            </td>
          	<td>$data[id_contents]</td>
          	<td>$data[nama_contents]</td>
          	<td>$data[id_item]</td>
          	<td>$data[itemdesc]</td>
          	<td style='width:85px;'><input class='form-control qty' size = '50' name ='qty[$id]' value = '$data[qty]'></td>
          	<td style='width:85px;'>
                  <select class='form-control select2' name ='unit[$id]'>";
                  $sql = "select nama_pilihan isi,nama_pilihan tampil from 
                    masterpilihan where kode_pilihan='Satuan'";
                  IsiCombo($sql,$data[unit],'');
                  echo"       
                  </select>
            </td>
            <td>$data[notes]</td>
          	<td>$data[dateinput]</td>
            <td>$status</td>
            <td>
            <a href='s_bom_jo_global.php?mod=delete&id=$id_item&idd=$data[id]'<i class='fa fa-trash'></a>
            </td>
            ";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
				?>
      </tbody>
<?php
echo "</table>";
echo "</form>";
echo "</div>";

echo "</div>";
# END COPAS ADD
?>