<?php

function sql_balance($id_cost){
	$sql ="
		SELECT 		 DCO_H.id_dc_out_header
					,ACT.kpno
					,SO.so_no
					,SOD.id id_so_det
					,SO.buyerno
					,DCO_H.id_cost
					,MAX(SOD.dest)dest
					,MAX(SOD.color)color
					,MAX(SOD.size)size
					,SUM(SOD.qty)qty_so
					,MAX(SOD.unit)unit
					,SUM(ifnull(DCO_D.qty ,0))qty_dc_out
					,ifnull(MZ.urut,9999)urut
					/*sewing in*/
					,ifnull(SEI_D.tot_qty_sew_in,0)tot_qty_sew_in
					,(SUM(ifnull(DCO_D.qty ,0)) - ifnull(SEI_D.tot_qty_sew_in,0))balance_qty_sew_in
					/*sewing out*/
					,ifnull(SEO_D.tot_qty_sew_out,0)tot_qty_sew_out
					,(ifnull(SEI_D.tot_qty_sew_in,0) -  ifnull(SEO_D.tot_qty_sew_out,0) )balance_qty_sew_out
					/*sewing qc input*/
					,ifnull(SQI_D.tot_qty_sew_qc_in,0)tot_qty_sew_qc_in
					,(ifnull(SEO_D.tot_qty_sew_out,0) -  ifnull(SQI_D.tot_qty_sew_qc_in,0) 
					)balance_qty_sew_qc_in
					/*sewing qc output*/
					,ifnull(SQO_D.tot_qty_sew_qc_out,0)tot_qty_sew_qc_out
					,(ifnull(SQI_D.tot_qty_sew_qc_in,0) -  ifnull(SQO_D.tot_qty_sew_qc_out,0))balance_qty_sew_qc_out 	
					/*steam output*/
					,ifnull(ST_D.tot_qty_steam_out,0)tot_qty_steam_out
					,(ifnull(SQO_D.tot_qty_sew_qc_out,0) -  ifnull(ST_D.tot_qty_steam_out,0))balance_steam_output		


					/*qc final input*/
					,ifnull(QCFI_D.tot_qty_qc_final_in,0)tot_qty_qc_final_in
					,(ifnull(ST_D.tot_qty_steam_out,0) -  ifnull(QCFI_D.tot_qty_qc_final_in,0))balance_qc_final_input	



					/*qc final output*/
					,ifnull(QCFO_D.tot_qty_qc_final_out,0)tot_qty_qc_final_out
					,(ifnull(QCFI_D.tot_qty_qc_final_in,0) -  ifnull(QCFO_D.tot_qty_qc_final_out,0))balance_qc_final_output	



					/*packing*/
					,ifnull(P_D.tot_qty_packing,0)tot_qty_packing
					,(ifnull(QCFO_D.tot_qty_qc_final_out,0) -  ifnull(P_D.tot_qty_packing,0))balance_packing	


					/*metal detector*/
					,ifnull(MED_P.tot_qty_mat_dec,0)tot_qty_mat_dec
					,(ifnull(P_D.tot_qty_packing,0) -  ifnull(MED_P.tot_qty_mat_dec,0))balance_met_det


					/*qc audit*/
					,ifnull(QCA_D.tot_qty_qc_audit,0)tot_qty_qc_audit
					,(ifnull(MED_P.tot_qty_mat_dec,0) -  ifnull(QCA_D.tot_qty_qc_audit,0))balance_qc_audit



					/*finish good*/
					,ifnull(FG_D.tot_qty_fg,0)tot_qty_fg
					,(ifnull(QCA_D.tot_qty_qc_audit,0) -  ifnull(FG_D.tot_qty_fg,0))balance_fg


					/*shipping*/
					,ifnull(SHP_D.tot_shp,0)tot_shp
					,(ifnull(FG_D.tot_qty_fg,0) -  ifnull(SHP_D.tot_shp,0))balance_shp

					
					FROM prod_dc_out_header DCO_H
						INNER JOIN dc_out DCO_D ON DCO_D.id_dc_out_header = DCO_H.id_dc_out_header
						INNER JOIN so_det SOD ON SOD.id = DCO_D.id_so_det
						INNER JOIN so SO ON SO.id = SOD.id_so
						INNER JOIN act_costing ACT ON SO.id_cost = ACT.id
						LEFT  JOIN mastersize MZ ON MZ.size = SOD.size
						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_sew_in,id_so_det FROM sew_in  GROUP BY id_so_det )SEI_D ON SEI_D.id_so_det = DCO_D.id_so_det
						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_sew_out,id_so_det FROM sew_out  GROUP BY id_so_det )SEO_D ON SEI_D.id_so_det = SEO_D.id_so_det
						LEFT JOIN(SELECT SUM(ifnull(qty_good,0))tot_qty_sew_qc_in,id_so_det FROM prod_qc_in_detail  GROUP BY id_so_det )SQI_D ON SQI_D.id_so_det = SEO_D.id_so_det	
						LEFT JOIN(SELECT SUM(ifnull(qty,0) )tot_qty_sew_qc_out,id_so_det FROM qc_out  GROUP BY id_so_det )SQO_D ON SQO_D.id_so_det = SQI_D.id_so_det
						#LEFT JOIN(SELECT SUM(ifnull(qty,0) - ifnull(rpr,0))tot_qty_sew_qc_out,id_so_det FROM qc_out  GROUP BY id_so_det )SQO_D ON SQO_D.id_so_det = SQI_D.id_so_det	
						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_steam_out,id_so_det FROM steam_out  GROUP BY id_so_det )ST_D ON ST_D.id_so_det = SQO_D.id_so_det		



						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_qc_final_in,id_so_det FROM prod_qc_final_in_detail  GROUP BY id_so_det )QCFI_D ON QCFI_D.id_so_det = ST_D.id_so_det		

						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_qc_final_out,id_so_det FROM 
						#LEFT JOIN(SELECT SUM(ifnull(qty,0) - ifnull(rpr,0))tot_qty_qc_final_out,id_so_det FROM prod_qc_final_out_detail  GROUP BY id_so_det )QCFO_D ON QCFO_D.id_so_det = QCFI_D.id_so_det	




						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_packing,id_so_det FROM prod_finishing_packing_detail  GROUP BY id_so_det )P_D ON P_D.id_so_det = QCFO_D.id_so_det	



						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_mat_dec,id_so_det FROM prod_finishing_metal_detector_detail  GROUP BY id_so_det )MED_P ON MED_P.id_so_det = P_D.id_so_det	

						LEFT JOIN(SELECT SUM(ifnull(qty,0) )tot_qty_qc_audit,id_so_det FROM prod_finishing_qc_audit_detail  GROUP BY id_so_det )QCA_D ON QCA_D.id_so_det = MED_P.id_so_det

						#LEFT JOIN(SELECT SUM(ifnull(qty,0) - ifnull(rpr,0))tot_qty_qc_audit,id_so_det FROM prod_finishing_qc_audit_detail  GROUP BY id_so_det )QCA_D ON QCA_D.id_so_det = MED_P.id_so_det
						
						

						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_qty_fg,id_so_det FROM prod_finishing_fg_detail  GROUP BY id_so_det )FG_D ON FG_D.id_so_det = QCA_D.id_so_det	



						LEFT JOIN(SELECT SUM(ifnull(qty,0))tot_shp,id_so_det FROM prod_finishing_shp_detail  GROUP BY id_so_det )SHP_D ON SHP_D.id_so_det = FG_D.id_so_det							
						

					WHERE DCO_H.id_cost = '{$id_cost}'		
					GROUP BY DCO_D.id_so_det
						
	";
	return $sql;
	
}



