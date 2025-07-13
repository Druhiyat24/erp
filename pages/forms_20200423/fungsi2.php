<?PHP
set_time_limit(0);



function show_roll_dev($from,$to,$bts,$ket)
{	if (substr($ket,0,8)=="CSBD_BOM")
	{	$x=$from;
		$arrket=explode("|",$ket);
	 	$id_contents=$arrket[2];
	 	$id_jo=$arrket[1];
		$rulebom=$arrket[3];
	 	$from2 = $from - 1;
	 	if ($rulebom=="All Color All Size")
		{	$sql="select 'All Color All Size' tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' 
				limit 1";
		}
		else if ($rulebom=="All Color Range Size")
		{	$sql="select concat('All Color | ',size) tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' 
				group by size order by s.id limit $from2,$to";
		}
		else if ($rulebom=="Per Color All Size")
		{	$sql="select concat(color,' | All Size') tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color
				order by s.id limit $from2,$to";
		}
		else
		{	$sql="select concat(color,' | ',size) tampil 
				from jo_det_dev a inner join so_det_dev s 
				on a.id_so=s.id_so where id_jo='$id_jo' group by color,size
				order by s.id limit $from2,$to";
		}
		$rs=mysql_query($sql);
		while($data = mysql_fetch_array($rs))
		{ if ($x<=$to and $x<=$bts) 
			{ $type="text"; 
				$stra=" style='width: 150px; height: 26px;' readonly value='$data[tampil]' ";
				$stra2=" style='width: 150px; height: 26px;' ";
				echo "
				<td style='padding-bottom: 1em;'>
					<input type ='$type' $stra name='no_roll[$x]' id='rollajax' class='rollclass'>";
					if (substr($ket,0,8)=="CSBD_BOM")
					{	if (substr($ket,-1)=="P")
						{	$sql = "SELECT a.id_item isi,
		          concat(a.itemdesc,' ',
		          a.color,' ',a.size) tampil
		          FROM masteritem a inner join mastercf s on a.matclass=s.cfdesc
		          where a.mattype='C' and s.id='$id_contents'
		          ORDER BY a.id_item DESC";
		          #echo $sql;
		        }
		     		else
						{	$sql = "SELECT j.id isi,
		          concat(f.nama_width,' ',
		          g.nama_length,' ',h.nama_weight,' ',i.nama_color,' ',j.nama_desc) tampil
		          FROM mastergroup a inner join mastersubgroup s on a.id=s.id_group
		          inner join mastertype2 d on s.id=d.id_sub_group
		          inner join mastercontents e on d.id=e.id_type
		          inner join masterwidth f on e.id=f.id_contents 
		          inner join masterlength g on f.id=g.id_width
		          inner join masterweight h on g.id=h.id_length
		          inner join mastercolor i on h.id=i.id_weight
		          inner join masterdesc j on i.id=j.id_color
		          where e.id='$id_contents'
		          ORDER BY j.id DESC";
		     		}
						echo " <select class='form-control select2' $stra2  name ='jml_roll[$x]' id='jmlajax' class='jmlclass'>";
						IsiCombo($sql,'','Pilih Item');
						echo "</select>";
					}
				echo"
				</td>";
			}
			$x++;
		}
	}
	else
	{	for ($x = $from; $x <= $to; $x++)
		{ if ($x<=$bts) 
			{ $type="text"; 
				if ($ket=="R") 
				{$stra=" style='width: 30px; text-align: right;' readonly value='$x' ";
				 $stra2=" style='width: 40px; text-align: right;' ";
				} 
				else 
				{$stra=" style='width: 50px;' ";
				 $stra2=" style='width: 40px; text-align: right;' ";
				}
				echo "
				<td style='padding-bottom: 1em;'>
					<input type ='$type' $stra name='no_roll[$x]' id='rollajax' class='rollclass'> 
					<input type ='$type' $stra2 name ='jml_roll[$x]' id='jmlajax' class='jmlclass'> ";
					if ($ket=="S")
					{echo "<input type ='$type' style='width: 100px;' name ='barcode[$x]' id='barajax' class='barclass'>";}
				echo "
				</td>";
			}
		}
	}
};

?>
