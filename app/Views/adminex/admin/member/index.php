<?php

/**
 * @var \Wow\Template\View $this
 */
$this->setLayout(NULL);
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="/themes/adminarea/assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Halaman Login | <?php echo Wow::get("ayar/site_title"); ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/themes/adminarea/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/libs/@form-validation/umd/styles/index.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="/themes/adminarea/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="/themes/adminarea/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="/themes/adminarea/assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/themes/adminarea/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              
              <h4 class="mb-1 pt-2">Selamat Datang <?php echo Wow::get("ayar/site_title"); ?>! 👋</h4>
              <?php
                        if (count($this->get('notifications')) > 0) {
                            $this->renderView("shared/notifications", $this->get('notifications'));
                        }
                        ?>

              <form  class="mb-3" method="POST">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    
                    name="username"
                    placeholder="username"
                    autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="inputRememberPassword" />
                    <label class="form-check-label" for="inputRememberPassword"> Remember Me </label>
                  </div>
                </div>
                <input type="hidden" name="antiForgeryToken" value="<?php echo $_SESSION["AntiForgeryToken"]; ?>">
                                        <?php if (!empty(Wow::get("ayar/GoogleCaptchaSiteKey")) && !empty(Wow::get("ayar/GoogleCaptchaSecretKey"))) { ?>
                                            <div data-theme="dark" class="h-captcha" data-sitekey="<?php echo Wow::get("ayar/GoogleCaptchaSiteKey"); ?>"></div>
                                        <?php } ?>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                </div>
              </form>

              
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="/themes/adminarea/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/popper/popper.js"></script>
    <script src="/themes/adminarea/assets/vendor/js/bootstrap.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="/themes/adminarea/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/themes/adminarea/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="/themes/adminarea/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

    <!-- Main JS -->
    <script src="/themes/adminarea/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="/themes/adminarea/assets/js/pages-auth.js"></script>
  </body>
</html>
