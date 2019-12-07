<!-- UIkit Stub-->
<link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/uikit/css/uikit.min.css"/>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit.min.js"></script>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit-icons.min.js"></script>
<?php require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');
if(isset($_POST['button_create'])){
    support_model::insertIssue($_POST['title'], $_POST['category'], $_POST['supportdesc']);
};
?>
   <div class="uk-container">
    <script src="/wp-content/plugins/Atavism-Integration/core/tinymce/tinymce.min.js"></script>
    <script>tinymce.init({
        selector: '.tinyeditor',
        language: 'en',
        menubar: false,
        plugins: ['advlist autolink autosave link lists charmap preview hr searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime contextmenu directionality emoticons textcolor paste fullpage textcolor colorpicker textpattern'],
        toolbar: 'outdent indent | bullist numlist | undo redo'});
    </script>
       <?php if(isset($_REQUEST['new'])) {?>
               <div class="uk-dialog">
                   <div class="uk-header">
                       <h2 class="uk-title uk-text-uppercase"><i class="fas fa-pencil-alt"></i> Create Ticket</h2>
                   </div>
                   <form action="" method="post" accept-charset="utf-8" autocomplete="off">
                       <div class="uk-body">
                           <div class="uk-margin">
                               <label class="uk-form-label uk-text-uppercase">Title</label>
                               <div class="uk-form-controls">
                                   <div class="uk-inline uk-width-1-1">
                                       <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: pencil"></span>
                                       <input class="uk-input" name="title" required type="text" placeholder="Title">
                                   </div>
                               </div>
                           </div>
                           <div class="uk-margin">
                               <label class="uk-form-label uk-text-uppercase">Category</label>
                               <div class="uk-form-controls">
                                   <select class="uk-select" id="form-stacked-select" name="category">
                                       <option value="1">Website</option>
                                       <option value="2">Quests</option>
                                       <option value="3">Items</option>
                                       <option value="4">Classes</option>
                                       <option value="5">Creatures</option>
                                       <option value="6">Exploits/Usebugs</option>
                                       <option value="7">Instances</option>
                                       <option value="8">Guilds</option>
                                       <option value="9">Friends</option>
                                       <option value="10">Skills</option>
                                       <option value="11">Other</option>
                                   </select>
                               </div>
                           </div>
                           <div class="uk-margin">
                               <label class="uk-form-label uk-text-uppercase">Description</label>
                               <div class="uk-form-controls">
                                   <div class="uk-width-1-1">
                                       <textarea class="tinyeditor" name="supportdesc" rows="10" cols="80"></textarea>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="uk-footer uk-text-right actions">
                           <button class="uk-button uk-button-default uk-close" type="button">Cancel</button>
                           <button class="uk-button uk-button-primary" type="submit" name="button_create">Create</button>
                       </div>
                   </form>
               </div>
       <?php } ?>
<?php
if (is_user_logged_in() && !isset($_REQUEST['new'])) : ?>
       <?php
	   $results = support_model::getSupportTickets();
	   ?>
        <div class="uk-space-xlarge"></div>
        <div class="uk-grid uk-grid-large" data-uk-grid>
            <div class="uk-width-1-6@l"></div>
            <div class="uk-width-4-6@l">
                <div class="uk-principal-title uk-text-uppercase uk-text-center uk-"><i class="fas fa-ticket-alt"></i>Support Tickets</div>
                <table class="uk-table uk-table-divider">
                    <thead>
                        <tr>
                            <th class="uk-text-white"><i class="fas fa-book"></i> ID</th>
                            <th class="uk-text-center uk-text"><i class="fas fa-bookmark"></i> Title</th>
                            <th class="uk-text-center uk-text"><i class="far fa-clock"></i> Date</th>
                            <th class="uk-text-center uk-text"><i class="fas fa-info-circle"></i> Category</th>
                            <th class="uk-text-center uk-text"><i class="fas fa-info-circle"></i> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
						echo($results);
						?>
                    </tbody>
                </table>
               <div class="uk-space-small"></div>
                <?php if (is_user_logged_in()) { ?>
                    <div class="space-adaptive-small"></div>
                    <div class="uk-margin uk-text-center">
                        <a href="/wp-content/plugins/atavism-integration/tickets.php?new">
                            <span class="uk-button">New Ticket</span>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="uk-width-1-6@l"></div>
        </div>
	   </div>
</div>
<?php endif ?>


