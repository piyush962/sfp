<?php

$cakeDescription = 'Managing Packaging Business';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css(['validationEngine.jquery', 'select2.min', 'bootstrap.min', 'font-awesome.min', 'style.css?' . time() . '']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <?= $this->Html->script(['jquery-3.6.0.min','select2.min', 'loader', 'jquery.validationEngine.min', 'languages/jquery.validationEngine-en', 'jquery.validationEngine', 'bootstrap.bundle.min', 'repeater', 'custom.js?' . time() . '']); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js" integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery(".validation_engine").validationEngine({
                notEmpty: true
            })
        });
    </script>
</head>

<body>
    <div class="page-wrapper">
        <?= $this->Flash->render() ?>
        <?php echo $this->element('admin/sidebar'); ?>
        <div class="admin-right-wrapper">
            <?php echo $this->element('admin/header'); ?>
            <?= $this->fetch('content') ?>
        </div>
    </div>
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