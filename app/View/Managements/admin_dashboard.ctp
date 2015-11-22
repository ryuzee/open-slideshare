<h3><?php echo __('Admin Dashboard'); ?></h3>

<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-file-image-o"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"># of Slides</span>
        <span class="info-box-number"><?php echo $slide_count; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"># of Users</span>
        <span class="info-box-number"><?php echo $user_count; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-green"><i class="ion ion-eye"></i></span>
      <div class="info-box-content">
          <span class="info-box-text"># of Page View</span>
          <span class="info-box-number"><?php echo $page_view; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-download-outline"></i></span>
      <div class="info-box-content">
          <span class="info-box-text"># of Download</span>
          <span class="info-box-number"><?php echo $download_count; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="panel panel-default">
    <!-- Table -->
    <table class="table table-striped table-bordered small">
        <thead>
            <tr>
                <th class="col-md-1"><?php echo __('# of Conversion Failed'); ?></th>
                <th class="col-md-1"><?php echo __('# of Comments'); ?></th>
                <th class="col-md-1"><?php echo __('Embedded View'); ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $conversion_failed_count; ?>&nbsp;</td>
                <td><?php echo $comment_count; ?>&nbsp;</td>
                <td><?php echo $embedded_view; ?>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- TABLE: LATEST ORDERS -->
<div class="row">
  <div class="col-md-6 col-sm-12 col-xs-24">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo __('Latest Published Slides'); ?></h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin">
            <thead>
              <tr>
                <th>ID</th>
                <th><?php echo __('Name'); ?></th>
                <th><?php echo __('Username'); ?></th>
                <th><?php echo __('Created'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($latest_slides as $slide): ?>
              <tr>
                <td><a href="/slides/view/<?php echo $slide['Slide']['id']; ?>"><?php echo $slide['Slide']['id']; ?></a></td>
                <td><?php echo h($slide['Slide']['name']); ?></td>
                <td><?php echo h($slide['User']['display_name']); ?></td>
                <td><?php echo $this->Time->format($slide['Slide']['created'], '%Y/%m/%d'); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="/admin/managements/slide_list" class="btn btn-sm btn-default btn-flat pull-right"><?php echo __('View All Slides'); ?></a>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
  </div><!-- /.col -->
  <div class="col-md-6 col-sm-12 col-xs-24">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo __('Popular Slides'); ?></h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table class="table no-margin">
            <thead>
              <tr>
                <th>ID</th>
                <th><?php echo __('Name'); ?></th>
                <th><?php echo __('Username'); ?></th>
                <th><?php echo __('Created'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($popular_slides as $slide): ?>
              <tr>
                <td><a href="/slides/view/<?php echo $slide['Slide']['id']; ?>"><?php echo $slide['Slide']['id']; ?></a></td>
                <td><?php echo h($slide['Slide']['name']); ?></td>
                <td><?php echo h($slide['User']['display_name']); ?></td>
                <td><?php echo $this->Time->format($slide['Slide']['created'], '%Y/%m/%d'); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="/admin/managements/slide_list" class="btn btn-sm btn-default btn-flat pull-right"><?php echo __('View All Slides'); ?></a>
      </div><!-- /.box-footer -->
    </div><!-- /.box -->
  </div><!-- /.col -->
</div>
