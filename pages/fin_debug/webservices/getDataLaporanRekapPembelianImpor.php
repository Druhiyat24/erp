<?php 
	function d_from($from){
		$d_from = explode("/",$from."/01");
		$d_from = $d_from[1]."-".$d_from[0]."-".$d_from[2];
		return $d_from;
	}
	function d_to($to){
		$d_to = explode("/",$to."/01");
		$d_to = $d_to[1]."-".$d_to[0]."-".$d_to[2];
		$d_to = date("Y-m-d", strtotime("1 month",strtotime($d_to)));
		$d_to = date("Y-m-d", strtotime("-1 days",strtotime($d_to)));
		return $d_to;
	}



include __DIR__ .'/../../../include/conn.php';
## Read value
$data = $_GET;
$d_from = d_from($data['from']);
$d_to   = d_to($data['to']);
/* 
print_r($_POST);
die();  */
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$table = "(

SELECT FINAL.id
,		FINAL.nama_group
,		FINAL.curr
,		FINAL.price_USD
,		FINAL.dpp_idr
,		FINAL.ppn
,		'N/A' bm
,		'N/A' pph22
,		(FINAL.dpp_idr+FINAL.ppn)total_idr
,		FINAL.date_journal

FROM (SELECT YY.id id 																						#START NON-GROUP 
,		YY.nama_group nama_group
,		YY.curr curr
,		YY.dpp_idr dpp_idr
,		YY.price_USD price_USD
,		YY.ppn ppn
,		YY.date_journal

FROM (SELECT ITEM.id
,		ITEM.nama_group
,		B.curr
,		SUM(B.total_idr)dpp_idr
,		SUM(B.total_usd)price_USD
,		SUM(B.ppn)ppn
,		B.date_journal
FROM (SELECT a.id, a.kode_group, a.nama_group, mi.id_item
FROM mastergroup a 
INNER JOIN mastersubgroup b ON b.id_group=a.id
INNER JOIN mastertype2 c ON c.id_sub_group=b.id
INNER JOIN mastercontents d ON d.id_type=c.id
INNER JOIN masterwidth e ON e.id_contents=d.id
INNER JOIN masterlength f ON f.id_width=e.id
INNER JOIN masterweight g ON g.id_length=f.id
INNER JOIN mastercolor h ON h.id_weight=g.id
INNER JOIN masterdesc i ON i.id_color=h.id
INNER JOIN masteritem mi ON mi.id_gen=i.id
WHERE mi.non_aktif='N'
ORDER BY a.id ASC )ITEM

INNER JOIN (
SELECT b.id_item
,		b.pono
, 		b.bpbno
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		b.curr
,		fjh.date_journal
FROM bpb b
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier 
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y' AND b.curr='IDR'
AND LEFT(b.bpbno,1) in ('A','F','B') 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.bpbno ASC 
)B ON B.id_item = ITEM.id_item
GROUP BY ITEM.id #BAHAN BAKU

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='EMB','13','13')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='EMB'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I' 
)B
GROUP BY B.mattype #EMBROIDERY

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='LSC','14','14')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='LSC' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #LASER CUTTING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='CMT','15','15')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='CMT' OR mi.matclass ='CMT'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #CMT

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='WSH','16','16')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='WSH'  
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #WASHING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='QLT','17','17')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='QLT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #QUILTING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='HTS','18','18')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='HTS' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #HTS

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='BND','19','19')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='BND' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #BND

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='PRT','20','20')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND SUBSTRING(mi.goods_code,1,3)='PRT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
)B
GROUP BY B.mattype #PRT

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='-','GENERAL','-') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='N','21','21')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND mi.mattype='N'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #GENERAL

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='-','MESIN','MESIN') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='M','22','22')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND mi.mattype='M'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #MESIN

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='MAJUN','MAJUN','MAJUN') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='S','23','23')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='IDR'
AND mi.mattype='S' OR SUBSTRING(mi.goods_code,1,3)='MJN' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #SCRAP / MAJUN


) YY GROUP BY YY.id , YY.curr																	# END NON-GROUP 

