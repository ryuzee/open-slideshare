        <h3><?php echo h($title); ?></h3>

        <?php echo $this->element('slide_thumbnail', array('slides' => $slides, 'column_class' => $column_class)); ?>

        <div>
            <!-- Paging -->
            <?php echo $this->Paginator->pagination(array(
            'ul' => 'pagination pagination-lg'
            )); ?>
        </div>

        <script type="text/javascript">
        $1102(window).load(function () {
            $1102('.<?php echo $column_class_primary; ?>').equalHeights();
        });
        </script>
