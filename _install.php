<!DOCTYPE>
<html>
<head>
	<title>Mooo::Installation</title>
	<style>
		body {
			background-color:   silver;
		}
		#container {
			background-color:   white;
			width:              800px;
			margin:             0 auto 0 auto;
			border-left:        1px solid black;
			border-right:       1px solid black;
			height:             400px;
		}
		#header {
			text-align:         center;
		}
		#header h1 {
			padding-top:        20px;
			font-size:          45px;
		}
		#header hr {
			width:              50%;
		}
		#table {
			padding:            20px;
		}
		.label {
			width:              250px;
			padding-bottom:     4px;
		}
		.info {
			font-weight:        bold;
			width:              500px;
		}
		#pass {
			font-size:          22px;
			color:              white;
			padding:            10px;
			margin-top:         20px;
		}
	</style>
</head>
<body>
	<?php $flag = TRUE; ?>
	<div id="container">
		<div id="header">
			<h1>Welcome to the Mooo Framework !</h1>
			<hr>
		</div>
		<div id="table">
			<table>
				<tr>
					<td class="label">PHP Version</td>
					<?php 
						if(phpversion() > 5.3)
						{
							echo '<td class="info" style="color:green;">' . phpversion() . '</td>';
						}
						else
						{
							$flag = FALSE;
							echo '<td class="info" style="color:red;">' . phpversion() . '</td>';
						}
					?>
				</tr>
				<tr>
					<td class="label">System directory</td>
					<?php 
						if(is_readable(SPATH))
						{
							echo '<td class="info" style="color:green;">' . SPATH . '</td>';
						}
						else
						{
							$flag = FALSE;
							echo '<td class="info" style="color:red;">' . SPATH . '</td>';
						}
					?>
				</tr>
				<tr>
					<td class="label">Modules directory</td>
					<?php 
						if(is_readable(MPATH))
						{
							echo '<td class="info" style="color:green;">' . MPATH . '</td>';
						}
						else
						{
							$flag = FALSE;
							echo '<td class="info" style="color:red;">' . MPATH . '</td>';
						}
					?>
				</tr>
				<tr>
					<td class="label">Application directory</td>
					<?php 
						if(is_readable(APATH))
						{
							echo '<td class="info" style="color:green;">' . APATH . '</td>';
						}
						else
						{
							$flag = FALSE;
							echo '<td class="info" style="color:red;">' . APATH . '</td>';
						}
					?>
				</tr>
				<tr>
					<td class="label">Logs directory</td>
					<?php 
						if(is_readable(APATH . 'logs/errors'))
						{
							echo '<td class="info" style="color:green;">' . APATH . 'logs'.DIRECTORY_SEPARATOR.'errors' . '</td>';
						}
						else
						{
							$flag = FALSE;
							echo '<td class="info" style="color:red;">' . APATH . 'logs'.DIRECTORY_SEPARATOR.'errors' . '</td>';
						}
					?>
				</tr>
			</table>
			<?php if($flag === TRUE): ?>
				<div id="pass" style="background-color:green;">
					All environmental tests have been passed! Rename, or remove <i>_install.php</i> file from your root directory.
				</div>
			<?php else: ?>
				<div id="pass" style="background-color:red;">
					Some environmental tests have not been passed. You might check the results before running the framework.
				</div>
			<?php endif; ?>
		</div>
	</div>
</body>
</html>
