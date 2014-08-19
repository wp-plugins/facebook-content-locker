<?php
/**
 * Facebook Content Locker
 * @version 1.0
 */
/*
  Plugin Name: Facebook Content Locker
  Plugin URI: http://www.baznani.com/facebook-content-locker
  Description: Facebook Content Locker locks your content until the user clicks "Like", helps you get more fans & social traffic. You can even create unlimited number of content lockers & apply on the pages you want!
  Author: Achraf Baznani
  Version: 1.0
  Author URI: http://www.baznani.com
 */
include ("inc/like.class.php");

add_action('admin_menu', 'wpliketounclock_adminTOPmenu');
add_action('wp_footer', 'wpliketounlock_execute');
add_action('wp_head', 'wpliketounclock_css');
add_action('add_meta_boxes', 'wpliketounlock_metabox');
add_action('save_post', 'wpliketounlock_metabox_savemeta');
add_filter('admin_head', 'wpliketounlock_tinymce');
register_activation_hook(__FILE__, 'wpliketonlock_install'); //installation - creating the table

function wpliketounlock_tinymce() {

    wp_admin_css('thickbox');
    wp_print_scripts('jquery-ui-core');
    wp_print_scripts('jquery-ui-tabs');
    wp_print_scripts('post');
    wp_print_scripts('editor');
    add_thickbox();
    wp_print_scripts('media-upload');
    echo "<link rel='stylesheet' href='".  get_bloginfo("wpurl")."/wp-admin/css/farbtastic.css' type='text/css'/>";
    wp_enqueue_style('farbtastic');
    wp_enqueue_script('farbtastic');

    if (function_exists('wp_tiny_mce'))
        wp_tiny_mce();
}

