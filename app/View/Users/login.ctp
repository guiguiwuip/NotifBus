<div class="users form">

<?php 
	echo $this->Form->create('User', array(
		'class' => 'login-form'
	)); 
?>
    <fieldset>
        <legend>Merci de  vous indentifier.</legend>
    <?php 
    	echo $this->Form->input('username', array(
    		'label'       => array(
    			'class' => "login-field-icon fui-man-16",
    			'text'  => ''
    		),
    		'class'       => 'login-field',
    		'placeholder' => 'Pseudo',
    		'div'         => 'control-group'
    		));
        echo $this->Form->input('password', array(
    		'label'       => array(
    			'class' => "login-field-icon fui-lock-16",
    			'text'  => ''
    		),
    		'class'       => 'login-field',
    		'placeholder' => 'Mot de passe',
    		'div'         => 'control-group'));
    ?>
    </fieldset>
<?php 
	echo $this->Form->end('Se connecter', array(
		'div' => array(
			'class' => 'btn btn-large btn-block'
			)
	));
?>

</div>

<?php if(1 ==0) : ?>
	
<div class="users form ajax">
	
<?php 
	echo $this->Form->create(null, array(
		'url'     => '/users/login',
		//'default' => false,
		'class'   => 'async',
		)); 
?>
    <?php 
    	echo $this->Form->input('username');
        echo $this->Form->input('password');
    ?>
    
<?php echo $this->Form->end(__('Login')); ?>

</div>
<?php endif; ?>
	
