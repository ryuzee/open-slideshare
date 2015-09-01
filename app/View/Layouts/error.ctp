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
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php echo $this->Html->script('jquery-1.10.2.min.js'); ?>
        <?php echo $this->Html->script('bootstrap.min.js'); ?>
        <?php echo $this->Html->script('jquery-ui.min.js'); ?>
        <?php echo $this->Html->css('bootstrap.min.css'); ?>
        <?php echo $this->Html->css('jquery-ui.min.css'); ?>
        <?php echo $this->Html->css('sticky-footer-navbar.css'); ?>
        <?php echo $this->Html->css('jquery.bxslider.css'); ?>
        <?php echo $this->Html->css('custom-theme/jquery-ui-1.10.0.custom.css'); ?>
        <?php echo $this->Html->css('openslideshare.css'); ?>
        <?php echo $this->Html->script('jquery.elastic.js'); ?>
        <?php echo $this->Html->script('jquery.events.input.js'); ?>
        <?php echo $this->Html->script('jquery.bxslider.js?20141218'); ?>
        <?php echo $this->Html->script('jquery.lazyload.js'); ?>
        <?php echo $this->Html->script('jquery.equalheights.min.js'); ?>
        <?php echo $this->Html->script('jquery.sticky-kit.min.js'); ?>
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
        <title>
            <?php echo $cakeDescription ?>:
            <?php echo $this->fetch('title'); ?>
        </title>
        <?php
             echo $this->fetch('meta');
             echo $this->fetch('css');
             echo $this->fetch('script');
         ?>
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
                    <li><a href="/slides/add"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;<?php echo __('Upload'); ?>!!</a></li>


                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<?php echo h(AuthComponent::user('username')); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
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
                    <form class="navbar-form navbar-right" action="/users/login" role="form" method="post">
                        <div class="form-group">
                            <input type="text" name="data[User][username]" placeholder="Username" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="password" name="data[User][password]" placeholder="Password" class="form-control">
                        </div>
                        <input type="hidden" name="return_url" value="<?php echo $_SERVER["REQUEST_URI"]; ?>" />
                        <button type="submit" class="btn btn-success"><?php echo __('Sign in'); ?></button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                    <li><a href="/users/signup"><?php echo __('Signup'); ?></a></li>
                    </ul>
                    <?php endif; ?>
                </div><!--/.navbar-collapse -->
            </div>
        </div>
        <a name="page_top"></a>
        <div class="container" id="main_container" role="main">
            <?php echo $this->Session->flash(); ?>
            <!-- start contents -->
            <?php echo $this->fetch('content'); ?>
            <!-- end contents -->
        </div>

        <div class="footer">
            <div class="container">
                <p class="text-muted">
                &copy; <?php echo date('Y'); ?>&nbsp; Ryuzee. MIT License.
                </p>
            </div>
        </div>


<div id="search_slide_dialog_" title="Search">
    <!-- search form -->
    <?php echo $this->Form->create('Slide', array(
        'url' => array('controller' => 'slides', 'action' => 'search'),
        'inputDefaults' => array(
            'div' => 'form-group',
            'label' => array(
                'class' => 'col col-md-2 control-label'
            ),
            'wrapInput' => 'col col-md-10',
            'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    )); ?>
    <fieldset>
    <?php
    echo $this->Form->input('name', array('required' => false, 'placeholder' => 'Name'));
    echo $this->Form->input('display_name', array('required' => false, 'placeholder' => 'Author'));
    echo $this->Form->input('description', array('required' => false, 'type' => 'text', 'placeholder' => 'Description'));
    echo $this->Form->input('tags', array('required' => false, 'type' => 'text', 'placeholder' => 'Tag'));
    $date_f = $this->Form->value("created_f");
    $date_t = $this->Form->value("created_t");
     echo $this->Form->input('created_f', array('required' => false, 'type' => 'text', 'placeholder' => 'From', 'label' => 'Date(From)', 'class' => 'datepicker_f form-control'));
     echo $this->Form->input('created_t', array('required' => false, 'type' => 'text', 'placeholder' => 'To', 'label' => 'Date(To)', 'class' => 'datepicker_t form-control'));

     ?>
    </fieldset>

    <div class="form-group">
        <div class="col col-md-10 col-md-offset-2">
            <?php echo $this->Form->submit('Search', array(
                'div' => false,
                'class' => 'btn btn-primary'
            )); ?>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
    <!-- end of search form -->
</div>

<script type="text/javascript">
$1102(document).ready(function(){
    $1102(".datepicker_f").datepicker();
    $1102(".datepicker_f").datepicker("option", "dateFormat", 'yy-mm-dd');
    $1102(".datepicker_f").datepicker("setDate", '<?php echo $date_f; ?>');
    $1102(".datepicker_t").datepicker();
    $1102(".datepicker_t").datepicker("option", "dateFormat", 'yy-mm-dd');
    $1102(".datepicker_t").datepicker("setDate", '<?php echo $date_t; ?>');

    $1102("#search_slide_dialog_").dialog({
        autoOpen: false,
        width: 600
    });
    $1102("#open_search_form_").on("click", function() {
        $1102('#search_slide_dialog_').dialog('open');
    });
});
</script>



    </body>
</html>
