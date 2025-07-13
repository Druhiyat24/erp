<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Rekap_AR_FORM","userpassword","username='$user'");
if ($akses=="0")
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking - End

require_once "../forms/journal_interface.php";
require_once "../log_activity/log.php";
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
$A = new Assets();
class Model{
    private $conn;
    private $result;
    private $last_id;

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

    public function last_insert_id()
    {
        return $this->conn->insert_id;
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

    public function get_invoice_list()
    {

		/*$q = "SELECT IAC.v_noinvoicecommercial
		,IAC.n_id
		,IAC.n_amount
		,IAC.bpbno
		,IAC.n_idinvoiceheader
		,IAC.v_from
		,IAC.v_to
		,IAC.d_insert
		,IAC.v_pono
		,IH.invno 
		,IH.shipped_by
		,IH.ship_to
		,IH.etd
		,IH.eta
		,IH.measurement
        ,IH.nw j_bersih
        ,IH.gw j_kotor
		,IH.shipper
		,IH.id_buyer
		,BPB.jqty bpbqty
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer buyer_act
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSST.goods_code
		,ID.j_carton
		,ID.qty_inv jqty
		,SO.amount
		,SO.buyerno
		,SO.fob
		,SO.curr
		,DATE_ADD(ji.date_journal, INTERVAL MSS.terms_of_pay DAY) as  jatuh_tempo
                ,ji.id_journal
                ,ji.reff_doc
				,ji.date_journal
				,MPT.days_pterms
FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv,max(id_so_det)id_so_det,SUM(qty)qty_inv FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv		
			LEFT JOIN(
				SELECT id,id_so FROM so_det
			)SOD ON ID.id_so_det = SOD.id
			LEFT JOIN(
				SELECT id,id_cost,ifnull(qty*fob,0) amount,fob,buyerno,curr FROM so
			)SO ON SOD.id_so = SO.id
			LEFT JOIN(
				SELECT id,kpno styleno,id_buyer FROM act_costing
			)ACT ON SO.id_cost = ACT.id 
			LEFT JOIN(
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,id_pterms,id_buyer FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode  
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay FROM mastersupplier
			)MSS ON MSS.Id_supplier = IH.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item

               INNER JOIN (
                    SELECT id_journal, reff_doc,date_journal FROM fin_journal_h WHERE type_journal = '1'
                )ji ON IAC.v_noinvoicecommercial = ji.reff_doc
                LEFT JOIN (
                		SELECT DISTINCT no_invoice FROM fin_status_journal_ar
                )rar ON IAC.v_noinvoicecommercial = rar.no_invoice
                LEFT JOIN (
                		SELECT id,days_pterms FROM masterpterms
                )MPT ON IH.id_pterms = MPT.id					
                WHERE 1=1 
                  AND rar.no_invoice IS NULL;";*/
		$q = "SELECT   FH.id_journal
		,FH.reff_doc
		,IH.id_buyer Id_Supplier
		,ID.amount
		#,(FD.nilai_journal - ifnull(POTONGAN.debit,0) ) n_amount
		,(FD.nilai_journal) n_amount
		,POTONGAN.debit
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		,POTONGAN.id_coa
		FROM fin_journal_h FH
		LEFT JOIN invoice_header IH ON FH.reff_doc = IH.invno
		LEFT JOIN(
			SELECT id_inv,SUM(ifnull(qty,0)*ifnull(price,0))amount,SUM(qty)jqty,MAX(id_so_det)id_so_det
			FROM invoice_detail GROUP BY id_inv
		)ID ON IH.id = ID.id_inv
		LEFT JOIN(
			SELECT SUM(ifnull(debit,0))nilai_journal,id_journal FROM fin_journal_d WHERE  id_coa NOT IN(SELECT disc_d FROM mapping_coa_sales) GROUP BY id_journal
		)FD On FD.id_journal = FH.id_journal
		LEFT JOIN so_det SOD ON ID.id_so_det = SOD.id
		LEFT JOIN so SO ON SO.id = SOD.id_so
		LEFT JOIN mastersupplier MS ON MS.Id_Supplier = IH.id_buyer
		LEFT JOIN(SELECT id_journal
					,id_coa
					,debit
						FROM 
					fin_journal_d WHERE debit > 0 AND id_coa IN(SELECT disc_d FROM mapping_coa_sales) GROUP BY id_journal)POTONGAN
		ON POTONGAN.id_journal = FH.id_journal
WHERE FH.type_journal ='1' AND fg_post = '2'
AND FH.id_journal NOT IN(SELECT id_journal FROM fin_status_journal_ar)";  
		return $this->query($q)->result();
    }

    public function get_rekap($id)
    {
       /* $sql = "
            SELECT far.*, inv.j_price, inv.jqty, inv.Supplier,inv.Id_Supplier, inv.id_journal, inv.supplier_code,inv.jatuh_tempo,inv.n_amount
            FROM 
                fin_status_journal_ar far 
                INNER JOIN (
         SELECT IAC.v_noinvoicecommercial
		,IAC.n_id
		,IAC.n_amount
		,IAC.bpbno
		,IAC.n_idinvoiceheader
		,IAC.v_from
		,IAC.v_to
		,IAC.d_insert
		,IAC.v_pono
		,IH.invno 
		,IH.shipped_by
		,IH.ship_to
		,IH.etd
		,IH.eta
		,IH.measurement
        ,IH.nw j_bersih
        ,IH.gw j_kotor
		,IH.shipper
		,IH.id_buyer
		,BPB.jqty
		,BPB.j_price
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer buyer_act
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSS.supplier_code
		,MSS.Id_Supplier
		,MSST.goods_code
		,ID.j_carton
		,SO.amount
		,SO.buyerno
		,SO.fob
		,DATE_ADD(ji.date_journal, INTERVAL MSS.terms_of_pay DAY) as  jatuh_tempo
                ,ji.id_journal
                ,ji.reff_doc
				,ji.date_journal
				,MPT.days_pterms
FROM invoice_commercial IAC
			LEFT JOIN(
				SELECT SUM(carton) j_carton,id_inv,max(id_so_det)id_so_det FROM invoice_detail GROUP BY id_inv 
			)ID ON IAC.n_idinvoiceheader = ID.id_inv		
			LEFT JOIN(
				SELECT id,id_so FROM so_det
			)SOD ON ID.id_so_det = SOD.id
			LEFT JOIN(
				SELECT id,id_cost,ifnull(qty*fob,0) amount,fob,buyerno FROM so
			)SO ON SOD.id_so = SO.id
			LEFT JOIN(
				SELECT id,kpno styleno,id_buyer FROM act_costing
			)ACT ON SO.id_cost = ACT.id 
			LEFT JOIN(
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,id_pterms,id_buyer FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_Supplier,alamat,terms_of_pay,supplier_code FROM mastersupplier
			)MSS ON MSS.Id_supplier = IH.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item

               INNER JOIN (
                    SELECT id_journal, reff_doc,date_journal FROM fin_journal_h WHERE type_journal = '1'
                )ji ON IAC.v_noinvoicecommercial = ji.reff_doc
                LEFT JOIN (
                		SELECT id,days_pterms FROM masterpterms
                )MPT ON IH.id_pterms = MPT.id						
                ) inv ON far.no_invoice = inv.v_noinvoicecommercial
            WHERE id_rekap = '$id';
        ";*/
		$sql = "SELECT   FH.id_journal
		,FH.reff_doc no_invoice
		,IH.id_buyer Id_Supplier
		,ID.amount
		#,(FD.nilai_journal - ifnull(POTONGAN.debit,0) ) n_amount
		,(FD.nilai_journal) n_amount
		,POTONGAN.debit
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		,POTONGAN.id_coa
		FROM fin_journal_h FH
		LEFT JOIN invoice_header IH ON FH.reff_doc = IH.invno
		LEFT JOIN(
			SELECT id_inv,SUM(ifnull(qty,0)*ifnull(price,0))amount,SUM(qty)jqty,MAX(id_so_det)id_so_det
			FROM invoice_detail GROUP BY id_inv
		)ID ON IH.id = ID.id_inv
		LEFT JOIN(
			SELECT SUM(ifnull(debit,0))nilai_journal,id_journal FROM fin_journal_d WHERE  id_coa NOT IN(SELECT disc_d FROM mapping_coa_sales) GROUP BY id_journal
		)FD On FD.id_journal = FH.id_journal
		LEFT JOIN so_det SOD ON ID.id_so_det = SOD.id
		LEFT JOIN so SO ON SO.id = SOD.id_so
		LEFT JOIN mastersupplier MS ON MS.Id_Supplier = IH.id_buyer
		LEFT JOIN fin_status_journal_ar AR ON AR.id_journal = FH.id_journal AND AR.no_invoice = IH.invno
		LEFT JOIN(SELECT id_journal
					,id_coa
					,debit
						FROM 
					fin_journal_d WHERE debit > 0 AND id_coa IN(SELECT disc_d FROM mapping_coa_sales) GROUP BY id_journal)POTONGAN
		ON POTONGAN.id_journal = FH.id_journal
WHERE FH.type_journal ='1' AND fg_post = '2' AND AR.id_rekap = '$id' GROUP BY AR.id_journal
		";
		
        return $this->query($sql)->result();
    }

    public function save($data)
    {
        $id_rekap = generate_rekap_id('AR');
        $sql = "
            INSERT INTO fin_status_journal_ar 
              (id_rekap, id_journal, no_invoice)
            VALUES 
        ";

        $detail = array();
        foreach($data as $d){
            $detail[] = "('{$id_rekap}', '{$d['id_journal']}', '{$d['no_invoice']}')";
        }
		
        $sql .= implode(', ', $detail);
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);

//        return $id_journal; //$this->conn->insert_id;
    }

