<?php
# display no errs to user
//error_reporting(E_ALL);
error_reporting(0);
ini_set('display_errors', 1);
include __DIR__.'/../../include/conn.php';
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

//include '../../../include/conn.php';
//debug
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
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

	public function period($period){
		$arr_periode = explode('/',$period);
		if(intval($arr_periode[0]) < 10){
			$bulan = "0".intval($arr_periode[0]);
		}else{
			$bulan = $arr_periode[0];
		}
		return $bulan."/".$arr_periode[1];
	}

    public function is_period_available($period){
        global $con_new;
		$period = $this->period($period);

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
		'17' => 'Jurnal Subcon',
		'18' => 'Jurnal Reverse',
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
		'18' => 'RJ',
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
		'SUBCON' => '17',	
		'REVERSE' => '18',	
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


function generate_packing_list($journal_code, $journal_date,$con_new__){
    //global $con_new;

    //Temporary override
   
	
    $year = date('Y', strtotime($journal_date));
    $sql = "
            SELECT sequence FROM fin_journal_pool WHERE segment = '$journal_code' and year='$year';
        ";
		//echo $sql;
    $pool = mysqli_fetch_object(mysqli_query($con_new__, $sql));
    $new_seq = 1;
    if(is_null($pool)){
        mysqli_query($con_new__, "INSERT INTO fin_journal_pool (segment, year, sequence) VALUES ('$journal_code', '$year', '$new_seq');");
    }else{
        $new_seq = $pool->sequence + 1;
        mysqli_query($con_new__, "UPDATE fin_journal_pool SET sequence = '$new_seq' WHERE segment = '$journal_code' and `year`='$year';");
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
}
function check_bpb($id,$type_nya,$ju_type, $checking = TRUE, $id2=''){
    global $con_new;
	
	$where_poi = "";
	if(ISSET($type_nya)){
		if($type_nya == 'WIP'){
			$is_jasa = "Y";
		}else{
			$is_jasa = "N";
		}
	}	
	
//echo $id;
    $Bm = new Basemodel($con_new);
$j_type = '99';
if(ISSET($ju_type)){
	$j_type= $ju_type;
	
}
if($type_nya == "WIP"){
	if($j_type=='14'){
		if($id2){
			$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_d WHERE trim(reff_doc) LIKE '%".trim($id)."%' AND trim(reff_doc) = '".trim($id2)."' ")->row();
		}else{                                                                                
			$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_d WHERE trim(reff_doc) LIKE '%".trim($id)."%' "  )->row();
		}	 		
		
	}else{
		if($id2){
			$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($id)."%' AND trim(reff_doc) = '".trim($id2)."' AND type_journal NOT IN('15','16') ")->row();
		}else{                                                                                
			$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($id)."%' AND type_journal NOT IN('15','16') "  )->row();
		}	  		
		
	}                                                                                    
																								
}else{      
		if($j_type=='14'){
			if($id2){                                                                                  
				$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_d WHERE trim(reff_doc) LIKE '%".trim($id)."%' AND trim(reff_doc) LIKE '%".trim($id2)."%' ")->row();
			}else{                                                                          
				$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_d WHERE trim(reff_doc) LIKE '%".trim($id)."%'")->row();
			}				
						
		}else{
			if($id2){                                                                                  
				$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($id)."%' AND trim(reff_doc) LIKE '%".trim($id2)."%' ")->row();
			}else{                                                                          
				$used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($id)."%'")->row();
			}				
		}
                                                                                 
	
}

/*     if($id2){
        $used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) = '$id' AND trim(reff_doc) LIKE '%".trim($id2)."%' ")->row();
    }else{
        $used = $Bm->query("SELECT trim(reff_doc) FROM fin_journal_h WHERE trim(reff_doc) = '$id'")->row();
    } */

    if($checking) {
        if (!is_null($used)) {
			
 $bpb = $Bm->query("SELECT * FROM bpb WHERE bpbno_int = '$id'")->result();


			$bpb_exist  = 'N';
			$po_exist   = 'N';
			$pono       = '-';
			$id_po_det  = '-';
			$id_bpb     = '-';
			$qty_po     = '0';
			$qty_bpb    = '0';
			$price_po   = '0';
			$price_bpb  = '0';
			$id_item_po = '';
			$id_item_bpb= '';
			$n_nilai    = '0';	
		
		$hist = array(
			'bpbno_int' 	=>$id,
			'bpb_exist' 	=>$bpb_exist,  
			'po_exist'  	=>$po_exist,  
			'pono'      	=>$pono,       
			'id_po_det' 	=>$id_po_det,  
			'id_bpb'    	=>$id_bpb,     
			'qty_po'    	=>$qty_po,    
			'qty_bpb'   	=>$qty_bpb,    
			'price_po'  	=>$price_po,   
			'price_bpb' 	=>$price_bpb,  
			'id_item_po'	=>$id_item_po, 
			'id_item_bpb'	=>$id_item_bpb,
			'n_nilai'   	=>$n_nilai    
		);
		history_log_purchase($hist);						
			
			
            return array(
                'status' => false,
                'errcode' => '01',
                'message' => 'BPB number already used'
            );
        }
    } 

   
		
		
    $bpb_detail = $Bm->query(
"SELECT MASTER.* FROM (
		SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,poi.price SAVE_price
				,(bpb.qty*poi.price)SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,'3' SAVE_type_bpb		
                ,bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
				,'Y' is_jasa
            FROM 
                bpb 
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = bpb.id_item AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				
			WHERE 
                bpb.bpbno_int = '$id' AND poi.cancel = 'N' group by bpb.id
				UNION ALL
            SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )SAVE_price
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',bpb.qty*poi.price,bpb.qty*bpb.price )SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/','4',if(SUBSTR(bpb.bpbno_int,1,3)='GEN','2','1'))SAVE_type_bpb
                ,bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn				
				,bpb.bpbdate
				,bpb.id_supplier
				,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*bpb.price)) + bpb.qty*bpb.price )nilai
				,'N' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
            WHERE 
                bpb.bpbno_int = '$id' IS NOT NULL group by bpb.id

				)MASTER WHERE MASTER.is_jasa = '$is_jasa'"		
		)->result();
		
		if(!$bpb_detail[0]->bpbno_int){
			$bpb_exist  = 'N';
			$po_exist   = 'N';
			$pono       = '-';
			$id_po_det  = '-';
			$id_bpb     = '-';
			$qty_po     = '0';
			$qty_bpb    = '0';
			$price_po   = '0';
			$price_bpb  = '0';
			$id_item_po = '';
			$id_item_bpb= '';
			$n_nilai    = '0';
		}else{
			$bpb_exist  = 'Y';
				if($bpb_detail[0]->pono){
					$po_exist = 'Y';
				}else{
					$po_exist = 'N';
				}
			$pono       = $bpb_detail[0]->pono;
			$id_po_det  = $bpb_detail[0]->id_po_det;
			$id_bpb     = $bpb_detail[0]->id_bpb;
			$qty_po     = $bpb_detail[0]->qty_po;
			$qty_bpb    = $bpb_detail[0]->qty;
			$price_po   = $bpb_detail[0]->price_po;
			$price_bpb  = $bpb_detail[0]->price;
			$id_item_po = $bpb_detail[0]->id_gen;
			$id_item_bpb= $bpb_detail[0]->id_item;
			$n_nilai    = $bpb_detail[0]->nilai;			
			
		}
		//print_r($bpb_detail);
		//die();
		$hist = array(
			'bpbno_int' 	=>$id,
			'bpb_exist' 	=>$bpb_exist,  
			'po_exist'  	=>$po_exist,  
			'pono'      	=>$pono,       
			'id_po_det' 	=>$id_po_det,  
			'id_bpb'    	=>$id_bpb,     
			'qty_po'    	=>$qty_po,    
			'qty_bpb'   	=>$qty_bpb,    
			'price_po'  	=>$price_po,   
			'price_bpb' 	=>$price_bpb,  
			'id_item_po'	=>$id_item_po, 
			'id_item_bpb'	=>$id_item_bpb,
			'n_nilai'   	=>$n_nilai    
		);
		history_log_purchase($hist);		
		
		
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $bpb_detail
    );
}



