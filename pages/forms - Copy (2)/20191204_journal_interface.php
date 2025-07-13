<?php
include '../../include/conn.php';
include '../../../include/conn.php';
//debug
function dd($var){
    echo '<pre>';print_r($var);exit();
}

class Basemodel{
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
}

class Coa_helper{
    private $coa_config;
    private $coa_separator;

    public function __construct()
    {
        $this->coa_config = $this->get_coa_digit_config();
        $this->coa_separator = Config::$coa_separator;
    }

    function get_coa_digit_config(){
        global $con_new;

        $q = mysqli_query($con_new, "
            SELECT num_of_digit FROM mastercoaconfig ORDER BY `position`;
        ");

        $rows = array();
        while($row = mysqli_fetch_object($q)){
            $rows[] = $row;
        }

        $result = array();
        foreach($rows as $r){
            $result[] = $r->num_of_digit;
        }
        return $result;
    }

    public function get_available_period(){
        global $con_new;

        $q = mysqli_query($con_new, "
            SELECT period FROM masteraccperiod WHERE fg_status = '1';
        ");

        $rows = array();
        while($row = mysqli_fetch_object($q)){
            $rows[] = $row;
        }

        $result = array();
        foreach($rows as $r){
            $result[] = $r->period;
        }
        return $result;
    }

    public function is_period_available($period){
        global $con_new;

        $q = mysqli_query($con_new, "
            SELECT period, fg_status FROM masteraccperiod WHERE period = '$period';
        ");

        $rows = array();
        while($row = mysqli_fetch_object($q)){
            $rows[] = $row;
        }

        if(!count($rows)){
            return array(
                'status' => false,
                'message' => 'Invalid period, or period has not been created'
            );
        }

        $fg_status = $rows[0]->fg_status;

        switch($fg_status){
            case '0' :
                return array(
                    'status' => false,
                    'message' => 'Periode accounting belum dibuka'
                ); break;
            case '1' :
                return array(
                    'status' => true,
                    'message' => 'Period sudah dibuka'
                ); break;
            case '2':
                return array(
                    'status' => false,
                    'message' => 'Period sudah ditutup'
                ); break;
            default:
                return array(
                    'status' => false,
                    'message' => 'Unknown error'
                ); break;
        }
    }

    public function is_detail_amount_valid($id_journal){
        global $con_new;

        $q = mysqli_query($con_new, "
            SELECT SUM(debit) t_debit, SUM(credit) t_credit FROM fin_journal_d WHERE id_journal = '$id_journal'
        ");

        $rows = array();
        while($row = mysqli_fetch_object($q)){
            $rows[] = $row;
        }

        $t_debit = $rows[0]->t_debit;
        $t_credit = $rows[0]->t_credit;

        if(is_null($t_debit)){
            return array(
                'status' => false,
                'message' => 'Debit dan Kredit tidak boleh kosong'
            );
        }
        if($t_debit != $t_credit){
            return array(
                'status' => false,
                'message' => 'Nilai Debit dan Kredit harus balance'
            );
        }
        return array(
            'status' => true,
            'message' => 'Ok'
        );
    }

    public function general_validation($data){
        $status = true;

        $period_status = $this->is_period_available($data['period']);
        if(!$period_status['status']){
            echo "<script>alert('Error: {$period_status['message']}');</script>";
            return false;
        }

        return $status;
    }

    public function posting_validation($data){
        $status = true;

        $detail_amount = $this->is_detail_amount_valid($data['id_journal']);
        if(!$detail_amount['status']){
            echo "<script>alert('Error: {$detail_amount['message']}');</script>";
            return false;
        }

        return $status;
    }

    public function format_coa($coa){
        $segment = array();

        $start = 0;
        foreach ($this->coa_config as $length){
            $segment[] = substr($coa,$start,$length);
            $start += $length;
        }

        return implode($this->coa_separator, $segment);
    }
}
class Config{
    public static $coa_separator = ".";

    // Loss percentage in fraction
    public static $loss = 0.03; //3%

    public function get_normal_balance($id_coa){
        $pref = substr($id_coa,0,1);

        if($pref == 2 or $pref == 3 or $pref == 4){
            return 'k'; // Kredit
        }else{
            return 'd'; // Debit
        }
    }

    public static $company_codes = array(
        '0' => 'NAG'
    );
    public static $coa_type = array(
        '0' => 'Total',
        '1' => 'Posting',
    );
    public static $coa_mapping = array(
        '0' => '-',
        '1' => 'Balance Sheet',
        '2' => 'Profit &amp; Loss',
    );
	
	
	public function ListGenerateJournal(){
		
		

	}
	
    public static $journal_type = array(
        '1' => 'Jurnal Penjualan',
        '2' => 'Jurnal Pembelian',
        '3' => 'Jurnal Pembayaran',
        '4' => 'Jurnal Alokasi AR',
        '5' => 'Jurnal Cash Masuk',
        '6' => 'Jurnal Umum',
        '7' => 'Jurnal Aktiva Tetap',
        '8' => 'Jurnal Penyesuaian',
        '9' => 'Jurnal Closing',
		'10' => 'Jurnal Cash Keluar',
		'11' => 'Jurnal Bank Masuk',
		'12' => 'Jurnal Bank Keluar',		
		'13' => 'Jurnal Penerimaan',
		'14' => 'Jurnal Kontra Bon',
		'15' => 'Jurnal Pemakloon',
		'16' => 'Jurnal Makloon',
		'17' => 'Jurnal Penjualan Makloon',
    );
    public static $journal_code = array(
        '1' => 'IJ',
        '2' => 'PJ',
        '3' => 'PV',
        '4' => 'AR',
        '5' => 'KM',
        '6' => 'GJ',
        '7' => 'FA',
        '8' => 'AJ',
        '9' => 'CJ',
		'10' => 'KK',
		'11' => 'BM',
		'12' => 'BK',
		'13' => 'RV',
		'14' => 'PK',
		'15' => 'PN',
		'16' => 'MN',
		'17' => 'PM',
    );
    public static $journal_usage = array(
        'SALES' => '1',
        'PURCHASE' => '2',
        'PAYMENT' => '3',
        'RECEIPT' => '4',
        'CASHKELUAR' => '5',
        'GENERAL' => '6',
        'ACTIVA' => '7',
        'ADJUSMENT' => '8',
        'CLOSING' => '9',
		'CASHKELUAR' => '10',
		'BANKMASUK' => '11',
		'BANKKELUAR' => '12',
		'ALLOCAR' => '13',
		'KONTRABON' => '14',
		'PEMAKLOON' => '15',
		'MAKLOON' => '16',
		'PENJUALANMAKLOON' => '17',		
    );
    public static $posting_flag = array(
        '0' => 'Parked',
        '2' => 'Posted',
    );
    public static $reference_src_key_item = 'ITEM';
    public static $reference_src_key_po = 'PO';
    public static $reference_src_key_bpb = 'BPB';
}



/**
 * Buat Chart of Account untuk Supplier
 * Jika berhasil akan me-return id coa
 * Jika Nama supplier sudah digunakan atau terjadi error database akan me-return FALSE
 *
 * @param string $nm_supplier
 * @return mixed
 */
function create_supplier_coa($nm_supplier)
{
	global $con_new;
	// TODO: buat parameter ini menjadi dinamis berdasarkan master coa config
    $segment = '2101';
    $segment_len = 4;
    $id_len = 3;
    $post_to = '2101000';

	// Check for existing name
    $q = mysqli_query($con_new, "
		SELECT 
			mcoa.*
		FROM 
			mastercoa mcoa
		WHERE 1=1
			AND id_coa LIKE	'$segment%'
			AND nm_coa = '$nm_supplier'
	");

    // Name already exists
    if($q->num_rows){
    	return false;
	}

	// Get current max id
    $r = mysqli_fetch_object(mysqli_query($con_new, "
    	SELECT MAX(id_coa) max_coa 
		FROM mastercoa
		WHERE 1=1
		AND id_coa LIKE	'$segment%'
		AND id_coa <> '2101999'
	"));

    if(is_null($r)){
    	// Belum ada id coa, mulai dari post_to ditambah 1
        $id_coa = (int) substr($post_to, $segment_len,$id_len) + 1;
	}else{
    	// Ambil coa terakhir dan ditambahkan 1
    	$id_coa = (int) substr($r->max_coa, $segment_len,$id_len) + 1;
	}
	// Format ke dalam bentuk string dengan padding '0', dan ditambahkan dengan segment
    $id_coa = $segment . str_pad($id_coa, $id_len, '0', STR_PAD_LEFT);

    // Prepare data untuk insert
	$data = array(
        'id_coa' => $id_coa,
		'nm_coa' => $nm_supplier,
		'fg_posting' => '1',
		'fg_mapping' => '1',
		'fg_active' => '1',
		'post_to' => $post_to,
	);

	// Insert data ke database
    mysqli_query($con_new, "
		INSERT INTO mastercoa 
		  (id_coa, nm_coa, fg_posting, fg_mapping, fg_active, post_to)
		VALUES (
		  '{$data['id_coa']}',
		  '{$data['nm_coa']}',
		  '{$data['fg_posting']}',
		  '{$data['fg_mapping']}',
		  '{$data['fg_active']}',
		  '{$data['post_to']}'
		);
        ");

    if(mysqli_affected_rows($con_new)){
        return $id_coa;
	}else{
    	return false;
	}
};

function get_master_company()
{
    global $con_new;
    $Bm = new Basemodel($con_new);

    return $Bm->query("
            SELECT * FROM mastercompany;
        ")->row();
}

function journal_posting($jh, $jd)
{
    global $con_new;

    $journal_type = Config::$journal_type;
    $journal_code = Config::$journal_code;
    $posting_flag = Config::$posting_flag;

    $_qCompany = mysqli_fetch_object(mysqli_query($con_new, "SELECT * FROM mastercompany;"));
    $company = $_qCompany->company;

    $dataJh = array(
        'company' => $company,
        'period' => $jh['period'],
        'date_journal' => $jh['date_journal'],
        'type_journal' => $jh['type_journal'],
        'reff_doc' => $jh['reff_doc'],
        'fg_intercompany' => $jh['fg_intercompany'],
        'id_intercompany' => $jh['id_intercompany'],
        'fg_post' => $jh['fg_post'],
        'date_post' => $jh['date_post'],
        'user_post' => $jh['user_post'],
        'dateadd' => $jh['dateadd'],
        'useradd' => $jh['useradd'],
    );

    // Validate inter company flag
    if(!($jh['fg_intercompany'] == "0" or $jh['fg_intercompany'] == "1")){
        return array(
            'status' => false,
            'message' => "Invalid Intercompany flag : ".$jh['fg_intercompany']
        );
    }

    // Validate journal type
    if(!isset($journal_type[$jh['type_journal']])){
        return array(
            'status' => false,
            'message' => "Invalid Journal type : ".$jh['type_journal']
        );
    }

    // Validate posting/parked status
    if(!isset($posting_flag[$jh['fg_post']])){
        return array(
            'status' => false,
            'message' => "Invalid Posting flag : ".$jh['fg_post']
        );
    }

    //TODO: Validate period

    $dataJd = array();
    $row_id = 1;
    foreach($jd as $_jd){
        $__jd = array(
            'row_id' => $row_id++,
            'id_coa' => $_jd['id_coa'],
            'curr' => $_jd['curr'],
            'id_costcenter' => $_jd['id_costcenter'],
            'nm_ws' => $_jd['nm_ws'],
            'debit' => $_jd['debit'] ? : 0,
            'credit' => $_jd['credit'] ? : 0,
            'description' => $_jd['description'],
            'dateadd' => $_jd['dateadd'],
            'useradd' => $_jd['useradd'],
        );

        // Validate COA Id
        if($__jd['id_coa']) {
            $coa = mysqli_fetch_object(mysqli_query($con_new, "
              SELECT mc.nm_coa FROM mastercoa mc WHERE 1=1 AND mc.fg_active = '1' AND mc.fg_posting = '1' AND mc.id_coa LIKE '{$__jd['id_coa']}%';
            "));
            if(is_null($coa)){
                return array(
                    'status' => false,
                    'message' => "Invalid COA id: ".$__jd['id_coa']
                );
            }
            $__jd['nm_coa'] = $coa->nm_coa;
        }
        // Validate cost center
        if($__jd['id_costcenter']) {
            $costcenter = mysqli_fetch_object(mysqli_query($con_new, "
                SELECT mc.nm_costcenter
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
            WHERE 1=1
                AND mc.id_costcenter = '{$__jd['id_costcenter']}'
            "));
            if(is_null($costcenter)){
                return array(
                    'status' => false,
                    'message' => "Invalid Cost Center id: ".$__jd['id_costcenter']
                );
            }

            $__jd['nm_costcenter'] = $costcenter->nm_costcenter;
            $__jd['id_cost_category'] = $costcenter->id_cost_category;
            $__jd['nm_cost_category'] = $costcenter->nm_cost_category;
            $__jd['id_cost_dept'] = $costcenter->id_cost_dept;
            $__jd['nm_cost_dept'] = $costcenter->nm_cost_dept;
            $__jd['id_cost_sub_dept'] = $costcenter->id_cost_sub_dept;
            $__jd['nm_cost_sub_dept'] = $costcenter->nm_cost_sub_dept;
        }else{
            $__jd['nm_costcenter'] = $__jd['id_cost_category'] = $__jd['nm_cost_category'] = $__jd['id_cost_dept']
                = $__jd['nm_cost_dept'] = $__jd['id_cost_sub_dept'] = $__jd['nm_cost_sub_dept'] = '';
        }

        $dataJd[] = $__jd;
    }

    $nm_journal = generate_coa_id("NAG", Config::$journal_code[$dataJh['type_journal']]);
    $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, date_journal, type_journal, reff_doc, fg_intercompany, id_intercompany
              ,fg_post, date_post, user_post, dateadd, useradd)
            VALUES 
              ('{$dataJh['company']}', '{$dataJh['period']}', '{$nm_journal}', '{$dataJh['date_journal']}'
              , '{$dataJh['type_journal']}', '{$dataJh['reff_doc']}', '{$dataJh['fg_intercompany']}', '{$dataJh['id_intercompany']}'
             ,'{$dataJh['fg_post']}','{$dataJh['date_post']}','{$dataJh['user_post']}'
             ,'{$dataJh['dateadd']}', '{$dataJh['useradd']}')
            ;
        ";

    // Insert header
    mysqli_query($con_new, $sql);
    // Get header id
    $id_journal  = $nm_journal; //$con_new->insert_id;

    foreach($dataJd as $_jd){
        // Set header id
        $_jd['id_journal'] = $id_journal;

        $sql = "
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
              ,id_costcenter, nm_costcenter, id_cost_category, nm_cost_category
              ,id_cost_dept, nm_cost_dept, id_cost_sub_dept, nm_cost_sub_dept
              ,nm_ws
              ,description, dateadd, useradd)
            VALUES 
              ('{$_jd['id_journal']}', '{$_jd['row_id']}', '{$_jd['id_coa']}', '{$_jd['nm_coa']}' ,'{$_jd['curr']}', '{$_jd['debit']}', '{$_jd['credit']}'
              ,'{$_jd['id_costcenter']}', '{$_jd['nm_costcenter']}', '{$_jd['id_cost_category']}', '{$_jd['nm_cost_category']}'
              ,'{$_jd['id_cost_dept']}','{$_jd['nm_cost_dept']}', '{$_jd['id_cost_sub_dept']}', '{$_jd['nm_cost_sub_dept']}'
              ,'{$_jd['nm_ws']}'
              ,'{$_jd['description']}','{$_jd['dateadd']}', '{$_jd['useradd']}'
              )
            ;
        ";
        // Insert detail
        mysqli_query($con_new, $sql);
    }

    return array(
        'status' => true,
        'message' => "Journal created"
    );

};

/**
 * Generate coa id baru
 * Number format = xxx-xx-xxxxx
 * @param $company_code
 * @param $journal_code
 * @return string
 */
function generate_coa_id($company_code, $journal_code, $journal_date){
    global $con_new;

    //Temporary override
    $company_code = Config::$company_codes[0];

    $year = date('Y', strtotime($journal_date));
    $sql = "
            SELECT sequence FROM fin_journal_pool WHERE segment = '$journal_code' and `year`='$year';
        ";
    $pool = mysqli_fetch_object(mysqli_query($con_new, $sql));
    $new_seq = 1;
    if(is_null($pool)){
        mysqli_query($con_new, "INSERT INTO fin_journal_pool (segment, `year`, sequence) VALUES ('$journal_code', '$year', $new_seq);");
    }else{
        $new_seq = $pool->sequence + 1;
        mysqli_query($con_new, "UPDATE fin_journal_pool SET sequence = $new_seq WHERE segment = '$journal_code' and `year`='$year';");
    }

    // 2 digit month and 2 digit year
    $my = date('ym', strtotime($journal_date));
    $number = $company_code.'-'.$journal_code.'-'.$my.'-'.str_pad($new_seq,5,"0", STR_PAD_LEFT);

    return $number;
};


function generate_packing_list($journal_code, $journal_date){
    global $con_new;

    //Temporary override
   
	
    $year = date('Y', strtotime($journal_date));
    $sql = "
            SELECT sequence FROM fin_journal_pool WHERE segment = '$journal_code' and `year`='$year';
        ";
    $pool = mysqli_fetch_object(mysqli_query($con_new, $sql));
    $new_seq = 1;
    if(is_null($pool)){
        mysqli_query($con_new, "INSERT INTO fin_journal_pool (segment, `year`, sequence) VALUES ('$journal_code', '$year', $new_seq);");
    }else{
        $new_seq = $pool->sequence + 1;
        mysqli_query($con_new, "UPDATE fin_journal_pool SET sequence = $new_seq WHERE segment = '$journal_code' and `year`='$year';");
    }

    // 2 digit month and 2 digit year
    $my = date('ym', strtotime($journal_date));
   // $number = $company_code.'-'.$journal_code.'-'.$my.'-'.str_pad($new_seq,5,"0", STR_PAD_LEFT);
	$number = str_pad($new_seq,5,"0", STR_PAD_LEFT).'/'.$journal_code.'/EXIM-NAG'.$year;
    return $number;
};


function generate_rekap_id($type){
    global $con_new;

    $year = date('Y', time());

    $type = strtoupper($type);
    switch($type){
        case 'AR':
        case 'AP':break;
        default: // type mismatch
            return false;
    }

    $sql = "
            SELECT sequence FROM fin_journal_pool WHERE segment = '$type' and `year`='$year';
        ";
    $pool = mysqli_fetch_object(mysqli_query($con_new, $sql));


    $new_seq = 1;
    if(is_null($pool)){
        mysqli_query($con_new, "INSERT INTO fin_journal_pool (segment, `year`, sequence) VALUES ('$type', '$year', $new_seq);");
    }else{
        $new_seq = $pool->sequence + 1;
        mysqli_query($con_new, "UPDATE fin_journal_pool SET sequence = $new_seq WHERE segment = '$type' and `year`='$year';");
    }

    // 2 digit month and 2 digit year
    $number = $year.'-'.str_pad($new_seq,5,"0", STR_PAD_LEFT);

    return $number;
};
function check_bpb($id, $checking = TRUE, $id2=''){
    global $con_new;
    $Bm = new Basemodel($con_new);

    if($id2){
        $used = $Bm->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id' AND reff_doc = '$id2' ")->row();
    }else{
        $used = $Bm->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id'")->row();
    }

/*     if($checking) {
        if (!is_null($used)) {
            return array(
                'status' => false,
                'errcode' => '01',
                'message' => 'BPB number already used'
            );
        }
    } */

    $bpb = $Bm->query("SELECT * FROM bpb WHERE bpbno_int = '$id'")->result();

    if(!count($bpb)){
        return array(
            'status' => false,
            'errcode' => '02',
            'message' => 'BPB number not exist'
        );
    }

    $bpb_detail = $Bm->query("
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


function get_id_coa_gr_from_bpb($bpb)
{
    global $con_new;
    $Bm = new Basemodel($con_new);

    $mapping_coa = $Bm->query("
            SELECT mp.gr_d, mcd.nm_coa nm_coa_d, mp.gr_k, mck.nm_coa nm_coa_k
            FROM mapping_coa mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.gr_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.gr_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' 
        ")->row();

    $coa_debit = array(
        'id' => $mapping_coa->gr_d,
        'nm' => $mapping_coa->nm_coa_d
    );
    $coa_credit = array(
        'id' => $mapping_coa->gr_k,
        'nm' => $mapping_coa->nm_coa_k
    );
    return array(
        $coa_debit,
        $coa_credit
    );
}

function IsGeneral($bpbno_nya){
	 global $con_new;
$Bm = new Basemodel($con_new);	 
    $mapping_coa_gen = $Bm->query("
            SELECT 
	BPB.id_item, BPB.bpbno_int,SUBSTR(MI.goods_code,1,3) code_gen FROM bpb BPB 
	INNER JOIN masteritem MI ON BPB.id_item = MI.id_item
	INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3)
	WHERE BPB.bpbno_int = '{$bpbno_nya}'  GROUP BY BPB.bpbno_int 

        ")->row();	

	if((ISSET($mapping_coa_gen->code_gen)) || (!EMPTY($mapping_coa_gen->code_gen))){
		return 1;
	}else{
		return 0;
		
	}
}




function get_id_coa_general($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
		

$q = "SELECT BPB.id_item
		, BPB.bpbno
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr 
		,MCG.id_coa_credit_gr 
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir 
		,COA_GR_DEB.nm_coa nama_coa_gr_deb
		,COA_GR_CRE.nm_coa nama_coa_gr_cre
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nama_coa_ir_cre		
		FROM bpb BPB 
			INNER JOIN masteritem MI ON BPB.id_item = MI.id_item 
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3) 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno = '{$bpb['bpbno']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno";
    $mapping_coa = $Bm->query($q)->row();
		
if(!ISSET($mapping_coa->id_item) || EMPTY($mapping_coa->id_item) ){
	
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,SUBSTR(MI.goods_code,1,3) code_gen
		,MCG.n_vendor_cat 
		,MCG.id_coa_debit_gr 
		,MCG.id_coa_credit_gr 
		,MCG.id_coa_debit_ir 
		,MCG.id_coa_credit_ir 
		,COA_GR_DEB.nm_coa nama_coa_gr_deb
		,COA_GR_CRE.nm_coa nama_coa_gr_cre
		,COA_IR_DEB.nm_coa nama_coa_ir_deb
		,COA_IR_CRE.nm_coa nama_coa_ir_cre		
		FROM bpb BPB 
			INNER JOIN masteritem MI ON BPB.id_item = MI.id_item 
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = SUBSTR(MI.goods_code,1,3) 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE MCG.n_code = 'UTL' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno";
    $mapping_coa = $Bm->query($q)->row();
}
    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_gr,
        'nm' => $mapping_coa->nama_coa_gr_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_gr,
        'nm' => $mapping_coa->nama_coa_gr_cre
    );
	





    return array(
        $coa_debit,
        $coa_credit
    );
	
	
}



function insert_bpb_gr($bpbno)
{


    global $con_new;
    $Bm = new Basemodel($con_new);

    $journal_code = Config::$journal_code['2'];
    $journal_date = date('Y-m-d', time());
    $id_journal = generate_coa_id("NAG", $journal_code, $journal_date);
    $IsGeneral = IsGeneral($bpbno);

    $bpb_request = check_bpb($bpbno);
    if($bpb_request['status']){
        $company = get_master_company();
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];
        $d = array(
            'company' => $company->company,
            'period' => date('m/Y', time()),
            'num_journal' => '',//$_POST['num_journal'],
            'id_journal' => $id_journal,
            'date_journal' => date('Y-m-d', time()),
            'type_journal' =>  Config::$journal_usage['PURCHASE'],
            'reff_doc' => $bpbno,
            'src_reference' => 'BPB',
            'dateadd' => $dateadd,
            'useradd' => $user,
            'fg_post' => '2',
            'date_post' => $dateadd,
            'user_post' => $user,
        );

        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, src_reference
              ,fg_intercompany, id_intercompany 
              ,fg_post, date_post, user_post 
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['src_reference']}'
              ,'0', ''
              , '{$d['fg_post']}', '{$d['date_post']}', '{$d['user_post']}'
              ,'{$d['dateadd']}', '{$d['useradd']}')
            ;
        ";
		
        $Bm->query($sql);
        // Insert Detail
        $dataBpb = array();
        $bpb = $bpb_request['data'];
        $row_id = 1;
        foreach($bpb as $_bpb){
            $_bpb = (array) $_bpb;
            $amount = $_bpb['price'] * $_bpb['qty'];
	
			if($IsGeneral == '1'){
				list($coa_debit, $coa_credit) = get_id_coa_general($_bpb);
			}else{
				list($coa_debit, $coa_credit) = get_id_coa_gr_from_bpb($_bpb);
			}

            // GR
            $_debit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $_bpb['curr'],
                'debit' => $amount,
                'credit' => 0,
                'description' => $_bpb['itemdesc'],
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            // Piutang
            $_credit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_credit['id'],
                'nm_coa' => $coa_credit['nm'],
                'curr' => $_bpb['curr'],
                'debit' => 0,
                'credit' => $amount,
                'description' => $_bpb['itemdesc'],
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
            $Bm->query($sql);
        }

        return array(
            'status' => TRUE,
            'errcode' => '',
            'message' => 'Journal created'
        );
    }else{
        // Journal not created
        return $bpb_request;
    }
}

function check_inv($id, $checking = TRUE)
{
    global $con_new;
    $Bm = new Basemodel($con_new);

    $used = $Bm->query("SELECT reff_doc FROM fin_journal_h WHERE reff_doc = '$id' AND type_journal='1'")->row();

    if($checking) {
        if (!is_null($used)) {
            return array(
                'status' => false,
                'errcode' => '01',
                'message' => 'INV number already used'
            );
        }
    }

    $inv = $Bm->query("SELECT * FROM invoice_header WHERE invno = '$id'")->result();

    if(!count($inv)){
        return array(
            'status' => false,
            'errcode' => '02',
            'message' => 'INV number not exist'
        );
    }

    $detail = $Bm->query("
           SELECT 
                ih.invno
                ,ih.invdate
                ,(id.qty * id.price) invoice_amount
                ,so.curr
                ,ms.supplier
                ,ms.vendor_cat
                ,ms.area
				,sd.id idso
				,ifnull(bppb.bppbno,'')bppbno
				,ifnull(bpb.pono,'')pono
                ,CONCAT(mp.product_item,' - ', sd.color, ' - ',sd.size) description
                ,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order
                ,'A' grade
            FROM 
            invoice_header ih
            LEFT JOIN invoice_commercial ic ON ic.v_noinvoicecommercial = ih.id
            LEFT JOIN invoice_detail id ON ih.id = id.id_inv
            LEFT JOIN so_det sd ON sd.id = id.id_so_det
			LEFT JOIN bpb  ON bpb.id_so_det = sd.id
            LEFT JOIN so ON so.id = sd.id_so
			LEFT JOIN bppb ON sd.id = bppb.id_so_det
            LEFT JOIN act_costing ac ON so.id_cost = ac.id
            LEFT JOIN masterproduct mp ON ac.id_product = mp.id
            LEFT JOIN mastersupplier ms ON ac.id_buyer = ms.Id_Supplier
            WHERE 
                ih.invno = '$id' GROUP BY idso
        ")->result();

    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'INV number found',
        'data'  => $detail
    );
}

function get_id_coa_sales_from_inv($inv)
{
    global $con_new;
    $Bm = new Basemodel($con_new);

    // TODO: filter by sales category. currently there is no sales category available

    $inv['area'] = $inv['area']=='I'?'I':'L'  ;
    $mapping_coa = $Bm->query("
            SELECT mp.sl_d, mcd.nm_coa nm_coa_d, mp.sl_k, mck.nm_coa nm_coa_k
            FROM mapping_coa_sales mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.sl_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.sl_k
            WHERE 1=1
            AND mp.area = '{$inv['area']}' 

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

function insert_inv_sales($invno,$faktur_pajak)
{
    global $con_new; 
    $Bm = new Basemodel($con_new);
    $invoice_request = check_inv($invno);
    $journal_code = Config::$journal_code['1'];
    $journal_date = date('Y-m-d', time());
    $id_journal = generate_coa_id("NAG", $journal_code, $journal_date);

    if($invoice_request['status']){
        $company = get_master_company();
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];
        $d = array(
            'company' => $company->company,
            'period' => date('m/Y', time()),
            'num_journal' => '',//$_POST['num_journal'],
            'id_journal' => $id_journal,
            'date_journal' => date('Y-m-d', time()),
            'type_journal' =>  Config::$journal_usage['SALES'],
            'reff_doc' => $invno,
            'src_reference' => 'AUTO-INVOICE',
            'dateadd' => $dateadd,
            'useradd' => $user,
            'fg_post' => '2',
            'date_post' => $dateadd,
            'user_post' => $user,
			'faktur_pajak' => $faktur_pajak,
        );
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, src_reference
              ,fg_intercompany, id_intercompany 
              ,fg_post, date_post, user_post 
              ,dateadd, useradd)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['src_reference']}'
              ,'0', ''
              , '{$d['fg_post']}', '{$d['date_post']}', '{$d['user_post']}'
              ,'{$d['dateadd']}', '{$d['useradd']}')
            ;
        ";
        $Bm->query($sql);
        $dataInvoice = array();
        $invoices = $invoice_request['data'];
		$bpb    = '';
		$n_amt  = 0;
        $row_id = 1;
		$po     = '';

        foreach($invoices as $_inv){
            $_inv = (array) $_inv;
			$bpb = $_inv['bppbno'];
			$po = $_inv['pono'];			
           $amount = $_inv['invoice_amount'];	
				
            list($coa_debit, $coa_credit) = get_id_coa_sales_from_inv($_inv);
			
            // Piutang
            $_debit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $_inv['curr'],
                'debit' => $amount,
                'credit' => 0,
                'description' => $_inv['description'],
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            // Sales
            $_credit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_credit['id'],
                'nm_coa' => $coa_credit['nm'],
                'curr' => $_inv['curr'],
                'debit' => 0,
                'credit' => $amount,
                'description' => $_inv['description'],
                'dateadd' => $dateadd,
                'useradd' => $user,
            );

            $dataInvoice[] = $_debit;
            $dataInvoice[] = $_credit;
        }

        foreach($dataInvoice as $_inv){
			if($_inv['debit'] > 0){
				$n_amt = $n_amt  + $_inv['debit'];
			}
            $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd)
                    VALUES 
                      ('{$_inv['id_journal']}', '{$_inv['row_id']}', '{$_inv['id_coa']}', '{$_inv['nm_coa']}', '{$_inv['curr']}', '{$_inv['debit']}', '{$_inv['credit']}'
                      ,'{$_inv['description']}','{$_inv['dateadd']}', '{$_inv['useradd']}'
                      )
                    ;
                "; 
            // Insert detail
            $Bm->query($sql);
        }
            $sql = "
                    INSERT INTO fin_journalheaderdetail
                      (v_idjournal, v_fakturpajak)
                    VALUES 
                      ('{$d['id_journal']}', '{$d['faktur_pajak']}'
                      )
                    ;
                ";
            // Insert detail
           $Bm->query($sql);
			
            $sql = "
                    UPDATE invoice_commercial SET 
					n_amount = '{$n_amt}',
					v_pono = '{$po}'
					WHERE v_noinvoicecommercial = '{$invno}'
                ";
            // Insert detail
            $Bm->query($sql);			
			
		
        return array(
            'status' => TRUE,
            'errcode' => '',
            'message' => 'Journal created'
        );
    }else{
        // Journal not created
        return $invoice_request; 
    }
}

function GetQuery_Allokasi_AR($source){
	$WHERE = "";
	if($source=="LOOKUP"){
	$WHERE = "AND JH.reff_doc IS NOT NULL AND  AR.id_rekap NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE type_journal IN ('4','13'))";		
	}
	$query = "SELECT AR.id_rekap
				,AR.id_journal
				,JH.reff_doc
				,JH.reff_doc2
				,IC.v_noinvoicecommercial no_invoice
				,SUM(ifnull(IC.n_amount,0)) j_price
				,SUM(ifnull(ID.qty,0)) jqty
				,MS.Supplier
				,MS.Id_Supplier
				,MS.supplier_code
				,MS.vendor_cat
				,MS.area
				,SO.curr
						FROM fin_status_journal_ar AR 
				LEFT JOIN(
					SELECT period,id_journal,type_journal,reff_doc,reff_doc2 FROM fin_journal_h 
				)JH ON AR.id_journal = JH.id_journal
				LEFT JOIN(
					SELECT n_id,v_noinvoicecommercial,n_amount,n_idinvoiceheader,bpbno FROM invoice_commercial
				)IC ON JH.reff_doc = IC.v_noinvoicecommercial
					LEFT JOIN(
					SELECT SUM(ifnull(qty,0)) qty,id_inv,id_so_det FROM invoice_detail GROUP BY id_inv
					)ID ON ID.id_inv = IC.n_idinvoiceheader  
					LEFT JOIN(
					SELECT max(id)idsod,id_so FROM so_det GROUP BY id
					)SOD ON ID.id_so_det = SOD.idsod  
					LEFT JOIN(
					SELECT id,curr FROM so GROUP BY id
					)SO ON SO.id = SOD.id_so 					
				LEFT JOIN(
					SELECT id,id_buyer FROM invoice_header 
				)IH ON IH.id = IC.v_noinvoicecommercial
				LEFT JOIN(
					SELECT Supplier,Id_Supplier,area,vendor_cat,supplier_code FROM mastersupplier
				)MS ON MS.Id_Supplier = IH.id_buyer

				WHERE 1 $WHERE
				GROUP BY AR.id_journal";
				
	return $query;
}

function GetQuery_Detail_Payment($source,$param){
	$query = "SELECT  A.v_nojournal
			,A.v_listcode no_payment
			,A.v_source
			,A.v_isapproval
			,JD.reff_doc
			,JD.reff_doc2
			,JD.debit bpb_amount
			,MS.supplier nm_supplier			
				FROM fin_status_journal_ap A LEFT JOIN
					(SELECT id_journal
						,SUM(debit)debit
						,reff_doc
						,reff_doc2
						FROM fin_journal_d GROUP BY id_journal	
					)JD ON A.v_nojournal = JD.id_journal 
				LEFT JOIN bpb BPB ON BPB.bpbno_int = JD.reff_doc
				LEFT JOIN mastersupplier MS ON MS.Id_Supplier = BPB.id_supplier 
				WHERE 1 {WHERE}  AND A.v_source = 'KB' 
				GROUP BY v_nojournal,A.v_listcode
			UNION ALL
			SELECT   A.v_nojournal
					,A.v_listcode no_payment
					,A.v_source
					,A.v_isapproval
					,A.reff_doc
					,A.reff_doc2
					,sum(ifnull(A.amount,0))bpb_amount
					,A.Supplier nm_supplier FROM(
			SELECT AP.v_nojournal
					,AP.v_listcode
					,AP.v_source
					,AP.v_isapproval
					,'' reff_doc
					,'' reff_doc2
					,(BPB.qty * BPB.price)amount
					,POI.id
					,MS.Supplier
					FROM fin_status_journal_ap AP
					LEFT JOIN(
						SELECT id,pono FROM po_header
					)POH ON AP.v_nojournal = POH.pono
					LEFT JOIN(
						SELECT id_po,(ifnull(qty,0) * ifnull(price,0) )amount_po,id FROM po_item 
					)POI ON POH.id = POI.id_po 
					LEFT JOIN bpb BPB ON BPB.pono = AP.v_nojournal
					LEFT JOIN mastersupplier MS ON MS.Id_Supplier = BPB.id_supplier 
					WHERE AP.v_source = 'PO' GROUP BY POI.id 
					)A WHERE 1 {WHERE}  GROUP BY A.v_listcode";
    $WHERE = '';
    if(isset($param['approved'])){
        $WHERE .= "AND A.v_isapproval = 1 ";
    }
    if(isset($param['no_payment'])){
        $WHERE .= " AND A.v_listcode like '%{$param['no_payment']}%' ";
    }	
	if($source=="LOOKUP"){
		$WHERE .= " AND A.v_listcode NOT IN(SELECT reff_doc FROM fin_journal_h WHERE type_journal = '3')";
	}	
    $query = str_replace('{WHERE}', $WHERE, $query);			
	//echo $query;
	return $query;	
	
}
function GetQuery_AP($source,$param){
	$query = "
SELECT  AA.v_nojournal
       ,AA.no_payment
       ,AA.v_source
       ,AA.v_isapproval
	   ,AA.v_status
       ,AA.reff_doc
       ,AA.reff_doc2
       ,SUM(AA.bpb_amount) bpb_amount
       ,AA.nm_supplier nm_supplier	FROM(SELECT  A.v_nojournal
			,A.v_listcode no_payment
			,A.v_source
			,A.v_isapproval
			,A.v_status
			,JD.reff_doc
			,JD.reff_doc2
			,JD.debit bpb_amount
			,MS.supplier nm_supplier			
				FROM fin_status_journal_ap A LEFT JOIN
					(SELECT id_journal
						,SUM(debit)debit
						,reff_doc
						,reff_doc2
						FROM fin_journal_d GROUP BY id_journal	
					)JD ON A.v_nojournal = JD.id_journal 
				LEFT JOIN bpb BPB ON BPB.bpbno_int = JD.reff_doc
				LEFT JOIN mastersupplier MS ON MS.Id_Supplier = BPB.id_supplier 
				WHERE 1 {WHERE}  AND A.v_source = 'KB' 
				GROUP BY v_nojournal,A.v_listcode
				)AA GROUP BY AA.no_payment
			UNION ALL
			SELECT   A.v_nojournal
					,A.v_listcode no_payment
					,A.v_source
					,A.v_isapproval
					,A.v_status
					,A.reff_doc
					,A.reff_doc2
					,sum(ifnull(A.amount,0))bpb_amount
					,A.Supplier nm_supplier FROM(
			SELECT AP.v_nojournal
					,AP.v_listcode
					,AP.v_source
					,AP.v_isapproval
					,AP.v_status
					,'' reff_doc
					,'' reff_doc2
					,(BPB.qty * BPB.price)amount
					,POI.id
					,MS.Supplier
					FROM fin_status_journal_ap AP
					LEFT JOIN(
						SELECT id,pono FROM po_header
					)POH ON AP.v_nojournal = POH.pono
					LEFT JOIN(
						SELECT id_po,(ifnull(qty,0) * ifnull(price,0) )amount_po,id FROM po_item 
					)POI ON POH.id = POI.id_po 
					LEFT JOIN bpb BPB ON BPB.pono = AP.v_nojournal
					LEFT JOIN mastersupplier MS ON MS.Id_Supplier = BPB.id_supplier 
					WHERE AP.v_source = 'PO' GROUP BY POI.id 
					)A WHERE 1 {WHERE}  GROUP BY A.v_listcode";
					 
    $WHERE = '';
    if(isset($param['approved'])){
        $WHERE .= "AND A.v_isapproval = 1 ";
    }
    if(isset($param['no_payment'])){
        $WHERE .= " AND A.v_listcode like '%{$param['no_payment']}%' ";
    }	
	if($source=="LOOKUP"){
		$WHERE .= " AND A.v_listcode NOT IN(SELECT reff_doc FROM fin_journal_h WHERE type_journal = '3') AND A.v_status !='C'";
	}	
    $query = str_replace('{WHERE}', $WHERE, $query);			
	//echo $query;
	return $query;
}