    public function update($id_rekap, $data)
    {
		

        $this->query("
            DELETE FROM fin_status_journal_ar WHERE id_rekap = '$id_rekap'
        ");

        $sql = "
            INSERT INTO fin_status_journal_ar 
              (id_rekap, id_journal, no_invoice)
            VALUES 
        ";

        $detail = array();
        foreach($data as $d){
            $detail[] = "('{$id_rekap}', '{$d['id_journal']}', '{$d['no_invoice']}')";
        }

        $sql .= implode(', ', $detail);
		insert_log_nw($sql,$_SESSION['username']);
        return $this->query($sql);
    }
    public function get_master_supplier() 
    {
		$q="SELECT   FH.id_journal
		,FH.reff_doc
		,IH.id_buyer Id_Supplier
		,ID.amount
		,FD.nilai_journal n_amount
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		FROM fin_journal_h FH
		LEFT JOIN invoice_header IH ON FH.reff_doc = IH.invno
		LEFT JOIN(
			SELECT id_inv,SUM(ifnull(qty,0)*ifnull(price,0))amount,SUM(qty)jqty,MAX(id_so_det)id_so_det
			FROM invoice_detail GROUP BY id_inv
		)ID ON IH.id = ID.id_inv
		LEFT JOIN(
			SELECT SUM(ifnull(debit,0))nilai_journal,id_journal FROM fin_journal_d GROUP BY id_journal
		)FD On FD.id_journal = FH.id_journal
		LEFT JOIN so_det SOD ON ID.id_so_det = SOD.id
		LEFT JOIN so SO ON SO.id = SOD.id_so
		LEFT JOIN mastersupplier MS ON MS.Id_Supplier = IH.id_buyer
WHERE FH.type_journal ='1' AND fg_post = '2'
AND FH.id_journal NOT IN(SELECT id_journal FROM fin_status_journal_ar) GROUP BY IH.id_buyer";
		
        return $this->query($q)->result();
    }	

}

// CONTROLLER BEGIN

$M = new Model($con_new);
echo "<script>";
echo "tmp_s = [];";
echo "</script>";
$id = isset($_GET['id']) ? $_GET['id'] : '';
$ch = new Coa_helper();
$view = isset($_GET['view']) ? $_GET['view'] : '';

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit'])){

