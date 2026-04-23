<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("bom_jo","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='index.php?mod=1';</script>"; }
# END CEK HAK AKSES KEMBALI
$nm_company = flookup("company","mastercompany","company!=''");
if ($jenis_company=="VENDOR LG")
{	$cprodgr="Part #"; 
	$cprodit="Part Name";
	$cstyle="Model";
}
else
{	$cprodgr="Product Group"; 
	$cprodit="Product Item";
	$cstyle="Style #";
}

if (isset($_GET['id'])) {$id_jo = $_GET['id']; } else {$id_jo = "";}
if (isset($_GET['pro'])) {$pro = $_GET['pro']; } else {$pro = "";}
$cekapp=flookup("app","jo","id='$id_jo'");

$titlenya="Act-Costing";
$mode="";
$mod=$_GET['mod'];
$id_item=$id_jo;
$st_num="style='text-align: right;'";
# COPAS EDIT
if ($id_item=="")
{	$id_pre_cost = "";
	$txtprodgroup="";
  $txtproditem="";
  $txtbuyer="";
  $txtstyle="";
}
else
{	$query = mysql_query("SELECT * FROM bom_jo_item where id_jo='$id_jo'");
	$data = mysql_fetch_array($query);
	$id_pre_cost = $data['id_jo'];
	$sql="select a.id,so_no,buyerno,kpno,cost_no,
   	supplier,product_group,product_item,styleno,a.qty,a.unit,
    deldate from so a inner join act_costing s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det j on a.id=j.id_so 
    where j.id_jo='$id_jo'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $txtprodgroup=$rs['product_group'];
  $txtproditem=$rs['product_item'];
  $txtbuyer=$rs['supplier'];
  $txtstyle=$rs['styleno']; 
}

$tglbomawal = date('Y-m-d');
$tglbomakhir = date('Y-m-d');

if (isset($_POST['submitfilter']))
{
  $tglbomawal = $_POST['tglbomawal'];
  $tglbomakhir = $_POST['tglbomakhir'];  
}

# END COPAS EDIT

# COPAS VALIDASI BUANG ELSE di IF pertama
echo "<script type='text/javascript'>
	function validasi()
	{	var id_pre_cost = document.form.txtid_pre_cost.value;
		var cekapp = '".$cekapp."';
		if (cekapp == 'A') { swal({ title: 'BOM Tidak Bisa Diubah', $img_alert }); valid = false;}
		else if (id_pre_cost == '') { document.form.txtid_pre_cost.focus(); swal({ title: 'JO # Tidak Boleh Kosong', $img_alert }); valid = false;}
		else valid = true;
		return valid;
		exit;
	}
</script>";
# END COPAS VALIDASI
# COPAS ADD
?>
<script type="text/javascript">
  function select_all()
  {
  	var chks = document.form2.getElementsByClassName('chkclass');
    for (var i = 0; i < chks.length; i++) 
    { chks[i].checked = true; }
  };
  function getSO()
  { var joid = document.form.txtid_pre_cost.value;
    jQuery.ajax
    ({  
      url: "ajax_bom.php?mdajax=get_so",
      method: 'POST',
      data: {joid: joid},
      dataType: 'json',
      success: function(response)
      {
        $('#txtprodgroup').val(response[0]);
        $('#txtproditem').val(response[1]);
        $('#txtbuyer').val(response[2]);
        $('#txtstyle').val(response[3]);
      },
      error: function (request, status, error) 
      { alert(request.responseText); },
    });
  };