function wpliketonlock_install() {
    global $wpdb;
    global $table_name;
    $table_name = $wpdb->prefix . "liketounlock";


    $sql = "CREATE TABLE `$table_name` (
  `id_like` int(11) NOT NULL AUTO_INCREMENT,
  `timetoexecute` int(11) NOT NULL,
  `timetoclose` int(11) NOT NULL,
  `timetoreexecute` int(11) NOT NULL,
  `url` varchar(128) NOT NULL,
  `showclose` int(11) NOT NULL,
  `showcounter` mediumint(9) NOT NULL,
  `showhomepage` int(11) NOT NULL,
  `showallpages` int(11) NOT NULL,
  `showallposts` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `txtmessage` text NOT NULL,
  `colorscheme` varchar(15) NOT NULL,
  `layout` varchar(15) NOT NULL,
  `opacity` int(11) NOT NULL,
  `bordertype` varchar(10) NOT NULL,
  `bordersize` int(11) NOT NULL,
  `bordercolor` varchar(16) NOT NULL,
  `titleBackground` varchar(16) NOT NULL,
  `titleColor` varchar(16) NOT NULL,
  `messageBackground` varchar(16) NOT NULL,
  `corners` varchar(8) NOT NULL,
  PRIMARY KEY (`id_like`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option("wpliketounlock_ver", "1.0");
}

function wpliketounclock_adminTOPmenu() {
    add_menu_page("Facebook Content Locker", "Facebook CL", 'administrator', 'wpliketounlock', "wpliketounlock_welcome", plugins_url('imgs/icon.png', __FILE__));
    add_submenu_page("wpliketounlock", "Create new FCL", "Create FCL", "administrator", "wpliketounlockadd", "wpliketounlock_addform");
    add_submenu_page("wpliketounlock", "Manage FCLs", "Manage FCLs", "administrator", "wpliketounlockmanage", "wpliketounlock_manage");
}

function wpliketounlock_welcome() {
    ?>
    <h1><img src='<?php echo plugins_url('imgs/icon.png', __FILE__); ?>'/> Facebook Content Locker v 1.0</h1>
    <p><h3>Welcome to Facebook Content Locker v 1.0</h3></p>
    <p><h4 style="font-size:14px;">Facebook Content Locker v 1.0 gives the ability to lock the content of any page or post in your website, and makes it visible only if the user clicks the Facebook Like button.<br/><br/>
        The plugin is able to remember automatically who clicked the Like button & who didn't, so don't worry about that.<br/><br/>
        You can apply the content locker on all the pages, all the posts, or only some selected pages, you can also create unlimited number of content lockers which are all independant.<br/><br/>
        Here is a <a target="_blank" href="http://www.baznani.com/facebook-content-locker">must-read document</a> to fully understand the meaning of each field & property of Facebook Content Locker.<br/> <br/>
    </p>
    <?php
}

function wpliketounclock_css() {
    ?>
    <style type="text/css">
        .connect_comment_widget, .fb_edge_widget_with_comment span.fb_edge_comment_widget iframe.fb_ltr {
            display: none !important;
        }
        .comment_content {display:none;}
    </style>
    <?php
}

function wpliketounlock_metabox() {
    add_meta_box(
            'wpliketounlock_metaboxID', "Facebook Content Locker", 'wpliketounlock_metabox_content', 'post'
    );
    add_meta_box(
            'wpliketounlock_metaboxID', "Facebook Content Locker", 'wpliketounlock_metabox_content', 'page'
    );
}


function wpliketounlock_metabox_content($post) {
    $old = get_post_meta($post->ID, "wpliketounlock_id_like", true);

    echo '<label for="liketounlock_locks"> Choose a lock to apply </label>';
    $liste = likeToUnlock::listLocks();

    echo '<select id="liketounlock_locks" name="wpliketounlock_id_like">';
    echo '<option value="x">Choose</option>';
    foreach ($liste as $row) {
        if ($old == $row->id_like)
            $sel = "selected='selected'";
        else
            $sel = "";

        echo "<option value='$row->id_like' $sel>$row->title</option>";
    }
    echo '</select>';
}

function wpliketounlock_metabox_savemeta($post_id) {
    if (isset($_POST["wpliketounlock_id_like"]) && $_POST["wpliketounlock_id_like"] != "x") {
        $id_like = $_POST["wpliketounlock_id_like"];
        update_post_meta($post_id, "wpliketounlock_id_like", $id_like);
    }
}

function wpliketounlock_check($data) {
    if ($data != NULL) {
        $flag = false;
        if (@$_COOKIE["wpliketounlock_liked_$data->id_like"]) {
            $liked = explode("#", $_COOKIE["wpliketounlock_liked_$data->id_like"]);
            for ($i = 0; $i < count($liked); $i++)
                if ($liked[$i] == $data->id_like) {
                    //echo "<h1>TRUE 1</h1>";
                    $flag = true;
                    break;
                }
            if ($flag == false) {
                //echo "<h1>FALSE 1</h1>";
                wpliketounlock_lock($data);
            }
        } else {
            //echo "<h1>FALSE 2<h1>";
            wpliketounlock_lock($data);
        }
    }
}

function wpliketounlock_execute() {
    global $wpdb;
    global $post;

    //if (@$_COOKIE["wpliketounlock_liked"])
    if (is_home()) {
        $data = likeToUnlock::getThis("where showhomepage=1");
        wpliketounlock_check($data);
    }
    if (is_page()) {
        $page_id = $post->ID;
        $id_like = get_post_meta($page_id, "wpliketounlock_id_like", true);
        if ($id_like != "") { //on a un lock attaché
            $data = likeToUnlock::getData($id_like);
            wpliketounlock_check($data);
        } else { // execution du showOnAllPages lock
            $data = likeToUnlock::getThis("where showallpages=1");
            if ($data != null) { // il y a un lock allpages
                wpliketounlock_check($data);
            }
        }
    }
    if (is_single()) { // post
        $post_id = $post->ID;
        $id_like = get_post_meta($post_id, "wpliketounlock_id_like", true);
        if ($id_like != "") { //on a un lock attaché
            $data = likeToUnlock::getData($id_like);
            wpliketounlock_check($data);
        } else { // execution du showOnAllPosts lock
            $data = likeToUnlock::getThis("where showallposts=1");
            if ($data != null) { // il y a un lock allpages
                wpliketounlock_check($data);
            }
        }
    }
}

function wpliketounlock_lock($data) {
    if ($data->layout == "standard") {
        $width = 450;
        $height = "24px";
        if (get_bloginfo("version") < 3)
            $height = "28px";
    }
    else if ($data->layout == "button_count") {
        $width = 65;
        $height = "26px;";
    } else if ($data->layout == "box_count") {
        $width = 65;
        $height = "69px;";
    }
    ?>
    <script type="text/javascript" src="<?php echo wp_enqueue_script('jquery'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('js/jquery.simplemodal.js', __FILE__); ?>"></script>

    <script type="text/javascript">
    function createCookie(name,value,exp) {
        if (exp != 0) {
            var date = new Date();
            date.setTime(date.getTime()+(24*60*60*1000*exp));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
                                                                                                                                                
    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function wpliketounlock()
    {
        jQuery('#branding').css({'z-index':'1'});
        jQuery('#wpliketounlock').css({'height':'auto','width':'450','padding':'0px'});
        jQuery('#wpliketounlock').modal({
            opacity:<?php echo $data->opacity; ?>,
            escClose:false,
            overlayCss: {backgroundColor:"black"},
            onClose: function (dialog) {
                dialog.data.fadeOut('slow', function () {
                    dialog.container.slideUp('fast', function () {
                        dialog.overlay.fadeOut('slow', function () {
                            if ( <?php echo $data->timetoreexecute; ?> == 0 )
                            createCookie("wpliketounlock_liked_<?php echo $data->id_like; ?>",<?php echo $data->id_like; ?>,365);
                            jQuery.modal.close();
                        });
                    });
                });
            }
        });
        jQuery('#simplemodal-container').css({'background-color':'white'});
        jQuery('#simplemodal-container').css({'border':'<?php echo $data->bordersize; ?>px <?php echo $data->bordertype; ?> <?php echo $data->bordercolor; ?>'});
                                        
    <?php
    if (@$data->corners == "rounded") {
        $pie = plugins_url("css/PIE.htc", __FILE__);
        ?>
                jQuery('#simplemodal-container').css({'border-radius':'10px','-webkit-border-radius':'10px','-moz-border-radius':'10px','behavior': 'url(<?php echo $pie; ?>)'});
                jQuery('#likeboxdiv').css({'border-radius':'0px 0px 10px 10px','-webkit-border-radius':'0px 0px 10px 10px','-moz-border-radius':'0px 0px 10px 10px','behavior': 'url(<?php echo $pie; ?>)'});
                jQuery('.popupTitle').css({'border-radius':'8px 8px 0px 0px','-webkit-border-radius':'8px 8px 0px 0px','-moz-border-radius':'8px 8px 0px 0px','behavior': 'url(<?php echo $pie; ?>)'});
                jQuery('.simplemodal-wrap').css({'border-radius':'10px 10px 10px 10px','-webkit-border-radius':'10px 10px 10px 10px','-moz-border-radius':'10px 10px 10px 10px','behavior': 'url(<?php echo $pie; ?>)','overflow':'hidden'});
        <?php
    }
    ?>
    }
                                                                                                                                                    
                                                                                                     
    var secs
    var timerID = null
    var timerRunning = false
    var delay = 1000

    function InitializeTimer()
    {
        secs = <?php echo $data->timetoclose; ?>        
        StopTheClock()
        StartTheTimer()
    }

    function StopTheClock()
    {
        if(timerRunning)
            clearTimeout(timerID)
        timerRunning = false
    }

    function StartTheTimer()
    {
        if (secs==0)
        {
            StopTheClock()
            jQuery.modal.close();  
        }
        else
        {
            self.status = secs
            secs = secs - 1
            timerRunning = true
            timerID = self.setTimeout("StartTheTimer()", delay)
            jQuery(".timetoclose").html(secs+"s")
        }
    }                       
    jQuery(function ($) {
                                                
    <?php if ($data->title == "auto") { ?>
            jQuery("div.popupTitle b").html(jQuery("title").html());
    <?php } ?>
        jQuery(".timetoclose").html("<?php echo $data->timetoclose; ?>");
        InitializeTimer();
        setTimeout("wpliketounlock()", <?php echo $data->timetoexecute; ?>*1000); 
        FB.Event.subscribe('edge.create',
        function(response) {
            //alert("Like Clicked");
            clicked = true;
            createCookie("wpliketounlock_liked_<?php echo $data->id_like; ?>",<?php echo $data->id_like; ?>,365);
            jQuery.modal.close();     
        }
    );
    });
    </script>
    <div id="wpliketounlock" style="display:none;z-index: 0">
        <div class="popupTitle" style="background-color: <?php echo $data->titleBackground; ?>; padding:5px 5px 5px 5px; color: <?php echo $data->titleColor; ?>">
            <span class="CounterAndClose">
                <?php
                echo $row->corners;
                if ($data->showcounter == 1) {
                    ?>       
                    <div style="position:absolute; top:-37px; right:16px; font-weight: bold; font-size: small; color:white; font-size:18px" class="timetoclose">
                        <?php echo $data->timetoclose; ?>s
                    </div>
                    <?php
                }
                ?>
                <?php
                if ($data->showclose == 1) {
                    ?> 
                    <a ref="#" style="color:red;text-decoration:underline;cursor:pointer;cursor:hand;position:absolute; top:-15px; right:-16px; " onclick="javascript:jQuery.modal.close();">
                        <img src="<?php echo plugins_url('imgs/x.png', __FILE__); ?> "/>
                    </a>
                    <?php
                }
                ?>
            </span>
            <b><?php echo $data->title; ?></b>
        </div>
        <div style="background-color:<?php echo $data->messageBackground; ?>">
            <?php echo do_shortcode($data->txtmessage); ?>
        </div>
        <?php
        if ($data->url == "auto")
            $url = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        else
            $url = $data->url;
        ?>
        <div style="padding:2px 10px 2px 10px; height:<?php echo $height ?>;text-align: center;background-color:<?php echo $data->messageBackground; ?>" id="likeboxdiv">
            <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
            <fb:like href="<?php echo $url; ?>" send="false" width="<?php echo $width; ?>" show_faces="false" colorscheme="<?php echo $data->colorscheme; ?>" layout="<?php echo $data->layout; ?>"></fb:like>
        </div>
    </div>
    <?php
}

function wpliketounlock_addform() {
    ?>
    <script type="text/javascript" src="<?php echo wp_enqueue_script('jquery'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('js/jquery.simplemodal.js', __FILE__); ?>"></script>
    <script type="text/javascript" src="<?php echo get_bloginfo("wpurl").'/wp-admin/js/farbtastic.js';?>"></script>

    <div class="wrap columns-2">
        <h2>
            
                <img src='<?php echo plugins_url('imgs/icon.png', __FILE__); ?>'/> 
                Create a new Facebook Content Locker
            
        </h2>
    </div>
    <?php
    if (!isset($_POST["save"])) {
        $infos = plugins_url("imgs/info.png", __FILE__);
        $close = plugins_url("imgs/x.png", __FILE__);
        $warn = plugins_url("imgs/warn.png", __FILE__);
        $pie = plugins_url("css/PIE.htc", __FILE__);


        include ("inc/addform.php");
    } else {
        $n = $_POST["name"];
        $ur = $_POST["url"];
        $txtmsg = $_POST["txtmessage"];
        $tte = $_POST["tte"];
        $ttc = $_POST["ttc"];

        $opacity = $_POST["opacity"];
        $color = $_POST["color"];
        $layout = $_POST["layout"];
        $title = $_POST["title"];
        $cor = $_POST["corners"];


        if ($ttrr == "other")
            $ttrr = $_POST["other"];

        $ttrr = (isset($_POST["ttrr"])) ? 1 : 0;
        $scl = (isset($_POST["scl"])) ? 1 : 0;
        $sco = (isset($_POST["sco"])) ? 1 : 0;
        $sopg = (isset($_POST["sopg"])) ? 1 : 0;
        $sops = (isset($_POST["sops"])) ? 1 : 0;
        $sohp = (isset($_POST["sohp"])) ? 1 : 0;

        $borderType = $_POST["bordert"];
        $borderSize = $_POST["borders"];
        $borderColor = $_POST["borderc"];
        $msgbg = $_POST["msgbg"];
        $titlebg = $_POST["titlebg"];
        $titlecolor = $_POST["titlecolor"];

        if (isset($_POST["autoTitle"]))
            $title = "auto";
        if (isset($_POST["autoUrl"]))
            $ur = "auto";

        $like = new likeToUnlock($tte, $ttc, $ttrr, $ur, $txtmsg, $scl, $sco, $sopg, $sops, $sohp, $color, $layout, $opacity, $title, $borderSize, $borderType, $borderColor, $msgbg, $titlebg, $titlecolor, $cor);
        $etat = $like->save();
        if ($etat == true)
            success("Saved successfully. <a href='admin.php?page=wpliketounlockadd'>Go back</a>");
        else
            $like->debug();
    }
}

function wpliketounlock_manage() {
    global $wpdb;
    $infos = plugins_url("imgs/info.png", __FILE__);
    $close = plugins_url("imgs/x.png", __FILE__);
    $warn = plugins_url("imgs/warn.png", __FILE__);
    $pie = plugins_url("css/PIE.htc", __FILE__);
    ?>
    
    <script type="text/javascript" src="<?php echo wp_enqueue_script('jquery'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('js/jquery.simplemodal.js', __FILE__); ?>"></script>
    <script type="text/javascript" src="<?php echo get_bloginfo("wpurl").'/wp-admin/js/farbtastic.js';?>"></script>

    <div class="wrap columns-2">
        <h2>
            
                <img src='<?php echo plugins_url('imgs/icon.png', __FILE__); ?>'/> Manage Facebook Content Lockers
            
        </h2>
    </div>
    <?php
    if (isset($_GET["action"]) && isset($_GET["id_like"]) && $_GET["action"] == "delete") {
        $id_like = intval($_GET["id_like"]);
        $delete = likeToUnlock::delete($id_like);
        if ($delete == true) {
            success("Deleted sucessfully");
            $res = likeToUnlock::listLocks();
            include ("inc/manage.php");
        }
        else
            echo "ERROR";
    } else if (isset($_GET["action"]) && isset($_GET["id_like"]) && $_GET["action"] == "edit") {
        if (!isset($_POST["save"])) {
            $id_like = intval($_GET["id_like"]);
            $row = likeToUnlock::getData($id_like);
            include ("inc/addform.php");
        } else {
            $id_like = intval($_POST["id_like"]);
            $n = $_POST["name"];
            $ur = $_POST["url"];
            $txtmsg = $_POST["txtmessage"];
            $tte = $_POST["tte"];
            $ttc = $_POST["ttc"];
            $opacity = $_POST["opacity"];
            $color = $_POST["color"];
            $layout = $_POST["layout"];
            $title = $_POST["title"];
            $ttrr = (isset($_POST["ttrr"])) ? 1 : 0;
            $scl = (isset($_POST["scl"])) ? 1 : 0;
            $sco = (isset($_POST["sco"])) ? 1 : 0;
            $sopg = (isset($_POST["sopg"])) ? 1 : 0;
            $sops = (isset($_POST["sops"])) ? 1 : 0;
            $sohp = (isset($_POST["sohp"])) ? 1 : 0;
            $borderType = $_POST["bordert"];
            $borderSize = $_POST["borders"];
            $borderColor = $_POST["borderc"];
            $msgbg = $_POST["msgbg"];
            $titlebg = $_POST["titlebg"];
            $titlecolor = $_POST["titlecolor"];
            $cor = $_POST["corners"];


            if (isset($_POST["autoTitle"]))
                $title = "auto";
            if (isset($_POST["autoUrl"]))
                $ur = "auto";

            $like = new likeToUnlock($tte, $ttc, $ttrr, $ur, $txtmsg, $scl, $sco, $sopg, $sops, $sohp, $color, $layout, $opacity, $title, $borderSize, $borderType, $borderColor, $msgbg, $titlebg, $titlecolor, $cor);
            $etat = $like->save($id_like);
            if ($etat == true)
                success("Update successfully");

            $row = likeToUnlock::getData($id_like);
            include ("inc/addform.php");
        }
    } else {
        $res = likeToUnlock::listLocks();
        include ("inc/manage.php");
    }
}

function success($str) {
    ?>
    <div class="updated below-h2" id="message"><p><?php echo $str; ?></p></div>
    <?php
}
?>