UNION ALL

SELECT Y.id id 																				#START GROUP 
,		Y.nama_group nama_group
,		Y.curr curr
,		Y.dpp_idr dpp_idr
,		Y.price_USD price_USD
,		Y.ppn ppn
,		Y.date_journal
FROM (SELECT ITEM.id
,		ITEM.nama_group
,		B.curr
,		SUM(B.total_idr)dpp_idr
,		SUM(B.total_usd)price_USD
,		SUM(B.ppn)ppn
,		B.date_journal
FROM (SELECT a.id, a.kode_group, a.nama_group, mi.id_item
FROM mastergroup a 
INNER JOIN mastersubgroup b ON b.id_group=a.id
INNER JOIN mastertype2 c ON c.id_sub_group=b.id
INNER JOIN mastercontents d ON d.id_type=c.id
INNER JOIN masterwidth e ON e.id_contents=d.id
INNER JOIN masterlength f ON f.id_width=e.id
INNER JOIN masterweight g ON g.id_length=f.id
INNER JOIN mastercolor h ON h.id_weight=g.id
INNER JOIN masterdesc i ON i.id_color=h.id
INNER JOIN masteritem mi ON mi.id_gen=i.id
WHERE mi.non_aktif='N'
ORDER BY a.id ASC )ITEM

INNER JOIN (
SELECT b.id_item
,		b.pono
, 		b.bpbno
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		b.curr
,		fjh.date_journal
FROM bpb b
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier 
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y' AND b.curr='USD'
AND LEFT(b.bpbno,1) in ('A','F','B') 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.bpbno ASC , b.qty ASC 
)B ON B.id_item = ITEM.id_item
GROUP BY ITEM.id #BAHAN BAKU

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='EMB','13','13')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='EMB' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #EMBROIDERY

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='LSC','14','14')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='LSC' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #LASER CUTTING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='CMT','15','15')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='CMT' OR mi.matclass ='CMT'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #CMT

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='WSH','16','16')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='WSH' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #WASHING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='QLT','17','17')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='QLT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #QUILTING

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='HTS','18','18')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='HTS' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #HTS

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='BND','19','19')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='BND' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #BND

UNION ALL 

SELECT B.mattype id
,	B.matclass nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(SUBSTRING(mi.goods_code,1,3)='PRT','20','20')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='17' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND SUBSTRING(mi.goods_code,1,3)='PRT' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #PRT

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='-','GENERAL','-') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='N','21','21')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND mi.mattype='N'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #GENERAL

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='-','MESIN','MESIN') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='M','22','22')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND mi.mattype='M'
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #MESIN

UNION ALL 

SELECT B.mattype id
,	if(B.matclass='MAJUN','MAJUN','MAJUN') nama_group
,	B.curr
,	SUM(B.total_idr)dpp_idr
,	SUM(B.total_usd)price_USD
,	SUM(B.ppn)ppn
,	B.date_journal
FROM (
SELECT b.id_item
,		b.pono
, 		b.bpbno_int
,		b.qty
,		b.price
,		if(b.curr='USD',(b.qty*b.price),'0')total_usd
,		if(b.curr='USD',(b.qty*b.price*mr.rate),(b.qty*b.price))total_idr
, 		if(poh.tax='0' OR poh.tax IS NULL OR poh.tax = '','0',if(b.curr='USD',(0.1*(b.qty*b.price)*mr.rate),(0.1*(b.qty*b.price))))ppn
, 		b.confirm 
,		if(mi.mattype='S','23','23')mattype
,		mi.matclass
,		b.curr
,		fjh.date_journal
,		mi.goods_code
FROM bpb b
LEFT JOIN masteritem mi on mi.id_item=b.id_item
LEFT JOIN fin_journal_h fjh ON fjh.reff_doc=b.bpbno_int
LEFT JOIN mastersupplier ms ON ms.Id_Supplier=b.Id_Supplier  
LEFT JOIN masterrate mr ON mr.tanggal=b.bpbdate
LEFT JOIN po_header poh ON poh.pono=b.pono
WHERE fjh.type_journal='2' AND fjh.fg_post='2' AND b.confirm = 'Y'  AND b.curr='USD'
AND mi.mattype='S' OR SUBSTRING(mi.goods_code,1,3)='MJN' 
AND fjh.date_journal >= '$d_from' AND fjh.date_journal <= '$d_to'
AND ms.area='I'
ORDER BY  b.qty ASC 
)B
GROUP BY B.mattype #SCRAP / MAJUN


) Y GROUP BY Y.id, Y.curr																			# END GROUP 

)FINAL 

