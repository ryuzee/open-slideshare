<?php
/**
* @link          http://cakephp.org CakePHP(tm) Project
* @package       app.View.Layouts
* @since         CakePHP(tm) v 0.10.0.1076
*/

$cakeDescription = __d('cake_dev', 'OpenSlideshare');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset() . "\n"; ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo $this->Html->script('jquery-1.10.2.min.js') . "\n"; ?>
        <?php echo $this->Html->script('bootstrap.min.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery-ui.min.js') . "\n"; ?>
        <?php echo $this->Html->css('bootstrap.min.css') . "\n"; ?>
        <?php echo $this->Html->css('jquery-ui.min.css') . "\n"; ?>
        <?php echo $this->Html->css('sticky-footer-navbar.css') . "\n"; ?>
        <?php echo $this->Html->css('jquery.bxslider.css') . "\n"; ?>
        <?php echo $this->Html->css('custom-theme/jquery-ui-1.10.0.custom.css') . "\n"; ?>
        <?php echo $this->Html->css('openslideshare.css?' . date('YmdHis')) . "\n"; ?>
        <?php echo $this->Html->script('jquery.elastic.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery.events.input.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery.bxslider.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery.lazyload.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery.equalheights.min.js') . "\n"; ?>
        <?php echo $this->Html->script('jquery.sticky-kit.min.js') . "\n"; ?>
        <?php echo $this->Html->script('mousetrap.min.js') . "\n"; ?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <script type="text/javascript">
        $1102= jQuery.noConflict(true);
        </script>
        <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0 (<?php echo __('Latest Slides'); ?>)" href="/slides/latest.rss" />
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0 (<?php echo __('Popular Slides'); ?>)" href="/slides/popular.rss" />
        <title><?php echo $cakeDescription ?>:<?php echo $this->fetch('title'); ?></title>
        <?php echo $this->fetch('meta') . "\n"; ?>
        <?php echo $this->fetch('css') . "\n"; ?>
        <?php echo $this->fetch('script') . "\n"; ?>
    </head>
    <body class="openslideshare_body">
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/slides/index"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;<?php echo $cakeDescription; ?></a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo __('Category'); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            <?php foreach ($category as $cat): ?>
                            <li><a href="/categories/view/<?php echo $cat["Category"]["id"]; ?>"><?php echo $cat["Category"]["name"]; ?></a></li>
                            <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-left">
                    <li><a href="/slides/popular"><?php echo __('Popular'); ?></a></li>
                    <li><a href="/slides/latest"><?php echo __('Latest'); ?></a></li>
                    <li><a href="#" id="open_search_form_"><?php echo __('Search'); ?></a></li>
                    </ul>
                    <?php if (AuthComponent::user('username')): ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<?php echo h(AuthComponent::user('username')); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            <li><a href="/slides/add"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;<?php echo __('Upload'); ?>!!</a></li>
                            <li><a href="/users/index"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;<?php echo __('My Slides'); ?></a></li>
                            <li><a href="/users/statistics"><span class="glyphicon glyphicon-signal" aria-hidden="true"></span>&nbsp;<?php echo __('My Statistics'); ?></a></li>
                            <li><a href="/users/edit/<?php echo AuthComponent::user('id'); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<?php echo __('My Account'); ?></a></li>
                            <?php if (AuthComponent::user('admin') === true): ?>
                            <li class="divider"></li>
                            <li><a href="/admin/managements/dashboard"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>&nbsp;<?php echo __('Admin Dashboard'); ?></a></li>
                            <?php endif; ?>
                            <li class="divider"></li>
                            <li><a href="/users/logout?return_url=<?php echo $_SERVER["REQUEST_URI"]; ?>"><?php echo __('Logout'); ?>&nbsp;</a></li>
                            </ul>
                        </li>
                    </ul>
                    <?php else: ?>
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (isset($config["signup_enabled"]) && $config["signup_enabled"] === "1"): ?>
                    <li><a href="/users/signup"><?php echo __('Signup'); ?></a></li>
                    <?php endif; ?>
                    <?php if (isset($config["display_login_link"]) && $config["display_login_link"] === "1"): ?>
                    <li><a href="/users/login"><?php echo __('Signin'); ?></a></li>
                    <?php endif; ?>
                    </ul>
                    <?php endif; ?>
                </div><!--/.navbar-collapse -->
            </div>
        </div>
        <a name="page_top"></a>
        <div class="container" id="main_container" role="main">
            <?php if (isset($custom_contents["center_top"])): ?>
                <div>
                <?php echo $custom_contents["center_top"]; ?>
                </div>
            <?php endif; ?>
            <?php echo $this->Session->flash(); ?>
            <!-- start contents -->
            <?php echo $this->fetch('content'); ?>
            <!-- end contents -->
            <?php if (isset($custom_contents["center_bottom"])): ?>
                <div>
                <?php echo $custom_contents["center_bottom"]; ?>
                </div><br />
            <?php endif; ?>
        </div>

        <div class="footer">
            <div class="container">
                <p class="text-muted">
                &copy; <?php echo date('Y'); ?>&nbsp; Ryuzee. MIT License.
                </p>
            </div>
        </div>
        <?php echo $this->element('search_dialog'); ?>
    </body>
</html>
