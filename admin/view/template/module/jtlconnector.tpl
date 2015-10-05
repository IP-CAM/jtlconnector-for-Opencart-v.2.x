<?= $header; ?><?= $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-connector" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?= $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $text_info; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-connector"
                      class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?= $text_url ?></label>

                        <div class="col-sm-10">
                            <p><?= $url ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?= $text_version ?></label>

                        <div class="col-sm-10">
                            <p><?= $version ?></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="connector-password"><?= $text_password ?></label>

                        <div class="col-sm-10">
                            <p><?= $connector_password ?></p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $text_requirements; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status"><?= $text_php_version ?></label>

                            <div class="col-sm-10">
                                <?php if ($php_version): ?>
                                <p class="text-success"><span class="fa fa-check"></span></p>
                                <?php else: ?>
                                <p class="text-danger"><span class="fa fa-times"></span></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $text_write_access; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <?php foreach($write_access as $folder => $success): ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status"><?= $folder; ?></label>

                            <div class="col-sm-10">
                                <?php if ($success): ?>
                                <p class="text-success"><span class="fa fa-check"></span></p>
                                <?php else: ?>
                                <p class="text-danger"><span class="fa fa-times"></span></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .col-sm-10 > p {
        margin-bottom: 0;
        padding-top: 9px;
    }
</style>
<?= $footer; ?>