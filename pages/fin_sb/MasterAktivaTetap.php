<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_M_Tipe_Aktiva","userpassword","username='$user'");
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
            INSERT INTO masteractivatype 
              (kd_tipe_aktiva
				,nm_tipe_aktiva
				,v_metodepenyusutan
				,n_yearsestimasiumur
				,n_monthestimasiumur
				,n_pernyusutanbydate
				,n_pernyusutan_perbulanbydate
				
				)
            VALUES (
			  '".$data['kodetipeaktiva']."' 	
			  ,'".$data['namatipeaktiva']."' 	
			  ,'".$data['metodepenyusutan']."' 	
			  ,'".$data['estimasitahun']."'		
			  ,'".$data['estimasibulan']."' 	
			  ,'".$data['penyusutan']."'
			  ,'".$data['penyusutanbulan']."'
            );
        ";

        return $this->query($sql);
    }

    public function update($data)
    {
		
        $sql = "
            UPDATE masteractivatype SET nm_tipe_aktiva = '".$data['namatipeaktiva']."'
				,v_metodepenyusutan  ='".$data['metodepenyusutan']."'
				,n_yearsestimasiumur ='".$data['estimasitahun']."'
				,n_monthestimasiumur ='".$data['estimasibulan']."'
				,n_pernyusutanbydate ='".$data['penyusutan']."'
				,n_pernyusutan_perbulanbydate ='".$data['penyusutanbulan']."'
			WHERE kd_tipe_aktiva = '".$data['kodetipeaktiva']."'
        ";

        return $this->query($sql);
    }

    public function getListData_list()
    {
		
        $sql = "
            SELECT kd_tipe_aktiva,nm_tipe_aktiva 
			,v_metodepenyusutan  
			,n_yearsestimasiumur 
			,n_monthestimasiumur 
			,n_pernyusutanbydate 
			,n_pernyusutan_perbulanbydate FROM masteractivatype 
        ";

        return $this->query($sql)->result();

    }

    public function getListData($kodetipeaktiva)
    {
		
        $sql = "
 SELECT kd_tipe_aktiva,nm_tipe_aktiva 
 ,v_metodepenyusutan  
 ,n_yearsestimasiumur 
 ,n_monthestimasiumur 
 ,n_pernyusutanbydate
 ,n_pernyusutan_perbulanbydate FROM masteractivatype  WHERE kd_tipe_aktiva = '$kodetipeaktiva';
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
	$namatipeaktiva = '';
	$kodetipeaktiva = '';
	$mode = 'add';
	$readonly = '';
	$metodepenyusutan ='';
	$estimasitahun    ='';
	$estimasibulan    ='';
	$penyusutan       ='';
	$penyusutanbulan       ='';
	
if((ISSET($_GET['kodetipeaktiva'])) ){
	
	$kodetipeaktiva = $_GET['kodetipeaktiva'];
	$row = $M->getListData($kodetipeaktiva);
	$mode = 'edit';
	$readonly = 'readonly';
	//print_r($row);
	$namatipeaktiva = $row->nm_tipe_aktiva;
	$metodepenyusutan	=$row->v_metodepenyusutan;  
	$estimasitahun		=$row->n_yearsestimasiumur;
	$estimasibulan		=$row->n_monthestimasiumur; 
	$penyusutan			=$row->n_pernyusutanbydate;
	$penyusutanbulan			=$row->n_pernyusutan_perbulanbydate;
	
	
}

if(isset($_POST['submit'])){
    $data = array(
		'kodetipeaktiva' 	=> $_POST['kodetipeaktiva'],
        'namatipeaktiva' 	=> $_POST['namatipeaktiva'],
		'metodepenyusutan' 	=>$_POST['metodepenyusutan'], 
		'estimasitahun'		=>$_POST['estimasitahun'],
		'estimasibulan' 	=>$_POST['estimasibulan'],
		'penyusutan' 		=>$_POST['penyusutan'],
		'penyusutanbulan' 		=>$_POST['penyusutanbulan'],
    );

    if($_POST['mode'] == 'add'){

            $status = $M->save($data); 
        }
    else{

            $status = $M->update($data);
        
    }

    if($status){
        echo "<script>alert('Data tersimpan'); window.location.href='?mod=MasterAktivaTetap';</script>";exit();
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
						<label>Kode Type Aktiva</label>
                        <input type='text' id='id_cost_dept' class='form-control' name='kodetipeaktiva'
                               placeholder='Masukkan kode aktiva' <?php echo $readonly ?> value='<?php echo $kodetipeaktiva ?>' >
					</div>								
				</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Nama Type Aktiva</label>
                        <input type='text' id='nm_cost_dept' class='form-control' name='namatipeaktiva'
                               placeholder='Masukkan nama' value='<?php echo $namatipeaktiva ?>'>
					</div>
				</div>
	
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Metode Penyusutan</label>
                        <input type='text' id='metodepenyusutan'class='form-control' name='metodepenyusutan'
                               placeholder='Masukkan Metode Penyusutan' value='<?php echo $metodepenyusutan ?>'>
					</div>
				</div>

                <div class='col-md-3'>
					<div class='form-group' id='number'>
						<label>Estimasi Umur(thn)</label>
                        <input type='text' id='estimasitahun' class='form-control' onkeyup="UmurBulan(this)" onkeypress="return hanyaAngka(event)" name='estimasitahun'
                               placeholder='Masukkan Estimasi Umur(thn)' value='<?php echo $estimasitahun ?>' >
					</div>
				</div>
                <div class='col-md-3'>
					<div class='form-group'>
						<label>Estimasi Umur(bln)</label>
                        <input type='text' id='estimasibulan' readonly class='form-control' name='estimasibulan'
                               placeholder='Masukkan Estimasi Umur(bln)'value='<?php echo $estimasibulan ?>'>
					</div>
				</div>
				
                <div class='col-md-3'>
					<div class='form-group'>
						<label>% Penyusutan/thn</label>
                        <input type='text' id='penyusutan' readonly class='form-control' name='penyusutan'
                               placeholder='Masukkan % estimasi penyusutan' value='<?php echo $penyusutan ?>'>
					</div>
				</div>	
				<div class='col-md-3'>
					<div class='form-group'>
						<label>% Penyusutan/Bulan</label>
                        <input type='text' id='penyusutanbulan' readonly class='form-control' name='penyusutanbulan'
                               placeholder='Masukkan % estimasi penyusutan' value='<?php echo $penyusutanbulan ?>'>
					</div>
				</div>	
	
                <div class="clearfix"></div>
				<div class="col-md-12">
					<div class="form-group">
                        <input type='hidden' name='mode' value='<?php echo $mode ?>'>
						<input type='hidden' name='submit' value='Save'>
						<button type="submit" class='btn btn-primary'  >Simpan</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">Master Aktiva Tetap</h3>
      <a onclick="show_form();" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> Buat Aktiva Baru</a>
  </div>
  <div class="box-body">
  	<table id="examplefix4" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>Kode Aktiva</th>
            <th>Nama Aktiva</th>
			<th>Metode Penyusutan</th>
			<th>Estimasi Umur (thn)</th>
			<th>Estimasi Umur (bln)</th>
			<th>% Penyusutan / thn</th>
			<th>% Penyusutan / Bulan</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->kd_tipe_aktiva?></td>
            <td><?=$l->nm_tipe_aktiva?></td>
			<td><?=$l->v_metodepenyusutan?></td>
			<td><?=$l->n_yearsestimasiumur?></td>
			<td><?=$l->n_monthestimasiumur?></td>
			<td><?=$l->n_pernyusutanbydate?></td>
			<td><?=$l->n_pernyusutan_perbulanbydate?></td>
            <td>
                <a class="" href="?mod=MasterAktivaTetap&kodetipeaktiva=<?php echo $l->kd_tipe_aktiva?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i> </a>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>


<script>
document.ready(function(){
	
	
	
	
})

function UmurBulan(Item){
	if(Item.id == 'estimasitahun'){
	
		var tahun = Item.value;
		var bulan = tahun*12;
		var penyusutan = Math.round((100/tahun),50);
		if (penyusutan=="Infinity"){penyusutan=0}
		var penyusutanbulan = Math.round((penyusutan/12),50);
		$("#estimasibulan").val(bulan);
		$("#penyusutan").val(penyusutan);
		$("#penyusutanbulan").val(penyusutanbulan);
	}
}

	function save(){
		//return false;
		var id_cost_dept = $("#id_cost_dept").val();
		var nm_cost_dept = $("#nm_cost_dept").val();
		var metodepenyusutan = $("#metodepenyusutan").val();
		var estimasitahun = $("#estimasitahun").val();
		if(id_cost_dept == ''){
			alert("Error: Kode aktiva tidak boleh kosong");
			return false;
		}
		if(nm_cost_dept == ''){
			alert("Error: Nama aktiva tidak boleh kosong");
			return false;
		}
		if(metodepenyusutan == ''){
			alert("Error: Metode penyusutan tidak boleh kosong");
			return false;
		}
		if(estimasitahun == ''){
			alert("Error: Estimasi umur(thn) tidak boleh kosong");
			return false;
		}
		
	}
	
	function hanyaAngka(evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode
		   if (charCode > 31 && (charCode < 48 || charCode > 57))
 
		    return false;
		  return true;
		}

    //var depts = <?=json_encode($list);?>;

    function show_form(){
        $('#formDept').removeClass('hidden');
        $("select.select2").select2("destroy").select2();
    }

 
</script>