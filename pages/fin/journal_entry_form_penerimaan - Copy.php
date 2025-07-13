<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$_SESSION['username']=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_P_Alokasi_AR","userpassword","username='$_SESSION[username]'");
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

    public function get_journal($id)
    {
		$q_allo_ar = GetQuery_Allokasi_AR("JOIN");
		$q = "SELECT BANK.deb,BANK.curr_bank,JOURNAL_AR.j_price,JOURNAL_AR.curr,jh.* FROM
            fin_journal_h jh
			LEFT JOIN (
				SELECT x.id_journal,x.type_journal,x.reff_doc,y.deb,curr_bank FROM fin_journal_h x LEFT JOIN
					(
						SELECT ifnull(sum(debit),0)deb,id_journal,curr curr_bank FROM fin_journal_d GROUP BY id_journal
					)y ON x.id_journal = y.id_journal WHERE x.type_journal = '13' 
				)BANK ON BANK.id_journal = jh.reff_doc		

			LEFT JOIN (
				$q_allo_ar
			)JOURNAL_AR ON jh.reff_doc2 = JOURNAL_AR.id_rekap
				
            WHERE jh.id_journal = '$id'";
//echo $q;
        return $this->query($q)->row();
    }

    public function get_journal_by_num($num)
    {
        return $this->query("
            SELECT * FROM
            fin_journal_h jh
            WHERE num_journal = '$num';
        ")->row();
    }

    public function get_journal_items($id)
    {
        return $this->query("
            SELECT * FROM (
                SELECT *, CASE WHEN credit = 0 THEN 0 ELSE 1 END ordering 
                FROM
                fin_journal_d jd
                WHERE id_journal = '$id'
            ) x
            ORDER BY ordering;
        ")->result();
    }

    public function check_bpb($id, $checking = TRUE){
        $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();

        if($checking) {
            if (!is_null($used)) {
                return array(
                    'status' => false,
                    'errcode' => '01',
                    'message' => 'BPB number already used'
                );
            }
        }

        $bpb = $this->query("SELECT * FROM bpb WHERE bpbno_int = '$id'")->result();

        if(!count($bpb)){
            return array(
                'status' => false,
                'errcode' => '02',
                'message' => 'BPB number not exist'
            );
        }

        $bpb_detail = $this->query("
            SELECT 
                bpb.bpbno
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,bpb.qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
                ,mg.id id_group
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
            WHERE 
                bpb.bpbno_int = '$id'
        ")->result();

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'BPB number found',
            'data'  => $bpb_detail
        );
    }

    public function save($d)
    {
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, reff_doc2, fg_intercompany, id_intercompany
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['reff_doc2']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}')
            ;
        ";

        $this->query($sql);

//        return $id_journal; //$this->conn->insert_id;
    }

    public function update($id, $data)
    {
        $sql = "
            UPDATE fin_journal_h SET 
              period = '{$data['period']}',
              num_journal = '{$data['num_journal']}',
              date_journal = '{$data['date_journal']}',
              type_journal = '{$data['type_journal']}',
              reff_doc = '{$data['reff_doc']}',
              reff_doc2 = '{$data['reff_doc2']}',
              fg_intercompany = '{$data['fg_intercompany']}',
              id_intercompany = '{$data['id_intercompany']}',
              dateedit = '{$data['dateedit']}',
              useredit = '{$data['useredit']}'
            WHERE id_journal = '$id';
        ";

        return $this->query($sql);
    }

    public function get_next_item_id($id)
    {
        $row = $this->query("
            SELECT IFNULL(MAX(row_id),0)+1 next_id FROM
            fin_journal_d jd
            WHERE id_journal = '$id';
        ")->row();

        return $row->next_id;
    }

    public function post_journal($id, $post)
    {
        $journal = (array) $this->get_journal($id);

        switch($journal['type_journal']){
            case Config::$journal_usage['purchase']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
                $bpb_request = $this->check_bpb($journal['reff_doc'], FALSE);

                if($bpb_request['errcode'] != '02'){
                    $this->insert_bpb_ir($journal['reff_doc'],$id);
                    /*
                    $data = array(
                        'company' => $journal['company'],
                        'period' => $journal['period'],
                        'num_journal' => '',//$journal['num_journal'],
                        'date_journal' => date('Y-m-d', time()),
                        'type_journal' => $journal['type_journal'],
//                    'reff_doc' => $journal['reff_doc'],
                        'reff_doc' => $id,
                        'fg_intercompany' => $journal['fg_intercompany'],
                        'id_intercompany' => $journal['id_intercompany'],
                    );

                    $journal_code = Config::$journal_code[$data['type_journal']];
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code);

                    $this->insert_bpb_ir($journal['reff_doc'],$data['id_journal']);

                    $data['dateadd'] = date('Y-m-d H:i:s', time());
                    $data['useradd'] = $_SESSION['username'];
                    $this->save($data);*/
                }
                break;
            default:
        }

        $sql = "
            UPDATE fin_journal_h SET 
              fg_post = '2',
              date_post = '{$post['date_post']}',
              user_post = '{$post['user_post']}'
            WHERE id_journal = '$id';
        ";

        return $this->query($sql);
    }

    public function get_cost_center_list()
    {
        $sql = "
            SELECT 
                mc.id_costcenter
                ,mc.nm_costcenter
                ,mcc.id_cost_category
                ,mcc.nm_cost_category
                ,mcd.id_cost_dept
                ,mcd.nm_cost_dept
                ,mcsd.id_cost_sub_dept
                ,mcsd.nm_cost_sub_dept
            FROM
                mastercostcenter mc
                INNER JOIN mastercostcategory mcc ON mc.id_cost_category = mcc.id_cost_category
                INNER JOIN mastercostdept mcd ON mc.id_cost_dept = mcd.id_cost_dept
                INNER JOIN mastercostsubdept mcsd ON mc.id_cost_sub_dept = mcsd.id_cost_sub_dept
        ";
        return $this
            ->query($sql)
            ->result();
    }

    public function get_coa_list($key='')
    {
        $sql = "
            SELECT 
                mc.id_coa
                ,mc.nm_coa
            FROM mastercoa mc
            WHERE 1=1
                AND mc.fg_active = '1'
                AND mc.fg_posting = '1'
                {WHERE}
        ";

        $where = '';
        if($key){
            $where .= "AND mc.id_coa LIKE '$key%' ";
        }
        $sql = str_replace('{WHERE}', $where, $sql);

        return $this
            ->query($sql)
            ->result();
    }

    public function get_accounting_period()
    {
        //TODO: fetch from actual table
        return array(
            date('m/Y', strtotime(date('Y-m-d', time()).' -1 month')),
            date('m/Y', time()),
            date('m/Y', strtotime(date('Y-m-d', time()).' +1 month')),
        );
    }
    public function save_penerimaan_detail($id_journal, $data)
    {
        $id_jurnal_bank_masuk = $data['reff_doc'];
        $sql = "
            SELECT 
                fjd.*
            FROM fin_journal_h fjh
                INNER JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal
            WHERE (fjh.type_journal = '11' OR fjh.type_journal = '13')
              AND fjd.debit > 0
              AND fjh.id_journal = '$id_jurnal_bank_masuk'
        ";

        $bank_masuk = $this->query($sql)->result();
		$amnt_bank = $bank_masuk[0]->debit;
//echo "BANK:$amnt_bank";
        $id_rekap = $data['reff_doc2'];
		$q_allo_ar =GetQuery_Allokasi_AR("JOIN");
        $sql = "SELECT   FH.id_journal
		,FH.reff_doc no_invoice
		,IH.id_buyer
		,ID.amount
		,(FD.nilai_journal - ifnull(POTONGAN.debit,0) ) n_amount
		,ifnull(POTONGAN.debit,0)pph
		,SO.curr
		,ID.id_so_det
		,ID.jqty
		,MS.Supplier
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		,POTONGAN.id_coa
		,MS.vendor_cat
		,MS.area
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
WHERE FH.type_journal ='1' AND fg_post = '2' AND AR.id_rekap = '$id_rekap' GROUP BY AR.id_journal";

        $invoices = $this->query($sql)->result();
        $dataInvoice = array();
		echo "<pre>";
		//print_r($invoices);
		//echo "</pre>";
		//die();
        $map_uang_muka = array();
        $_map_uang_muka = $this->query("SELECT DISTINCT mcs.area, mcs.um_k, mc.nm_coa FROM mapping_coa_sales mcs LEFT JOIN mastercoa mc ON mcs.um_k = mc.id_coa")->result();
        foreach($_map_uang_muka as $_mum){
            $map_uang_muka[$_mum->area] = array(
                'id_coa' => $_mum->um_k, 
                'nm_coa' => $_mum->nm_coa,
            ); 
        }

        $row_id = 1;
        foreach($bank_masuk as $r){
            $row = (array) $r;
            $row['row_id'] = $row_id++;
            $row['id_journal'] = $id_journal;
            if($r->curr == 'IDR'){
                $row['id_coa'] = $map_uang_muka['L']['id_coa'];
                $row['nm_coa'] = $map_uang_muka['L']['nm_coa'];
            }else{
                $row['id_coa'] = $map_uang_muka['I']['id_coa'];
                $row['nm_coa'] = $map_uang_muka['I']['nm_coa'];
            }
            $dataInvoice[] = $row;
        }

        $dateadd = date('Y-m-d H:i:s', time());
        $_SESSION['username'] = $_SESSION['username'];
		$pph_value = 0;
        foreach($invoices as $_bpb){
            $_bpb = (array) $_bpb;
			$pph_value = $pph_value + $_bpb['pph'];
			$total_nilai = $_bpb['n_amount'];
			$j_price = $_bpb['n_amount'];
			$curr= $_bpb['curr'];
            //$amount = $_bpb['j_price'] * $_bpb['jqty'];
            list($coa_debit, $coa_credit) = $this->get_id_coa_from_invoices($_bpb);
            // GR
				$_credit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $_bpb['curr'],
                'debit' => 0,
                'credit' => $_bpb['n_amount'],
                'description' => $_bpb['no_invoice'],//.' - '.$_bpb['product_group'].' - '.$_bpb['product_item'],
                'dateadd' => $dateadd,
                'useradd' => $_SESSION['username'],
            );
            // Piutang
			$dataInvoice[] = $_credit;

        }
           // $dataInvoice[] = $_debit;
            //$dataInvoice[] = $_credit;
			if($pph_value != '0'){
				$_debit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => '15202',
                'nm_coa' => 'PAJAK DIBAYAR DIMUKA PPH PASAL 23',
                'curr' => $curr,
                'debit' => $pph_value,
                'credit' => 0,
                'description' => PP,//.' - '.$_bpb['product_group'].' - '.$_bpb['product_item'],
                'dateadd' => $dateadd,
                'useradd' => $_SESSION['username'],
            );
			$dataInvoice[] = $_debit;
			$dataInvoice[0]['debit'] = $amnt_bank - $pph_value;
			 //$dataInvoice[0]['debit'] = $amn_after_tax ;
			}
		/*	if($amnt_bank != $j_price){
				$selisih_bank = $j_price - $amnt_bank;
				if($selisih_bank < 0){
					$selisih_bank = ($selisih_bank * -1);
				}
				
				$_debit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => '85101',
                'nm_coa' => 'BEBAN ADMINISTRASI BANK',
                'curr' => $curr,
                'debit' => $selisih_bank,
                'credit' => 0,
                'description' => PP,//.' - '.$_bpb['product_group'].' - '.$_bpb['product_item'],
                'dateadd' => $dateadd,
                'useradd' => $_SESSION['username'],
            );				
				
				
			}		
*/			
			//$dataInvoice[] = $_debit;
/* echo "<pre>";
print_r($dataInvoice);
echo "</pre>";
die();			 */

			//$dataInvoice[0]['debit'] = $amnt_bank - $pph_value;
			//print_r($dataInvoice);
			//die();
        foreach($dataInvoice as $_bpb){
            $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
            // Insert detail
            $this->query($sql);
        }
    }
    public function get_id_coa_from_invoices($bpb){

        $id_group = $bpb['product_group'];
        $id_group = '';//override temporary

        //Default to lokal
        $bpb['area'] =  $bpb['area'] == 'I'?'I':'L'  ;

        $sql = "
            SELECT mp.disc_d,mp.sl_d, mcd.nm_coa nm_coa_d, mp.sl_k, mck.nm_coa nm_coa_k
            FROM mapping_coa_sales mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.sl_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.sl_k
            WHERE 1=1
            and vendor_cat = '{$bpb['vendor_cat']}' and area = '{$bpb['area']}'
        ";

        $mapping_coa = $this->query($sql)->row();

        $coa_debit = array(
            'id' => $mapping_coa->sl_d,
            'nm' => $mapping_coa->nm_coa_d
        );
        $coa_credit = array(
            'id' => $mapping_coa->sl_k,
            'nm' => $mapping_coa->nm_coa_k
        );

        return array(
            $coa_debit,
            $coa_credit
        );
    }

}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();

