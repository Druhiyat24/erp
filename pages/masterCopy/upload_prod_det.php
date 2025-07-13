<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("upload_master_product_detail","userpassword","username='$user'");
$aksescomp = flookup("upload_master_product_detail","mastercompany","company!=''");
if ($akses=="0" or $aksescomp=="N") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var filenya = document.form.txtfile.value;
    if (filenya == '') { swal({ title: 'File Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
# COPAS ADD
?>
<?php if($_GET['mod']=="28") { ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='../master/?mod=28U' onsubmit='return validasi()'>
    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          <div class='col-md-12'>
            <div class="alert alert-success" role="alert">
              <b>Please Make Sure!</b>
              <br>1. File Format Excel 97-2003 (.xls)
              <br>2. Only 1 Sheet
              <br>3. First Row in Row 3
              <br>4. Column Structure seq | level | kode_barang | desc | spec | unit | cons | process | jenis_barang
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label for='exampleInputFile'>Pilih File</label>
              <input type='file' name='txtfile' accept='.xls'>
            </div>
            <button type='submit' name='submit' class='btn btn-primary'>Upload</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php } else { ?>
  <form method='post' name='form2' action='../master/?mod=28S'>
    <div class='box'>
      <div class='box-body'>
        <?php if($mod=="28L") { ?>
        <a href='../master/?mod=28' class='btn btn-primary btn-s'>
          <i class='fa fa-plus'></i> New Upload
        </a>
        <p>
        <?php } ?>
        <table id="examplefix" class="display responsive" style="width:100%">
          <thead>
          <tr>
            <th>Seq #</th>
            <th>Level</th>
            <th>Part #</th>
            <th>Description</th>
            <th>Jenis</th>
            <th>Cons</th>
            <th>Unit</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
            <?php
            # QUERY TABLE
            if($mod=="28R")
            { $sql_sesi=" and a.username='$user' and a.sesi='$sesi' "; 
              $sql="select a.*,s.product_group,s.product_item from masterproduct_detail_tmp a 
                inner join masterproduct s on a.id_product=s.id 
                where a.jenis_barang='FG' group by a.id_product 
                union all 
                select a.*,s.goods_code,s.itemdesc from masterproduct_detail_tmp a 
                inner join masteritem s on a.id_item=s.id_item 
                where a.jenis_barang!='FG'  order by seqno ";
            }
            else if($mod=="28LD")
            { if(isset($_GET['id'])) { $id_prd=$_GET['id']; } else { $id_prd="0"; }
              $sql_sesi=" "; 
              $sql="select '' seqno,'' level,mattype jenis_barang,a.*,s.goods_code,s.itemdesc from masterproduct_h a 
                inner join masteritem s on a.id_item=s.id_item 
                where a.id_product='$id_prd' order by a.id_item ";
              // $sql="select a.*,s.product_group,s.product_item from masterproduct_detail_tmp a 
              //   inner join masterproduct s on a.id_product=s.id 
              //   where a.jenis_barang='FG' and a.id_product='$id_prd' group by a.id_product   
              //   union all 
              //   select a.*,s.goods_code,s.itemdesc from masterproduct_detail_tmp a 
              //   inner join masteritem s on a.id_item=s.id_item 
              //   where a.jenis_barang!='FG' and a.id_product='$id_prd' order by seqno ";
            }
            else
            { $sql_sesi=" "; 
              $sql="select a.*,s.product_group,s.product_item from masterproduct_detail_tmp a 
                inner join masterproduct s on a.id_product=s.id 
                where a.jenis_barang='FG' group by a.id_product";
            }
            #echo $sql;
            $query = mysql_query($sql); 
            $no = 1; 
            while($data = mysql_fetch_array($query))
            { $cekexists=flookup("id_product","masterproduct_h","id_product='$data[id_product]'");
              if($cekexists!="") { $cekexists="Data Exists"; } else { $cekexists=""; }
              echo "<tr>";
                echo "
                <td>$data[seqno]</td>
                <td>$data[level]</td>";
                if($data['jenis_barang'=="FG"])
                { echo "
                  <td>$data[product_group]</td>
                  <td>$data[product_item]</td>";
                }
                else
                { echo "
                  <td>$data[goods_code]</td>
                  <td>$data[itemdesc]</td>";
                }
                echo "<td>$data[jenis_barang]</td>
                <td>$data[cons]</td>
                <td>$data[unit]</td>";
                if($mod=="28L")
                { echo "<td><a href='?mod=28LD&id=$data[id_product]'
                    data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a></td>";
                }
                else
                { echo "<td>$cekexists</td>"; }
              echo "</tr>";
              $no++; // menambah nilai nomor urut
            }
            ?>
          </tbody>
        </table>
        <?php if($mod=="28R") { ?>
        <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        <?php } ?>
      </div>
    </div>
  </form>
<?php } 
# END COPAS ADD
?>