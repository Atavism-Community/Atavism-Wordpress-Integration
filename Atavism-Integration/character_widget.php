<?php

/*  Atavism World Status Widget  */

/*  Copyright 2018 Scott Meadows  (email : smeadows0155@yahoo.com)

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
class Character_Widget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function Character_Widget() {
		$widget_ops = array( 'classname'   => 'Character_Widget',
		                     'description' => 'Displays the status of an Atavism world server'
		);
		$this->WP_Widget( 'Character_Widget', 'Atavism Character Widget', $widget_ops );
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
public function realmConnection( $username, $password, $hostname, $database ) {
		$conn   = new mysqli( $hostname, $username, $password, $database );
		$sql    = "SELECT world_id, world_name FROM world";
		$result = $conn->query( $sql );
		$conn->close();
		if ( $conn->connect_error ) {
			echo "Error:" . $conn > error;
		} else {
			return $result;
		}
}
function widget( $args, $instance )
{
extract( $args, EXTR_SKIP );
/* User-selected values from options */
/* Before widget (defined by theme) */
if(!isset($before_widget)){
    $before_widget = '';
}
echo $before_widget;
/* Title of widget (before and after defined by themes). */
$id = get_current_user_id();
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
}
$options = get_option('atavism_plugin_options');
function getBetween2( $content, $start44, $end ) {
	$r = explode( $start44, $content );
	if ( isset( $r[1] ) ) {
		$r = explode( $end, $r[1] );
		return $r[0];
	}
	return '';
}
global $cnt2;
global $charclicked;
global $admindbconn;
global $atavismdbconn;
global $world;
// Check if character buttons have been clicked
$postnames = array_keys($_REQUEST);
if(!empty($_REQUEST)){
	$a = $postnames[0];
	if (strpos($a, 'char') !== false) {
	    //character button clicked!
        $charclicked = true;
		$char = getBetween2($postnames[0], 'char=', '&');
		$world= getBetween2($postnames[0], 'world=', '&');
		$cnt2= getBetween2($postnames[0], 'cnt2=', '&');
		$charname= getBetween2($postnames[0], 'name=', '&');
	}
}else{
		$cnt2 = '1';
	    $charclicked = false;
}
function getCharlogs( $conn3, $ObjId ) {
	    $sq3 = "SELECT * FROM data_logs WHERE source_oid = '$ObjId' LIMIT 20";
	    $result = $conn3->query($sq3);
	    if ( $conn3->connect_error ) {
		    return '0';
	    } else {
		    return  $result;
	    }
    }
function getCharInfoBlob($conn3, $ObjId) {
	$sq4    = "SELECT data FROM objstore WHERE type = 'PLAYER' AND obj_id = '$ObjId' AND instance  IS NOT NULL";
	$result = $conn3->query( $sq4 );
	$row    = $result->fetch_assoc();
	if ( $conn3->connect_error ) {
		return '0';
	} else {
		return $row['data'];
	}
}
function getCharBlob($conn3, $ObjId)
    {
        $sq4 = "SELECT data FROM objstore WHERE type = 'CombatInfo' AND obj_id = '$ObjId'";
	    $result = $conn3->query($sq4);
	    $row = $result->fetch_assoc();
	    if ( $conn3->connect_error ) {
	    	return '0';
	    } else {
		    return $row['data'];
	    }
    }
