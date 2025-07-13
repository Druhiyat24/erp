<?php
// Session Checking
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

$user=$_SESSION['username'];
$sesi=$_SESSION['sesi'];
# CEK HAK AKSES KEMBALI
$akses = flookup("F_L_Umur_Utang","userpassword","username='$user'");
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
                id_supplier
                ,supplier_code
                ,supplier
                ,terms_of_pay
                ,date_journal
                ,due_date
                ,age
                ,reff_doc
                ,tipe
                ,SUM(debit) debit
                ,SUM(credit) credit
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
                id_supplier
                ,supplier_code
                ,supplier
                ,terms_of_pay
                ,date_journal
                ,due_date
                ,age
                ,reff_doc
                ,tipe
            ORDER BY
                id_supplier
                ,reff_doc
                ,date_journal
                ,tipe
        ";


        $result = $this
            ->query($sql)
            ->result();

        $aging = array(
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0
        );

        // Merge invoice with payment
        $invoices = array();
        foreach($result as $r){
            @$invoices[$r->id_supplier][$r->reff_doc] += $r->credit - $r->debit;
        }

        $suppliers = array();
        if(count($result)){
            foreach($result as $r){
                // Initialise supplier array with zero value
                if(!isset($suppliers[$r->id_supplier])){
                    $suppliers[$r->id_supplier] = array(
                        'id' => $r->id_supplier,
                        'kd' => $r->supplier_code,
                        'nm' => $r->supplier,
                        'top' => $r->terms_of_pay,
                        'invoice_count' => count($invoices[$r->id_supplier]),
                        'invoice_total' => 0,
                        'invoice_total_due' => 0,
                        'aging' => $aging
                    );
                }

                if($r->tipe == 0){
                    if($r->age <= 0){
                        $age = 0;
                    }else if($r->age >= 6){
                        $age = 6;
                    }else{
                        $age = (int) $r->age;
                    }

                    $suppliers[$r->id_supplier]['aging'][$age] += $invoices[$r->id_supplier][$r->reff_doc];
                    $suppliers[$r->id_supplier]['invoice_total'] += $invoices[$r->id_supplier][$r->reff_doc];
                    if($r->age >= 0) {
                        $suppliers[$r->id_supplier]['invoice_total_due'] += $invoices[$r->id_supplier][$r->reff_doc];
                    }
                }
            }
        }

        return $suppliers;
    }
}


// Instantiate model object
$m = new Model($con_new);
$ch = new Coa_helper();

// Instantiate helper object
$h = new Helper();

$suppliers = $m->get_report();

// Get master company
$company = $m->get_master_company();

// Close db connection
$con_new->close();
?>


<div class='box'>
    <div class='box-body'>
        <?php if(!isset($_GET['dest'])):?>
            <a class="btn btn-primary" href="?mod=<?=$_GET['mod']?>&dest=xls" target="_blank">Save to excel</a><br><br>
        <?php endif;?>
        <table class="table table-condensed table-bordered" border="1">
            <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th colspan="2">SUPPLIER</th>
                <th rowspan="2">TOP</th>
                <th rowspan="2">BASED ON</th>
                <th rowspan="2">TOTAL</th>
                <th rowspan="2">M-&gt;=6</th>
                <th rowspan="2">M-5</th>
                <th rowspan="2">M-4</th>
                <th rowspan="2">M-3</th>
                <th rowspan="2">M-2</th>
                <th rowspan="2">M-1</th>
                <th rowspan="2">BULAN BERJALAN</th>
                <th rowspan="2">AP DAYS</th>
            </tr>
            <tr>
                <th>KODE</th>
                <th>NAMA</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($suppliers)): $no = 1;?>
            <?php foreach ($suppliers as $s):?>
            <tr>
                <td><?=$no++;?></td>
                <td><?=$s['kd'];?></td>
                <td><?=$s['nm'];?></td>
                <td><?=$s['top'];?></td>
                <td>AKTUAL</td>
                <td class="text-right"><?=number_format($s['invoice_total']);?></td>
                <td class="text-right"><?=number_format($s['aging'][6]);?></td>
                <td class="text-right"><?=number_format($s['aging'][5]);?></td>
                <td class="text-right"><?=number_format($s['aging'][4]);?></td>
                <td class="text-right"><?=number_format($s['aging'][3]);?></td>
                <td class="text-right"><?=number_format($s['aging'][2]);?></td>
                <td class="text-right"><?=number_format($s['aging'][1]);?></td>
                <td class="text-right"><?=number_format($s['aging'][0]);?></td>
                <td class="text-right"><?=number_format(@(365/($s['invoice_total']/($s['invoice_total']/$s['invoice_count']))) ?: 0);?></td>
            </tr>
            <tr>
                <td colspan="4" class="warning">&nbsp;</td>
                <td>JT TEMPO</td>
                <td class="text-right"><?=number_format($s['invoice_total_due']);?></td>
                <td colspan="8" class="warning">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" class="warning">&nbsp;</td>
                <td>SELISIH</td>
                <td class="text-right"><?=number_format($s['invoice_total'] - $s['invoice_total_due']);?></td>
                <td colspan="8" class="warning">&nbsp;</td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr>
                <td colspan="13" class="text-center">--Tidak ada hutang--</td>
            </tr>
            <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
