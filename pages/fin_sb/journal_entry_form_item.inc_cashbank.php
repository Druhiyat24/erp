<?php
class ModelDetail extends Model{
    public function get_cost_category_list_hash()
    {
        $sql = "SELECT * FROM mastercostcategory";

        $list = $this
            ->query($sql)
            ->result();

        if(!@count($list)){
            return array();
        }

        $hash = array();
        foreach($list as $l){
            $hash[$l->id_cost_category] = $l->nm_cost_category;
        }
        return $hash;
    }

    public function get_cost_dept_list_hash()
    {
        $sql = "SELECT * FROM mastercostdept";

        $list = $this
            ->query($sql)
            ->result();

        if(!@count($list)){
            return array();
        }

        $hash = array();
        foreach($list as $l){
            $hash[$l->id_cost_dept] = $l->nm_cost_dept;
        }

        return $hash;
    }

    public function get_cost_subdept_list_hash()
    {
        $sql = "SELECT * FROM mastercostsubdept";

        $list = $this
            ->query($sql)
            ->result();

        if(!@count($list)){
            return array();
        }

        $hash = array();
        foreach($list as $l){
            $hash[$l->id_cost_sub_dept] = $l->nm_cost_sub_dept;
        }

        return $hash;
    }

    public function get_coa_list($key='')
    {
        $sql = "
            SELECT 
                mc.id_coa
                ,mc.nm_coa
                ,mc.fg_mapping
            FROM mastercoa mc
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '1'
                {WHERE}
        ";

        $where = '';
        if($key){
            $where .= "AND mc.id_coa LIKE '$key%' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->result();
    }

    public function get_currency_list()
    {
        // TODO: get from master currency

        return array(
            array(
                'id_curr' => 'IDR',
                'nm_curr' => 'IDR',
            ),
            array(
                'id_curr' => 'EUR',
                'nm_curr' => 'EUR',
            ),
            array(
                'id_curr' => 'JPY',
                'nm_curr' => 'JPY',
            ),
            array(
                'id_curr' => 'USD',
                'nm_curr' => 'USD',
            )
        );
    }

    public function get_cost_center_list()
    {
        $sql = "
            SELECT 
                mc.id_costcenter
                ,mc.nm_costcenter
                ,mcc.id_cost_category
                ,mcc.nm_cost_category
                ,mcd.id_cost_dept
                ,mcd.nm_cost_dept
                ,mcsd.id_cost_sub_dept
                ,mcsd.nm_cost_sub_dept
            FROM
                mastercostcenter mc
                INNER JOIN mastercostcategory mcc ON mc.id_cost_category = mcc.id_cost_category
                INNER JOIN mastercostdept mcd ON mc.id_cost_dept = mcd.id_cost_dept
                INNER JOIN mastercostsubdept mcsd ON mc.id_cost_sub_dept = mcsd.id_cost_sub_dept
        ";

        return $this
            ->query($sql)
            ->result();
    }

    public function get_ws_list($key='')
    {
        $sql = "
            SELECT 
                ac.kpno nm_ws
            FROM
                act_costing ac
            WHERE 1=1
                {WHERE}
        ";

        $where = '';
        if($key){
            $where .= "AND ac.kpno = '$key' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->result();
    }
	
    public function get_pph()
    {
        $sql = "
		SELECT idtax,category_tax,kriteria,percentage FROM mtax WHERE category_tax = 'PPH' 
        ";
        return $this
            ->query($sql)
            ->result();
    }	
	

}

if(ISSET($_GET['id'])){
	$pecah = explode("-",$_GET['id']);
	if($pecah[1] == "PK"){
		$prop_pph = 1;
	}else{
		$prop_pph = 0;
	}
	
	
}else{
	$prop_pph = 0;
}
$Md = new ModelDetail($con_new);
$ch = new Coa_helper();

// Detail
$cost_category_hash = $Md->get_cost_category_list_hash();
$cost_dept_hash = $Md->get_cost_dept_list_hash();
$cost_subdept_hash = $Md->get_cost_subdept_list_hash();

$coa = array();
$_coa = $Md->get_coa_list();
if(count($_coa)){
    foreach($_coa as $_r){
        $coa[$_r->id_coa]['desc'] = $_r->nm_coa;
        $coa[$_r->id_coa]['map'] = $_r->fg_mapping;
    }
}

$currency = array();
$_currency = $Md->get_currency_list();
if(count($_currency)){
    foreach($_currency as $_r){
        $currency[$_r['id_curr']] = $_r['nm_curr'];
    }
}

$costcenter = array();
$_costcenter = $Md->get_cost_center_list();
if(count($_costcenter)){
    foreach($_costcenter as $_r){
        $costcenter[$_r->id_costcenter] = $_r->nm_costcenter;
    }
}

$ws = array();
$_ws = $Md->get_ws_list();
if(count($_ws)){
    foreach($_ws as $_r){
        $ws[$_r->nm_ws] = $_r->nm_ws;
    }
}


$pph = array();
$_pph= $Md->get_pph();
$x = 0;
 if(count($_pph)){
    foreach($_pph as $_r){
        $pph[$x]['id']  = $_r->idtax;
		$pph[$x]['isi'] = $_r->kriteria.'-'.$_r->percentage."%";
     $x++;
	}
} 

// Detail End
?>

<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<div class="box">
      <div class="box-header">
        <h3 class="box-title">Journal Entries</h3>
          <?php if(isset($row) and !$row->fg_post):?>
    <!--          <a href="../fin/?mod=jefd&amp;id=--><?//=$id?><!--" class="btn btn-primary btn-s"><i class="fa fa-plus"></i> Add Item</a>-->
<?php endif;?>
<!--          <a href="../fin/?mod=jefdel&amp;id=--><?//=$id?><!--" class="btn btn-danger btn-s"><i class="fa fa-trash"></i> Multi Delete</a>-->
</div>
<div class="box-body loading__" >
	<center><img src="img/25.gif"></center>
</div>
<div class="box-body table__" style="display:none">
    <table id="tableDetail" class="display responsive" style="width:100%;font-size:12px;">
        <thead>
        <tr>
            <th>No</th>
            <th>ID Chart of Account</th>
            <th>Nama Chart of Account</th>
            <th>Mata Uang</th>
            <th>Debit</th>
            <th>Kredit</th>
			<?=$prop_pph == "1" ? "<th style='text-align:center'>Pph</th>":" " ?>
            <th>No WS/Cost Center</th>
            <th>Deskripsi</th>
            <th width='14%'>Action</th>
        </tr>
        </thead>
        <?php if(@count($list)):?>
            <tbody>
            <?php $no=1;?>
            <?php foreach($list as $l):?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$ch->format_coa($l->id_coa)?></td>
                    <td><?=$l->nm_coa?></td>
                    <td><?=$l->curr?></td>
                    <td class="text-right"><?=number_format((float)$l->debit, 2, '.', ',');?></td>
                    <td class="text-right"><?=number_format((float)$l->credit, 2, '.', ',');?></td>
					<?php if($prop_pph == "1"){ ?>
					<td style="text-align:center">
					<?php if(($l->credit > 0) && ($l->id_coa !='15207') && ($l->is_retur_bh !='Y') ) {
						if(ISSET($l->id_bpb) || (!empty($l->id_bpb)) ){
							$id_mypph = $l->id_bpb;
						}else{
							$id_mypph = $l->row_id;
						}

					?>
					<select onchange="get_pph(this)" disabled name="row_<?=$id_mypph ?>" id="row_<?=$id_mypph ?>" class="pph" style="width:150px"> 
								<option selected="selected" value="0" >--Pilih Pph--</option>
                                <?php foreach($pph as $_p):?>
									<option value="<?=$_p['id']?>"><?=$_p['isi']?></option>
                                <?php endforeach;?>
					</select>
					<?php 
					} 
					else{
						echo "--";
						}
					?>
					</td>
					<?php } ?>					
                    <td><?=$l->nm_ws ? : $l->nm_costcenter?></td>
                    <td><?=$l->description?></td>
                    <td>
                        <?php if(isset($row) and !$row->fg_post):?>
                            <a class="btn btn-primary btn-s btn_edit validasi_proses" onclick="getDetailsInc('<?php echo $id ?>','<?php echo $l->row_id ?>')" href="#" data-toggle="tooltip" title="" data-item-id="<?=$id?>" data-item-row-id="<?=$l->row_id?>" data-original-title="Ubah"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger btn-s btn_delete validasi_proses" href="?mod=jefdcashbank&amp;id=<?=$id?>&amp;rid=<?=$l->row_id?>&amp;mode=del" data-toggle="tooltip" title="" data-item-id="<?=$id?>" data-item-row-id="<?=$l->row_id?>"  data-original-title="Hapus">
                                <i class="fa fa-trash-o"></i></a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        <?php endif;?>
        <tfoot><?php 
		if($prop_pph == "1"){ ?>
        <tr>
            <th colspan="4">&nbsp;</th>
			 <th class="text-left"><?=number_format((float)$t_debit, 2, '.', ',');?></th>
            <th class="text-right"><?=number_format((float)$t_credit, 2, '.', ',');?></th>
            <th colspan="4">&nbsp;</th>
        </tr>			
		<?php } else{
			
		?>
        <tr>
            <th colspan="4">&nbsp;</th>
            <th class="text-right"><?=number_format((float)$t_debit, 2, '.', ',');?></th>
            <th class="text-right"><?=number_format((float)$t_credit, 2, '.', ',');?></th>
            <th colspan="3">&nbsp;</th>
        </tr>
		<?php } ?>		
        </tfoot>
    </table>
</div>
</div>

<form id="form_detail" method='post' name='form' enctype='multipart/form-data' action=""  >
    <div class='box'>
        <div class="box-header">
            <h3 class="box-title">Tambah/Edit Entry</h3>
        </div>
        <div class='box-body'>
            <div class='row'>

                <div class="col-md-4">
                    <div class='form-group'>
                        <label>Cost Center</label>
                        <div class="input-group">
                            <select type='text' disabled id='id_costcenter' class='form-control select2 id_costcenter' name='id_costcenter'>
                                <option value="" selected>Pilih Cost Center</option>
                                <?php foreach($costcenter as $_id=>$_v):?>
                                    <option style="display:none !important" value="<?=$_id?>" ><?=$_id.' - '.$_v?></option>
                                <?php endforeach;?>
                            </select>
                            <a onclick="lookup_group()" style="cursor: pointer" class="input-group-addon" >...</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class='form-group'>
                        <label>Nomor Akun*</label>
                        <select type='text'  id='id_coa' class='form-control select2 id_coa' name='id_coa' onchange="getCurrencyDetail(this.value)" >
                            <option value="" disabled selected>Pilih Akun</option>
                            <?php//  foreach($coa as $_id=>$_v):?>
                              <!--  <option data-map="<?// =  $_v['map']?>" value="<?//= $_id?>" ><?//=  $_id.' - '.$_v['desc']?></option> -->
                            <?php// endforeach;?>
                        </select>
                    </div>
                </div>				
                <div class="col-md-4">
                    <div class='form-group'>
                        <label>WS</label>
                        <div class="input-group">
                            <select type='text' id='nm_ws' class='form-control select2 nm_ws' name='nm_ws'>
                                <option value="" selected>Pilih WS</option>
                                <?php foreach($ws as $_id=>$_v):?>
                                    <option value="<?=$_id?>"  ><?=$_id.' - '.$_v?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class='col-md-2'>
                    <div class='form-group'>
                        <label>Currency</label>
                        <select type='text' id='curr' class='form-control select2' name='curr' onchange="getKonversi(this.value);">
                        <option value="" disabled selected>Pilih Currency</option>
                            <?php foreach($currency as $_id=>$_v):?>
                                <option value="<?=$_id?>"  ><?=$_v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Debit</label>
                        <input type='text' id='debit' class='form-control' name='debit' onclick="getDefaultNumber(this)"  onchange="getDefaultNumber(this);"
                               placeholder='Masukkan Jumlah Debit' value=''>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class='form-group'>
                        <label>Kredit</label>
                        <input type='text' id='credit' class='form-control' onchange="getDefaultNumber(this)"  onclick="getDefaultNumber(this)" name='credit'
                               placeholder='Masukkan Jumlah Kredit' value=''>
                    </div>
                </div>
				
				
				
                <div class="col-md-4">
                    <div class='form-group'>
                        <label>Deskripsi*</label>
                        <input type='text' id='description' class='form-control' name='description'
                               placeholder='Masukkan Deskripsi' value=''>
                    </div>
                </div>
			
            </div>
            <div class="row">
                <div class='col-md-2'>
				<br/>
                    <div class='form-group validasi_proses'>
					
					                        <input type='hidden' name='mode' id="mode" value='save'>
                        <input type='hidden' name='row_id' id="row_id" value=''>

					
                                  <button type='button' name='clear' class='btn btn-danger mySimpan' onclick="clear_detail_form()">Batal</button>
                        <a href="#"  onclick="submitMy()" class='btn btn-primary mySimpan'>Simpan</a>
                    </div>
                </div>
                <div class='col-md-3 konversi' >
                    <div class='form-group'>
                        <label>Konversi Debit</label>
                        <input type='text' id='konversidebit' class='form-control' readonly name='konversidebit'
                               placeholder='Masukkan Jumlah Debit' value=''>
                    </div>
                </div>
                <div class="col-md-3 konversi" >
                    <div class='form-group '>
                        <label>Konversi Credit</label>
                        <input type='text' id='konversicredit' readonly class='form-control' name='konversicredit'
                               placeholder='Masukkan Jumlah Kredit' value=''>
                    </div>
                </div>
                <div class="col-md-2 konversi" >
                    <div class='form-group '>
                        <label>Rate</label>
                        <input type='text' id='rate' class='form-control' readonly onclick="handlekeyUpRate(this)" name='rate'
                               placeholder='Masukkan Rate' value=''>
                    </div>
                </div>					

            </div>			

        </div>
    </div>
</form>

<div class="modal fade" id="modalGroup"  role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cost Center Lookup</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class='form-group'>
                            <label>Kategori Biaya</label><br>
                            <select class='form-control select2 id_cost_category' id="category_biaya" name='id_cost_category'  >
                                <option value="">Pilih Kategori Biaya</option>
                                <?php foreach($cost_category_hash as $k=>$v):?>
                                    <option value="<?=$k?>" <?=(@$row->id_cost_category==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
					
                    </div>
                    <div class="col-md-4" style="display:none">
                        <div class='form-group'>
                            <label>Department</label><br>
                            <select class='form-control select2 id_cost_dept' id="department" onchange="getAkunCoaInc(this.value)" name='id_cost_dept'  >
                                <option value="">Pilih Departemen</option>
                                <?php foreach($cost_dept_hash as $k=>$v):?>
                                    <option value="<?=$k?>" <?=(@$row->id_cost_dept==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class='form-group'>
                            <label>Sub Departmen</label>
                            <select onchange="getAkunCoaIncBySubDepartment(this.value)" class='form-control select2 id_cost_sub_dept' id="sub_department" name='id_cost_sub_dept'  >
                                <option value="">Pilih Sub-Departemen</option>
                                <?php foreach($cost_subdept_hash as $k=>$v):?>
                                    <option value="<?=$k?>" <?=(@$row->id_cost_sub_dept==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
					<div class="col-md-4">
					                        <div class='form-group'>
                            <label></label><br>

						<a class="btn btn-primary" onclick="resetDepartmentInc()">Reset Department</a>
                        </div>		
					</div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
                            <thead>
                            <tr>
                                <th>ID Cost Center</th>
                                <th>Nama Cost Center</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <?php if(@count($costcenter)):?>
                                <tbody>
                                <?php foreach($costcenter as $k=>$l):?>
                                    <tr>
                                        <td><?=$k?></td>
                                        <td><?=$l?></td>
                                        <td><a  data-id-cost-center="<?=$k?>" class="btn btn-primary btnChooseCostCenter">Pilih</a></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            <?php endif;?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" onclick="">Accept</a>
            </div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
	    //reload_detail();
	
	
});
	coba = "Coba Saja";
    var costCenterFilter = '';
    var cost_subdept_hash = <?=json_encode($cost_subdept_hash);?>;
    var coaMap = '';
    var tableDetail;

    var t_debit = <?=$t_debit?:'0'?>;
    var t_credit = <?=$t_credit?:'0'?>;

    function validasi_balance(){
        if(t_debit != t_credit){
            alert('Error: Debit dan Kredit harus balance');
            return false;
        }
        if(t_debit == 0){
            alert('Error: Debit dan Kredit tidak boleh kosong');
            return false;
        }
        return true;
    }
    function lookup_group(){
        $('#modalGroup').modal('show');
        $("#modalGroup .select2").select2({
            dropdownParent: $('#modalGroup'),
            dropdownAutoWidth: true,
            width: '100%'
        });
    }
    function toggle_costcenter(){
        if(coaMap==1){
            $('#id_costcenter ').val('').trigger('change.select2');
            $('#id_costcenter').prop('disabled', true);
        }else{
            $('#id_costcenter').prop('disabled', false);
        }
    }

    function validasi_detail() {
//        $('#debit').unmask();
//        $('#credit').unmask();

//        var id_coa = document.form.id_coa.value;
        var id_coa = $('#id_coa').val();
//        var curr = document.form.curr.value;
        var curr = $('#curr').val();
//        var id_costcenter = document.form.id_costcenter.value;
        var id_costcenter = $('#id_costcenter').val();
//        var nm_ws = document.form.nm_ws.value;
        var nm_ws = $('#nm_ws').val();
//        var debit = document.form.debit.value;
        var debit = $('#debit').val();
//        var credit = document.form.credit.value;
        var credit = $('#credit').val();
//        var description = document.form.description.value;
        var description = $('#description').val();
        if (!id_coa) {
            alert('Error: Nomor Akun tidak boleh kosong');
//            document.form.id_coa.focus();
            $('#id_coa').focus();
            return false;
        } else if (coaMap == 2 && id_costcenter == '' && nm_ws == '') {
            alert('Error: Cost Center & WS tidak boleh kosong untuk COA yang berhubungan dengan Profit and Loss');
//            document.form.id_costcenter.focus();
            $('#id_costcenter').focus();
            return false;
        } else if (curr == '') {
            alert('Error: Currency tidak boleh kosong');
//            document.form.curr.focus();
            $('#curr').focus();
            return false;
        } else if (debit == '' && credit == '') {
            alert('Error: Debit & Kredit tidak boleh kosong');
//            document.form.debit.focus();
            $('#debit').focus();
            return false;
        } else if (isNaN(debit)) {
            alert('Error: Debit harus berupa angka');
//            document.form.debit.focus();
            $('#debit').focus();
            return false;
        }else if (!description) {
            alert('Error: Description Tidak Boleh Kosong');
//            document.form.debit.focus();
            $('#description').focus();
           return false;
        }/*else if (credit == '') {
         alert('Error: Kredit tidak boleh kosong');
         document.form.credit.focus();
         valid = false;
         }*/ else if (isNaN(credit)) {
            alert('Error: Kredit harus berupa angka');
//            document.form.credit.focus();
            $('#credit').focus();
           return false;
        }else valid = true;

        if(!valid) {
//            $('#debit').mask("000.000.000.000.000", {reverse: true});
//            $('#credit').mask("000.000.000.000.000", {reverse: true});
        }

        return valid;
    }

    function submit_detail()
    {
        var id_coa = $('#id_coa').val();
        var curr = $('#curr').val();
        var id_costcenter = $('#id_costcenter').val();
        var nm_ws = $('#nm_ws').val();
        var debit = $('#debit').val();
        var credit = $('#credit').val();
        var description = $('#description').val();
        var mode = $('#mode').val();
        var row_id = $('#row_id').val();
		var rate = $('#rate').val();
		
		
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=journal_item&id=<?=$id?>&row_id="+row_id+"&mode="+mode,
            data: "submit=save&id_coa=" +id_coa+"&curr="+curr
            +"&id_costcenter="+id_costcenter+"&nm_ws="+nm_ws+"&debit="+debit
            +"&credit="+credit+"&description="+description+"&rate="+rate+"&mode="+mode
            ,
            dataType: "json",
            async: false
        }).responseText;

//        request = JSON.parse(request);
//        console.log(request);

        reload_detail();
        clear_detail_form();

        return false;
    }

