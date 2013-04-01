<style>
	#errorExceptionHeading {
		padding:            10px;
		background-color:   red;
		color:              white;
	}
	#errorExceptionBody {
		padding:            10px;
		background-color:   rgb(191,191,191);
	}
	#errorExceptionBody div {
		padding:            5px; 
		margin:	            5px; 
		background-color:   white; 
		line-height:        8px;
		border-radius:      9px;
	}
</style>
<div id="errorExceptionHeading">
	<span style="color:black; font-weight:bold;">
	ErrorException [ <?php echo $container->_type; ?> ] : 
	</span>
	<?php echo $container->_message; ?>
</div>
<div id="errorExceptionBody">
	<?php echo $container->_file .  ' [' . $container->_line_number . ']'; ?> <br>
	<div><?php echo $container->_context; ?></div>
	<?php echo $backtrace; ?>
</div>
