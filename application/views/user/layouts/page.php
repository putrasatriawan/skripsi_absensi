<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
	<?php $this->load->view("user/layouts/header");?>  
	<body class="hold-transition layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view("user/layouts/menu");?>  
			<?php $this->load->view($content);?>  
		 	<?php $this->load->view("user/layouts/footer");?> 
		<input type="hidden" id="base_url" value="<?php echo base_url();?>">
		</div>
	</body>
</html>
