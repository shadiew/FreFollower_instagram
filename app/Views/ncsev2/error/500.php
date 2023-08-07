<?php

/**
 * @var \Wow\Template\View $this
 * @var array              $model
 * @var Throwable          $e
 */
$this->set("title", $this->translate("error/500/title"));
$this->response->status(500);
$e = $model["error"];
$this->setLayout(NULL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->section("section_head"); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->renderView("layout/inc/header-assets"); ?>
    <?php $this->show(); ?>
    <?php $this->renderView("layout/inc/header-meta"); ?>
</head>


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php $this->renderView("layout/inc/header-nav"); ?>
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">500 Internal Server Error</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active">500</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img src="/themes/ncse/images/500.svg" width="300px" class="img-fluid" />
                                    <h2 class="text-uppercase">
                                        <?php echo $this->translate("error/500/title"); ?>!</h2>
                                    <p>
                                        <?php echo $this->translate("error/500/description"); ?>
                                    </p>
                                    <div class="d-block">
                                        <a class="btn btn-danger" href="javascript:void(0);" onclick="document.getElementById('divErrorDetails').style.display='block';"><?php echo $this->translate("error/500/see_details"); ?></a>
                                        <a class="btn btn-primary" href="<?php echo Wow::get("ayar/group_telegram"); ?>" target="_blank">
                                            <i class="fab fa-telegram-plane"></i> Send Report</a>

                                    </div>
                                    <div class="small text-start mt-4" style="display: none;" id="divErrorDetails">
                                        <?php if ($e instanceof Throwable || $e instanceof Exception) { ?>
                                            <h3><?php echo $e->getMessage(); ?> (<?php echo $e->getCode(); ?>)</h3>
                                            <code><?php echo $e->getTraceAsString(); ?></code>
                                        <?php } else { ?>
                                            <p><?php echo $this->translate("error/500/no_details"); ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="py-2">
                                        <a href="javascript:history.go(-1)" class="btn btn-primary">
                                            <i class="fas fa-arrow-left"></i>
                                            Back</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php $this->renderView("layout/inc/footer"); ?>
    </div>
</body>

</html>