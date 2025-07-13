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


		$explodedate = explode("/",$data['tanggalbeli']);
		$data['tanggalbeli'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
		$explodedate = explode("/",$data['tanggalpakai']);
		$data['tanggalpakai'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];

		$sql = "
            INSERT INTO masteractiva 
              (kd_aktiva, nm_aktiva
					,v_tipeactiva
					,d_tanggalbeli
					,d_tanggalpakai
					,d_umurtahunactiva
					,d_umurbulanactiva
					,v_akunactiva
					,v_akunakumulasipenyusutan
					,v_akunbiayapenyusutan			
			  )
            VALUES (
               '".$data['kodeaktiva']."'
			  ,'".$data['namaaktiva']."'
			  ,'".$data['tipeaktiva']."'
			  ,'".$data['tanggalbeli']."'
			  ,'".$data['tanggalpakai']."'
			  ,'".$data['umurtahunctiva']."'
			  ,'".$data['umurbulanactiva']."'
			  ,'".$data['akunactiva']."'
			  ,'".$data['akunakumulasipenyusutan']."'
			  ,'".$data['akunbiayapenyusutan']."'
            );
        ";
        return $this->query($sql);
    }

    public function update($data)
    {

		$explodedate = explode("/",$data['tanggalbeli']);
		$data['tanggalbeli'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];
		$explodedate = explode("/",$data['tanggalpakai']);
		$data['tanggalpakai'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];		
		
        $sql = "
            UPDATE masteractiva SET nm_aktiva = '".$data['namaaktiva']."'
			,v_tipeactiva             ='".$data['tipeaktiva']."'
        	,d_tanggalbeli            ='".$data['tanggalbeli']."'
			,d_tanggalpakai           ='".$data['tanggalpakai']."'
        	,d_umurbulanactiva        ='".$data['umurbulanactiva']."'
			,d_umurtahunactiva        ='".$data['umurtahunactiva']."'
        	,v_akunactiva             ='".$data['akunactiva']."'
        	,v_akunakumulasipenyusutan  ='".$data['akunakumulasipenyusutan']."'
        	,v_akunbiayapenyusutan	  ='".$data['akunbiayapenyusutan']."'
			WHERE kd_aktiva = '".$data['kodeaktiva']."'
        ";

        return $this->query($sql);
    }

    public function getListData_list()
    {
		
        $sql = "
            SELECT A.kd_aktiva,A.nm_aktiva
  ,A.v_tipeactiva
  ,A.d_tanggalbeli
  ,A.d_tanggalpakai
  ,A.d_umurtahunactiva
  ,A.d_umurbulanactiva
  ,A.v_akunactiva X
  ,A.v_akunakumulasipenyusutan Y
  ,A.v_akunbiayapenyusutan Z
  ,B.nm_coa v_akunactiva
  ,C.nm_coa v_akunakumulasipenyusutan
  ,D.nm_coa v_akunbiayapenyusutan
			FROM masteractiva A INNER JOIN (SELECT id_coa,nm_coa FROM mastercoa) B ON A.v_akunactiva =B.id_coa
								INNER JOIN (SELECT id_coa,nm_coa FROM mastercoa ) C ON A.v_akunakumulasipenyusutan =C.id_coa
								INNER JOIN (SELECT id_coa,nm_coa FROM mastercoa ) D ON A.v_akunbiayapenyusutan =D.id_coa
				
        ";

        return $this->query($sql)->result();

    }

    public function getListData($kodeaktiva)
    {
		
        $sql = "
 SELECT kd_aktiva,nm_aktiva 
 ,v_tipeactiva
  ,d_tanggalbeli
  ,d_tanggalpakai
  ,d_umurtahunactiva
  ,d_umurbulanactiva
  ,v_akunactiva
  ,v_akunakumulasipenyusutan
  ,v_akunbiayapenyusutan
 FROM masteractiva  WHERE kd_aktiva = '$kodeaktiva';
        ";

        return $this->query($sql)->row();

    }

    public function getListData_by($param)
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
	$namaaktiva = '';
	$kodeaktiva = '';
	$mode = 'add';
	$readonly = '';
	$tipeaktiva = '';
	$tanggalbeli = '';
	$tanggalpakai = '';
	$umurtahunactiva = '';
	$umurbulanactiva = '';
	$akunactiva  = '';
	$akunakumulasipenyusutan  = '';
	$akunbiayapenyusutan  = '';
	$readonly = '';
