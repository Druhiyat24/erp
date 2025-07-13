<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

# START CEK HAK AKSES KEMBALI
$mod=$_GET['mod'];
$akses = flookup("over_ship_alloc","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
# COPAS EDIT
# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function validasi()
  { 
    var ponya = document.form.cbopo.value;
    var qtyover = document.form.txtqtyover.value;
    var idjo_o = document.form.getElementsByClassName('id_joclass');
    var qtyov = document.form.getElementsByClassName('qtyoverclass');
    var qtyo = document.form.getElementsByClassName('qtyfocclass');
    var qtyr = document.form.getElementsByClassName('qtyretclass');
    var qtya = document.form.getElementsByClassName('qtyaddclass');
    var tot_qty_over = 0;
    var cek_tot_qty_over = 0;
    var tot_qty_over_det = 0;
    var jo_kos = "";
    var cuma_tes = "DEFVAL";
    var qtyovernya = 0;
    for (var i = 0; i < qtyo.length; i++) 
    { 
      if (idjo_o[i].value !== '')
      {
        jo_kos = 'Oke';
      }
      else
      {
        jo_kos = ''; break;
      } 
      if (Number(qtyo[i].value) > 0)
      { 
        tot_qty_over = tot_qty_over + Number(qtyo[i].value);
      }
      if (Number(qtyr[i].value) > 0)
      { 
        tot_qty_over = tot_qty_over + Number(qtyr[i].value);
      }
      if (Number(qtya[i].value) > 0)
      { 
        tot_qty_over = tot_qty_over + Number(qtya[i].value);
      }
      qtyovernya = qtyov[i].value;
      cek_tot_qty_over = cek_tot_qty_over + tot_qty_over;
      if (Number(parseFloat(qtyovernya).toFixed(2)) != Number(parseFloat(tot_qty_over).toFixed(2)))
      { 
        tot_qty_over_det = tot_qty_over_det + 1;
        cuma_tes = Number(parseFloat(qtyovernya).toFixed(2))+' vs '+Number(parseFloat(tot_qty_over).toFixed(2));
        break;
      }
      tot_qty_over = 0;
    }
    if(ponya == '') { swal({ title: 'PO # Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if(jo_kos == '') { swal({ title: 'WS # Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if(Number(tot_qty_over_det) > 0) { swal({ title: 'Qty Over Tidak Valid', <?php echo $img_alert; ?> });valid = false;}
    else if(Number(qtyover) == 0) { swal({ title: 'Qty Over Tidak Boleh Kosong', <?php echo $img_alert; ?> });valid = false;}
    else if(Number(parseFloat(qtyover).toFixed(2)) != Number(parseFloat(cek_tot_qty_over).toFixed(2))) { swal({ title: 'Qty Over (' + qtyover + ') Tidak Sesuai (' + tot_qty_over + ')', <?php echo $img_alert; ?> });valid = false;}
    else valid = true;
    return valid;
    exit;
  };
  function getDetail(ponya)
  { jQuery.ajax
    ({  url: 'ajax_cek_qty_over.php?modeajax=get_qty_over',
        method: 'POST',
        data: {ponya: ponya}, 
        success: function(response){
          jQuery('#txtqtyover').val(response);  
        },
        error: function (request, status, error) 
        { alert(request.responseText); },
    });
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_cek_qty_over.php?modeajax=view_list_po',
        data: "ponya=" +ponya,
        async: false
    }).responseText;
    if(html)
    { $("#detail_item").html(html); }
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
<?php
# END COPAS VALIDASI
# COPAS ADD
if($mod=="15L")
{
?>
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">List Outstanding</h3>
      <a href='../pur/?mod=15' class='btn btn-primary btn-s'>
        <i class='fa fa-plus'></i> New
      </a>
    </div>
    <div class="box-body">
      <table id="examplefix" class="display responsive" style="width:100%;font-size:11px;">
        <thead>
          <tr>
            <th>Nomor BPB</th>
            <th>Tgl BPB</th>
            <?php if($jenis_company=="VENDOR LG") { ?>
              <th>Nomor JO</th>
            <?php } else { ?>
              <th>Nomor WS</th>
            <?php } ?> 
            <th>Kode Bahan Baku</th>
            <th>Deskripsi</th>
            <th>Satuan</th>
            <th>Qty PO</th>
            <th>Qty BPB</th>
            <th>Qty Tollerance</th>
            <th>Qty Over</th>
            <th>FOC</th>
            <th>Status FOC</th>
            <th>Retur</th>
            <th>Retur #</th>
            <th>Add PO</th>
            <th>Add PO #</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql_join=" s.id_gen=mi.id_gen ";
          $jenispo="";
          if($jenispo=="N")
          { 
          }
          else
          { $sql="select f.bpbdate,ac.kpno,if(f.bpbno_int!='',f.bpbno_int,f.bpbno) trxno,f.bpbno,
              a.id_po_item,s.id_jo,s.id_gen,a.qty_foc,qty_ret,qty_add,a.bpbno,mi.id_item,
              mi.goods_code,mi.itemdesc,f.unit,(a.qty_foc+a.qty_ret+a.qty_add) tot_over,
              if(d.po_over='W','Waiting','Completed') statusnya,a.dateinput from bpb_over a inner join 
              po_item s on a.id_po_item=s.id inner join po_header d on s.id_po=d.id 
              inner join bpb f on a.bpbno=f.bpbno and a.id_po_item=f.id_po_item 
              inner join masteritem mi on $sql_join inner join jo_det jod on s.id_jo=jod.id_jo 
              inner join jo on jod.id_jo=jo.id 
              inner join so on jod.id_so=so.id
              inner join act_costing ac on so.id_cost=ac.id 
              ORDER BY FIELD(d.po_over, 'W') desc ";
            #echo $sql;
          }
          $i=1;
          $query=mysql_query($sql);
          while($data=mysql_fetch_array($query))
          { $id=$data['bpbno'].":".$data['id_po_item'];
            $qty_po = flookup("qty","po_item","id='$data[id_po_item]'");
            $qty_bpb = flookup("sum(qty)","bpb","id_po_item='$data[id_po_item]' group by id_po_item");
            $qty_bal = $qty_bpb - $qty_po;
            $readtxt = " readonly ";
            $po_add = "";
            $status_app = "N";
            if($data['qty_foc']>0)
            {
              $tgl_foc = date('Y-m-d',strtotime($data['dateinput']));
            }
            else
            {
              $tgl_foc = "";
            }
            if($data['qty_add']>0)
            {
            	$po_add = flookup("concat(pono,' ',podate)","po_header a inner join po_item s on a.id=s.id_po","pono regexp 'ADD' 
            		and s.id_gen='$data[id_gen]' and s.id_jo='$data[id_jo]'");	
            	if($po_add!="")
            	{
            		$status_app = "Y";
            	}
            	else
            	{
            		$status_app = "N";
            	}
            }
            else
            {
            	$status_app = "Y";
            }
            $status_app = "N";
            $nom_ret = "";
            if($data['qty_ret']>0)
            {
            	$nom_ret = flookup("concat(ifnull(bppbno_int,bppbno),' ',bppbdate)","bppb","bpbno_ro='$data[bpbno]' 
            		and id_item='$data[id_item]' and id_jo='$data[id_jo]'");	
            	if($nom_ret!="")
            	{
            		$status_app = "Y";
            	}
            	else
            	{
            		$status_app = "N";
            	}
            }
            else
            {
            	$status_app = "Y";
            }
            echo "
            <tr>";
              echo "
              <td>$data[trxno]</td>
              <td>$data[bpbdate]</td>";
              if($jenis_company=="VENDOR LG")
              { echo "<td>$data[jo_no]</td>"; }
              else
              { echo "<td>$data[kpno]</td>"; }
              echo "
              <td>$data[goods_code]</td>
              <td>$data[itemdesc]</td>
              <td>$data[unit]</td>
              <td>$qty_po</td>
              <td>$qty_bpb</td>
              <td>$qty_bal</td>
              <td>$data[tot_over]</td>
              <td>$data[qty_foc]</td>
              <td>$tgl_foc</td>
              <td>$data[qty_ret]</td>
              <td>$nom_ret</td>
              <td>$data[qty_add]</td>
              <td>$po_add</td>
              <td>$data[statusnya]</td>";
              if($data['statusnya']=="Waiting" and $status_app == "Y")
              {
                echo "
                <td><a href='app_over_toll.php?mod=$mod&id=$data[id_po_item]&idd=$data[bpbno]'
                  data-toggle='tooltip' title='Mark As Complete' ";?> 
                  onclick="return confirm('Apakah Anda Yakin ?')">
                  <?php echo "<i class='fa fa-check'></i>"."</a>
                </td>";
              }
              else
              {
                echo "<td></td>";
              }
            echo "
            </tr>";
            $i++;
          };
          ?>
        </tbody>
      </table>
    </div>
  </div>
<?php } else { ?>
  <form method='post' name='form' enctype='multipart/form-data' 
    action='s_over_alloc.php?mod=<?=$mod?>' onsubmit='return validasi()'>
    <div class='box'>
      <div class='box-body'>
        <div class='row'>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>PO #</label>
              <?php
              $sql="select a.pono isi,concat(a.pono) tampil from 
                po_header a inner join po_item s on a.id=s.id_po 
                inner join masteritem mi on s.id_gen=mi.id_gen 
                inner join 
                (select ifnull(d.bpbno,'Ok') st_over,a.pono,a.id_po_item from bpb a inner join po_header s on a.pono=s.pono 
                	left join bpb_over d on a.bpbno=d.bpbno and a.id_po_item=d.id_po_item 
                  where a.qty_over>0 group by a.pono,a.id_po_item) tmpbpb on s.id=tmpbpb.id_po_item	
                where a.po_over='Y' and st_over='Ok' group by a.pono";
              echo "<select class='form-control select2' style='width: 100%;' name='cbopo' 
                onchange='getDetail(this.value)'>";
              IsiCombo($sql,'',$cpil.' PO #');
              echo "</select>";
              ?>
            </div>
          </div>
          <div class='col-md-3'>
            <div class='form-group'>
              <label>Qty Over Ship</label>
              <input type='text' class='form-control' readonly id='txtqtyover'>
            </div>
          </div>
          <div class='col-md-12'>
            <div id='detail_item'>
            </div>
            <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php
}
# END COPAS ADD
?>