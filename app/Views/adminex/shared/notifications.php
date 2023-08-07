<?php
    /**
     * @var array $model
     */
    if(empty($model)) {
        return;
    }
?>
    <div class="cl10"></div>
        <?php
            foreach($model as $notification) {
                /**
                 * @var \App\Models\Notification $notification
                 */
                ?>
                <div class="alert alert-<?php echo $notification->type; ?> alert-dismissible fade show text-start">
                    <?php if($notification->closable) { ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php } ?>
                    <?php if(!empty($notification->title)) {
                        ?><h4 class="alert-heading mb-10"><i class="fa <?php switch($notification->type) {
                            case $notification::PARAM_TYPE_DANGER:
                                echo "fas fa-times";
                                break;
                            case $notification::PARAM_TYPE_INFO:
                                echo "fa-info";
                                break;
                            case $notification::PARAM_TYPE_WARNING:
                                echo "fa-warning";
                                break;
                            case $notification::PARAM_TYPE_SUCCESS:
                                echo "fa-check";
                                break;
                            default:
                                echo "";
                        } ?>"></i> <?php echo $notification->title; ?></h4><?php } ?>
                    <?php if(!empty($notification->messages)) { ?>
                        <p class="mb-10">
                            <?php $intLoop = 0;
                                foreach($notification->messages as $message) {
                                    $intLoop++;
                                    if($intLoop > 1) {
                                        echo "<br />";
                                    }
                                    echo $message;
                                }
                            ?>
                        </p>
                    <?php } ?>
                    <?php if(!empty($notification->buttons)) { ?>
                        <p>
                            <?php foreach($notification->buttons as $button) {
                                echo $button . " ";
                            } ?>
                        </p>
                    <?php } ?>
                </div>
                <?php
            }
        ?>