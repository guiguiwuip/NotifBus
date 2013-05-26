<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>
			<?php echo $title_for_layout; ?> NotifBus
		</title>
		
		<!--<script src="http://code.jquery.com/jquery-latest.min.js"></script>-->
		
		<?php
			echo $this->Html->meta('icon');

			echo $this->Html->css('bootstrap');
			echo $this->Html->css('flat-ui');
			echo $this->Html->css('style');

			echo $this->Html->script('jquery-1.9.1.min');
			echo $this->Html->script('main');

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
	    <!--[if lt IE 9]>
	      <script src="js/html5shiv.js"></script>
	    <![endif]-->
	
	</head>
	
<body class="accueil">

	<div class="container-login">
		<?php echo $this->element('login'); ?>
	</div>
	
	<?php 
		$flash = $this->Session->flash();

		echo $this->element('footer', array('flash' =>$flash));
	?>

</body>
</html>
