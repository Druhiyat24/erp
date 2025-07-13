<?php
error_reporting(E_ERROR);
require_once "../forms/journal_interface.php";
error_reporting( E_ALL ^ E_DEPRECATED );

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
            SELECT * FROM
            fin_journal_h jh
            WHERE id_journal = '$id';
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


    public function get_cash_bank($id_journal)
    {
		$q = "SELECT v_codeterimakeluar,v_journaltype FROM
            fin_prosescashbank 
            WHERE v_idjournal = '$id_journal'
        ";
		//print_r ($this->query($q)->row());
        return $this->query($q)->row();
    }

}

// CONTROLLER BEGIN

$M = new Model($con_new);

$ch = new Coa_helper();
$company = $M->get_master_company();

$id = isset($_GET['id']) ? $_GET['id'] : '';

    if($id){
        $row = $M->get_journal($id);
			$headers = "";
			$masukkeluar = "";
			$objek = "";
		if($row->type_journal == "5" || $row->type_journal == "10" || $row->type_journal == "11" || $row->type_journal == "12"){
			//$cashbank = $M->get_cash_bank($row->id_journal);
			//print_r($cashbank->v_journaltype);
			if($row->type_journal == "5" ){
				$headers = "Bukti Cash Masuk";
			}else if($row->type_journal == "10"){
				$headers = "Bukti Cash Keluar";
			}else if($row->type_journal == "11"){
				$headers = "Bukti Bank masuk";
			}else if($row->type_journal == "12"){
				$headers = "Bukti Bank Keluar";
			}
			
			
/*			if($cashbank->v_codeterimakeluar == "P1"){
				$masukkeluar = "Masuk";
				
			if($cashank->v_journaltype == "C")	{
				$objek = 'Cash';
				
			}else {
				$objek = "Bank";
				
				}				
				
			}else {
				$masukkeluar = "Keluar";
				
			if($cashank->v_journaltype == "C")	{
				$objek = 'Cash';
				
			}else {
				$objek = "Bank";
				
				}				
			}
				
*/
			
			//echo "123";
		}
        $list = $M->get_journal_items($id);
        $t_debit = $t_credit = 0;
        if(count($list)){
            foreach($list as $l){
                $t_debit += $l->debit;
                $t_credit += $l->credit;
            }
        }
    }


// CONTROLLER END

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        html {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        html, body{
            font-family: sans-serif;
            width: 100%;
            height: 100%;
            margin:0;
            padding:0;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            margin:0;
            padding:0;
        }
        td,
        th {
            padding: 2px;
            margin:0;
        }
        .table {
            border-collapse: collapse !important;
            width: 100%;
            max-width: 100%;
            font-size: 10px;
        }
        .table td{
            background-color: #fff;
        }
        .table th {
            background-color: #eee;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        
		.myHeader{
			width:100%;
			height:10px;
			text-align:center;
			padding-bottom:20px;
			font-weight:bold;
		}
		
		
    </style>
    <title>Journal  Voucher</title>
</head>
<body>

<div class="myHeader">
<?=$headers?>

</div>
<table width="100%">
    <tr>
	
        <th style="text-align: left;vertical-align: top"><?=$row->company?></th>
        <td>&nbsp;</td>
        <td>
            <table border="1" width="100%">
                <tr>
                    <th colspan="2" style="text-align: center">JURNAL VOUCHER</th>
                </tr>
                <tr>
                    <th style="text-align: left">No Jurnal</th>
                    <td><?=$row->id_journal?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Tanggal</th>
                    <td><?=$row->date_journal?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Tipe Jurnal</th>
                    <td><?=Config::$journal_type[$row->type_journal]?></td>
                </tr>
                <tr>
                    <th style="text-align: left">No Referensi</th>
                    <td><?=$row->reff_doc?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>
<br>
<br>
<br>

<?php if($id):?>
    <table id="examplefix" class="display responsive" style="width:100%;font-size:12px;" border="1">
      <thead>
        <tr>
            <th>No. Akun</th>
            <th>Nama Chart of Account</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Mata Uang</th>
            <th>No WS/Cost Center</th>
            <th>Keterangan</th>
        </tr>
      </thead>
      <?php if(@count($list)):?>
      <tbody>
        <?php $no=1;?>
        <?php foreach($list as $l):?>
        <tr>
            <td><?=$ch->format_coa($l->id_coa)?></td>
            <td><?=$l->nm_coa?></td>
            <td class="text-right"><?=number_format($l->debit,2,'.',',')?></td>
            <td class="text-right"><?=number_format($l->credit,2,'.',',')?></td>
            <td><?=$l->curr?></td>
            <td><?=$l->nm_ws ? : $l->nm_costcenter?></td>
            <td><?=$l->description?></td>

        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
        <tfoot>
        <tr>
            <th colspan="2">TOTAL</th>
            <th class="text-right"><?=number_format($t_debit,2,',','.')?></th>
            <th class="text-right"><?=number_format($t_credit,2,',','.')?></th>
            <th colspan="3">&nbsp;</th>
        </tr>
        </tfoot>
    </table>

    <br>
    <br>
    <br>
    <br>


    <table width="60%">
        <tr>
            <td>
                Dipersiapkan Oleh:
                <br>
                <br>
                <br>
                <hr>
                Tanggal:
            </td>
            <td>&nbsp;&nbsp;&nbsp;</td>
            <td>
                Disetujui Oleh:
                <br>
                <br>
                <br>
                <hr>
                Tanggal:
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>
<?php endif;?>

</body>
</html>
<?php
// Store output into vars
$html = ob_get_clean();
//exit($html);
// Convert output into pdf
include("../../mpdf57/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML($html);
$mpdf->Output();
?>