$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit']) or isset($_POST['posting'])){
    $company = $M->get_master_company();
    $data = array(
        'company' => $company->company,
        'period' => $_POST['period'],
        'num_journal' => '',//$_POST['num_journal'],
        'date_journal' => date('Y-m-d', strtotime($_POST['date_journal'])),
        'type_journal' => $_POST['type_journal'],
        'reff_doc' => $_POST['reff_doc'],
        'reff_doc2' => $_POST['reff_doc2'],
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
    );

    if($_POST['mode'] == 'save'){
        $data['dateadd'] = date('Y-m-d H:i:s', time());
        $data['useradd'] = $_SESSION['username'];
        $journal_code = Config::$journal_code[$data['type_journal']];

        $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
        $M->save_penerimaan_detail($data['id_journal'],$data);
        $M->save($data);
        echo "<script>window.location.href='?mod=jallocar&id={$data['id_journal']}';</script>";exit();
    }elseif($_POST['mode'] == 'update'){
        $journal = $M->get_journal($id);
        if($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])){
            echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
            $status = false; // Num journal already exists
            $row = json_decode(json_encode($data));
        }else{
            $data['dateedit'] = date('Y-m-d H:i:s', time());
            $data['useredit'] = $_SESSION['username'];
            $status = $M->update($id, $data);

            if(isset($_POST['posting'])){
                //TODO: Posting validation
                $data = array(
                    'date_post' => date('Y-m-d H:i:s', time()),
                    'user_post' => $_SESSION['username']
                );
                $M->post_journal($id, $data);

            }
            echo "<script>window.location.href='?mod=jallocar';</script>";exit();
//            echo "<script>window.location.href='?mod=jefh&id=$id';</script>";exit();
        }
    }

    if($status){
        $id = '';
    }
}else{
    if($id){
        $row = $M->get_journal($id);
        $list = $M->get_journal_items($id);
        $t_debit = $t_credit = 0;
        if(count($list)){
            foreach($list as $l){
                $t_debit += $l->debit;
                $t_credit += $l->credit;
            }
        }
    }
}

