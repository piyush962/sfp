<?php

$cakeDescription = 'Login';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css(['validationEngine.jquery','bootstrap.min','font-awesome.min','style']) ?>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
   
    <?= $this->Html->script(['jquery-3.6.0.min','jquery.validationEngine.min','languages/jquery.validationEngine-en','jquery.validationEngine','bootstrap.bundle.min']); ?>
    <script>
        jQuery(document).ready(function(){
			jQuery(".validation_engine").validationEngine({
                notEmpty: true
            })
		});
        
    </script>
</head>
<body>
    
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    <script>
$(document).ready(function(){
setTimeout(() => {
    $('.autotimeout').slideUp('2000',function(){
        $(this).hide();
    });
}, 5000);
});
        </script>
</body>
</html>
