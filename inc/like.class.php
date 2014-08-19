<?php

class likeToUnlock {

    private $id_like;
    private $timeToExecute;
    private $timeToClose;
    private $timeToReExecute;
    private $url;
    private $txtMessage;
    private $showClose;
    private $showCounter;
    private $showAllPages;
    private $showAllPosts;
    private $showHomePage;
    private $colorscheme;
    private $layout;
    private $opacity;
    private $title;
    
    private $borders;
    private $bordert;
    private $borderc;
    private $msgbg;
    private $titlebg;
    private $titlec;
    private $corners;
    

    public function __construct($tte, $ttc, $ttrr, $ur, $txtmsg, $scl, $sco, $sopg, $sops, $sohp, $clr, $lay, $opa, $tit, $bs, $bt, $bc, $mb, $tb, $tc, $cor) {
        $this->timeToExecute = $tte;
        $this->timeToClose = $ttc;
        $this->timeToReExecute = $ttrr;
        $this->url = $ur;
        $this->txtMessage = $txtmsg;
        $this->showClose = $scl;
        $this->showCounter = $sco;
        $this->showAllPages = $sopg;
        $this->showAllPosts = $sops;
        $this->showHomePage = $sohp;
        $this->colorscheme = $clr;
        $this->layout = $lay;
        $this->opacity = $opa;
        $this->title = $tit;
        
        $this->borders = $bs;
        $this->borderc= $bc;
        $this->bordert = $bt;
        $this->titlebg = $tb;
        $this->titlec = $tc;
        $this->msgbg = $mb;
        $this->corners = $cor;
    }

    public function save($id_like = 0) {
        global $wpdb;

        if ($this->showHomePage == 1)
            $wpdb->query("update " . $wpdb->prefix . "liketounlock set showhomepage = 0 where id_like != $id_like");

        if ($this->showAllPages == 1)
            $wpdb->query("update " . $wpdb->prefix . "liketounlock set showallpages = 0 where id_like != $id_like");

        if ($this->showAllPosts == 1)
            $wpdb->query("update " . $wpdb->prefix . "liketounlock set showallposts = 0 where id_like != $id_like");


        if ($id_like == 0) {
            $insert = $wpdb->query("insert into " . $wpdb->prefix . "liketounlock(`timetoexecute` ,`timetoclose` ,`timetoreexecute` ,`url` ,`showclose` ,`showcounter` ,`showhomepage` ,`showallpages` ,`showallposts` ,`title` ,`txtmessage` ,`colorscheme` ,`layout` ,`opacity` ,`bordertype` ,`bordersize` ,`bordercolor` ,`titleBackground` ,`titleColor` ,`messageBackground` ,`corners`) values ('$this->timeToExecute','$this->timeToClose','$this->timeToReExecute','$this->url','$this->showClose','$this->showCounter','$this->showHomePage','$this->showAllPages','$this->showAllPosts','$this->title','$this->txtMessage','$this->colorscheme','$this->layout','$this->opacity','$this->bordert','$this->borders','$this->borderc','$this->titlebg','$this->titlec','$this->msgbg','$this->corners')");
            if ($insert == FALSE)
                return false;
            else
                return true;
        }
        else {
            $update = $wpdb->query("update " . $wpdb->prefix . "liketounlock set title='$this->title',timetoexecute='$this->timeToExecute',timetoclose='$this->timeToClose',timetoreexecute='$this->timeToReExecute',url='$this->url',showclose='$this->showClose',showcounter='$this->showCounter',showhomepage='$this->showHomePage',showallpages='$this->showAllPages',showallposts='$this->showAllPosts',txtmessage='$this->txtMessage',colorscheme='$this->colorscheme',layout='$this->layout',opacity='$this->opacity',bordertype='$this->bordert',bordersize='$this->borders',bordercolor='$this->borderc',titleBackground='$this->titlebg',titleColor='$this->titlec',messageBackground='$this->msgbg',corners='$this->corners' where id_like=$id_like");
            if ($update == FALSE)
                return false;
            else
                return true;
        }
    }

    public static function listLocks() {
        global $wpdb;
        $results = $wpdb->get_results("select id_like, title, showhomepage, showallpages, showallposts from " . $wpdb->prefix . "liketounlock order by id_like desc");
        return $results;
    }

    public static function delete($id_like) {
        global $wpdb;
        $delete = $wpdb->query("delete from " . $wpdb->prefix . "liketounlock where id_like = $id_like");
        if ($delete == FALSE)
            return false;
        else
            return true;
    }

    public function debug() {
        global $wpdb;
        print_r($_POST);
        $wpdb->show_errors();
        $wpdb->print_error();
    }

    public static function getData($id_like) {
        global $wpdb;
        $data = $wpdb->get_row("select * from " . $wpdb->prefix . "liketounlock where id_like='$id_like'");
        return $data;
    }

    public static function getThis($str) {
        global $wpdb;
        $data = $wpdb->get_row("select * from " . $wpdb->prefix . "liketounlock $str");
        return $data;
    }

}

?>