$acct_period = $M->get_accounting_period();


// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group' style="display:none">
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker_' readonly class='form-control' name='period' autocomplete="off"
                               placeholder='MM/YYYY' value='<?=isset($row)?$row->period:''?>'>
                        <?php /*<select id='period' class='form-control select2' name='period'>
                            <option value="" disabled selected>Pilih Periode</option>
                            <?php if(@$row->fg_post):?>
                                <option value="<?=$row->period?>" selected><?=$row->period?></option>
                            <?php else:?>
                                <?php if(@$row->period):?>
                                    <option value="<?=$row->period?>" selected><?=$row->period?></option>
                                <?php endif;?>
                            <?php foreach($acct_period as $_id):?>
                                <option value="<?=$_id?>" <?php //=(@$row->period==$_id) ? 'selected':''?> ><?=$_id?></option>
                            <?php endforeach;?>
                            <?php endif;?>
                        </select>*/?>
                    </div>
                    <div class='form-group'>
                        <label>Tipe Jurnal</label>

                  <select id='type_journal' name="type_journal" class='form-control' onchange="filter_table()" readonly>
                      <option value="<?=Config::$journal_usage['RECEIPT']?>" ><?=Config::$journal_type[Config::$journal_usage['RECEIPT']]?></option>
                  </select>


                    </div>
                </div>
                 <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' id='datepicker1' onchange="getPeriodAccounting(this)"  class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Dokumen' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Jurnal Bank Masuk</label>
                         <div class="input-group">
                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>'>
                         <a onclick="modal_ref()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>
                     <div class='form-group'>
                         <label>Rekap AR</label>
                         <div class="input-group">
                             <input type='text' id='reff_doc2' class='form-control' name='reff_doc2'
                                    placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc2:''?>'>
                             <a onclick="modal_ref2()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>
                </div>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <input type='hidden' id='' name='fg_intercompany' value="0">
                        <label><input type='checkbox' id='fg_intercompany' class='' onclick="toggleInterCompany()" name='fg_intercompany' value="1"
                                <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'checked':''?>> Inter-company</label>
                        <input type='text' id='id_intercompany' class='form-control' name='id_intercompany'
                               placeholder='IC Partner' value='<?=isset($row)?$row->id_intercompany:''?>'
                            <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'':'readonly'?>>
                    </div>
                    <div class='form-group '>
                        <label>Nilai Penerimaan</label>
                        <input type='text' id='nilai' value='<?=isset($row)?$row->curr_bank.' '.number_format($row->deb,2,'.',','):''?>' class='form-control text-right' name='nilai'
                               placeholder='Nilai Penerimaan' readonly>
                    </div>
                    <div class='form-group '>
                        <label>Nilai Invoice</label>
                        <input type='text' id='nilai_inv' value='<?=isset($row)?$row->curr.' '.number_format($row->j_price,2,'.',','):''?>' class='form-control text-right' name='nilai_inv' placeholder='Nilai Invoice' readonly>
                    </div>
                </div>
                <?php if($id):?>
                    <div class='col-md-3'>
                        <div class='form-group'>
                            <label>Status Posting: </label><br><?=(isset($row) and $row->fg_post)? 'POSTED':'PARKED'?><br>
                            <label>Create date: </label><br>
                            <?=($row->dateadd) ? $row->dateadd.' ('.$row->useradd.')' : '-'?><br>
                            <label>Edit date: </label><br>
                            <?=($row->dateedit) ? $row->dateedit.' ('.$row->useredit.')' : '-'?><br>
                            <label>Post date: </label><br>
                            <?=($row->date_post) ? $row->date_post.' ('.$row->user_post.')' : '-'?>
                        </div>
                    </div>
                <?php endif;?>
                <div class="col-md-12">
                    <input type='hidden' name='mode' value='<?=$id ? 'update' : 'save';?>'>
                    <?php if(!isset($row) or ($id==false)):?>
                    <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <button type='submit' name='submit' class='btn btn-primary'>Simpan</button>
                    <button type='submit' name='posting' class='btn btn-primary'>Posting</button>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if($id):?>
    <?php include("journal_entry_form_item.inc.php"); ?>