function sql_balance_from_so($id_cost){
	if($id_cost == "REPORT"){
		$sql_where ="";
		$group_by  ="GROUP BY ACT.id,SOD.id";
	}else{
		$sql_where = "AND ACT.id = '{$id_cost}'";
		$group_by  ="GROUP BY SOD.id";
		
	}
	
	$sql ="
					SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.id_buyer,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no, 	
			ifnull(DC_SET.tot_qty_dc_set,0)tot_qty_dc_set,
			/*dc set mut*/
			ifnull(MUT_DC_SET.tot_qty_mut_dc_set, 0)tot_qty_mut_dc_set,		
			(
				ifnull(DC_SET.tot_qty_dc_set,0) - ifnull(MUT_DC_SET.tot_qty_mut_dc_set, 0)
			)balance_mut_dc_set,
			/*sewing in*/
			
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(MUT_DC_SET.tot_qty_mut_dc_set, 0)) - ifnull(SEI_D.tot_qty_sew_in, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_in, 	
			/*sewing out*/
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_out,
			
			
			/*sewing qc input*/
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_qc_in,
			
			
			/*sewing qc output*/
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_qc_out,
			
			/*mutation qc sewing output*/
			ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0)tot_qty_mut_sew_qc_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_mut_sew_qc_out,
			

			/*steam input*/			
			ifnull(ST_IN_D.tot_qty_steam_in, 0)tot_qty_steam_in,
			(
				ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0) - ifnull(ST_IN_D.tot_qty_steam_in, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_steam_input, 	

			
			/*steam output*/			
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(ST_IN_D.tot_qty_steam_in, 0) - ifnull(ST_D.tot_qty_steam_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_steam_output, 	
			
			/*qc final input*/
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qc_final_input,
			
			
			/*qc final output*/
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(QCFO_D.tot_qty_ds, 0) + ifnull(REJ_QOT_D.tot_qty_rej_qot,0)
			)
			balance_qc_final_output,
			

			/*qotiti output*/
			ifnull(QOT_D.tot_qty_qotiti, 0)tot_qty_qotiti,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(QOT_D.tot_qty_qotiti, 0)
			)
			balance_qotiti,			
			

			/*mutation qc output final*/
			ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0)tot_qty_mut_qc_fin_out,
			(
				ifnull(QOT_D.tot_qty_qotiti, 0) - ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0)
			)
			balance_mut_qotiti,		


			
			/*packing*/
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing,
			
			
			/*metal detector*/
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det,


			/*qc audit*/
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit,


			/*finish good*/
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg,


			/*shipping*/
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_dc_set, 0))tot_qty_dc_set,
						id_so_det 
					FROM
						prod_dc_set_detail 
					GROUP BY
						id_so_det 
				)
				DC_SET 
				ON DC_SET.id_so_det = SOD.id 				
				
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mut_dc_set,
						id_so_det 
					FROM
						prod_mut_dc_set_detail 
					GROUP BY
						id_so_det 
				)
				MUT_DC_SET 
				ON MUT_DC_SET.id_so_det = DC_SET.id_so_det 				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = MUT_DC_SET.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_sew_qc_out,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_sew_qc_out,
						id_so_det 
					FROM
						prod_rej_qc_sew_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QC_OUT_D 
				ON REJ_QC_OUT_D.id_so_det = SQO_D.id_so_det

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_mut_sew_qc_out,
						id_so_det 
					FROM
						prod_mut_sew_detail 
					GROUP BY
						id_so_det 
				)
				MUT_SQO_D 
				ON MUT_SQO_D.id_so_det = SQO_D.id_so_det

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_in,
						id_so_det 
					FROM
						prod_sew_steam_in_detail 
					GROUP BY
						id_so_det 
				)
				ST_IN_D 
				ON ST_IN_D.id_so_det = MUT_SQO_D.id_so_det 	

	
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_qc_final_out,
						SUM(ifnull(qty_ds, 0) )tot_qty_ds,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
				
				
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_qc_fin_out,
						id_so_det 
					FROM
						prod_rej_qc_fin_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QC_FIN_OUT_D 
				ON REJ_QC_FIN_OUT_D.id_so_det = QCFO_D.id_so_det				
				

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qotiti,
						id_so_det 
					FROM
						prod_qotiti_out_detail 
					GROUP BY
						id_so_det 
				)
				QOT_D 
				ON QOT_D.id_so_det = QCFO_D.id_so_det 


			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_mut_qc_fin_out,
						id_so_det 
					FROM
						prod_mut_qc_fin_detail 
					GROUP BY
						id_so_det 
				)
				MUT_QC_FIN_D 
				ON MUT_QC_FIN_D.id_so_det = QOT_D.id_so_det


			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_qot,
						id_so_det 
					FROM
						prod_reject_qot_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QOT_D 
				ON REJ_QOT_D.id_so_det = QOT_D.id_so_det	

				
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_qc_audit,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE 1=1 AND SOD.cancel = 'N'
/*			AND SOD.id IN(SELECT X.id_so_det FROM(
 SELECT A.id_dc_set,B.id_so_det FROM prod_dc_set A
	INNER JOIN prod_dc_set_detail B
	ON A. id_dc_set = B.id_dc_set
	WHERE A.id_cost = '{$id_cost}')X GROUP BY X.id_so_det)
*/	
			{$sql_where} 
			{$group_by}
									
	";
/* 	echo $sql;
	die(); */
	return $sql;
	
}


function sql_balance_from_so_sod($id_cost){
	$sql ="
					SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.id_buyer,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_in, 	
			/*sewing out*/
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_out,
			
			
			/*sewing qc input*/
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) + ifnull(REJ_QC_OUT_D.tot_qty_rej_sew_qc_out,0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_qc_in,
			
			
			/*sewing qc output*/
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_sew_qc_out,
			
			/*mutation qc sewing output*/
			ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0)tot_qty_mut_sew_qc_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qty_mut_sew_qc_out,
			

			/*steam input*/			
			ifnull(ST_IN_D.tot_qty_steam_in, 0)tot_qty_steam_in,
			(
				ifnull(MUT_SQO_D.tot_qty_mut_sew_qc_out, 0) - ifnull(ST_IN_D.tot_qty_steam_in, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_steam_input, 	

			
			/*steam output*/			
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(ST_IN_D.tot_qty_steam_in, 0) - ifnull(ST_D.tot_qty_steam_out, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_steam_output, 	
			
			/*qc final input*/
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0) + ifnull(REJ_QC_FIN_OUT_D.tot_qty_rej_qc_fin_out,0)
			)
			balance_qc_final_input,
			
			
			/*qc final output*/
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(QCFO_D.tot_qty_ds, 0) + ifnull(REJ_QOT_D.tot_qty_rej_qot,0)
			)
			balance_qc_final_output,
			

			/*qotiti output*/
			ifnull(QOT_D.tot_qty_qotiti, 0)tot_qty_qotiti,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(QOT_D.tot_qty_qotiti, 0)
			)
			balance_qotiti,			
			

			/*mutation qc output final*/
			ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0)tot_qty_mut_qc_fin_out,
			(
				ifnull(QOT_D.tot_qty_qotiti, 0) - ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0)
			)
			balance_mut_qotiti,		


			
			/*packing*/
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(MUT_QC_FIN_D.tot_qty_mut_qc_fin_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing,
			
			
			/*metal detector*/
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det,


			/*qc audit*/
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit,


			/*finish good*/
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg,


			/*shipping*/
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_sew_qc_out,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_sew_qc_out,
						id_so_det 
					FROM
						prod_rej_qc_sew_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QC_OUT_D 
				ON REJ_QC_OUT_D.id_so_det = SQO_D.id_so_det

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_mut_sew_qc_out,
						id_so_det 
					FROM
						prod_mut_sew_detail 
					GROUP BY
						id_so_det 
				)
				MUT_SQO_D 
				ON MUT_SQO_D.id_so_det = SQO_D.id_so_det

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_in,
						id_so_det 
					FROM
						prod_sew_steam_in_detail 
					GROUP BY
						id_so_det 
				)
				ST_IN_D 
				ON ST_IN_D.id_so_det = MUT_SQO_D.id_so_det 	

	
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_qc_final_out,
						SUM(ifnull(qty_ds, 0) )tot_qty_ds,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
				
				
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_qc_fin_out,
						id_so_det 
					FROM
						prod_rej_qc_fin_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QC_FIN_OUT_D 
				ON REJ_QC_FIN_OUT_D.id_so_det = QCFO_D.id_so_det				
				

			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qotiti,
						id_so_det 
					FROM
						prod_qotiti_out_detail 
					GROUP BY
						id_so_det 
				)
				QOT_D 
				ON QOT_D.id_so_det = QCFO_D.id_so_det 


			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_mut_qc_fin_out,
						id_so_det 
					FROM
						prod_mut_qc_fin_detail 
					GROUP BY
						id_so_det 
				)
				MUT_QC_FIN_D 
				ON MUT_QC_FIN_D.id_so_det = QOT_D.id_so_det


			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_rej_qot,
						id_so_det 
					FROM
						prod_reject_qot_detail 
					GROUP BY
						id_so_det 
				)
				REJ_QOT_D 
				ON REJ_QOT_D.id_so_det = QOT_D.id_so_det	

				
				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) )tot_qty_qc_audit,
						#SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.id = '{$id_cost}'  
			AND SOD.cancel = 'N' 
			GROUP BY
			SOD.id
									
	";
	return $sql;
	
}

