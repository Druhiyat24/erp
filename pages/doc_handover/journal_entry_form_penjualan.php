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

    public function get_journal($id)
    {
        return $this->query("
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
		,MSST.goods_code
		,ID.j_carton
		,SO.buyerno
		,SO.fob
		,JH.reff_doc
		,JHD.v_fakturpajak
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
				SELECT invno,id,shipped_by,ship_to,measurement,etd,eta,nw,gw,shipper FROM invoice_header
			)IH ON IAC.n_idinvoiceheader =  IH.id
			LEFT JOIN(
				SELECT SUM(price) j_price,SUM(qty) jqty,SUM(berat_bersih) j_bersih,SUM(berat_kotor) j_kotor,invno,max(id_supplier) id_supplier,nomor_mobil,styleno,bpbno,bpbno_int,id_item FROM bpb GROUP BY bpbno_int,bpbno
			)BPB ON IAC.bpbno = BPB.bpbno
			LEFT JOIN(
				SELECT id,shipdesc FROM mastershipmode
			) MSM ON IH.shipped_by = MSM.id
			LEFT JOIN(
				SELECT Supplier,Id_supplier,alamat,terms_of_pay FROM mastersupplier
			)MSS ON MSS.Id_supplier = ACT.id_buyer
			LEFT JOIN(
				SELECT id_item,itemname,Color,goods_code FROM masterstyle 
			)MSST ON MSST.id_item = BPB.id_item
			LEFT JOIN (
				SELECT id_journal,reff_doc FROM fin_journal_h
			)JH ON JH.reff_doc = IH.invno
			LEFT JOIN(
				SELECT v_idjournal,v_fakturpajak FROM fin_journalheaderdetail
			)JHD ON JHD.v_idjournal= JH.id_journal

			WHERE JH.reff_doc   = '$id';
        ")->row();
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

    public function check_inv($id, $checking = TRUE){
        $used = $this->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();

        if($checking) {
            if (!is_null($used)) {
                return array(
                    'status' => false,
                    'errcode' => '01',
                    'message' => 'INV number already used'
                );
            }
        }

        $inv = $this->query("SELECT * FROM invoice_header WHERE invno = '$id'")->result();

        if(!count($inv)){
            return array(
                'status' => false,
                'errcode' => '02',
                'message' => 'INV number not exist'
            );
        }

        $detail = $this->query("
            SELECT 
                ih.invno
                ,ih.invdate
                ,(id.qty * id.price) invoice_amount
                ,so.curr
                ,ms.supplier
                ,ms.vendor_cat
                ,ms.area
                ,CONCAT(mp.product_item,' - ', sd.color, ' - ',sd.size) description
            FROM 
            invoice_header ih
            INNER JOIN invoice_detail id ON ih.id = id.id_inv
            LEFT JOIN so_det sd ON sd.id = id.id_so_det
            LEFT JOIN so ON so.id = sd.id_so
            LEFT JOIN act_costing ac ON so.id_cost = ac.id
            LEFT JOIN masterproduct mp ON ac.id_product = mp.id
            LEFT JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
            WHERE 
                ih.invno = '$id'
        ")->result();

        return array(
            'status' => true,
            'errcode' => null,
            'message' => 'INV number found',
            'data'  => $detail
        );
    }

    public function save($d)
    {
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, fg_intercompany, id_intercompany
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
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
            case Config::$journal_usage['SALES']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
                $bpb_request = $this->check_inv($journal['reff_doc'], FALSE);

                if($bpb_request['errcode'] != '02'){
//                    $this->insert_bpb_ir($journal['reff_doc'],$id);
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

    public function get_id_coa_sales_from_inv($inv){
        // TODO: filter by sales category. currently there is no sales category available

        $inv['area'] = $inv['area']=='I'?:'L';//Import / Local
        $mapping_coa = $this->query("
            SELECT mp.sl_d, mcd.nm_coa nm_coa_d, mp.sl_k, mck.nm_coa nm_coa_k
            FROM mapping_coa_sales mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.sl_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.sl_k
            WHERE 
            area = '{$inv['area']}' and vendor_cat = '{$inv['vendor_cat']}' 
        ")->row();

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



    public function insert_inv_sales($invno, $id_journal)
    {
        $bpb_request = $this->check_inv($invno);

        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $user = $_SESSION['username'];
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
                $amount = $_bpb['invoice_amount'];
                list($coa_debit, $coa_credit) = $this->get_id_coa_sales_from_inv($_bpb);

                // Piutang
                $_debit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_debit['id'],
                    'nm_coa' => $coa_debit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => $_bpb['description'],
                    'dateadd' => $dateadd,
                    'useradd' => $user,
                );
                // Sales
                $_credit = array(
                    'row_id' => $row_id++,
                    'id_journal' => $id_journal,
                    'id_coa' => $coa_credit['id'],
                    'nm_coa' => $coa_credit['nm'],
                    'curr' => $_bpb['curr'],
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => $_bpb['description'],
                    'dateadd' => $dateadd,
                    'useradd' => $user,
                );

                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;
            }
            foreach($dataBpb as $_bpb){
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
        'fg_intercompany' => $_POST['fg_intercompany'],
        'id_intercompany' => $_POST['id_intercompany'],
    );

    if($_POST['mode'] == 'save'){
        $data['dateadd'] = date('Y-m-d H:i:s', time());
        $data['useradd'] = $user;
        $journal_code = Config::$journal_code[$data['type_journal']];

        switch($data['type_journal']){
            case Config::$journal_usage['SALES']:
                $reff_check = $M->check_bpb($data['reff_doc']);
                if($reff_check['errcode'] == '01') {
                    echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
                    $status = false; // Num journal already exists
                    $row = json_decode(json_encode($data));
                }else{
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code,$data['date_journal']);
                    $M->insert_inv_sales($data['reff_doc'], $data['id_journal']);
                    $M->save($data);
                    echo "<script>window.location.href='?mod=js&id={$data['id_journal']}';</script>";exit();
                }
                break;
            default:
                $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
                $M->save($data);
                echo "<script>window.location.href='?mod=js&id={$data['id_journal']}';</script>";exit();
        }
    }elseif($_POST['mode'] == 'update'){
        $journal = $M->get_journal($id);
        if($journal->num_journal != $data['num_journal'] and $M->get_journal_by_num($data['num_journal'])){
            echo "<script>alert('Error: Nomor Dokumen sudah terdaftar');</script>";
            $status = false; // Num journal already exists
            $row = json_decode(json_encode($data));
        }else{
            $data['dateedit'] = date('Y-m-d H:i:s', time());
            $data['useredit'] = $user;
            $status = $M->update($id, $data);

            if(isset($_POST['posting'])){
                $data['id_journal'] = $id;
                if($status = $ch->posting_validation($data)) {
                    $data = array(
                        'date_post' => date('Y-m-d H:i:s', time()),
                        'user_post' => $user
                    );
                    $M->post_journal($id, $data);
                }else{
                    $journal = (array) $M->get_journal($id);
                    $row = json_decode(json_encode(array_merge($journal, $data)));
                    $list = $M->get_journal_items($id, $journal['fg_post']);
                    $t_debit = $t_credit = 0;
                    if(count($list)){
                        foreach($list as $l){
                            $t_debit += $l->debit;
                            $t_credit += $l->credit;
                        }
                    }
                }
            }
            echo "<script>window.location.href='?mod=je';</script>";exit();
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

$acct_period = $ch->get_available_period();


// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<form method='post' name='form' enctype='multipart/form-data' action="" onsubmit='return validasi()'>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group'>
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker' class='form-control' name='period' autocomplete="off"
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


				
                    <div class='form-group' style="display:none">
                        <label>Tipe Jurnal</label>

                  <select id='type_journal' name="type_journal" class='form-control' onchange="filter_table()" readonly>
                      <option value="<?=Config::$journal_usage['SALES']?>" ><?=Config::$journal_type[Config::$journal_usage['SALES']]?></option>
                  </select>


                    </div>
				
                     <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' id='datepicker1' class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Dokumen' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
 
                     <div class='form-group'>
                         <label>Referensi Dokumen</label>
                         <div class="input-group">
                         <input readonly type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>'>
                         <a onclick="modal_ref()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
                     </div>
                     <div class='form-group'>
                         <label>Mata Uang</label>
							<select readonly class="form-control" id="curr" name="matauang">
								<option>USD</option>
								<option>IDR</option>
								<option>EUR</option>
								<option>JPN</option>
							</select>
                     </div>
                     <div class='form-group'>
                         <label>Amount</label>
                         <input readonly type='text' id='amount' class='form-control' name='amount' autocomplete="off"
                                placeholder='Masukkan Nilai' value=''>
                     </div>
                </div>
                 <div class='col-md-3'>
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>


					                      <div class='form-group'>
                         <label>No.Invoice</label>
                         <input readonly type='text' id='noinvoice' class='form-control' name='noinvoice'
                                placeholder='No Invoice' value=''>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Invoice</label>
                         <input readonly type='text' id='datepicker2' class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Dokumen' value=''>
                     </div>
	                     <div class='form-group'>
                         <label>Faktur Pajak</label>
                         <input readonly type='text' id='fakturoajak' class='form-control' name='fakturpajak' autocomplete="off"
                                placeholder='Masukkan Nomor Faktur' value=''>
                     </div>				 
					 
                     <div class='form-group'>
                         <label>Customer</label>
                         <input readonly type='text' id='customer' class='form-control' name='customer' autocomplete="off"
                                placeholder='Masukkan Nama Customer' value=''>
                     </div>


                     <div class='form-group'>
                         <label>Tgl Jatuh Tempo</label>
                         <input readonly type='text' id='tgljatuhtempo' class='form-control' name='tgljatuhtempo' autocomplete="off"
                                placeholder='tgl jatuh tempo' value=''>
                     </div>

         <!--            <div class='form-group'>
                         <label>Tanggal Jurnal</label>
                         <input type='text' id='datepicker1' class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Dokumen' value='<? // isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
                     </div>
					 -->

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

    <?php if($id):?>
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Journal Entry</h3>
          <?php if(isset($row) and !$row->fg_post):?>
          <a href="../fin/?mod=jefd&amp;id=<?=$id?>" class="btn btn-primary btn-s"><i class="fa fa-plus"></i> Add Item</a>
          <?php endif;?>
<!--          <a href="../fin/?mod=jefdel&amp;id=--><?//=$id?><!--" class="btn btn-danger btn-s"><i class="fa fa-trash"></i> Multi Delete</a>-->
      </div>
      <div class="box-body">
        <table id="examplefix" class="display responsive" style="width:100%;font-size:12px;">
          <thead>
            <tr>
                <th>No</th>
                <th>ID Chart of Account</th>
                <th>Nama Chart of Account</th>
                <th>Mata Uang</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>No WS/Cost Center</th>
                <th>Deskripsi</th>
                <th width='14%'>Action</th>
            </tr>
          </thead>
          <?php if(@count($list)):?>
          <tbody>
            <?php $no=1;?>
            <?php foreach($list as $l):?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$ch->format_coa($l->id_coa)?></td>
                <td><?=$l->nm_coa?></td>
                <td><?=$l->curr?></td>
                <td class="text-right"><?=number_format($l->debit)?></td>
                <td class="text-right"><?=number_format($l->credit)?></td>
                <td><?=$l->nm_ws ? : $l->nm_costcenter?></td>
                <td><?=$l->description?></td>
                <td>
                    <?php if(isset($row) and !$row->fg_post):?>
                    <a class="btn btn-primary btn-s" href="?mod=jefd&amp;id=<?=$id?>&amp;rid=<?=$l->row_id?>" data-toggle="tooltip" title="" data-original-title="Ubah"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-danger btn-s" href="?mod=jefd&amp;id=<?=$id?>&amp;rid=<?=$l->row_id?>&amp;mode=del" data-toggle="tooltip" title="" onclick="return confirm('Apakah anda yakin akan menghapus ?')" data-original-title="Hapus">
                        <i class="fa fa-trash-o"></i></a>
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach;?>
          </tbody>
          <?php endif;?>
            <tfoot>
            <tr>
                <th colspan="4">&nbsp;</th>
                <th class="text-right"><?=number_format($t_debit)?></th>
                <th class="text-right"><?=number_format($t_credit)?></th>
                <th colspan="3">&nbsp;</th>
            </tr>
            </tfoot>
        </table>
      </div>
    </div>
    <?php endif;?>
</form>

<div class="modal fade" id="modalBpb"  role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Invoice</h4>
            </div>
            <div class="modal-body">
                <div class='form-group'>
                    <div class="row">
                        <div class="col-md-4">
                            <label>No Invoice</label>
                            <input type='text' class='form-control' id="invno" >
                        </div>
                        <div class="col-md-4">
                            <label>Nama Supplier</label>
                            <input type='text' class='form-control' id="supplier" >
                        </div>
                        <div class="col-md-4">
                            <label for="">&nbsp;</label><br>
                            <a onclick="lookup_bpb()" class="btn btn-primary">Lookup</a>
                        </div>
                    </div>
                    <div class="row hidden">
                        <div class="col-md-12">
                            <em>Gunakan % untuk pencarian wildcard</em>
                        </div>
                    </div>
                </div>
                <div class="bpbresult">
                    <table class="table">
                        <thead>
                            <th>Nama Supplier</th>
                            <th>No. Invoice </th>
                            <th>Invoiced Qty</th>
                            <th>Invoice Amount</th>
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
            case "1":
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
    function modal_bpb(){
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
    }
    function lookup_bpb(){
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_invoice",
            data: "invno=" +$("#invno").val()+"&supplier="+$("#supplier").val()+"&posted=1",
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
                    rows += '<tr>';
					rows += '<td>' + invoice_list[i].supplier + '</td>';
                    rows += '<td>' + invoice_list[i].invno + '</td>';
					rows += '<td>' + invoice_list[i].jqty + '</td>';
                    rows += '<td>' + invoice_list[i].n_amount + '</td>';
                   // rows += '<td>' + invoice_list[i].item_count + '</td>';
                    rows += '<td><a onclick="select_bpb(' + "'" + invoice_list[i].invno + "'" + ')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Invoice not found--</em></td></tr>';
            }
            $("#modalBpb .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
    }
    function select_bpb(bpb){
        $('#reff_doc').val(bpb);
        load_reference();
        $('#modalBpb').modal('hide');
    }
    function load_reference(){
        var bpbno = $('#reff_doc').val();

        if(!bpbno){
            return false;
        }

        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=get_invoice",
            data: "invno="+bpbno,
            dataType: "json",
            async: false
        }).responseText;

        request = JSON.parse(request);

        if(request.status == true) {
            var bpb = request.data;

            $('#amount').val(bpb.invoice_amount);
            $('#noinvoice').val(bpb.invno);
            $('#datepicker2').val(bpb.invdate);
//            $('#fakturpajak').val(bpb.no_fp);
            $('#curr').val(bpb.curr);
            $('#customer').val(bpb.supplier);
//            $('#tgljatuhtempo').val(bpb.due_date);
        }else{
//            alert("Error: BPB not available");
        }
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

    var acct_periods = <?=json_encode($acct_period);?>;
    function check_period(period){
        if(period==''){
            return true;
        }

        var found = false;
        for(var i in acct_periods){
            if(acct_periods[i] == period){
                found = true;
                break;
            }
        }

        return found;
    }
    $(document).ready(function(){
        load_reference();
        $("#periodpicker").datepicker( {
            format: "mm/yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
        }).on('changeDate', function(e) {
            if(!check_period(e.format())){
                alert("Periode Akuntansi "+e.format()+" belum dibuka");
                $("#periodpicker").val('');
            }
        });
    });
</script>