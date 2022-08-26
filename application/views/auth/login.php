<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIPAS | Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <meta name="description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta property="og:url" content="http://demo.madebytilde.com/elephant">
    <meta property="og:type" content="website">
    <meta property="og:title" content="The fastest way to build Modern Admin APPS for any platform, browser, or device.">
    <meta property="og:description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta property="og:image" content="http://demo.madebytilde.com/elephant.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@madebytilde">
    <meta name="twitter:creator" content="@madebytilde">
    <meta name="twitter:title" content="The fastest way to build Modern Admin APPS for any platform, browser, or device.">
    <meta name="twitter:description" content="Elephant is an admin template that helps you build modern Admin Applications, professionally fast! Built on top of Bootstrap, it includes a large collection of HTML, CSS and JS components that are simple to use and easy to customize.">
    <meta name="twitter:image" content="http://demo.madebytilde.com/elephant.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url(); ?>assets/admin/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/admin/img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/admin/img/logo.png" sizes="16x16">
    <link rel="manifest" href="<?= base_url(); ?>assets/admin/manifest.json">
    <link rel="mask-icon" href="<?= base_url(); ?>assets/admin/safari-pinned-tab.svg" color="#0288d1">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/Admin_new.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/vendor.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/elephant.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/admin/css/login-3.min.css">
  </head>
  <body>
    <div class="login-root">
      <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
        <div class="loginbackground box-background--white padding-top--64">
          <div class="loginbackground-gridContainer">
            <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
              <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
              </div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
              <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
              <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
              <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
              <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
              <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
              <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
              <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
            </div>
            <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
              <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
            </div>
          </div>
        </div> 
        <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
          <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
            <h1><a href="http://blog.stackfindover.com/" rel="dofollow">E-OFFICE AKADEMI KEPOLISIAN</a></h1>
          </div>
          <div class="formbg-outer">
            <div class="formbg">
              <div class="formbg-inner padding-horizontal--48">
                  <a class="login-brand" href="#">
                    <img class="img-responsive" src="<?= base_url(); ?>assets/admin/img/logo.png" alt="Elephant">
                  </a>
                <span class="padding-bottom--15">Sign In</span>
                <form method="post" id="stripe-login">
                  <div class="field padding-bottom--24">
                    <label>Username</label>
                    <input type="text" name="username" spellcheck="false" autocomplete="off" data-msg-required="Please enter your username." required>
                  </div>
                  <div class="field padding-bottom--24">
                      <label>Password</label>
                      <input type="password" name="password" minlength="3" data-msg-required="Please enter your password." required>
                  </div>
                  <div class="field padding-bottom--24">
                    <input type="submit" name="submit" value="Continue">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <!-- <body>
    <div class="login">
    <?= $this->session->flashdata('message');?>
      <div class="login-body">
        <a class="login-brand" href="#">
          <img class="img-responsive" src="<?= base_url(); ?>assets/admin/img/logo.png" alt="Elephant">
        </a>
        <center><h3>SIGN IN</h3></center> 
        <div class="login-form">
          <form method="post">
            <div class="md-form-group md-label-floating">
              <input class="md-form-control" type="text" name="username" spellcheck="false" autocomplete="off" data-msg-required="Please enter your username." required>
              <label class="md-control-label">Username</label>
            </div>
            <div class="md-form-group md-label-floating">
              <input class="md-form-control" type="password" name="password" minlength="3" data-msg-minlength="Password must be 3 characters or more." data-msg-required="Please enter your password." required>
              <label class="md-control-label">Password</label><br>
              <center>
              <a href="<?= base_url(); ?>auth/forgotPassword">Forgot password?</a></center>
            </div>
            <button class="btn btn-primary btn-block" type="submit">Sign in</button>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      $('#notifikasi').slideDown('slow').delay(4000).slideUp('slow');
    </script>
    
    <script src="<?= base_url(); ?>assets/admin/js/vendor.min.js"></script>
    <script src="<?= base_url(); ?>assets/admin/js/elephant.min.js"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-83990101-1', 'auto');
      ga('send', 'pageview');
    </script>
  </body> -->
</html>