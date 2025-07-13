<?php
// Load db connection
include_once '../../include/conn.php';
// Assets class (image, css, etc..)
class Assets{
    // Logo path
    public static $logo = '../../include/img-01.png';
    public static $sketch_patch = 'upload_files/costing/';
}
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
            die($err);
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

    /**
     * Get report data and transform into report friendly format
     * @param $do_id Delivevry Order ID
     * @return array of Job Order and Sales Order
     */
    public function get_report($do_id)
    {
        $sql = "
            SELECT 
                if(h.bppbno_int<>'',h.bppbno_int,h.bppbno) bppbno
                ,h.bppbdate
                ,h.styleno
                ,CONCAT(ms.itemname, ' ', ms.color, ' ', ms.size) as product
                ,h.id_item
                ,i.itemdesc
                ,h.qty
                ,h.unit
                ,h.id_supplier
                ,s.supplier
                ,s.alamat
                ,h.nomor_aju
                ,'(state)' as state
                ,'(note)' as note
            FROM
                bppb h
                LEFT JOIN mastersupplier s ON h.id_supplier = s.Id_Supplier
                LEFT JOIN masteritem i ON h.id_item = i.id_item
                LEFT JOIN masterstyle ms ON h.id_item = ms.id_item
            WHERE
                h.bppbno = '$do_id'
        ";

        $raw_result = $this
            ->query($sql)
            ->result();

        if(!count($raw_result)){
            return array(null, null, null);
        }

        $h = array();
        $d = array();
        $total = 0;
        foreach($raw_result as $r){
            if(!count($h)){
                $h = array(
                    'bppbno' => $r->bppbno,
                    'bppbdate' => $r->bppbdate,
                    'supplier' => $r->supplier,
                    'alamat' => $r->alamat,
                    'state' => $r->state,
                    'note' => $r->note,
                );
            }


            $d[] = array(
                'product' => $r->product,
                'itemdesc' => $r->itemdesc,
                'qty' => $r->qty,
                'unit' => $r->unit,
                'nomor_aju' => $r->nomor_aju,
            );

            $total += $r->qty;

        }



        return(array(
            json_decode(json_encode($h)),
            json_decode(json_encode($d)),
            json_decode(json_encode($total)),
        ));
    }
}

// Retrieve parameters
if(!isset($_GET['noid'])){
    exit('Missing DO Id parameter');
}
$id = $_GET['noid'];

// Instantiate model object
$m = new Model($con_new);

//echo '<pre>';print_r($m->get_report($id));exit();

// Get report data, assign into job order (header) and sales order(detail) vars
list($h, $d, $total) = $m->get_report($id);

// Get master company
$company = $m->get_master_company();

// If job order is null, then it is non existent
if(is_null($h)){
    exit('Delivery Order Not Found');
}

// Close db connection
$con_new->close();

// Begin Capturing Output
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
                font-size: 14px;
            }
            .table td{
                background-color: #fff;
                font-weight: normal;
                padding:10px;
            }
            .table th {
                background-color: #fff;
                font-weight: bold;
                padding:10px;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #ddd !important;
            }
            #table-h th, #table-d th{
                border-bottom: 2px solid #000;
            }
            #table-h td, #table-d td{
                border-top: 1px solid #000;
            }
            #table-h th, #table-h td{
                text-align: left;
            }
            #table-sign td{
                height: 100px;
            }
            .text-right {
                text-align: right !important;
            }
            .text-center {
                text-align: center !important;
            }
            .text-left{
                text-align: left !important;
            }
        </style>
        <title>Worksheet</title>
    </head>
    <body>
    <table class="table" style="border-bottom: 2px solid #000000; margin-bottom:5px;">
        <tr>
            <td><img src="<?=Assets::$logo?>" width="100px" height="70px"> </td>
            <td style="text-align: right;vertical-align: bottom;font-size: 16px;"><?=strtoupper($company->company)?></td>
        </tr>
    </table>

    <div id="address">
        <strong>Customer Address: </strong><br>
        <?=$h->supplier?><br>
        <?=$h->alamat?>
    </div>

    <h2>Delilvery Orders: <br><?=$h->bppbno?></h2>

    <table id="table-h" class="table" >
        <tr>
            <th>Order (Origin)</th>
            <th>State</th>
            <th>Date of Transfer</th>
        </tr>
        <tr>
            <td><?=$h->bppbno?></td>
            <td><?=$h->state?></td>
            <td><?=$h->bppbdate?></td>
        </tr>
    </table>

    <br>

    <table id="table-d" class="table table-condensed">
        <tr>
            <th>Product</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Source</th>
        </tr>
        <?php if(count((array) $d)):?>
        <?php foreach($d as $_d):?>
        <tr>
            <td><?=$_d->product?></td>
            <td><?=$_d->itemdesc?></td>
            <td><?=$_d->qty.' '.$_d->unit?></td>
            <td><?=$_d->nomor_aju?></td>
        </tr>
        <?php endforeach;?>
            <tr>
                <td></td>
                <td></td>
                <td><?=$total?></td>
                <td></td>
            </tr>
        <?php endif;?>
    </table>

    <br>

    <div id="note">
        <strong>Note: </strong><br>
        <?=$h->note?>
    </div>

    <br>

    <table id="table-sign" class="table table-bordered">
        <tr>
            <th>Made By</th>
            <th>Chief</th>
            <th>Director</th>
            <th>Received By</th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

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