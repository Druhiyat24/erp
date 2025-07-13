<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$akses = flookup("F_P_List_Jurnal","userpassword","username='$user'");
if ($akses=="0") 
{ echo "<script>alert('Akses tidak dijinkan'); window.location.href='?mod=1';</script>"; }
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }
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
	
	public function getPrivillagesJournal(){
		$where_Select = '';
		$useName = $_SESSION['username'];
		$menu_SS = ['','F_P_Penjualan','F_P_Pembelian','F_P_Pembayaran','F_P_Alokasi_AR','finance','F_P_J_Umum','F_P_Fixed_Asset','F_P_Adjustment','F_P_J_Closing','finance','F_P_Bank','F_P_Bank','F_P_Penerimaan','F_P_Kontrabon','0','0','F_P_Subcon'];		
		$array_type_journal = array();
		$id_coa_where =  array();
		
		$cnt = count($menu_SS);
		$id_coa_cash = array();
		
		if($cnt > 0){
			for($xx=1;$xx<$cnt;$xx++){
				$myKYX = flookup($menu_SS[$xx],"userpassword","username='$useName'");
				
				if($myKYX == '1'){
					if($menu_SS[$xx] == "finance"){
						$cash_kecil_pabrik = flookup("F_P_Cash_Pabrik","userpassword","username='$useName'");
						$cash_kecil_kantor = flookup("F_P_Cash_Kantor","userpassword","username='$useName'");
						$cash_besar 	   = flookup("F_P_Cash_Besar","userpassword","username='$useName'");
						if($cash_kecil_pabrik  == '1' || $cash_kecil_kantor  == '1' || $cash_besar  == '1' ){
							//array_push($array_type_journal,$xx);
							//$implode_nya_jurnal = implode(',',$array_type_journal);							
							if($cash_kecil_pabrik  == '1'){
								//$id_coa_where = '10101'
								array_push($id_coa_where,'10101');
							}if($cash_kecil_kantor == '1'){
								array_push($id_coa_where,'10102');
							}if($cash_besar == '1'){
								array_push($id_coa_where,'10103');
							}							
						}
						
						array_push($array_type_journal,$xx);
						
					}					
					else{
						array_push($array_type_journal,$xx);
						//$implode_nya_jurnal = implode(',',$array_type_journal);						
					}
				}

				
			}	
			$implode_nya_jurnal = implode(',',$array_type_journal);	
			$implode_nya_coa    =  implode(',',$id_coa_where);	
			$where_coa = '';
			$count_coanya = count($id_coa_where);
			if($count_coanya > 0 ){
				$where_coa = "WHERE LIST.id_coa IN ($implode_nya_coa)";
				//echo $where_coa;
			}
			else{
				$where_coa = "WHERE LIST.id_coa IN ('999999999999999999')";
				
				
			}
			$where_Select = "WHERE LIST.type_journal IN ($implode_nya_jurnal) AND LIST.type_journal NOT IN('15','16')";
			
			
		}
		else{
			$where_Select = "WHERE LIST.type_journal IN ('TAKADAAKSES!')";
			
		}
		//print_r($array_type_journal);
		//echo $where_Select;
		$array_where = array();
		array_push($array_where,$where_Select);
		array_push($array_where,$where_coa);
		
		return $array_where;
	}
	
	
    public function get_journal_summary_list($type_journal)
    {
		$journal_detail_pph = journal_detail();
		$WHERE = $this->getPrivillagesJournal();
		$WHERE_SATU = $WHERE[0];
		$WHERE_DUA = $WHERE[1];
		if($type_journal == '14'){
			$sql = "			SELECT YY.* FROM (
			SELECT 
			LIST.id_journal
                ,LIST.date_journal
                ,LIST.type_journal
                ,LIST.reff_doc
                ,LIST.period
                ,LIST.fg_post
                ,(pph_value.amount)nilai_journal
                ,LIST.total_credit
				,LIST.total_debit nilai_journal_vef_pph
                ,LIST.row_id 
				,LIST.id_coa
				,LIST.voucher
				,MSS.Supplier supplier
				,'' customer
				FROM(
			SELECT 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
				,(jd.total)total_debit
                ,0 total_credit
				
                ,jd.row_id
                ,jd.id_coa
				,jhdd.v_novoucher voucher
            FROM 
                fin_journal_h jh
		LEFT JOIN (
		SELECT 
				(SUM(X.debit) - ((2*SUM(X.utang))) - (X.pajak) )total
				,X.* FROM(	
					SELECT a.reff_doc bpb_ref
						,b.id_coa coa_utang
						,a.curr
						,a.id_coa
						,a.reff_doc2 journal_ref
						,a.id_journal
						,a.row_id
						,a.description
						,a.debit
						,if(a.id_coa = '15204' AND a.debit > 0,'1','0' )is_pajak
						,if(a.id_coa = '15204' AND a.debit > 0,a.debit,0 )pajak
						,if(b.id_coa IS NOT NULL AND a.debit > 0,a.debit,0)utang
						,if(b.id_coa IS NOT NULL,'D','C')v_normal
						,a.credit  FROM fin_journal_d  a
					LEFT JOIN mapping_utang b ON a.id_coa = b.id_coa
					WHERE 1 = 1  
					 /* AND a.id_journal = 'NAG-PK-2001-00007' */
					AND a.id_journal  LIKE '%-PK-%'
									AND a.debit > 0 GROUP BY a.id_journal,a.row_id
			)X WHERE is_pajak = 0 GROUP BY X.id_journal,X.is_pajak
		)jd ON jd.id_journal = jh.id_journal 
				LEFT JOIN (SELECT v_idjournal,v_novoucher FROM fin_journalheaderdetail)jhdd ON jh.id_journal = jhdd.v_idjournal
            WHERE 1=1

            GROUP BY 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
				)LIST
	LEFT JOIN( $journal_detail_pph )pph_value
	ON LIST.id_journal = pph_value.id_journal
	LEFT JOIN bpb BPB ON BPB.bpbno_int = LIST.reff_doc
	LEFT JOIN mastersupplier MSS ON BPB.id_supplier = MSS.Id_Supplier
	WHERE  LIST.type_journal IN (14)
)YY WHERE 1=1 GROUP BY YY.id_journal";
			
		}
		else
		{
		$sql = "
			SELECT YY.* FROM (
			SELECT 
			LIST.id_journal
                ,LIST.date_journal
                ,LIST.type_journal
                ,LIST.reff_doc
                ,LIST.period
                ,LIST.fg_post
                ,LIST.total_debit
                ,LIST.total_credit
				,if(LIST.total_debit >LIST.total_credit,LIST.total_debit,LIST.total_credit )nilai_journal
                ,LIST.row_id 
				,LIST.id_coa
				,LIST.voucher
				,MSS.Supplier supplier
				,MSC.Supplier customer
				FROM(
			SELECT 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
                ,SUM(jd.debit) total_debit
                ,SUM(jd.credit) total_credit
                ,jd.row_id
                ,jd.id_coa
				,jhdd.v_novoucher voucher
            FROM 
                fin_journal_h jh
                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
				LEFT JOIN (SELECT v_idjournal,v_novoucher FROM fin_journalheaderdetail)jhdd ON jh.id_journal = jhdd.v_idjournal
            WHERE 1=1
              AND (jd.id_coa IS NULL OR jd.id_coa NOT IN ('10101','10102','10103','21001', 	'21002', 	'21003', 	'21004', 	'21005', 	'21006', 	'21007', 	'21008', 	'21009', 	'21010', 	'21011', 	'21049', 	'21101', 	'21102', 	'21103', 	'21104', 	'21105', 	'21106', 	'21107', 	'21108', 	'21109', 	'21110', 	'21111', 	'21149'))
            GROUP BY 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
				)LIST
	LEFT JOIN bpb BPB ON BPB.bpbno_int = LIST.reff_doc
	LEFT JOIN mastersupplier MSS ON BPB.id_supplier = MSS.Id_Supplier
	LEFT JOIN invoice_header IH ON IH.invno = LIST.reff_doc
	LEFT JOIN mastersupplier MSC ON IH.id_buyer = MSC.Id_Supplier
	$WHERE_SATU
	GROUP BY LIST.id_journal
UNION ALL
			SELECT 
			LIST.id_journal
                ,LIST.date_journal
                ,LIST.type_journal
                ,LIST.reff_doc
                ,LIST.period
                ,LIST.fg_post
                ,LIST.total_debit
                ,LIST.total_credit
				,if(LIST.total_debit >LIST.total_credit,LIST.total_debit,LIST.total_credit )nilai_journal
                ,LIST.row_id 
				,LIST.id_coa
				,LIST.voucher
				,MSS.Supplier supplier
				,MSC.Supplier customer
				FROM(
			SELECT 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
                ,SUM(jd.debit) total_debit
                ,SUM(jd.credit) total_credit
                ,jd.row_id
                ,jd.id_coa
				,jhdd.v_novoucher voucher
            FROM 
                fin_journal_h jh
                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
				LEFT JOIN (SELECT v_idjournal,v_novoucher FROM fin_journalheaderdetail)jhdd ON jh.id_journal = jhdd.v_idjournal
            WHERE 1=1
              AND (jd.id_coa IS NULL OR jd.id_coa NOT IN ('21001', 	'21002', 	'21003', 	'21004', 	'21005', 	'21006', 	'21007', 	'21008', 	'21009', 	'21010', 	'21011', 	'21049', 	'21101', 	'21102', 	'21103', 	'21104', 	'21105', 	'21106', 	'21107', 	'21108', 	'21109', 	'21110', 	'21111', 	'21149'))
            GROUP BY 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
				)LIST
	LEFT JOIN bpb BPB ON BPB.bpbno_int = LIST.reff_doc
	LEFT JOIN mastersupplier MSS ON BPB.id_supplier = MSS.Id_Supplier
	LEFT JOIN invoice_header IH ON IH.invno = LIST.reff_doc
	LEFT JOIN mastersupplier MSC ON IH.id_buyer = MSC.Id_Supplier
	$WHERE_DUA
	GROUP BY LIST.id_journal)YY WHERE 1=1 AND YY.nilai_journal > 0 AND YY.type_journal='$type_journal' GROUP BY YY.id_journal
	LIMIT 1000
	
				";
	}
		
		//echo "<pre>$sql</pre>";
		
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

    public function delete_journal($id)
    {
        $sql = "
            DELETE
            FROM 
                fin_journal_d 
                WHERE id_journal = '$id'
        ";

        $this->query($sql);


        $sql = "
            DELETE
            FROM 
                fin_journal_h 
                WHERE id_journal = '$id'
        ";

        $this->query($sql);

        $sql = "
            DELETE
            FROM 
                fin_journalheaderdetail 
                WHERE v_idjournal = '$id'
        ";
        $this->query($sql);
        $sql = "
            DELETE
            FROM 
                fin_prosescashbank 
                WHERE v_idjournal = '$id'
        ";
        $this->query($sql);		
        return $this->conn->affected_rows;
    }
}

