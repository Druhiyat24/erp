<?php
// Session Checking
error_reporting(0);
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$_SESSION['username']=$_SESSION['username'];
$sesi=$_SESSION['sesi'];



$akses = flookup("F_P_Bank","userpassword","username='$_SESSION[username]'");
if ($akses=="0")
{ 
echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1' </script>";; 
}

# CEK HAK AKSES KEMBALI

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


    public function GenerateNoVoucher($d)
    {	
		if($d['terimakeluar'] == 'P1' ){
			$stringawal = "BBM";
		}else if($d['terimakeluar'] == 'P2' ){
			$stringawal = "BBK";
			
		}
		//BBM/1910/1979/0001
		//Penjelasan : BBM Fix, 1910 = tahun bulan, 1979 = 4 digit terakhir dari no rekening bank, 0001 = auto numbering berulang setiap bulan.
		$hari_ini = date("Y-m-d");
		$from = date('Y-m-01');
		$to = date('Y-m-t', strtotime($hari_ini));
		$explodess = explode("/",$d["period"]);
		$tahun = substr($explodess[1],2,2);
		$bulan = $explodess[0];
		$thnbulan = $tahun.$bulan;	
		$year = date('Y');
		$getAkunBank = $this->query("
                        SELECT id_coa,nm_coa FROM mastercoa WHERE id_coa = '$d[idcoa]'  ;
        ")->row();
		$digitakhir =  substr($getAkunBank->nm_coa,-4);
		$segment = $stringawal."_".$digitakhir."_".$bulan;
		$new_segment = $stringawal."_".$digitakhir."_01";
		$pool = 
				$this->query("
  			  SELECT segment,year,sequence FROM fin_journal_pool WHERE segment = '$segment'")->row();	  
		if($pool->segment){
	
			if($pool->year != $year){
				$sequence = 1;
				$sql_nya = "
					UPDATE fin_journal_pool SET sequence = '$sequence',year = '$year',segment ='$new_segment'  WHERE segment = '$segment';
					";
					insert_log_nw($sql_nya,$_SESSION['username']);
				$this->query($sql_nya);				
				
			}else{
				$sequence = 1;
				if($pool->segment != $segment){
				$sql_nya = "
					UPDATE fin_journal_pool SET sequence = '$sequence',year = '$year',segment ='$segment'  WHERE segment = '$segment';
					";
					insert_log_nw($sql_nya,$_SESSION['username']);
					
				$this->query($sql_nya);									
				}else{

			$sequence =$pool->sequence+1;
					$sql_nya = "
						UPDATE fin_journal_pool SET sequence = '$sequence'  WHERE segment = '$segment';
						";
						
						//die();
						insert_log_nw($sql_nya,$_SESSION['username']);
					
					$this->query($sql_nya);						
					
				}				
			}			
		}else{
				$sequence = 1;
				$sql_nya = "
					INSERT INTO fin_journal_pool (segment,year,sequence) VALUES('$segment','$year','$sequence');
					";
				insert_log_nw($sql_nya,$_SESSION['username']);	
				$this->query($sql_nya);				
			
			
		}
		
		
		//check,ini bulannya sama ngga sama data terakhir database
		//$checkbulan = 
		//		$this->query("
       //       SELECT count(type_journal) jumlah FROM fin_journal_h WHERE type_journal = '$d[type_journal]' and dateadd //>='$from' AND dateadd <= '$to';
		//		")->row();
/* 				if($checkbulan->jumlah){
					$autonumber = sprintf('%04d', intval($checkbulan->jumlah) + 1);
				}else{
					$autonumber = sprintf('%04d',1);
				} */
		$autonumber = sprintf('%04d', intval($sequence));
		$no_voucher = $stringawal."/".$thnbulan."/".$digitakhir."/".$autonumber;
		//die();	
		//print_r($digitakhir);
		return $no_voucher;
    }


    public function get_journal($id)
    {
        return $this->query("
                        SELECT jh.*
            	,cashbank.d_create
                ,cashbank.n_checkno
                ,cashbank.v_idcoa
                ,cashbank.n_nilai 
				,cashbank.v_codeterimakeluar
				,cashbank.v_umurar
				,cashbank.d_tgltempoar
				,cashbank.v_idkonsumen
				,cashbank.v_namakonsumen
				,cashbank.v_rekap_ar
                ,coa.nm_coa
                ,HD.v_novoucher
                ,HD.v_fakturpajak
                FROM
                
            fin_journal_h jh
			LEFT JOIN (SELECT * FROM fin_prosescashbank) cashbank ON jh.id_journal = cashbank.v_idjournal
            LEFT JOIN (SELECT nm_coa,id_coa FROM mastercoa)coa ON cashbank.v_idcoa = coa.id_coa
			LEFT JOIN (SELECT v_idjournal,v_novoucher,v_fakturpajak FROM fin_journalheaderdetail)HD ON jh.id_journal = HD.v_idjournal
            WHERE jh.id_journal = '$id'
            ;
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

    public function getNamaAkun($idAkun){
        $q = $this->query("SELECT id_coa,nm_coa FROM mastercoa WHERE id_coa = '$idAkun'")->row();
		$Nama = $q->nm_coa;
		return $Nama;
    }
	
	public function typekarensinya($nama_coa){

		$explode = explode(" ",$nama_coa);
					$gabung = str_replace("-","-",$explode[1]);
				
	
		$q = $this->query("SELECT curr,no_rek FROM masterbank WHERE no_rek = '$gabung' LIMIT 1" )->row();
		$karensinya = $q->curr;
		//print_r($karensinya);
		//die();
		return $karensinya;
	}

    public function save($d)
    {
		//$no_voucher = $this->GenerateNoVoucher($d);
		$d['no_voucher'] = $this->GenerateNoVoucher($d);

		$namaakun = $this->getNamaAkun($d['idcoa']);
		$d['nilai'] = str_replace(",","",$d['nilai']);
		
		//$explodedate = explode("/",$d['d_create']);
		//$d['d_create'] = $explodedate[2].'-'.$explodedate[1].'-'.$explodedate[0];


		//$tgltempoar = date("Y-m-d", strtotime($d['tgltempoar']));
		$sql33 = "
			INSERT INTO fin_journalheaderdetail (v_idjournal,v_novoucher,v_fakturpajak)
			VALUES('".$d['id_journal']."','".$d['no_voucher']."','".$d['faktur_pajak']."')
        ";
		insert_log_nw($sql33,$_SESSION['username']);
		$this->query($sql33);			
		
		$sql2 = "
			INSERT INTO fin_prosescashbank (v_idjournal,n_checkno,v_idcoa,n_nilai,n_reffdoc,d_create,	v_codeterimakeluar,	v_journaltype
				,v_umurar
				
				,v_idkonsumen
				,v_namakonsumen			
				,v_rekap_ar
			)
			VALUES('".$d['id_journal']."','".$d['checkno']."','".$d['idcoa']."','".$d['nilai']."','".$d['reff_doc']."','".date('Y-m-d')."','".$d['terimakeluar']."','B'
			,'".$d['umurar']."'
			
			,'".$d['codekonsumen']."'
			,'".$d['namakonsumen']."'
			,'".$d['rekap_ar']."'
			)
        ";
		insert_log_nw($sql2,$_SESSION['username']);
		$this->query($sql2);

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
		insert_log_nw($sql,$_SESSION['username']);
        $this->query($sql);
//        return $id_journal; //$this->conn->insert_id;
//ADD TO JOURNAL D P1 = Penerimaan P2 = Pnegeluaran
if($d['terimakeluar'] == 'P1'){
	$debit = $d['nilai'];
	$credit = 0;
}
else if($d['terimakeluar'] == 'P2'){
	$debit = 0;
	$credit = $d['nilai'];	
}
/* insert to jurnal_fin_d */

		$karensi = $this->typekarensinya($namaakun);
		//echo $karensi;
		//die();
        $sql3 = "  
            INSERT INTO fin_journal_d
              (id_journal, row_id, id_coa, nm_coa, curr, debit,credit
              ,description, dateadd, useradd)
            VALUES 
              ('{$d['id_journal']}', '1', '{$d['idcoa']}', '{$namaakun}' ,'{$karensi}', '{$debit}','{$credit}'
              ,'{$d['reff_doc']}','{$d['dateadd']}', '{$_SESSION['username']}'
              )
            ;
        ";
			insert_log_nw($sql3,$_SESSION['username']);
		  $this->query($sql3);
//ADD TO JOURNAL D



    }
    public function update($id, $data)
    {
		
		//$tgltempoar = date("Y-m-d", strtotime($data['tgltempoar']));
			$namaakun = $this->getNamaAkun($data['idcoa']);
		
		
		$sql33 = "
			UPDATE fin_journalheaderdetail SET  
						v_novoucher   		= '".$data['no_voucher']."'
						,v_fakturpajak    	= '".$data['faktur_pajak']."'
			WHERE v_idjournal = '".$data['id_journal']."'
			";
			insert_log_nw($sql33,$_SESSION['username']);
		$this->query($sql33);				
					
		
		$data['nilai'] = str_replace(",","",$data['nilai']);
		$sql2 = "
			UPDATE fin_prosescashbank SET 
						n_checkno  = '".$data['checkno']."'
						,n_nilai    = '".$data['nilai']."'
						,n_reffdoc  = '".$data['reff_doc']."'  
						,v_umurar  = '".$data['umurar']."'
						,v_idcoa  = '".$data['idcoa']."'
						,v_idkonsumen  = '".$data['codekonsumen']."'						
			WHERE v_idjournal = '".$data['id_journal']."'
			";

		$this->query($sql2);
        $sql2__ = "
            UPDATE fin_journal_h SET 
              period = '{$data['period']}',
              num_journal = '{$data['num_journal']}',
              reff_doc = '{$data['reff_doc']}',
              fg_intercompany = '{$data['fg_intercompany']}',
              id_intercompany = '{$data['id_intercompany']}',
			  date_journal = '{$data['date_journal']}',
              dateedit = '{$data['dateedit']}',
              useredit = '{$data['useredit']}'
            WHERE id_journal = '$id';
        ";

insert_log_nw($sql2__,$_SESSION['username']);
     $this->query($sql2__);
		

	$debit = 1;
	$credit = 2;
if($data['terimakeluar'] == 'P1'){

	$debit = $data['nilai'];
	$credit = 0;
}
else if($data['terimakeluar'] == 'P2'){
	$debit = 0;
	$credit = $data['nilai'];	
		//print_r($data);
}
/* insert to jurnal_fin_d */
		$karensi = $this->typekarensinya($namaakun);
        $sql__ = "
            UPDATE fin_journal_d SET 
              debit = '{$debit}'
			  ,credit = '{$credit}'
			  ,id_coa = '{$data['idcoa']}'
			  ,nm_coa = '{$namaakun}'
			  ,curr = '{$karensi}'
            WHERE id_journal = '$id' AND row_id = '1';
        ";
		//echo $sql__;
		//print_r($debit);
		//print_r($credit);
		//die();
		insert_log_nw($sql__,$_SESSION['username']);
        return $this->query($sql__);
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
/*         $journal = (array) $this->get_journal($id);

        switch($journal['type_journal']){
            case Config::$journal_usage['purchase']:
                //Check whether this is gr journal or not by checking on reff to bpb. code 02 means not existent
                $bpb_request = $this->check_bpb($journal['reff_doc'], FALSE);

                if($bpb_request['errcode'] != '02'){
                    $this->insert_bpb_ir($journal['reff_doc'],$id); */
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
  //              }
  //              break;
  //          default:
   //     }

        $sql = "
            UPDATE fin_journal_h SET 
              fg_post = '2',
              date_post = '{$post['date_post']}',
              user_post = '{$post['user_post']}'
            WHERE id_journal = '$id';
        ";
		insert_log_nw($sql,$_SESSION['username']);
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

    public function get_id_coa_gr_from_bpb($bpb){
        $mapping_coa = $this->query("
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

    public function get_id_coa_ir_from_bpb($bpb){
        $mapping_coa = $this->query("
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

    public function insert_bpb_gr($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno);
        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = 1;
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
                $amount = $_bpb['price'] * $_bpb['qty'];
                list($coa_debit, $coa_credit) = $this->get_id_coa_gr_from_bpb($_bpb);

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
				insert_log_nw($sql,$_SESSION['username']);
                $this->query($sql);
            }
        }
    }

    public function insert_bpb_ir($bpbno, $id_journal)
    {
        $bpb_request = $this->check_bpb($bpbno, FALSE);

        if($bpb_request['status']){
            $dataBpb = array();
            $bpb = $bpb_request['data'];
            $row_id = $this->get_next_item_id($id_journal);
            $dateadd = date('Y-m-d H:i:s', time());
            $_SESSION['username'] = $_SESSION['username'];
            foreach($bpb as $_bpb){
                $_bpb = (array) $_bpb;
                $amount = $_bpb['price'] * $_bpb['qty'];
                list($coa_debit, $coa_credit) = $this->get_id_coa_ir_from_bpb($_bpb);

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
				insert_log_nw($sql,$_SESSION['username']);
                $this->query($sql);
            }
        }
    }



    public function save_penerimaan_detail($id_journal, $data)
    {
        $id_jurnal_bank_masuk = $data['reff_doc'];
        $sql = "
            SELECT 
                fjd.*
            FROM fin_journal_h fjh
                INNER JOIN fin_journal_d fjd ON fjh.id_journal = fjd.id_journal
            WHERE fjh.type_journal = '11'
              AND fjd.debit > 0
              AND fjh.id_journal = '$id_jurnal_bank_masuk'
        ";

        $bank_masuk = $this->query($sql)->result();


        $id_rekap = $data['reff_doc2'];

        $sql = "
            SELECT far.*, inv.j_price price, inv.jqty qty, inv.Supplier, inv.id_journal, inv.vendor_cat, inv.area, inv.product_group, inv.curr
            FROM 
                fin_status_journal_ar far 
                INNER JOIN (
                     SELECT 
                         IAC.v_noinvoicecommercial
                      ,IAC.n_id
                      ,IAC.n_amount
                      ,IAC.n_idinvoiceheader
                      ,IAC.v_from
                      ,IAC.v_to
                      ,IAC.d_insert
                      ,IAC.v_pono
                      ,IH.invno 
                      ,IH.shipped_by
                      ,IH.etd
                      ,IH.eta
                      ,IH.measurement
                      ,BPB.j_bersih
                      ,BPB.j_kotor
                      ,BPB.jqty
                      ,BPB.j_price
                      ,BPB.id_supplier
                      ,BPB.nomor_mobil
                      ,BPB.styleno
                      ,MSM.shipdesc
                      ,MSS.Supplier
                      ,MSS.terms_of_pay
                      ,MSS.alamat
                      ,MSST.itemname
                      ,MSST.color
                      ,MSST.goods_code
                      ,ID.j_carton
                      ,ji.id_journal
                      ,ji.reff_doc
                      ,mss.vendor_cat
                      ,mss.area
                      ,mp.product_group
                      ,bpb.curr
                  FROM invoice_commercial IAC
                      LEFT JOIN(
                          SELECT invno,id,shipped_by,measurement,etd,eta FROM invoice_header
                      )IH ON IAC.n_idinvoiceheader =  IH.id
                      LEFT JOIN(
                          SELECT (price) j_price,(qty) jqty,(berat_bersih) j_bersih,(berat_kotor) j_kotor,invno,(id_supplier) id_supplier,nomor_mobil,styleno,id_item, curr FROM bpb
                      )BPB ON IH.invno = BPB.invno
                      LEFT JOIN masterproduct mp ON BPB.id_item = mp.id
                      LEFT JOIN(
                          SELECT id,shipdesc FROM mastershipmode
                      ) MSM ON IH.shipped_by = MSM.id
                      LEFT JOIN(
                          SELECT Supplier,Id_supplier,alamat,terms_of_pay,vendor_cat,area FROM mastersupplier
                      )MSS ON MSS.Id_supplier = BPB.id_supplier
                      LEFT JOIN(
                          SELECT id_item,itemname,Color,goods_code FROM masterstyle 
                      )MSST ON MSST.id_item = BPB.styleno
                      LEFT JOIN(
                          SELECT SUM(carton) j_carton,id_inv FROM invoice_detail GROUP BY id_inv 
                      )ID ON IAC.n_idinvoiceheader = ID.id_inv
                      INNER JOIN (
                          SELECT id_journal, reff_doc FROM fin_journal_h WHERE type_journal = '1'
                      )ji ON IAC.v_noinvoicecommercial = ji.reff_doc
                ) inv ON far.no_invoice = inv.v_noinvoicecommercial
            WHERE 1=1
              AND far.id_rekap = '$id_rekap'
        ";

        $invoices = $this->query($sql)->result();

        $dataInvoice = array();

        $row_id = 1;
        foreach($bank_masuk as $r){
            $row = (array) $r;
            $row['row_id'] = $row_id++;
            $row['id_journal'] = $id_journal;
            $dataInvoice[] = $row;
        }

        $dateadd = date('Y-m-d H:i:s', time());
        $_SESSION['username'] = $_SESSION['username'];
        foreach($invoices as $_bpb){
            $_bpb = (array) $_bpb;
            $amount = $_bpb['price'] * $_bpb['qty'];
            list($coa_debit, $coa_credit) = $this->get_id_coa_from_invoices($_bpb);

            // GR
            $_debit = array(
                'row_id' => $row_id++,
                'id_journal' => $id_journal,
                'id_coa' => $coa_debit['id'],
                'nm_coa' => $coa_debit['nm'],
                'curr' => $_bpb['curr'],
                'debit' => $amount,
                'credit' => 0,
                'description' => $_bpb['no_invoice'],//.' - '.$_bpb['product_group'].' - '.$_bpb['product_item'],
                'dateadd' => $dateadd,
                'useradd' => $_SESSION['username'],
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
                'description' => $_bpb['no_invoice'],//.' - '.$_bpb['product_group'].' - '.$_bpb['product_item'],
                'dateadd' => $dateadd,
                'useradd' => $_SESSION['username'],
            );

            $dataInvoice[] = $_debit;
            $dataInvoice[] = $_credit;
        }

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
			insert_log_nw($sql,$_SESSION['username']);
            $this->query($sql);
        }
    }





    public function get_id_coa_from_invoices($bpb){

        $id_group = $bpb['product_group'];
        $id_group = '';//override temporary

        //Default to lokal
        $bpb['area'] =  $bpb['area'] ?: 'L';

        $sql = "
            SELECT mp.sl_d, mcd.nm_coa nm_coa_d, mp.sl_k, mck.nm_coa nm_coa_k
            FROM mapping_coa_sales mp
            LEFT JOIN mastercoa mcd ON mcd.id_coa = mp.sl_d
            LEFT JOIN mastercoa mck ON mck.id_coa = mp.sl_k
            WHERE 1=1
            and vendor_cat = '{$bpb['vendor_cat']}' and area = '{$bpb['area']}'
            -- id_group = '{$id_group}' 
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


$idcoa       = '';
$id_journal  = '';
$d_create    = '';
$checkno     = '';
$nilai       = '';
$terimakeluar= '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if(isset($_POST['submit']) or isset($_POST['posting'])){
	
    $company = $M->get_master_company();
	if(ISSET($_POST['codekonsumen'])){
		
		$namakonsumen=$_POST['namakonsumen'];	
        $codekonsumen=$_POST['codekonsumen'];
        $rekap_ar=$_POST['rekap_ar'];
        $umurar="";


	}
	else{
		$namakonsumen="";	
        $codekonsumen="";
        $rekap_ar="";
        $umurar="";
		
		
		
	}
    $data = array(
		'company'        => $company->company,
		'period'         => $_POST['period'],
		'num_journal'    => '',//$_POST['num_journal'],
		'date_journal'   => date('Y-m-d', strtotime($_POST['date_journal'])),
		'type_journal'   => $_POST['type_journal'],
		'reff_doc'       => $_POST['reff_doc'],
		'fg_intercompany'=> $_POST['fg_intercompany'],
		'id_intercompany'=> $_POST['id_intercompany'],
		'idcoa'          =>	$_POST['idcoa'],
		'id_journal'     =>	$_POST['id_journal'],
		'd_create'       =>	date('Y-m-d'),
		'checkno'        =>	$_POST['checkno'],
		'nilai'          =>	$_POST['nilai'],
		'terimakeluar'   =>	$_POST['terimakeluar'],
		'no_voucher'   =>	$_POST['no_voucher'],
		'faktur_pajak'   =>	$_POST['faktur_pajak'],		
		
		
		'namakonsumen'        =>	$namakonsumen,
		'codekonsumen'          =>	$codekonsumen,
		'rekap_ar'          =>	$rekap_ar,
		'umurar'   =>	$umurar,

    );
		$arr_ = explode('/',$data['period']);
		$data['period'] = sprintf('%02d', $arr_[0])."/".$arr_[1];
    if($_POST['mode'] == 'save'){
        $data['dateadd'] = date('Y-m-d H:i:s', time());
        $data['useradd'] = $_SESSION['username'];
		if($data['type_journal'] == "CASH" ){
			if($_POST['terimakeluar'] == "P1"){
				$data['type_journal'] = "5";
			}
			else if($_POST['terimakeluar'] == "P2"){
				$data['type_journal'] = "10";
			}
		}else if($data['type_journal'] == "BANK" ){
			if($_POST['terimakeluar'] == "P1"){
				$data['type_journal'] = "11";
			}
			else if($_POST['terimakeluar'] == "P2"){
				$data['type_journal'] = "12";
			}
		}		
		

        $journal_code = Config::$journal_code[$data['type_journal']];
        switch($data['type_journal']){
            case Config::$journal_usage['purchase']:
                $bpb_check = $M->check_bpb($data['reff_doc']);
                if($bpb_check['errcode'] == '01') {
                    echo "<script>alert('Error: Nomor Refference (BPB) sudah digunakan');</script>";
                    $status = false; // Num journal already exists
                    $row = json_decode(json_encode($data));
                }else{
                    $data['id_journal'] = generate_coa_id("NAG", $journal_code,$data['date_journal']);

                    $M->insert_bpb_gr($data['reff_doc'], $data['id_journal']);
                    $M->save($data);
                    echo "<script>window.location.href='?mod=EntryBank&id={$data['id_journal']}';</script>";exit();
                }
                break;
            default:
                $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
				

                $M->save($data);
                echo "<script>window.location.href='?mod=EntryBank&id={$data['id_journal']}';</script>";exit();
        }
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
                $data_post = array(
                    'date_post' => date('Y-m-d H:i:s', time()),
                    'user_post' => $_SESSION['username']
                );
                $M->post_journal($id, $data_post);


                // Create jurnal penerimaan
                /*$journal_code = Config::$journal_code['4'];
                $data['id_journal'] = generate_coa_id("NAG", $journal_code, $data['date_journal']);
                $M->save_penerimaan_detail($data['id_journal'],$data);*/
                // End create jurnal penerimaan

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
		//print_r($row);
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


// CONTROLLER END terimakeluar


?>
<form method='post' name='form' enctype='multipart/form-data' action="" '>
    <div class='box'>
        <div class='box-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class='form-group' style="display:none">
                        <label>Periode Akuntansi</label>
                        <input type='text' id='periodpicker_' class='form-control' name='period' autocomplete="off" readonly
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
                      <option value="BANK" >Bank</option>
                  </select>


                    </div>
					<div class='form-group'>
					        <label>Penerimaan/Pengeluaran</label>
					<?php if(isset($row)){
						echo "<input type='hidden' name='terimakeluar' id='terimakeluar' class='form-control' readonly />";
						echo "<input type='text' name='namaterimakeluar' id='namaterimakeluar' class='form-control' readonly />";
						
					}	else{
						$dis = "";
						if((isset($row))){
							$dis = "disabled";
							
						}else{
							$dis = "";
							
						}
						
						
						echo "
                  <select id='terimakeluar' name='terimakeluar' $dis class='form-control' onchange='terimaK(this.value)' >
                      <option value='0' >--Pilih Satu--</option>
					  <option value='P1' >Penerimaan</option>
					  <option value='P2' >Pengeluaran</option>
                  </select>								
						";
						
					}	?>

</div>				  
                    <div class='form-group'>
                        <label>ID COA</label>

                  <select id='idcoa' name="idcoa" class='form-control'  onchange="getnamacoa(this.value)" >
					<option value="9X">--Select--</option>
                  </select>


                    </div>					
                    <div class='form-group'>
                        <label>Nama COA</label>


                         <input type='text' id='namacoa' class='form-control' name='namacoa' readonly
                                placeholder='Nama ' value='<?=(isset($row) and isset($row->nm_coa))?$row->nm_coa:''?>'>				  
					</div>
	                  <!--   <div class='form-group'>  
                         <label>Tanggal Dibuat</label>
                         <input type='text' id='d_create' class='form-control' name='d_create' autocomplete="off"
                                placeholder='Masukkan Tanggal dibuat' value='<? //=isset($row)?date('d/m/Y', strtotime($row->d_create)):''?>'>
                     </div>					 
                			-->		
					
                </div>
                 <div class='col-md-3'>

                     <div class='form-group'>
                         <label>CHEQUE NO.</label>
                         <input type='text' id='checkno' class='form-control' name='checkno' autocomplete="off"
                                placeholder='Masukkan CHEQUE NO.' value='<?=(isset($row) and isset($row->n_checkno))?$row->n_checkno:''?>'>		
                     </div>	
                     <div class='form-group'>
                         <label>Nilai.</label>
                         <input type='text' id='nilai' required class='form-control' name='nilai' autocomplete="off"
                                placeholder='Masukkan Nilai' onchange="getDefaultNumber(this)" value='<?=(isset($row) and isset($row->n_nilai))?$row->n_nilai:''?>'>
                     </div>				 
                     <div class='form-group'>
                         <label>Nomor Jurnal</label>
                         <input type='text' id='id_journal' class='form-control' name='id_journal' readonly
                                placeholder='(Auto)' value='<?=(isset($row) and isset($row->id_journal))?$row->id_journal:''?>'>
                     </div>
                     <div class='form-group'>
                         <label>Tanggal Voucher</label>
                         <input type='text' id='datepicker1_new' onchange="getPeriodAccounting(this);" class='form-control' name='date_journal' autocomplete="off"
                                placeholder='Masukkan Tanggal Voucher' value='<?=isset($row)?date('d M Y', strtotime($row->date_journal)):''?>'>
								
								

                     </div>

                     <div class='form-group'>
                         <label>Nomor Voucher</label>
                         <input type='text' id='no_voucher' readonly class='form-control' name='no_voucher' autocomplete="off"
                                placeholder='Masukkan No Voucher' value="<?=isset($row)?$row->v_novoucher:''?>">
                     </div>		
                </div>
                <div class='col-md-3'>
					                      <div class='form-group'>
                         <label>Nomor Invoice</label>

                         <input type='text' id='reff_doc' class='form-control' name='reff_doc'
                                placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->reff_doc:''?>'>
                     </div>				
	                     <div class='form-group'> 
                         <label>Faktur Pajak</label>:
                         <input type='text' id='faktur_pajak' class='form-control' name='faktur_pajak'
                                placeholder='Masukkan Faktur Pajak' value='<?=isset($row)?$row->v_fakturpajak:''?>'>
                     </div>				
                    <div class='form-group'>
                        <input type='hidden' id='' name='fg_intercompany' value="0">
                        <label><input type='checkbox' id='fg_intercompany' class='' onclick="toggleInterCompany()" name='fg_intercompany' value="1"
                                <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'checked':''?>> Inter-company</label>
                        <input type='text' id='id_intercompany' class='form-control' name='id_intercompany'
                               placeholder='IC Partner' value='<?=isset($row)?$row->id_intercompany:''?>'
                            <?=(isset($row->fg_intercompany) and $row->fg_intercompany=='1')?'':'readonly'?>>
                    </div>
					
					
					
					
					

			
					 <div class='form-group' style="display:none">
                         <label>Tanggal Jatuh Tempo AR</label>
                         <input type='text' id='tgltempoar' class='form-control' name='tgltempoar'
                                placeholder='Masukkan Tanggal Tempo AR' value='<?=isset($row)?date('d M Y', strtotime($row->d_tgltempoar)):''?>'>
                     </div>	



			
                </div>
				 <div class='col-md-3 hidden'>

					<div class='form-group'>
					<label><input type='checkbox' disabled id='umurar' class='' onclick="togglecheckboxar()" name='umurar' value="1"
                                <?=(isset($row->v_namakonsumen))?'checked':''?>> <font size="3">AR</font><br/> Code Customer</label>
						<select disabled id='codekonsumen' onchange="getNamaSupplier(this.value)" class='form-control select2' name='codekonsumen'>
                            <option value="" disabled selected>Pilih Code</option>

                        </select>
					</div>
					 <div class='form-group'>
                         <label>Nama Customer</label>
                         <input  type='text' id='namakonsumen' disabled class='form-control' name='namakonsumen'
                                placeholder='Masukkan Nama Customer' value='<?=isset($row)?$row->v_namakonsumen:''?>'>
                     </div>

                     <div class='form-group'>
                         <label>Rekap AR</label>
                         <div class="input-group">
                             <input type='text' id='reff_doc2' disabled class='form-control' name='rekap_ar'
                                    placeholder='Masukkan Referensi Dokumen' value='<?=isset($row)?$row->v_rekap_ar:''?>'>
                             <a onclick="modal_ref2()" style="cursor: pointer" class="input-group-addon" >...</a>
                         </div>
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
                    <button type='submit' id='go_to_save' name='submit' class='btn btn-primary validasi_proses' style="display:none">Simpan</button>
					<a id='validasi_save' onclick="validasi(this.id)" name='submit' class='btn btn-primary validasi_proses'>Simpan</a>
                    <?php endif;?>
                    <?php if(isset($row) and !@$row->fg_post and $id):?>
                    <button type='submit' id='go_to_save' name='submit' class='btn btn-primary validasi_proses' style="display:none">Simpan</button>
					<a id='validasi_save' onclick="validasi(this.id)" name='submit' class='btn btn-primary validasi_proses' >Simpan</a>
                    <button type='submit' id='go_to_posting' name='posting' class='btn btn-primary validasi_proses' style="display:none">Posting</button>
					<a id='validasi_posting' onclick="validasi(this.id)" name='submit' class='btn btn-primary validasi_proses'>Posting</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>

</form>
<?php if($id):?>
    <?php include("journal_entry_form_item.inc_cashbank.php"); ?>
<?php endif;?>
<div class="modal fade" id="modalBpb"  role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Lookup Bpb</h4>
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
                    <div class="row">
                        <div class="col-md-12">
                            <em>Gunakan % untuk pencarian wildcard</em>
                        </div>
                    </div>
                </div>
                <div class="bpbresult">
                    <table class="table">
                        <thead>
                            <th>Supplier</th>
                            <th>Kode</th>
                            <th>Num of Items</th>
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


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="js/CashBank.js"></script>
<script>

<?php


?>
$(function () {
    $('#datepicker1_new').datepicker
    ({  format: "dd M yyyy",
        autoclose: true,
		  maxDate: new Date(),
    });
	})
    function modal_ref(){
        var tipe_jurnal = $('#type_journal').val();
		alert(tipe_jurnal);
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
                rows += '<tr>';
                rows += '<td>' + invoice_list[i].id_rekap + '</td>';
                rows += '<td>' + invoice_list[i].invoices + '</td>';
                rows += '<td>' + invoice_list[i].j_price + '</td>';
                rows += '<td>' + invoice_list[i].jqty + '</td>';
                rows += '<td><a onclick="select_rekap(' + "'" + invoice_list[i].id_rekap + "'" + ', ' + "'" + invoice_list[i].j_price + "'" + ')" class="btn btn-info">Choose</td>';
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
    function modal_bpb(){
        $('#modalBpb').modal('show');
        $("#modalBpb .bpbresult table tbody").html('');
        $("#bpbNo").val('');
        $("#bpbNoInternal").val('');
    }
    function lookup_bpb(){
        var request = $.ajax
        ({  type: "POST",
            url: "ajax_fin.php?mdajax=lookup_supplier",
            data: "invno=" +$("#invno").val()+"&supplier="+$("#supplier").val(),
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
					rows += '<td>' + invoice_list[i].Supplier + '</td>';
                    rows += '<td>' + invoice_list[i].invno + '</td>';
					rows += '<td>' + invoice_list[i].jumlah + '</td>';
                    //rows += '<td>' + invoice_list[i].bpbno_int + '</td>';
                   // rows += '<td>' + invoice_list[i].item_count + '</td>';
                    rows += '<td><a onclick="select_bpb(' + "'" + invoice_list[i].invno + "'" + ')" class="btn btn-info">Choose</td>';
                    rows += '</tr>';
                }
            }else{
                rows = '<tr><td colspan="4" class="text-center"><em>--Bpb not found--</em></td></tr>';
            }
            $("#modalBpb .bpbresult table tbody").html(rows);
        }else{
            alert(request.message);
        }
    }
    function select_bpb(bpb){
        $('#reff_doc').val(bpb);
        $('#modalBpb').modal('hide');
    }
    function toggleInterCompany(){
        if($('#fg_intercompany').is(':checked')){
            $('#id_intercompany').attr('readonly', false);
        }else{
            $('#id_intercompany').attr('readonly', true);
        }
    }	
    function validasi(id_nya) {
			//return false;9X datepicker1_new
			$("#datepicker1_new").attr("disabled",false);
			//return false;
		var idcoa =$("#idcoa").val();
        var period =$("#periodpicker_").val(); //terimakeluar
        var type_journal = $("#type_journal").val();
        //var num_journal = document.form.num_journal.value;
		var nilai = $("#nilai").val(); // datepicker1_new
        var date_journal =  $("#datepicker1_new").val();
		//alert(date_journal);
		var terimakeluar = $("#terimakeluar").val();
        //var reff_doc = document.form.reff_doc.value;
		//var datepicker1_new = document.form.datepicker1_new.value;
        if (period == '') {
            alert('Error: Periode Akuntansi tidak boleh kosong');
          //  document.form.period.focus();
            return false;
        } 
		 if (type_journal == '') {
            alert('Error: Tipe Jurnal tidak boleh kosong');
         //   document.form.type_journal.focus();
            return false;
			$("#datepicker1_new").attr("disabled",true);
        }
		 if (date_journal == '') {
            alert('Error: Tanggal Voucher tidak boleh kosong');
          //  document.form.datepicker1_new.focus();
            return false;
			$("#datepicker1_new").attr("disabled",true);
        }		
		 if (nilai == '') {
            alert('Error: Nilai tidak boleh kosong');
            //document.form.nilai.focus();
           return false;
			$("#datepicker1_new").attr("disabled",true);
        }		
	
		 if (idcoa == '9X') {
            alert('Error: ID COA tidak boleh kosong');
           // document.form.idcoa.focus();
            return false;
			$("#datepicker1_new").attr("disabled",true);
        }		
		 if (terimakeluar == '0') {
            alert('Penerimaan/Pengeluaran tidak boleh kosong');
          //  document.form.terimakeluar.focus();
             return false;
			$("#datepicker1_new").attr("disabled",true);
        } 
/* 
		 if (num_journal == '') {
            alert('Error: Nomor Dokumen tidak boleh kosong');
           // document.form.num_journal.focus();
            valid = false;
			$("#datepicker1_new").attr("disabled",true);
        }  */ /* if (date_journal == '') {
            alert('Error: Tanggal Dokumen tidak boleh kosong');
           // document.form.date_journal.focus();
             return false;
			
        } */
	$("#datepicker1_new").attr("disabled",false);
	
	if(id_nya == 'validasi_save'){
		$("#go_to_save").trigger("click");
	}else if(id_nya == 'validasi_posting'){
		$("#go_to_posting").trigger("click");
	}

	
    }
</script>