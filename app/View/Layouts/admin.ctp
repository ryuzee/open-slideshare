<?php
$cakeDescription = __d('cake_dev', 'OpenSlideshare');
?>
<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->Html->charset() . "\n"; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $cakeDescription ?>:<?php echo $this->fetch('title'); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php echo $this->Html->css('bootstrap.min.css') . "\n"; ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/mc/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/mc/css/skins/skin-blue.min.css">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php echo $this->Html->script('mousetrap.min.js') . "\n"; ?>
    <?php echo $this->fetch('meta') . "\n"; ?>
    <?php echo $this->fetch('css') . "\n"; ?>
    <?php echo $this->fetch('script') . "\n"; ?>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="/admin/managements/dashboard" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>O</b>SM</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>OpenSlideshare</b>MC</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs"><?php echo h(AuthComponent::user('username')); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-6 text-center">
                      <a href="/users/statistics"><?php echo __('My Statistics'); ?></a>
                    </div>
                    <div class="col-xs-6 text-center">
                        <a href="/users/<?php echo AuthComponent::user('id'); ?>"><?php echo __('My Slides'); ?></a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a class="btn btn-default btn-flat" href="/users/edit/<?php echo AuthComponent::user('id'); ?>"><?php echo __('My Account'); ?></a>
                    </div>
                    <div class="pull-right">
                      <a class="btn btn-default btn-flat" href="/users/logout?return_url=/slides/index"><?php echo __('Logout'); ?></a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

          <!-- search form (Optional) -->
          <form action="/admin/managements/slide_list" method="post" class="sidebar-form">
            <div class="input-group">
              <input type="hidden" name="_method" value="POST"/>
              <input type="text" name="data[Slide][name]" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->

          <!-- Sidebar Menu -->
          <ul class="sidebar-menu">
            <li class="header">Admin Menu</li>
            <li><a href="/admin/managements/dashboard"><i class="fa fa-link"></i> <span><?php echo __('Admin Dashboard'); ?></span></a></li>
            <li><a href="/admin/managements/slide_list"><i class="fa fa-link"></i> <span><?php echo __('Slide List'); ?></span></a></li>
            <li><a href="/admin/managements/user_list"><i class="fa fa-link"></i> <span><?php echo __('User List'); ?></span></a></li>
            <li><a href="/admin/managements/site_setting"><i class="fa fa-link"></i> <span><?php echo __('Site Settings'); ?></span></a></li>
            <li><a href="/admin/managements/custom_contents_setting"><i class="fa fa-link"></i> <span><?php echo __('Custom Contents Settings'); ?></span></a></li>
            <li class="header">Public Site</li>
            <li><a href="/slides/index"><i class="fa fa-link"></i> <span><?php echo __('Slide List'); ?></span></a></li>
            <li><a href="/slides/popular"><i class="fa fa-link"></i> <span><?php echo __('Popular'); ?></span></a></li>
            <li><a href="/slides/latest"><i class="fa fa-link"></i> <span><?php echo __('Latest'); ?></span></a></li>
            <li class="treeview">
            <a href="#"><i class="fa fa-link"></i> <span><?php echo __('Category'); ?></span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <?php foreach ($category as $cat): ?>
                <li><a href="/categories/view/<?php echo $cat["Category"]["id"]; ?>"><?php echo $cat["Category"]["name"]; ?></a></li>
                <?php endforeach; ?>
              </ul>
            </li>
          </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <!-- Your Page Content Here -->
          <?php echo $this->Session->flash(); ?>
          <!-- start contents -->
          <?php echo $this->fetch('content'); ?>
          <!-- end contents -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            See <a href="https://github.com/ryuzee/open-slideshare/" target="_blank">project page</a>.
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2015 Ryuzee. </strong> MIT License.
      </footer>
    </div><!-- ./wrapper -->

    <!-- REQUIRED JS SCRIPTS -->

    <!-- jQuery 2.1.4 -->
    <script src="/mc/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <?php echo $this->Html->script('bootstrap.min.js') . "\n"; ?>
    <!-- AdminLTE App -->
    <script src="/mc/js/app.min.js"></script>

  </body>
</html>