if((ISSET($_GET['kodeaktiva'])) ){
	$kodeaktiva = $_GET['kodeaktiva'];
	$row = $M->getListData($kodeaktiva);
	$mode = 'edit';
	$readonly = 'readonly';
	//print_r($row);
	$namaaktiva = $row->nm_aktiva;
	$tipeaktiva             =  $row->v_tipeactiva;
	$tanggalbeli            =  date("d/m/Y", strtotime($row->d_tanggalbeli));
	$tanggalpakai           =  date("d/m/Y", strtotime($row->d_tanggalpakai));
	$umurbulanactiva             =  $row->d_umurbulanactiva;
	$umurtahunactiva             =  $row->d_umurtahunactiva;
	$akunactiva             =  $row->v_akunactiva;
	$akunakumulasipenyusutan=  $row->v_akunakumulasipenyusutan;
	$akunbiayapenyusutan    =  $row->v_akunbiayapenyusutan;
	$readonly = "readonly";
	
}

if(isset($_POST['submit'])){
		


		
    $data = array(
		'kodeaktiva' => $_POST['kodeaktiva'],
        'namaaktiva' => $_POST['namaaktiva'],
	'tipeaktiva' => $_POST['tipeaktiva'],
	'tanggalbeli' => $_POST['tanggalbeli'],
	'tanggalpakai' => $_POST['tanggalpakai'],
	'umuratahunctiva' => $_POST['umurtahunactiva'],
	'umurbulanactiva' => $_POST['umurbulanactiva'],
	'akunactiva' => $_POST['akunactiva'],
	'akunakumulasipenyusutan' => $_POST['akunakumulasipenyusutan'],
	'akunbiayapenyusutan' => $_POST['akunbiayapenyusutan'],
		
    );

    if($_POST['mode'] == 'add'){

            $status = $M->save($data); 
        }
    else{

            $status = $M->update($data);
        
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=MasterAktiva';</script>";exit();
    }else{
        $row = json_decode(json_encode($data));
        print "<script>alert('$errMsg');</script>";
    }
}else{
 //   if($id){
 //       $row = $M->getListData($id);
 //   }
}

$list = $M->getListData_list();
//echo '<pre>';var_dump($list);exit();
// CONTROLLER END


