<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_COA","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

class Config{
    public static $coa_type = array(
            '0' => 'Total',
            '1' => 'Posting',
    );
    public static $coa_mapping = array(
            '0' => '-',
            '1' => 'Neraca',
            '2' => 'Laba Rugi',
    );

}

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
        if($this->conn->errno){
            $err = "
            <h3>Database Query Error:</h3>
            <p>".mysqli_error($this->conn)."</p>
            <p>$sql</p>
            ";
            exit($err);
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
        if(is_bool($this->result)){
            return array();
        }else {
            return mysqli_fetch_object($this->result);
        }
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
            INSERT INTO mastercoa 
              (id_coa, nm_coa, fg_posting, fg_mapping, fg_active, post_to, map_category)
            VALUES (
              '{$data['id_coa']}',
              '{$data['nm_coa']}',
              '{$data['fg_posting']}',
              '{$data['fg_mapping']}',
              '{$data['fg_active']}',
              '{$data['post_to']}',
              '{$data['map_category']}'
            );
        ";

        return $this->query($sql);
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE mastercoa SET 
              id_coa = '{$data['id_coa']}',
              nm_coa = '{$data['nm_coa']}',
              fg_posting = '{$data['fg_posting']}',
              fg_mapping = '{$data['fg_mapping']}',
              fg_active = '{$data['fg_active']}',
              post_to = '{$data['post_to']}',
              map_category = '{$data['map_category']}'
            WHERE id_coa = '$id';
        ";

        return $this->query($sql);
    }

    public function get_segment_list()
    {
        $sql = "
            SELECT * FROM
            (
                SELECT 
                id_segment_1, id_segment_2
                , nm_segment_1, nm_segment_2
                FROM
                (
                    SELECT 
                        id_segment id_segment_1
                        ,nm_segment nm_segment_1
                    FROM mastercoasegment
                    WHERE id_coaconfig = 1
                )s1 LEFT JOIN
                (
                    SELECT 
                        SUBSTRING(id_segment, 1, 1) parent_segment_1
                        ,SUBSTRING(id_segment, 2) id_segment_2
                        ,nm_segment nm_segment_2
                    FROM mastercoasegment
                    WHERE id_coaconfig = 2
                ) s2 ON s1.id_segment_1 = s2.parent_segment_1
            )X

        ";

        $result = $this->query($sql)->result();
        $hash = array();
        foreach($result as $r){
            $hash[$r->id_segment_1]['desc'] = $r->id_segment_1.' - '.$r->nm_segment_1;
            $hash[$r->id_segment_1]['rows'][] = array(
                'id' => $r->id_segment_2,
                'text' => $r->id_segment_2.' - '.$r->nm_segment_2
            );
        }

        return $hash;
    }

    public function get_coa_list()
    {
        $sql = "
            SELECT 
                mcoa.*
            FROM 
                mastercoa mcoa
        ";

        return $this
            ->query($sql)
            ->result();
    }

    public function get_coa_category()
    {
        $sql = "
            SELECT 
                mccat.*
            FROM 
                mastercoacategory mccat
              ORDER BY mccat.report_type, mccat.order
        ";

        $result = $this
            ->query($sql)
            ->result();

        if(!count($result)){
            return array();
        }

        $hash = array();
        foreach($result as $r){
            $hash[$r->report_type]['i'][$r->id_map] = array(
                'id' => $r->id_map,
                'text' => $r->nm_map
            );
            $hash[$r->report_type]['u'][] = array(
                'id' => $r->id_map,
                'text' => $r->nm_map
            );
        }

        return $hash;
    }

    public function get_coa_group_list()
    {
        $sql = "
            SELECT 
                mcoa.*
            FROM 
                mastercoa mcoa
            WHERE mcoa.fg_posting = '0'
        ";

        return $this
            ->query($sql)
            ->result();
    }

    public function get_coa($id)
    {
        $sql = "
            SELECT 
                mcoa.*
                ,mcoa.map_category id_map
            FROM 
                mastercoa mcoa
            WHERE
                mcoa.id_coa = '$id'
        ";

        return $this
            ->query($sql)
            ->row();
    }

    public function get_coa_by($param = array())
    {
        $sql = "
            SELECT 
                mcoa.*
            FROM 
                mastercoa mcoa
            WHERE 1=1
                {WHERE}
        ";

        if(!count($param)){
            return array();
        }

        $where = '';
        foreach($param as $k=>$v){
            $p = explode(' ', $k);
            if(isset($p[1])){
                $operator = $p[1];
                $where .= " AND $p[0] $operator '$v' ";
            }else{
                $where .= " AND $k = '$v' ";
            }
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->row();
    }


}

