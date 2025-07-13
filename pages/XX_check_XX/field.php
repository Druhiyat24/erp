<?php 
if (empty($_SESSION['username'])) { header("location:../../index.php"); }

?>
# TABLE DEBUG
<div class="box">
  <div class="box-header">
    <h3 class="box-title"></h3>
	<form action='?mod=CheckField' method='GET'>
		<input type="text" class="form-control" name="table" placeholder="Nama Table">
		<input type="hidden" class="form-control" name="mod" value="CheckField" placeholder="Nama Table">
		<br/>
		<input type="submit" class="btn btn-primary" value="GO">
    </a>&nbsp

  </div>
  <?php if(ISSET($_GET['table'])){ 
  $table = $_GET['table'];
  
  ?>
  <div class="box-body">
    <div id='detail_item'>
      <table id="examplefix" class="display responsive" style="width:100%;font-size:11px;">
        <thead>
        <tr>
          <th>field</th>
          <th>description</th>
		  <th>Null</th>
		  <th>Key</th>
		  <th>Default</th>
		  <th>Extra</th>
        </tr>
        </thead>
        <tbody>
			<?php
          $sql="DESCRIBE $table";
          $result=mysql_query($sql);
          while($data = mysql_fetch_array($result))
          { ?>			
			<tr>
				<td><?php echo $data['Field'] ?> </td>
				<td><?php echo $data['Type'] ?> </td>
				<td><?php echo $data['Null'] ?> </td>
				<td><?php echo $data['Key'] ?> </td>
				<td><?php echo $data['Default'] ?> </td>
				<td><?php echo $data['Extra'] ?> </td>
			</tr>
			
			
			
			
		  <?php 
		  }
			?>
        </tbody>
      </table>
    </div>  
  </div>
  <?php } ?>
</div>

