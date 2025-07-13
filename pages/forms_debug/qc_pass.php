<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$query = mysql_query("SELECT * FROM mastercompany limit 1");
$data = mysql_fetch_array($query);
$nm_company = $data['company'];
$st_company = $data['status_company'];

$titlenya="Bahan Baku";
$id_item="";

# COPAS EDIT

# END COPAS EDIT
# COPAS VALIDASI BUANG ELSE di IF pertama
?>
<script type='text/javascript'>
  function get_list_data(perinya)
  {   
    var modenya = '<?=$mode?>';
    var modnya = '<?=$mod?>';
    var html = $.ajax
    ({  type: "POST",
        url: 'ajax_get_list_qc_pass.php',
        data: {perinya: perinya, modenya: modenya, modnya: modnya},
        async: false
    }).responseText;
    if(html)
    {  
        $("#detail_item2").html(html);
    }
    $(document).ready(function() {
      var table = $('#examplefix3').DataTable
      ({  scrollCollapse: true,
          paging: true,
          fixedColumns:   
          { leftColumns: 1,
            rightColumns: 1
          }
      });
    });
  };
  function validasi()
  {
    var statcek = document.getElementsByClassName('staclass');
    var qtycek = document.getElementsByClassName('qtyqcclass');
    var defcek = document.getElementsByClassName('defqcclass');
    var statnodef = '0';
    var ceknodat = '0';
    var qtynostat = '0';
    for (var i = 0; i < statcek.length; i++) 
    { 
      if (Number(qtycek[i].value)>0)
      { ceknodat = 1;
        if (statcek[i].value != 'Pass' && defcek[i].value == '')
        { statnodef='1'; }

      }
    }
    if (ceknodat == '0') { alert('Tidak Ada Data');valid = false;}
    else if (statnodef == '1') { alert('Jenis Defect Tidak Boleh Kosong');valid = false;}
    else valid = true;
    return valid;
    exit;
  }
</script>
<?php
# END COPAS VALIDASI
?>
<script type="text/javascript">
  function getListKPNo(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_list_qc',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#cbosj").html(html);
      }
  }
  function getListData(cri_item)
  {   var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=view_list_qc',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {	 
          $("#detail_item").html(html);
      }
      $(".select2").select2();
      $(document).ready(function() {
        var table = $('#examplefix').DataTable
        ({  sorting: false,
            searching: false,
            paging: false,
            fixedColumns:   
            { leftColumns: 1,
              rightColumns: 1
            }
        });
      });
      var html = $.ajax
      ({  type: "POST",
          url: 'ajax2.php?modeajax=cari_defect',
          data: "cri_item=" +cri_item,
          async: false
      }).responseText;
      if(html)
      {  
          $("#cbodefect").html(html);
      }
  }