function getCharClass($conn3, $CharId) {
    $sq3 = "SELECT additional_data FROM data_logs WHERE data_name = 'CHARACTER_CREATED' AND source_oid = '$CharId'";
	$result = $conn3->query($sq3);
	$row = $result->fetch_assoc();
	if ( $conn3->connect_error ) {
		return '0';
	} else {
		return $row['additional_data'];
	}
}
//fetch item from world_content db
function getBagBlob($conn3, $CharId)
{
        $sq4 = "SELECT data FROM objstore WHERE type = 'Bag' AND obj_id like '$CharId'";
	    $result = $conn3->query($sq4);
	    //$row = $result->fetch_assoc();
	    if ( $conn3->connect_error ) {
		    return '0';
	    } else {
		    //return $row['data'];
		    return $result;
	    }
//this returns the blob data regarding which bags are equipped
}
//fetch inventory items from atavism db
function getItemids($conn3, $bagId) {
    $sq3 = "SELECT * FROM `player_items` WHERE `inv.backref` = '$bagId'";
	$result = $conn3->query($sq3);
	//$data = $result->fetch_assoc();
	if ( $conn3->connect_error ) {
		return '0';
	} else {
		return $result;
	}
}
//fetch item from world_content db
function getItemNames($conn3, $templateId) {
	$sq3 = "SELECT * FROM item_templates where id = $templateId";
	$result = $conn3->query($sq3);
	$row = $result->fetch_assoc();
	if ( $conn3->connect_error ) {
		return '0';
	} else {
		return $row;
	}
}
?>
<?php if (!empty(get_userdata($id)->user_login)): ?>
    <!-- UIkit Stub-->
