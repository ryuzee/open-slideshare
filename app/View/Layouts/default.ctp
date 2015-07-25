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
        <?php echo $this->Html->css('openslideshare.css'); ?>
        <?php echo $this->Html->css('jquery.mentionsInput.css'); ?>
        <?php echo $this->Html->css('jquery.bxslider.css'); ?>
        <?php echo $this->Html->css('custom-theme/jquery-ui-1.10.0.custom.css'); ?>
        <?php echo $this->Html->script('underscore-min.js'); ?>
        <?php echo $this->Html->script('jquery.elastic.js'); ?>
        <?php echo $this->Html->script('jquery.events.input.js'); ?>
        <?php echo $this->Html->script('jquery.mentionsInput.js'); ?>
        <?php echo $this->Html->script('jquery.bxslider.js?20141218'); ?>
        <?php echo $this->Html->script('jquery.lazyload.js'); ?>
        <?php echo $this->Html->script('jquery.equalheight.min.js'); ?>

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
    <body style="background-color:#ddd;">

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
                        <li><a href="#" id="open_search_form_">Search</a></li>
                    </ul>
                    <?php if (AuthComponent::user('username')): ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/slides/add"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;Upload!!</a></li>


                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;<?php echo h(AuthComponent::user('username')); ?><span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="/users/index"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;My Slides</a></li>
                                <li><a href="/users/edit/<?php echo AuthComponent::user('id'); ?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;My Account</a></li>
                                <li class="divider"></li>
                                <li><a href="/users/logout">Logout&nbsp;</a></li>
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
                        <button type="submit" class="btn btn-success">Sign in</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/users/signup">Signup</a></li>
                    </ul>
                    <?php endif; ?>
                </div><!--/.navbar-collapse -->
            </div>
        </div>

        <div class="container" style="max-width:1024px; background-color:#ddd; padding-top: 60px;; margin-top:1em; auto; border:1px solid #ddd;" role="main">
            <!--<header>--><?php echo $this->Session->flash(); ?><!--</header>-->
            <!-- start contents -->
            <?php echo $this->fetch('content'); ?>
            <!-- end contents -->
        </div>

        <?php if(Configure::read('debug') >= 2): ?>
        <div class="container">
            <div class="panel panel-default" style="padding:10px;">
                <?php echo $this->element('sql_dump'); ?>
            </div>
        </div>
        <?php endif; ?>

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
        'url' => array('controller' => 'slides', 'action' => 'index'),
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
$(function(){
    $(".datepicker_f").datepicker();
    $(".datepicker_f").datepicker("option", "dateFormat", 'yy-mm-dd');
    $(".datepicker_f").datepicker("setDate", '<?php echo $date_f; ?>');
    $(".datepicker_t").datepicker();
    $(".datepicker_t").datepicker("option", "dateFormat", 'yy-mm-dd');
    $(".datepicker_t").datepicker("setDate", '<?php echo $date_t; ?>');

    $("#search_slide_dialog_").dialog({
        autoOpen: false,
        width: 600
    });
    $("#open_search_form_").on("click", function() {
        $('#search_slide_dialog_').dialog('open');
    });
});
</script>



    </body>
</html>