<?php endif;?>

<div class="modal fade" id="modalBpb"  role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Bank Masuk</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No Journal</label>
                            <input type='text' class='form-control' id="modal_id_journal" >
                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label><br>
                            <a onclick="lookup_bpb()" class="btn btn-primary">Lookup</a>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-12">
                            <em>Gunakan % untuk pencarian wildcard</em>
                        </div>
                    </div>-->
                </div>
                <div class="bpbresult">
                    <table class="table">
                        <thead>
                            <th>No Journal</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Action</th>
                        </thead>
                        <hr>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRekap"  role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Rekap</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No Rekap</label>
                            <input type='text' class='form-control' id="modal_id_rekap" >
                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label><br>
                            <a onclick="lookup_rekap()" class="btn btn-primary">Lookup</a>
                        </div>
                    </div>
                    <!--<div class="row">
                        <div class="col-md-12">
                            <em>Gunakan % untuk pencarian wildcard</em>
                        </div>
                    </div>-->
                </div>
                <div class="bpbresult">
                    <table class="table">
                        <thead>
                        <th>No Rekap</th>
                        <th>Invoice</th>
                        <th>Nilai</th>
                        <th>Qty</th>
                        <th>Action</th>
                        </thead>
                        <hr>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    function modal_ref(){
        var tipe_jurnal = $('#type_journal').val();
        switch (tipe_jurnal){
            case "4":
                modal_bpb();
                break;
            default:
                if(tipe_jurnal==""){
                    alert("Pilih tipe jurnal terlebih dahulu");;
                }else {
                    alert("Pilihan tidak tersedia untuk tipe jurnal tersebut");
                }
        }
    }
    function modal_ref2(){
        $('#modalRekap').modal('show');
        $("#modalRekap .bpbresult table tbody").html('');
//        $("#bpbNo").val('');
//        $("#bpbNoInternal").val('');
    }
    function modal_bpb(){
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
    }
    function lookup_bpb(){
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_bank_masuk",
            data: "id_journal=" +$("#modal_id_journal").val(),
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        var invoice_list = [];
        if(request.status == true){
            invoice_list = request.data;
            var rows = '';
            if(invoice_list.length) {
                for (var i in invoice_list) {
					$priceBank = invoice_list[i].curr+" "+number_format(invoice_list[i].amount);
                    rows += '<tr>';
					rows += '<td>' + invoice_list[i].id_journal + '</td>';
                    rows += '<td>' + invoice_list[i].date_journal + '</td>';
					rows += '<td>' + $priceBank + '</td>';
                    rows += '<td><a onclick="select_bpb(' + "'" + invoice_list[i].id_journal + "'" + ', ' + "'" + number_format(invoice_list[i].amount)  + "'" +')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Journal not found--</em></td></tr>';
            }
            $("#modalBpb .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
    }
    function select_bpb(bpb,amount){
        $('#reff_doc').val(bpb);
        $('#nilai').val(amount);
        $('#modalBpb').modal('hide');
    }
    function lookup_rekap(){
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_rekap_ar",
            data: "id_rekap=" +$("#modal_id_rekap").val(),
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        var invoice_list = [];
        if(request.status == true){
            invoice_list = request.data;
            var rows = '';
            if(invoice_list.length) {
                for (var i in invoice_list) {
					$myPrice = invoice_list[i].curr +" "+ invoice_list[i].j_price;
                    rows += '<tr>';
                    rows += '<td>' + invoice_list[i].id_rekap + '</td>';
                    rows += '<td>' + invoice_list[i].invoices + '</td>';
                    rows += '<td>' + $myPrice + '</td>';
                    rows += '<td>' + invoice_list[i].jqty + '</td>';
                    rows += '<td><a onclick="select_rekap(' + "'" + invoice_list[i].id_rekap + "'" + ', ' + "'" + $myPrice + "'" + ')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Journal not found--</em></td></tr>';
            }
            $("#modalRekap .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
    }
    function select_rekap(id_rekap, nilai_inv){
        $('#reff_doc2').val(id_rekap);
        $('#nilai_inv').val(nilai_inv);
        $('#modalRekap').modal('hide');
    }
    function toggleInterCompany(){
        if($('#fg_intercompany').is(':checked')){
            $('#id_intercompany').attr('readonly', false);
        }else{
            $('#id_intercompany').attr('readonly', true);
        }
    }
    function validasi() {
        var period = document.form.period.value;
        var type_journal = document.form.type_journal.value;
        var num_journal = document.form.num_journal.value;
        var date_journal = document.form.date_journal.value;
        var reff_doc = document.form.reff_doc.value;
        if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
            document.form.period.focus();
            valid = false;
        } else if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
            document.form.type_journal.focus();
            valid = false;
        } /*else if (num_journal == '') {
            alert('Error: Nomor Dokumen tidak boleh kosong');
            document.form.num_journal.focus();
            valid = false;
        }*/ else if (date_journal == '') {
            alert('Error: Tanggal Dokumen tidak boleh kosong');
            document.form.date_journal.focus();
            valid = false;
        }
        <?php if(isset($t_debit)):?>
        else if ((<?=@$t_debit?> + <?=@$t_credit?>) == 0) {
            alert('Error: Nilai Debit dan Kredit tidak boleh kosong');
            valid = false;
        } else if (<?=@$t_debit?> != <?=@$t_credit?>) {
            alert('Error: Debit dan Kredit harus balance');
            valid = false;
        }
        <?php endif;?>
        else if (<?=(isset($row) and isset($row->fg_post) and $row->fg_post)?'true':'false'?>) {
            alert('Error: Journal sudah di posting');
            valid = false;
        } else valid = true;

        return valid;
    }


    function load_reference_populate(){
        var bpbno = $('#reff_doc').val();
        if(!bpbno){
            return false;
        }
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_bank_masuk",
            data: "id_journal=" +bpbno,
            dataType: "json",
            async: false
        }).responseText;
        request = JSON.parse(request);
        if(request.status == true) {
            var bpb = request.data[0];
            $('#nilai').val(bpb.amount);
        }else{
//            alert("Error: BPB not available");
        }
	}

 function load_reference_populate_inv(){
	 
        var bpbno = $('#reff_doc2').val();
		//alert(bpbno);
        if(!bpbno){
            return false;
        }
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_rekap_ar",
            data: "id_rekap=" +bpbno,
            dataType: "json",
            async: false
        }).responseText;
        request = JSON.parse(request);
		console.log(request);
        if(request.status == true) {
            var bpb = request.data[0];
            $('#nilai_inv').val(bpb.j_price);
        }else{
//            alert("Error: BPB not available");
        }
    }


    $(document).ready(function(){
       // load_reference_populate();
		load_reference_populate_inv();
    });
</script>