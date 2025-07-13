<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("act_costing","userpassword","username='$user'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

require_once "../forms/journal_interface.php";


class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
$A = new Assets();
class Model{
    private $conn;
    private $result;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Query helper
     * @param $sql
     * @return $this
     */
    public function query($sql)
    {
        $this->result = mysqli_query($this->conn, $sql);
        if(!$this->result){
            $err = "
            <h3>Database Query Error:</h3>
            <p>".mysqli_error($this->conn)."</p>
            <p>$sql</p>
            ";
            #show_error($err);
        }
        return $this;
    }

    /**
     * Fetch multiple rows helper
     * @return array
     */
    public function result()
    {
        $rows = array();
        if(!$this->result){
            return $rows;
        }
        while($row = mysqli_fetch_object($this->result)){
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Fetch single row helper
     * @return null|object
     */
    public function row()
    {
        return mysqli_fetch_object($this->result);
    }

    public function get_master_company()
    {
        return $this->query("
            SELECT * FROM mastercompany;
        ")->row();
    }

    public function get_journal($id)
    {
        return $this->query("
            SELECT * FROM
            fin_journal_h jh
            WHERE id_journal = '$id'';
        ")->row();
    }

    public function get_journal_item($id, $row_id)
    {
        return $this->query("
            SELECT * FROM
            fin_journal_d jd
            WHERE id_journal = '$id'
              AND row_id = '$row_id';
        ")->row();
    }

    public function delete_journal_item($id, $row_id)
    {
        $sql = "
            DELETE FROM
            fin_journal_d
            WHERE id_journal = '$id'
              AND row_id = '$row_id'
        ";

        return $this->query($sql);
    }

    public function get_next_item_id($id)
    {
        $row = $this->query("
            SELECT IFNULL(MAX(row_id),0)+1 next_id FROM
            fin_journal_d jd
            WHERE id_journal = '$id';
        ")->row();

        return $row->next_id;
    }

    public function save($d)
    {
        $sql = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
              ,id_costcenter, nm_costcenter, id_cost_category, nm_cost_category
              ,id_cost_dept, nm_cost_dept, id_cost_sub_dept, nm_cost_sub_dept
              ,nm_ws
              ,description, dateadd, useradd)
            VALUES 
              ('{$d['id_journal']}', '{$d['row_id']}', '{$d['id_coa']}', '{$d['nm_coa']}' ,'{$d['curr']}', '{$d['debit']}', '{$d['credit']}'
              ,'{$d['id_costcenter']}', '{$d['nm_costcenter']}', '{$d['id_cost_category']}', '{$d['nm_cost_category']}'
              ,'{$d['id_cost_dept']}','{$d['nm_cost_dept']}', '{$d['id_cost_sub_dept']}', '{$d['nm_cost_sub_dept']}'
              ,'{$d['nm_ws']}'
              ,'{$d['description']}','{$d['dateadd']}', '{$d['useradd']}'
              )
            ;
        ";

        $this->query($sql);

        return $this->conn->insert_id;
    }

    public function update($id, $row_id, $data)
    {
        $sql = "
            UPDATE fin_journal_d SET 
              id_coa = '{$data['id_coa']}',
              nm_coa = '{$data['nm_coa']}',
              curr = '{$data['curr']}',
              debit = '{$data['debit']}',
              credit = '{$data['credit']}',
              id_costcenter = '{$data['id_costcenter']}',
              nm_costcenter = '{$data['nm_costcenter']}',
              id_cost_category = '{$data['id_cost_category']}',
              nm_cost_category = '{$data['nm_cost_category']}',
              id_cost_dept = '{$data['id_cost_dept']}',
              nm_cost_dept = '{$data['nm_cost_dept']}',
              id_cost_sub_dept = '{$data['id_cost_sub_dept']}',
              nm_cost_sub_dept = '{$data['nm_cost_sub_dept']}',
              nm_ws = '{$data['nm_ws']}',
              description = '{$data['description']}',
              dateedit = '{$data['dateedit']}',
              useredit = '{$data['useredit']}'
            WHERE id_journal = '$id'
              AND row_id = '$row_id';
        ";

        return $this->query($sql);
    }

    public function get_cost_center_list($key='')
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
            WHERE 1=1
                {WHERE}
        ";

        $where = '';
        if($key){
            $where .= "AND mc.id_costcenter = '$key' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

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
            ORDER By mc.id_coa
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


}

// CONTROLLER BEGIN

$M = new Model($con_new);

$id = isset($_GET['id']) ? $_GET['id'] : '';
$row_id = isset($_GET['rid']) ? $_GET['rid'] : '';
$mode = isset($_GET['mode']) ? $_GET['mode'] : '';

if(isset($_POST['submit'])){
    $data = array(
        'id_journal' => $id,
        'row_id' => $M->get_next_item_id($id),
        'id_coa' => $_POST['id_coa'],
        'curr' => $_POST['curr'],
        'id_costcenter' => isset($_POST['id_costcenter']) ? $_POST['id_costcenter'] : '',
        'nm_ws' => isset($_POST['nm_ws']) ? $_POST['nm_ws'] : '',
        'debit' => $_POST['debit'] ? : 0,
        'credit' => $_POST['credit'] ? : 0,
        'description' => $_POST['description']
    );
    if($_POST['id_coa']) {
        $coa = $M->get_coa_list($_POST['id_coa']);
        $data['nm_coa'] = $coa[0]->nm_coa;
    }
    if(isset($_POST['id_costcenter']) and $_POST['id_costcenter']) {
        $costcenter = $M->get_cost_center_list($_POST['id_costcenter']);
        $data['nm_costcenter'] = $costcenter[0]->nm_costcenter;
        $data['id_cost_category'] = $costcenter[0]->id_cost_category;
        $data['nm_cost_category'] = $costcenter[0]->nm_cost_category;
        $data['id_cost_dept'] = $costcenter[0]->id_cost_dept;
        $data['nm_cost_dept'] = $costcenter[0]->nm_cost_dept;
        $data['id_cost_sub_dept'] = $costcenter[0]->id_cost_sub_dept;
        $data['nm_cost_sub_dept'] = $costcenter[0]->nm_cost_sub_dept;
    }else{
        $data['nm_costcenter'] = $data['id_cost_category'] = $data['nm_cost_category'] = $data['id_cost_dept']
            = $data['nm_cost_dept'] = $data['id_cost_sub_dept'] = $data['nm_cost_sub_dept'] = '';
    }

    if($_POST['mode'] == 'save')
    {
        $data['dateadd'] = date('Y-m-d H:i:s', time());
        $data['useradd'] = $user;
        $insert_id = $M->save($data);

        echo "<script>window.location.href='?mod=jefh&id=$id';</script>";exit();
    }elseif($_POST['mode'] == 'update'){
        $data['dateedit'] = date('Y-m-d H:i:s', time());
        $data['useredit'] = $user;
        $status = $M->update($id, $row_id, $data);

        echo "<script>window.location.href='?mod=jefh&id=$id';</script>";exit();
    }

    if($status){
        $id = '';
    }
}else{
    if($mode=='del'){
        $M->delete_journal_item($id, $row_id);
        echo "<script>window.location.href='?mod=jefh&id=$id';</script>";exit();
    }
    if($row_id){
        $row = $M->get_journal_item($id, $row_id);
    }
}

$cost_category_hash = $M->get_cost_category_list_hash();
$cost_dept_hash = $M->get_cost_dept_list_hash();
$cost_subdept_hash = $M->get_cost_subdept_list_hash();

$coa = array();
$_coa = $M->get_coa_list();
if(count($_coa)){
    foreach($_coa as $_r){
        $coa[$_r->id_coa]['desc'] = $_r->nm_coa;
        $coa[$_r->id_coa]['map'] = $_r->fg_mapping;
    }
}

$currency = array();
$_currency = $M->get_currency_list();
if(count($_currency)){
    foreach($_currency as $_r){
        $currency[$_r['id_curr']] = $_r['nm_curr'];
    }
}

$costcenter = array();
$_costcenter = $M->get_cost_center_list();
if(count($_costcenter)){
    foreach($_costcenter as $_r){
        $costcenter[$_r->id_costcenter] = $_r->nm_costcenter;
    }
}

$ws = array();
$_ws = $M->get_ws_list();
if(count($_ws)){
    foreach($_ws as $_r){
        $ws[$_r->nm_ws] = $_r->nm_ws;
    }
}
// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>

<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()' >
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class="col-md-6">
                    <div class='form-group'>
                        <label>Nomor Akun</label>
                        <select type='text' id='id_coa' class='form-control select2 id_coa' name='id_coa'>
                            <option value="" disabled selected>Pilih Akun</option>
                            <?php foreach($coa as $_id=>$_v):?>
                                <option data-map="<?=$_v['map']?>" value="<?=$_id?>" <?=(@$row->id_coa==$_id) ? 'selected':''?> ><?=$_id.' - '.$_v['desc']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class='form-group'>
                        <label>Cost Center</label>
                        <div class="input-group">
                            <select type='text' id='id_costcenter' class='form-control select2 id_costcenter' name='id_costcenter'>
                                <option value="" selected>Pilih Cost Center</option>
                                <?php foreach($costcenter as $_id=>$_v):?>
                                    <option value="<?=$_id?>" <?=(@$row->id_costcenter==$_id) ? 'selected':''?> ><?=$_id.' - '.$_v?></option>
                                <?php endforeach;?>
                            </select>
                            <a onclick="lookup_group()" style="cursor: pointer" class="input-group-addon" >...</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class='form-group'>
                        <label>WS</label>
                        <div class="input-group">
                            <select type='text' id='nm_ws' class='form-control select2 nm_ws' name='nm_ws'>
                                <option value="" selected>Pilih WS</option>
                                <?php foreach($ws as $_id=>$_v):?>
                                    <option value="<?=$_id?>" <?=(@$row->nm_ws==$_id) ? 'selected':''?> ><?=$_id.' - '.$_v?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Currency</label>
                        <select type='text' id='curr' class='form-control select2' name='curr'>
                            <option value="" disabled selected>Pilih Currency</option>
                            <?php foreach($currency as $_id=>$_v):?>
                                <option value="<?=$_id?>" <?=(@$row->curr==$_id) ? 'selected':''?> ><?=$_v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                 <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Debit</label>
                         <input type='number' id='debit' class='form-control' name='debit'
                                placeholder='Masukkan Jumlah Debit' value='<?=isset($row)?$row->debit:''?>'>
                     </div>
                 </div>
                <div class="col-md-3">
                     <div class='form-group'>
                         <label>Kredit</label>
                         <input type='number' id='credit' class='form-control' name='credit'
                                placeholder='Masukkan Jumlah Kredit' value='<?=isset($row)?$row->credit:''?>'>
                     </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class='form-group'>
                        <label>Deskripsi</label>
                        <input type='text' id='description' class='form-control' name='description'
                               placeholder='Masukkan Deskripsi' value='<?=isset($row)?$row->description:''?>'>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class='col-md-3'>
                    <div class="form-group">
                        <input type='hidden' name='mode' value='<?=$row_id ? 'update' : 'save';?>'>
                        <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
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
                        <select class='form-control select2 id_cost_category' name='id_cost_category'  >
                            <option value="">Pilih Kategori Biaya</option>
                            <?php foreach($cost_category_hash as $k=>$v):?>
                                <option value="<?=$k?>" <?=(@$row->id_cost_category==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class='form-group'>
                        <label>Departmen</label><br>
                        <select class='form-control select2 id_cost_dept' name='id_cost_dept'  >
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
                        <select class='form-control select2 id_cost_sub_dept' name='id_cost_sub_dept'  >
                            <option value="">Pilih Sub-Departemen</option>
                            <?php foreach($cost_subdept_hash as $k=>$v):?>
                                <option value="<?=$k?>" <?=(@$row->id_cost_sub_dept==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                            <?php endforeach;?>
                        </select>
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
    var costCenterFilter = '';
    var cost_subdept_hash = <?=json_encode($cost_subdept_hash);?>;
    var coaMap = '';

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
    function validasi() {
//        $('#debit').unmask();
//        $('#credit').unmask();

        var id_coa = document.form.id_coa.value;
        var curr = document.form.curr.value;
        var id_costcenter = document.form.id_costcenter.value;
        var nm_ws = document.form.nm_ws.value;
        var debit = document.form.debit.value;
        var credit = document.form.credit.value;
        var description = document.form.description.value;
        if (id_coa == '') {
            alert('Error: Nomor Akun tidak boleh kosong');
            document.form.id_coa.focus();
            valid = false;
        } else if (coaMap == 2 && id_costcenter == '' && nm_ws == '') {
            alert('Error: Cost Center & WS tidak boleh kosong untuk COA yang berhubungan dengan Profit and Loss');
            document.form.id_costcenter.focus();
            valid = false;
        } else if (curr == '') {
            alert('Error: Currency tidak boleh kosong');
            document.form.curr.focus();
            valid = false;
        } else if (debit == '' && credit == '') {
            alert('Error: Debit & Kredit tidak boleh kosong');
            document.form.debit.focus();
            valid = false;
        } else if (isNaN(debit)) {
            alert('Error: Debit harus berupa angka');
            document.form.debit.focus();
            valid = false;
        }/*else if (credit == '') {
            alert('Error: Kredit tidak boleh kosong');
            document.form.credit.focus();
            valid = false;
        }*/ else if (isNaN(credit)) {
            alert('Error: Kredit harus berupa angka');
            document.form.credit.focus();
            valid = false;
        }else valid = true;

        if(!valid) {
//            $('#debit').mask("000.000.000.000.000", {reverse: true});
//            $('#credit').mask("000.000.000.000.000", {reverse: true});
        }

        return valid;
    }

    function setCostCenterFilter(){
        var id_cost_category = $('.id_cost_category').val();
        var id_cost_dept = $('.id_cost_dept').val();
        var id_cost_sub_dept = $('.id_cost_sub_dept').val();

        if(id_cost_category && id_cost_dept && id_cost_sub_dept){
            costCenterFilter = id_cost_category+'-'+id_cost_dept+'-'+id_cost_sub_dept;
        }else if(id_cost_category && id_cost_dept){
            costCenterFilter = id_cost_category+'-'+id_cost_dept+'-';
        }else if(id_cost_dept && id_cost_sub_dept){
            costCenterFilter = '-'+id_cost_dept+'-'+id_cost_sub_dept;
        }else if(id_cost_category){
            costCenterFilter = id_cost_category+'-';
        }else if(id_cost_dept){
            costCenterFilter = '-'+id_cost_dept+'-';
        }else if(id_cost_sub_dept){
            costCenterFilter = '-'+id_cost_sub_dept;
        }
    }

    function applyCostCenterFilter(){
        $('#examplefix4').DataTable().search(costCenterFilter).draw();
    }

    function populateCostSubdept(){
        var id_cost_dept = $('.id_cost_dept').val();
        var data = [{
            'id': '',
            'text': 'Pilih Sub-Departemen',
            'selected': true,
            'disabled': true
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

    $(document).ready(function(){
//        $('#debit').mask("000.000.000.000.000", {reverse: true});
//        $('#credit').mask("000.000.000.000.000", {reverse: true});

        $('#modalGroup').on('shown.bs.modal', function (e) {
            // Clear filter and redraw datatable
            populateCostSubdept();
            $('#examplefix4').DataTable().search('').draw();
        });
        $('.id_coa').on('select2:select', function (e) {
            var map = $(e.params.data.element).data('map');
            coaMap = map;
            toggle_costcenter();
        });
        $('.id_cost_category').on('select2:select', function (e) {
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

        $('.btnChooseCostCenter').click(function(){
            var id_cost_center = $(this).data('id-cost-center');
            $('.id_costcenter').val(id_cost_center);
            $('.id_costcenter').trigger('change');
            $('#modalGroup').modal('hide');
        });

    });
</script>