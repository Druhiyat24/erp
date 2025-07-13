<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
require_once "../../log_activity/log.php";
$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Department","userpassword","username='$user'");
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
            INSERT INTO mastercostdept 
              (id_cost_dept, nm_cost_dept)
            VALUES (
              '{$data['id_cost_dept']}',
              '{$data['nm_cost_dept']}'
            );
        ";
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE mastercostdept SET 
              id_cost_dept = '{$data['id_cost_dept']}',
              nm_cost_dept = '{$data['nm_cost_dept']}'
            WHERE id_cost_dept = '$id';
        ";
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);
    }

    public function get_cost_dept_list()
    {
        $sql = "
            SELECT 
                id_cost_dept
                ,nm_cost_dept
            FROM mastercostdept
            ORDER BY 
              id_cost_dept ASC
        ";

        return $this->query($sql)->result();

    }

    public function get_cost_dept($id)
    {
        $sql = "
            SELECT 
                id_cost_dept
                ,nm_cost_dept
            FROM mastercostdept
            WHERE id_cost_dept = '$id'
        ";

        return $this->query($sql)->row();

    }

    public function get_cost_dept_by($param)
    {
        $sql = "
            SELECT 
                id_cost_dept
                ,nm_cost_dept
            FROM mastercostdept
            WHERE 1=1
              {WHERE}
            ORDER BY 
              id_cost_dept ASC
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


}

// CONTROLLER BEGIN

$M = new Model($con_new);
$errMsg = '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit'])){
    $data = array(
        'id_cost_dept' => $_POST['id_cost_dept'],
        'nm_cost_dept' => $_POST['nm_cost_dept'],
    );

    if($_POST['mode'] == 'save'){
        if($M->get_cost_dept($data['id_cost_dept'])){
            $status = false; // Id already exists
            $errMsg = 'Error: ID Department sudah digunakan';
        }elseif($M->get_cost_dept_by(array('nm_cost_dept'=>$data['nm_cost_dept'],'id_cost_dept !='=>$data['id_cost_dept']))){
            $status = false; // Nm already exists
            $errMsg = 'Error: Nama Department sudah digunakan';
        }else{
            $status = $M->save($data);
        }
    }else{
        if($id != $data['id_cost_dept'] and $M->get_cost_dept($data['id_cost_dept'])){
            $status = false; // Id coa already exists
            $errMsg = 'Error: ID Department sudah digunakan';
        }elseif($M->get_cost_dept_by(array('nm_cost_dept'=>$data['nm_cost_dept'],'id_cost_dept !='=>$data['id_cost_dept']))){
            $status = false; // Nm coa already exists
            $errMsg = 'Error: Nama Department sudah digunakan';
        }else{
            $status = $M->update($id, $data);
        }
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=costdept';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
    if($id){
        $row = $M->get_cost_dept($id);
    }
}

$list = $M->get_cost_dept_list();
//echo '<pre>';var_dump($list);exit();
// CONTROLLER END


?>
<div id="formDept" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>ID Department</label>
                        <input type='text' id='id_cost_dept' class='form-control' name='id_cost_dept'
                               placeholder='Masukkan ID Department' value='<?=isset($row)?$row->id_cost_dept:''?>' >
					</div>								
				</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Nama Department</label>
                        <input type='text' id='nm_cost_dept' class='form-control' name='nm_cost_dept'
                               placeholder='Masukkan Nama Department' value='<?=isset($row)?$row->nm_cost_dept:''?>'>
					</div>
				</div>
                <div class="clearfix"></div>
				<div class="col-md-12">
					<div class="form-group">
                        <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                        <input type='hidden' name='old_id' value='<?=$id ? $id : '';?>'>
						<button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">List Department</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Department Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>ID Department</th>
            <th>Nama Department</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->id_cost_dept?></td>
            <td><?=$l->nm_cost_dept?></td>
            <td>
                <a class=" " href="?mod=costdept&amp;id=<?=$l->id_cost_dept?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>


<script>
    var depts = <?=json_encode($list);?>;

    function show_form(){
        $('#formDept').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function validasi() {
        var status = true;
        if(!$('#id_cost_dept').val()){
            alert('Error: ID Department tidak boleh kosong');
            status = false;
        }else
        if(!$('#nm_cost_dept').val()){
            alert('Error: Nama Department tidak boleh kosong');
            status = false;
        }
        return status;
    }
</script>