function get_id_coa_ir_from_bpb($bpb){
	    global $con_new;
    $Bm = new Basemodel($con_new);

        $mapping_coa = $Bm->query("
            SELECT mp.ir_d, mcd.nm_coa nm_coa_d, mp.ir_k, mck.nm_coa nm_coa_k
            FROM mapping_coa mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.ir_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.ir_k
            WHERE 
            id_group = '{$bpb['id_group']}' and vendor_cat = '{$bpb['vendor_cat']}' 
        ")->row();

        $coa_debit = array(
            'id' => $mapping_coa->ir_d,
            'nm' => $mapping_coa->nm_coa_d
        );
        $coa_credit = array(
            'id' => $mapping_coa->ir_k,
            'nm' => $mapping_coa->nm_coa_k
        );
        return array(
            $coa_debit,
            $coa_credit
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

function get_id_coa_wip_ir($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno_int
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
            LEFT JOIN mastercf MCF ON MCF.cfdesc = MI.matclass			
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = MCF.cfcode
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno_int']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int";
    $mapping_coa = $Bm->query($q)->row();


    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_ir,
        'nm' => $mapping_coa->nama_coa_ir_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_ir,
        'nm' => $mapping_coa->nama_coa_ir_cre
		);
		
		
    return array(
        $coa_debit,
        $coa_credit
    );
}


function get_id_coa_fg_ir($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno_int
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
		LEFT JOIN(
			SELECT MY.itemname,POI.id_gen,POH.app,POI.cancel,BPB_NYA.* FROM(
			SELECT * FROM bpb WHERE bpbno_int = '{$bpb['bpbno_int']}')BPB_NYA
			LEFT JOIN (
			
			SELECT * FROM masterstyle )MY ON BPB_NYA.id_so_det = MY.id_so_det
			LEFT JOIN(
			
			SELECT * FROM so_det)SOD ON SOD.id = MY.id_so_det
			LEFT JOIN(
			SELECT * FROM jo_det)JOD ON JOD.id_so = SOD.id_so
			LEFT JOIN(
			SELECT * FROM po_item)POI ON POI.id_jo = JOD.id_jo
			INNER JOIN(
			SELECT * FROM po_header)POH ON POH.id = POI.id_po AND BPB_NYA.pono = POH.pono
			INNER JOIN(
			 SELECT * FROM masteritem
			)MI ON MI.id_item = POI.id_gen)FG ON BPB.id = FG.id		
			INNER JOIN masteritem MI ON FG.id_gen = MI.id_item 
            LEFT JOIN mastercf MCF ON MCF.cfdesc = MI.matclass			
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = MCF.cfcode
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno_int']}' AND MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int
			";
/* 			echo "<pre>$q</pre>";
			die(); */
    $mapping_coa = $Bm->query($q)->row();

    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_ir,
        'nm' => $mapping_coa->nama_coa_ir_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_ir,
        'nm' => $mapping_coa->nama_coa_ir_cre
		);
		
    return array(
        $coa_debit,
        $coa_credit
    );
}

function get_id_coa_fg($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno_int
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
		LEFT JOIN(
			SELECT MY.itemname,POI.id_gen,POH.app,POI.cancel,BPB_NYA.* FROM(
			SELECT * FROM bpb WHERE bpbno_int = '{$bpb['bpbno']}')BPB_NYA
			LEFT JOIN (
			
			SELECT * FROM masterstyle )MY ON BPB_NYA.id_so_det = MY.id_so_det
			LEFT JOIN(
			
			SELECT * FROM so_det)SOD ON SOD.id = MY.id_so_det
			LEFT JOIN(
			SELECT * FROM jo_det)JOD ON JOD.id_so = SOD.id_so
			LEFT JOIN(
			SELECT * FROM po_item)POI ON POI.id_jo = JOD.id_jo
			INNER JOIN(
			SELECT * FROM po_header)POH ON POH.id = POI.id_po AND BPB_NYA.pono = POH.pono
			INNER JOIN(
			 SELECT * FROM masteritem
			)MI ON MI.id_item = POI.id_gen)FG ON BPB.id = FG.id		
			INNER JOIN masteritem MI ON FG.id_gen = MI.id_item 
            LEFT JOIN mastercf MCF ON MCF.cfdesc = MI.matclass			
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = MCF.cfcode
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno']}' AND MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int
			";
    $mapping_coa = $Bm->query($q)->row();

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


function get_id_coa_wip($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno_int
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
            LEFT JOIN mastercf MCF ON MCF.cfdesc = MI.matclass			
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code = MCF.cfcode
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int";
    $mapping_coa = $Bm->query($q)->row();

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

function get_id_coa_general_ir($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,BPB.bpbno_int
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category  
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno_int']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int";
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category  
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE MCG.n_code = 'UTL' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int";
    $mapping_coa = $Bm->query($q)->row();
}
    $coa_debit = array(
        'id' => $mapping_coa->id_coa_debit_ir,
        'nm' => $mapping_coa->nama_coa_ir_deb
    );
    $coa_credit = array(
        'id' => $mapping_coa->id_coa_credit_ir,
        'nm' => $mapping_coa->nama_coa_ir_cre
    );
    return array(
        $coa_debit,
        $coa_credit
    );
	
	
}


function get_id_coa_general($bpb){
		 global $con_new;
	    $Bm = new Basemodel($con_new);
$q = "SELECT BPB.id_item
		, BPB.bpbno
		,BPB.bpbno_int
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category 
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE BPB.bpbno_int = '{$bpb['bpbno']}' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int LIMIT 1";
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
			INNER JOIN mapping_coa_gen MCG ON MCG.n_code_category = MI.n_code_category  
			INNER JOIN mastercoa COA_GR_DEB 
				ON COA_GR_DEB.id_coa = MCG.id_coa_debit_gr
			INNER JOIN mastercoa COA_GR_CRE 
				ON COA_GR_CRE.id_coa = MCG.id_coa_credit_gr
			INNER JOIN mastercoa COA_IR_DEB 
				ON COA_IR_DEB.id_coa = MCG.id_coa_debit_ir
			INNER JOIN mastercoa COA_IR_CRE 
				ON COA_IR_CRE.id_coa = MCG.id_coa_credit_ir
			WHERE MCG.n_code = 'UTL' and MCG.n_vendor_cat = '{$bpb['vendor_cat']}'  GROUP BY BPB.bpbno_int LIMIT 1";
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

function history_log_purchase($hist){
	global $con_new;
	$Bm = new Basemodel($con_new);
	/*
			$bpb_exist  = 'N';
			$po_exist   = 'N';
			$pono       = '-';
			$id_po_det  = '-';
			$id_bpb     = '-';
			$qty_po     = '0';
			$qty_bpb    = '0';
			$price_po   = '0';
			$price_bpb  = '0';
			$id_item_po = '';
			$id_item_bpb= '';
			$n_nilai    = '0';	
	
	
	*/
	$input_sql = "INSERT INTO fin_history_auto_bpb (
					bpbno_int
					,pono
					,id_po_det
					,id_bpb
					,qty_po
					,qty_bpb
					,price_po
					,price_bpb
					,id_item_po
					,id_item_bpb
					,is_exist_bpb
					,is_exist_po
					,n_nilai
					) VALUES(
		'{$hist['bpbno_int']}',
		'{$hist['pono']}',
		'{$hist['id_po_det']}',
		'{$hist['id_bpb']}',
		'{$hist['qty_po']}',
		'{$hist['qty_bpb']}',
		'{$hist['price_po']}',
		'{$hist['price_bpb']}',
		'{$hist['id_item_po']}',
		'{$hist['id_item_bpb']}',
		'{$hist['bpb_exist']}',
		'{$hist['po_exist']}',
		'{$hist['n_nilai']}'
	
	)";
	//echo $input_sql;
	//die();
	$Bm->query($input_sql);
}
function check_bpb_update($id,$type_nya){
	$where_poi = "";
	if(ISSET($type_nya)){
		if($type_nya == 'WIP'){
			$is_jasa = "Y";
		}else{
			$is_jasa = "N";
			
		}
	}	
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
		$sql="	SELECT MASTER.* FROM (
				SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,poi.price SAVE_price
				,((ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price)SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,'3' SAVE_type_bpb		
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,bpb.price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*poi.price)) + bpb.qty*poi.price )nilai
				,'Y' is_jasa
            FROM 
                bpb 
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = bpb.id_item AND bpb.id_jo = poi.id_jo
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier
				
			WHERE 
                bpb.bpbno_int = '$id' AND poi.cancel = 'N' AND POH.pono IS NOT NULL group by bpb.id
				UNION ALL
            SELECT 
				 bpb.id SAVE_id_bpb
				,poh.id SAVE_id_po
				,poh.pono SAVE_pono
				,bpb.id_item SAVE_id_item
				,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)SAVE_qty
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )SAVE_price
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',(ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0))*poi.price,bpb.qty*bpb.price )SAVE_amount_ori
				,poi.id SAVE_id_po_det
				,bpb.id_supplier SAVE_id_supplier
				,if(SUBSTR(bpb.bpbno_int,1,3)='FG/','4',if(SUBSTR(bpb.bpbno_int,1,3)='GEN','2','1'))SAVE_type_bpb
				,poh.ppn SAVE_ppn
                ,bpb.bpbno
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn				
				,bpb.bpbdate
				,bpb.id_supplier
				,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,bpb.curr
                ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,((ifnull(poh.ppn,0)/100)*((bpb.qty*bpb.price)) + bpb.qty*bpb.price )nilai
				,'N' is_jasa
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
            WHERE 
                bpb.bpbno_int = '$id'  group by bpb.id

				)MASTER WHERE MASTER.is_jasa = '$is_jasa'";
		$bpb_detail = $Bm->query($sql)->result();			
/* 		echo "<pre>";
		print_r($bpb_detail);
		echo "</pre>";
		die();
			 */
		
		if(!$bpb_detail[0]->bpbno_int){
			$bpb_exist  = 'N';
			$po_exist   = 'N';
			$pono       = '-';
			$id_po_det  = '-';
			$id_bpb     = '-';
			$qty_po     = '0';
			$qty_bpb    = '0';
			$price_po   = '0';
			$price_bpb  = '0';
			$id_item_po = '';
			$id_item_bpb= '';
			$n_nilai    = '0';
		}else{
			$bpb_exist  = 'Y';
				if($bpb_detail[0]->pono){
					$po_exist = 'Y';
				}else{
					$po_exist = 'N';
				}
			$pono       = $bpb_detail[0]->pono;
			$id_po_det  = $bpb_detail[0]->id_po_det;
			$id_bpb     = $bpb_detail[0]->id_bpb;
			$qty_po     = $bpb_detail[0]->qty_po;
			$qty_bpb    = $bpb_detail[0]->qty;
			$price_po   = $bpb_detail[0]->price_po;
			$price_bpb  = $bpb_detail[0]->price;
			$id_item_po = $bpb_detail[0]->id_gen;
			$id_item_bpb= $bpb_detail[0]->id_item;
			$n_nilai    = $bpb_detail[0]->nilai;			
		}
		//print_r($bpb_detail);
		//die();
		$hist = array(
			'bpbno_int' 	=>$id,
			'bpb_exist' 	=>$bpb_exist,  
			'po_exist'  	=>$po_exist,  
			'pono'      	=>$pono,       
			'id_po_det' 	=>$id_po_det,  
			'id_bpb'    	=>$id_bpb,     
			'qty_po'    	=>$qty_po,    
			'qty_bpb'   	=>$qty_bpb,    
			'price_po'  	=>$price_po,   
			'price_bpb' 	=>$price_bpb,  
			'id_item_po'	=>$id_item_po, 
			'id_item_bpb'	=>$id_item_bpb,
			'n_nilai'   	=>$n_nilai    
		);
		history_log_purchase($hist);
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $bpb_detail
    );
}


function check_invoice_exist($bpbno){

		$type_nya = substr($bpbno,0,3);
	if($type_nya == "WIP"){
$type_journal = '17';
	}else{
$type_journal = '2';
	}
	
	
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $bpb_exist = $Bm->query(
"SELECT reff_doc,type_journal,id_journal FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($bpbno)."%'  AND type_journal ='1' LIMIT 1"		
		)->row();
		if(count($bpb_exist) > 0 ){
			$key = '1';
			
		}else{
			$key = '0';
		}

    return $key;
}


function check_bpb_reverse($bpbno){

		$type_nya = substr($bpbno,0,3);
	if($type_nya == "WIP"){
$type_journal = '17';
	}else{
$type_journal = '2';
	}
	
	
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $bpb_exist = $Bm->query(
"SELECT reff_doc,type_journal,id_journal FROM fin_journal_h WHERE trim(reff_doc) LIKE '%".trim($bpbno)."%'  LIMIT 1"		
		)->row();
		if(count($bpb_exist) > 0 ){
			//	print_r($bpb_exist);
			//	die();
			if($bpb_exist->type_journal == '2' && $type_nya == 'WIP'){
				$key = '0';
			}else{
				$key = '1';
			}
			
		}else{
			$key = '0';
		}

    return $key;
}

function check_bpb_exist($bpbno){
		$type_nya = substr($bpbno,0,3);
	if($type_nya == "WIP"){
$type_journal = '17';
	}else{
$type_journal = '2';
	}
	
	
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $bpb_exist = $Bm->query(
"SELECT reff_doc,type_journal,id_journal FROM fin_journal_h WHERE trim(reff_doc) = '".trim($bpbno)."'  LIMIT 1"		
		)->row();
		if(count($bpb_exist) > 0 ){
			//	print_r($bpb_exist);
			//	die();
			if($bpb_exist->type_journal == '2' && $type_nya == 'WIP'){

				delete_journal_h($bpb_exist->id_journal);
				delete_journal_d($bpb_exist->id_journal);
				$key = '0';
			}else{
				$key = '1';
			}
			
		}else{
			$key = '0';
		}

    return $key;
}



function Get_Journal_By_Bpb($bpb_nya){
    global $con_new;
    $Bm = new Basemodel($con_new);	
	 $bpb_detail = $Bm->query("SELECT * FROM bpb WHERE bpbno_int = '$id'")->result();
}

function Update_Cancel_bpb($bpb_nya){
    global $con_new;
    $Bm = new Basemodel($con_new);
	$sql ="UPDATE bpb SET is_cancel ='N',d_cancel = NULL WHERE bpbno_int='$bpb_nya'";	
	return $Bm->query($sql)->result();
}

function insert_bpb_gr($bpbno)
{
    global $con_new;
    $Bm = new Basemodel($con_new);
	Update_Cancel_bpb($bpbno);
	$type_nya = substr($bpbno,0,3);
	if($type_nya == "WIP"){
		 $journal_code = Config::$journal_code['17'];
		 $usages ='SUBCON';
	}else{
		 $journal_code = Config::$journal_code['2'];
		 $usages ='PURCHASE';
	}
    $journal_date = date('Y-m-d', time());
   // $id_journal = generate_coa_id("NAG", $journal_code, $journal_date);
    $IsGeneral = IsGeneral($bpbno);
	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}
	if($type_nya == "FG/"){
		$IsGeneral = "3";
	}	

    $bpb_request = check_bpb_update($bpbno,$type_nya,'99');
