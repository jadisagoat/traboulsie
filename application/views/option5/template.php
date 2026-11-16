<?php $is_ar = isset($lang) && $lang == 'ar'; ?>
<!DOCTYPE html>
<html lang="<?=$is_ar ? 'ar' : 'en'?>" dir="<?=$is_ar ? 'rtl' : 'ltr'?>" class="no-js<?=$is_ar ? ' is-ar' : ''?>">
<head>
<?php $this->load->view('option5/_head'); ?>
</head>
<body class="o4 o5"><?php $this->load->view('_analytics'); ?>

<?php $this->load->view($content); ?>

</body>
</html>