// CONTROLLER BEGIN

$M = new Model($con_new);

if(@$_GET['action']=='del'){
    $M->delete_journal($_GET['id']);
    echo "<script>window.location.href='?mod=je';</script>";exit();
}elseif(@$_GET['action']=='search'){
    // Store last search
    $_SESSION['search_journal'] = array(
        'journal_type' => $_POST['journal_type'],
        'period' => $_POST['period'],
        'posting_flag' => $_POST['posting_flag'],
        'keyword' => $_POST['keyword'],
    );
    exit();
}

$search = array(
    'journal_type' => '',
    'period' => '',
    'posting_flag' => '',
    'keyword' => '',
);
if(isset($_SESSION['search_journal'])){
    $search = $_SESSION['search_journal'];
}
$par_jurnal = 99;
if(ISSET($_GET['jurnal'])){
	$par_jurnal =$_GET['jurnal'];
	$list = $M->get_journal_summary_list($_GET['jurnal']);
}
//$list = $M->get_journal_summary_list();
$acct_period = $M->get_accounting_period();
// CONTROLLER END


?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<div class="box">
<!--  <div class="box-header">-->
<!--      <a href="../fin/?mod=jefh" class="btn-primary btn pull-right"><i class="fa fa-plus"></i> New Journal</a>-->
<!--  </div>-->
  <div class="box-body">
      <div class="panel panel-default">
      <div class="panel-heading">Filter</div>
      <div class="table_filter panel-body">
          <div class="row">
              <div class="col-md-3">
                 <!-- <select id="journal_type" class="form-control" onchange="filter_table('CURRENT')"> -->
                      <select  class="form-control" onchange="getJournal(this.value)"> 
					  
