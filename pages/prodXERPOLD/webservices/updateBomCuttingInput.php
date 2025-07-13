<?php

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];

$id_cost = $_GET['id_cost'];

/* 
print_r($data);
die();  */

class Proses{

   public function connect()
   {
      include __DIR__ .'/../../../include/conn.php';
      return $conn_li;
   }

   public function insert($id_cost)
   {
      $connect = $this->connect();
      
      $table = "SELECT MAS.* FROM (

         SELECT k.posno,k.id,k.id_item,a.nama_group,a.id id_group,s.nama_sub_group,k.color,k.size,
            
               if(nama_sub_group regexp 'BARCODE' or nama_sub_group regexp 'STICKER', concat(if(d.nama_type!='-',concat(d.nama_type,' '),''),
                  if(e.nama_contents!='-',concat(e.nama_contents,' '),''),
                  if(f.nama_width!='-',concat(f.nama_width,' '),''),
                  if(g.nama_length!='-',concat(g.nama_length,' '),''),
                  if(h.nama_weight!='-',concat(h.nama_weight,' '),''),
                  if(i.nama_color!='-',concat(i.nama_color,' '),''),'',
                  if(j.nama_desc!='-',concat(j.nama_desc,' '),''),
                  if(j.add_info!='-' and j.add_info!='',j.add_info,''),k.sku,' ',k.barcode), 
                  if(nama_type='SKU', concat(if(d.nama_type!='-',concat(d.nama_type,' '),''),
                  if(e.nama_contents!='-',concat(e.nama_contents,' '),''),
                  if(f.nama_width!='-',concat(f.nama_width,' '),''),
                  if(g.nama_length!='-',concat(g.nama_length,' '),''),
                  if(h.nama_weight!='-',concat(h.nama_weight,' '),''),
                  if(i.nama_color!='-',concat(i.nama_color,' '),''),'',
                  if(j.nama_desc!='-',concat(j.nama_desc,' '),''),
                  if(j.add_info!='-' and j.add_info!='',j.add_info,''),k.sku), concat(if(d.nama_type!='-',concat(d.nama_type,' '),''),
                  if(e.nama_contents!='-',concat(e.nama_contents,' '),''),
                  if(f.nama_width!='-',concat(f.nama_width,' '),''),
                  if(g.nama_length!='-',concat(g.nama_length,' '),''),
                  if(h.nama_weight!='-',concat(h.nama_weight,' '),''),
                  if(i.nama_color!='-',concat(i.nama_color,' '),''),'',
                  if(j.nama_desc!='-',j.nama_desc,''),
                  if(j.add_info!='-' and j.add_info!='',j.add_info,'')))
               ) item,
               k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,
      
               k.unit,urut,k.notes
      
         From bom_production k INNER JOIN masterdesc j on k.id_item=j.id
      
         INNER JOIN mastercolor i on i.id=j.id_color
      
         INNER JOIN masterweight h on h.id=i.id_weight
      
         INNER JOIN masterlength g on g.id=h.id_length
      
         INNER JOIN masterwidth f on f.id=g.id_width
      
         INNER JOIN mastercontents e on e.id=f.id_contents
      
         INNER JOIN mastertype2 d on d.id=e.id_type
      
         INNER JOIN mastersubgroup s on s.id=d.id_sub_group
      
         INNER JOIN mastergroup a on a.id=s.id_group 
      
         LEFT JOIN mastersize msz on k.size=msz.size
         
          
         
         INNER JOIN so_det sd ON sd.id=k.id_so_det
         
         INNER JOIN so so ON so.id=sd.id_so
         
         INNER JOIN act_costing ac ON ac.id=so.id_cost
         
         
      
         WHERE ac.id= '$id_cost' AND k.status='M'
      
         union all 
      
         SELECT k.posno,k.id,k.id_item,j.matclass nama_group,'4' id_group,concat(j.matclass,' ',j.goods_code) nama_sub_group,
      
               k.color,k.size,
      
               j.itemdesc item,k.qty qty_gmt,k.cons,round(k.qty*k.cons,2) qty_bom,
      
               k.unit,urut,k.notes
      
         From bom_production k INNER JOIN masteritem j on k.id_item=j.id_item 
      
         LEFT JOIN mastersize msz on k.size=msz.size
         
         
         
         INNER JOIN so_det sd ON sd.id=k.id_so_det
         
         INNER JOIN so so ON so.id=sd.id_so
         
         INNER JOIN act_costing ac ON ac.id=so.id_cost
         
         
      
         WHERE ac.id= '$id_cost' and k.status='P'
      
         ORDER BY posno,nama_group,color,urut,size
         
      )   MAS 
      
      WHERE MAS.nama_group LIKE '%FABRIC%'
      
      ORDER BY MAS.id_group ASC";

      

      while ($row = mysqli_fetch_assoc($table)) {

         $sql  ="INSERT INTO bom_production (posno,id_panel,id_jo,id_so_det,dest,status,
                     id_item,cons,unit,id_supplier,id_supplier2,notes,username,dateinput,rule_bom,cancel,
                     add_item,color,size,qty,sku,barcode,id_so)
                  VALUES (
                     '{$row['posno']}',
                     '{$row['id_panel']}',
                     '{$row['id_jo']}',
                     '{$row['id_so_det']}',
                     '{$row['dest']}',
                     '{$row['status']}',
                     '{$row['id_item']}',
                     '{$row['cons']}',
                     '{$row['unit']}',
                     '{$row['id_supplier']}',
                     '{$row['id_supplier2']}',
                     '{$row['notes']}',
                     '{$row['username']}',
                     '{$row['dateinput']}',
                     '{$row['rule_bom']}',
                     '{$row['cancel']}',
                     '{$row['add_item']}',
                     '{$row['color']}',
                     '{$row['size']}',
                     '{$row['qty']}',
                     '{$row['sku']}',
                     '{$row['barcode']}',
                     '{$row['id_so']}'
         )";			
		   $result = $connect->query($sql);

      }

      return $result;	
		
		$result = '{ "respon":"'.'200'.'", "message":"'.'OK'.'", "records":"0"}';
		print_r($result);	
		$connect->close();


   }


}

?>