<link rel="stylesheet" href="/wp-content/plugins/Atavism-Integration/core/uikit/css/uikit.min.css"/>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit.min.js"></script>
<script src="/wp-content/plugins/Atavism-Integration/core/uikit/js/uikit-icons.min.js"></script>
<?php if($charclicked != true): //user not clicked?>
        <div>
            <div class="uk-text-center">
				<?php if ( get_userdata($id)) { ?>
                    <div uk-lightbox>
                        <img class="uk-border-circle" src="<?= get_avatar_url( $id, $size = 120 ); ?>" width="120"
                             height="120" alt=""/>
                        </a>
                    </div>
				<?php } ?>
                <div class="uk-space-small"></div>
                <div class="uk-principal-title uk-text-white"><h3><?= get_userdata($id)->user_login; ?></h3>My Characters</div>
                <div class="uk-space-medium"></div>
            </div>
                <form action="" method="post">
                <hr class="uk-divider-icon">
                <ul uk-accordion>
                    <?php
                    foreach(range(1,$options['server_count']) as $i ) {
                        $worldID = $options['atavism_world'.strval($i).'_name_string'];
	                    $multiRealm = $this->realmConnection( $options[ 'atavism_master_db_user_string' ], $options[ 'atavism_master_db_pass_string' ], $options[ 'atavism_master_db_hostname_string' ],$options[ 'atavism_master_db_schema_string']);
	                    ?>
                    <li class="uk-closed">
                        <h3 class="uk-accordion-title uk-text-white"><i class="fas fa-server"></i> <?= $worldID; ?> </h3>
                        <div class="uk-accordion-content">
                            <div class="uk-grid uk-grid-small uk-child-width-auto@l uk-flex-center">
                                <?php
								$sql2 = "SELECT * FROM account JOIN account_character ON account.id = account_character.accountId WHERE id = '$id'";
                                $atavismdbconn = new mysqli( $options[ 'atavism_atavism_db' . strval( $i ) . '_hostname_string' ], $options[ 'atavism_atavism_db' . strval( $i ) . '_user_string' ], $options[ 'atavism_atavism_db' . strval( $i ) . '_pass_string' ], $options[ 'atavism_atavism_db' . strval( $i ) . '_schema_string' ], $options[ 'atavism_atavism_db' . strval( $i ) . '_port_string' ] );
                                $admindbconn   = new mysqli( $options[ 'atavism_admin_db' . strval( $i ) . '_hostname_string' ], $options['atavism_admin_db'.strval($i).'_user_string'], $options['atavism_admin_db'.strval($i).'_pass_string'], $options['atavism_admin_db'.strval($i).'_schema_string'], $options['atavism_admin_db'.strval($i).'_port_string'] );
                                $conn    = new mysqli( $options['atavism_admin_db'.strval($i).'_hostname_string'], $options['atavism_admin_db'.strval($i).'_user_string'], $options['atavism_admin_db'.strval($i).'_pass_string'], $options['atavism_admin_db'.strval($i).'_schema_string'], $options['atavism_admin_db'.strval($i).'_port_string'] );
								$result = $conn->query( $sql2 );?>
								<?php foreach($result as $chars)  { ?>
								<?php $CharName = $chars['status'] ; ?>
	                            <?php $CharId = $chars['characterId'];?>
	                            <?php $CharClass = getCharClass( $admindbconn, $CharId );?>
	                            <?php $CharBlob = getCharBlob( $atavismdbconn, $CharId )?>
		                    <?php $level = getbetween2($CharBlob,'<void class="atavism.agis.objects.AgisStat" method="getField">
      <string>current</string>
      <void method="set">
       <object idref="AgisStat11"/>
       <int>', '</int>');?>
                                    <div class="uk-text-center">
                                        <img class="uk-border-circle" src="<?= ('/wp-content/plugins/atavism-integration/assets/images/class/' . $CharClass . '.png'); ?>" title="<?= $chars->characterName ?> (Lvl<?= ' ' . $level . ' ' . $CharClass.')' ?><br> Character ID = <?= $CharId ?>" width="32" height="32" uk-tooltip>
                                        <br>
                                        <input type="submit" class="uk-label" value="<?= $chars['characterName']?>" Name="char=<?= $CharId ?>&cnt2=<?= $i ?>&world=<?= $worldID ?>&name=<?= $chars['characterName'] ?>" title="View Character"" uk-tooltip></span>
                                    </div>
								<?php }?>
                                <?php $cnt2 = $cnt2 + 1;  ?>
                            </div>
                        </div>
                    </li>
				<?php } ?>
                </ul>
                </form>
            </div>
	        <?php else://user clicked?>
<div>
            <?php
		function get_Between( $content, $start44, $end ) {
		$r = explode( $start44, $content );
		if ( isset( $r[1] ) ) {
			$r = explode( $end, $r[1] );
			return $r[0];
		}
		    return '';
	    }
        function get_between_array($content,$start,$end){
	            $r = explode($start, $content);
	            if (isset($r[1])){
		            array_shift($r);
		            $ret = array();
		            foreach ($r as $one) {
			            $one = explode($end,$one);
			            $ret[] = $one[0];
		            }
		            return $ret;
	            } else {
		            return array();
	            }
		}
	    $cnt2 = get_Between($postnames[0], 'cnt2=', '&');
            $start = '</void>
      <void property="skillID">
       <int>';
            $end = '</int>';
            $skill ='</void>
      <void property="skillLevel">
       <int>';
            $skillmax ='<void property="skillMaxLevel">
       <int>';
            $skillname ='</void>
      <void property="skillName">
       <string>';
            $endstring ='</string>';
            $statbegin ='</void>
     </void>
     <void property="name">
      <string>';
            $statnum = 0;
            $statbasestart = '<string>base</string>
      <void method="set">
       <object idref="AgisStat'.$statnum.'"/>
       <int>';
            $guildstart = '<void method="put">
    <string>guildName</string>
    <string>';
        $r = 0;
        $r2 = 0;
        $admindbconn  = new mysqli( $options['atavism_admin_db'.strval($cnt2).'_hostname_string'], $options['atavism_admin_db'.strval($cnt2).'_user_string'], $options['atavism_admin_db'.strval($cnt2).'_pass_string'], $options['atavism_admin_db'.strval($cnt2).'_schema_string'], $options['atavism_admin_db'.strval($cnt2).'_port_string'] );
        $atavismdbconn = new mysqli( $options['atavism_atavism_db'.strval($cnt2).'_hostname_string'], $options['atavism_atavism_db'.strval($cnt2).'_user_string'], $options['atavism_atavism_db'.strval($cnt2).'_pass_string'], $options['atavism_atavism_db'.strval($cnt2).'_schema_string'], $options['atavism_atavism_db'.strval($cnt2).'_port_string'] );
        $charlogdata = getCharLogs($admindbconn, $char);
        $combatinfoblob = getCharBlob($atavismdbconn, $char);
        $bagBlob = getBagBlob($atavismdbconn, $char);
        $CharInfoBlob = getCharInfoBlob($atavismdbconn, $char);
        $skillid1 = get_between_array($combatinfoblob,$start,$end);
        $skilllvl1 = get_between_array($combatinfoblob,$skill,$end);
        $skilllvlmax1 = get_between_array($combatinfoblob,$skillmax,$end);
        $skillname1 = get_between_array($combatinfoblob,$skillname,$endstring);
        $statname = get_between_array($combatinfoblob,$statbegin,$endstring);
        $statbase = get_between_array($combatinfoblob,$statbasestart,$end);
        $guildname =get_between($CharInfoBlob,$guildstart,$endstring);
        $CharClass = getCharClass( $admindbconn, $char );
        ?>
    <div>
        <div class="uk-text-center">
			<?php if ( get_userdata($id)) { ?>
            <div uk-lightbox>
                    <img class="" src="<?= ('/wp-content/plugins/atavism-integration/assets/images/class/' . $CharClass . '.png'); ?>" width="120"
                         height="120" alt=""/>
                    </a>
			<?php } ?>
        </div>
        <h3 class="uk-text-heading uk-text-white uk-text-center"><?= $charname; ?></h3><hr class="uk-divider-icon">
			<?php if(empty($guildname)):?>
			    <?php $guildname = 'Not a guild member.'?>
                <h3 class="uk-text-white uk-flex-top">Guild: <div style="color : grey;"</style><?=$guildname;?></h3>
			<?php else: ?>
                <h3 class="uk-text-white uk-flex-top">Guild: <div style="color : green;"</style><?=$guildname;?></h3>
			<?php endif ?>
            <form action="" method="post">
                <ul uk-accordion>
                    <li class="uk-closed">
                        <h3 class="uk-accordion-title uk-text-white"><i class="fas fa-server"></i> <span>Skills</span> </h3>
                            <div class="uk-accordion-content">
                                <div class="uk-grid uk-grid-small uk-child-width-auto@l uk-flex-center">
                                    <div class="uk-width-auto uk-text-center">
                                        <?php foreach ($skillid1 as $skill){ ?>
                                            <p>
                                                <a href="" id="" ><img class="uk-border-circle" src="<?= '/wp-content/plugins/Atavism-Integration/assets/images/skill/'.preg_replace('/\s+/', '', $skillname1[$r]).'.png'; ?>" width="32" height="32"/>
                                                    <?= $skillname1[$r].':	&nbsp Current Level:	&nbsp'.$skilllvl1[$r].'    &nbsp Max Level:    &nbsp'.$skilllvlmax1[$r]; ?>
                                                </a>
                                            </p>
                                            <?php $r = $r + 1;?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    </li>
                </ul>
            </form>
        <form action="" method="post">
            <hr class="uk-heading-divider">
            <ul uk-accordion>
                <li class="uk-closed">
                    <h3 class="uk-accordion-title uk-text-white"><i class="fas fa-server"></i><span>Stats</span> </h3>
                    <div class="uk-accordion-content">
                        <div class="uk-grid uk-grid-small uk-child-width-auto@l uk-flex-center">
                            <div class="uk-width-auto uk-text-center">
										        <?php $health = get_between($combatinfoblob,'<void class="atavism.agis.objects.AgisStat" method="getField">
      <string>base</string>
      <void method="set">
       <object idref="AgisStat23"/>
       <int>',$end);$mana = get_between($combatinfoblob,'<void class="atavism.agis.objects.AgisStat" method="getField">
      <string>base</string>
      <void method="set">
       <object idref="AgisStat28"/>
       <int>',$end);$showstat = true;?>
                                                <p>
                                                    <a href="" id="" ><img src="<?= './wp-content/plugins/Atavism-Integration/assets/images/stat/health.png'; ?>" width="32" height="32"/>
												        <?= 'health:	&nbsp'.$health[0]; ?>
                                                    </a>
                                                </p>
                                                <p>
                                                    <a href="" id="" ><img  src="<?= './wp-content/plugins/Atavism-Integration/assets/images/stat/mana.png'; ?>" width="32" height="32"/>
												        <?= '&nbsp mana:	&nbsp'.$mana[0]; ?>
                                                    </a>
                                                </p>
										        <?php foreach ($statname as $stat){ ?>
											        <?php if($statname[$r2] == 'health-max'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'critic'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'mana-max'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'experience'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'experience-max'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'level'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'weight'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'dmg-max'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'dmg-base'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'dmg-taken-mod'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'dmg-dealt-mod'){$showstat = false;}?>
											        <?php if($statname[$r2] == 'attack_speed'){$showstat = false;}?>
											        <?php if($showstat == true){ ?>
                                                        <p>
                                                            <a href="" id="" ><img  src="<?= '/wp-content/plugins/Atavism-Integration/assets/images/stat/'.preg_replace('/\s+/', '', $statname[$r2].'.png'); ?>"  width="32" height="32"/>
														        <?= $statname[$r2].':	&nbsp'.$statbase[0]; ?>
                                                            </a>
                                                        </p>
												        <?php $r2 = $r2 + 1; $statnum = $statnum + 1;
												        $statbasestart = '<string>base</string>
      <void method="set">
       <object idref="AgisStat'.$statnum.'"/>
       <int>';$statbase = get_between($combatinfoblob,$statbasestart,$end);
												        $showstat = true;
											        }else{ ?>
												        <?php $r2 = $r2 + 1; $statnum = $statnum + 1;
												        $showstat = true;?>
											        <?php } ?>
										        <?php } ?>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
        <form action="" method="post">
            <hr class="uk-heading-divider">
            <ul uk-accordion>
                <li class="uk-closed">
                    <h3 class="uk-accordion-title uk-text-white"><i class="fas fa-server"></i><span>Character Log</span> </h3>
                    <div class="uk-accordion-content">
                        <div class="uk-grid uk-grid-small uk-child-width-auto@l uk-flex-center">
                            <div class="uk-width-auto uk-text-center">
		<?php foreach ($charlogdata as $data){ ?>
		<?php $w = true; ?>
		<?php if($data['data_name'] == 'PLAYER_LOGGED_IN_EVENT'){$data['data_name'] = 'Logged In';$data['additional_data'] = '';?>
        <a  id="" ><img src="<?= '/wp-content/plugins/atavism-integration/assets/images/login.png'; ?>"  width="32" height="32"/><br></a>
		<?php } ?>
		<?php if($data['data_name'] == 'PLAYER_LOGGED_OUT_EVENT'){$data['data_name'] = 'Logged Out';$data['additional_data'] = '';?>
        <a  id="" ><img src="<?= '/wp-content/plugins/atavism-integration/assets/images/logout.png'; ?>"  width="32" height="32"/><br></a>
		<?php } ?>
		<?php if($data['data_name'] == 'ITEM_LOOTED_EVENT'){$data['data_name'] = 'Looted Item:&nbsp';$item = get_between($data['additional_data'],"loot+%3A+","+%3A+OID"); $data['additional_data'] = urldecode($item);$w = false;?>
        <a  id="" ><img src="<?= '/wp-content/plugins/atavism-integration/assets/images/items/'.preg_replace('/\s+/', '', $data['additional_data'].'.png'); ?>" width="32" height="32"/><br></a>
		<?php } ?>
		<?php if($data['data_name'] == 'PLAYER_DIED'){$data['data_name'] = $charname.' Died';$w = false;?>
        <a  id="" ><img src="<?= '/wp-content/plugins/atavism-integration/assets/images/grave.png'; ?>" width="32" height="32"/><br></a>
		<?php } ?>
		<?php if($data['data_name'] == 'CHARACTER_CREATED'){$data['data_name'] = $charname.' became a&nbsp'.$data['additional_data'].'&nbspof the realm.';$w = false;?>
        <a  id="" ><img src="<?= '/wp-content/plugins/atavism-integration/assets/images/class/'.$CharClass.'.png'; ?>" width="32" height="32"/><br></a>
		<?php $data['additional_data'] = ''; } ?>
        <?php if (empty($data['additional_data'])){?>
            <?= $data['data_timestamp'].'&nbsp&nbsp'.$data['data_name']; ?>
			<?php }else{ ?>
            <?php if($w == true){ ?>
				<?= $data['data_timestamp'].'&nbsp&nbsp'.$data['data_name'].'	&nbsp Detail:&nbsp'.$data['additional_data']; ?>
			<?php }else{ ?>
				<?= $data['data_timestamp'].'&nbsp&nbsp'.$data['data_name'].'	&nbsp'.$data['additional_data']; ?>
			<?php } ?>
			<?php } ?>
            <br>
        <?php } ?>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
        <hr class="uk-heading-divider">
        <form action="" method="post">
            <ul uk-accordion>
                <li class="uk-closed">
                    <h3 class="uk-accordion-title uk-text-white"><i class="fas fa-server"></i><span>Inventory</span> </h3>
                    <div class="uk-accordion-content">
                        <div class="uk-grid uk-grid-small uk-child-width-auto@l uk-flex-center">
                            <div class="uk-width-auto uk-text-center">
                                <?php $worlddbconn = new mysqli( $options['atavism_worldcontent_db'.strval($cnt2).'_hostname_string'], $options['atavism_worldcontent_db'.strval($cnt2).'_user_string'], $options['atavism_worldcontent_db'.strval($cnt2).'_pass_string'], $options['atavism_worldcontent_db'.strval($cnt2).'_schema_string'], $options['atavism_worldcontent_db'.strval($cnt2).'_port_string'] );?>
                                <?php foreach ($bagBlob as $bag){ ?>
	                                <?php $bagId[0] = get_between($bag['data'],'<void id="OIDArray0" property="bags">
   <void index="0">
    <object class="atavism.server.engine.OID">
     <void property="data">
      <long>', ' <long>')?>
	                                <?php $bagId[1] = get_between($bag['data'],'<void index="1">
    <object class="atavism.server.engine.OID">
     <void property="data">
      <long>', ' <long>')?>
	                                <?php $bagId[2] = get_between($bag['data'],'<void index="2">
    <object class="atavism.server.engine.OID">
     <void property="data">
      <long>', ' <long>')?>
	                                <?php $bagId[3] = get_between($bag['data'],'<void index="3">
    <object class="atavism.server.engine.OID">
     <void property="data">
      <long>', ' <long>')?>
                                    <?php foreach ($bagId as $bag){ ?>
	                                <?php $itemarray = getItemids($atavismdbconn, $bag);?>
                                        <?php foreach ($itemarray as $item){ ?>
	                                        <?php $stacksize = $item['stackSize'];?>
	                                        <?php $itembinding = $item['binding'];?>
	                                        <?php $templateid = $item['templateID'];?>
	                                        <?php $itemtype= $item['ItemType'];?>
	                                        <?php $itemquality= $item['ItemQuality'];?>
	                                        <?php $itemstring = 'Item ID: '.$templateid;?>
                                            <?php $itemtemplate = getItemNames($worlddbconn, $templateid);?>
		                                    <?php $linkname = preg_replace('/\s+/','',$itemtemplate['name']);?>
                                            <p>
                                                <a id="" ><img class="uk-border-circle" src="<?= '/wp-content/plugins/atavism-integration/assets/images/items/'.$linkname.'.png'; ?>" width="32" height="32" title="<?= $itemstring ?>"/>
	                                               <?php if($stacksize > 1){ ?>
                                                        <?= $stacksize.' x '.$itemtemplate['name']?>
	                                                <?php }else{ ?>
	                                                    <?= $itemtemplate['name'] ?>
                                                    <?php } ?>
                                                </a>
                                            </p>
                                        <?php } ?>
                                    <?php } ?>
                                <?php }?>
                            </div>
                        </div>
                </li>
            </ul>
        </form>
    </div>
    </div>
</div>
        <?php endif ?>
    <?php
   /* End ping code. */
    endif ?> <?php
	if(!isset($after_widget)){
		$after_widget = '';
	}
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("Character_Widget");') );