//print_r($bpb_request);
    if($bpb_request['status']){
		$id_journal = generate_coa_id("NAG", $journal_code, $bpb_request['data'][0]->bpbdate);
        $company = get_master_company();
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];
        $d = array(
            'company' => $company->company,
            'period' => date('m/Y', strtotime($bpb_request['data'][0]->bpbdate)),
            'num_journal' => '',//$_POST['num_journal'],
            'id_journal' => $id_journal, 
            //'date_journal' => date('Y-m-d', time()),
			'date_journal' => $bpb_request['data'][0]->bpbdate,
            'type_journal' =>  Config::$journal_usage[$usages],
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
		$id_group = "";
		$vendor_cat = "";
		$curr = "";
		$fg_pkp = "0";
		$ppn_percentage = "0";
		$amount = 0;
		//print_r($bpb);
		//die();
        foreach($bpb as $_bpb){
			$_bpb          = (array) $_bpb;
			$vendor_cat    = $_bpb['vendor_cat'];
			$id_group      = $_bpb['id_group'];
			$bpb_nya       = $_bpb['bpbno_int'];
			$curr          = $_bpb['curr'];
			if($type_nya == "WIP"){
				$amount = $amount + ( $_bpb['price_po'] * $_bpb['qty']);	
				//$fg_pkp        = $_bpb['fg_pkp'];
				//$ppn_percentage= $_bpb['ppn'];						
				}else{
				$amount = $amount + ( $_bpb['price'] * $_bpb['qty']);
				//$fg_pkp        = $_bpb['fg_pkp_non_jasa'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];				
			}
			$ppn_percentage= $_bpb['ppn'];	
		}
		$___bpb[] = [];
		$___bpb['id_group'] = $id_group;
		$___bpb['vendor_cat'] = $vendor_cat;
		$___bpb['bpbno'] = $bpb_nya;
		$___bpb['fg_pkp'] = 0;
		$___bpb['ppn'] = $ppn_percentage;
		//print_r($___bpb);
		///die();
			if($IsGeneral == '1'){
				list($coa_debit, $coa_credit) = get_id_coa_general($___bpb);
			}else if($IsGeneral == '2'){
				list($coa_debit, $coa_credit) = get_id_coa_wip($___bpb);
			}else if($IsGeneral == '3'){
				list($coa_debit, $coa_credit) = get_id_coa_fg($___bpb);
			}else{
				list($coa_debit, $coa_credit) = get_id_coa_gr_from_bpb($___bpb);
			}
            // GR
            $_debit = array(
                'row_id' => '1',
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $curr,
                'debit' => $amount,
                'credit' => 0,
                'description' => $bpbno,
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            // Piutang
            $_credit = array(
                'row_id' => '2',
                'id_journal' => $id_journal,
                'id_coa' => $coa_credit['id'],
                'nm_coa' => $coa_credit['nm'],
                'curr' => $curr,
                'debit' => '0',
                'credit' => $amount,
                'description' => $bpbno,
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            $dataBpb[] = $_debit;
            $dataBpb[] = $_credit;
			if($___bpb['fg_pkp']== '1' || $___bpb['ppn'] > 0 ){
				if($___bpb['ppn'] > 0 ){
					$ppn = ($___bpb['ppn']/100)*$amount;
				}else{
					$ppn = 0;
				}
				$ppn_value = $amount + $ppn;
			//PPN
            $_debit = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '15207', //$coa_debit['id'],
				'nm_coa'     => 'PAJAK DIBAYAR DIMUKA PPN MASUKAN (UNBILLED)', //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $ppn, //$amount,
				'credit'     => 0,
				'description'=> "PPN", //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );			
				$dataBpb[] = $_debit;
				$dataBpb[1]['credit'] = $ppn_value;
			}			
		//print_r($dataBpb);
		//die();	
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

function get_journal_nya($id){
	global $con_new;
	$Bm = new Basemodel($con_new);
	        return $Bm->query("
            SELECT * FROM
            fin_journal_h jh
            WHERE TRIM(reff_doc) LIKE '%".TRIM($id)."%' AND type_journal IN('2','17','1') LIMIT 1;
        ")->row();
	
};

function delete_journal_d($id_journal_nya){
	global $con_new;
	$Bm = new Basemodel($con_new);
			            $sql = "
DELETE FROM fin_journal_d WHERE id_journal = '{$id_journal_nya}'
                ";
				//echo  $sql;
            $Bm->query($sql);	
	
}
function delete_journal_h($id_journal_nya){
	global $con_new;
	$Bm = new Basemodel($con_new);
			            $sql = "
DELETE FROM fin_journal_h WHERE id_journal = '{$id_journal_nya}'
                ";
				//echo $sql;
				//die();
            $Bm->query($sql);	
	
}
function update_bpb_gr($bpbno)
{
	

    global $con_new;

	$journal = (array) get_journal_nya($bpbno);
    $Bm = new Basemodel($con_new);
	$type_nya = substr($bpbno,0,3);
	if($type_nya == "WIP"){
		 $journal_code = Config::$journal_code['17'];
		 $usages ='SUBCON';
	}else{
		 $journal_code = Config::$journal_code['2'];
		 $usages ='PURCHASE';
	}
    $journal_date = date('Y-m-d', time());
    $id_journal = $journal['id_journal'];

    $IsGeneral = IsGeneral($bpbno);
	if($type_nya == "WIP" ){
		$IsGeneral = "2";
	}
	if($type_nya == "FG/"){
		$IsGeneral = "3";
	}
	

    $bpb_request = check_bpb_update($bpbno,$type_nya,'99');

/* echo "<pre>";
		print_r($bpb_detail);
		echo "</pre>";
		die(); */
	
    if($bpb_request['status']){
        $company = get_master_company();
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];
        $d = array(
            'company' => $company->company,
            'period' => date('m/Y', strtotime($bpb_request['data'][0]->bpbdate)),
            'num_journal' => '',//$_POST['num_journal'],
            'id_journal' => $id_journal, 
            //'date_journal' => date('Y-m-d', time()),
			'date_journal' => $bpb_request['data'][0]->bpbdate,
            'type_journal' =>  Config::$journal_usage[$usages],
            'reff_doc' => $bpbno,
            'src_reference' => 'BPB',
            'dateadd' => $dateadd,
            'useradd' => $user,
            'fg_post' => '2',
            'date_post' => $dateadd,
            'user_post' => $user,
        );
        // Insert Detail
        $dataBpb = array();
        $bpb = $bpb_request['data'];
        $row_id = 1;	
		$id_group = "";
		$vendor_cat = "";
		$curr = "";
		$fg_pkp = "0";
		$ppn_percentage = "0";
		$amount = 0;
        foreach($bpb as $_bpb){
			$_bpb          = (array) $_bpb;
			$vendor_cat    = $_bpb['vendor_cat'];
			$id_group      = $_bpb['id_group'];
			$bpb_nya       = $_bpb['bpbno_int'];
			$curr          = $_bpb['curr'];
			if($type_nya == "WIP"){
				$amount = $amount + ( $_bpb['price_po'] * $_bpb['qty']);	
				//$fg_pkp        = $_bpb['fg_pkp'];
				//$ppn_percentage= $_bpb['ppn'];						
				}else{
				$amount = $amount + ( $_bpb['price'] * $_bpb['qty']);
				//$fg_pkp        = $_bpb['fg_pkp_non_jasa'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];				
			}
			$ppn_percentage= $_bpb['ppn'];	
		}
		$___bpb[] = [];
		$___bpb['id_group'] = $id_group;
		$___bpb['vendor_cat'] = $vendor_cat;
		$___bpb['bpbno'] = $bpb_nya;
		$___bpb['fg_pkp'] = 0;
		$___bpb['ppn'] = $ppn_percentage;

			if($IsGeneral == '1'){
				list($coa_debit, $coa_credit) = get_id_coa_general($___bpb);
			}else if($IsGeneral == '2'){
				list($coa_debit, $coa_credit) = get_id_coa_wip($___bpb);

			}else if($IsGeneral == '3'){
				list($coa_debit, $coa_credit) = get_id_coa_fg($___bpb);

			}else{
				list($coa_debit, $coa_credit) = get_id_coa_gr_from_bpb($___bpb);
			}
            // GR
            $_debit = array(
                'row_id' => '1',
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $curr,
                'debit' => $amount,
                'credit' => 0,
                'description' => $bpbno,
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            // Piutang
            $_credit = array(
                'row_id' => '2',
                'id_journal' => $id_journal,
                'id_coa' => $coa_credit['id'],
                'nm_coa' => $coa_credit['nm'],
                'curr' => $curr,
                'debit' => '0',
                'credit' => $amount,
                'description' => $bpbno,
                'dateadd' => $dateadd,
                'useradd' => $user,
            );
            $dataBpb[] = $_debit;
            $dataBpb[] = $_credit;
			if($___bpb['fg_pkp']== '1' || $___bpb['ppn'] > 0 ){
				if($___bpb['ppn'] > 0 ){
					$ppn = ($___bpb['ppn']/100)*$amount;
				}else{
					$ppn = 0;
				}
				$discount_value = $amount + $ppn;
			//PPN
            $_debit = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '15207', //$coa_debit['id'],
				'nm_coa'     => 'PAJAK DIBAYAR DIMUKA PPN MASUKAN (UNBILLED)', //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $ppn, //$amount,
				'credit'     => 0,
				'description'=> "PPN", //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );			
				$dataBpb[] = $_debit;
				$dataBpb[1]['credit'] = $discount_value;
			}			
	

	
	
		$xx = 0;
		delete_journal_d($id_journal);
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

function check_inv_update($id, $checking = TRUE)
{
    global $con_new;
    $Bm = new Basemodel($con_new);



	$q="    SELECT 	
	 INV.invno
    ,INV.invdate
	,INV.fg_discount
	,INV.n_discount
    ,(SUM(INV.invoice_amount))invoice_amount
    ,INV.curr
    ,INV.tax
    ,INV.supplier
    ,INV.vendor_cat
    ,INV.area
    ,INV.idsod
    ,INV.bppbno
    ,INV.pono
    ,INV.description
    ,INV.status_order
    /*,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order*/
    ,'A' grade
FROM(
SELECT 
                 ih.invno
                ,ih.invdate
				,ih.fg_discount
				,ifnull(ih.n_discount,0)n_discount
                ,(id.qty * id.price) invoice_amount
                ,so.curr
				,so.tax
                ,ms.supplier
                ,ms.vendor_cat
                ,ms.area
				,sd.id idsod
				,ifnull(bppb.bppbno,'')bppbno
				,ifnull(bpb.pono,'')pono
                ,CONCAT(mp.product_item,' - ', sd.color, ' - ',sd.size) description
				,ifnull(ac.type_ws,'FOB')status_order
                /*,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order*/
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
                ih.invno = '$id' GROUP BY ih.invno,id.id)INV 
				GROUP BY INV.invno";
    $detail = $Bm->query($q)->result();
//echo "<pre>$q</pre>";
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'INV number found',
        'data'  => $detail
    );
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

	$q="    SELECT 	
	 INV.invno
    ,INV.invdate
    ,(SUM(INV.invoice_amount))invoice_amount
    ,INV.curr
    ,INV.tax
    ,INV.supplier
    ,INV.vendor_cat
    ,INV.area
    ,INV.idsod
    ,INV.bppbno
    ,INV.pono
    ,INV.description
    ,INV.status_order
    /*,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order*/
    ,'A' grade
FROM(
SELECT 
                 ih.invno
                ,ih.invdate
                ,(id.qty * id.price) invoice_amount
                ,so.curr
				,so.tax
                ,ms.supplier
                ,ms.vendor_cat
                ,ms.area
				,sd.id idsod
				,ifnull(bppb.bppbno,'')bppbno
				,ifnull(bpb.pono,'')pono
                ,CONCAT(mp.product_item,' - ', sd.color, ' - ',sd.size) description
				,ifnull(ac.type_ws,'FOB')status_order
                /*,case when ac.status_order='CMT' THEN 'CMT' ELSE 'FOB' END status_order*/
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
                ih.invno = '$id' GROUP BY ih.invno,id.id)INV GROUP BY INV.invno";
    $detail = $Bm->query($q)->result();

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
	$inv['vendor_cat'] = $inv['vendor_cat']=='NG'?'NG':'GR';
$q= "SELECT MAPP.sl_d ID_COA_FOB_DEBIT
	  ,MAPP.sl_d_c ID_COA_CMT_DEBIT
	  ,MAPP.sl_k ID_COA_FOB_CREDIT
	  ,MAPP.sl_k_c ID_COA_CMT_CREDIT
	  ,MAPP.disc_d ID_COA_DISCOUNT_DEBIT
	  ,MAPP.disc_k ID_COA_DISCOUNT_CREDIT
      ,FOB_D.nm_coa NM_COA_FOB_DEBIT
	  ,FOB_C.nm_coa NM_COA_FOB_CREDIT
	  ,CMT_C.nm_coa NM_COA_CMT_DEBIT
	  ,CMT_D.nm_coa NM_COA_CMT_CREDIT
	  ,DISCOUNT_C.nm_coa NM_COA_DISCOUNT_CREDIT
	  ,DISCOUNT_D.nm_coa NM_COA_DISCOUNT_DEBIT
	  
	   FROM mapping_coa_sales MAPP
	   LEFT JOIN mastercoa FOB_D ON FOB_D.id_coa = MAPP.sl_d
	   LEFT JOIN mastercoa FOB_C ON FOB_C.id_coa = MAPP.sl_k
	   LEFT JOIN mastercoa CMT_C ON CMT_C.id_coa = MAPP.sl_d_c
	   LEFT JOIN mastercoa CMT_D ON CMT_D.id_coa = MAPP.sl_k_c
	   LEFT JOIN mastercoa DISCOUNT_C ON DISCOUNT_C.id_coa = MAPP.disc_k
	   LEFT JOIN mastercoa DISCOUNT_D ON DISCOUNT_D.id_coa = MAPP.disc_d	   
	   WHERE 1 =1  AND MAPP.area = '{$inv['area']}'
	   AND MAPP.vendor_cat = '{$inv['vendor_cat']}'";
	   

    $mapping_coa = $Bm->query($q)->row();

			$coa_debit_fob = array(
				'id' => $mapping_coa->ID_COA_FOB_DEBIT,
				'nm' => $mapping_coa->NM_COA_FOB_DEBIT
			);
			$coa_credit_fob = array(
				'id' => $mapping_coa->ID_COA_FOB_CREDIT,
				'nm' => $mapping_coa->NM_COA_FOB_CREDIT
			);			

			$coa_debit_cmt = array(
				'id' => $mapping_coa->ID_COA_CMT_DEBIT,
				'nm' => $mapping_coa->NM_COA_CMT_DEBIT
			);		


			$coa_credit_cmt = array(
				'id' => $mapping_coa->ID_COA_CMT_CREDIT,
				'nm' => $mapping_coa->NM_COA_CMT_CREDIT
			);			


			$coa_debit_discount = array(
				'id' => $mapping_coa->ID_COA_DISCOUNT_DEBIT,
				'nm' => $mapping_coa->NM_COA_DISCOUNT_DEBIT
			);		


			$coa_credit_discount = array(
				'id' => $mapping_coa->ID_COA_DISCOUNT_CREDIT,
				'nm' => $mapping_coa->NM_COA_DISCOUNT_CREDIT
			);						

    return array(
        $coa_debit_fob,
        $coa_credit_fob,
        $coa_debit_cmt,
        $coa_credit_cmt,
        $coa_debit_discount,
        $coa_credit_discount			
    );
}



function update_inv_saless($invno)
{


    global $con_new; 
    $Bm = new Basemodel($con_new);
	$invoice_request = check_inv_update($invno);
	
    $journal = (array) get_journal_nya($invno);
    $journal_date = date('Y-m-d', time());
    $id_journal = $journal['id_journal'];
    if($invoice_request['status']){
        $company = get_master_company();
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];

        $dataInvoice = array();
        $invoices = $invoice_request['data'];
		$bpb    = '';
		$n_amt  = 0;
        $row_id = 1;
		$po     = '';
		$_var = 0;
		$tax = $invoice_request['data'][0]->tax;
		$status_order =$invoice_request['data'][0]->status_order;

		$total_amount = 0;
		$discount = 0;
		$amount_discount = 0;
		$discount_value = 0;
		$discount_nya = 0;
		$fg_discount_nya = 0;		
		//print_r($invoices);
        foreach($invoices as $_inv){
            $_inv = (array) $_inv;
			$bpb = $_inv['bppbno'];
			$po = $_inv['pono'];	
			$discount_nya = $_inv['n_discount'];
			$fg_discount_nya = $_inv['fg_discount'];			
           //$amount = $_inv['invoice_amount'];	
			$total_amount = $total_amount + $_inv['invoice_amount'];
		
			
            list($coa_debit_fob, $coa_credit_fob,$coa_debit_cmt, $coa_credit_cmt,$coa_debit_discount, $coa_credit_discount) = get_id_coa_sales_from_inv($_inv);
			

			if($status_order == 'FOB'){
			if($_var == "0"){
				$id_coa_debit = $coa_debit_fob['id'];
				$nm_coa_debit = $coa_debit_fob['nm'];
				$id_coa_credit = $coa_credit_fob['id'];
				$nm_coa_credit = $coa_credit_fob['nm'];				
				$curr = $_inv['curr'];
			}				
				
			}else {
				if($_var == "0"){
				$id_coa_debit = $coa_debit_cmt['id'];
				$nm_coa_debit = $coa_debit_cmt['nm'];
				$id_coa_credit = $coa_credit_cmt['id'];
				$nm_coa_credit = $coa_credit_cmt['nm'];				
				$curr = $_inv['curr'];		
				}				
			}
			//potongan kepala...
				$id_coa_debit_discount = $coa_debit_discount['id'];
				$nm_coa_debit_discount = $coa_debit_discount['nm'];
				$id_coa_credit_discount = $coa_credit_discount['id'];
				$nm_coa_credit_discount = $coa_credit_discount['nm'];			

			
			
			//potongan 

        }

            // Piutang
            $_debit = array(
				'row_id'     => 1, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_debit, //$coa_debit['id'],
				'nm_coa'     => $nm_coa_debit, //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $total_amount, //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            // Sales
            $_credit = array(
				'row_id'     => 2, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_credit, //$coa_credit['id'],
				'nm_coa'     => $nm_coa_credit, //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $total_amount, //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            $dataInvoice[] = $_debit;
            $dataInvoice[] = $_credit;	
		
			//potongan
			if($discount_nya != '0' || $discount_nya != '0'  ){
	
				$param_ppn = 5;
				$amount_discount = ($discount_nya/100)*$total_amount;
            $_debit_discount = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_debit_discount, //$coa_debit['id'],
				'nm_coa'     => $nm_coa_debit_discount, //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $amount_discount , //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            // Sales
            $_credit_discount = array(
				'row_id'     => 4, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_credit_discount, //$coa_credit['id'],
				'nm_coa'     => $nm_coa_credit_discount, //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $amount_discount , //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );				
            $dataInvoice[] = $_debit_discount;
            $dataInvoice[] = $_credit_discount;				
			
				
			}else{
				$param_ppn = 3;
			}
			if($tax != '0'){
				$ppn_value = ($tax/100)*($total_amount - $amount_discount);
				$amn_after_tax = $total_amount+$ppn_value ;

            $_credit_ppn = array(
				'row_id'     => $param_ppn, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '25301', //$coa_credit['id'],
				'nm_coa'     => 'PPN KELUARAN', //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $ppn_value, //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
			//$dataInvoice[1]['credit'] = $amn_after_tax;
			 $dataInvoice[]= $_credit_ppn;
			 $dataInvoice[0]['debit'] = $amn_after_tax ;

			}

			

			/*
			if($fg_discount_nya == '1'){
				if($discount_nya > 0 ){
					$discount = ($discount_nya/100)*$total_amount;
				}else{
					$discount = 0;
				}
				$discount_value = $total_amount - $discount;
			//PPH
            $_debit = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '15204', //$coa_debit['id'],
				'nm_coa'     => 'PAJAK DIBAYAR DIMUKA PPN MASUKAN', //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $discount, //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );			
				$dataInvoicedataInvoicedataInvoice[] = $_debit;
				$dataInvoice[0]['debit'] = $discount_value;
			}
			
*/		

		//$dataInvoice;
		//die();
		//echo "<pre>";
		//print_r($dataInvoice);
		//echo "</pre>";
		//die();
		delete_journal_d($id_journal);
        foreach($dataInvoice as $_inv){
/* 			if($_inv['debit'] > 0){
				$n_amt = $n_amt  + $_inv['debit'];
			} */
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


function insert_inv_sales($invno)
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
		$_var = 0;
		$tax = $invoice_request['data'][0]->tax;
		$status_order =$invoice_request['data'][0]->status_order;

		$total_amount = 0;
		$discount = 0;
		$discount_value = 0;
		//print_r($invoices);
        foreach($invoices as $_inv){
            $_inv = (array) $_inv;
			$bpb = $_inv['bppbno'];
			$po = $_inv['pono'];			
           //$amount = $_inv['invoice_amount'];	
			$total_amount = $total_amount + $_inv['invoice_amount'];
            list($coa_debit_fob, $coa_credit_fob,$coa_debit_cmt, $coa_credit_cmt,$coa_debit_discount, $coa_credit_discount) = get_id_coa_sales_from_inv($_inv);
			

			if($status_order == 'FOB'){
			if($_var == "0"){
				$id_coa_debit = $coa_debit_fob['id'];
				$nm_coa_debit = $coa_debit_fob['nm'];
				$id_coa_credit = $coa_credit_fob['id'];
				$nm_coa_credit = $coa_credit_fob['nm'];				
				$curr = $_inv['curr'];
			}				
				
			}else {
				if($_var == "0"){
				$id_coa_debit = $coa_debit_cmt['id'];
				$nm_coa_debit = $coa_debit_cmt['nm'];
				$id_coa_credit = $coa_credit_cmt['id'];
				$nm_coa_credit = $coa_credit_cmt['nm'];				
				$curr = $_inv['curr'];		
				}				
			}
			//potongan kepala...
				$id_coa_debit_discount = $coa_debit_discount['id'];
				$nm_coa_debit_discount = $coa_debit_discount['nm'];
				$id_coa_credit_discount = $coa_credit_discount['id'];
				$nm_coa_credit_discount = $coa_credit_discount['nm'];			

			
			
			//potongan 

        }

            // Piutang
            $_debit = array(
				'row_id'     => 1, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_debit, //$coa_debit['id'],
				'nm_coa'     => $nm_coa_debit, //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $total_amount, //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            // Sales
            $_credit = array(
				'row_id'     => 2, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_credit, //$coa_credit['id'],
				'nm_coa'     => $nm_coa_credit, //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $total_amount, //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            $dataInvoice[] = $_debit;
            $dataInvoice[] = $_credit;			
			//potongan
			if($discount_nya != '0' || $discount_nya != '0'  ){
				$param_ppn = 5;
				$amount_discount = ($tax/100)*$total_amount;
            $_debit_discount = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_debit_discount, //$coa_debit['id'],
				'nm_coa'     => $nm_coa_debit_discount, //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $amount_discount , //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
            // Sales
            $_credit_discount = array(
				'row_id'     => 4, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => $id_coa_credit_discount, //$coa_credit['id'],
				'nm_coa'     => $nm_coa_credit_discount, //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $amount_discount , //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );				
            $dataInvoice[] = $_debit_discount;
            $dataInvoice[] = $_credit_discount;				
			
				
			}else{
				$param_ppn = 3;
			}
			if($tax != '0'){
				$ppn_value = ($tax/100)*$total_amount;
				$amn_after_tax = $total_amount+$ppn_value ;

            $_credit_ppn = array(
				'row_id'     => $param_ppn, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '25301', //$coa_credit['id'],
				'nm_coa'     => 'PPN KELUARAN', //$coa_credit['nm'],
				'curr'       => $curr,//$_inv['curr'],
				'debit'      => 0, 
				'credit'     => $ppn_value, //$amount,
				'description'=> $invno,// $_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );
			//$dataInvoice[1]['credit'] = $amn_after_tax;
			 $dataInvoice[]= $_credit_ppn;
			 $dataInvoice[0]['debit'] = $amn_after_tax ;

			}

			
			//print_r($dataInvoice);
			//die();
			
			
			/*
			if($fg_discount_nya == '1'){
				if($discount_nya > 0 ){
					$discount = ($discount_nya/100)*$total_amount;
				}else{
					$discount = 0;
				}
				$discount_value = $total_amount - $discount;
			//PPH
            $_debit = array(
				'row_id'     => 3, //$row_id++,
				'id_journal' => $id_journal,
				'id_coa'     => '15204', //$coa_debit['id'],
				'nm_coa'     => 'PAJAK DIBAYAR DIMUKA PPN MASUKAN', //$coa_debit['nm'],
				'curr'       => $curr, //$_inv['curr'],
				'debit'      => $discount, //$amount,
				'credit'     => 0,
				'description'=> $invno, //$_inv['description'],
				'dateadd'    => $dateadd,
				'useradd'    => $user,
            );			
				$dataInvoice[] = $_debit;
				$dataInvoice[0]['debit'] = $discount_value;
			}
			
*/

        foreach($dataInvoice as $_inv){
/* 			if($_inv['debit'] > 0){
				$n_amt = $n_amt  + $_inv['debit'];
			} */
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
	$WHERE = "AND FH.reff_doc IS NOT NULL AND  AR.id_rekap NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE type_journal IN ('4','13'))";		
	}
	/*$query = "SELECT AR.id_rekap
				,AR.id_journal
				,JH.reff_doc
				,JH.reff_doc2
				,IC.v_noinvoicecommercial no_invoice
				,if(IH.fg_discount = '0',SUM(ifnull(IC.n_amount,0)),
					if(IH.n_discount = '0',SUM(ifnull(IC.n_amount,0)),(SUM(ifnull(IC.n_amount,0)))-(SUM(ifnull(IC.n_amount,0))*(IH.n_discount/100)) )
				)j_price
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
					SELECT id,id_buyer,fg_discount,n_discount FROM invoice_header 
				)IH ON IH.id = IC.n_idinvoiceheader
				LEFT JOIN(
					SELECT Supplier,Id_Supplier,area,vendor_cat,supplier_code FROM mastersupplier
				)MS ON MS.Id_Supplier = IH.id_buyer

				WHERE 1 AND JH.reff_doc IS NOT NULL AND  AR.id_rekap NOT IN(SELECT reff_doc2 FROM fin_journal_h WHERE type_journal IN ('4','13'))
				GROUP BY AR.id_journal";
				*/
		$query ="SELECT
		AR.id_rekap
		,FH.id_journal
		,SUM(ifnull(FD_PPN.credit,0)) ppn
		,SUM(ifnull(FD_PPH.debit,0)) pph
		,GROUP_CONCAT(FH.reff_doc SEPARATOR '<br/>-')reff_doc
		,IH.id_buyer
		,GROUP_CONCAT(IH.invno SEPARATOR '<br/>')no_invoice
		,SUM(ID.amount) amount_before_ppn_pph
		,SUM(FD.nilai_journal) j_price__
		,(SUM(FD.nilai_journal) - SUM(ifnull(FD_PPH.debit,0)))j_price
		,SO.curr
		,ID.id_so_det 
		,ID.jqty
		,MS.Supplier
		,MS.supplier_code
		,MS.vendor_cat
		,DATE_ADD(FH.date_journal, INTERVAL MS.terms_of_pay DAY) as  jatuh_tempo
		FROM fin_journal_h FH
		LEFT JOIN invoice_header IH ON FH.reff_doc = IH.invno
		LEFT JOIN(
			SELECT id_inv,SUM(ifnull(qty,0)*ifnull(price,0))amount,SUM(qty)jqty,MAX(id_so_det)id_so_det
			FROM invoice_detail GROUP BY id_inv
		)ID ON IH.id = ID.id_inv
		LEFT JOIN(
			SELECT SUM(ifnull(debit,0))nilai_journal,id_journal FROM fin_journal_d WHERE  
			id_coa NOT IN ('47401','47501','48901','49001')  GROUP BY id_journal
		)FD On FD.id_journal = FH.id_journal
		LEFT JOIN(
			SELECT credit,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%IJ%' AND 
			id_coa = '25301'
		)FD_PPN On FD_PPN.id_journal = FD.id_journal		
		LEFT JOIN(
			SELECT ifnull(debit,0)debit,id_coa,nm_coa,id_journal  FROM fin_journal_d WHERE id_journal LIKE '%IJ%' AND 
			id_coa IN ('47401','47501','48901','49001') 
		)FD_PPH On FD_PPH.id_journal = FD.id_journal			
		LEFT JOIN so_det SOD ON ID.id_so_det = SOD.id
		LEFT JOIN so SO ON SO.id = SOD.id_so
		LEFT JOIN mastersupplier MS ON MS.Id_Supplier = IH.id_buyer 
		INNER JOIN fin_status_journal_ar AR ON AR.id_journal = FH.id_journal AND AR.no_invoice = IH.invno
WHERE FH.type_journal ='1' AND fg_post = '2' $WHERE  GROUP BY AR.id_rekap";
		//echo $query;		
	return $query;
}

function GetQuery_Detail_Payment($source,$param){
	$j_detail =journal_detail();
	$query = "SELECT  A.v_nojournal
			,A.v_listcode no_payment
			,A.v_source
			,A.v_isapproval
			,JD.bpb_ref reff_doc
			,JD.journal_ref reff_doc2
			,JD.curr
			,round(JD.nilai,2) bpb_amount
			,MS.supplier nm_supplier			
				FROM fin_status_journal_ap A LEFT JOIN
					(
					$j_detail
					)JD ON A.v_nojournal = JD.id_journal 
				LEFT JOIN bpb BPB ON BPB.bpbno_int = JD.bpb_ref
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
					,A.curr
					,sum(ifnull(A.amount,0))bpb_amount
					,A.Supplier nm_supplier FROM(
			SELECT AP.v_nojournal
					,AP.v_listcode
					,AP.v_source
					,AP.v_isapproval
					,'' reff_doc
					,'' reff_doc2
					,POI.curr
					,(BPB.qty * BPB.price)amount
					,POI.id
					,MS.Supplier
					FROM fin_status_journal_ap AP
					LEFT JOIN(
						SELECT id,pono FROM po_header
					)POH ON AP.v_nojournal = POH.pono
					LEFT JOIN(
						SELECT id_po,(ifnull(qty,0) * ifnull(price,0) )amount_po,id,curr FROM po_item 
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
	$journal_detail = journal_detail();
	$query = "
SELECT  AA.v_nojournal
       ,AA.no_payment
       ,AA.v_source
       ,AA.v_isapproval
	   ,AA.v_status
       ,AA.bpb_ref reff_doc
       ,AA.journal_ref reff_doc2
       ,round(SUM(AA.bpb_amount),2) bpb_amount
	   ,AA.curr
       ,AA.nm_supplier nm_supplier	
	   ,AA.terms_of_pay
	   FROM(SELECT  A.v_nojournal
			,A.v_listcode no_payment
			,A.v_source
			,A.v_isapproval
			,A.v_status
			,JD.bpb_ref
			,JD.journal_ref
			,JD.is_utang
			,JD.nilai bpb_amount
			,BPB.curr
			,MS.supplier nm_supplier	
			,MS.terms_of_pay
				FROM fin_status_journal_ap A LEFT JOIN
					(
						/* Journal Detail */
						$journal_detail
						/* Journal Detail */
					)JD ON A.v_nojournal = JD.id_journal 
				LEFT JOIN bpb BPB ON BPB.bpbno_int = JD.bpb_ref
				LEFT JOIN mastersupplier MS ON MS.Id_Supplier = BPB.id_supplier 	

				WHERE 1 {WHERE} AND A.v_source = 'KB' 

				
				GROUP BY v_nojournal,A.v_listcode
				)AA  WHERE 1  GROUP BY AA.no_payment
			UNION ALL
			SELECT   A.v_nojournal
					,A.v_listcode no_payment
					,A.v_source
					,A.v_isapproval
					,A.v_status
					,A.reff_doc
					,A.reff_doc2
					,sum(ifnull(A.amount,0))bpb_amount
					,A.curr
					,A.Supplier nm_supplier
					,A.terms_of_pay
					FROM(
			SELECT AP.v_nojournal
					,AP.v_listcode
					,AP.v_source
					,AP.v_isapproval
					,AP.v_status
					,'' reff_doc
					,'' reff_doc2
					,(BPB.qty * BPB.price)amount
					,BPB.curr
					,POI.id
					,MS.Supplier
					,MS.terms_of_pay
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

function check_pajak($id_journal){
			global $con_new; 
			$Bm = new Basemodel($con_new);	
			$q="SELECT X.id_journal
		,X.row_id
		,X.pono
		,(if(SUBSTR(X.reff_doc,1,3) = 'WIP',X.ppn_wip,X.ppn_bh))ppn
		,if((if(SUBSTR(X.reff_doc,1,3) = 'WIP',X.ppn_wip,X.ppn_bh)) = '0' OR (if(SUBSTR(X.reff_doc,1,3) = 'WIP',X.ppn_wip,X.ppn_bh)) IS NULL,0,1  )key_nya
		FROM(
SELECT JD.id_journal
			 ,JD.reff_doc
			 ,JD.row_id
			 ,BPB.bpbno_int bpb
			 ,MAX(POH_WIP.ppn_wip)ppn_wip
			 ,MAX(POH_WIP.pono)pono
			 ,POH_BH.ppn_bh
			 FROM fin_journal_d JD
LEFT JOIN(SELECT id_supplier,bpbno_int, pono,id_jo,id_item FROM bpb)BPB ON JD.reff_doc = BPB.bpbno_int
LEFT JOIN (select id_jo,id_po,id_gen,cancel FROM po_item WHERE cancel !='Y' )POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
LEFT JOIN (SELECT pono,id,ppn ppn_wip,id_supplier FROM po_header)POH_WIP 
ON POH_WIP.id = POI_WIP.id_po 
	AND 
	BPB.id_supplier = POH_WIP.id_supplier
LEFT JOIN(SELECT pono,id,ppn ppn_bh FROM po_header)POH_BH ON BPB.pono = POH_BH.pono
WHERE JD.id_journal = '$id_journal' GROUP BY JD.reff_doc)X GROUP BY X.id_journal";
    $pajak_detail = $Bm->query($q)->result();	
	 $pajaknya = array(
        'keys' => $pajak_detail[0]->key_nya,
        'ppn' => $pajak_detail[0]->ppn
    );
	
	return  $pajaknya;
}


function check_nilai($id_journal){
			global $con_new; 
			$Bm = new Basemodel($con_new);	
	$q = "SELECT X.id_journal
		,X.row_id
		,sum(X.credit)utang_before_pajak
		,(X.ppn_wip/100)*sum(X.credit) nilai_ppn_wip
		,(X.ppn_bh/100)*sum(X.credit) nilai_ppn_bh
		,X.ppn_wip
		,X.ppn_bh
		,X.po_wip
		,X.po_bh
		,X.bpb
		FROM(
SELECT JD.id_journal
			 ,JD.reff_doc
			 ,JD.row_id
			 ,JD.credit
			 ,BPB.bpbno_int bpb
			 ,MAX(POH_WIP.ppn_wip)ppn_wip
			 ,MAX(POH_WIP.pono)po_wip
			 ,POH_BH.pono po_bh
			 ,MAX(POH_BH.ppn_bh)ppn_bh
			 FROM fin_journal_d JD
				LEFT JOIN(SELECT bpbno_int, pono,id_jo,id_item,id_supplier FROM bpb)BPB ON JD.reff_doc = BPB.bpbno_int
				LEFT JOIN (select id_jo,id_po,id_gen,cancel FROM po_item WHERE cancel !='Y')POI_WIP ON BPB.id_jo = POI_WIP.id_jo AND BPB.id_item = POI_WIP.id_gen
LEFT JOIN (SELECT pono,id,ppn ppn_wip,id_supplier FROM po_header)POH_WIP 
ON POH_WIP.id = POI_WIP.id_po 
	AND 
	BPB.id_supplier = POH_WIP.id_supplier
				LEFT JOIN(SELECT pono,id,ppn ppn_bh FROM po_header)POH_BH ON BPB.pono = POH_BH.pono
			WHERE JD.id_journal = '$id_journal' AND id_coa NOT IN('15204','15207') GROUP BY JD.row_id,JD.reff_doc)X GROUP BY X.id_journal,X.bpb";		
    $nilai_detail = $Bm->query($q)->result();	

/* 	die();
	 $nilainya = array(
        'nilai_bh' => $nilai_detail[0]->nilai_ppn_bh,
		'nilai_wip' => $nilai_detail[0]->nilai_wip,
		'ppn_bh' => $nilai_detail[0]->ppn_bh,
		'ppn_wip' => $nilai_detail[0]->ppn_wip,
    ); */
	return  $nilai_detail;
}

function j_detail($id_journal){
			global $con_new; 
			$Bm = new Basemodel($con_new);	
    $j_det = $Bm->query("SELECT * FROM fin_journal_d WHERE id_journal = '$id_journal' LIMIT 1")->result();	

	 $detail = array(
        'data' => $j_det[0]
    );
	return  $detail;
}


function get_next_item_id($id)
    {
			global $con_new; 
			$Bm = new Basemodel($con_new);		
        $row = $Bm->query("
            SELECT IFNULL(MAX(row_id),0)+1 next_id FROM
            fin_journal_d jd
            WHERE id_journal = '$id';
        ")->row();
        return $row->next_id;
    }


function update_bpb_ir($bpbno, $id_journal,$reff_doc2,$pph)
    {
			global $con_new; 
			$Bm = new Basemodel($con_new);
		$type_nya = substr($bpbno,0,3);	
        $bpb_request = check_bpb_update($bpbno,$type_nya,'14');
		$IsGeneral = IsGeneral($bpbno);

	if($type_nya == "WIP"){
		$IsGeneral = "2";
	}


            $dataBpb = array();
            $bpb = $bpb_request['data'];
		
            $row_id = get_next_item_id($id_journal);

            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
			$fg_pkp = "0";
			$ppn_percentage = "0";
			$amount = 0;
			$total_amount = 0;		
			$curr = '';
			$row_nya = 0;

            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;

             //   $amount = $_bpb['price'] * $_bpb['qty'];
				
			if($type_nya == "WIP"){
				$amount = ( $_bpb['price_po'] * $_bpb['qty']);	
				//$fg_pkp        = $_bpb['fg_pkp_wip'];
				//$ppn_percentage= $_bpb['ppn_wip'];						
				}else{
				$amount = ( $_bpb['price'] * $_bpb['qty']);
				//$fg_pkp        = $_bpb['fg_pkp_non_jasa'];
				//$ppn_percentage= $_bpb['ppn_non_jasa'];		
		
			}			
				$ppn_percentage = $_bpb['ppn'];

			$curr = $_bpb['curr'];

			$total_amount = $total_amount + $amount;
			
            //list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);
/* 			if($IsGeneral == '1'){
				//echo "123";
				list($coa_debit, $coa_credit) = $this->getIdCoaIrGeneral($_bpb);
			}else{
				list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);
			} */
			if($IsGeneral == '1'){
				list($coa_debit, $coa_credit) = get_id_coa_general_ir($_bpb);
			}else if($IsGeneral == '2'){
				list($coa_debit, $coa_credit) = get_id_coa_wip_ir($_bpb);
				//echo "123";
			}else{
				
				
				list($coa_debit, $coa_credit) = get_id_coa_ir_from_bpb($_bpb);
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
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],
					'pph' => '0',
					'ppn' => $_bpb['SAVE_ppn'],
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
                    'useradd' => $_SESSION['username'],
					'reff_doc' => $bpbno,
					'reff_doc2' => $reff_doc2,
					'id_bpb' => $_bpb['SAVE_id_bpb'],
					'id_po' => $_bpb['SAVE_id_po'],
					'id_po_det' => $_bpb['SAVE_id_po_det'],
					'id_item' => $_bpb['SAVE_id_item'],
					'qty' => $_bpb['SAVE_qty'],
					'price' => $_bpb['SAVE_price'],
					'id_supplier' => $_bpb['SAVE_id_supplier'],
					'type_bpb' => $_bpb['SAVE_type_bpb'],
					'amount_original' => $_bpb['SAVE_amount_ori'],	
					'pph' => $pph,		
					'ppn' => $_bpb['SAVE_ppn'],					
                );

                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;

            }
/* print_r($dataBpb);
die(); */
            foreach($dataBpb as $_bpb){
		
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd,reff_doc,reff_doc2,
					  id_bpb
					  ,id_po
					  ,id_po_det
					  ,id_item
					  ,qty
					  ,price
					  ,id_supplier
					  ,type_bpb
					  ,amount_original
					  ,n_pph
					  ,n_ppn
					  
					  )
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}','{$_bpb['reff_doc']}','{$_bpb['reff_doc2']}'
					  ,'{$_bpb['id_bpb']}'
					  ,'{$_bpb['id_po']}'
					  ,'{$_bpb['id_po_det']}'
					  ,'{$_bpb['id_item']}'
					  ,'{$_bpb['qty']}'
					  ,'{$_bpb['price']}'
					  ,'{$_bpb['id_supplier']}'
					  ,'{$_bpb['type_bpb']}'
					  ,'{$_bpb['amount_original']}'
					  ,'{$_bpb['pph']}'
					  ,'{$_bpb['ppn']}'
                      )
                    
                ";

                // Insert detail
                 $Bm->query($sql);
				//print_r($this->query($sql));
				//die();
            }
        

    }

function insert_bpb_ir_pajak($id_journal)
		{	
			global $con_new; 
			$Bm = new Basemodel($con_new);
			$check_pajak = check_pajak($id_journal);		
			if($check_pajak['keys'] == '1'){
				$total_nilai = check_nilai($id_journal);
				$bpb__ = array();
				$ppn__ = array();
				$utang_before_pajak = 0;
				$key_ppn = 0;
				$val_ppn = 0;
				foreach($total_nilai as $_total_nilai){

					$type_nya = substr($_total_nilai->bpb,0,3);	
					if($type_nya == 'WIP'){
						$amount = $_total_nilai->nilai_ppn_wip;
						$ppn = $_total_nilai->ppn_wip;
						array_push($bpb__,$_total_nilai->bpb);
						array_push($ppn__,$_total_nilai->ppn_wip);				
						if($_total_nilai->ppn_wip != '0'){
							$key_ppn  = 1;

						}
						if($val_ppn == '0'){
							$val_ppn = $_total_nilai->ppn_wip;
						}
					}else{
						$amount = $_total_nilai->nilai_ppn_bh;
						$ppn = $_total_nilai->ppn_bh;
						array_push($bpb__,$_total_nilai->bpb);
						array_push($ppn__,$_total_nilai->ppn_bh);
						if($_total_nilai->ppn_wip != '0'){
							$key_ppn  = 1;
						}
						if($val_ppn == '0'){
							$val_ppn = $_total_nilai->ppn_bh;
						}						
					}
					$utang_before_pajak = $utang_before_pajak + $_total_nilai->utang_before_pajak;
				}
				//echo $utang_before_pajak;
				//die();
				$amount_ppn = (($val_ppn/100)*$utang_before_pajak);
				$j_detail = j_detail($id_journal);
				//print_r($j_detail);
				$row_nya = get_next_item_id($id_journal);
/* 				print_r($row_nya);
				die(); */
			// GR
                $_debit = array(
                    'row_id' => $row_nya,
                    'id_journal' => $id_journal,
                    'id_coa' => '15204',
                    'nm_coa' => 'Pajak Dibayar Dimuka PPn Masukan',
                    'curr' => $j_detail['data']->curr,
                    'debit' => $amount_ppn,
                    'credit' => 0,
                    'description' => 'Pajak Dibayar Dimuka PPn Masukan',
                    'dateadd' => $j_detail['data']->dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => '',
					'reff_doc2' => '',
                );
                // Piutang
                $_credit = array(
                    'row_id' => $row_nya+1,
                    'id_journal' => $id_journal,
                    'id_coa' => '15207',
                    'nm_coa' => 'PAJAK DIBAYAR DIMUKA PPN MASUKAN (UNBILLED)',
                    'curr' => $j_detail['data']->curr,
                    'debit' => 0,
                    'credit' => $amount_ppn,
                    'description' => 'Pajak Dibayar Dimuka PPn Masukan',
                    'dateadd' => $j_detail['data']->dateadd,
                    'useradd' => $_SESSION['username'],
					'reff_doc' => '',
					'reff_doc2' => '',
                );

                $dataBpb[] = $_debit;
                $dataBpb[] = $_credit;
				//print_r($dataBpb);
				//die();
				for($q=0;$q<count($bpb__);$q++){
					$sql = "UPDATE fin_journal_d SET debit =(debit + ((".$ppn__[$q]."/100)*debit)) WHERE id_journal = '".$id_journal."' AND debit > 0 AND trim(reff_doc) ='".trim($bpb__[$q])."'  AND id_coa NOT IN('15204','15207')";
					$Bm->query($sql);
					$sql = "UPDATE fin_journal_d SET credit =(credit + ((".$ppn__[$q]."/100)*credit)) WHERE id_journal = '".$id_journal."' AND credit > 0 AND trim(reff_doc) ='".trim($bpb__[$q])."'  AND id_coa NOT IN('15204','15207')";
					$Bm->query($sql);							
					
				}		
            foreach($dataBpb as $_bpb){
		
                $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd,reff_doc,reff_doc2)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['debit']}', '{$_bpb['credit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}','{$_bpb['reff_doc']}','{$_bpb['reff_doc2']}'
                      )
                ";
//echo $sql;
//die();
                // Insert detail
                $Bm->query($sql);
				//print_r($this->query($sql));
				//die();
            }
			}else{
				//echo "No PPN";
				return "pajak tidak ada!";
			}
		
		}
		
		
function po_request($pono){

	global $con_new; 
	$Bm = new Basemodel($con_new);	
	$sql = "SELECT  (MASTER.price * MASTER.qty)amount
				   ,MAX(MASTER.id_gen)id_gen
				   ,MAX(MASTER.id_po)id_po
				   ,MASTER.pono
				   ,MAX(MASTER.fg_installment)fg_installment
				   ,MAX(MASTER.n_installment)n_installment
				   ,MAX(MASTER.d_installment)d_installment
				   ,SUBSTR(MASTER.goods_code,1,3)code_item
				   FROM (
				SELECT   
						POI.price
						,POI.qty
						,POI.id_gen
						,POI.id_po
						,POH.pono
						,POH.fg_installment
						,POH.n_installment
						,POH.d_installment
						,MI.goods_code
				FROM po_item POI LEFT JOIN
					(SELECT id
							,pono
							,fg_installment
							,n_installment
							,d_installment
						FROM po_header
					)POH ON POI.id_po = POH.id
				
				LEFT JOIN masteritem MI ON MI.id_item = POI.id_gen
				WHERE POH.pono = '$pono'
				)MASTER GROUP BY MASTER.pono
					";
$detail = $Bm->query($sql)->result();		
	if($detail[0]->fg_installment == 'N'){
                $sql = "DELETE FROM fin_installment WHERE pono = '{$pono}'
                ";		
				$Bm->query($sql);
				return "0";
	}else{
		$jumlah_installment = $detail[0]->n_installment;
		$month_start = explode('-',$detail[0]->d_installment);
		$month_start = intval($month_start[1]);
		
		$date_start = explode('-',$detail[0]->d_installment);
		$date_start = intval($date_start[2]);
	
		$year_start = explode('-',$detail[0]->d_installment);
		$year_start = intval($year_start[0]);
	
	$arr_cicilan = [];
	$param_array  = 0;
		for($i=1;$i <= intval($jumlah_installment);$i++){
			if($month_start == 13){
				$month_start = 1;
				$year_start = $year_start + 1;
			}
			$arr_cicilan[$param_array]['d_installment'] = $year_start."-".sprintf('%02d', $month_start)."-".sprintf('%02d', $date_start);
			$arr_cicilan[$param_array]['pono'] = $detail[0]->pono;
			$arr_cicilan[$param_array]['code_item'] = $detail[0]->code_item;
			$arr_cicilan[$param_array]['id_gen'] = $detail[0]->id_gen;
			$param_array++;
			$month_start++;
		}
		
	}

	return $arr_cicilan;
}		
function installment_insert($pono){
	global $con_new; 
	$Bm = new Basemodel($con_new);
	$po_detail = po_request($pono);
	if($po_detail == '0'){
		echo "Invalid!";
		return "Invalid";
	}else{
		$sql = "DELETE FROM fin_installment WHERE pono = '$pono'";
		 $Bm->query($sql);
        foreach($po_detail as $_po){

            $sql = "
                    INSERT INTO fin_installment
                      (pono, id_gen, v_code_item, d_payment)
                    VALUES 
                      ('{$_po['pono']}', '{$_po['id_gen']}', '{$_po['code_item']}', '{$_po['d_installment']}'
                      )
                    ;
                ";
            // Insert detail
            $Bm->query($sql);
        }
	}
			
	
	
}		


function get_pph_kontra_bon($id,$step){
	global $con_new; 
	$Bm = new Basemodel($con_new);
	if($step == "KB"){
$sql = "SELECT MASTER.id_journal
		,IF(MASTER.pph_h IS NOT NULL OR MASTER.pph_h > 0 OR MASTER.pph_h !='',( (IFNULL(MASTER.pph_h,0)/100) * SUM(IFNULL(nilai_ori,0)) ),SUM(MASTER.value_pph))value_pph
		FROM(	
	SELECT 
				((IFNULL(MT.percentage,0)/100)*bpb.qty*poi.price)value_pph
                 ,bpb.bpbno
				,FD.id_journal
				,FD.pph_h
				,FD.row_id
				,FD.credit
				,bpb.bpbno_int
				,bpb.id id_bpb
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,FD.curr
                ,if(SUBSTR(bpb.bpbno_int,1,3)='WIP',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,(bpb.qty*poi.price )nilai_ori
				,'Y' is_jasa
				,'N' is_pajak
				,'N' is_utang
				,'N' is_other
				,ACT.kpno
				,MT.percentage
            FROM 
                bpb 
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_item poi ON poi.id_gen = bpb.id_item AND bpb.id_jo = poi.id_jo AND poi.cancel = 'N'
				LEFT JOIN po_header poh ON poh.id = poi.id_po AND poh.id_supplier = bpb.id_supplier AND poh.app = 'A'
				LEFT JOIN jo JO ON JO.id = bpb.id_jo
				LEFT JOIN jo_det JOD ON bpb.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		LEFT JOIN (SELECT MT.percentage pph_h,A.n_pph pph_hd,B.description,B.credit,B.curr,A.id_journal journal_h,A.fg_tax,B.row_id,B.id_journal,B.reff_doc,B.n_pph FROM fin_journal_h A
LEFT JOIN(
SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND credit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL GROUP BY id_journal,row_id

)B ON A.id_journal = B.id_journal
LEFT JOIN mtax MT ON MT.idtax = A.n_pph
WHERE A.fg_tax = '1') FD ON FD.reff_doc = bpb.bpbno_int AND TRIM(mi.itemdesc) = TRIM(FD.description) AND mi.id_item = bpb.id_item
		LEFT JOIN mtax MT ON FD.n_pph = MT.idtax
			WHERE 1=1 AND FD.id_journal IS NOT NULL 
                 AND poi.cancel = 'N' AND poh.id IS NOT NULL
		AND (SUBSTR(bpb.bpbno_int,1,3)='WIP')
		group by bpb.id
				UNION ALL
            SELECT 
				  ((ifnull(MT.percentage,0)/100)*bpb.qty*bpb.price)value_pph
                 ,bpb.bpbno
				 ,FD.id_journal
				 ,FD.pph_h
				 ,FD.row_id
				 ,FD.credit
				 ,bpb.bpbno_int
				 ,bpb.id id_bpb
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,FD.curr
                 ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,(if(SUBSTR(bpb.bpbno_int,1,3)='FG/',bpb.qty*poi.price,bpb.qty*bpb.price) )nilai_ori
				,'N' is_jasa
				,'N' is_pajak
				,'N' is_utang	
				,'N' is_other
				,ACT.kpno
				,MT.percentage
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
				LEFT JOIN jo JO ON JO.id = poi.id_jo
				LEFT JOIN jo_det JOD ON JOD.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		LEFT JOIN (SELECT MT.percentage pph_h, A.n_pph pph_hd,B.description,B.credit,B.curr,A.id_journal journal_h,A.fg_tax,B.row_id,B.id_journal,B.reff_doc,B.n_pph FROM fin_journal_h A
LEFT JOIN(
SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND credit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL GROUP BY id_journal,row_id

)B ON A.id_journal = B.id_journal
LEFT JOIN mtax MT ON MT.idtax = A.n_pph
WHERE A.fg_tax = '1') FD ON FD.reff_doc = bpb.bpbno_int AND TRIM(mi.itemdesc) = TRIM(FD.description) AND mi.id_item = bpb.id_item
		LEFT JOIN mtax MT ON FD.n_pph = MT.idtax
			WHERE 1=1 AND FD.id_journal IS NOT NULL 
                 AND poi.cancel = 'N' AND (SUBSTR(bpb.bpbno_int,1,3)!='WIP') AND poh.id IS NOT NULL AND bpb.bpbno_int !='FG/'
		group by bpb.id
		
		
UNION ALL
            SELECT 
				  ((ifnull(MT.percentage,0)/100)*bpb.qty*poi.price)value_pph
                  ,bpb.bpbno
				  ,FD.id_journal
				  ,FD.pph_h
				 ,FD.row_id
				 ,FD.credit
				 ,bpb.bpbno_int
				 ,bpb.id id_bpb
				,poh.pono
				,poh.podate
				,poh.id_supplier supplier_po
				,poi.price price_po
				,poh.ppn
                ,bpb.bpbdate
                ,bpb.id_supplier
                ,bpb.id_item
                ,ifnull(bpb.qty,0) - ifnull(bpb.qty_reject,0)qty
                ,bpb.unit
                ,FD.curr
                 ,if(SUBSTR(bpb.bpbno_int,1,3)='FG/',poi.price,bpb.price )price
                ,ms.Supplier
                ,ms.supplier_code
                ,ms.vendor_cat
                ,mi.itemdesc
                ,mi.mattype
                ,mi.matclass
				,mi.n_code_category
                ,mg.id id_group
                ,poi.qty qty_po
                ,poi.id id_po_det
				,poi.id_gen
				,(if(SUBSTR(bpb.bpbno_int,1,3)='FG/',bpb.qty*poi.price,bpb.qty*bpb.price) )nilai_ori
				,'N' is_jasa
				,'N' is_pajak
				,'N' is_utang	
				,'N' is_other
				,ACT.kpno
				,MT.percentage
            FROM 
                bpb
                LEFT JOIN mastersupplier ms ON bpb.id_supplier = ms.Id_Supplier
                LEFT JOIN masteritem mi ON bpb.id_item = mi.id_item
                LEFT JOIN mastergroup mg ON mi.matclass = mg.nama_group
				LEFT JOIN po_header poh ON poh.pono = bpb.pono
				LEFT JOIN po_item poi ON poi.id_po = poh.id
				LEFT JOIN jo JO ON JO.id = poi.id_jo
				LEFT JOIN jo_det JOD ON JOD.id_jo = JOD.id
				LEFT JOIN so SO ON SO.id = JOD.id_so
				LEFT JOIN act_costing ACT ON ACT.id = SO.id_cost
		LEFT JOIN (SELECT MT.percentage pph_h,A.n_pph pph_hd,B.description,B.credit,B.curr,A.id_journal journal_h,A.fg_tax,B.row_id,B.id_journal,B.reff_doc,B.n_pph FROM fin_journal_h A
LEFT JOIN(
SELECT * FROM fin_journal_d WHERE id_journal LIKE '%-PK-%' AND credit > 0 AND id_coa NOT IN('15204','15207') AND reff_doc IS NOT NULL GROUP BY id_journal,row_id

)B ON A.id_journal = B.id_journal
LEFT JOIN mtax MT ON MT.idtax = A.n_pph
WHERE A.fg_tax = '1') FD ON FD.reff_doc = bpb.bpbno_int AND TRIM(mi.itemdesc) = TRIM(FD.description) AND mi.id_item = bpb.id_item
		LEFT JOIN mtax MT ON FD.n_pph = MT.idtax
			WHERE 1=1 AND FD.id_journal IS NOT NULL 
                 AND poi.cancel = 'N' AND poh.id IS NOT NULL AND bpb.bpbno_int ='FG/'
		group by bpb.id
		)MASTER WHERE MASTER.id_journal='$id' GROUP BY MASTER.id_journal";		

$run = $Bm->query($sql)->result(); 
$pph = $run[0]->value_pph;

	}
else{
$pph = "TIDAK ADA PPH";
}


return $pph;

}
function journal_detail(){
	$q= "
	SELECT D.is_normal
       ,D.is_utang		
       ,D.is_pajak	
       ,D.pajak
       ,D.debit
       ,D.utang	
       ,pph.nilai
	   ,(pph.value_pph ) value_pph
	
	   ,pph.amount_original
	   ,pph.value_ppn
       ,D.bpb_ref		
	   ,D.coa_utang
	   ,D.nm_utang
       ,D.journal_ref		
       ,D.fg_tax		
       ,D.n_pph		
       ,D.percentage		
       ,D.id_journal		
       ,D.id_coa		
       ,D.nm_coa
	   ,D.curr
	   ,D.id_supplier
	   				,D.id_po
					,D.id_bpb
					,D.id_item
	    FROM(
		
SELECT 	
			 C.is_normal
			,C.is_utang		
			,C.is_pajak	
			,SUM(C.pajak)pajak
			,SUM(C.debit)debit
			,SUM(C.utang)utang	
			,(SUM(C.debit) - SUM(C.utang))nilai
			,C.bpb_ref		
			,C.journal_ref		
			,C.fg_tax		
			,MAX(C.coa_utang)coa_utang
			,MAX(C.nm_utang)nm_utang
			,C.n_pph		
			,C.percentage		
			,C.id_journal		
			,C.id_coa		
			,C.nm_coa
			,C.curr
			,C.id_supplier
					,C.id_po
					,C.id_bpb
					,C.id_item
FROM (			
	SELECT 			
			 B.is_normal
			,B.is_utang		
			,B.is_pajak	
			,B.pajak
			,if(B.is_normal = '1',B.debit,0)debit 	
			,B.utang			
			,B.bpb_ref		
			,B.coa_utang
			,B.nm_utang
			,B.journal_ref		
			,B.fg_tax		
			,B.n_pph		
			,B.percentage		
			,B.id_journal		
			,B.id_coa		
			,B.nm_coa	
			,B.curr
			,B.id_supplier
					,B.id_po
					,B.id_bpb
					,B.id_item
  FROM (
  SELECT 	
			 A.pajak		
			,if(A.is_pajak = 'N' AND A.is_utang = 'N','1',0)is_normal
			,A.is_utang		
			,A.is_pajak	
			,A.utang	
			,A.coa_utang
			,A.nm_utang			
			,A.bpb_ref		
			,A.journal_ref		
			,A.fg_tax		
			,A.n_pph		
			,A.percentage		
			,A.id_journal		
			,A.id_coa		
			,A.nm_coa	
			,A.curr			
			,A.debit 	
			,A.id_supplier
					,A.id_po
					,A.id_bpb
					,A.id_item			
		FROM(
			SELECT  a.reff_doc bpb_ref
					,a.reff_doc2 journal_ref
					,a.row_id
					,b.fg_tax
					,b.n_pph
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit 
					,a.credit
					,a.id_bppb
					,a.id_supplier
					,a.id_po
					,a.id_bpb
					,a.id_item
					,a.is_retur_bh
					,if(a.id_coa LIKE '%15204%' OR a.id_coa LIKE '%15207%',
						if(a.id_coa = '15204',a.debit,a.credit)
					
					 ,0 )pajak
					,if(a.id_coa LIKE '%15204%' OR a.id_coa LIKE '%15207%',
						if(a.id_coa = '15204','P','N')
					
					 ,'N')is_pajak					 
					 ,if(a.debit !='0',if(c.id_coa IS NOT NULL AND a.is_retur_bh = 'N','U','N'),'N')is_utang
					,if(a.debit !='0',if(c.id_coa IS NOT NULL AND a.is_retur_bh = 'N',a.debit,0),0)utang
					,if(a.debit !='0',if(c.id_coa IS NOT NULL AND a.is_retur_bh = 'N',c.id_coa,0),0)coa_utang
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = b.n_pph WHERE
					a.debit > 0 GROUP BY a.id_journal,a.row_id)A
					)B
				)C	GROUP BY C.id_journal)D LEFT JOIN(
				
SELECT 	 
		 MASTER.id_journal
		,SUM(ifnull(MASTER.value_pph,0))value_pph_d
		,SUM(ifnull(MASTER.value_ppn,0))value_ppn
		,SUM(ifnull(MASTER.amount_original,0))amount_original
		,( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0)))value_pph_header
		,( ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) 
		
			+  SUM(ifnull(MASTER.value_pph,0))) value_pph
		,(SUM(ifnull(MASTER.nilai,0)  ) - ( (ifnull(MAX(MASTER.percentage_h),0)/100) * SUM(ifnull(MASTER.amount_original,0))) ) nilai
FROM(

		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.amount_original
					,a.n_ppn
					, (ifnull(a.amount_original,0) - ( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) ) + ( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )  )nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1
					AND a.is_retur_bh = 'N' AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NOT NULL GROUP BY a.id_journal,a.row_id
UNION ALL


		SELECT       a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,MT_H.percentage percentage_h
					,( (ifnull(MT.percentage,0)/100) * ifnull(a.amount_original,0) )value_pph
					,( (ifnull(a.n_ppn,0)/100) * ifnull(a.amount_original,0) )value_ppn
					,a.credit amount_original
					,a.n_ppn
					,a.credit nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax,n_pph FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph 
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT_H ON MT_H.idtax = b.n_pph
					
					WHERE 1=1
					AND a.is_retur_bh = 'N' AND
					a.credit > 0 AND  a.id_coa NOT IN('15204','15207') AND a.reff_doc IS NULL GROUP BY a.id_journal,a.row_id
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.debit)) amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'N' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
					
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.debit)) amount_original
					,a.n_ppn
					, (-1*(a.debit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.debit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'Y' AND
					a.debit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NOT NULL GROUP BY a.id_journal,a.row_id
					
UNION ALL

			SELECT   a.reff_doc bpb_ref
					,a.id_bpb
					,a.n_pph
					,'0' value_pph
					,'0' value_ppn
					,'0' percentage_h
					,(-1*(a.credit)) amount_original
					,a.n_ppn
					, (-1*(a.credit))nilai
					,a.qty
					,a.price
					,a.reff_doc2 journal_ref
					,a.row_id
					,a.description
					,b.fg_tax
					,'0' percentage
					,a.id_journal
					,a.id_coa
					,c.id_coa coa_utang
					,c.nm_coa nm_utang
					,a.nm_coa
					,a.curr
					,a.credit
					,a.is_retur_bh
					FROM fin_journal_d a
					INNER JOIN (SELECT id_journal,type_journal,fg_tax FROM fin_journal_h
						WHERE type_journal = '14'
					)b ON a.id_journal = b.id_journal 
					LEFT JOIN mapping_utang c ON c.id_coa = a.id_coa
					LEFT JOIN (SELECT idtax,percentage FROM mtax)MT ON MT.idtax = a.n_pph WHERE 1 =1
					AND a.is_retur_bh = 'Y' AND
					a.credit > 0 AND
					a.id_coa NOT IN('15204','15207') AND c.id_coa IS NULL  AND a.reff_doc IS NULL GROUP BY a.id_journal,a.row_id
					
										
					
					)MASTER GROUP BY MASTER.id_journal 

					
					
					
					


)pph ON pph.id_journal = D.id_journal
GROUP BY D.id_journal";
	return $q;
	
}



function insert_bpb_reverse($bpbno)
{
    global $con_new;
    $Bm = new Basemodel($con_new);
		 $journal_code = Config::$journal_code['18'];
		 $usages ='REVERSE';
    $bpb_request = check_journal_reff($bpbno);
	$detail_request = check_journal_reff_detail($bpb_request['data'][0]->id_journal);

   if($bpb_request['status']){
		$date = date('Y-m-d');
		$id_journal = generate_coa_id("NAG", $journal_code, $date);
        $company = get_master_company();
		$id_journal_reff = $bpb_request['data'][0]->id_journal;
        $dateadd = date('Y-m-d H:i:s', time());
        @$user = $_SESSION['username'];
        $d = array(
            'company' => $company->company,
            'period' => date('m/Y', strtotime($date)),
            'num_journal' => '',//$_POST['num_journal'],
            'id_journal' => $id_journal, 
			'date_journal' => $date,
            'type_journal' =>  Config::$journal_usage[$usages],
            'reff_doc' => $bpbno,
			'reff_doc2' => $id_journal_reff,
            'src_reference' => $id_journal_reff,
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
              ,dateadd, useradd,reff_doc2)
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$d['date_journal']}'
              , '{$d['type_journal']}', '{$d['reff_doc']}', '{$d['src_reference']}'
              ,'0', ''
              , '{$d['fg_post']}', '{$d['date_post']}', '{$d['user_post']}'
              ,'{$d['dateadd']}', '{$d['useradd']}', '{$d['reff_doc2']}')
            ;
        ";
		
		$Bm->query($sql);
		foreach($detail_request['data'] as $_bpb){
			$_bpb	=(array)$_bpb;
			$_bpb['id_journal'] = $id_journal;
	            $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['credit']}', '{$_bpb['debit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}', '{$_bpb['useradd']}'
                      )
                    ;
                ";
            $Bm->query($sql);
	
		}
		
		//check journal on kontra bon 
	$key_kontra_bon = check_kontra_bon($bpbno);
	if($key_kontra_bon['data'][0]->jumlah > 0){
		
		$bpb_request_kb = check_journal_reff_kb($id_journal_reff);	

		$id_journal = generate_coa_id("NAG", $journal_code, $date);
		foreach($bpb_request_kb['data'] as $d){
			$d = (array)$d;
			
			$rev_journal =$d['id_journal'];

			$d['id_journal'] = $id_journal;
        $sql = "
            INSERT INTO fin_journal_h 
              (company, period, id_journal, num_journal, date_journal, type_journal, reff_doc, reff_doc2, src_reference, fg_intercompany, id_intercompany
              ,dateadd, useradd,fg_tax,n_ppn,n_pph,inv_supplier,d_invoice 
			  )
            VALUES 
              ('{$d['company']}', '{$d['period']}', '{$d['id_journal']}', '{$d['num_journal']}', '{$date}'
		, '18', '{$rev_journal}', 'REV_KB', '{$d['src_reference']}', '{$d['fg_intercompany']}', '{$d['id_intercompany']}'
             ,'{$d['dateadd']}', '{$d['useradd']}','{$d['fg_tax']}','{$d['ppn']}','{$d['pph']}','{$d['noinvoiceSupplier']}',
			 '{$d['tgl_invoice']}'
			 )
            ;
        ";		
		 $Bm->query($sql);

		}

	$detail_request = check_journal_reff_detail($rev_journal);
		foreach($detail_request['data'] as $_bpb){
			$_bpb	=(array)$_bpb;
			$_bpb['id_journal'] = $id_journal; 
	            $sql = "
                    INSERT INTO fin_journal_d
                      (id_journal, row_id, id_coa, nm_coa, curr, debit, credit
                      ,description, dateadd, useradd,reff_doc,reff_doc2,n_pph)
                    VALUES 
                      ('{$_bpb['id_journal']}', '{$_bpb['row_id']}', '{$_bpb['id_coa']}', '{$_bpb['nm_coa']}', '{$_bpb['curr']}', '{$_bpb['credit']}', '{$_bpb['debit']}'
                      ,'{$_bpb['description']}','{$_bpb['dateadd']}','{$_bpb['useradd']}', '{$_bpb['reff_doc']}', '{$_bpb['reff_doc2']}', '{$_bpb['n_pph']}'
                      )
                    ;
                ";
            $Bm->query($sql);

		}		
		
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

function check_journal_reff_kb($id_journal){
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $journal_header = $Bm->query(
		"SELECT * FROM fin_journal_d WHERE reff_doc2 ='$id_journal' LIMIT 1;
				"
		)->result();
	
	$reff_kb = $journal_header[0]->id_journal;
	//print_r($journal_header[0]);

	$det_header = $Bm->query(
		"SELECT * FROM fin_journal_h WHERE id_journal ='$reff_kb' LIMIT 1;
				"
		)->result();
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $det_header
    );
}

function check_journal_reff($bpbno){
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $journal_header = $Bm->query(
		"SELECT * FROM fin_journal_h WHERE type_journal IN('2','17') AND reff_doc ='$bpbno' LIMIT 1;
				"
		)->result();
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $journal_header
    );
}
function check_journal_reff_detail($id_journal){
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $journal_header = $Bm->query(
		"SELECT * FROM fin_journal_d WHERE id_journal = '$id_journal';
				"
		)->result();
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $journal_header
    );
}


function getDescription($bpbno,$id_item){
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $detail = $Bm->query(
		"			SELECT MY.itemname
			,CONCAT(MI.itemdesc,'---',MY.itemname,'-',MY.Color,'-',MY.size)descx
			,POI.id_gen,POH.pono,POH.app,POI.cancel,POI.price,BPB_NYA.* FROM(
			SELECT * FROM bpb WHERE bpbno_int = '{$bpbno}')BPB_NYA
			LEFT JOIN (
			
			SELECT * FROM masterstyle )MY ON BPB_NYA.id_so_det = MY.id_so_det
			LEFT JOIN(
			
			SELECT * FROM so_det)SOD ON SOD.id = MY.id_so_det
			LEFT JOIN(
			SELECT * FROM jo_det)JOD ON JOD.id_so = SOD.id_so
			LEFT JOIN(
			SELECT * FROM po_item)POI ON POI.id_jo = JOD.id_jo
			INNER JOIN(
			SELECT * FROM po_header)POH ON POH.id = POI.id_po AND BPB_NYA.pono = POH.pono
			INNER JOIN(
			 SELECT * FROM masteritem
			)MI ON MI.id_item = POI.id_gen
			WHERE BPB_NYA.id_item = '{$id_item}'
				"
		)->result();
    return $detail[0]->descx;
}
function check_kontra_bon($bpbno_int){
		 global $con_new;
	    $Bm = new Basemodel($con_new);	
    $check_ = $Bm->query(
		"SELECT count(*)jumlah FROM fin_journal_d WHERE reff_doc = '$bpbno_int';
				"
		)->result();
    return array(
        'status' => true,
        'errcode' => null,
        'message' => 'BPB number found',
        'data'  => $check_
    );
}