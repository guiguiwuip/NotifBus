	<div class="loader">
		<div class="anim inline"></div>
		<div class="etai"></div>
	</div>
	
	<div class="container-flash <?php if($flash) echo "open";?>">
	
		<?php //if($flash) echo $flash; ?>
			
		<?php 
		//Exemples :
		/*	<div class="flash red">
				<span class="close fui-cross-16"></span>
				<h1>Mon joli titre</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam illo ratione sint minima aliquid reprehenderit debitis mollitia velit modi vitae sapiente tenetur quibusdam repellendus dignissimos eaque dolor quas sit officia?</p>
				</p>
				<div class="button">
					<button class="btn btn-large next">Oui</button>
					<button class="btn btn-large">Non</button>
				</div>
			</div>
			<div class="flash green">
				<span class="close fui-cross-16"></span>
				<h1>Mon joli titre</h1>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis porro numquam dolorum debitis id repellendus adipisci aspernatur sapiente laborum ipsa possimus vitae veritatis rem qui vel sed consequuntur saepe natus.</p>
				</p>
				<div class="button">
					<button class="btn btn-large next">Oui</button>
					<button class="btn btn-large">Non</button>
				</div>
			</div>*/
		 ?>
			
	</div>
		
	<?php
		echo $this->Html->script('jquery-ui-1.10.0.custom.min');
		echo $this->Html->script('jquery.dropkick-1.0.0');
		echo $this->Html->script('custom_checkbox_and_radio');
		echo $this->Html->script('custom_radio');
		echo $this->Html->script('jquery.tagsinput');
		echo $this->Html->script('bootstrap-tooltip');
		echo $this->Html->script('jquery.placeholder');
		//echo $this->Html->script('google-tts');
		echo $this->Html->script('zelect');
	?>
	