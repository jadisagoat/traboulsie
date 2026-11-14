<?
if (!$this->session->userdata('user_id'))
  redirect(base_url() . 'back_office/home');
?>
<!DOCTYPE html>
<html lang="<?php echo bo_lang(); ?>" class="light-style layout-menu-fixed layout-compact" dir="<?php echo bo_is_ar() ? 'rtl' : 'ltr'; ?>" data-theme="theme-default" data-assets-path="<?php echo base_url(); ?>new_theme/assets/" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title><?php echo $title; ?></title>
  <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>favicon.ico" />

  <!-- Bootstrap 5 + DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/css/dataTables.dataTables.css">

  <!-- Icons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/vendor/fonts/boxicons.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/vendor/fonts/fontawesome.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/vendor/css/rtl/theme-bordered.css" class="template-customizer-theme-css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/css/style.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>new_theme/assets/css/demo.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/datepicker.css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.lightbox-0.5.css" />

  <!-- jQuery 3 (+ shim for old plugins that still read $.browser) -->
  <script src="<?php echo base_url(); ?>js/jquery-3.4.1.min.js"></script>
  <script>jQuery.browser = jQuery.browser || { msie: false };</script>
  <script src="<?php echo base_url(); ?>js/jquery.lightbox-0.5.js"></script>
  <script type="text/javascript" src="<?php echo base_url(); ?>js/ckeditor/ckeditor.js"></script>
  <?php if (bo_is_ar()) { ?>
  <!-- control panel in Arabic: editor menus in Arabic, the website text itself stays left to right -->
  <script>if (window.CKEDITOR) { CKEDITOR.config.language = 'ar'; CKEDITOR.config.contentsLangDirection = 'ltr'; }</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
  <?php } ?>

  <style>
    .bg-menu-theme .menu-inner .menu-item.open>.menu-link:hover,
    .bg-menu-theme .menu-inner .menu-item:not(.active) .menu-link:hover {
      color: #fff !important;
      background-color: #5a8dee !important;
    }

    .menu-item,
    .menu-header {
      text-transform: capitalize;
    }

    #template-customizer {
      display: none !important;
    }

    .breadcrumb li {
      display: inline-block;
      text-shadow: 0 1px 0 #ffffff;
    }

    .breadcrumb li a {
      color: #5a8dee !important;
    }

    .breadcrumb .active {
      color: #999999;
    }

    .breadcrumb li.pull-right {
      float: right;
    }

    /* Old Bootstrap 2 helpers still used by the content pages */
    .alert-error {
      color: #ff3e1d;
      background-color: #ffe0db;
      border-color: #ffc5bb;
    }

    .text-error {
      color: #ff3e1d;
      font-size: 12px;
    }

    .hidden {
      display: none;
    }

    .cur {
      cursor: pointer;
    }

    .clearFix {
      display: block;
      clear: both;
      height: 0;
      overflow: hidden;
    }

    .align-right {
      text-align: right;
    }

    .hundred {
      width: 100%;
      float: left;
    }

    .middle-forms fieldset legend {
      font-size: 1.1rem;
      font-weight: 600;
      color: #5a8dee;
      border-bottom: 1px solid #eee;
      padding-bottom: 6px;
      margin: 15px 0 10px;
    }

    .middle-forms textarea.input-xxlarge,
    .middle-forms select.input-xxlarge {
      width: 100%;
    }

    .middle-forms input[type="file"] {
      display: block;
      width: 100%;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-bottom: 6px;
    }

    .middle-forms input[type="image"] {
      background: none;
      border: 0;
      padding: 0;
    }

    .table-edit-link i,
    .table-delete-link i {
      font-size: 18px;
      color: #f16625;
    }

    .table-edit-link,
    .table-delete-link {
      margin: 0 4px;
    }

    .order_active {
      font-weight: bold;
    }

    .dataTables_empty {
      text-align: center;
    }

    /* Fonts: the theme asks for fonts that are not bundled, use system fonts */
    body,
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6,
    .breadcrumb li,
    .breadcrumb a {
      font-family: "Public Sans", -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
    }

    .btn-save {
      width: auto;
      min-width: 160px;
      padding: 8px 20px;
      background: #5a8dee !important;
      border-color: #5a8dee !important;
      text-shadow: none;
    }

    .btn-save:hover {
      background: #4a7ddc !important;
    }

    .content-clear {
      clear: both;
    }

    @media(min-width: 1200px) {
      .layout-navbar {
        right: 0;
        z-index: 1030;
      }
    }

    /* language switch in the top bar */
    .lang-switch {
      display: inline-flex !important;
      align-items: center;
      gap: 6px;
      font-weight: 600;
      font-size: 14px;
      padding: 6px 12px !important;
      border: 1px solid rgba(90, 141, 238, .4);
      border-radius: 6px;
      color: #5a8dee !important;
      white-space: nowrap;
    }

    .lang-switch:hover {
      background: #5a8dee;
      color: #fff !important;
    }

    .lang-switch .ar {
      font-family: "IBM Plex Sans Arabic", "Segoe UI", Tahoma, sans-serif !important;
    }

    /* ---------- Arabic: right to left ---------- */
    [dir=rtl] body,
    [dir=rtl] h1, [dir=rtl] h2, [dir=rtl] h3, [dir=rtl] h4, [dir=rtl] h5, [dir=rtl] h6,
    [dir=rtl] .h1, [dir=rtl] .h2, [dir=rtl] .h3, [dir=rtl] .h4, [dir=rtl] .h5, [dir=rtl] .h6,
    [dir=rtl] .breadcrumb li,
    [dir=rtl] .breadcrumb a,
    [dir=rtl] .btn,
    [dir=rtl] .menu-link,
    [dir=rtl] .dropdown-item,
    [dir=rtl] input, [dir=rtl] select, [dir=rtl] textarea, [dir=rtl] button {
      font-family: "IBM Plex Sans Arabic", "Segoe UI", Tahoma, sans-serif !important;
    }

    [dir=rtl] .breadcrumb li.pull-right { float: left; }
    [dir=rtl] .pull-right { float: left !important; }
    [dir=rtl] .pull-left { float: right !important; }
    [dir=rtl] .hundred { float: right; }
    [dir=rtl] .align-right { text-align: left; }
    [dir=rtl] .menu-item, [dir=rtl] .menu-header { text-transform: none; }
    /* the theme also loads plain bootstrap: cancel its left-to-right side margins */
    [dir=rtl] .me-1, [dir=rtl] .me-2, [dir=rtl] .me-3, [dir=rtl] .me-4 { margin-right: 0 !important; }
    [dir=rtl] .ms-1, [dir=rtl] .ms-2, [dir=rtl] .ms-3 { margin-left: 0 !important; }
    [dir=rtl] .ms-auto { margin-left: 0 !important; margin-right: auto !important; }
    [dir=rtl] .nav-btn { margin: 0 0 0 10px !important; }
    [dir=rtl] .nav-btn i, [dir=rtl] .bx-arrow-back { transform: scaleX(-1); }
    [dir=rtl] .table th, [dir=rtl] .table td { text-align: right; }
    [dir=rtl] .table td[style*="text-align:right"], [dir=rtl] .table th[style*="text-align:right"] { text-align: left !important; }
    [dir=rtl] th[style*="padding-right:80px"] { padding-right: 1.25rem !important; padding-left: 80px !important; }
    /* website content typed in English stays left to right, Arabic fields right to left */
    [dir=rtl] .middle-forms input[type=text]:not([dir]),
    [dir=rtl] .middle-forms input[type=url],
    [dir=rtl] .middle-forms input[type=email],
    [dir=rtl] .middle-forms textarea:not([dir]) {
      unicode-bidi: plaintext;
      text-align: start;
    }
    [dir=rtl] .dataTables_wrapper .dataTables_filter { text-align: left; }
    /* links and e-mails always read left to right */
    [dir=rtl] input[type=url], [dir=rtl] input[type=email] { direction: ltr; text-align: left; }
  </style>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <?php $this->load->view('back_office/includes/left_box'); ?>
      <!-- / Menu -->

      <div class="layout-page">
        <!-- Navbar -->
        <?php $this->load->view('back_office/includes/header'); ?>
        <!-- / Navbar -->

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div id="myModal" class="modal fade" tabindex="-1" aria-hidden="true"></div>
            <?php $this->load->view($content); ?>
            <div class="content-clear"></div>
          </div>
          <?php $this->load->view('back_office/includes/footer'); ?>
          <div class="content-backdrop fade"></div>
        </div>
      </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="drag-target"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Theme JS -->
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/js/helpers.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/js/template-customizer.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/js/config.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/libs/popper/popper.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/js/bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/libs/hammer/hammer.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/js/menu.js"></script>
  <script src="<?php echo base_url(); ?>new_theme/assets/js/main1.js"></script>

  <!-- DataTables -->
  <script src="<?php echo base_url(); ?>new_theme/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <script src="<?php echo base_url(); ?>js/jszip/jszip.min.js"></script>
  <script src="<?php echo base_url(); ?>js/jszip/buttons.html5.min.js"></script>
  <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>

  <div id="popup-image" class="popup-image" style="display:none;position:fixed;z-index:99999;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.9);">
    <span class="close-button" style="color:#fff;position:absolute;top:10px;right:25px;font-size:35px;font-weight:bold;cursor:pointer;">&times;</span>
    <img src="" alt="Popup Image" style="margin:10% auto;display:block;max-width:80%;max-height:400px;">
  </div>

  <script>
    // Turn list tables into DataTables (search, export, paging).
    // Skipped when a table has a "No records" row, so DataTables does not warn about column counts.
    $('table.table-striped').each(function() {
      if (!$.fn.DataTable) return;
      var $table = $(this);
      if ($table.find('tbody td[colspan]').length) return;
      var $firstRow = $table.find('tbody tr').first();
      if (!$firstRow.length) return;
      var cols = $firstRow.children('td,th').length;

      // Header/footer rows must span exactly the same number of columns as the body,
      // otherwise DataTables freezes the page. Fix the last cell's colspan of each row.
      var badRow = false;
      $table.find('thead tr, tfoot tr').each(function() {
        var $cells = $(this).children('td,th');
        if (!$cells.length) return;
        var total = 0;
        $cells.each(function() {
          total += parseInt($(this).attr('colspan') || 1, 10);
        });
        if (total == cols) return;
        var $last = $cells.last();
        var fixed = parseInt($last.attr('colspan') || 1, 10) - (total - cols);
        if (fixed < 1) {
          badRow = true;
          return;
        }
        $last.attr('colspan', fixed);
      });
      if (badRow) return;

      $table.DataTable({
        dom: 'lBfrtip',
        order: [], // keep the order from the database (drag & drop sort order)
        lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, '<?php echo bo_t('All'); ?>']
        ],<?php if (bo_is_ar()) { ?>
        language: {
          search: 'بحث:',
          lengthMenu: 'عرض _MENU_',
          info: 'عرض _START_ إلى _END_ من أصل _TOTAL_',
          infoEmpty: 'لا توجد بيانات',
          infoFiltered: '(من أصل _MAX_)',
          zeroRecords: 'لا توجد نتائج مطابقة',
          emptyTable: 'لا توجد بيانات بعد',
          paginate: { first: 'الأولى', last: 'الأخيرة', next: 'التالي', previous: 'السابق' }
        },<?php } ?>
        pageLength: -1,
        columnDefs: [{
          orderable: false,
          targets: [0, -1]
        }],
        buttons: [{
          extend: 'collection',
          className: 'dt-button buttons-collection dropdown-toggle btn btn-label-primary me-2',
          text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block"><?php echo bo_t('Export'); ?></span>',
          buttons: [{
              extend: 'print',
              text: '<i class="bx bx-printer me-1"></i><?php echo bo_t('Print'); ?>',
              className: 'dt-button buttons-print dropdown-item'
            },
            {
              extend: 'csv',
              text: '<i class="bx bx-file me-1"></i><?php echo bo_t('Csv'); ?>',
              className: 'dt-button buttons-csv buttons-html5 dropdown-item'
            },
            {
              extend: 'copy',
              text: '<i class="bx bx-copy me-1"></i><?php echo bo_t('Copy'); ?>',
              className: 'dt-button buttons-copy buttons-html5 dropdown-item'
            }
          ]
        }]
      });
    });

    function popup(imageUrl) {
      $('#popup-image img').attr('src', imageUrl);
      $('#popup-image').css('display', 'block');
    }

    $(function() {
      $('#popup-image .close-button').click(function() {
        $('#popup-image').css('display', 'none');
      });

      if ($.fn.lightBox) {
        $('a.gallery').lightBox({
          imageLoading: '<?php echo base_url(); ?>images/lightbox-ico-loading.gif',
          imageBtnPrev: '<?php echo base_url(); ?>images/lightbox-btn-prev.gif',
          imageBtnNext: '<?php echo base_url(); ?>images/lightbox-btn-next.gif',
          imageBtnClose: '<?php echo base_url(); ?>images/lightbox-btn-close.gif',
          imageBlank: '<?php echo base_url(); ?>images/lightbox-blank.gif'
        });
      }

      $('#checkAllAuto').click(function() {
        $("INPUT[name='cehcklist[]']").prop('checked', $('#checkAllAuto').is(':checked'));
      });

      if ($.fn.datepicker) $('.date').datepicker();
    });
  </script>
</body>

</html>
