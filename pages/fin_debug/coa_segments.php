<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
require_once "../log_activity/log.php";
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_COA_Segmen","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

class Config{
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
            INSERT INTO mastercoasegment 
              (id_coaconfig, id_segment, nm_segment)
            VALUES (
              '{$data['id_coaconfig']}',
              '{$data['id_segment']}',
              '{$data['nm_segment']}'
            );
        ";
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE mastercoasegment SET 
              id_coaconfig = '{$data['id_coaconfig']}',
              id_segment = '{$data['id_segment']}',
              nm_segment = '{$data['nm_segment']}'
            WHERE id_segment = '$id';
        ";
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);
    }

    public function get_segment_list()
    {
        $sql = "
            SELECT 
                mcs.id_segment
                ,mcc.nm_coaconfig
                ,mcs.nm_segment
            FROM mastercoasegment mcs
            LEFT JOIN mastercoaconfig mcc ON mcs.id_coaconfig = mcc.id_coaconfig
            ORDER BY 
              mcs.id_segment ASC
        ";

        return $this->query($sql)->result();

    }

    public function get_segment($id)
    {
        $sql = "
            SELECT 
                mcs.id_segment
                ,mcs.id_coaconfig
                ,mcc.nm_coaconfig
                ,mcs.nm_segment
            FROM mastercoasegment mcs
            LEFT JOIN mastercoaconfig mcc ON mcs.id_coaconfig = mcc.id_coaconfig
            WHERE mcs.id_segment = '$id'
            ORDER BY 
              mcs.id_segment ASC
        ";

        return $this->query($sql)->row();

    }

    public function get_segment_by($param)
    {
        $sql = "
            SELECT 
                id_segment
                ,id_coaconfig
                ,nm_segment
            FROM mastercoasegment mcs
            WHERE 1=1
              {WHERE}
            ORDER BY 
              id_segment ASC
        ";

        if(!count($param)){
            return array();
        }

        $where = '';
        foreach($param as $k=>$v){
            $p = explode(' ', $k);
            if(isset($p[1])){
                $operator = $p[1];
                $where .= " AND $k '$v' ";
            }else{
                $where .= " AND $k = '$v' ";
            }
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this->query($sql)->row();

    }

    public function get_config_list()
    {
        $sql = "
            SELECT 
                *
            FROM mastercoaconfig
            ORDER BY 
              `position` ASC
        ";

        $result = $this->query($sql)->result();
        if(count($result)){
            array_pop($result);
        }

        $hash = array();
        if(count($result)){
            foreach($result as $r) {
                $hash[$r->id_coaconfig] = $r->nm_coaconfig;
            }
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
        'id_coaconfig' => $_POST['id_coaconfig'],
        'id_segment' => $_POST['id_segment'],
        'nm_segment' => $_POST['nm_segment'],
    );

    if($_POST['mode'] == 'save'){
        if($M->get_segment($data['id_segment'])){
            $status = false; // Id already exists
            $errMsg = 'Error: ID Segment sudah digunakan';
        }/*elseif($M->get_segment_by(array('nm_segment'=>$data['nm_segment'],'id_segment !='=>$data['id_segment']))){
            $status = false; // Nm already exists
            $errMsg = 'Error: Nama Segment sudah digunakan';
        }*/else{
            $status = $M->save($data);
        }
    }else{
        if($id != $data['id_segment'] and $M->get_segment($data['id_segment'])){
            $status = false; // Id coa already exists
            $errMsg = 'Error: ID Segment sudah digunakan';
        }/*elseif($M->get_segment_by(array('nm_segment'=>$data['nm_segment'],'id_segment !='=>$data['id_segment']))){
            $status = false; // Nm coa already exists
            $errMsg = 'Error: Nama Segment sudah digunakan';
        }*/else{
            $status = $M->update($id, $data);
        }
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=coas';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
    if($id){
        $row = $M->get_segment($id);
    }
}

$list = $M->get_segment_list();
$group = $M->get_config_list();
//echo '<pre>';var_dump($list);exit();
// CONTROLLER END


?>
<div id="formCoaSegment" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
                <div class="col-md-3">
                    <div class='form-group'>
                        <label>Group Segment</label>
                        <select id='id_coaconfig' class='form-control select2' name='id_coaconfig'>
                            <option value="" disabled selected>Pilih Group Segment</option>
                            <?php foreach($group as $_id=>$_v):?>
                                <option value="<?=$_id?>" <?=(@$row->id_coaconfig==$_id) ? 'selected':''?> ><?=$_id.' - '.$_v?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>ID Segment</label>
                        <input type='text' id='id_segment' class='form-control' name='id_segment'
                               placeholder='Masukkan ID Chart of Account' value='<?=isset($row)?$row->id_segment:''?>' >
					</div>								
				</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Nama Segment</label>
                        <input type='text' id='nm_segment' class='form-control' name='nm_segment'
                               placeholder='Masukkan Nama Segment' value='<?=isset($row)?$row->nm_segment:''?>'>
					</div>
				</div>
                <div class="clearfix"></div>
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
    <h3 class="box-title">List Segments</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Segment Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>ID Segment</th>
            <th>Nama Group Segment</th>
            <th>Nama Segment</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->id_segment?></td>
            <td><?=$l->nm_coaconfig?></td>
            <td><?=$l->nm_segment?></td>
            <td>
                <a class=" " href="?mod=coas&amp;id=<?=$l->id_segment?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>


<script>
    var segments = <?=json_encode($list);?>;

    function show_form(){
        $('#formCoaSegment').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function validasi() {
        var status = true;
        if(!$('#id_coaconfig').val()){
            alert('Error: Group Segment tidak boleh kosong');
            status = false;
        }else
        if(!$('#id_segment').val()){
            alert('Error: ID Segment tidak boleh kosong');
            status = false;
        }else
        if(!$('#nm_segment').val()){
            alert('Error: Nama Segment tidak boleh kosong');
            status = false;
        }
        return status;
    }
</script>