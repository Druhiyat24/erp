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
        $list = $this->query("
            SELECT far.*, inv.j_price, inv.jqty, inv.Supplier, inv.id_journal, inv.supplier_code,inv.n_amount,inv.edit
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
		,BPB.jqty
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSS.supplier_code
		,MSST.goods_code
		,ID.j_carton
		,SO.amount
		,SO.buyerno
		,SO.fob
		,DATE_ADD(ji.date_journal, INTERVAL MPT.days_pterms DAY) as  jatuh_tempo
        ,ji.id_journal
        ,ji.reff_doc
		,ji.date_journal
		,MPT.days_pterms
		,(SELECT ifnull(count(reff_doc),0) FROM fin_journal_h WHERE reff_doc = IH.invno AND type_journal IN('1','13')  )edit 
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
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,id_pterms FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay,supplier_code FROM mastersupplier
			)MSS ON MSS.Id_supplier = ACT.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item

               INNER JOIN (
                    SELECT id_journal, reff_doc,date_journal FROM fin_journal_h WHERE type_journal = '1'
                )ji ON IAC.v_noinvoicecommercial = ji.reff_doc
                LEFT JOIN (
                		SELECT id,days_pterms FROM masterpterms
                )MPT ON IH.id_pterms = MPT.id						
                ) inv ON far.no_invoice = inv.v_noinvoicecommercial;
        ")->result();

        if(!count($list)){
            return array();
        }

        $hash = array();
        foreach($list as $l){
            $hash[$l->id_rekap]['id_rekap'] = $l->id_rekap;
            $hash[$l->id_rekap]['tgl_rekap'] = $l->tgl_rekap;
            if(!isset($hash[$l->id_rekap]['invoices'])){
                $hash[$l->id_rekap]['invoices'] = $l->no_invoice;
                $hash[$l->id_rekap]['price'] = number_format($l->n_amount);
                $hash[$l->id_rekap]['supplier'] = $l->Supplier;
                $hash[$l->id_rekap]['supplier_code'] = $l->supplier_code;
            }else{
                $hash[$l->id_rekap]['invoices'] .= '<br>'.$l->no_invoice;
                $hash[$l->id_rekap]['price'] .= '<br>'.number_format($l->n_amount);
                $hash[$l->id_rekap]['supplier'] .= '<br>'.$l->Supplier;
                $hash[$l->id_rekap]['supplier_code'] .= '<br>'.$l->supplier_code;
            }
        }
        return $hash;
    }
    public function get_master_supplier()
    {
        return $this->query("
            SELECT DISTINCT(inv.Supplier)Supplier
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
		,BPB.jqty
		,BPB.j_price
		,BPB.id_supplier
		,BPB.nomor_mobil
		,ACT.styleno
		,ACT.id_buyer
		,MSM.shipdesc
		,MSS.Supplier
		,MSS.terms_of_pay
		,MSS.alamat
		,MSST.itemname
		,MSST.color
		,MSS.supplier_code
		,MSST.goods_code
		,ID.j_carton
		,SO.amount
		,SO.buyerno
		,SO.fob
		,DATE_ADD(ji.date_journal, INTERVAL MPT.days_pterms DAY) as  jatuh_tempo
        ,ji.id_journal
        ,ji.reff_doc
		,ji.date_journal
		,MPT.days_pterms
		,(SELECT ifnull(count(reff_doc),0) FROM fin_journal_h WHERE reff_doc = IH.invno AND type_journal IN('1','13')  )edit 
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
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper,id_pterms FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay,supplier_code FROM mastersupplier
			)MSS ON MSS.Id_supplier = ACT.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item

               INNER JOIN (
                    SELECT id_journal, reff_doc,date_journal FROM fin_journal_h WHERE type_journal = '1'
                )ji ON IAC.v_noinvoicecommercial = ji.reff_doc
                LEFT JOIN (
                		SELECT id,days_pterms FROM masterpterms
                )MPT ON IH.id_pterms = MPT.id						
                ) inv ON far.no_invoice = inv.v_noinvoicecommercial;
        ")->result();
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
                <td><?php 
					if($l['edit'] == "0" ){
						?>
						
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