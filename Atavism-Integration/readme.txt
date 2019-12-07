=== Atavism-Integration ===
Contributors: smzero
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9T6NJ747QR8PU&source=url
Tags: atavism, MMO, server
Requires at least: 4.6
Tested up to: 5.0
Stable tag: 5.0
Requires PHP: 7.1.12
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect wordpress to your Atavism Online deployment.

== Description ==

Connecting Wordpress to you Atavism Online deployment is made easy with this plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/Atavism-Integration` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Atavism Integration screen to configure the plugin and remote php account connector.
4. Add the Server Status widget wherever you would like.
5. Configure auth_server.py on your Atavism server(s) (see screenshot# 3) in order to route your user authentication requests to the verification script. 
If your Wordpress installation is not on the same subnet as your Atavism auth server, I recommend utilizing an SSL connection in conjunction with a VPN Tunnel.

== Frequently Asked Questions ==

= Why is my server status incorrect, and why don't my start or stop buttons work? =

Your atavism deployment needs to have CRON jobs setup to manage the master.server_status table in your database, and to monitor the master.server table for commands. 
You can find a working example of a working CRON job setup by inspecting the latest Atavism Ubuntu VM downloadable from http://apanel.atavismonline.com.

== Screenshots ==

1. You can easily use this plugin to stop or start/restart your Atavism Servers.
2. Convenient and dynamic fields allow this plugin to support a multi-server Atavism deployment
3. Edit your auth_server.py under atavism_server/bin so that the lines pertaining to the connector are uncommented. Fill out connector.setUrl() as shown.

== Changelog ==

= 0.0.1 =
* Created initial settings fields for use by admin server control page and the status widget.
* Created initial admin server control page.
* Created initial server status widget.Server status widget.

= 0.0.2 =
* Created Admin Server Controls that work via CRON so you can start, restart, or stop your Atavism servers from wordpress.
* Added support for multiple server Atavism deployments
* Created Atavism User listing with sortby, search, and pagination.
* Added User ban/promote functionality to User listing
* Added gravatar support to User listing.
* Created built in installer for the Atavism remote php authenticator.

= 0.0.3 =
* Fixed bug in the Atavism User listing caused by selecting a checkbox next to a user, and then searching or sorting results via the role buttons. The problem occured where $role was NULL and the mysqli query was still being triggered.

= 0.4 = 
* Created Character list widget
* Added Guild listing to Character list widget
* Added Skills, Stats and Character log to Character list widget
* Added Inventory listing to Character list widget

= 0.6 =
* Updated remote PHP account authenticator to deny banned users to login or to require subscriptions if option is defined in the atavism plugin settings.
* Created sister plugin "Xsolla for Woocommerce" with this integration in mind that includes a subscription status widget.

= 0.7 =
* Corrected issue with setting roles in user management *
* Added fields for admin_db_name to plugin settings *
* Added multi server support to user management page *
* Code cleanup *

= 0.8 =
* Corrected issues with character widget and various other bugs *
* Corrected issues with the remote php account authenticator (verify.php) *
* Tweaked admin user management page *
* Added in game delivery of items purchased via Xsolla (if they match a templateId) *

= 1.0 =
* Implemented Support Ticket System *
* Corrected depreciated construct methods in widgets *

= 1.0.1 =
* Removed Redundant closing tags that caused "headers already sent" error *


= 1.0.2 =
* Fix for code that caused themes and preloaders to freeze *


