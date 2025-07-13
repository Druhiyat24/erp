<?php

        if (isset($_GET['pow'])) { #PO Waiting

        	echo "<section class='content-header' style='padding-bottom: 10px'>

        	<h1>PO Waiting

        	</h1>

        	</section>";

        	echo "<div class='box'>";

        	echo "<div class='box-body'><a href='xlsPO_waiting.php'>Save To Excel</a><br></div>";		

        	echo "</div>";		        	

        	echo "<div class='box'>";

        	echo "<div class='box-body'>";

        	echo "<table id='examplefix' class='display responsive' style='width:100%'>";

        	echo "<thead>";	

        	echo "<tr>";		

        	echo "<th>No</th>";			

        	echo "<th># PO</th>";			 

        	echo "<th>PO Date</th>";			

        	echo "<th>Supplier</th>";			

        	echo "<th>P.Terms</th>";			

        	echo "<th>ETD</th>";			

        	echo "<th>ETA</th>";			

        	echo "<th>Expected Date</th>";		

        	echo "</tr>";		

        	echo "</thead>";	

        	echo "<tbody>";	



        	$sql="SELECT a.id, a.pono, a.podate, b.supplier, c.nama_pterms, a.etd, a.eta, a.expected_date from po_header a inner join mastersupplier b inner join masterpterms c WHERE a.id_supplier=b.Id_Supplier AND a.id_terms=c.id AND a.app='W' AND pono is not null	order by podate DESC";



        # QUERY TABLE

        	$query = mysql_query($sql); 

        	$no = 1;

        	while($data = mysql_fetch_array($query))

        	{ 

        		echo "<tr>";

        		echo "<td>$no</td>"; 

        		echo "<td>$data[pono]</td>";

        		echo "<td>".fd_view($data['podate'])."</td>";

        		echo "<td>$data[supplier]</td>";

        		echo "<td>$data[nama_pterms]</td>";

        		echo "<td>".fd_view($data['etd'])."</td>";

        		echo "<td>".fd_view($data['eta'])."</td>";

        		echo "<td>".fd_view($data['expected_date'])."</td>";

        		echo "</tr>";

          	$no++; // menambah nilai nomor urut

          }



          echo "</tbody>";

          echo "</table>";

          echo "</div>";

          echo "</div>";

      } 

    	elseif (isset($_GET['leta'])) { #Late ETA PO

    		echo "<section class='content-header' style='padding-bottom: 10px'>

    		<h1>List Purchase Order With ETA in 3 Days

    		</h1>

    		</section>";

    		echo "<div class='box'>";

    		echo "<div class='box-body'><a href='xlsPO_late_eta.php'>Save To Excel</a><br></div>";		

    		echo "</div>";	

    		echo "<div class='box'>";

    		echo "<div class='box-body'>";

    		echo "<table id='examplefix' class='display responsive' style='width:100%'>";

    		echo "<thead>";	

    		echo "<tr>";		

    		echo "<th>No</th>";			

    		echo "<th># PO</th>";			 

    		echo "<th>Rev</th>";			 

    		echo "<th>PO Date</th>";			

    		echo "<th>Supplier</th>";			

    		echo "<th>P.Terms</th>";			

    		echo "<th>Buyer</th>";			

    		echo "<th>WS #</th>";			

    		echo "<th>Style</th>";			

    		echo "<th>ETA</th>";						

    		echo "</tr>";		

    		echo "</thead>";	

    		echo "<tbody>";



    		$datefil=date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));

    		$sql="select a.eta,tmppoit.kpno,tmppoit.styleno,a.revise,a.jenis,a.app,a.id,pono,podate,supplier,

    		nama_pterms,tmppoit.buyer from po_header a inner join 

    		mastersupplier s on a.id_supplier=s.id_supplier inner join 

    		masterpterms d on a.id_terms=d.id

    		inner join 

    		(select ac.kpno,ac.styleno,poit.id_jo,poit.id_po,ms.supplier buyer from po_item poit 

    		inner join jo_det jod on jod.id_jo=poit.id_jo 

    		inner join so on jod.id_so=so.id

    		inner join act_costing ac on so.id_cost=ac.id 

    		inner join mastersupplier ms on ac.id_buyer=ms.id_supplier

    		group by poit.id_po) tmppoit 

    		on tmppoit.id_po=a.id 

    		where a.eta<='$datefil' and jenis in ('M','P')  

    		union all 

    		select eta,'','',a.revise,a.jenis,a.app,a.id,pono,podate,supplier,

    		nama_pterms,tmppoit.buyer from po_header a inner join 

    		mastersupplier s on a.id_supplier=s.id_supplier inner join 

    		masterpterms d on a.id_terms=d.id

    		inner join 

    		(select poit.id_jo,poit.id_po,'' buyer from po_item poit 

    		inner join reqnon_header reqnonh on reqnonh.id=poit.id_jo 

    		group by poit.id_po) tmppoit 

    		on tmppoit.id_po=a.id 

    		where a.eta<='$datefil' and jenis='N' order by eta desc";



        # QUERY TABLE

    		$query = mysql_query($sql); 

    		$no = 1;

    		while($data = mysql_fetch_array($query)) { 

    			echo "<tr>";

    			echo "<td>$no</td>"; 

    			echo "<td>$data[pono]</td>";

    			echo "<td>$data[revise]</td>";

    			echo "<td>".fd_view($data['podate'])."</td>";

    			echo "<td>$data[supplier]</td>";

    			echo "<td>$data[nama_pterms]</td>";

    			echo "<td>$data[buyer]</td>";

    			echo "<td>$data[kpno]</td>";

    			echo "<td>$data[styleno]</td>";

    			echo "<td>".fd_view($data["eta"])."</td>";

    		#echo "<td><a class='btn-s' href='xlsPur.php?id=$data[id]'

		    #data-toggle='tooltip' title='Save XLS' target='_blank'><i class='fa fa-file-excel-o'></i></a></td>";

    			echo "</tr>";

          $no++; // menambah nilai nomor urut

      }



      echo "</tbody>";

      echo "</table>";

      echo "</div>";

      echo "</div>";



		} elseif (isset($_GET['app_pr_po'])) { #PR Approved PO Waiting

			echo "<section class='content-header' style='padding-bottom: 10px'>

			<h1>List Approved PR, PO Waiting

			</h1>

			</section>";

			echo "<div class='box'>";

			echo "<div class='box-body'><a href='xlsPur.php'>Save To Excel</a><br></div>";		

			echo "</div>";	

			echo "<div class='box'>";

			echo "<div class='box-body'>";

			echo "<table id='examplefix' class='display responsive' style='width:100%'>";

			echo "<thead>";	

			echo "<tr>";		

			echo "<th>No</th>";			

			echo "<th># PO</th>";			 

			echo "<th>PO Date</th>";			

			echo "<th>Supplier</th>";			

			echo "<th>P.Terms</th>";			

			echo "<th>ETD</th>";			

			echo "<th>ETA</th>";			

			echo "<th>Expected Date</th>";			

			echo "</tr>";		

			echo "</thead>";	

			echo "<tbody>";

			

			$sql="select b.id, b.pono, b.podate, e.supplier,d.nama_pterms, b.eta, b.etd, b.expected_date from po_header b INNER JOIN po_item a INNER JOIN jo c inner join masterpterms d inner join mastersupplier e where a.id_po=b.id AND a.id_jo=c.id AND b.app='W' AND c.app='A' and b.id_terms=d.id and e.id_supplier=b.id_supplier";



        # QUERY TABLE

			$query = mysql_query($sql); 

			$no = 1;

			while($data = mysql_fetch_array($query))

			{ 

				echo "<tr>";

				echo "<td>$no</td>"; 

				echo "<td>$data[pono]</td>";

				echo "<td>".fd_view($data['podate'])."</td>"; 

				echo "<td>$data[supplier]</td>";

				echo "<td>$data[nama_pterms]</td>";

				echo "<td>".fd_view($data['etd'])."</td>";

				echo "<td>".fd_view($data['eta'])."</td>";

				echo "<td>".fd_view($data['expected_date'])."</td>";

				echo "</tr>";

          	$no++; // menambah nilai nomor urut

          }



          echo "</tbody>";

          echo "</table>";

          echo "</div>";

          echo "</div>";



      } elseif (isset($_GET['allpo'])) { #ALL PO

      	echo "<section class='content-header' style='padding-bottom: 10px'>

      	<h1>List All Purchase Order

      	</h1>

      	</section>";

      	echo "<div class='box'>";

      	echo "<div class='box-body'><a href='xlsPO_allPO.php'>Save To Excel</a><br></div>";		

      	echo "</div>";	

      	echo "<div class='box'>";

      	echo "<div class='box-body'>";

      	echo "<table id='examplefix' class='display responsive' style='width:100%'>";

      	echo "<thead>";	

      	echo "<tr>";		

      	echo "<th>No</th>";			

      	echo "<th># PO</th>";			 

      	echo "<th>PO Date</th>";			

      	echo "<th>Supplier</th>";			

      	echo "<th>P.Terms</th>";			

      	echo "<th>ETD</th>";			

      	echo "<th>ETA</th>";			

      	echo "<th>Expected Date</th>";					

      	echo "</tr>";		

      	echo "</thead>";	

      	echo "<tbody>";



      	$sql="SELECT a.id, a.pono, a.podate, b.supplier, c.nama_pterms, a.etd, a.eta, a.expected_date from po_header a inner join mastersupplier b inner join masterpterms c WHERE a.id_supplier=b.Id_Supplier AND a.id_terms=c.id AND pono is not null order by podate DESC";



        # QUERY TABLE

      	$query = mysql_query($sql); 

      	$no = 1;

      	while($data = mysql_fetch_array($query))

      	{ 

      		echo "<tr>";

      		echo "<td>$no</td>"; 

      		echo "<td>$data[pono]</td>";

      		echo "<td>".fd_view($data['podate'])."</td>";

      		echo "<td>$data[supplier]</td>";

      		echo "<td>$data[nama_pterms]</td>";

      		echo "<td>".fd_view($data['etd'])."</td>";

      		echo "<td>".fd_view($data['eta'])."</td>";

      		echo "<td>".fd_view($data['expected_date'])."</td>";

      		echo "</tr>";

          	$no++; // menambah nilai nomor urut

          }



          echo "</tbody>";

          echo "</table>";

          echo "</div>";

          echo "</div>";



    } elseif (isset($_GET['latepo'])) { #LATE PO OPEN BY PR DATE

    	echo "<section class='content-header' style='padding-bottom: 10px'>

    	<h1>Late PO Open by PR Date

    	</h1>

    	</section>";

    	echo "<div class='box'>";

    	echo "<div class='box-body'><a href='xlsPO_latePO.php'>Save To Excel</a><br></div>";		

    	echo "</div>";	

    	echo "<div class='box'>";

    	echo "<div class='box-body'>";

    	echo "<table id='examplefix' class='display responsive' style='width:100%'>";

    	echo "<thead>";	

    	echo "<tr>";		

    	echo "<th>No</th>";			

    	echo "<th>Supplier</th>";			

    	echo "<th># JO</th>";			 

    	echo "<th>JO Date</th>";			 

    	echo "<th>Username</th>";			

    	echo "<th>Approval Date</th>";			

    	echo "<th>Approved By</th>";				

    	echo "</tr>";		

    	echo "</thead>";	

    	echo "<tbody>";



    	#$datefil=date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));

    	$sql="SELECT d.supplier, a.jo_no, a.jo_date, a.username, a.app_date, a.app_by

    	FROM jo a inner join po_header b inner join po_item c inner join mastersupplier d WHERE b.id_supplier=d.Id_Supplier AND b.id=c.id_po AND a.id=c.id_jo AND a.app_date IS NOT NULL AND a.jo_date=a.jo_date AND a.app='A' GROUP BY d.Id_Supplier ORDER BY a.jo_date asc 



    	";





        # QUERY TABLE

    	$query = mysql_query($sql); 

    	$no = 1;

    	while($data = mysql_fetch_array($query))

    	{ 

    		echo "<tr>";

    		echo "<td>$no</td>"; 

    		echo "<td>$data[supplier]</td>";

    		echo "<td>$data[jo_no]</td>";

    		echo "<td>".fd_view($data['jo_date'])."</td>";

    		echo "<td>$data[username]</td>";

    		echo "<td>".fd_view($data['app_date'])."</td>";

    		echo "<td>$data[app_by]</td>";

    		echo "</tr>";

          	$no++; // menambah nilai nomor urut

          }



          echo "</tbody>";

          echo "</table>";

          echo "</div>";

          echo "</div>";



    } elseif (isset($_GET['fullpo'])) { #FULL QTY PO

    	echo "<section class='content-header' style='padding-bottom: 10px'>

    	<h1>Full Qty PO (Qty PO = Qty BPB)

    	</h1>

    	</section>";

    	echo "<div class='box'>";

    	echo "<div class='box-body'><a href='xlsPO_fullqty.php'>Save To Excel</a><br></div>";		

    	echo "</div>";	

    	echo "<div class='box'>";

    	echo "<div class='box-body'>";

    	echo "<table id='examplefix' class='display responsive' style='width:100%'>";

    	echo "<thead>";	

    	echo "<tr>";		

    	echo "<th>No</th>";			

    	echo "<th># JO</th>";			 

    	echo "<th>Username</th>";			

    	echo "<th>Approval Date</th>";			

    	echo "<th>Approved By</th>";				

    	echo "</tr>";		

    	echo "</thead>";	

    	echo "<tbody>";

			#"DATEDIFF(c.jo_date,(c.jo_date+3))

    	#$datefil=date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));

    	$sql="SELECT d.supplier, a.jo_no, a.jo_date, a.username, a.app_date, a.app_by

    	FROM jo a inner join po_header b inner join po_item c inner join mastersupplier d WHERE b.id_supplier=d.Id_Supplier AND b.id=c.id_po AND a.id=c.id_jo AND a.app_date IS NOT NULL AND a.jo_date=a.jo_date AND a.app='A' GROUP BY d.Supplier ORDER BY a.jo_date asc 



    	";







        # QUERY TABLE

    	$query = mysql_query($sql); 

    	$no = 1;

    	while($data = mysql_fetch_array($query))

    	{ 

    		echo "<tr>";

    		echo "<td>$no</td>"; 

    		echo "<td>$data[supplier]</td>";

    		echo "<td>$data[jo_no]</td>";

    		echo "<td>".fd_view($data['jo_date'])."</td>";

    		echo "<td>$data[username]</td>";

    		echo "<td>".fd_view($data['app_date'])."</td>";

    		echo "<td>$data[app_by]</td>";

    		echo "</tr>";

          	$no++; // menambah nilai nomor urut

          }



          echo "</tbody>";

          echo "</table>";

          echo "</div>";

          echo "</div>";



      }





      

      ?>





