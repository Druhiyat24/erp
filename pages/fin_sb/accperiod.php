<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Acc_Period","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

class Config{
    public static $period_status = array(
        '0' => 'Created',
        '1' => 'Open',
        '2' => 'Closed',
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
        $date = date('Y-m-d H:i:s', time());

        $sql = "
            INSERT INTO masteraccperiod 
              (period, fg_status, create_date)
            VALUES (
              '{$data['period']}',
              '{$data['fg_status']}',
              '{$date}'
            );
        ";

        $this->query($sql);

        return $this->update($data['period'], $data);
    }

    public function update($id, $data)
    {
        $date = date('Y-m-d H:i:s', time());
        $timestamp = '';
        if($data['fg_status'] == '1'){
            $timestamp = " ,open_date = '$date' ";
        }elseif($data['fg_status'] == '2'){
            $timestamp = " ,closed_date = '$date' ";
        }
        $sql = "
            UPDATE masteraccperiod SET 
              period = '{$data['period']}',
              fg_status = '{$data['fg_status']}'
              {$timestamp}
            WHERE period = '$id';
        ";

        return $this->query($sql);
    }

    public function get_accperiod($id){
        $sql = "
            SELECT 
                period
                ,fg_status
                ,create_date
                ,open_date
                ,closed_date
            FROM masteraccperiod
            WHERE period = '$id'
        ";

        return $this->query($sql)->row();
    }

    public function get_accperiod_list()
    {
        $sql = "
            SELECT 
                period
                ,fg_status
                ,create_date
                ,open_date
                ,closed_date
            FROM masteraccperiod
            ORDER BY 
              period ASC
        ";

        return $this->query($sql)->result();

    }


}

// CONTROLLER BEGIN

$M = new Model($con_new);
$errMsg = '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit'])){
    $data = array(
        'period' => $_POST['period'],
        'fg_status' => isset($_POST['fg_status']) ? $_POST['fg_status'] : '0',
    );

    if($_POST['mode'] == 'save'){
        if($M->get_accperiod($data['period'])){
            $status = false; // Id already exists
            $errMsg = 'Error: Period sudah pernah dibuat';
        }else{
            $status = $M->save($data);
        }
    }else{
        if($id != $data['period'] and $M->get_accperiod($data['period'])){
            $status = false; // Id already exists
            $errMsg = 'Error: Period sudah pernah dibuat';
        }else{
            $status = $M->update($id, $data);
        }
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=accperiod';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
    if($id){
        $row = $M->get_accperiod($id);
    }
}

$list = $M->get_accperiod_list();
//echo '<pre>';var_dump($list);exit();
// CONTROLLER END


?>
<div id="formDept" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Periode</label>
                        <input type='text' id='period' class='form-control monthpicker' name='period'
                               placeholder='Masukkan Periode' value='<?=isset($row)?$row->period:''?>' >
					</div>								
				</div>
                <div class="col-md-3">
                    <label>Status Periode</label>
                    <select id="fg_status" name="fg_status" class="form-control" >
                        <?php foreach(Config::$period_status as $k => $j):?>
                            <option value="<?=$k?>" <?=(isset($row) and $row->fg_status==$k)?'selected':''?>><?=$j?></option>
                        <?php endforeach;?>
                    </select>
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
    <h3 class="box-title">List Periode</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Periode Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>Periode</th>
            <th>Status</th>
            <th>Tanggal Buat</th>
            <th>Tanggal Buka</th>
            <th>Tanggal Tutup</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->period?></td>
            <td><?=Config::$period_status[$l->fg_status]?></td>
            <td><?=$l->create_date?></td>
            <td><?=$l->open_date?></td>
            <td><?=$l->closed_date?></td>
            <td>
                <a class="btn btn-primary btn-s" href="?mod=accperiod&amp;id=<?=$l->period?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>


<script>
    function show_form(){
        $('#formDept').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

    function validasi() {
        var status = true;
        if(!$('#period').val()){
            alert('Error: Periode tidak boleh kosong');
            status = false;
        }
        return status;
    }
</script>