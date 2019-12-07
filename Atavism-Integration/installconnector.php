
<?php
/*
    Copyright 2012  Scott Meadows  (email: smeadows0155@yahoo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
$filename = 'verify.php';
if(isset($_POST['button_ok'])) {
	echo '<script>window.location.href = "/wp-admin/";</script>';
}
    if(isset($_POST['button_startinstall'])) {
        $db_host = $_POST['db_host'];
        $public_ip = $_POST['public_ip'];
        $db_user = $_POST['db_user'];
	    $db_pass = $_POST['db_pass'];
        $db_schema = $_POST['db_schema'];
        $db_port = $_POST['db_port'];
	    $fileverify = file_get_contents("verify.php.dist");
        $verifysearch = array(
            'public_ip',
            'db_host',
            'db_user',
            'db_pass',
            'db_schema',
            'db_port',
        );
        $verifyreplace = array(
            $public_ip,
            $db_host,
            $db_user,
            $db_pass,
            $db_schema,
            $db_port
        );
//verify.php config and replace
	       $filename = '/verify.php';
        if (!file_exists($filename)) {
	           $newverify  = str_replace( $verifysearch, $verifyreplace, $fileverify );
	           $openverify = fopen( "verify.php.dist", "w" );
	           fwrite( $openverify, $newverify );
	           fclose( $openverify );
	           rename( "verify.php.dist", "verify.php" );
	            if ( $error == false ) {
	    	        echo '<script>window.location.href = "/wp-admin/";</script>';
	            } else
	                {
	    	        echo 'Installation Failed! There were errors with your verify configuration.';
	    	            exit();
	                }
        } else
            {
                echo 'verify.php is configured and is in your atavism-integration plugin directory.. To reconfigure the php connector, manually edit the file.';
                exit();
            }
        }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Installation | PHP Remote Account Connector for Atavism</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/css/install.css">

    <!-- UIkit -->
    <link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/uikit/css/uikit.min.css"/>
    <script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit.min.js"></script>
    <script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit-icons.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/fontawesome/css/fontawesome-all.css">

    <!-- JQuery -->
    <script src="/wp-content/plugins/Atavism-Integration/core/js/jquery-3.3.1.min.js"></script>
</head>
<?php if (!file_exists($filename)) :?>
<body>
    <div class="uk-section-primary">
        <div class="uk-section uk-section-xsmall uk-flex" uk-height-viewport="offset-top: true; offset-bottom: true">
            <div class="uk-container">
                <div class="uk-width-3-4"></div>
                <div class="uk-width-2-6">
                    <h3 class="uk-text-center"><span class="uk-text">Welcome to the </span><strong>PHP remote account connector</strong> installation</span></h3>
                    <p class="uk-text-center"><img class="uk-border" src="images/refresh.png" width="100" height="100" alt="" uk-scrollspy="cls: uk-animation-fade; delay: 400; repeat: true"></p>
                    <p class="uk-text-center">We are pleased to present the <strong> Remote PHP Authenticator </strong> for <strong>Atavism</strong>! Please see instructions at the <a href="http://wiki.atavismonline.com/">Atavism Wiki</a>  for how to use this with your Atavism Deployment.</p>
                    <div class="uk-card uk-card-secondary uk-card-hover uk-card-body uk-light uk-padding">
                        <form action="" method="POST" accept-charset="utf-8" autocomplete="off">
                            <h3 class="uk-card-title uk-text-uppercase uk-text-bold uk-text-center"><i class="fas fa-wrench"></i>Settings</h3>
                            <div class="uk-text-center">
                            </div>
                            <hr class="uk-hr">
                            <?php if(isset($_GET['continue'])) { ?>
                                <div class="uk-child-width-1-1@s" uk-grid>
                                    <div>
                                        <div class="uk-alert-success" uk-alert>
                                            <p class="uk-text-center uk-text-bold"><i class="far fa-check-circle fa-lg"></i> Installation is complete!</p>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="uk-child-width-1-1@s" uk-grid>
                                    <div>
                                        <div class="uk-margin">
                                    <div>
                                        <div class="uk-margin">
                                            <div class="uk-grid-small" uk-grid>
                                                <div class="uk-inline uk-width-1-2@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Wordpress</strong> DB Host</label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="db_host" type="text" placeholder="Example: localhost" required>
                                                    </div>
                                                </div>

                                                <div class="uk-inline uk-width-1-2@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Wordpress</strong> DB Schema</label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="db_schema" type="text" placeholder="Example: wordpress" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <div class="uk-grid-small" uk-grid>
                                                <div class="uk-inline uk-width-1-2@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Wordpress</strong> DB Username</label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="db_user" type="text" placeholder="Example: root" required>
                                                    </div>
                                                </div>
                                                <div class="uk-inline uk-width-1-2@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Wordpress</strong> DB Password</label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="db_pass" type="password" placeholder="Example: atavism" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-margin">
                                            <div class="uk-grid-small" uk-grid>
                                                <div class="uk-inline uk-width-1-1@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Wordpress</strong> DB Port</label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="db_port" type="text" placeholder="Example: 3306" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										<div class="uk-margin">
                                            <div class="uk-grid-small" uk-grid>
                                                <div class="uk-inline uk-width-1-1@s">
                                                    <label class="uk-form-label uk-text-uppercase"><strong>Atavism Auth Server Public IP</strong></label>
                                                    <div class="uk-form-controls">
                                                        <input class="uk-input" name="public_ip" type="text" placeholder="Example: 35.105.233.143" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php } ?>
                            <br>
                            <?php if(isset($_GET['continue'])) { ?>
                                <div class="uk-margin">
                                    <div class="uk-form-controls">
                                        <button class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom" type="submit" name="button_ok"><i class="fas fa-cog fa-spin"></i> Continue</button>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="uk-margin">
                                    <div class="uk-form-controls">
                                        <button class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom" type="submit" name="button_startinstall"><i class="fas fa-cog fa-spin"></i> Start Installation</button>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
                <div class="uk-width-1-6"></div>
            </div>
        </div>
        <div class="uk-section-small">
            <div class="uk-container uk-container-expand uk-text-center uk-position-relative">
                <ul class="uk-subnav uk-flex-inline uk-flex-center uk-margin-remove-bottom" uk-margin>
                    <li>
                        <a target="_blank" href="https://discord.gg/M6VwFFz"><i class="fab fa-discord fa-2x"></i> Wordpress integration for Atavism by SMZERO</a>
                    </li>
                </ul>
                <p>This product is brought to you <strong>in partnership</strong> with <strong>Dragonsan Studios.</strong></p>
            </div>
        </div>
    </div>
</body>
<?php else: ?><body>
<div class="uk-section-primary">
    <div class="uk-section uk-section-xsmall uk-flex" uk-height-viewport="offset-top: true; offset-bottom: true">
        <div class="uk-container">
            <div class="uk-width-3-4"></div>
            <div class="uk-width-2-6">
                <h3 class="uk-text-center"><span class="uk-text">The </span><strong>PHP remote account connector</strong> is installed.</span></h3>
                <p class="uk-text-center"><img class="uk-border" src="images/refresh.png" width="100" height="100" alt="" uk-scrollspy="cls: uk-animation-fade; delay: 400; repeat: true"></p>
                <p class="uk-text-center">The PHP Account Connector is configured and is in your atavism-integration plugin directory! To reconfigure the php connector, manually edit the file. If you are having trouble, please see instructions at the <a href="http://wiki.atavismonline.com/">Atavism Wiki</a>  for how to use this with your Atavism Deployment.</p>
                <div class="uk-card uk-card-body uk-padding">
                    <form action="" method="POST" accept-charset="utf-8" autocomplete="off">
                        <div class="uk-text-center">
                        </div>
                                    <div class="uk-margin">
                                        <div class="uk-form-controls">
                                            <button class="uk-button uk-button-secondary uk-width-1-1 uk-margin-small-bottom" type="submit" name="button_ok"><i class="fas fa-cog fa-spin"></i> Continue</button>
                                        </div>
                                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="uk-section-small">
        <div class="uk-container uk-container-expand uk-text-center uk-position-relative">
            <ul class="uk-subnav uk-flex-inline uk-flex-center uk-margin-remove-bottom" uk-margin>
                <li>
                    <a target="_blank" href="https://discord.gg/M6VwFFz"><i class="fab fa-discord fa-2x"></i> Wordpress integration for Atavism</a>
                </li>
            </ul>
            <p>This product is brought to you <strong>in partnership</strong> with <strong>Dragonsan Studios.</strong></p>
        </div>
    </div>
</div>
    </body>
<?php endif ?>
</html>