<?php
					$menu_ = ['','F_P_Penjualan','F_P_Pembelian','F_P_Pembayaran','F_P_Alokasi_AR','finance','F_P_J_Umum','F_P_Fixed_Asset','F_P_Adjustment','F_P_J_Closing','finance','F_P_Bank','F_P_Bank','F_P_Penerimaan','F_P_Kontrabon','0','0','F_P_Subcon'];
						echo "<option value='99' >-- Select Journal Type --</option>";
						$PLUS = 1;
						$username = $_SESSION['username'];
						$val_nya = 0;
				      foreach(Config::$journal_type as $j){
						  $val_nya++;
						  $x = $menu_[$PLUS];
						  $myKY = flookup($x,"userpassword","username='$username'");
						  if($myKY == 1){  
							if($menu_[$PLUS] == "finance"){
									$cash_kecil_pabrik = flookup("F_P_Cash_Pabrik","userpassword","username='$username'");
									$cash_kecil_kantor = flookup("F_P_Cash_Kantor","userpassword","username='$username'");
									$cash_besar 	   = flookup("F_P_Cash_Besar","userpassword","username='$username'");								
								if($cash_kecil_pabrik  == '1' || $cash_kecil_kantor  == '1' || $cash_besar  == '1' ){ ?>
										<option  value='<?= $val_nya ?>' <?=($val_nya==$par_jurnal)?'selected':''?>><?=$j?></option>
									<?php		}	
									}else{ ?>
									<option value='<?= $val_nya ?>' <?=($val_nya==$par_jurnal)?'selected':''?>><?=$j?></option>
							<?php  }		 } 
					 $PLUS++; };
			