function sql_summary_from_so($from,$to,$id_buyer,$status){
	
/* 	print_r($status);
	die(); */
	
	if($status == ''){
		$status_query = "";
	}else{
		$status_query = "AND A.status='{$status}'";
	}
	if($id_buyer == ''){
		$buyer_query = "";
	}else{
		$buyer_query = "AND A.id_buyer ='{$id_buyer}'";
	}
	
	
	$from 	=  date("Y-m-d", strtotime($from));
	$to 	=  date("Y-m-d", strtotime($to));
	$sql_balance = sql_balance_from_so("REPORT");
	$sql ="
	SELECT  
			SUMMARY.kpno,
			SUMMARY.nm_buyer,
			SUMMARY.id_dc_out_header,
			SUMMARY.id_cost,
			SUMMARY.id_buyer,
			SUMMARY.id_so_det,
			SUMMARY.dest,
			SUMMARY.color,
			SUMMARY.size,
			SUMMARY.unit,
			#SUMMARY.qty_dc_out,
			SUMMARY.urut,
			SUMMARY.buyerno,
			SUMMARY.so_no,
			SUM(ifnull(SUMMARY.qty_so,0))qty_so,
			'0' tot_qty_cutt_out,
			'0' balance_cutt_out,
			'0' tot_qty_numbering,
			'0' balance_numbering,
			'0' tot_qty_cutt_out_qc,
			'0' balance_cutt_out_qc,
			'0' tot_qty_sec_in,
			'0' balance_sec_in,
			'0' tot_qty_sec_out,
			'0' balance_sec_out,
			'0' tot_qty_sec_qc,
			'0' balance_sec_qc,
			'0' tot_dc_join,
			'0' balance_dc_join,
			'0' tot_dc_set,
			'0' balance_dc_set,
			/*mutation DC SET to sewing in  */
			SUM(ifnull(SUMMARY.tot_qty_mut_dc_set,0))tot_qty_mut_dc_set,
			SUM(ifnull(SUMMARY.balance_mut_dc_set,0))balance_mut_dc_set,
			/*sewing in*/
			SUM(ifnull(SUMMARY.tot_qty_sew_in,0))tot_qty_sew_in,
			SUM(ifnull(SUMMARY.balance_qty_sew_in,0))balance_qty_sew_in,
			/*sewing out*/
			SUM(ifnull(SUMMARY.tot_qty_sew_out,0))tot_qty_sew_out,
			SUM(ifnull(SUMMARY.balance_qty_sew_out,0))balance_qty_sew_out,			
			/*sewing qc input*/
			SUM(ifnull(SUMMARY.tot_qty_sew_qc_in,0))tot_qty_sew_qc_in,
			SUM(ifnull(SUMMARY.balance_qty_sew_qc_in,0))balance_qty_sew_qc_in,
			/*sewing qc output*/
			SUM(ifnull(SUMMARY.tot_qty_sew_qc_out,0))tot_qty_sew_qc_out,
			SUM(ifnull(SUMMARY.balance_qty_sew_qc_out,0))balance_qty_sew_qc_out,
			/*mutation sewing qc output*/
			SUM(ifnull(SUMMARY.tot_qty_mut_sew_qc_out,0))tot_qty_mut_sew_qc_out,
			SUM(ifnull(SUMMARY.balance_qty_mut_sew_qc_out,0))balance_qty_mut_sew_qc_out,
			/*steam input*/
			SUM(ifnull(SUMMARY.tot_qty_steam_in,0))tot_qty_steam_in,
			SUM(ifnull(SUMMARY.balance_steam_input,0)) balance_steam_input,				
			/*steam output*/
			SUM(ifnull(SUMMARY.tot_qty_steam_out,0))tot_qty_steam_out,
			SUM(ifnull(SUMMARY.balance_steam_output,0)) balance_steam_output,	
			/*qc final input*/
			SUM(ifnull(SUMMARY.tot_qty_qc_final_in,0))tot_qty_qc_final_in,
			SUM(ifnull(SUMMARY.balance_qc_final_input,0))balance_qc_final_input,
			/*qc final output*/
			SUM(ifnull(SUMMARY.tot_qty_qc_final_out,0))tot_qty_qc_final_out,
			SUM(ifnull(SUMMARY.balance_qc_final_output,0))balance_qc_final_output,
			/*qotiti output*/
			SUM(ifnull(SUMMARY.tot_qty_qotiti,0))tot_qty_qotiti,
			SUM(ifnull(SUMMARY.balance_qotiti,0))balance_qotiti,			
			/*mutatuion qotiti*/
			SUM(ifnull(SUMMARY.tot_qty_mut_qc_fin_out,0))tot_qty_mut_qc_fin_out,
			SUM(ifnull(SUMMARY.balance_mut_qotiti,0))balance_mut_qotiti,			
			/*packing*/
			SUM(ifnull(SUMMARY.tot_qty_packing,0))tot_qty_packing,
			SUM(ifnull(SUMMARY.balance_packing,0))balance_packing,
			/*metal detector*/
			SUM(ifnull(SUMMARY.tot_qty_mat_dec,0))tot_qty_mat_dec,
			SUM(ifnull(SUMMARY.balance_met_det,0))balance_met_det,
			/*qc audit*/
			SUM(ifnull(SUMMARY.tot_qty_qc_audit,0))tot_qty_qc_audit,
			SUM(ifnull(SUMMARY.balance_qc_audit,0))balance_qc_audit,
			/*finish good*/
			SUM(ifnull(SUMMARY.tot_qty_fg,0))tot_qty_fg,
			SUM(ifnull(SUMMARY.balance_fg,0))balance_fg,
			/*shipping*/
			SUM(ifnull(SUMMARY.tot_shp,0))tot_shp,
			SUM(ifnull(SUMMARY.balance_shp,0))balance_shp
			FROM(
				SELECT COSTING.nm_buyer,BALANCE.* FROM(
				{$sql_balance})BALANCE 
				INNER JOIN(
					SELECT A.id id_costing,A.kpno,A.status,A.deldate,A.id_buyer,D.Supplier nm_buyer
						FROM act_costing A
						LEFT JOIN(SELECT * FROM so WHERE cancel_h !='Y') B ON A.id=B.id_cost
						LEFT JOIN so_det C ON B.id = C.id_so
						LEFT JOIN mastersupplier D ON A.id_buyer = D.Id_Supplier
					
					WHERE 1=1 AND A.id IN(".generate_where_in_summary().") {$buyer_query}  {$status_query} AND A.deldate>='{$from}' AND A.deldate<='{$to}'
				)COSTING ON BALANCE.id_cost = COSTING.id_costing
				GROUP BY BALANCE.id_so_det
			)SUMMARY GROUP BY SUMMARY.id_cost
						
	";
	
	return $sql;
	
}

function populasi_table(){
		return array(
			"0"=>"prod_mut_dc_set_header",
		//	"1"=>"prod_sew_in_header",
		//	"2"=>"prod_sew_out_header",
		//	"3"=>"prod_qc_in_header",
		//	"4"=>"prod_qc_out_header",
		//	"5"=>"prod_mut_sew_header",
		//	"6"=>"prod_sew_steam_in_header",
		//	"7"=>"prod_sew_steam_out_header",
		//	"8"=>"prod_qc_final_in_header",
		//	"9"=>"prod_qotiti_out_header",
		//	"10"=>"prod_mut_qc_fin_header",
		//	"11"=>"prod_finishing_packing_header",
		//	"12"=>"prod_finishing_metal_detector_header",
		//	"13"=>"prod_finishing_qc_audit_header",
		//	"14"=>"prod_finishing_fg_header",
		//	"15"=>"prod_finishing_shp_header"
		);	
	
}
           
function generate_where_in_summary(){
	$pop_table = populasi_table();
	$str_dynamic = "";
	$field = "A.id_cost";
	$field_costing = "B.id";
	$trigger = count($pop_table) - 1;
	for($i=0;$i<count($pop_table);$i++){
		if($trigger == $i){  
			$union_all = "";
		}else{
			$union_all = "\nUNION ALL";
		}
		$str_dynamic  .= "\nSELECT  {$field} FROM {$pop_table[$i]} A INNER JOIN act_costing B ON  {$field} = {$field_costing} GROUP BY {$field}  \n{$union_all} ";
	}
	$q = "SELECT A.* FROM ({$str_dynamic}\n)A GROUP BY {$field}";
	//echo $q;
	return $q;
	
}


function where_proses_summary(){
	//SEWING IN
	$q_sew_in = "OR A.id IN(SELECT $field FROM $nama_table GROUP BY field)";
	
	
}