    $data = array();
    if($_POST['id_journal'] and @count($_POST['id_journal'])){
        foreach($_POST['id_journal'] as $k=>$v){
            $data[] = array(
                'id_journal' => $_POST['id_journal'][$k],
                'no_invoice' => $_POST['no_invoice'][$k]
            );
        }

        if($_POST['mode'] == 'save'){
            $M->save($data);
        }elseif($_POST['mode'] == 'update'){
            $M->update($id, $data);
        }
        echo "<script>window.location.href='?mod=rekarlist';</script>";exit();
    }else{
        echo '<script>alert("Rekap tidak boleh kosong!");</script>';
    }
}else{
    if($id){
        $rows = $M->get_rekap($id);
		//print_r($rows);
    }
}
$listSupplier = $M->get_master_supplier();
$list = $M->get_invoice_list();
//print_r($list);
//print_r($list);
// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <div class='form-group'>
                            <label>Nomor Rekap</label>
                            <input type='text' id='id_rekap' class='form-control' name='id_rekap' readonly
                                   placeholder='(Auto)' value='<?=(isset($id))?$id:''?>'>
                        </div>
                    </div>
                </div>


                <div class="col-md-12">
                    <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                    <button type='submit' name='submit' class='btn btn-primary'
					<?=$view =='1' ? 'style="display:none"' : '';?>
					
					>Simpan</button>
                </div>
            </div>
        </div>
    </div>


    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Rekap Entry</h3>
      </div>
      <div class="box-body">
        <table id="tbl2" class="display responsive" style="width:100%;font-size:12px;">
          <thead>
            <tr>