?>					  
					  
					  
					  
					  

                  </select>
              </div>
              <div class='col-md-3'>
                  <input type='text' id='period' class='monthpicker form-control' name='period' onchange="filter_table()"
                         placeholder='MM/YYYY' value='<?=$search['period']?>'>
                  <?php /*<select id='period' class='form-control' onchange="filter_table()">
                      <option value="" >-- Pilih Periode --</option>
                      <?php foreach($acct_period as $_id):?>
                          <option <?=($search['period']==$_id)?'selected':''?>><?=$_id?></option>
                      <?php endforeach;?>
                  </select>*/?>
              </div>
              <div class='col-md-3'>
                  <select id='posting_flag' class='form-control' onchange="filter_table()">
                      <option  value="" >-- Pilih Status --</option>
                      <?php foreach(Config::$posting_flag as $_id):?>
                          <option  <?=($search['posting_flag']==$_id)?'selected':''?>><?=$_id?></option>
                      <?php endforeach;?>
                  </select>
              </div>
              <div class='col-md-3'>
                  <input type="text" id='keyword' value="<?=$search['keyword']?>" class='form-control' placeholder="Keyword" onkeyup="filter_table()" />
              </div>
          </div>
      </div>
      </div>
  	<table id="tbl_journal" class="display responsive" style="width:100%;font-size:12px;">
      <thead>
        <tr>
	    	<th>Nomor Dokumen</th>
			<th>Nomor Voucher</th>
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Periode</th>
			<th>Nama Customer</th>
            <th>Reff</th>
            <th>Nilai Jurnal</th>
            <th>Status</th>
            <th width='14%'>Action</th>
		</tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$l->id_journal?></td>
			<td><?=$l->voucher?></td>
            <td><?=$l->date_journal?></td>
            <td><?=@Config::$journal_type[$l->type_journal]?></td>
            <td><?=$l->period?></td>
			<td><?php 
			if(ISSET($l->customer)){
				IF((ISSET($_GET['jurnal']) && $_GET['jurnal'] == '14') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '10') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '3') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '2') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '17') ){
					echo '';
				}ELSE{
					echo $l->customer;
				}
			}
			if(ISSET($l->supplier)){
				IF((ISSET($_GET['jurnal']) && $_GET['jurnal'] == '14') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '10') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '3') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '2') || (ISSET($_GET['jurnal']) && $_GET['jurnal'] == '17') ){
				   echo '';
				}ELSE{
					echo $l->supplier;
				}
			}
				
			?></td>
            <td><?=$l->reff_doc?></td>
            <td><?=number_format($l->nilai_journal,2,',','.')?></td>
            <td><?=@Config::$posting_flag[$l->fg_post]?></td>
            <td>
                <?php switch($l->type_journal){
                    case '1': $mod = 'js';break; // Jurnal Penjualan
                    case '2': $mod = 'jp';break; // Jurnal pembelian
                    case '3': $mod = 'jpay';break; //Jurnal Pembayaran
                    case '4': $mod = 'jallocar';break;//Jurnal Allokasi ar
					case '5': $mod = 'EntryCashBank';break; //CashMasuk
                    case '6': $mod = 'jg';break;//Jurnal Umun
					 case '10': $mod = 'EntryCashBank';break;//cash keluar
					 case '11': $mod = 'EntryBank';break;//bank masuk
					 case '12': $mod = 'EntryBank';break;//bank masuk
					 case '13': $mod = 'jrcp';break;//penerimaan
					 case '14': $mod = 'kb';break;//penerimaan
                    default : $mod = 'jefh';break;
                }?>
                <a class="btn btn-primary btn-s" href="?mod=<?=$mod?>&amp;id=<?=$l->id_journal?>" data-toggle="tooltip" title="" data-original-title="Lihat"><i class="fa fa-pencil"></i> </a>
				<?php 
					if($l->type_journal == '14'){
						echo "<a class='btn btn-primary btn-s' href='pdf_kontrabon.php?id=$l->id_journal' data-toggle='tooltip' title='Cetak Voucher' data-original-title='Cetak Voucher' target='_blank'><i class='fa fa-print'></i> </a>";
						
					}
					else{
						echo "<a class='btn btn-primary btn-s' href='pdfVoucher.php?id=$l->id_journal' data-toggle='tooltip' title='Cetak Voucher' data-original-title='Cetak Voucher' target='_blank'><i class='fa fa-print'></i> </a>";					
						
					}
				
				
				?>
                <?php //if($l->fg_post!='2'):?>
                <!--<a class="btn btn-danger btn-s" onclick="return confirm('Hapus Jurnal?');" href="?mod=je&amp;id=<?=$l->id_journal?>&amp;action=del" data-toggle="tooltip" title="" data-original-title="Hapus"><i class="fa fa-times"></i> </a>
                -->
				<?php //endif;?>
            </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
  </div>
