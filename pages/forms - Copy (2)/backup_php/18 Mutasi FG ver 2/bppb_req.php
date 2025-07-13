<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$akses = flookup("req_mat","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
if (isset($_GET['id'])) {$id_req=$_GET['id'];} else {$id_req="";}
if (isset($_GET['mode'])) {$mode=$_GET['mode'];} else {$mode="";}
$rscomp=mysql_fetch_array(mysql_query("select * from mastercompany"));
  $st_company = $rscomp["status_company"];
  $harus_bpb = $rscomp["req_harus_bpb"];
  $logo_company = $rscomp["logo_company"];
$id_item="";
if($mod=="61rvp")
{
  // $nmpdf="pdfPickList.php";
  $nmpdf="pdfPLGDOUT.php";
}
else
{
  $nmpdf="pdfPickListReq.php";
}
# COPAS EDIT
if ($id_req=="")
{ $reqno="";
  $reqdate=date("d M Y");
  $sentto="";
  $notes="";
}
else
{ $cekbppb=flookup("count(*)","bppb","bppbno_req='$id_req'");
  if($cekbppb<>"0")
  {
    $_SESSION['msg']="XRequest # Sudah Ada Pengeluaran";
    echo "
    <script>
      window.location.href='?mod=1';
    </script>";
  }          
  $query = mysql_query("SELECT a.* FROM bppb_req a where a.bppbno='$id_req' ");
  $data = mysql_fetch_array($query);
  $reqno=$data['bppbno'];
  $reqdate=fd_view($data['bppbdate']);
  $sentto=$data['id_supplier'];
  $notes=$data['remark'];
}
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { var id_supplier = document.form.txtid_supplier.value;
    var id_jo = document.form.txtJOItem.value;
    var pilih = 0;
    var qtykos = 0;
    var qtyover = 0;
    var qtys = document.form.getElementsByClassName('qtybpbclass');
    var qtybtss = document.form.getElementsByClassName('qtysisaclass');
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value !== '')
      { qtykos = qtykos + 1; }
    }
    for (var i = 0; i < qtys.length; i++) 
    { if (qtys[i].value != '')
      {
        if (Number(qtys[i].value) > Number(qtybtss[i].value))
        { qtyover = qtyover + 1; }
      }
    }
    if (id_supplier == '') { swal({ title: 'Dikirim Ke Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (id_jo == '') { swal({ title: 'JO # Tidak Boleh Kosong', <?php echo $img_alert; ?> }); valid = false;}
    else if (qtykos == 0) { swal({ title: 'Tidak Ada Data', <?php echo $img_alert; ?>}); valid = false; }
    else if (qtyover > 0) { swal({ title: 'Qty Melebihi Stock', <?php echo $img_alert; ?>}); valid = false; }
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI

# COPAS ADD
?>
<script type="text/javascript">
  function getJO()
  { var id_jo = $('#cboJO').val();
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax3.php?modeajax=view_list_stock',
        data: {id_jo: id_jo},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item").html(html);
    }
  $(document).ready(function() {
      var table = $('#examplefix2').DataTable
      ({  scrollCollapse: true,
          paging: false,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
</script>
<?php if ($mod=="61r" or $mod=="61re") { ?>
<div class='box'>
  <div class='box-body'>
    <div class='row'>
      <form method='post' name='form' action='s_bppb_req.php?mod=<?php echo $mod; ?>&id=<?php echo $id_req; ?>' onsubmit='return validasi()'>
        <div class='col-md-3'>              
          <div class='form-group'>
            <label>Request #</label>
            <input type='text' readonly class='form-control' name='txtreqno' placeholder='Masukkan Request #' value='<?php echo $reqno;?>' >
          </div>        
          <div class='form-group'>
            <label>Request Date *</label>
            <input type='text' class='form-control' id='datepicker1' name='txtreqdate' placeholder='Masukkan Request Date' value='<?php echo $reqdate;?>' >
          </div>
          <div class='form-group'>
            <label>Dikirim Ke *</label>
            <select class='form-control select2' style='width: 100%;' 
              name='txtid_supplier'>
              <?php 
                $sql = "select id_supplier isi,supplier tampil from mastersupplier where area!='LINE' ";
                IsiCombo($sql,$sentto,'Pilih Dikirim Ke');
              ?>
            </select>
          </div>
        </div>
        <div class='col-md-3'>
          <div class='form-group'>
            <label>Notes</label>
            <textarea row='5' class='form-control' name='txtnotes' placeholder='Masukkan Notes'><?php echo $notes;?></textarea>
          </div>
          <?php if ($mod=="61r") { ?>
          <div class='form-group'>
            <label>Job Order # *</label>
            <select class='form-control select2' multiple='multiple' style='width: 100%;' 
              name='txtJOItem' id='cboJO' onchange='getJO()'>
            <?php 
            if($harus_bpb=="Y")
            { if($logo_company=="Z") 
              { $tmpl="concat(a.jo_no,'|',mp.product_group,'|',ms.supplier)"; }
              else
              { $tmpl="concat(a.jo_no,'|',ac.styleno,'|',ac.kpno)"; }
              $sql="select a.id isi, $tmpl tampil 
                from jo a inner join jo_det s on a.id=s.id_jo 
                inner join  so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
                inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
                inner join masterproduct mp on ac.id_product=mp.id 
                inner join bpb on a.id=bpb.id_jo group by a.id ";
            }
            else
            { $sql="select a.id isi,concat(a.jo_no,'|',ac.styleno,'|',ac.kpno) tampil 
                from jo a inner join jo_det s on a.id=s.id_jo 
                inner join  so on s.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
                group by a.id";
            }
            IsiCombo($sql,'','');
            ?>
            </select>
          </div>
          <?php } ?>
        </div>
        <div class='box-body'>
          <?php if($mod=="61re") 
          { echo "<table id='examplefix3' style='width: 100%;'>";
            echo "
              <thead>
                <tr>
                  <th>Kode Bahan Baku</th>
                  <th>Deskripsi</th>
                  <th>Qty Req</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>";
              $sql="select a.id_item,s.goods_code,s.itemdesc,a.qty,a.unit from bppb_req a inner join masteritem s on a.id_item=s.id_item 
                where bppbno='$id_req'";
              $i=1;
              $query=mysql_query($sql);
              while($data=mysql_fetch_array($query))
              { $id_item_req=$data['id_item'];
                echo "
                  <tr>
                    <td>$data[goods_code]</td>
                    <td>$data[itemdesc]</td>
                    <td><input type ='text' name ='txtqtyed[$id_item_req]' value='$data[qty]' id='qtyed$i' class='qtyedclass'></td>
                    <td><input type ='text' name ='txtuomed' value='$data[unit]' readonly></td>
                  </tr>";
                $i++;
              };
          echo "</tbody></table>";
          } 
          else 
          { ?>
            <div id='detail_item'></div>
          <?php } ?>
        </div>
        <div class='col-md-3'>
          <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div><?php } 
# END COPAS ADD
#if ($id_req=="") {
if ($mod=="61rv" or $mod=="61rvp") {
?>
<div class="box">
  <div class="box-header">
    <?php if($mod=="61rv") { ?>
      <h3 class="box-title">Permintaan Bahan Baku</h3>
    <?php } else { ?>
      <h3 class="box-title">Picklist Bahan Baku</h3>
    <?php } ?>
    <?php if($mod=="61rv") { ?>
  <a href='../forms/?mod=61r&mode=<?php echo $mode; ?>' class='btn btn-primary btn-s'>
      <i class='fa fa-plus'></i> New 
    </a>
  <?php } ?>
  </div>
  <div class="box-body">
    <table id="examplefix3" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>Request #</th>
        <th>Request Date</th>
        <th>Buyer</th>
        <th>Style #</th>
        <th>WS #</th>
        <th>Sent To</th>
        <th>Created By</th>
        <th>No. BPPB</th>
        <th></th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.username,bppbno,bppbdate,s.supplier,ac.kpno,ac.styleno,ms.supplier buyer  
          from bppb_req a inner join mastersupplier s on a.id_supplier=s.id_supplier 
          inner join jo_det jod on a.id_jo=jod.id_jo 
          inner join so on jod.id_so=so.id 
          inner join act_costing ac on so.id_cost=ac.id 
          inner join mastersupplier ms on ac.id_buyer=ms.id_supplier
          group by bppbno order by bppbdate desc "); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { $cekbppb=flookup("if(bppbno_int!='',bppbno_int,bppbno)","bppb","bppbno_req='$data[bppbno]'");
          echo "<tr>";
            echo "
            <td>$no</td>
            <td>$data[bppbno]</td>
            <td>$data[bppbdate]</td>
            <td>$data[buyer]</td>
            <td>$data[styleno]</td>
            <td>$data[kpno]</td>
            <td>$data[supplier]</td>
            <td>$data[username]</td>
            <td>$cekbppb</td>";
            if($cekbppb=="")
            {
              echo "
              <td>
                <a href='?mod=61re&mode=Bahan_Baku&id=$data[bppbno]'
                  data-toggle='tooltip' title='View'><i class='fa fa-eye'></i></a>
              </td>";
            }
            else
            {
              echo "<td></td>";
            }
            echo "
            <td>
              <a href='$nmpdf?id=$data[bppbno]'
                data-toggle='tooltip' title='Preview'><i class='fa fa-print'></i></a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if (($mod=="3" or $mod=="3e") and $id_req!="") { ?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">Request Detail</h3>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
        <th>No</th>
        <th>JO #</th>
        <th>Item</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Curr</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select l.id,jo_no,concat(a.nama_group,' ',s.nama_sub_group,' ',
        d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
        g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) item,l.qty,l.unit,l.curr,l.price 
        from po_item l inner join jo m on l.id_jo=m.id 
        inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
        inner join mastertype2 d on s.id=d.id_sub_group
        inner join mastercontents e on d.id=e.id_type
        inner join masterwidth f on e.id=f.id_contents 
        inner join masterlength g on f.id=g.id_width
        inner join masterweight h on g.id=h.id_length
        inner join mastercolor i on h.id=i.id_weight
        inner join masterdesc j on i.id=j.id_color 
        and l.id_gen=j.id where l.id_po='$id_req'"); 
        $no = 1; 
        while($data = mysql_fetch_array($query))
        { echo "<tr>";
            echo "<td>$no</td>"; 
            echo "<td>$data[jo_no]</td>";
            echo "<td>$data[item]</td>";
            echo "<td>".number_format($data['qty'],2)."</td>";
            echo "<td>$data[unit]</td>";
            echo "<td>$data[curr]</td>";
            echo "<td>".number_format($data['price'],2)."</td>";
            echo "
            <td>
              <a $cl_ubah href='?mod=3ei&id=$data[id]'
                $tt_ubah</a>
            </td>";
          echo "</tr>";
          $no++; // menambah nilai nomor urut
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<?php } ?>