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


class Helper{
    public function count_end_balance($d, $k, $id_coa){
        $C = new Config();
        $nb = $C->get_normal_balance($id_coa);

        if($nb == 'k'){
            return $k - $d;
        }else{
            return $d - $k;
        }
    }
}
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
}
class Model{
    private $conn;
    private $result;
    private $fg_mapping_bs = '1';// Balance sheet flag
    private $fg_mapping_pnl = '2';// Balance sheet flag

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
//            show_error($err);
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

    public function get_accounting_period()
    {
        //TODO: fetch from actual table
        $periods = array();
        for($i=-12;$i<=12;$i++){
            if($i<0){
                $periods[] = date('m/Y', strtotime(date('Y-m-d', time())." $i month"));
            }elseif($i==0){
                $periods[] = date('m/Y', time());
            }else{
                $periods[] = date('m/Y', strtotime(date('Y-m-d', time())." +$i month"));
            }
        }

        return $periods;
//        return array(
//            date('m/Y', strtotime(date('Y-m-d', time()).' -6 month')),
//            date('m/Y', time()),
//            date('m/Y', strtotime(date('Y-m-d', time()).' +6 month')),
//        );
    }

    /**
     * Get report data and transform into report friendly format
     * @param $jo_id Job Order ID
     * @return array of Job Order and Sales Order
     */
    public function get_report()
    {
        $sql = "
            SELECT 
                id_coa
                ,nm_coa
                ,id_supplier 
                ,supplier_code kd
                ,supplier nm
                ,SUM(debit) debit
                ,SUM(credit) credit
                ,SUM(credit) - SUM(debit) endbal
            FROM
            (
                (
                    SELECT 
                        ms.id_supplier
                        ,ms.supplier_code
                        ,ms.Supplier
                        ,ms.terms_of_pay
                        ,ms.vendor_cat
                        ,jh.id_journal
                        ,jh.date_journal
                        ,DATE_ADD(jh.date_journal, INTERVAL ms.terms_of_pay DAY) due_date
                        ,PERIOD_DIFF(DATE_FORMAT(CURDATE(), \"%Y%m\"), DATE_FORMAT(DATE_ADD(jh.date_journal, INTERVAL ms.terms_of_pay DAY), \"%Y%m\")) age
                        ,jh.reff_doc
                        ,jd.id_coa
                        ,jd.nm_coa
                        ,jd.curr
                        ,jd.debit 
                        ,jd.credit 
                        ,jd.description
                        ,'0' tipe
                    FROM
                        fin_journal_h jh
                        INNER JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                        LEFT JOIN (SELECT DISTINCT bpbno_int, id_supplier FROM bpb) _bpb ON jh.reff_doc = _bpb.bpbno_int
                        LEFT JOIN mastersupplier ms ON _bpb.id_supplier = ms.Id_Supplier
                    WHERE 1=1
                        AND jh.type_journal = '2'
                        AND jh.fg_post = '2'
                        AND jd.id_coa IN (SELECT DISTINCT ir_k FROM  mapping_coa)
                ) 
                UNION ALL 
                (
                    SELECT 
                        ms.id_supplier
                        ,ms.supplier_code
                        ,ms.Supplier
                        ,ms.terms_of_pay
                        ,ms.vendor_cat
                        ,jh.id_journal
                        ,jh.date_journal
                        ,DATE_ADD(jh.date_journal, INTERVAL ms.terms_of_pay DAY) due_date
                        ,PERIOD_DIFF(DATE_FORMAT(CURDATE(), \"%Y%m\"), DATE_FORMAT(DATE_ADD(jh.date_journal, INTERVAL ms.terms_of_pay DAY), \"%Y%m\")) age
                        ,jh.reff_doc
                        ,jd.id_coa
                        ,jd.nm_coa
                        ,jd.curr
                        ,jd.debit 
                        ,jd.credit
                        ,jd.description
                        ,'1' tipe
                    FROM 
                        fin_journal_h jh
                        LEFT JOIN fin_journal_d jd ON jh.id_journal = jd.id_journal
                        LEFT JOIN (SELECT DISTINCT bpbno_int, id_supplier FROM bpb) _bpb ON jh.reff_doc = _bpb.bpbno_int
                        LEFT JOIN mastersupplier ms ON _bpb.id_supplier = ms.Id_Supplier
                    WHERE 1=1
                        AND jh.type_journal = '3'
                        AND jh.fg_post = '2'
                        AND jd.id_coa IN (SELECT DISTINCT ir_k FROM  mapping_coa)
                )  
            ) hutang
            GROUP BY
                id_coa
                ,nm_coa
                ,id_supplier
                ,supplier_code
                ,supplier
            ORDER BY
                id_coa
                ,id_supplier
        ";


        $result = $this
            ->query($sql)
            ->result();

        $result = json_decode(json_encode($result), TRUE);
        return $result;
    }
}


// Instantiate model object
$m = new Model($con_new);
$ch = new Coa_helper();

// Instantiate helper object
$h = new Helper();

$suppliers = $m->get_report();

$total = array(
    'debit' => 0,
    'credit' => 0,
    'endbal' => 0
);

$category = array();
foreach($suppliers as $s){
    if(!isset($category[$s['id_coa']])){
        $category[$s['id_coa']] = array(
            'id_coa' => $s['id_coa'],
            'nm_coa' => $s['nm_coa'],
            'debit' => 0,
            'credit' => 0,
            'endbal' => 0,
            'suppliers' => array(),
        );
    }
    $category[$s['id_coa']]['suppliers'][] = $s;

    $category[$s['id_coa']]['debit'] += $s['debit'];
    $category[$s['id_coa']]['credit'] += $s['credit'];
    $category[$s['id_coa']]['endbal'] += $s['endbal'];

    $total['debit'] += $s['debit'];
    $total['credit'] += $s['credit'];
    $total['endbal'] += $s['endbal'];
}

// Get master company
$company = $m->get_master_company();

// Close db connection
$con_new->close();

?>


<div class='box'>
    <div class='box-body'>
        <?php if(count($category)):?>
            <?php if(!isset($_GET['dest'])):?>
                <a class="btn btn-primary" href="?mod=<?=$_GET['mod']?>&dest=xls" target="_blank">Save to excel</a><br><br>
            <?php endif;?>
        <?php foreach ($category as $c):?>
        <table class="table table-condensed table-bordered" border="1">
            <thead>
            <tr>
                <th colspan="6"><?=$ch->format_coa($c['id_coa']).' '.$c['nm_coa']?></th>
            </tr>
            <tr>
                <th>NO</th>
                <th>ID SUPPLIER</th>
                <th>NAMA SUPPLIER</th>
                <th>DEBIT</th>
                <th>CREDIT</th>
                <th>SALDO AKHIR</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($c['suppliers'])): $no = 1;?>
            <?php foreach ($c['suppliers'] as $s):?>
            <tr>
                <td><?=$no++;?></td>
                <td><?=$s['kd'];?></td>
                <td><?=$s['nm'];?></td>
                <td class="text-right"><?=number_format($s['debit']);?></td>
                <td class="text-right"><?=number_format($s['credit']);?></td>
                <td class="text-right"><?=number_format($s['endbal']);?></td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr>
                <td colspan="13" class="text-center">--Tidak ada hutang--</td>
            </tr>
            <?php endif;?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th class="text-right"><?=number_format($c['debit']);?></th>
                    <th class="text-right"><?=number_format($c['credit']);?></th>
                    <th class="text-right"><?=number_format($c['endbal']);?></th>
                </tr>
            </tfoot>
        </table>
        <hr>
        <?php endforeach;?>
        <?php endif;?>
    </div>
</div>
