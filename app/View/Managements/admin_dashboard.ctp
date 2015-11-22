<h3><?php echo __('Admin Dashboard'); ?></h3>

<!-- Info boxes -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"># of Slides</span>
                  <span class="info-box-number"><?php echo $slide_count; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text"># of Users</span>
                  <span class="info-box-number"><?php echo $user_count; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"># of Page View</span>
                    <span class="info-box-number"><?php echo $page_view; ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
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