function sql_balance_cutting($id_cost){
	
	$sql = "
		SELECT
			ACT.kpno,
			'' AS id_dc_out_header,
			ACT.id AS id_cost,
			ACT.id_buyer,
			SOD.id AS id_so_det,
			MAX(SOD.dest) AS dest,
			MAX(SOD.color) AS color,
			MAX(SOD.size) AS size,
			SUM(SOD.qty) AS qty_so,
			MAX(SOD.unit) AS unit,
			SUM(IFNULL(SOD.qty , 0)) AS qty_dc_out,
			IFNULL(MZ.urut, 9999) AS urut,
			SO.buyerno,
			SO.so_no,
			COD_D.id_panel,
			COD_D.id_item,
			COD_D.lot,
			
			/* cutting out */
			IFNULL(COD_D.tot_qty_cut_out, 0) AS tot_qty_cut_out,
			(
				SUM(IFNULL(SOD.qty, 0)) - IFNULL(COD_D.tot_qty_cut_out, 0)
			) AS balance_qty_cut_out,
				
			/* numbering */
			IFNULL(CND_D.tot_qty_cut_num, 0) AS tot_qty_cut_num,
			(
				SUM(IFNULL(COD_D.tot_qty_cut_out, 0)) - IFNULL(CND_D.tot_qty_cut_num, 0)
			) AS balance_qty_cut_num,
			
			/* qc */
			IFNULL(CQD_D.tot_qty_cut_qc, 0) AS tot_qty_cut_qc,
			(
				SUM(IFNULL(CND_D.tot_qty_cut_num, 0)) - IFNULL(CQD_D.tot_qty_cut_qc, 0)
			) AS balance_qty_cut_qc,

			/* dc join */
			IFNULL(DJD_D.tot_qty_dc_join, 0) AS tot_qty_dc_join,
			(
				#SUM(IFNULL(CQD_D.tot_qty_cut_qc, 0)) - IFNULL(DJD_D.tot_qty_dc_join, 0)
				SUM(IFNULL(SQD_D.tot_qty_sec_qc, CQD_D.tot_qty_cut_qc)) - IFNULL(DJD_D.tot_qty_dc_join, 0)
			) AS balance_qty_dc_join,
			
			/* dc set */
			IFNULL(DSD_D.tot_qty_dc_set, 0) AS tot_qty_dc_set,
			(
				SUM(IFNULL(DJD_D.tot_qty_dc_join, 0)) - IFNULL(DSD_D.tot_qty_dc_set, 0)
			) AS balance_qty_dc_set
				
		FROM act_costing AS ACT 
		INNER JOIN so AS SO ON SO.id_cost = ACT.id 
		INNER JOIN so_det AS SOD ON SOD.id_so = SO.id 
		LEFT JOIN mastersize AS MZ ON MZ.size = SOD.size

		/* Cutting */
		LEFT JOIN (
			SELECT
				d_cod.id_cut_out,
				d_cod.id_cut_out_detail,
				d_cod.color,
				d_cod.id_panel,
				d_cod.id_so_det,
				d_cod.id_item,
				d_cod.size,
				d_cod.lot,
				SUM(IFNULL(d_cod.qty_cut_out, 0)) AS tot_qty_cut_out
			FROM prod_cut_out_detail AS d_cod
			GROUP BY d_cod.id_item,d_cod.id_so_det,d_cod.lot,d_cod.id_panel
		) AS COD_D ON COD_D.id_so_det = SOD.id
			
		/* Numbering */
		LEFT JOIN (
			SELECT				
				d_cnd.id_cut_number,
				d_cnd.id_cut_number_detail,
				d_cnd.id_cut_out_detail,
				d_cnd.id_so_det,
				d_cnd.id_item,
				d_cnd.color,
				d_codn.id_panel,
				d_codn.size,
				d_codn.lot,
				SUM(IFNULL(d_cnd.qty_cut_number, 0)) AS tot_qty_cut_num
			FROM prod_cut_number_detail AS d_cnd
			INNER JOIN (
				SELECT 
					d_codn.id_cut_out_detail,
					d_codn.id_panel,
					d_codn.size,
					d_codn.lot
				FROM prod_cut_out_detail AS d_codn
			) AS d_codn ON d_codn.id_cut_out_detail = d_cnd.id_cut_out_detail
			GROUP BY d_cnd.id_item,d_cnd.id_so_det,d_codn.id_panel,d_codn.lot
			ORDER BY d_cnd.id_cut_out_detail ASC
		) AS CND_D ON CND_D.id_cut_out_detail = COD_D.id_cut_out_detail

		/* QC */
		LEFT JOIN (
			SELECT 
				d_cqd.id_cut_qc_detail,
				d_cqd.id_cut_qc,
				d_cq.id_cost,
				d_cqd.id_cut_number_detail,
				d_cqd.id_cut_out_detail,
				d_cqd.id_so_det,
				d_cqd.id_item,
				d_cqd.color,
				d_codnn.id_panel,
				d_codnn.size,
				d_codnn.lot,
				SUM(IFNULL(d_cqd.qty_cut_qc, 0)) AS tot_qty_cut_qc
				#IFNULL(sec.qty_sec_qc, SUM(IFNULL(d_cqd.qty_cut_qc, 0))) AS tot_qty_cut_qc
			FROM prod_cut_qc_detail AS d_cqd
			INNER JOIN prod_cut_qc AS d_cq ON d_cq.id_cut_qc = d_cqd.id_cut_qc
			INNER JOIN (
				SELECT 
					d_codnn.id_cut_out_detail,
					d_codnn.id_panel,
					d_codnn.size,
					d_codnn.lot
				FROM prod_cut_out_detail AS d_codnn
			) AS d_codnn ON d_codnn.id_cut_out_detail = d_cqd.id_cut_out_detail
			
			/*LEFT JOIN (
				SELECT 
					sqd.id_sec_qc_detail,
					sqd.id_sec_qc_header,
					sq.id_cost,
					sqd.id_panel,
					sqd.id_so_det,
					sqd.id_item,
					MIN(sqd.qty - sqd.qty_reject) AS qty_sec_qc
				FROM prod_sec_qc_detail AS sqd
				INNER JOIN prod_sec_qc_header AS sq ON sq.id_sec_qc_header = sqd.id_sec_qc_header
				GROUP BY sqd.id_so_det
			) sec ON sec.id_cost = d_cq.id_cost 
			AND sec.id_so_det = d_cqd.id_so_det 
			AND sec.id_item = d_cqd.id_item 
			AND sec.id_panel = d_codnn.id_panel*/
			
			GROUP BY d_cqd.id_item,d_cqd.id_so_det,d_codnn.id_panel,d_codnn.lot
			ORDER BY d_cqd.id_cut_out_detail ASC
		) AS CQD_D ON CQD_D.id_cut_out_detail = CND_D.id_cut_out_detail #AND CQD_D.id_cut_number_detail = CND_D.id_cut_number_detail

		/* Sec QC */
		LEFT JOIN (
			SELECT 
				sqd.id_sec_qc_detail,
				sqd.id_sec_qc_header,
				sq.id_cost,
				sqd.id_panel,
				sqd.id_so_det,
				sqd.id_item,
				SUM(sqd.qty - sqd.qty_reject) AS tot_qty_sec_qc
			FROM prod_sec_qc_detail AS sqd
			INNER JOIN prod_sec_qc_header AS sq ON sq.id_sec_qc_header = sqd.id_sec_qc_header
			GROUP BY sqd.id_so_det
		) SQD_D ON SQD_D.id_cost = CQD_D.id_cost 
		AND SQD_D.id_so_det = CQD_D.id_so_det 
		AND SQD_D.id_item = CQD_D.id_item 
		AND SQD_D.id_panel = CQD_D.id_panel

		/* DC Join */
		LEFT JOIN (
			SELECT 
				d_djd.id_dc_join_detail,
				d_djd.id_dc_join,
				d_djd.id_cut_out_detail,
				d_djd.id_cut_number_detail,
				d_djd.id_cut_qc_detail,
				d_djd.id_so_det,
				d_djd.id_item,
				d_codnnn.id_panel,
				d_codnnn.size,
				d_codnnn.lot,
				SUM(IFNULL(d_djd.qty_dc_join, 0)) AS tot_qty_dc_join
			FROM prod_dc_join_detail AS d_djd
			INNER JOIN (
				SELECT 
					d_codnnn.id_cut_out_detail,
					d_codnnn.id_panel,
					d_codnnn.size,
					d_codnnn.lot
				FROM prod_cut_out_detail AS d_codnnn
			) AS d_codnnn ON d_codnnn.id_cut_out_detail = d_djd.id_cut_out_detail
			GROUP BY d_djd.id_item,d_djd.id_so_det,d_codnnn.id_panel,d_codnnn.lot
			ORDER BY d_djd.id_cut_out_detail ASC
		) AS DJD_D ON DJD_D.id_so_det = CQD_D.id_so_det AND DJD_D.id_item = CQD_D.id_item AND DJD_D.id_panel = CQD_D.id_panel AND DJD_D.lot = CQD_D.lot

		/* DC Set */
		LEFT JOIN (
			SELECT 
				d_dsd.id_dc_set_detail,
				d_dsd.id_dc_set,
				d_dsd.id_so_det,
				d_dsd.color,
				SUM(IFNULL(d_dsd.qty_dc_set, 0)) AS tot_qty_dc_set
			FROM prod_dc_set_detail AS d_dsd
			GROUP BY d_dsd.id_so_det
			ORDER BY d_dsd.id_dc_set_detail ASC
		) AS DSD_D ON DSD_D.id_so_det = DJD_D.id_so_det

		WHERE ACT.id = '{$id_cost}'
		AND SOD.cancel = 'N'
		GROUP BY SOD.id,SOD.color,COD_D.id_panel,COD_D.id_item,COD_D.lot
		ORDER BY COD_D.id_item ASC,COD_D.id_panel ASC,COD_D.lot ASC,SOD.id ASC
	";
	return $sql;
	
}