?>
<div id="formDept" class='box <?=isset($row) ? '':'hidden'?>'>
	<div class='box-body'>
		<div class='row'>
			<form method='post' name='form' onsubmit="return save()" id="formsaya" enctype='multipart/form-data' action="" >

	                <div class='col-md-3'>
					<div class='form-group'>
						<label>Periode Akuntansi</label>

						                        <input type='text' <?php echo "$readonly" ?> autocomplete="off" id='periodeakuntansi' class='form-control' name='periodeakuntansi'
                               placeholder='Masukkan Tipe Activa' value='<?php echo $periodeakuntansi ?>'>						 
					</div>	
					
					</div>

			
	                <div class='col-md-3'>
					<div class='form-group'>
						<label>Tipe Aktiva</label>
                        <select id='tipeaktiva' class='form-control' <?php echo "$readonly" ?> onchange="getTipeAktivaDetail(this)" name='tipeaktiva'>
                          
						 </select> 							      
					</div>	
					
					</div>		
			
               <div class='col-md-3'>
					<div class='form-group'>
						<label>Kode Type Aktiva</label>
						                        <input type='text' <?php echo "$readonly" ?> autocomplete="off" id='kodeaktiva' class='form-control' name='kodeaktiva'
                               placeholder='Masukkan Tipe Activa' value='<?php echo $kodeaktiva ?>'>

					</div>								
				</div>
                <div class='col-md-3'  style="display:none">
					<div class='form-group'>
						<label>Nama Aktiva</label>
                        <input type='text' autocomplete="off" id='namaaktiva' id='date' class='form-control' name='namaaktiva'
                               placeholder='Masukkan Nama Activa' value='<?php echo $namaaktiva ?>'>
					</div>
				</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Tanggal Beli</label>
                        <input type='text' id='tanggalbeli' id='date' class='form-control' name='tanggalbeli' autocomplete="off"
                               placeholder='Masukkan Tanggal Beli' value='<?php echo $tanggalbeli ?>'>
					</div>	
				</div>
                <div class='col-md-3'>					
					<div class='form-group'>
						<label>Tanggal Pakai</label>
                        <input type='text' id='tanggalpakai' id='date' class='form-control' name='tanggalpakai' autocomplete="off"
                               placeholder='Masukkan Tanggal pakai'  value='<?php echo $tanggalpakai ?>'>
					</div>	
									</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Umur Tahun Activa</label>
                        <input type='text' id='umurtahunactiva' id='date' class='form-control' name='umurtahunactiva'
                               placeholder='Masukkan Tahun Tanggal Activa' autocomplete="off" readonly value='<?php echo $umurtahunactiva ?>'>
					</div>	
									</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Umur Bulan Activa</label>
                        <input type='text' id='umuractiabulan' id='date' class='form-control' name='umurbulanactiva'
                               placeholder='Masukkan Bulan Tanggal Activa' autocomplete="off" readonly value='<?php echo $umurbulanactiva ?>'>
					</div>	
									</div>									

            <div class='col-md-3'>
					<div class='form-group'>
						<label>Akun Activa</label>
                        <select id='akunactiva' class='form-control' name='akunactiva'>
                          
						 </select> 
					</div>								
				</div>									
									
            <div class='col-md-3'>
					<div class='form-group'>
						<label>Akun Akumulasi Penyususan</label>
                        <select id='akunakumulasipenyusutan' class='form-control' name='akunakumulasipenyusutan'>
                          
						 </select> 
					</div>								
				</div>
            <div class='col-md-3'>
					<div class='form-group'>
						<label>Akun Biaya Penyusutan </label>
                        <select id='akunbiayapenyusutan' class='form-control' name='akunbiayapenyusutan'>
                          
						 </select> 
					</div>								
				</div>

 




							
                <div class="clearfix"></div>
				<div class="col-md-12">
					<div class="form-group">
                        <input type='hidden' name='mode' value='<?php echo $mode ?>'>
						<input type='hidden' name='submit' value='Save'>
						<button type="submit" class='btn btn-primary'  >Simpan</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">Master Aktiva</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Aktiva Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>Kode Aktiva</th>
	    	<th>Tipe Activa</th>
            <th>Tanggal Beli</th>
	    	<th>Tanggal Pakai</th>
            <th>Umur Tahun Activa</th>
			<th>Umur Bulan Activa</th>
	    	<th>Akun Activa</th>
            <th>Akun Akumulasi Penyusutan</th>			
			<th>Akun Biaya Penyusutan</th>	
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->kd_aktiva ?></td>
			<td><?=$l->v_tipeactiva ?></td>
			<td><?=$l->d_tanggalbeli ?></td>
			<td><?=$l->d_tanggalpakai ?></td>
			<td><?=$l->d_umurtahunactiva ?></td>
			<td><?=$l->d_umurbulanactiva ?></td>
			<td><?=$l->v_akunactiva ?></td>
			<td><?=$l->v_akunakumulasipenyusutan ?></td>	
			<td><?=$l->v_akunbiayapenyusutan ?></td>	
            <td>
                <a class="btn btn-primary btn-s" href="?mod=MasterAktiva&kodeaktiva=<?php echo $l->kd_aktiva?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/MasterAktivaTetap.js"></script>
<script>


	function save(){

		//return false;
		var curr = $("#kurs").val();
		var date = $("#date").val();
		var kurs = $("#kurs").val();
		if(curr == ''){
			alert("Currency Harus diisi");
			return false;
		}
		if(date == ''){
			alert("Date Harus diisi");
			return false;
		}
		if(kurs == ''){
			alert("Kurs Harus diisi");
			return false;
		}

	}
	


    //var depts = <?=json_encode($list);?>;

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