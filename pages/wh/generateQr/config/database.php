<?php 
 
class database{
 
	var $host = "localhost";
	var $uname = "root";
	var $pass = "ERP@S19n4lB1t";
	var $db = "signalbit_erp";
    var $conn;
 
	function __construct()
    {
        $this->conn = mysqli_connect($this->host,$this->uname,$this->pass,$this->db);

        if (mysqli_connect_errno()){
            echo "Connection error : " . mysqli_connect_error();
        }
	}

    function getKapasitas($rak)
    {
        $sql = "select kode_rak, kapasitas from m_rak where cancel = 'N' and kode_rak = '".$rak."'";
        $data = $this->conn->query($sql);
		
        if ($data->num_rows > 0) {
            $result = $data->fetch_assoc();
        } else {
            $result = "Error";
        }

        return $result;
    }

    function getTotalBarang($rak)
    {
        $sql = "
            select 
                a.id_barcode, roll_qty, roll_qty - coalesce(qtyout,0) sisa, kode_rak, count(*) total, max(date_input)
            from 
                in_material_det	a
            left join 
                (select id_barcode, sum(qty) qtyout from out_material where cancel = 'N' group by id_barcode) b on a.id_barcode = b.id_barcode
            where 
                a.cancel = 'N' and
                kode_rak = '".$rak."' AND (roll_qty - coalesce(qtyout,0)) > 0
            group by 
                kode_rak
            ";
        $data = $this->conn->query($sql);
		
        if ($data->num_rows > 0) {
            $result = $data->fetch_assoc();
        } else {
            $result = "Error";
        }

        return $result['total'];
    }

    function getSpaceKosong($rak)
    {
        $sql = "
            select 
                a.id_barcode, a.kode_rak, (m_rak.kapasitas- count(*)) space_kosong
            from 
                in_material_det	a
            left join 
                (select id_barcode, sum(qty) qtyout from out_material where cancel = 'N' group by id_barcode) b on a.id_barcode = b.id_barcode
            left join
                m_rak on a.kode_rak = m_rak.kode_rak
            where 
                a.cancel = 'N' and
                a.kode_rak = '".$rak."' AND (roll_qty - coalesce(qtyout,0)) > 0
            group by 
                kode_rak
            ";
        $data = $this->conn->query($sql);
		
        if ($data->num_rows > 0) {
            $result = $data->fetch_assoc();
        } else {
            $result = "Error";
        }

        return $result['space_kosong'];
    }

    function getLatestUpdateDate($rak)
    {
        $sql = "
            select 
                max(date_input) latest_update
            from 
                in_material_det	a
            left join 
                (select id_barcode, sum(qty) qtyout from out_material where cancel = 'N' group by id_barcode) b on a.id_barcode = b.id_barcode
            where 
                a.cancel = 'N' and
                kode_rak = '".$rak."' AND (roll_qty - coalesce(qtyout,0)) > 0
            group by 
                kode_rak
            ";
        $data = $this->conn->query($sql);
		
        if ($data->num_rows > 0) {
            $result = $data->fetch_assoc();
        } else {
            $result = "Error";
        }

        return $result['latest_update'];
    }

    function getIsiRak($rak)
    {
        $sql = "
            select 
                db.*, kode_barang, nama_barang, job_order, c.unit, supplier
            from
                (
                    select 
                        kode_rak, id_in_material,a.id_barcode, roll_no, lot_no, roll_qty, coalesce(qtyout,0) used_roll, (roll_qty - coalesce(qtyout,0)) sisa, ((coalesce(qtyout,0)/roll_qty)*100) persentase
                    from 
                        in_material_det a
                    left join 
                        (select id_barcode,sum(qty) qtyout from out_material where cancel = 'N' group by id_barcode) b 
                    on 
                        a.id_barcode = b.id_barcode
                ) db            
            inner join 
                in_material c 
            on 
                db.id_in_material = c.id
            inner join 
                (select * from m_rak where kode_rak = '".$rak."' limit 1) m_rak 
            on 
                db.kode_rak = m_rak.kode_rak
            where 
                db.sisa > '0'
            ";
        $data = $this->conn->query($sql);
		
        if ($data->num_rows > 0) {
            while ($row = $data->fetch_assoc()) {
                $result[] = $row;
            }
        } else {
            $result = "Error";
        }

        return $result;
    }
} 
 
?>