GROUP BY FINAL.id, FINAL.curr ORDER BY FINAL.id ASC 




)X";


## Search 
$searchQuery = " ";
if($searchValue != ''){
$searchQuery = " AND (

  X.matclass      	=  LIKE'%".$searchValue."%'
	X.curr    =  LIKE'%".$searchValue."%'
	X.price_USD     =  LIKE'%".$searchValue."%'
	X.dpp_idr     	=  LIKE'%".$searchValue."%'
	X.bm     		=  LIKE'%".$searchValue."%'
	X.pph22 	=  LIKE'%".$searchValue."%'	
	X.ppn     		=  LIKE'%".$searchValue."%'
	X.total_idr 	=  LIKE'%".$searchValue."%'
	
)
";
}

## Total number of records without filtering
$sel = mysqli_query($conn_li,"select count(*) allcount from $table");
$records = mysqli_fetch_assoc($sel); 

$totalRecords = $records['allcount'];

## Total number of record with filtering
$sel = mysqli_query($conn_li,"select count(*)  allcount from $table WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
## Fetch records
$colomn = "		 
				X.nama_group     
				,X.curr
				,X.price_USD       
				,X.dpp_idr           
				,X.bm        
				,X.pph22        
				,X.ppn        
				,X.total_idr        
				";

$empQuery = "select $colomn  from $table WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($conn_li, $empQuery);
$data = array();
// echo $empQuery;
$subtotal_dpp_idr = 0;
$subtotal_price_USD = 0;
$subtotal_ppn = 0;
$subtotal_total_idr = 0;

while ($row = mysqli_fetch_assoc($empRecords)) {
//echo $row['n_post'];
$subtotal_dpp_idr = $row['subtotal_dpp_idr'];
$subtotal_price_USD = $row['subtotal_price_USD'];
$subtotal_ppn = $row['subtotal_ppn'];
$subtotal_total_idr = $row['subtotal_total_idr'];

   $data[] = array(
"nama_group"=>htmlspecialchars($row['nama_group']),
"curr"=>htmlspecialchars($row['curr']),
"price_USD"=>htmlspecialchars(number_format((float)$row['price_USD'], 2, '.', ',')),
"dpp_idr"=>htmlspecialchars(number_format((float)$row['dpp_idr'], 2, '.', ',')),
"bm"=>htmlspecialchars($row['bm']),
"pph22"=>htmlspecialchars($row['pph22']),
"ppn"=>htmlspecialchars(number_format((float)$row['ppn'], 2, '.', ',')),
"total_idr"=>htmlspecialchars(number_format((float)$row['total_idr'], 2, '.', ','))

   );

//    $data[] = array(
// "nama_group"=>htmlspecialchars("TOTAL"),
// "curr"=>htmlspecialchars(""),
// "price_USD"=>htmlspecialchars(number_format((float)$subtotal_price_USD, 2, '.', ',')),
// "dpp_idr"=>htmlspecialchars(number_format((float)$subtotal_dpp_idr, 2, '.', ',')),
// "bm"=>htmlspecialchars("N/A"),
// "pph22"=>htmlspecialchars("N/A"),
// "ppn"=>htmlspecialchars(number_format((float)$subtotal_ppn, 2, '.', ',')),
// "total_idr"=>htmlspecialchars(number_format((float)$subtotal_total_idr, 2, '.', ',')),

//    );

}
## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecordwithFilter,
  "iTotalDisplayRecords" => $totalRecords,
  "aaData" => $data
);
echo json_encode($response);
?>