</script>
<?php if ($mod=="14") { ?>
<div class='box'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="../marketting/?mod=14d" onsubmit='return validasi()'>
				<div class='col-md-3'>								
					<div class='form-group'>
						<label>Job Order # *</label>
						<select class='form-control select2' style='width: 100%;' name='txtid_pre_cost' onchange='getSO()'>
							<?php 
								if ($id_jo=="") {$sql_wh="where bji.id_jo is null";} else {$sql_wh=" where jo.id='$id_jo'";}
								$sql = "select jo.id isi,concat(jo.jo_no,' - ',supplier,' - ',ac.styleno,' - ',ac.kpno) tampil from 
									jo inner join jo_det jod on jo.id=jod.id_jo
									inner join so on jod.id_so=so.id
									inner join act_costing ac on so.id_cost=ac.id 
									inner join mastersupplier ms on ac.id_buyer=ms.id_supplier 
									left join (select id_jo from bom_jo_item group by id_jo) bji on jo.id=bji.id_jo $sql_wh 
									group by jo.id ";
								IsiCombo($sql,$id_pre_cost,'Pilih Job Order #');
							?>
						</select>
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodgr;?></label>
						<input type='text' class='form-control' readonly id='txtprodgroup' value='<?php echo $txtprodgroup; ?>'>
					</div>				
					<div class='form-group'>
						<label><?php echo $cprodit;?></label>
						<input type='text' class='form-control' readonly id='txtproditem' value='<?php echo $txtproditem; ?>'>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Buyer</label>
						<input type='text' class='form-control' readonly id='txtbuyer' value='<?php echo $txtbuyer; ?>'>
					</div>				
					<div class='form-group'>
						<label><?php echo $cstyle;?></label>
						<input type='text' class='form-control' readonly id='txtstyle' value='<?php echo $txtstyle; ?>'>
					</div>
					<button type='submit' name='submit' class='btn btn-primary'>Add Item</button>
				</div>
			</form>
		</div>
	</div>
</div><?php } 
# END COPAS ADD REMOVE </DIV> TERAKHIR
if ($mod=="14L") {
?>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">BOM List</h3>
    <a href='../marketting/?mod=14' class='btn btn-primary btn-s'>
  		<i class='fa fa-plus'></i> New
  	</a>
  </div>

<div class='row'>
    <form action="" method="post">

    <div class="box-header">
      <div class='col-md-2'>                            
        <label>Tgl BOM (Awal) : </label>
        <input type='date' class='form-control' id='tglbomawal' name='tglbomawal' placeholder='Masukkan Tgl JO /  Worksheet' value='<?php echo $tglbomawal;?>' >             
      </div>
      <div class='col-md-2'>                            
        <label>Tgl BOM (Akhir) : </label>
        <input type='date' class='form-control' id='tglbomakhir' name='tglbomakhir' placeholder='Masukkan Tgl JO /  Worksheet' value='<?php echo $tglbomakhir;?>' >             
      </div>      
      <div class='col-md-3'>
          <div>
          <br>
              <button type='submit' name='submitfilter' class='btn btn-primary'>Tampilkan</button>              
          </div>         
      </div>

   </div>
    </form>
  </div>


  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
      <tr>
	    <th>No</th>
        <th>Buyer</th>
        <th>WS #</th>
        <th>Job Order #</th>
		<th>Job Order Date</th>
		<th><?php echo $cstyle;?></th>
		<th>Brand</th>
		<th>Rev</th>
		<th>Created By</th>
		<th>All</th>
		<th>Fabric</th>
		<th>Accs Packing</th>
		<th>Accs Sewing</th>
		<th>Manufacturing</th>
		<th>View</th>
		</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        $query = mysql_query("select a.revise,group_concat(distinct concat(' ',ac.kpno)) kpno,ac.brand,a.id,
        	a.jo_no,a.jo_date,fullname,msup.supplier,ac.styleno,max(s.dateinput) last_update   
        	from jo a inner join bom_jo_item s on a.id=s.id_jo
        	inner join jo_det jod on a.id=jod.id_jo
        	inner join so on jod.id_so=so.id inner join act_costing ac on so.id_cost=ac.id 
        	inner join mastersupplier msup on ac.id_buyer=msup.id_supplier 
        	inner join userpassword up on a.username=up.username 
        	where a.jo_date >= '$tglbomawal' and a.jo_date <= '$tglbomakhir'
        	group by a.jo_no order by a.jo_date desc"); 
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { 
			  	$createby=$data['fullname']." ".fd_view_dt($data['last_update']);
			  	echo "<tr>";
				    echo "
				    <td>$no</td>
				    <td>$data[supplier]</td>
				    <td>$data[kpno]</td>
				    <td>$data[jo_no]</td>
				    <td>".fd_view($data['jo_date'])."</td>
				    <td>$data[styleno]</td>
				    <td>$data[brand]</td>
				    <td>$data[revise]</td>
				    <td>$createby</td>";
						echo "
			<td style='text-align: center; vertical-align: middle;'>
            	<a href='pdfBOM.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-pdf-o'></i>
	            </a>
	        </td>
            <td style='text-align: center; vertical-align: middle;'>
            	<a href='pdfBOM_fabric.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-text-o'></i>
	            </a>
	        </td>
            <td style='text-align: center; vertical-align: middle;'>
            	<a href='pdfBOM_acc_packing.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-text'></i>
	            </a>
	        </td>
            <td style='text-align: center; vertical-align: middle;'>
            	<a href='pdfBOM_acc_sewing.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file-text'></i>
	            </a>
	        </td>			
            <td style='text-align: center; vertical-align: middle;'>
            	<a href='pdfBOM_manufacturing.php?id=$data[id]'
	              data-toggle='tooltip' title='Save PDF' target='_blank'><i class='fa fa-file'></i>
	            </a>
	        </td>									
	          <td>
	            <a href='?mod=14&id=$data[id]'
              	data-toggle='tooltip' title='View'><i class='fa fa-eye'></i>
              </a>
            </td>";
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
			  ?>
      </tbody>
    </table>
  </div>
</div>
<?php } else if ($id_jo!="") { ?>
<form name='form2' method='post' action='d_so_mul.php?mod=14&id=<?php echo $id_jo;?>'>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">BOM Detail</h3>
    <?php if ($mod=="14md") { ?>
    <button type='submit' name='submit' class='btn btn-danger btn-s'>
			<i class='fa fa-trash'></i>
			Cancel Selected
		</button>
		<a href='#' class='btn btn-primary btn-s' onclick='select_all()'>Select All</a>
		<?php } else { ?>
		<a href='../marketting/?mod=14md&id=<?php echo $id_jo; ?>' class='btn btn-danger btn-s'>
			<i class='fa fa-trash'></i> Multi Cancel
		</a> <a href='../marketting/?mod=14mu&id=<?php echo $id_jo; ?>' class='btn btn-primary btn-s'>
  		<i class='fa fa-pencil'></i> Multi Update
		</a> <a href='?mod=14&id=<?php echo $id_jo;?>&dest=xls' class='btn btn-success btn-s'>
  		<i class='fa fa-print'></i> Save To Excel 		
  	</a>
		<?php } ?>

  </div>
  <div class="box-body">
    <table id="examplefixnopage" class="display responsive" style="width:100%;font-size:11px;">
      <thead>
      <tr>
	    	<th>No</th>
	    	<th>ID Item</th>
	    	<th>ID Contents</th>
        <th>Panel</th>
				<th>Dest</th>
				<th>Color Gmt</th>
				<th>Size Gmt</th>
				<th>Item</th>
				<th>Qty Gmt</th>
				<th>Cons</th>
				<th>Qty BOM</th>
				<th>Unit</th>
				<th>Notes</th>
				<th>Created By</th>
				<th>Rule BOM</th>
				<th>Status</th>
				<th></th>
			</tr>
      </thead>
      <tbody>
        <?php
        # QUERY TABLE
        if($mod=="14md") { $flg_cancel=" and k.cancel='N' "; } else { $flg_cancel=""; }
        $sql="select k.id,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
          d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
          g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,
					l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom,
        	k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,mpan.nama_panel,k.dest, k.notes,
        	f.id_contents, mi.id_item  
        	from bom_jo_item k inner join so_det l on k.id_so_det=l.id 
        	inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type
          inner join masterwidth f on e.id=f.id_contents 
          inner join masterlength g on f.id=g.id_width
          inner join masterweight h on g.id=h.id_length
          inner join mastercolor i on h.id=i.id_weight
          inner join masterdesc j on i.id=j.id_color and k.id_item=j.id
          inner join masteritem mi on k.id_item = mi.id_gen
          inner join userpassword up on k.username=up.username
					left join masterpanel mpan on k.id_panel=mpan.id 
					where k.id_jo='$id_jo' and status='M' $flg_cancel 
					union all 
					select k.id,l.color,l.size,concat(s.matclass,' ',s.goods_code,' ',s.itemdesc, ' ',s.color) item,
					l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom,
        	k.unit,up.fullname,k.cancel,k.rule_bom,k.posno,'' nama_panel,k.dest, k.notes,
        	'' id_contents, k.id_item 
        	from bom_jo_item k inner join so_det l on k.id_so_det=l.id inner join masteritem s on k.id_item=s.id_item
          inner join userpassword up on k.username=up.username
					where k.id_jo='$id_jo' and status='P' $flg_cancel";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { if($data['cancel']=="Y") { $bgcol=" style='background-color:red;color:yellow;'"; } else { $bgcol=""; }
          echo "
          <tr $bgcol>
          	<td>$data[posno]</td>
          	<td>$data[id_item]</td>
          	<td>$data[id_contents]</td>
          	<td>$data[nama_panel]</td>
          	<td>$data[dest]</td>
          	<td>$data[color]</td>
          	<td>$data[size]</td>
          	<td>$data[item]</td>
          	<td>$data[qty_gmt]</td>
          	<td>$data[cons]</td>
          	<td>$data[qty_bom]</td>
          	<td>$data[unit]</td>
			<td>$data[notes]</td>
          	<td>$data[fullname]</td>
          	<td>$data[rule_bom]</td>";
						if($data['cancel']=="Y")
						{	echo "<td>Canceled</td>";	}
						else
						{	echo "<td>-</td>";	}
						if ($mod=="14md")
						{	echo "
			    		<td>
			    			<input type ='hidden' name='chkhide[$data[id]]' value='$data[id]'>
                <input type ='checkbox' name='itemchk[$data[id]]' class='chkclass'>
             	</td>";	
						}
						else
						{	if($data['cancel']=="N")
							{	echo "
		            <td>
		            	<a href='?mod=14e&id=$id_jo&idd=$data[id]'
		              	$tt_ubah</a>
		            </td>";
		          }
		          else { echo "<td></td>"; }
						}
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
				?>
      </tbody>
    </table>
  </div>
</div>
</form>
<?php } ?>