// CONTROLLER BEGIN

$M = new Model($con_new);
$errMsg = '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit'])){
    $data = array(
        'id_coa' => $_POST['id_coa'],
        'nm_coa' => $_POST['nm_coa'],
        'fg_posting' => $_POST['fg_posting'],
        'fg_mapping' => $_POST['fg_mapping'],
        'fg_active' => isset($_POST['fg_active']) ? '1' : '0',
        'post_to' => isset($_POST['post_to']) ? $_POST['post_to'] : '',
        'map_category' => isset($_POST['map_category']) ? $_POST['map_category'] : '',
    );

    if($_POST['mode'] == 'save'){
        if($M->get_coa($data['id_coa'])){
            $status = false; // Id coa already exists
            $errMsg = 'Error: ID Chart of Account sudah digunakan';
        }/*elseif($M->get_coa_by(array('nm_coa'=>$data['nm_coa']))){
            $status = false; // Nm coa already exists
            $errMsg = 'Error: Nama Chart of Account sudah digunakan';
        }*/else{
            $status = $M->save($data);
        }
    }else{
        if($id != $data['id_coa'] and $M->get_coa($data['id_coa'])){
            $status = false; // Id coa already exists
            $errMsg = 'Error: ID Chart of Account sudah digunakan';
        }/*elseif($M->get_coa_by(array('nm_coa'=>$data['nm_coa'],'id_coa !='=>$data['id_coa']))){
            $status = false; // Nm coa already exists
            $errMsg = 'Error: Nama Chart of Account sudah digunakan';
        }*/else{
            $status = $M->update($id, $data);
        }
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=coa';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
    if($id){
        $row = $M->get_coa($id);
    }
}

$coa = array();
$_coa = $M->get_coa_group_list();
if(count($_coa)){
    foreach($_coa as $_r){
        $coa[$_r->id_coa] = $_r->nm_coa;
    }
}

$segments = $M->get_segment_list();
$list = $M->get_coa_list();
$categories = $M->get_coa_category();
//echo '<pre>';print_r($categories);exit();
// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<div id="formCoa" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>ID Chart of Account</label>
                        <div class="input-group">
                            <input type='text' id='id_coa' class='form-control' name='id_coa'
                                   placeholder='Masukkan ID Chart of Account' value='<?=isset($row)?$row->id_coa:''?>' readonly>
                            <a onclick="lookup_group()" style="cursor: pointer" class="input-group-addon" >...</a>
                        </div>
					</div>								
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>Nama Chart of Account</label>
                        <input type='text' id='nm_coa' class='form-control' name='nm_coa'
                               placeholder='Masukkan Nama Chart of Account' value='<?=isset($row)?$row->nm_coa:''?>'>
					</div>
				</div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Mapping Laporan</label>
                        <select class='form-control select2' name='fg_mapping' id="fg_mapping" onchange="toggle_category()" >
                            <option value="0">Pilih Mapping</option>
                            <option value="1" <?=(@$row->fg_mapping=='1') ? 'selected':''?> >Balance Sheet</option>
                            <option value="2" <?=(@$row->fg_mapping=='2') ? 'selected':''?> >Profit &amp; Loss</option>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Kategori</label>
                        <select class='form-control select2' name='map_category' id="map_category" >
                            <option value="">Pilih Kategori</option>
                            <?php if(@$row->map_category):?>
                            <option value="<?=$row->map_category?>" selected ><?=$categories[$row->fg_mapping]['i'][$row->id_map]['text']?></option>
                            <?php foreach($categories[$row->fg_mapping]['i'] as $_map):?>
                            <option value="<?=$_map['id']?>" ><?=$_map['text']?></option>
                            <?php endforeach;?>
                            <?php endif;?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
				<div class='col-md-3'>				
					<div class='form-group'>
						<label>Tipe</label>
						<select class='form-control select2' name='fg_posting'  >
                            <option value="">Tipe Posting</option>
                            <option value="0" <?=(@$row->fg_posting=='0') ? 'selected':''?> >Total</option>
                            <option value="1" <?=(@$row->fg_posting=='1') ? 'selected':''?> >Posting</option>
                        </select>
					</div>				
				</div>
                <div class="col-md-6">
                    <div class='form-group'>
                        <label>Akun Grouping</label>
                        <select type='text' id='post_to' class='form-control select2' name='post_to'>
                            <option value="" disabled selected>Pilih Akun Grouping</option>
                            <?php foreach($coa as $_id=>$_v):?>
                                <option value="<?=$_id?>" <?=(@$row->post_to==$_id) ? 'selected':''?> ><?=$_id.' - '.$_v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label><input type="checkbox" value="1" name="fg_active" <?=(@$row->fg_active=='1') ? 'checked':''?> /> Aktif</label>
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
    <h3 class="box-title">List Chart of Account</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Akun Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>ID Chart of Account</th>
            <th>Nama Chart of Account</th>
            <th>Tipe</th>
            <th>Mapping</th>
            <th>Status</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->id_coa?></td>
            <td><?=$l->nm_coa?></td>
            <td><?=@Config::$coa_type[$l->fg_posting]?></td>
            <td><?=@Config::$coa_mapping[$l->fg_mapping]?></td>
            <td><?=$l->fg_active ? 'Aktif' : 'Tidak Aktif'?></td>
            <td>
                <a class="" href="?mod=coa&amp;id=<?=$l->id_coa?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>


<div class="modal fade" id="modalGroup"  role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">ID Lookup</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <label>Group</label><br>
                    <select class='form-control select2' id="group" onchange="select_group()"  >
                        <option value="" selected disabled>Pilih Group</option>
                        <?php foreach($segments as $sid => $s):?>
                        <option value="<?=$sid?>"><?=$s['desc']?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class='form-group'>
                    <label>Sub-Group</label><br>
                    <select class='form-control select2' id="subgroup"  >
                    </select>
                </div>
                <div class='form-group'>
                    <label>Address Group</label>
                    <input type='text' class='form-control' id="addressgroup"
                           placeholder='' value=''>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" onclick="accept_segment()">Accept</a>
            </div>
        </div>
    </div>
</div>

<script>
    var segments = <?=json_encode($segments);?>;
    var id_coa_length = 5;
    var last_coa_length = 2;
    var categories = <?=json_encode($categories)?>;

    function toggle_category(){
        var rt = $('#fg_mapping').val();
        var list;

        if(rt!='0'){
            list = categories[rt]['u'];
        }else{
            list = [];
        }

        $('#map_category').select2('destroy').empty().select2({data: list});
    }

    function show_form(){
        $('#formCoa').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function lookup_group(){
        $('#modalGroup').modal('show');
        $("#modalGroup .select2").select2({
            dropdownParent: $('#modalGroup'),
            dropdownAutoWidth: true,
            width: '100%'
        });
    }
    function select_group(){
        $('#subgroup').select2('destroy').empty().select2({
            data: segments[$('#group').val()].rows
        });
    }
    function validate(){
        var status = true;
        if(!$('#group').val()){
            alert('Error: Group tidak boleh kosong');
            status = false;
        }else
        if(!$('#subgroup').val()){
            alert('Error: Sub-group tidak boleh kosong');
            status = false;
        }else
        if(!$('#addressgroup').val()){
            alert('Error: Address-group tidak boleh kosong');
            status = false;
        }else
        if($('#addressgroup').val().length != last_coa_length){
            alert('Error: Address-group harus terdiri dari '+last_coa_length+' karakter');
            status = false;
        }
        return status;
    }
    function accept_segment(){
        if(!validate()){
            return;
        }
        var id = '';
        id += $('#group').val();
        id += $('#subgroup').val();
        id += $('#addressgroup').val();

        $('#id_coa').val(id);

        $('#modalGroup').modal('hide');
    }

    function validasi() {
        var id_coa = document.form.id_coa.value;
        var nm_coa = document.form.nm_coa.value;
        var fg_posting = document.form.fg_posting.value;
		  var post_to = document.form.post_to.value;
        if (id_coa == '') {
            alert('Error: ID Chart of Account tidak boleh kosong');
            document.form.id_coa.focus();
            valid = false;
        } else if (id_coa.length != id_coa_length) {
            alert('Error: ID Chart of Account harus terdiri dari '+id_coa_length+' karakter');
            document.form.id_coa.focus();
            valid = false;
        } else if (nm_coa == '') {
            alert('Error: Nama Chart of Account tidak boleh kosong');
            document.form.nm_coa.focus();
            valid = false;
        } else if (fg_posting == '') {
            alert('Error: Tipe tidak boleh kosong');
            document.form.fg_posting.focus();
            valid = false;
        } else if (post_to == '') {
            alert('Error: Akun Grouping tidak boleh kosong');
            document.form.post_to.focus();
            valid = false;
        } else valid = true;

        return valid;
    }
</script>