</div>

<style>
    #tbl_journal_filter{
        display: none;
    }
</style>
<script>
    var table;
    $(document).ready(function(){
        table = $('#tbl_journal').DataTable
        ({  scrollY: "300px",
            scrollCollapse: true,
            paging: true,
            pageLength: 100,
            order: [0, 'desc'],
            fixedColumns:
                { leftColumns: 1,
                    rightColumns: 1
                }
        });
        filter_table();
    });

    function filter_table()
    {

        var journalType = $('#journal_type').val();
        table.column(3).search(journalType);			
		

        var periode = $('#period').val();
        table.column(4).search(periode).draw();

        var posting_flag = $('#posting_flag').val();
        table.column(8).search(posting_flag)

        var keyword = $('#keyword').val();
        table.search(keyword);

        table.draw();	
		// otable.fnFilter("Jurnal Pembelian|Jurnal Cash Keluar", 3, true, false, false, false); 
        $.ajax
        ({  type: "POST",
            url: "?mod=je&action=search",
            data: "journal_type=" +journalType+"&period="+periode+"&posting_flag="+posting_flag+"&keyword="+keyword,
            dataType: "html",
            async: true
        });
    }
	function getJournal(val__nya){
		window.location.href = '/erp/pages/fin/?mod=je&jurnal='+val__nya;
	}
</script>