<!--                <th>No</th>-->
                <th>ID Journal</th>
                <th>Nomor Invoice</th>
<!--                <th>Mata Uang</th>-->
                <th>Nilai Invoice</th>
                <th>Qty Invoice</th>
                <th>Customer</th>
				  <th>Tanggal Jatuh Tempo</th>
                <th width='14%'>Action</th>
            </tr>
          </thead>
          <?php if(@count($rows)):?>
          <tbody>
            <?php $no=1;?>
            <?php foreach($rows as $l):?>
<?php 
					echo "<script>";
					echo "tmp_s.push(parseFloat('".$l->Id_Supplier."'))";
					echo "</script>";
?>			
            <tr>
<!--                <td>--><?//=$no++?><!--</td>-->
                <td><?=$l->id_journal?></td>
                <td><?=$l->no_invoice?></td>
                <td class=""><?=number_format($l->n_amount)?></td>
                <td class=""><?=number_format($l->jqty)?></td>
                <td><?=$l->Supplier?></td>
				 <td><?=$l->jatuh_tempo?></td>
                <td>
                    <input type="hidden" name="id_journal[]" value="<?=$l->id_journal?>" />
                    <input type="hidden" name="no_invoice[]" value="<?=$l->no_invoice?>" />
                    <a href="#" class="btn btn-danger btn-del"
						<?=$view =='1' ? 'style="display:none"' : '';?>
						
					>Remove</a>
                </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
      </div>
    </div>

</form>
<div class="box">
<div class="box-body">
<div class="col-md-3">
<!-- FILTER TABLE -->
<label>Nama Customer </label>
<select class="form-control select2" onchange="filter_table()" id="filter_supplier">
	<option value="">--Pilih Customer--</option>
	<?php 
		//$listSupplier;
		if(@count($listSupplier))
		foreach($listSupplier as $sup){
			echo "<option value='$sup->Supplier'>$sup->Supplier</option>";
		}
	?>
</select>
<!-- FILTER TABLE -->
</div>
</div>
</div>
<form id="form_detail" method='post' name='form' enctype='multipart/form-data' action=""  >
    <div class='box'>
        <div class="box-header">
            <h3 class="box-title">Pilih Entry</h3>
        </div>
        <div class='box-body'>
            <table id="tbl1" class="display responsive" style="width:100%;font-size:12px;">
                <thead>
                <tr>
                    <th>No</th>
                    <th>ID Journal</th>
                    <th>Nomor Invoice</th>