</script>
<?php if($mod=="23") {
# COPAS ADD
echo "<div class='box'>";
  echo "<div class='box-body'>";
    echo "<div class='row'>";
      echo "<form method='post' name='form' action='save_data_qc.php?mod=$mod&mode=$mode&id=$id_item' onsubmit='return validasi()'>";
        echo "<div class='box-body'>";
        	$sql="select s.mattype,if(a.bpbno_int='',a.bpbno,a.bpbno_int) bpbno,a.bpbno bpbno_ori,a.id_item,s.goods_code,s.itemdesc,
        		s.color,a.qty,
						a.unit,a.dicekqc,tjo.kpno,pono 
            from bpb a inner join masteritem s 
						on a.id_item=s.id_item 
            left join 
            (select jod.id_jo,act.kpno from jo_det jod inner join so on jod.id_so=so.id 
              inner join act_costing act on so.id_cost=act.id group by jod.id_jo) tjo on tjo.id_jo=a.id_jo 
            where dicekqc='N' and mattype in ('A','F') and left(a.bpbno,2)!='FG'   
						order by a.bpbdate desc";
					echo "<table style='width: 100%;' id='examplefix3'>";
						echo "
							<thead>
								<tr>
									<th>No</th>
									<th>BPB #</th>
									<th>Nomor WS</th>
									<th>Nomor PO</th>
									<th>Deskripsi</th>
									<th>Qty BPB</th>
									<th>Satuan</th>
									<th>Qty Check</th>
									<th>Status</th>
									<th>Nama QC</th>
									<th>Defect</th>
								</tr>
							</thead>";
							$i=1;
							$query=mysql_query($sql);
							while($data=mysql_fetch_array($query))
							{	$id = $data['id_item']."|".$data['bpbno_ori'];
								echo "<tr>";
									echo "
										<td>$i</td>
										<td>$data[bpbno]</td>
										<td>$data[kpno]</td>
										<td>$data[pono]</td>
										<td>$data[itemdesc]</td>
										<td><input type ='text' size='8' name ='itemstock[$id]' value='$data[qty]' id='stockajax$i' class='form-control' readonly></td>
										<td>$data[unit]</td>
										<td><input type ='text' size='8' name ='itemqc[$id]' id='qcajax$i' class='form-control qtyqcclass'></td>
										<td>
											<select style='width: 80px;' name='cbostatus[$id]' class='form-control select2 staclass'>
												<option></option>";
			              		if ($data['dicekqc']=="Y")
			              		{ echo "<option selected>Pass</option>"; 
			              			echo "<option>Reject</option>";
			              			echo "<option>Pass With Condition</option>";
			              		} 
			              		else if ($data['dicekqc']=="R")
			              		{ echo "<option>Pass</option>"; 
			              			echo "<option selected>Reject</option>";
			              			echo "<option>Pass With Condition</option>";
			              		} 
			              		else if ($data['dicekqc']=="C")
			              		{ echo "<option>Pass</option>"; 
			              			echo "<option>Reject</option>";
			              			echo "<option selected>Pass With Condition</option>";
			              		} 
			              		else
			              		{ echo "<option>Pass</option>"; 
			              			echo "<option>Reject</option>";
			              			echo "<option>Pass With Condition</option>";
			              		}
			              	echo "	
			              	</select>
										</td>
										<td><input type ='text' size='4' name ='itemby[$id]' id='byajax$i' class='form-control namaqcclass'></td>
										<td>
											<select class='form-control select2 defqcclass' style='width: 100px;' name='itemdef[$id]' id='defajax'>";
												$sql="select id_defect isi,nama_defect tampil from 
													master_defect
													where left(mattype,1)='$data[mattype]' ";
												IsiCombo($sql,'','Pilih Jenis Defect');
              				echo "
              				</select>
										</td>";
								echo "</tr>";
								$i++;
							};
					echo "</table>";
        echo "</div>";
        echo "<div class='col-md-3'>";
          echo "<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>";
        echo "</div>";
      echo "</form>";
    echo "</div>";
  echo "</div>";
echo "</div>";
# END COPAS ADD
} else { ?>
  <div class="box">
    <?php 
    if ($mode=="FG")
    { $fldnyacri=" left(bpbno,2)='FG' "; $mod2=55; }
    else if ($mode=="Mesin")
    { $fldnyacri=" left(bpbno,1)='M' "; $mod2=53; }
    else if ($mode=="Scrap")
    { $fldnyacri=" left(bpbno,1) in ('S','L') "; $mod2=52; }
    else if ($mode=="WIP")
    { $fldnyacri=" left(bpbno,1)='C' "; $mod2=54; }
    else if ($mode=="General")
    { $fldnyacri=" left(bpbno,1)='N' "; $mod2="26e"; }
    else 
    { $fldnyacri=" left(bpbno,1) in ('A','F','B') and left(bpbno,2)!='FG' "; $mod2="26e"; }
    ?>
    <div class="box-header">
      <h3 class="box-title">List QC <?php echo $titlenya; ?></h3>
      <a href='../forms/?mod=<?php echo 23; ?>&mode=<?php echo $mode; ?>' class='btn btn-primary btn'>
        <i class='fa fa-plus'></i> New
      </a>
      <input type='text' class='monthpick' style='width:100px;height:31px;' onchange='get_list_data(this.value)' 
        autocomplete='off' onkeydown='return false' placeholder='Pilih Bulan'>
    </div>
    <div class="box-body">
      <div id='detail_item2'>
      </div>
    </div>
  </div>  
<?php } ?>