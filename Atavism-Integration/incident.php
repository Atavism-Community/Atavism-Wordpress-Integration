<!-- UIkit Stub-->
<link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/uikit/css/uikit.min.css"/>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit.min.js"></script>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit-icons.min.js"></script>
<?php
require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
$id = get_current_user_id();
if (empty(get_userdata($id)->user_login)){
    exit();
}
if(isset($_REQUEST['ticket'])) {
    $idlink = $_REQUEST['ticket'];
}
//this next if checks if the ticket is a valid number
if(!support_model::getAuthor($idlink)){
    exit();
}
//this only lets users view their own tickets unless they are a WP admin
$author = get_current_user_id();
if(!current_user_can('administrator')) {
    if ($author != support_model::getAuthor($idlink)) {
        exit();
    }
}
if (isset($_POST['changePriory'])) {
    $value = $_POST['prioryValue'];
    support_model::changePriority($idlink, $value);
}
if (isset($_POST['changeStatus'])) {
    $value = $_POST['StatusValue'];
    support_model::changeStatus($idlink, $value);
}
if (isset($_POST['changetypes'])) {
    $value = $_POST['typesValue'];
    support_model::changeCategory($idlink, $value);
}
if (isset($_POST['btn_closeincident'])) {
    support_model::closeIssue($idlink);
}
$status = support_model::getStatus(support_model::getStatusID($idlink));
$closestatus = support_model::closeStatus($idlink);
$author = get_user_by('id',support_model::getAuthor($idlink));

?>
    <div class="uk-container">
        <div class="uk-space-xlarge"></div>
        <div class="uk-grid uk-grid-large" data-uk-grid>
            <div class="uk-width-1-6@l"></div>
            <div class="uk-width-3-6@l">
                <div>
                   <div class="uk-principal-title uk-text-uppercase uk-text-center uk-text"><i class="fas fa-ticket-alt"></i>Support Ticket #<?=$idlink?></div>
                    <h3 class="uk-text"> <?= support_model::getTitleIssue($idlink); ?></h3>
                    <p><?= support_model::getDescIssue($idlink); ?></p>
                    <div class="uk-column-1-3 uk-column-divider">
                        <p><i class="fas fa-list"></i>Category: <span class="uk-label"><?= support_model::getCategory(support_model::getCategoryID($idlink)); ?></span></p>
                        <? if( current_user_can('administrator')) : ?>
                        	<p><i class="fas fa-exclamation-circle"></i> Priority: <span class="uk-label uk-label"><?= support_model::getPriority(support_model::getPriorityID($idlink)); ?></span></p>
                        <? endif ?>
                        <p><i class="far fa-clock"></i>Date: <span class="uk-label"><?= date('Y-m-d', support_model::getDate($idlink)) ?></span></p>
                    </div>
                    <div class="uk-column-1-3 uk-column-divider">
                        <p><i class="fas fa-tags"></i>
                            Status:
                            <?php if($closestatus) {
                                echo '<span class="uk-label uk-label-danger">Closed - '.$status.'</span>';
                            }else{
                                echo '<span class="uk-label uk-label-success">Open - '.$status.'</span>';
                            }
                          ?></span></p>
                        <p><i class="far fa-user-circle"></i> Author: <span class="uk-label"><?= $author->user_login ?></span></p>
                    </div>
                </div>
                <hr>
                <? if( current_user_can('administrator')) : ?>
                    <div>
                        <div class="uk-column-1-3 uk-column-divider">
                            <div>
                                <div class="uk-margin">
                                    <form method="post" action="">
                                        <div class="uk-form-controls">
                                            <select class="uk-select uk-form-width-medium" id="form-stacked-select" name="prioryValue">
                                                <?php foreach(support_model::getPriorityGeneral() as $priory) { ?>
                                                    <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <br>
                                        <button class="uk-button uk-button-primary uk-width-1-1" type="submit" name="changePriory">Change Priority</button>
                                    </form>
                                </div>
                            </div>
                            <div>
                                <div class="uk-margin">
                                    <form method="post" action="">
                                        <div class="uk-form-controls">
                                            <select class="uk-select uk-form-width-medium" id="form-stacked-select" name="StatusValue">
                                                <?php foreach(support_model::getStatusGeneral() as $priory) { ?>
                                                    <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <br>
                                        <button class="uk-button uk-button-primary uk-width-1-1" type="submit" name="changeStatus">Change Status</button>
                                    </form>
                                </div>
                            </div>
                            <div>
                                <div class="uk-margin">
                                    <form method="post" action="">
                                        <div class="uk-form-controls">
                                            <select class="uk-select uk-form-width-medium" id="form-stacked-select" name="typesValue">
                                                <?php foreach(support_model::getCategoryGeneral() as $priory) { ?>
                                                    <option value="<?= $priory->id ?>"><?= $priory->title ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <br>
                                        <button class="uk-button uk-button-primary uk-width-1-1" type="submit" name="changetypes">Change Category</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                        <form method="post" action="">
                            <button type="submit" name="btn_closeincident" class="uk-label uk-label-danger uk-width-1-1">Close Ticket</button>
                        </form>
                    </div>
                <? endif ?>
            </div>
            <div class="uk-width-1-5@l"></div>
        </div>