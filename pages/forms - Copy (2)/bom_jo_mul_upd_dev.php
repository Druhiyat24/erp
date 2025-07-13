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
$cekapp=flookup("app","jo_dev","id='$id_jo'");

$titlenya="Act-Sample";
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
{	$query = mysql_query("SELECT * FROM bom_dev_jo_item where id_jo='$id_jo'");
	$data = mysql_fetch_array($query);
	$id_pre_cost = $data['id_jo'];
	$sql="select a.id,so_no,buyerno,kpno,cost_no,
   	supplier,product_group,product_item,styleno,a.qty,a.unit,
    deldate from so_dev a inner join act_development s on 
    a.id_cost=s.id inner join mastersupplier g on 
    s.id_buyer=g.id_supplier inner join masterproduct h 
    on s.id_product=h.id left join jo_det_dev j on a.id=j.id_so 
    where j.id_jo='$id_jo'";
  $rs=mysql_fetch_array(mysql_query($sql));
  $txtprodgroup=$rs['product_group'];
  $txtproditem=$rs['product_item'];
  $txtbuyer=$rs['supplier'];
  $txtstyle=$rs['styleno']; 
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
  
</script>
<?php if ($mod=="22DEV") { ?>
<?php } 
# END COPAS ADD REMOVE </DIV> TERAKHIR
if ($mod=="14L") {
?>
<?php } else if ($id_jo!="") { ?>
<form method='post' action='upd_bom_mul.php?mod=14&id=<?php echo $id_jo;?>'>
<div class="box">
  <div class="box-header">
    <h3 class="box-title">BOM Detail</h3>
    <?php if ($mod=="14mu") { ?>
    <button type='submit' name='submit' class='btn btn-primary btn-s'>
			<i class='fa fa-pencil'></i>
			Update Data
		</button>
		<?php } else { ?>
		<a href='../marketting/?mod=14md&id=<?php echo $id_jo; ?>' class='btn btn-danger btn-s'>
			<i class='fa fa-trash'></i> Multi Cancel
		</a> <a href='../marketting/?mod=14mu&id=<?php echo $id_jo; ?>' class='btn btn-primary btn-s'>
  		<i class='fa fa-pencil'></i> Multi Update
  	</a>
		<?php } ?>
  </div>
  <div class="box-body">
    <table id="examplefix" class="display responsive" style="width:100%">
      <thead>
      <tr>
	    	<th>No</th>
        <th>Color Gmt</th>
				<th>Size Gmt</th>
				<th>Item</th>
				<th>Qty Gmt</th>
				<th>Cons</th>
				<th>Qty BOM</th>
				<th>Unit</th>
				<th>Created By</th>
				<th>Status</th>
			</tr>
      </thead>
      <tbody> 
        <?php
        # QUERY TABLE
        if($mod=="14mu") { $flg_cancel=" and k.cancel='N' "; } else { $flg_cancel=""; }
        $sql="select k.id,l.color,l.size,concat(a.nama_group,' ',s.nama_sub_group,' ',
          d.nama_type,' ',e.nama_contents,' ',f.nama_width,' ',
          g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc,' ',j.add_info) item,l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom,
        	k.unit,up.fullname,k.cancel from bom_dev_jo_item k inner join so_det_dev l on k.id_so_det=l.id 
        	inner join mastergroup a inner join mastersubgroup s on a.id=s.id_group
          inner join mastertype2 d on s.id=d.id_sub_group
          inner join mastercontents e on d.id=e.id_type
          inner join masterwidth f on e.id=f.id_contents 
          inner join masterlength g on f.id=g.id_width
          inner join masterweight h on g.id=h.id_length
          inner join mastercolor i on h.id=i.id_weight
          inner join masterdesc j on i.id=j.id_color and k.id_item=j.id
          inner join userpassword up on k.username=up.username
					where k.id_jo='$id_jo' and status='M' $flg_cancel ";
        $query = mysql_query($sql); 
        #echo $sql;
        $no = 1; 
				while($data = mysql_fetch_array($query))
			  { if($data['cancel']=="Y") { $bgcol=" style='color: red; font-weight:bold;'"; } else { $bgcol=""; }
          $idcri=$data['id'];
          echo "
          <tr $bgcol>
          	<td>$no</td>
          	<td>$data[color]</td>
          	<td>$data[size]</td>
          	<td>$data[item]</td>
          	<td>$data[qty_gmt]</td>
          	<td>
				    	<input type='text' class='form-control conscl' size='4' name='consar[$idcri]' 
				    		value='$data[cons]'>
				    </td>
				    <td>$data[qty_bom]</td>
				    <td>
				    	<input type='text' class='form-control unitcl' size='4' name='unitar[$idcri]' 
				    		value='$data[unit]'>
				    </td>
				    <td>$data[fullname]</td>";
						if($data['cancel']=="Y")
						{	echo "<td>Canceled</td>";	}
						else
						{	echo "<td></td>";	}
					echo "</tr>";
				  $no++; // menambah nilai nomor urut
				}
				$query = mysql_query("select k.cancel,k.id,l.color,l.size,concat(s.matclass,' ',s.goods_code,' ',s.itemdesc) item,l.qty qty_gmt,k.cons,round(l.qty*k.cons,2) qty_bom,
        	k.unit,up.fullname 
        	from bom_dev_jo_item k inner join so_det_dev l on k.id_so_det=l.id inner join masteritem s on k.id_item=s.id_item
          inner join userpassword up on k.username=up.username
					where k.id_jo='$id_jo' and status='P' $flg_cancel "); 
        while($data = mysql_fetch_array($query))
			  { echo "<tr>";
				    echo "<td>$no</td>"; 
            echo "<td>$data[color]</td>";
						echo "<td>$data[size]</td>";
						echo "<td>$data[item]</td>";
						echo "<td>$data[qty_gmt]</td>";
						echo "<td>$data[cons]</td>";
						echo "<td>$data[qty_bom]</td>";
						echo "<td>$data[unit]</td>";
						echo "<td>$data[fullname]</td>";
						if($data['cancel']=="Y")
						{	echo "<td>Canceled</td>";	}
						else
						{	echo "<td></td>";	}
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