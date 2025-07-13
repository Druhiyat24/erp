<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Cost_Cent","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>";exit(); }
// Session Checking - End

class Config{
    // Loss percentage in fraction
    public static $loss = 0; //3%
}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
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

    public function save($data)
    {
        $sql = "
            INSERT INTO mastercostcenter VALUES (
              '{$data['id_costcenter']}',
              '{$data['nm_costcenter']}',
              '{$data['id_cost_category']}',
              '{$data['id_cost_dept']}',
              '{$data['id_cost_sub_dept']}'
            );
        ";

        return $this->query($sql);
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE mastercostcenter SET 
              id_cost_sub_dept = '{$data['id_costcenter']}',
              nm_costcenter = '{$data['nm_costcenter']}',
              id_cost_category = '{$data['id_cost_category']}',
              id_cost_dept = '{$data['id_cost_dept']}',
              id_cost_sub_dept = '{$data['id_cost_sub_dept']}'
            WHERE id_costcenter = '$id';
        ";

        return $this->query($sql);
    }

    public function get_cost_center_list()
    {
        $sql = "
            SELECT 
                mcc.* 
                ,mcg.nm_cost_category
                ,mcd.nm_cost_dept
                ,mcs.nm_cost_sub_dept
            FROM 
                mastercostcenter mcc
                INNER JOIN mastercostcategory mcg ON mcc.id_cost_category = mcg.id_cost_category
                INNER JOIN mastercostdept mcd ON mcc.id_cost_dept = mcd.id_cost_dept
                INNER JOIN mastercostsubdept mcs ON mcc.id_cost_sub_dept = mcs.id_cost_sub_dept
        ";

        return $this
            ->query($sql)
            ->result();
    }

    public function get_cost_center($id)
    {
        $sql = "
            SELECT 
                mcc.* 
                ,mcg.nm_cost_category
                ,mcd.nm_cost_dept
                ,mcs.nm_cost_sub_dept
            FROM 
                mastercostcenter mcc
                INNER JOIN mastercostcategory mcg ON mcc.id_cost_category = mcg.id_cost_category
                INNER JOIN mastercostdept mcd ON mcc.id_cost_dept = mcd.id_cost_dept
                INNER JOIN mastercostsubdept mcs ON mcc.id_cost_sub_dept = mcs.id_cost_sub_dept
            WHERE
                mcc.id_costcenter = '$id'
        ";

        return $this
            ->query($sql)
            ->row();
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
$errMsg = '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit'])){
    $data = array(
        'id_costcenter' => $_POST['id_costcenter'],
        'nm_costcenter' => $_POST['nm_costcenter'],
        'id_cost_category' => $_POST['id_cost_category'],
        'id_cost_dept' => $_POST['id_cost_dept'],
        'id_cost_sub_dept' => $_POST['id_cost_sub_dept']
    );

    if($_POST['mode'] == 'save'){
        $existing = $M->get_cost_center($_POST['id_costcenter']);
        if($existing){
            echo "<script>alert('Error: ID Cost Center sudah terdaftar');</script>";
            $status = false;
            $row = json_decode(json_encode($data));
        }else{
            $status = $M->save($data);
        }
    }else{
        $existing = $M->get_cost_center($_POST['id_costcenter']);
        if($id != $_POST['id_costcenter'] and $existing){
            echo "<script>alert('Error: ID Cost Center sudah terdaftar');</script>";
            $status = false;
            $row = json_decode(json_encode($data));
        }else{
            $status = $M->update($id, $data);
        }
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=cost';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
    if($id){
        $row = $M->get_cost_center($id);
    }
}

$cost_category_hash = $M->get_cost_category_list_hash();
$cost_dept_hash = $M->get_cost_dept_list_hash();
$cost_subdept_hash = $M->get_cost_subdept_list_hash();

$list = $M->get_cost_center_list();
// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<div id="formCost" class='box <?=isset($row) ? '':'hidden'?>'>
    <div class='box-body'>
        <div class='row'>
            <form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>ID Cost Center</label>
                        <input type='text' id='id_costcenter' class='form-control' name='id_costcenter' maxlength="10"
                               placeholder='Masukkan ID Cost Center' value='<?=isset($row)?$row->id_costcenter:''?>'>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Nama Cost Center</label>
                        <input type='text' id='nm_costcenter' class='form-control' name='nm_costcenter'
                               placeholder='Masukkan Nama Cost Center' value='<?=isset($row)?$row->nm_costcenter:''?>'>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Kategori Biaya</label>
                        <select class='form-control select2' name='id_cost_category'  >
                            <option value="">Pilih Kategori Biaya</option>
                            <?php foreach($cost_category_hash as $k=>$v):?>
                                <option value="<?=$k?>" <?=(@$row->id_cost_category==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Departemen</label>
                        <select class='form-control select2 id_cost_dept' name='id_cost_dept'  >
                            <option value="">Pilih Departemen</option>
                            <?php foreach($cost_dept_hash as $k=>$v):?>
                                <option value="<?=$k?>" <?=(@$row->id_cost_dept==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Sub-Departemen</label>
                        <select class='form-control select2 id_cost_sub_dept' name='id_cost_sub_dept'  >
                            <option value="">Pilih Sub-Departemen</option>
                            <?php foreach($cost_subdept_hash as $k=>$v):?>
                                <option value="<?=$k?>" <?=(@$row->id_cost_sub_dept==$k) ? 'selected':''?> ><?=$k.' - '.$v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                        <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header">
        <h3 class="box-title">List Cost Center</h3>
        <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Cost Center Baru</a>
    </div>
    <div class="box-body">
        <table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
            <thead>
            <tr>
                <th>ID Cost Center</th>
                <th>Nama Cost Center</th>
                <th>Kategori Biaya</th>
                <th>Departemen</th>
                <th>Sub-Departemen</th>
                <th width='14%'>Action</th>
            </tr>
            </thead>
            <?php if(@count($list)):?>
                <tbody>
                <?php foreach($list as $l):?>
                    <tr>
                        <td><?=$l->id_costcenter?></td>
                        <td><?=$l->nm_costcenter?></td>
                        <td><?=$l->nm_cost_category?></td>
                        <td><?=$l->nm_cost_dept?></td>
                        <td><?=$l->nm_cost_sub_dept?></td>
                        <td>
                            <a class="btn btn-primary btn-s" href="?mod=cost&amp;id=<?=$l->id_costcenter?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            <?php endif;?>
        </table>
    </div>
</div>

<script>
    var cost_subdept_hash = <?=json_encode($cost_subdept_hash);?>;

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

    function show_form(){
        $('#formCost').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function validasi() {
        var id_costcenter = document.form.id_costcenter.value;
        var nm_costcenter = document.form.nm_costcenter.value;
        var id_cost_category = document.form.id_cost_category.value;
        var id_cost_dept = document.form.id_cost_dept.value;
        var id_cost_sub_dept = document.form.id_cost_sub_dept.value;

        if (id_costcenter == '') {
            alert('Error: ID Cost Center tidak boleh kosong');
            document.form.id_costcenter.focus();
            valid = false;
        } else if (nm_costcenter == '') {
            alert('Error: Nama Cost Center tidak boleh kosong');
            document.form.nm_costcenter.focus();
            valid = false;
        } else if (id_cost_category == '') {
            alert('Error: Kategori Biaya tidak boleh kosong');
            document.form.id_cost_category.focus();
            valid = false;
        } else if (id_cost_dept == '') {
            alert('Error: Departemen tidak boleh kosong');
            document.form.id_cost_dept.focus();
            valid = false;
        } else if (id_cost_sub_dept == "Pilih Sub-Departemen") {
            alert('Error: Sub-Departemen tidak boleh kosong');
            document.form.id_cost_sub_dept.focus();
            valid = false;
        } else valid = true;

        return valid;
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
        $('.id_cost_dept').on('select2:select', function (e) {
            populateCostSubdept();
            setCostCenterFilter();
            applyCostCenterFilter();
        });
    });
</script>