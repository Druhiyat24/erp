<?php
	include "../../include/conn.php";
	include "fungsi.php";

	$id_gen=$_REQUEST['cri_item'];
	$sql = "SELECT a.nama_group,s.nama_sub_group,d.nama_type,
		e.nama_contents,f.nama_width,g.nama_length,h.nama_weight,
		i.nama_color,j.nama_desc,concat(if(a.kode_group='-','',a.kode_group),
		if(s.kode_sub_group='-','',s.kode_sub_group),if(d.kode_type='-','',d.kode_type),
		if(e.kode_contents='-','',e.kode_contents),if(f.kode_width='-','',f.kode_width),
		if(g.kode_length='-','',g.kode_length),
		if(h.kode_weight='-','',h.kode_weight),
		if(i.kode_color='-','',i.kode_color),
		if(j.kode_desc='-','',j.kode_desc)) gen_kode,
		concat(a.nama_group,
    if(s.nama_sub_group='-','',concat(' ',s.nama_sub_group)),
		if(d.nama_type='-','',concat(' ',d.nama_type)),
    if(e.nama_contents='-','',concat(' ',e.nama_contents)),
		if(f.nama_width='-','',concat(' ',f.nama_width)),
		if(g.nama_length='-','',concat(' ',g.nama_length)),
		if(h.nama_weight='-','',concat(' ',h.nama_weight)),
		if(i.nama_color='-','',concat(' ',i.nama_color)),
		if(j.nama_desc='-','',concat(' ',j.nama_desc))) itemdesc 
		FROM mastergroup a inner join mastersubgroup s on a.id=s.id_group
    inner join mastertype2 d on s.id=d.id_sub_group
    inner join mastercontents e on d.id=e.id_type
    inner join masterwidth f on e.id=f.id_contents 
    inner join masterlength g on f.id=g.id_width
    inner join masterweight h on g.id=h.id_length
    inner join mastercolor i on h.id=i.id_weight
    inner join masterdesc j on i.id=j.id_color
    where j.id='$id_gen'
    ORDER BY j.id DESC";
	$rs=mysql_fetch_array(mysql_query($sql));
	echo json_encode(array($rs['itemdesc'],$rs['nama_color'],$rs['nama_group'],$rs['nama_width'],$rs['gen_kode']));
?>