    function delete_detail(id, row_id)
    {
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=journal_item&id=<?=$id?>&row_id="+row_id+"mode=del",
            data: "mode=del",
            dataType: "json",
            async: false
        }).responseText;

//        request = JSON.parse(request);
        reload_detail();
    }

    function edit_detail(id, row_id)
    {
	
		
		event.preventDefault();
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_journal_item&id=<?=$id?>&row_id="+row_id,
            data: "",
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);
        console.log(request);

        var d = request.data;
		dmpid = d.id_coa
       
        $('#id_costcenter').val(d.id_costcenter).trigger('change');
        $('#nm_ws').val(d.nm_ws).trigger('change');
        $('#curr').val(d.curr).trigger('change');
        $('#debit').val(d.debit);
        $('#credit').val(d.credit);
        $('#description').val(d.description);
        $('#row_id').val(d.row_id);
        $('#mode').val('update');
		
				setTimeout(function(){ 				
					$('#id_coa').val(d.id_coa).trigger('change');
					$('#debit').trigger('click');
					$('#credit').trigger('click');		
				}, 3000);	
        return false;
    }

    function clear_detail_form(){
        $('#id_coa').val('').trigger('change');
        $('#id_costcenter').val('').trigger('change');
        $('#nm_ws').val('').trigger('change');
        $('#curr').val('').trigger('change');
        $('#debit').val('');
        $('#credit').val('');
        $('#description').val('');
        $('#mode').val('save');
    }

    function setCostCenterFilter(){
        var id_cost_category = $('.id_cost_category').val()+"-";
		 var id_cost_category_2 = $('.id_cost_category').val();
        var id_cost_dept = $('.id_cost_dept').val();
        var id_cost_sub_dept = "-"+$('.id_cost_sub_dept').val();
		console.log(id_cost_category);
		if(!id_cost_category){
			id_cost_category = "";
		}
		if(!id_cost_category_2){
			id_cost_category_2 = "";
		}
		if(!id_cost_sub_dept){
			id_cost_sub_dept = "";
			
		}



		
		if(id_cost_category == '-'  || id_cost_category == '' ){
			id_cost_category = "";
		}if(id_cost_category_2 == '' || id_cost_category_2 == ''){
			id_cost_category_2 = "";
			
		}if(id_cost_sub_dept == "-Pilih Sub-Departemen" || id_cost_sub_dept == "" ){
			id_cost_sub_dept = "";
		}
		
		
		costCenterFilter = id_cost_category+id_cost_category_2+id_cost_sub_dept;
		

  /*      if(id_cost_category && id_cost_dept && id_cost_sub_dept){
            costCenterFilter = id_cost_category+'-'+id_cost_dept+'-'+id_cost_sub_dept;
        }else if(id_cost_category && id_cost_dept){
            costCenterFilter = id_cost_category+'-'+id_cost_dept+'-';
        }else if(id_cost_dept && id_cost_sub_dept){
            costCenterFilter = '-'+id_cost_dept+'-'+id_cost_sub_dept;
        }else if(id_cost_category){
            costCenterFilter = id_cost_category+'-'+id_cost_category;
        }else if(id_cost_dept){
            costCenterFilter = '-'+id_cost_dept+'-';
        }else if(id_cost_sub_dept){
            costCenterFilter = '-'+id_cost_sub_dept;
        }else{
			 costCenterFilter = "";
			
		}
		populateCostSubdept();
		*/
    }

    function applyCostCenterFilter(){
        $('#examplefix4').DataTable().search(costCenterFilter).draw();
    }

    function populateCostSubdept(){
	
        var id_cost_dept = $('.id_cost_category').val();
        var data = [{
            'id': '',
            'text': 'Pilih Sub-Departemen',
            'selected': true,
            'disabled': false,
        }];
        for(var i in cost_subdept_hash){
            if(!id_cost_dept || id_cost_dept == i.substr(0,2)) {
                data.push({
                    'id': i,
                    'text': cost_subdept_hash[i]
                });
            }
        }

        $('.id_cost_sub_dept').select2('destroy').empty().select2({data: data});
    }

    function formatNumber (num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
    }

    function reload_detail(){
		
        var details = [];
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_journal_items&id=<?=$id?>",
            data: "",
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        if(!request.status){
            return;
        }

        details = request.data.rows;
        tableDetail.clear().draw();
        var no = 1;
        for(var i in details){
            // Add rows
            var row = [
                no++,
                details[i].id_coa,
                details[i].nm_coa,
                details[i].curr,
                details[i].debit,
                details[i].credit,
				'',
                details[i].nm_ws ? details[i].nm_ws : details[i].nm_costcenter,
                details[i].description,
                <?php if(isset($row) and !$row->fg_post):?>
                '<a class="btn btn-primary btn-s btn_edit" href="?mod=jefd&amp;id=<?=$id?>&amp;rid='+details[i].row_id+'" data-toggle="tooltip" title="" data-item-id="<?=$id?>" data-item-row-id="'+details[i].row_id+'" data-original-title="Ubah"><i class="fa fa-pencil"></i></a> '
                +'<a class="btn btn-danger btn-s btn_delete" href="?mod=jefd&amp;id=<?=$id?>&amp;rid='+details[i].row_id+'&amp;mode=del" data-toggle="tooltip" title="" data-item-id="<?=$id?>" data-item-row-id="'+details[i].row_id+'" data-original-title="Hapus"><i class="fa fa-trash-o"></i></a>'
                <?php else:?>
                ''
                <?php endif;?>
            ];

            var rowNode = tableDetail.row.add(row).node();
            $( rowNode ).find('td').eq(4).addClass('text-right');
            $( rowNode ).find('td').eq(5).addClass('text-right');
        }

        tableDetail.columns.adjust().draw();
        $(tableDetail.column(4).footer()).html(formatNumber(request.data.total_debit));
        $(tableDetail.column(5).footer()).html(formatNumber(request.data.total_credit));

        t_debit = request.data.total_debit;
        t_credit = request.data.total_credit;
	
    }

	

