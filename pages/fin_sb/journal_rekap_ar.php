<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI  
$akses = flookup("F_P_Rekap_AR","userpassword","username='$user'");
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
    public function get_rekap_ar_list()
    {
		$q = "SELECT  FH.id_journal
		,FH.reff_doc no_invoice
		,REFF.id_journal penerimaan
		,AR.id_rekap
		,AR.tgl_rekap
		,IH.id_buyer
		,ID.amount
		,SUM((FD.nilai_journal - ifnull(POTONGAN.debit,0) )) n_amount
		,POTONGAN.debit
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		,POTONGAN.id_coa
		,(SELECT count(*) FROM fin_journal_h WHERE reff_doc2 = AR.id_rekap)edit 
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
		INNER JOIN fin_status_journal_ar AR ON AR.id_journal = FH.id_journal AND AR.no_invoice = IH.invno
		LEFT JOIN(SELECT id_journal
					,id_coa
					,debit
						FROM 
					fin_journal_d WHERE debit > 0 AND id_coa IN(SELECT disc_d FROM mapping_coa_sales) GROUP BY id_journal)POTONGAN
		ON POTONGAN.id_journal = FH.id_journal
		LEFT JOIN(SELECT id_journal,reff_doc2,type_journal FROM fin_journal_h WHERE type_journal IN('4','13'))REFF ON REFF.reff_doc2 = AR.id_rekap
WHERE FH.type_journal ='1' AND fg_post = '2' GROUP BY AR.id_rekap";
        $list = $this->query($q)->result();

        if(!count($list)){
            return array();
        }

        $hash = array();
        foreach($list as $l){
            $hash[$l->id_rekap]['id_rekap'] = $l->id_rekap;
            $hash[$l->id_rekap]['tgl_rekap'] = $l->tgl_rekap;
            if(!isset($hash[$l->id_rekap]['invoices'])){
                $hash[$l->id_rekap]['invoices'] = $l->no_invoice;
                $hash[$l->id_rekap]['price'] = $l->curr." ".number_format($l->n_amount, 2, '.', ','); 
                $hash[$l->id_rekap]['supplier'] = $l->Supplier;
                $hash[$l->id_rekap]['supplier_code'] = $l->supplier_code;
				 $hash[$l->id_rekap]['penerimaan'] = $l->penerimaan;
				 $hash[$l->id_rekap]['edit'] = $l->edit;
            }else{
                $hash[$l->id_rekap]['invoices'] .= '<br>'.$l->no_invoice;
                $hash[$l->id_rekap]['price'] .= '<br>'.number_format($l->n_amount);
                $hash[$l->id_rekap]['supplier'] .= '<br>'.$l->Supplier;
                $hash[$l->id_rekap]['supplier_code'] .= '<br>'.$l->supplier_code;
				$hash[$l->id_rekap]['penerimaaan'] .= '<br>'.$l->penerimaaan;
				$hash[$l->id_rekap]['edit'] = $l->edit;
            }
        }
        return $hash;
    }
    public function get_master_supplier()
    {
		$q="SELECT   
		AR.id_rekap
		,FH.id_journal
		,FH.reff_doc
		,IH.id_buyer
		,IH.invno no_invoice
		,ID.amount
		,SUM(ID.amount) amount_ori
		,SUM(FD.nilai_journal) n_amount
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,MS.supplier_code
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		,(SELECT count(*) FROM fin_journal_h WHERE reff_doc2 = AR.id_rekap)edit 
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
		INNER JOIN fin_status_journal_ar AR ON AR.id_journal = FH.id_journal AND AR.no_invoice = IH.invno
WHERE FH.type_journal ='1' AND fg_post = '2' GROUP BY IH.id_buyer";
        return $this->query($q)->result();
    }	
}

// CONTROLLER BEGIN

$M = new Model($con_new);
$listSupplier = $M->get_master_supplier();
$list = $M->get_rekap_ar_list();
//print_r($list);
// CONTROLLER END


?>
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
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Rekap Entry</h3>
          <div class="pull-right">
              <a href="?mod=rekar" class="btn btn-primary">Create Rekap AR</a>
          </div>
      </div>
      <div class="box-body">
        <table id="tbl1" class="display responsive" style="width:100%;font-size:12px;">
          <thead>
            <tr>
                <th>No</th>
                <th>Nomor Rekap</th>
                <th>Tgl Rekap</th>
                <th>Invoice</th>
                <th>Amount</th>
                <th>Customer</th>
                <th>Customer Code</th>
				<th>Penerimaan</th>
                <th width='14%'>Action</th>
            </tr>
          </thead>
          <?php if(@count($list)):?>
          <tbody>
            <?php $no=1;?>
            <?php foreach($list as $l):?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$l['id_rekap']?></td>
                <td><?=date('d F Y', strtotime($l['tgl_rekap']))?></td>
                <td><?=$l['invoices']?></td>
                <td><?=$l['price']?></td>
                <td><?=$l['supplier']?></td>
                <td><?=$l['supplier_code']?></td>
				<td><?=$l['penerimaan']?></td>
                <td>
					<a href="?mod=rekar&id=<?=$l['id_rekap']?>&view=1" class="btn btn-info" title="detail" ><i class='fa fa-info'></i></a>
				
				<?php 

				
					if($l['edit'] == "0" ){
						?>
					<a href="#" class="btn btn-danger" onclick="executedAR('<?=$l['id_rekap']?>','DELETE')"  title="delete"><i class='fa fa-trash'></i></a>						
						<a href="?mod=rekar&amp;id=<?=$l['id_rekap']?>" class="btn btn-default">Edit</a>
					<?php	
					}
					?>
				
	
                    
                </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
        </table>
      </div>
    </div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script src="js/ListPayment.js"></script>
	<script>
    $(document).ready(function(){
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
	function filter_table()
    {	
	var filter_supplier = $('#filter_supplier').val();
			tbl1.column(5).search(filter_supplier);
			 tbl1.draw();
    }			
</script>