function sql_balance_cutting_backup($id_cost){
	
	$sql = "

		SELECT
			ACT.kpno,
			'' AS id_dc_out_header,
			ACT.id AS id_cost,
			ACT.id_buyer,
			SOD.id AS id_so_det,
			MAX(SOD.dest) AS dest,
			MAX(SOD.color) AS color,
			MAX(SOD.size) AS size,
			SUM(SOD.qty) AS qty_so,
			MAX(SOD.unit) AS unit,
			SUM(IFNULL(SOD.qty , 0)) AS qty_dc_out,
			IFNULL(MZ.urut, 9999) AS urut,
			SO.buyerno,
			SO.so_no,
			COD_D.id_panel,
			
			IFNULL(COD_D.tot_qty_cut_out, 0) AS tot_qty_cut_out,
			(
				SUM(IFNULL(SOD.qty , 0)) - IFNULL(COD_D.tot_qty_cut_out, 0)
			) AS balance_qty_cut_out /*cutting out*/
			
			/* IFNULL(SEO_D.tot_qty_sew_out, 0) AS tot_qty_sew_out,
			(
				IFNULL(SEI_D.tot_qty_sew_in, 0) - IFNULL(SEO_D.tot_qty_sew_out, 0) 
			) AS balance_qty_sew_out,
			
			IFNULL(SQI_D.tot_qty_sew_qc_in, 0) AS tot_qty_sew_qc_in,
			(
				IFNULL(SEO_D.tot_qty_sew_out, 0) - IFNULL(SQI_D.tot_qty_sew_qc_in, 0) 
			) AS balance_qty_sew_qc_in,
			
			IFNULL(SQO_D.tot_qty_sew_qc_out, 0) AS tot_qty_sew_qc_out,
			(
				IFNULL(SQI_D.tot_qty_sew_qc_in, 0) - IFNULL(SQO_D.tot_qty_sew_qc_out, 0)
			) AS balance_qty_sew_qc_out,
			
			IFNULL(ST_D.tot_qty_steam_out, 0) AS tot_qty_steam_out,
			(
				IFNULL(SQO_D.tot_qty_sew_qc_out, 0) - IFNULL(ST_D.tot_qty_steam_out, 0)
			) AS balance_steam_output,
			
			IFNULL(QCFI_D.tot_qty_qc_final_in, 0) AS tot_qty_qc_final_in,
			(
				IFNULL(ST_D.tot_qty_steam_out, 0) - IFNULL(QCFI_D.tot_qty_qc_final_in, 0)
			) AS balance_qc_final_input,
			
			IFNULL(QCFO_D.tot_qty_qc_final_out, 0) AS tot_qty_qc_final_out,
			(
				IFNULL(QCFI_D.tot_qty_qc_final_in, 0) - IFNULL(QCFO_D.tot_qty_qc_final_out, 0)
			) AS balance_qc_final_output,
			
			IFNULL(P_D.tot_qty_packing, 0) AS tot_qty_packing,
			(
				IFNULL(QCFO_D.tot_qty_qc_final_out, 0) - IFNULL(P_D.tot_qty_packing, 0)
			) AS balance_packing,
			
			IFNULL(MED_P.tot_qty_mat_dec, 0) AS tot_qty_mat_dec,
			(
				IFNULL(P_D.tot_qty_packing, 0) - IFNULL(MED_P.tot_qty_mat_dec, 0)
			) AS balance_met_det,
			
			IFNULL(QCA_D.tot_qty_qc_audit, 0) AS tot_qty_qc_audit,
			(
				IFNULL(MED_P.tot_qty_mat_dec, 0) - IFNULL(QCA_D.tot_qty_qc_audit, 0)
			) AS balance_qc_audit,
			
			IFNULL(FG_D.tot_qty_fg, 0) AS tot_qty_fg,
			(
				IFNULL(QCA_D.tot_qty_qc_audit, 0) - IFNULL(FG_D.tot_qty_fg, 0)
			) AS balance_fg,
			
			IFNULL(SHP_D.tot_shp, 0) AS tot_shp,
			(
				IFNULL(FG_D.tot_qty_fg, 0) - IFNULL(SHP_D.tot_shp, 0)
			) AS balance_shp */
			
		FROM act_costing AS ACT 
		INNER JOIN so AS SO ON SO.id_cost = ACT.id 
		INNER JOIN so_det AS SOD ON SOD.id_so = SO.id 
		LEFT JOIN mastersize AS MZ ON MZ.size = SOD.size

		LEFT JOIN (
			SELECT
				id_cut_out,
				id_cut_out_detail,
				color,
				id_panel,
				id_so_det,
				size,
				SUM(IFNULL(qty_cut_out, 0)) AS tot_qty_cut_out
			FROM prod_cut_out_detail /* Cutting */
			GROUP BY id_so_det,id_panel
		) AS COD_D ON COD_D.id_so_det = SOD.id

		/* LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_sew_out,
				id_so_det 
			FROM sew_out 
			GROUP BY id_so_det 
		) AS SEO_D ON SEI_D.id_so_det = SEO_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty_good, 0)) AS tot_qty_sew_qc_in,
				id_so_det 
			FROM prod_qc_in_detail 
			GROUP BY id_so_det 
		) AS SQI_D ON SQI_D.id_so_det = SEO_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_sew_qc_out,
				id_so_det 
			FROM qc_out 
			GROUP BY id_so_det 
		) AS SQO_D ON SQO_D.id_so_det = SQI_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_steam_out,
				id_so_det 
			FROM steam_out 
			GROUP BY id_so_det 
		) AS ST_D ON ST_D.id_so_det = SQO_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_qc_final_in,
				id_so_det 
			FROM prod_qc_final_in_detail 
			GROUP BY id_so_det 
		) AS QCFI_D ON QCFI_D.id_so_det = ST_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0))tot_qty_qc_final_out,
				id_so_det 
			FROM prod_qc_final_out_detail 
			GROUP BY id_so_det 
		) AS QCFO_D ON QCFO_D.id_so_det = QCFI_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_packing,
				id_so_det 
			FROM prod_finishing_packing_detail 
			GROUP BY id_so_det 
		) AS P_D ON P_D.id_so_det = QCFO_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_mat_dec,
				id_so_det 
			FROM prod_finishing_metal_detector_detail 
			GROUP BY id_so_det
		) AS MED_P ON MED_P.id_so_det = P_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_qc_audit,
				id_so_det 
			FROM prod_finishing_qc_audit_detail 
			GROUP BY id_so_det 
		) AS QCA_D ON QCA_D.id_so_det = MED_P.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_qty_fg,
				id_so_det 
			FROM prod_finishing_fg_detail
			GROUP BY id_so_det 
		) AS FG_D ON FG_D.id_so_det = QCA_D.id_so_det 

		LEFT JOIN (
			SELECT
				SUM(IFNULL(qty, 0)) AS tot_shp,
				id_so_det
			FROM prod_finishing_shp_detail 
			GROUP BY id_so_det 
		) AS SHP_D ON SHP_D.id_so_det = FG_D.id_so_det */

		WHERE ACT.id = '{$id_cost}'
		AND SOD.cancel = 'N'
		GROUP BY SOD.id,SOD.color,COD_D.id_panel

	";
	return $sql;
	
}


 
function sql_balance_secondary_from_cutting($id_cost){
	$sql = "
	
	SELECT  
		SEC.number_cutting AS cutting_number,
		SEC.number_bundle AS bundle_number,
		SEC.number_sack AS sack_number,
		SEC.kpno,																					
		SEC.id_cf id_proses,
		SEC.cfcode,
		SEC.cfdesc,
		SEC.id_dc_out_header,
		SEC.id_cost,
		SEC.id_buyer,
		SEC.id_so_det,
		SEC.id_panel,
		SEC.dest,
		SEC.color,
		SEC.size,
		SEC.qty_so,
		SEC.unit,
		SEC.qty_dc_out,
		SEC.urut,
		SEC.buyerno,
		SEC.so_no,
		SEC.id_item,
		SEC.lot,
		SEC.nama_group shell,
		SEC.nama_item,
		SEC.nama_panel,
		SEC.tot_qty_cut_out,
		SEC.balance_qty_cut_out,
		IFNULL(SEC_IN_D.tot_qty_sec_in, 0) AS tot_qty_sec_in,
			(
				SUM(IFNULL(SEC.tot_qty_cut_out , 0)) - IFNULL(SEC_IN_D.tot_qty_sec_in, 0)
			) AS balance_sec_in,
		IFNULL(SEC_OUT_D.tot_qty_sec_out, 0) AS tot_qty_sec_out,
			(
				SUM(IFNULL(SEC_IN_D.tot_qty_sec_in , 0)) - IFNULL(SEC_OUT_D.tot_qty_sec_out, 0)
			) AS balance_sec_out,			
		IFNULL(SEC_QC_D.tot_qty_sec_qc, 0) AS tot_qty_sec_qc,
			(
				SUM(IFNULL(SEC_OUT_D.tot_qty_sec_out , 0)) - IFNULL(SEC_QC_D.tot_qty_sec_qc, 0)
			) AS balance_sec_qc				
			
	FROM (
		SELECT 	
			CUTT_OUT.kpno,
			CF.id_cf,
			CF.cfcode,
			CF.cfdesc,
			CUTT_OUT.id_dc_out_header,
			CUTT_OUT.id_cost,
			CUTT_OUT.id_buyer,
			CUTT_OUT.id_so_det,
			CUTT_OUT.id_panel,
			CUTT_OUT.dest,
			CUTT_OUT.color,
			CUTT_OUT.size,
			CUTT_OUT.qty_so,
			CUTT_OUT.unit,
			CUTT_OUT.qty_dc_out,
			CUTT_OUT.urut,
			CUTT_OUT.buyerno,
			CUTT_OUT.so_no,
			CUTT_OUT.id_item,
			CUTT_OUT.lot,
			CUTT_OUT.nama_group,
			CUTT_OUT.nama_item,
			CUTT_OUT.nama_panel,
			CUTT_OUT.number_cutting,
			CUTT_OUT.number_bundle,
			CUTT_OUT.number_sack,
			CUTT_OUT.tot_qty_cut_out,
			CUTT_OUT.balance_qty_cut_out
		FROM (

			SELECT
				ACT.kpno,
				'' AS id_dc_out_header,
				ACT.id AS id_cost,
				ACT.id_buyer,
				SOD.id AS id_so_det,
				COD_D.id_panel,
				MAX(SOD.dest) AS dest,
				MAX(SOD.color) AS color,
				MAX(SOD.size) AS size,
				SUM(SOD.qty) AS qty_so,
				MAX(SOD.unit) AS unit,
				SUM(IFNULL(SOD.qty , 0)) AS qty_dc_out,
				IFNULL(MZ.urut, 9999) AS urut,
				SO.buyerno,
				SO.so_no,
				COD_D.id_item,
				COD_D.lot,
				COD_D.nama_group,
				COD_D.nama_item,
				COD_D.nama_panel,
				COD_D.number_cutting,
				COD_D.number_bundle,
				COD_D.number_sack,
				IFNULL(COD_D.tot_qty_cut_out, 0) AS tot_qty_cut_out,
				(
					SUM(IFNULL(SOD.qty , 0)) - IFNULL(COD_D.tot_qty_cut_out, 0)
				) AS balance_qty_cut_out
			FROM act_costing AS ACT 
			INNER JOIN so AS SO ON SO.id_cost = ACT.id 
			INNER JOIN so_det AS SOD ON SOD.id_so = SO.id 
			LEFT JOIN mastersize AS MZ ON MZ.size = SOD.size
			LEFT JOIN (
				SELECT
					A.id_cut_out,
					AA.id_cut_qc_detail,
					AA.id_cut_number_detail,
					AA.id_cut_out_detail,
					A.color,
					A.id_panel,
					A.id_so_det,
					A.id_item,
					A.size,
					A.lot,
					SUM(IFNULL(AA.qty_cut_qc, 0)) AS tot_qty_cut_out,
					A.id_grouping,
					B.name_grouping nama_group,
					C.itemdesc nama_item,
					D.nama_panel,
					GROUP_CONCAT(AAA.number_cutting) AS number_cutting,
					GROUP_CONCAT(AAA.number_bundle) AS number_bundle,
					GROUP_CONCAT(AAA.number_sack) AS number_sack
				FROM prod_cut_qc_detail AS AA
				INNER JOIN prod_cut_out_detail A ON A.id_cut_out_detail = AA.id_cut_out_detail
				INNER JOIN prod_cut_number_detail AS AAA ON AAA.id_cut_number_detail = AA.id_cut_number_detail
				INNER JOIN mastergrouping B ON A.id_grouping = B.id_grouping
				INNER JOIN masteritem C ON A.id_item =C.id_item
				INNER JOIN masterpanel D ON A.id_panel = D.id
				GROUP BY id_item,id_so_det,id_panel
			) AS COD_D ON COD_D.id_so_det = SOD.id
			WHERE ACT.id = '{$id_cost}' 
			AND SOD.cancel = 'N'
			AND ifnull(COD_D.id_panel,0) !='0'
			GROUP BY SOD.id,SOD.color,COD_D.id_panel,COD_D.id_item
		) CUTT_OUT
			
		CROSS JOIN (
			SELECT   
				A.id id_cf
				,A.cfcode
				,A.cfdesc
				,B.id_item
				,B.id_act_cost id_cost
			FROM mastercf A
			INNER JOIN act_costing_mfg B ON A.id = B.id_item
			GROUP BY B.id_item,B.id_act_cost
		) CF ON CUTT_OUT.id_cost = CF.id_cost
	) SEC

	LEFT JOIN (
		SELECT 
			id_sec_in_detail
			,id_so_det
			,id_panel
			,id_item
			,id_proses
			,SUM(IFNULL(qty_sec_in - qty_reject_sec_in, 0)) AS tot_qty_sec_in
		FROM prod_sec_in_detail
		GROUP BY id_item,id_so_det,id_panel,id_proses
	) AS SEC_IN_D ON SEC.id_so_det = SEC_IN_D.id_so_det 
	AND SEC.id_panel = SEC_IN_D.id_panel
	AND SEC.id_item = SEC_IN_D.id_item
	AND SEC.id_cf = SEC_IN_D.id_proses

	LEFT JOIN (
		SELECT 
			id_sec_out_detail
			,id_so_det
			,id_panel
			,id_item
			,id_proses
			,SUM(IFNULL(qty_sec_out - qty_reject_sec_out, 0)) AS tot_qty_sec_out
		FROM prod_sec_out_detail
		GROUP BY id_item,id_so_det,id_panel,id_proses
	) AS SEC_OUT_D ON SEC_IN_D.id_so_det = SEC_OUT_D.id_so_det 
	AND SEC_IN_D.id_panel = SEC_OUT_D.id_panel
	AND SEC_IN_D.id_item = SEC_OUT_D.id_item	
	AND SEC_IN_D.id_proses = SEC_OUT_D.id_proses

	LEFT JOIN (
		SELECT 	 
			id_sec_qc_detail
			,id_so_det
			,id_panel
			,id_item
			,id_proses
			,SUM(IFNULL(qty - qty_reject, 0)) AS tot_qty_sec_qc
		FROM prod_sec_qc_detail
		GROUP BY id_item,id_so_det,id_panel,id_proses
	) AS SEC_QC_D ON SEC_OUT_D.id_so_det = SEC_QC_D.id_so_det
	AND SEC_OUT_D.id_panel = SEC_QC_D.id_panel
	AND SEC_OUT_D.id_item = SEC_QC_D.id_item
	AND SEC_OUT_D.id_proses = SEC_QC_D.id_proses
	GROUP BY SEC.color,SEC.id_so_det,SEC.id_panel,SEC.id_item,SEC.id_cf
	
	";	
	return $sql;
			
	
}

