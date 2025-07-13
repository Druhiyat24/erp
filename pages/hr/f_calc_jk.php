<?php
function calc_jk($nik,$tgl,$hari,$Jam_Msk,$Jam_Plg,$user)
{	$rsSet=mysql_fetch_array(mysql_query("select * from hr_masterwaktu where hari='$hari'"));
	$bts_jk = $rsSet['jamkerja'];
	$bts_in = $rsSet['waktuin'];
	$bts_ist1 = $rsSet['break1'];
	$dur_ist1 = $rsSet['durasi1'];
	
	if (strtotime($Jam_Msk)<strtotime($bts_in)) { $Jam_Msk = $bts_in; }
	if (strtotime($Jam_Plg)>strtotime($bts_ist1)) { $krg_ist1 = $dur_ist1; } else { $krg_ist1=0; }
	
	$Jam_1 = substr($Jam_Msk, 3, 2);
  $Jam_2 = substr($Jam_Msk, 0, 2);
  If ($Jam_1 < 25)
	{	$Jam_1 = "00";
	  $Jam_Msk = $Jam_2.":".$Jam_1;
	  $Jam_Msk = ft($Jam_Msk);
	}
  Else If ($Jam_1 >= 25 And $Jam_1 <= 54)
  {	$Jam_1 = "30";
		$Jam_Msk = $Jam_2.":".$Jam_1;
		$Jam_Msk = ft($Jam_Msk);
  }
  Else If ($Jam_1 >= 55)
  {	$Jam_1 = "00";
  	$Jam_2 = $Jam_2 + 1;
    $Jam_Msk = $Jam_2.":".$Jam_1;
    $Jam_Msk = ft($Jam_Msk);
  }
  
  $Jam_1 = substr($Jam_Plg, 3, 2);
  $Jam_2 = substr($Jam_Plg, 0, 2);
  If ($Jam_1 < 25)
  { $Jam_1 = "00";
    $Jam_Plg = $Jam_2.":".$Jam_1;
    $Jam_Plg = ft($Jam_Plg);
  }
  Else If ($Jam_1 >= 25 And $Jam_1 <= 54)
  { $Jam_1 = "30";
    $Jam_Plg = $Jam_2.":".$Jam_1;
    $Jam_Plg = ft($Jam_Plg);
  }
  Else If ($Jam_1 >= 55)
  { $Jam_1 = "00";
  	$Jam_2 = $Jam_2 + 1;
    $Jam_Plg = $Jam_2.":".$Jam_1;
    $Jam_Plg = ft($Jam_Plg);
  }
  
  $start_date = new DateTime($Jam_Msk);
	$since_start = $start_date->diff(new DateTime($Jam_Plg));
	
	$h_jk = $since_start->h;
	$m_jk = $since_start->i;
	if ($m_jk>=30) { $h_jk = $h_jk + 0.5; }
	$h_jk = $h_jk - $krg_ist1;
	#echo $bts_jk;
	if ($h_jk>$bts_jk)
	{	$h_ot = $h_jk - $bts_jk; 
		$h_jk = $bts_jk;
	}
	else
	{	$h_ot = 0; }
	if ($h_ot>0)
	{	if ($h_ot==0.5)
		{	$ot1 = 0.5;	}
		else
		{	$ot1 = 1;	}
		$pot1 = $ot1 * 1.5;
		$ot2 = $h_ot - $ot1;
		$pot2 = $ot2 * 2;
		$tpot = $pot1 + $pot2;
	}
	else
	{ $tpot = 0; }
	$sqlout="update hr_timecard set workhour='$h_jk',overtime='$h_ot',
		payovertime='$tpot' where empno='$nik' and 
		workdate='$tgl'";
	insert_log($sqlout,$user);
};
?>