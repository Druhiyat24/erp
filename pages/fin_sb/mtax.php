<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Tax__Rate","userpassword","username='$user'");
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
            INSERT INTO mtax 
              ( Name_tax,type,kriteria,kondisi,percentage)
            VALUES (
              '{$data['tax_name']}',
              '{$data['type']}',
              '{$data['kriteria']}',
              '{$data['kondisi']}',
              '{$data['percentage']}'
              
            );
        ";

        return $this->query($sql);
    }

    public function delete($id)
    {
        $sql = "
            DELETE FROM mtax WHERE idtax = '$id';
        ";

        return $this->query($sql);
    }


    public function update($id, $data)
    {
        $sql = "
            UPDATE mtax SET 
              Name_tax = '{$data['tax_name']}',
              type = '{$data['type']}',
              kriteria = '{$data['kriteria']}',
              kondisi = '{$data['kondisi']}',
              percentage = '{$data['percentage']}'
              
            WHERE idtax = '$id';
        ";

        return $this->query($sql);
    }

    public function get_tax_list()
    {
        $sql = "
            SELECT 
                idtax
                ,Name_tax,type,kriteria,kondisi,Percentage
            FROM mtax
            ORDER BY 
              idtax ASC
        ";

        return $this->query($sql)->result();

    }

    public function get_cost_dept($id)
    {
        $sql = "
            SELECT 
                idtax
                ,Name_tax,type,kriteria,kondisi,Percentage
            FROM mtax
            WHERE idtax = '$id'
        ";

        return $this->query($sql)->row();

    }

    public function get_cost_dept_by($param)
    {
        $sql = "
            SELECT 
                idtax
                ,Name_tax,type,kriteria,kondisi,Percentage
            FROM mtax
            WHERE 1=1
              {WHERE}
            ORDER BY 
              percentage desc
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
        'idtax' => $_POST['idtax'],
        'tax_name' => $_POST['tax_name'],
        'type' => $_POST['type'],
        'kriteria' => $_POST['kriteria'],
        'kondisi' => $_POST['kondisi'],
        'percentage' => $_POST['percentage'],
    );

    if($_POST['mode'] == 'save'){
        if($M->get_cost_dept($data['idtax'])){
            $status = false; // Id already exists
            $errMsg = 'Error: ID Tax sudah digunakan';
        }else{
            $status = $M->save($data);
        }
    }elseif($_GET['mode']=='update'){
        if($id != $data['idtax'] and $M->get_cost_dept($data['idtax'])){
            $status = false; // Id coa already exists
            $errMsg = 'Error: ID Tax sudah digunakan';
        }else{
            $status = $M->update($id, $data);
        }

    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=mtax';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}elseif(isset($_GET['id']) && $_GET['mode']=='delete' ){
        $id=$_GET['id'];
        $result = mysql_query("DELETE FROM mtax where idtax='$id'");
		 echo "<script>alert('Data berhasil dihapus'); window.location.href='?mod=mtax';</script>";exit();

}else{
    if($id){
        $row = $M->get_cost_dept($id);
    }
}

$list = $M->get_tax_list();
//echo '<pre>';var_dump($list);exit();
// CONTROLLER END


?>
<div id="formTax" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
                
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Tax Name</label>
                        <input type='text' id='tax_name' class='form-control' name='tax_name'
                               placeholder='Masukkan Tax Name' value='<?=isset($row)?$row->Name_tax:''?>' >
                    </div>                              
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Type Tax</label>
                        <input type='text' id='type' class='form-control' name='type'
                               placeholder='Masukkan Type Tax' value='<?=isset($row)?$row->type:''?>' >
                    </div>                              
                </div>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label>kriteria</label>
                        <input type='text' id='kriteria' class='form-control' name='kriteria'
                               placeholder='Masukkan kriteria Tax' value='<?=isset($row)?$row->kriteria:''?>' >
                    </div>                              
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Condition</label>
                        <select id="kondisi"  class="form-control" name="kondisi">
                            <option ><?=isset($row)?$row->kondisi:'--Pilih Condition--'?></option>
                            <option>Tidak Final</option>
                            <option>Final</option>
                        </select>
                    </div>                              
                </div>
                
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Percentage (%)</label>
                        <input type='text' id='percentage' class='form-control' name='percentage'
                               placeholder='Masukkan Percentage' value='<?=isset($row)?$row->Percentage:''?>'>
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
    <h3 class="box-title">List Master Rate Tax</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Create Master Tax </a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th width='5%'>ID Tax</th>
            <th>Tax Name</th>
            <th>Type Tax </th>
            <th>Kriteria </th>
            <th>Condition </th>
            <th>Percentage (%)</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->idtax?></td>
            <td><?=$l->Name_tax?></td>
            <td><?=$l->type?></td>
            <td><?=$l->kriteria?></td>
            <td><?=$l->kondisi?></td>
            <td><?=$l->Percentage ?></td>
            <td>
                <a class=" " href="?mod=mtax&amp;id=<?=$l->idtax?>&mode=update" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fa fa-pencil"></i> </a>
                
                <a class=" " href="?mod=mtax&amp;id=<?=$l->idtax?>&mode=delete" onclick="return confirm('Apakah anda yakin akan menghapus ?')"  data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-trash"></i> </a>
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
        $('#formTax').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function validasi() {
        var status = true;
        
        if(!$('#tax_name').val()){
            alert('Error: Nama Tax tidak boleh kosong');
            status = false;
        }else
        if(!$('#type').val()){
            alert('Error: Type Tax tidak boleh kosong');
            status = false;
        }else
        if(!$('#kriteria').val()){
            alert('Error: kriteria Tax tidak boleh kosong');
            status = false;
        }else
        if(!$('#kondisi').val()){
            alert('Error: Condition Tax tidak boleh kosong');
            status = false;
        }else
        if(!$('#percentage').val()){
            alert('Error: percentage Tax tidak boleh kosong');
            status = false;
        }
        return status;
    }
</script>