function submitMy(){
		$("#id_costcenter").attr("disabled",false);
		var credits = getOriginal($("#credit").val());
		var debits  = getOriginal($("#debit").val());
		var Kcredits= getOriginal($("#konversicredit").val());
		var Kdebits = getOriginal($("#konversidebit").val());
		var Krate   = getOriginal($("#rate").val());
		
		//console.log(credits);
		//console.log(debits);
		//return false;
		
		$("#credit").val(credits);
		$("#debit").val(debits);
		$("#konversicredit").val(Kcredits);
		$("#konversidebit").val(Kdebits);
		$("#rate").val(Krate);
		
		console.log(credits);
		console.log(debits);
		console.log(Kcredits);
		console.log(Kdebits);
		console.log(Krate);

	event.preventDefault();
			$("#id_costcenter").attr("disabled",true);
            if(!validasi_detail()){
				
                return false;
            }
            submit_detail();
			$("#konversidebit").val("");
			$("#konversicredit").val("");
		location.reload();
	return false;
    
}
    $(document).ready(function(){
//        $('#debit').mask("000.000.000.000.000", {reverse: true});
//        $('#credit').mask("000.000.000.000.000", {reverse: true});

setTimeout(function(){ 	
$(".table__").css("display","");
$(".loading__").css("display","none");
        tableDetail = $('#tableDetail').DataTable
        ({scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                },
        });

        $('#modalGroup').on('shown.bs.modal', function (e) {
            // Clear filter and redraw datatable
            //populateCostSubdept();
           // $('#examplefix4').DataTable().search('').draw();
		   searchDefault = $("#sub_department").val();
		   $('#examplefix4').DataTable().search(searchDefault).draw();
        });
        $('.id_coa').on('select2:select', function (e) {
            var map = $(e.params.data.element).data('map');
            coaMap = map;
            toggle_costcenter();
        });
        $('.id_cost_category').on('select2:select', function (e) {
			populateCostSubdept();
            setCostCenterFilter();
            applyCostCenterFilter();
        });
        $('.id_cost_dept').on('select2:select', function (e) {
            populateCostSubdept();
            setCostCenterFilter();
            applyCostCenterFilter();
        });
        $('.id_cost_sub_dept').on('select2:select', function (e) {
            setCostCenterFilter();
            applyCostCenterFilter();
        });

/*         $('.btnChooseCostCenter').click(function(){
            var id_cost_center = $(this).data('id-cost-center');
            $('.id_costcenter').val(id_cost_center);
            $('.id_costcenter').trigger('change');
            $('#modalGroup').modal('hide');
        }); */

        $(document).on('click', '.btnChooseCostCenter', function(){
            var id_cost_center = $(this).data('id-cost-center');
            $('.id_costcenter').val(id_cost_center);
            $('.id_costcenter').trigger('change');
            $('#modalGroup').modal('hide');
        });

        $(document).on('click', '.btn_delete', function(){
            if(confirm('Apakah anda yakin akan menghapus ?')){
                var id = $(this).data('item-id');
                var row_id = $(this).data('item-row-id');
                delete_detail(id, row_id);
            }

            return false;
        });

        $(document).on('click', '.btn_edit', function(){
            var id = $(this).data('item-id');
            var row_id = $(this).data('item-row-id');
            edit_detail(id, row_id);

            return false;
        });
        $( "button[name='posting']" ).click(function(){
            return validasi_balance();
        });
},5000);
    });
</script>