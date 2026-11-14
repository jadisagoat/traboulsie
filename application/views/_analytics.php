<?php
// Google Analytics 4: the measurement ID (G-XXXXXXXXXX) is set in the back office, Home page > Google
$_hp = isset($home_page) ? $home_page : $this->fct->get_home_page();
$_ga = isset($_hp['ga4_id']) ? preg_replace('/[^A-Z0-9-]/', '', strtoupper(trim($_hp['ga4_id']))) : '';
if ($_ga != '') { ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $_ga ?>"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= $_ga ?>');</script>
<?php }