//Daniel - 3 Des 2020 - Laporan Production Detail
function sql_detail_from_so($from,$to,$id_buyer){ //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	$from 	=  date("Y-m-d", strtotime($from));
	$to 	=  date("Y-m-d", strtotime($to));
	//$id_buyer = 
	
	$sql ="

	SELECT 

			Z.kpno
		,	IFNULL(Z.date_output,'-')date_output
		,	IFNULL(Z.no_proses,'-')no_proses
		,	Z.Urut_Proses
		,	Z.Proses_Name
		,	Z.buyerno
		,	Z.dest
		,	Z.color
		,	Z.size
		,	Z.qty_so
		,	Z.unit
		,	Z.Proses Qty_Proses
		,	(Z.qty_so-Z.Proses)Balance
		FROM
		(SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '1' Urut_Proses
, 'Total Qty Sewing In' Proses_Name
, 	Y.tot_qty_sew_in Proses
,	Y.date_output date_output
,	Y.no_sew_in no_proses
/*
,	Y.tot_qty_sew_in            <--
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out
,	Y.balance_qty_sew_out
,	Y.tot_qty_sew_qc_in
,	Y.balance_qty_sew_qc_in
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SEI_H.date_output,
			SEI_H.no_sew_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det,
						id_sew_in_header 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						id_sew_in_header,
						date_output,
						no_sew_in
					FROM
						prod_sew_in_header
				)
				SEI_H
				ON SEI_H.id_sew_in_header = SEI_D.id_sew_in_header
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer
			
UNION ALL

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '2' Urut_Proses
, 'Balance Qty Sewing In' Proses_Name
, 	Y.balance_qty_sew_in Proses
,	Y.date_output date_output
,	Y.no_sew_in no_proses
/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in       <--
,	Y.tot_qty_sew_out
,	Y.balance_qty_sew_out
,	Y.tot_qty_sew_qc_in
,	Y.balance_qty_sew_qc_in
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SEI_H.date_output,
			SEI_H.no_sew_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det,
						id_sew_in_header 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						id_sew_in_header,
						date_output,
						no_sew_in
					FROM
						prod_sew_in_header
				)
				SEI_H
				ON SEI_H.id_sew_in_header = SEI_D.id_sew_in_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer
			
UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '3' Urut_Proses
, 'Total Qty Sewing Out' Proses_Name
, 	Y.tot_qty_sew_out Proses
,	Y.date_output date_output
,	Y.no_sew_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          <--
,	Y.balance_qty_sew_out
,	Y.tot_qty_sew_qc_in
,	Y.balance_qty_sew_qc_in
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SEO_H.date_output,
			SEO_H.no_sew_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det,
						id_sew_out_header
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_out_header,
						date_output,
						no_sew_out
					FROM
						prod_sew_out_header
				)
				SEO_H
				ON SEO_H.id_sew_out_header = SEO_D.id_sew_out_header
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer
			
UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '4' Urut_Proses
, 'Balance Qty Sewing Out' Proses_Name
, 	Y.balance_qty_sew_out Proses
,	Y.date_output date_output
,	Y.no_sew_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      <--
,	Y.tot_qty_sew_qc_in
,	Y.balance_qty_sew_qc_in
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SEO_H.date_output,
			SEO_H.no_sew_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det,
						id_sew_out_header 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_out_header,
						date_output,
						no_sew_out
					FROM
						prod_sew_out_header
				)
				SEO_H
				ON SEO_H.id_sew_out_header = SEO_D.id_sew_out_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer

UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '5' Urut_Proses
, 'Total Qty Sewing QC In' Proses_Name
, 	Y.tot_qty_sew_qc_in Proses
,	Y.date_output date_output
,	Y.no_sew_qc_in no_proses
/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			<--
,	Y.balance_qty_sew_qc_in
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SQI_H.date_output,
			SQI_H.no_sew_qc_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det,
						id_sew_qc_in_header 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_qc_in_header,
						date_output,
						no_sew_qc_in
					FROM
						prod_qc_in_header
				)
				SQI_H
				ON SQI_H.id_sew_qc_in_header = SQI_D.id_sew_qc_in_header				 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer						


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '6' Urut_Proses
, 'Balance Qty Sewing QC In' Proses_Name
, 	Y.balance_qty_sew_qc_in Proses
,	Y.date_output date_output
,	Y.no_sew_qc_in no_proses
/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		<--
,	Y.tot_qty_sew_qc_out
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SQI_H.date_output,
			SQI_H.no_sew_qc_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det,
						id_sew_qc_in_header 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_qc_in_header,
						date_output,
						no_sew_qc_in
					FROM
						prod_qc_in_header
				)
				SQI_H
				ON SQI_H.id_sew_qc_in_header = SQI_D.id_sew_qc_in_header					 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer						


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '7' Urut_Proses
, 'Total Qty Sewing QC Out' Proses_Name
, 	Y.tot_qty_sew_qc_out Proses
,	Y.date_output date_output
,	Y.no_sew_qc_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		<--
,	Y.balance_qty_sew_qc_out
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SQO_H.date_output,
			SQO_H.no_sew_qc_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det,
						id_sew_qc_out_header
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_qc_out_header,
						no_sew_qc_out,
						date_output
					FROM
						prod_qc_out_header
				)
				SQO_H
				ON SQO_H.id_sew_qc_out_header = SQO_D.id_sew_qc_out_header				 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer			


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '8' Urut_Proses
, 'Balance Qty Sewing QC Out' Proses_Name
, 	Y.balance_qty_sew_qc_out Proses
,	Y.date_output date_output
,	Y.no_sew_qc_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   <--
,	Y.tot_qty_steam_out
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SQO_H.date_output,
			SQO_H.no_sew_qc_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det,
						id_sew_qc_out_header 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_sew_qc_out_header,
						no_sew_qc_out,
						date_output
					FROM prod_qc_out_header
				)
				SQO_H
				ON SQO_H.id_sew_qc_out_header = SQO_D.id_sew_qc_out_header					
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer			


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '9' Urut_Proses
, 'Total Qty Steam Out' Proses_Name
, 	Y.tot_qty_steam_out Proses
,	Y.date_output date_output
,	Y.no_sew_steam_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         <--
,	Y.balance_steam_output
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			ST_H.date_output,
			ST_H.no_sew_steam_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det,
						id_sew_steam_out_header 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_steam_out_header,
						date_output,
						no_sew_steam_out
					FROM
						prod_sew_steam_out_header
				)				 
				ST_H
				ON ST_H.id_sew_steam_out_header = ST_D.id_sew_steam_out_header
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer			


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '10' Urut_Proses
, 'Balance Steam Out' Proses_Name
, 	Y.balance_steam_output Proses
,	Y.date_output date_output
,	Y.no_sew_steam_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     <--
,	Y.tot_qty_qc_final_in
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			ST_H.date_output,
			ST_H.no_sew_steam_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det,
						id_sew_steam_out_header 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_steam_out_header,
						date_output,
						no_sew_steam_out
					FROM
						prod_sew_steam_out_header
				)				 
				ST_H
				ON ST_H.id_sew_steam_out_header = ST_D.id_sew_steam_out_header				 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer			


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '11' Urut_Proses
, 'Total Qty QC Final In' Proses_Name
, 	Y.tot_qty_qc_final_in Proses
,	Y.date_output date_output
,	Y.no_sew_final_qc_in no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      <--
,	Y.balance_qc_final_input
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCFI_H.date_output,
			QCFI_H.no_sew_final_qc_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det,
						id_sew_qc_final_in_header 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_qc_final_in_header,
						no_sew_final_qc_in,
						date_output
					FROM
						prod_qc_final_in_header
				)
				QCFI_H
				ON QCFI_H.id_sew_qc_final_in_header = QCFI_D.id_sew_qc_final_in_header			 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '12' Urut_Proses
, 'Balance QC Final In' Proses_Name
, 	Y.balance_qc_final_input Proses
,	Y.date_output date_output
,	Y.no_sew_final_qc_in no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   <--
,	Y.tot_qty_qc_final_out
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCFI_H.date_output,
			QCFI_H.no_sew_final_qc_in,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det,
						id_sew_qc_final_in_header 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_sew_qc_final_in_header,
						no_sew_final_qc_in,
						date_output
					FROM
						prod_qc_final_in_header
				)
				QCFI_H
				ON QCFI_H.id_sew_qc_final_in_header = QCFI_D.id_sew_qc_final_in_header					
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '13' Urut_Proses
, 'Total Qty QC Final Out' Proses_Name
, 	Y.tot_qty_qc_final_out Proses
,	Y.date_output date_output
,	Y.no_sew_qc_final_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     <--
,	Y.balance_qc_final_output
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCFO_H.date_output,
			QCFO_H.no_sew_qc_final_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det,
						id_sew_qc_final_out_header 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_sew_qc_final_out_header,
						no_sew_qc_final_out,
						date_output
					FROM
						prod_qc_final_out_header
				)
				QCFO_H
				ON QCFO_H.id_sew_qc_final_out_header = QCFO_D.id_sew_qc_final_out_header					 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '14' Urut_Proses
, 'Balance QC Final Out' Proses_Name
, 	Y.balance_qc_final_output Proses
,	Y.date_output date_output
,	Y.no_sew_qc_final_out no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  <--
,	Y.tot_qty_packing
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCFO_H.date_output,
			QCFO_H.no_sew_qc_final_out,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det,
						id_sew_qc_final_out_header 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_sew_qc_final_out_header,
						no_sew_qc_final_out,
						date_output
					FROM
						prod_qc_final_out_header
				)
				QCFO_H
				ON QCFO_H.id_sew_qc_final_out_header = QCFO_D.id_sew_qc_final_out_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '15' Urut_Proses
, 'Total Qty Packing' Proses_Name
, 	Y.tot_qty_packing Proses
,	Y.date_output date_output
,	Y.no_finishing_packing no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           <--
,	Y.balance_packing
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			P_H.date_output,
			P_H.no_finishing_packing,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det,
						id_finishing_packing_header 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_finishing_packing_header,
						no_finishing_packing,
						date_output
					FROM
						prod_finishing_packing_header
				)
				P_H
				ON P_H.id_finishing_packing_header = P_D.id_finishing_packing_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '16' Urut_Proses
, 'Balance Packing' Proses_Name
, 	Y.balance_packing Proses
,	Y.date_output date_output
,	Y.no_finishing_packing no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			<--
,	Y.tot_qty_mat_dec
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			P_H.date_output,
			P_H.no_finishing_packing,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det,
						id_finishing_packing_header 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_finishing_packing_header,
						no_finishing_packing,
						date_output
					FROM
						prod_finishing_packing_header
				)
				P_H
				ON P_H.id_finishing_packing_header = P_D.id_finishing_packing_header					
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '17' Urut_Proses
, 'Total Qty Metal Detector' Proses_Name
, 	Y.tot_qty_mat_dec Proses
,	Y.date_output date_output
,	Y.no_finishing_metal_detector no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			<--
,	Y.balance_met_det
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			MED_H.date_output,
			MED_H.no_finishing_metal_detector,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det,
						id_finishing_metal_detector_header 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_finishing_metal_detector_header,
						no_finishing_metal_detector,
						date_output
					FROM
						prod_finishing_metal_detector_header
				)
				MED_H
				ON MED_H.id_finishing_metal_detector_header = MED_P.id_finishing_metal_detector_header				 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '18' Urut_Proses
, 'Balance Metal Detector' Proses_Name
, 	Y.balance_met_det Proses
,	Y.date_output date_output
,	Y.no_finishing_metal_detector no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		<--
,	Y.tot_qty_qc_audit
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			MED_H.date_output,
			MED_H.no_finishing_metal_detector,			
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det,
						id_finishing_metal_detector_header 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_finishing_metal_detector_header,
						no_finishing_metal_detector,
						date_output
					FROM
						prod_finishing_metal_detector_header
				)
				MED_H
				ON MED_H.id_finishing_metal_detector_header = MED_P.id_finishing_metal_detector_header						 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '19' Urut_Proses
, 'Total Qty QC Audit' Proses_Name
, 	Y.tot_qty_qc_audit Proses
,	Y.date_output date_output
,	Y.no_finishing_qc_audit no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			<--
,	Y.balance_qc_audit
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCA_H.date_output,
			QCA_H.no_finishing_qc_audit,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det,
						id_finishing_qc_audit_header 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_finishing_qc_audit_header,
						no_finishing_qc_audit,
						date_output
					FROM
						prod_finishing_qc_audit_header
				)
				QCA_H
				ON QCA_H.id_finishing_qc_audit_header = QCA_D.id_finishing_qc_audit_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '20' Urut_Proses
, 'Balance QC Audit' Proses_Name
, 	Y.balance_qc_audit Proses
,	Y.date_output date_output
,	Y.no_finishing_qc_audit no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			
,	Y.balance_qc_audit			<--
,	Y.tot_qty_fg
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			QCA_H.date_output,
			QCA_H.no_finishing_qc_audit,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det,
						id_finishing_qc_audit_header 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det
			LEFT JOIN
				(
					SELECT
						id_finishing_qc_audit_header,
						no_finishing_qc_audit,
						date_output
					FROM
						prod_finishing_qc_audit_header
				)
				QCA_H
				ON QCA_H.id_finishing_qc_audit_header = QCA_D.id_finishing_qc_audit_header				 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '21' Urut_Proses
, 'Total Qty FG' Proses_Name
, 	Y.tot_qty_fg Proses
,	Y.date_output date_output
,	Y.no_finishing_fg no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			
,	Y.balance_qc_audit			
,	Y.tot_qty_fg 		<--
,	Y.balance_fg
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			FG_H.date_output,
			FG_H.no_finishing_fg,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det,
						id_finishing_fg_header 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_finishing_fg_header,
						no_finishing_fg,
						date_output
					FROM
						prod_finishing_fg_header
				)
				FG_H
				ON FG_H.id_finishing_fg_header = FG_D.id_finishing_fg_header				
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno
,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut
,	Y.so_no

, '22' Urut_Proses
, 'Balance FG' Proses_Name
, 	Y.balance_fg Proses
,	Y.date_output date_output
,	Y.no_finishing_fg no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			
,	Y.balance_qc_audit			
,	Y.tot_qty_fg 		
,	Y.balance_fg		<--
,	Y.tot_shp
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			FG_H.date_output,
			FG_H.no_finishing_fg,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det,
						id_finishing_fg_header 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						id_finishing_fg_header,
						no_finishing_fg,
						date_output
					FROM
						prod_finishing_fg_header
				)
				FG_H
				ON FG_H.id_finishing_fg_header = FG_D.id_finishing_fg_header					
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno

,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut

,	Y.so_no

, '23' Urut_Proses
, 'Total Shipping' Proses_Name
, 	Y.tot_shp Proses
,	Y.date_output date_output
,	Y.no_finishing_shp no_proses

/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			
,	Y.balance_qc_audit			
,	Y.tot_qty_fg 		
,	Y.balance_fg		
,	Y.tot_shp			<--
,	Y.balance_shp
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SHP_H.date_output,
			SHP_H.no_finishing_shp,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det,
						id_finishing_shp_header 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_finishing_shp_header,
						no_finishing_shp,
						date_output
					FROM
						prod_finishing_shp_header
				)
				SHP_H
				ON SHP_H.id_finishing_shp_header = SHP_D.id_finishing_shp_header				 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer	


UNION ALL 

SELECT 

	Y.kpno
,	Y.id_dc_out_header
,	Y.id_cost
,	Y.deldate
,	Y.id_buyer
,	Y.buyerno

,	Y.id_so_det
,	Y.dest
,	Y.color
,	Y.size
,	Y.qty_so
,	Y.unit
,	Y.qty_dc_out
,	Y.urut

,	Y.so_no

, '24' Urut_Proses
, 'Balance SHP' Proses_Name
, 	Y.balance_shp Proses
,	Y.date_output date_output
,	Y.no_finishing_shp no_proses
/*
,	Y.tot_qty_sew_in
,	Y.balance_qty_sew_in
,	Y.tot_qty_sew_out          
,	Y.balance_qty_sew_out      
,	Y.tot_qty_sew_qc_in			
,	Y.balance_qty_sew_qc_in		
,	Y.tot_qty_sew_qc_out		
,	Y.balance_qty_sew_qc_out   
,	Y.tot_qty_steam_out         
,	Y.balance_steam_output     
,	Y.tot_qty_qc_final_in      
,	Y.balance_qc_final_input   
,	Y.tot_qty_qc_final_out     
,	Y.balance_qc_final_output  
,	Y.tot_qty_packing           
,	Y.balance_packing			
,	Y.tot_qty_mat_dec 			
,	Y.balance_met_det    		
,	Y.tot_qty_qc_audit 			
,	Y.balance_qc_audit			
,	Y.tot_qty_fg 		
,	Y.balance_fg		
,	Y.tot_shp			
,	Y.balance_shp		<--
*/
FROM
(

SELECT
			ACT.kpno,
			''id_dc_out_header,
			ACT.id id_cost,
			ACT.deldate,
			ACT.id_buyer,
			SHP_H.date_output,
			SHP_H.no_finishing_shp,
			SOD.id id_so_det,
			MAX(SOD.dest)dest,
			MAX(SOD.color)color,
			MAX(SOD.size)size,
			SUM(SOD.qty)qty_so,
			MAX(SOD.unit)unit,
			SUM(ifnull(SOD.qty , 0))qty_dc_out,
			ifnull(MZ.urut, 9999)urut,
			SO.buyerno,
			SO.so_no 	/*sewing in*/
			,
			ifnull(SEI_D.tot_qty_sew_in, 0)tot_qty_sew_in,
			(
				SUM(ifnull(SOD.qty , 0)) - ifnull(SEI_D.tot_qty_sew_in, 0)
			)
			balance_qty_sew_in 	/*sewing out*/
			,
			ifnull(SEO_D.tot_qty_sew_out, 0)tot_qty_sew_out,
			(
				ifnull(SEI_D.tot_qty_sew_in, 0) - ifnull(SEO_D.tot_qty_sew_out, 0) 
			)
			balance_qty_sew_out 	/*sewing qc input*/
			,
			ifnull(SQI_D.tot_qty_sew_qc_in, 0)tot_qty_sew_qc_in,
			(
				ifnull(SEO_D.tot_qty_sew_out, 0) - ifnull(SQI_D.tot_qty_sew_qc_in, 0) 
			)
			balance_qty_sew_qc_in 	/*sewing qc output*/
			,
			ifnull(SQO_D.tot_qty_sew_qc_out, 0)tot_qty_sew_qc_out,
			(
				ifnull(SQI_D.tot_qty_sew_qc_in, 0) - ifnull(SQO_D.tot_qty_sew_qc_out, 0)
			)
			balance_qty_sew_qc_out 	/*steam output*/
			,
			ifnull(ST_D.tot_qty_steam_out, 0)tot_qty_steam_out,
			(
				ifnull(SQO_D.tot_qty_sew_qc_out, 0) - ifnull(ST_D.tot_qty_steam_out, 0)
			)
			balance_steam_output 	/*qc final input*/
			,
			ifnull(QCFI_D.tot_qty_qc_final_in, 0)tot_qty_qc_final_in,
			(
				ifnull(ST_D.tot_qty_steam_out, 0) - ifnull(QCFI_D.tot_qty_qc_final_in, 0)
			)
			balance_qc_final_input 	/*qc final output*/
			,
			ifnull(QCFO_D.tot_qty_qc_final_out, 0)tot_qty_qc_final_out,
			(
				ifnull(QCFI_D.tot_qty_qc_final_in, 0) - ifnull(QCFO_D.tot_qty_qc_final_out, 0)
			)
			balance_qc_final_output 	/*packing*/
			,
			ifnull(P_D.tot_qty_packing, 0)tot_qty_packing,
			(
				ifnull(QCFO_D.tot_qty_qc_final_out, 0) - ifnull(P_D.tot_qty_packing, 0)
			)
			balance_packing 	/*metal detector*/
			,
			ifnull(MED_P.tot_qty_mat_dec, 0)tot_qty_mat_dec,
			(
				ifnull(P_D.tot_qty_packing, 0) - ifnull(MED_P.tot_qty_mat_dec, 0)
			)
			balance_met_det 	/*qc audit*/
			,
			ifnull(QCA_D.tot_qty_qc_audit, 0)tot_qty_qc_audit,
			(
				ifnull(MED_P.tot_qty_mat_dec, 0) - ifnull(QCA_D.tot_qty_qc_audit, 0)
			)
			balance_qc_audit 	/*finish good*/
			,
			ifnull(FG_D.tot_qty_fg, 0)tot_qty_fg,
			(
				ifnull(QCA_D.tot_qty_qc_audit, 0) - ifnull(FG_D.tot_qty_fg, 0)
			)
			balance_fg 	/*shipping*/
			,
			ifnull(SHP_D.tot_shp, 0)tot_shp,
			(
				ifnull(FG_D.tot_qty_fg, 0) - ifnull(SHP_D.tot_shp, 0)
			)
			balance_shp 
			FROM
			act_costing ACT 
			INNER JOIN
				so SO 
				ON SO.id_cost = ACT.id 
			INNER JOIN
				so_det SOD 
				ON SOD.id_so = SO.id 
			LEFT JOIN
				mastersize MZ 
				ON MZ.size = SOD.size 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_in,
						id_so_det 
					FROM
						sew_in 
					GROUP BY
						id_so_det 
				)
				SEI_D 
				ON SEI_D.id_so_det = SOD.id 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_sew_out,
						id_so_det 
					FROM
						sew_out 
					GROUP BY
						id_so_det 
				)
				SEO_D 
				ON SEI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty_good, 0))tot_qty_sew_qc_in,
						id_so_det 
					FROM
						prod_qc_in_detail 
					GROUP BY
						id_so_det 
				)
				SQI_D 
				ON SQI_D.id_so_det = SEO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_sew_qc_out,
						id_so_det 
					FROM
						qc_out 
					GROUP BY
						id_so_det 
				)
				SQO_D 
				ON SQO_D.id_so_det = SQI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_steam_out,
						id_so_det 
					FROM
						steam_out 
					GROUP BY
						id_so_det 
				)
				ST_D 
				ON ST_D.id_so_det = SQO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_qc_final_in,
						id_so_det 
					FROM
						prod_qc_final_in_detail 
					GROUP BY
						id_so_det 
				)
				QCFI_D 
				ON QCFI_D.id_so_det = ST_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_final_out,
						id_so_det 
					FROM
						prod_qc_final_out_detail 
					GROUP BY
						id_so_det 
				)
				QCFO_D 
				ON QCFO_D.id_so_det = QCFI_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_packing,
						id_so_det 
					FROM
						prod_finishing_packing_detail 
					GROUP BY
						id_so_det 
				)
				P_D 
				ON P_D.id_so_det = QCFO_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_mat_dec,
						id_so_det 
					FROM
						prod_finishing_metal_detector_detail 
					GROUP BY
						id_so_det 
				)
				MED_P 
				ON MED_P.id_so_det = P_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0) - ifnull(rpr, 0))tot_qty_qc_audit,
						id_so_det 
					FROM
						prod_finishing_qc_audit_detail 
					GROUP BY
						id_so_det 
				)
				QCA_D 
				ON QCA_D.id_so_det = MED_P.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_qty_fg,
						id_so_det 
					FROM
						prod_finishing_fg_detail 
					GROUP BY
						id_so_det 
				)
				FG_D 
				ON FG_D.id_so_det = QCA_D.id_so_det 
			LEFT JOIN
				(
					SELECT
						SUM(ifnull(qty, 0))tot_shp,
						id_so_det,
						id_finishing_shp_header 
					FROM
						prod_finishing_shp_detail 
					GROUP BY
						id_so_det 
				)
				SHP_D 
				ON SHP_D.id_so_det = FG_D.id_so_det
			LEFT JOIN
				(
					SELECT
						id_finishing_shp_header,
						no_finishing_shp,
						date_output
					FROM
						prod_finishing_shp_header
				)
				SHP_H
				ON SHP_H.id_finishing_shp_header = SHP_D.id_finishing_shp_header					 
			WHERE
			ACT.deldate >= '{$from}' AND ACT.deldate <= '{$to}' $id_buyer
			AND SOD.cancel = 'N' 
			GROUP BY ACT.deldate, ACT.id_buyer, SOD.id
			)Y GROUP BY Y.deldate, Y.id_buyer
)Z ORDER BY Z.kpno, Z.id_buyer, FIELD(Z.Urut_Proses, 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24) ASC 

		
		
	";
	
	return $sql;
	
}


?>