<!--                    <th>Mata Uang</th>-->
                    <th>Nilai Invoice</th>
                    <th>Qty Invoice</th>
                    <th>Customer</th>
					<th>Tanggal Jatuh Tempo</th>
                    <th width='14%'>Action</th>
                </tr>
                </thead>  
                <?php if(@count($list)):?>
                    <tbody>
                    <?php $no=1;?>
                    <?php foreach($list as $l):?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?=$l->id_journal?></td>
                            <td><?=$l->reff_doc?></td>

                            <td class=""><?=$l->curr." ".number_format($l->n_amount)?></td>
                            <td class=""><?=number_format($l->jqty)?></td>
                            <td><?=$l->Supplier?></td>
							<td><?=$l->jatuh_tempo?></td>
                            <td>
                                <a class="btn btn-primary btn-s btn-add" href="#"
									id="<?=str_replace('-','_',$l->id_journal)?>"  href="#"
                                   data-id_journal="<?=$l->id_journal?>"
								   data-idsupplier="<?php echo $l->Id_Supplier?>"
								   data-kodesupplier="<?php echo $lpo->supplier_code?>"
                                   data-no_invoice="<?=$l->reff_doc?>"
                                   data-amount="<?=number_format($l->n_amount)?>"
                                   data-qty="<?=number_format($l->jqty)?>"
                                   data-supplier="<?=$l->Supplier?>"
								   data-jatuh_tempo="<?=$l->jatuh_tempo?>"
                                   title="Add to rekap" 
								   <?=$view =='1' ? 'style="display:none"' : '';?>
								   
								   >Add</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                <?php endif;?>
            </table>
        </div>
    </div>
</form>



<script src="../../appr/js/ApprovalPage.js"></script>
<script>
    var tbl2;
    function validasi() {
        valid = true;

        return valid;
    }
	id_s = [];
	function checkSupplier(id){
		console.log(id);
		var key = 0;
		var count = id_s.length;
		console.log(id_s);
		console.log(id);
			for(var i=0;i<count;i++){
				if(id == id_s[i]){
					key = 0;
				}else{
					if(id_s[i] == 'XX'){
						key = 0;
					}else{
						key = 1;
					} 
				}
			}
			
		
		
		if(key > 0){
			alert("Supplier harus sama!");
			return false;
		}
		else{
			id_s.push(id);
			console.log(id_s);
			return true;
		}
	}
			
	
	
    $(document).ready(function(){
		console.log(tmp_s);
		id_s = tmp_s;
        $('.btn-add').click(function(){
			if($(this).is('[disabled=disabled]')){
				return false;
				alert("123");
			}			
			var idsupplier=$(this).data('idsupplier');
			if(!checkSupplier(idsupplier)){
				return false;
			};			
			$(this).attr('disabled',true);
            var id_journal = $(this).data('id_journal');
            var no_invoice = $(this).data('no_invoice');
            var amount = $(this).data('amount');
            var qty = $(this).data('qty');
			var jatuh_tempo = $(this).data('jatuh_tempo');
            var supplier = $(this).data('supplier');
			var rep_2 = id_journal.replace(/[^\w\s]/gi, '_')+'__R';
            var btn = '<input type="hidden" name="id_journal[]" value="'+id_journal+'" />'+
                '<input type="hidden" name="no_invoice[]" value="'+no_invoice+'" />'+
               '<a href="#" data-myids="'+idsupplier+'" id ="'+rep_2+'" class="btn btn-danger btn-del">Remove</a>';
            tbl2.row.add( [
                id_journal,
                no_invoice,
                amount,
                qty,
                supplier,
				jatuh_tempo,
                btn
            ] ).draw( false );
            return false;
        });



        tbl2 = $('#tbl2').DataTable
        ({  scrollY: "300px", 
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });
		
        tbl1= $('#tbl1').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 20,
            /*fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }*/
        });				
		
		
    });

				
    $(document).on( 'click', '.btn-del', function () {
		 var myids = $(this).data('myids');
		var idTmp = this.id;
			idTmp = idTmp.split("__R");
			console.log(idTmp);
			idTmp = idTmp[0];
			console.log(idTmp);
		$("#"+idTmp).removeAttr('disabled');
	
	   tbl2
         .row( $(this).parents('tr') )
         .remove()
         .draw();
		var key= id_s.indexOf(myids);
		id_s.splice(key, 1, 'XX');
		console.log(id_s);
        return false;
    } );
	function filter_table()
    {	
	var filter_supplier = $('#filter_supplier').val();
			tbl1.column(5).search(filter_supplier);
			 tbl1.draw();
    }		
</script>