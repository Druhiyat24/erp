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

    public function get_journal_summary_list()
    {
        $sql = "
            SELECT 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
                ,SUM(jd.debit) total_debit
                ,SUM(jd.credit) total_credit
            FROM 
                fin_journal_h jh
                LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
            WHERE 1=1
              AND (jd.id_coa IS NULL OR jd.id_coa NOT IN ('21001', 	'21002', 	'21003', 	'21004', 	'21005', 	'21006', 	'21007', 	'21008', 	'21009', 	'21010', 	'21011', 	'21049', 	'21101', 	'21102', 	'21103', 	'21104', 	'21105', 	'21106', 	'21107', 	'21108', 	'21109', 	'21110', 	'21111', 	'21149'))
            GROUP BY 
                jh.id_journal
                ,jh.date_journal
                ,jh.type_journal
                ,jh.reff_doc
                ,jh.period
                ,jh.fg_post
        ";

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

$list = $M->get_journal_summary_list();
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
                  <select id="journal_type" class="form-control" onchange="filter_table()">
                      <option value="">-- Select Journal Type --</option>
                      <?php foreach(Config::$journal_type as $j):?>
                      <option <?=($search['journal_type']==$j)?'selected':''?>><?=$j?></option>
                      <?php endforeach;?>
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
                      <option value="" >-- Pilih Status --</option>
                      <?php foreach(Config::$posting_flag as $_id):?>
                          <option <?=($search['posting_flag']==$_id)?'selected':''?>><?=$_id?></option>
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
            <th>Tanggal</th>
            <th>Tipe</th>
            <th>Periode</th>
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
            <td><?=$l->date_journal?></td>
            <td><?=@Config::$journal_type[$l->type_journal]?></td>
            <td><?=$l->period?></td>
            <td><?=$l->reff_doc?></td>
            <td><?=number_format($l->total_debit)?></td>
            <td><?=@Config::$posting_flag[$l->fg_post]?></td>
            <td>
                <?php switch($l->type_journal){
                    case '1': $mod = 'js';break;
                    case '2': $mod = 'jp';break;
                    case '3': $mod = 'jpay';break;
					case '5': $mod = 'EntryCashBank';break;
                    case '6': $mod = 'jg';break;
					 case '10': $mod = 'EntryCashBank';break;
					 case '11': $mod = 'EntryBank';break;
					 case '12': $mod = 'EntryBank';break;
                    default : $mod = 'jefh';break;
                }?>
                <a class="btn btn-primary btn-s" href="?mod=<?=$mod?>&amp;id=<?=$l->id_journal?>" data-toggle="tooltip" title="" data-original-title="Lihat"><i class="fa fa-pencil"></i> </a>
                <a class="btn btn-primary btn-s" href="pdfVoucher.php?id=<?=$l->id_journal?>" data-toggle="tooltip" title="Cetak Voucher" data-original-title="Cetak Voucher" target="_blank"><i class="fa fa-print"></i> </a>
                <?php //if($l->fg_post!='2'):?>
                <a class="btn btn-danger btn-s" onclick="return confirm('Hapus Jurnal?');" href="?mod=je&amp;id=<?=$l->id_journal?>&amp;action=del" data-toggle="tooltip" title="" data-original-title="Hapus"><i class="fa fa-times"></i> </a>
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
        table.column(2).search(journalType);

        var periode = $('#period').val();
        table.column(3).search(periode);

        var posting_flag = $('#posting_flag').val();
        table.column(6).search(posting_flag);

        var keyword = $('#keyword').val();
        table.search(keyword);

        table.draw();

        $.ajax
        ({  type: "POST",
            url: "?mod=je&action=search",
            data: "journal_type=" +journalType+"&period="+periode+"&posting_flag="+posting_flag+"&keyword="+keyword,
            dataType: "html",
            async: true
        });
    }
</script>