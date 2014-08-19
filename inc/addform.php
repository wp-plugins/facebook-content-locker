<script type="text/javascript">
 
    jQuery(document).ready(function() {
        jQuery("th[align=center]:eq(0)").css({'border-radius':'20px 20px 0px 0px'});
        jQuery("td[align=center]").css({'border-radius':'0px 0px 20px 20px'});

        jQuery('#color1').hide();
        jQuery('#color1').farbtastic(".color1");
        jQuery(".color1").click(function(){jQuery('#color1').slideToggle()});
    
        jQuery('#color2').hide();
        jQuery('#color2').farbtastic(".color2");
        jQuery(".color2").click(function(){jQuery('#color2').slideToggle()});
    
        jQuery('#color3').hide();
        jQuery('#color3').farbtastic(".color3");
        jQuery(".color3").click(function(){jQuery('#color3').slideToggle()});
    
        jQuery('#color5').hide();
        jQuery('#color5').farbtastic(".color5");
        jQuery(".color5").click(function(){jQuery('#color5').slideToggle()});
    });
    var tmpT = "";
    var tmpU = "";
    function autotitle()
    {
        if (document.addform.autoTitle.checked == true)
        {
            tmpT = document.addform.title.value ;
            jQuery("input[name=title]").attr({"disabled":"disabled"});
            jQuery("input[name=title]").attr({"value":"auto"});
        }
        else
        {
            jQuery("input[name=title]").removeAttr("disabled");
            jQuery("input[name=title]").attr({"value":tmpT});                
        }
    }
    
    function autoUrls()
    {
        if (document.addform.autoUrl.checked == true)
        {
            tmpU = document.addform.url.value ;
            jQuery("input[name=url]").attr({"disabled":"disabled"});
            jQuery("input[name=url]").attr({"value":"auto"});
        }
        else
        {
            jQuery("input[name=url]").removeAttr("disabled");
            jQuery("input[name=url]").attr({"value":tmpU});                
        }
    }
 
</script>
<form method="post" action="" name="addform">
    <input type="hidden" name="id_like" value="<?php echo intval(@$_GET["id_like"]); ?>" />

    <table align="left" bgcolor="white">
        <tr align="left">

            <th colspan="3" align="center" style="background-color:#EEEEEE"><h2> Popup Content</h2></th>
        </a>
        </tr>
        <tr align="left" class="popoptions">
            <th>Popup Title</th>
            <td>:</td>
            <td>
                <?php
                if ($row->title == "auto") {
                    $row->title = "auto";
                    $dis1 = 'disabled="disabled"';
                    $ch1 = 'checked="checked"';
                } else {
                    $dis1 = "";
                    $ch1 = "";
                }

                if ($row->url == "auto") {
                    $row->url = "auto";
                    $dis2 = 'disabled="disabled"';
                    $ch2 = 'checked="checked"';
                } else {
                    $dis2 = "";
                    $ch2 = "";
                }
                ?>
                &nbsp;&nbsp;<input type="text" name="title" size="64" value="<?php echo $row->title; ?>" <?php echo $dis1; ?>/>
                <br/> &nbsp;&nbsp;<b>Or</b> <br/>
                &nbsp;&nbsp;<label><input type="checkbox" name="autoTitle" onchange="autotitle();" <?php echo $ch1; ?>/> Auto Title</label> ( <img src="<?php echo $infos; ?>"/> takes automatically the title of the active post/page )
            </td>
        </tr>
        <tr align="left" class="popoptions">
            <th>Pop Message ( <img src="<?php echo $infos; ?>"/> HTML is allowed)</th>
            <td>:</td>
            <td>
                <div id="stuff">
                    <?php
                    if (get_bloginfo("version") < 3.3)
                        the_editor("$row->txtmessage", "txtmessage");
                    else
                        wp_editor("$row->txtmessage", "txtmessage");

                    if (!@$row->titleBackground)
                        $row->titleBackground = "#EEEEEE";

                    if (!@$row->titleColor)
                        $row->titleColor = "#ffffff";

                    if (!@$row->messageBackground)
                        $row->messageBackground = "#ffffff";

                    if (!@$row->bordercolor)
                        $row->bordercolor = "#EEEEEE";

                    if (!@$row->bordersize)
                        $row->bordersize = "4";

                    if (!@$row->opacity)
                        $row->opacity = "50";
                    
                    if (!@$row->timetoexecute)
                        $row->timetoexecute = "1";
                    
                    if (!@$row->timetoclose)
                        $row->timetoclose = "30";
                    ?>

                </div>
            </td>
        </tr>
        <tr align="left">
            <th colspan="3" align="center" style="background-color:#EEEEEE"><h2> Popup Style</h2></th>
        </tr>
        <tr align="left">
            <th>Title Background Color</th>
            <td>:</td>
            <td><input type="text" name="titlebg" class="color1" size="7" value="<?php echo $row->titleBackground; ?>"/> ( <img src="<?php echo $infos; ?>"/> click to show/hide color picker) 
                <div id="color1"></div>
            </td>
        </tr>
        <tr align="left">
            <th>Title Font Color</th>
            <td>:</td>
            <td><input type="text" name="titlecolor" class="color2" size="7" value="<?php echo $row->titleColor; ?>"/>
                <div id="color2"></div></td>
        </tr>
        <tr align="left">
            <th>Message Background</th>
            <td>:</td>
            <td><input type="text" name="msgbg" class="color3" size="7" value="<?php echo $row->messageBackground; ?>"/> 
                <div id="color3"></div></td>
        </tr>
        <tr align="left">
            <th>Border Type</th>
            <td>:</td>
            <td>
                <?php
                $sld = ($row->bordertype == "solid") ? "selected='selected'" : "";
                $dtd = ($row->bordertype == "dotted") ? "selected='selected'" : "";
                $dsd = ($row->bordertype == "dashed") ? "selected='selected'" : "";
                ?>
                <select name="bordert">
                    <option value="solid" <?php echo $sld; ?>>solid</option>
                    <option value="dotted"<?php echo $dtd; ?>>dotted</option>
                    <option value="dashed"<?php echo $dld; ?>>dashed</option>
                </select>
            </td>
        </tr>
        <tr align="left">
            <th>Border Size</th>
            <td>:</td>
            <td><input type="text" name="borders" size="7" value="<?php echo $row->bordersize; ?>"/> </td>
        </tr>
        <tr align="left">
            <th>Border Color</th>
            <td>:</td>
            <td><input type="text" name="borderc" class="color5" size="7" value="<?php echo $row->bordercolor; ?>"/>
                <div id="color5"></div></td>
        </tr>
        <tr align="left">
            <?php
            $shr = ($row->corners == "sharp") ? "selected='selected'" : "";
            $rnd = ($row->corners == "rounded") ? "selected='selected'" : "";
            ?>
            <th>Corners</th>
            <td>:</td>
            <td><select name="corners">
                    <option value="rounded" <?php echo $rnd; ?>>Rounded</option>
                    <option value="sharp" <?php echo $shr; ?>>Sharp</option>
                </select>
            </td> 
        </tr>
        <tr align="left">
            <th>Background Opacity</th>
            <td>:</td>
            <td><input type="text" name="opacity" size="7" value="<?php echo $row->opacity; ?>"/> ( <img src="<?php echo $infos; ?>"/> between 0 & 100 | 0 = transparent & 100 = dark)</td>
        </tr>

        <tr align="left">
            <th colspan="3" align="center" style="background-color:#EEEEEE"><h2> Like Button Options</h2></th>
        </tr>
        <tr align="left" class="popoptions">
            <th>URL to Like</th>
            <td>:</td>
            <td><input type="text" name="url" size="64" value="<?php echo $row->url; ?>" <?php echo $dis2; ?>/> ( <img src="<?php echo $infos; ?>"/> without http://)
                <br/> &nbsp;&nbsp;<b>Or</b> <br/>
                &nbsp;&nbsp;<label><input type="checkbox" name="autoUrl" onchange="autoUrls();" <?php echo $ch2; ?>/> Auto URL</label> ( <img src="<?php echo $infos; ?>"/> takes automatically the URL of the active post/page )
            </td>
        </tr>
        <tr align="left">
            <?php
            $stn = ($row->layout == "standard") ? "selected='selected'" : "";
            $buc = ($row->layout == "button_count") ? "selected='selected'" : "";
            $box = ($row->layout == "box_count") ? "selected='selected'" : "";
            ?>
            <th>Like Button Layout</th>
            <td>:</td>
            <td><select name="layout">
                    <option value="standard" <?php echo $stn; ?>>standard</option>
                    <option value="button_count" <?php echo $buc; ?>>button_count</option>
                    <option value="box_count" <?php echo $box; ?>>box_count</option>
                </select>
            </td>
        </tr>
        <tr align="left">
            <?php
            $light = ($row->colorscheme == "light") ? "selected='selected'" : "";
            $dark = ($row->colorscheme == "dark") ? "selected='selected'" : "";
            ?>
            <th>Color Scheme</th>
            <td>:</td>
            <td><select name="color">
                    <option value="light" <?php echo $light; ?>>light</option>
                    <option value="dark" <?php echo $dark; ?>>dark</option>
                </select>
            </td>
        </tr>
        <tr align="left">
            <th colspan="3" align="center" style="background-color:#EEEEEE"><h2> Timing options</h2></th>
        </tr>
        <tr align="left">
            <th>Delay time</th>
            <td>:</td>
            <td><input type="text" name="tte" size="5" value="<?php echo $row->timetoexecute; ?>"/> ( <img src="<?php echo $infos; ?>"/> in seconds)</td>
        </tr>
        <tr align="left">
            <th>Lock time (countdown timer)</th>
            <td>:</td>
            <td><input type="text" name="ttc" size="5" value="<?php echo $row->timetoclose; ?>"/> ( <img src="<?php echo $infos; ?>"/> in seconds)</td>
        </tr>
        <tr align="left">
            <th colspan="3" align="center" style="background-color:#EEEEEE"><h2> Visibility options</h2></th>
        </tr>
        <tr align="left">
            <?php
            $ttrr = ($row->timetoreexecute == 1) ? "checked='checked'" : "";
            ?>
            <th>Keep showing the popup even if the user ignores it</th>
            <td>:</td>
            <td>
                <label><input type="checkbox" name="ttrr" value="1" <?php echo $ttrr; ?>/> Yes </label> 
            </td>
        </tr>
        <?php
        $scl = ($row->showclose == 1) ? "checked='checked'" : "";
        $sco = ($row->showcounter == 1) ? "checked='checked'" : "";
        $sohp = ($row->showhomepage == 1) ? "checked='checked'" : "";
        $sopg = ($row->showallpages == 1) ? "checked='checked'" : "";
        $sops = ($row->showallposts == 1) ? "checked='checked'" : "";
        ?>
        <tr align="left">
            <th>Show the exit button</th>
            <td>:</td>
            <td><label><input type="checkbox" name="scl" value="1" <?php echo $scl; ?>/> Yes </label></td>
        </tr>
        <tr align="left">
            <th>Show the countdown timer</th>
            <td>:</td>
            <td><label><input type="checkbox" name="sco" value="1" <?php echo $sco; ?>/> Yes </label> </td>
        </tr>
        <tr align="left">
            <th>Enable on the homepage</th>
            <td>:</td>
            <td><label><input type="checkbox" name="sohp" value="1" <?php echo $sohp; ?>/> Yes </label></td>
        </tr>
        <tr align="left">
            <th>Enable on all pages</th>
            <td>:</td>
            <td><label><input type="checkbox" name="sopg" value="1" <?php echo $sopg; ?>/> Yes </label> </td>
        </tr>
        <tr align="left">
            <th>Enable on all posts</th>
            <td>:</td>
            <td><label><input type="checkbox" name="sops" value="1" <?php echo $sops; ?>/> Yes </label></td>
        </tr>
        <tr>
            <td colspan="3" align="center" style="background-color:#EEEEEE">
                <div style="width:190px;text-align: center">
                    <input type="submit" name="save" value="Save" class="button-primary"/> <input class="preview button" type="reset" value="Cancel"/> <input class="preview button" type="button" value="Preview" onclick="wpliketounlock();"/> 
                </div>
            </td>
        </tr>
    </table>
</form>

<div id="wpliketounlock" style="display:none;z-index: 0">
    <div class="popupTitle" style="padding:5px 5px 5px 5px;">
        <span class="CounterAndClose">

            <div style="position:absolute; top:-37px; right:16px; font-weight: bold; font-size: small; color:white; font-size:18px" class="timetoclose">
            </div>

            <div style="position:absolute; top:-160px;background-color: white; border: 3px dashed red; color:black">
                <img style="float:left" src="<?php echo $warn; ?>"/> THIS IS ONLY A <strong><u> PREVIEW </u></strong>, TO VIEW THE FULL OPTIONS YOU MUST EXECUTE IT ON A REAL PAGE/POST<br/>
                <center><font color="red">PRESS "ESC" TO CLOSE</font></center>
            </div>
            <a ref="#" id="exitbutton" style="color:red;text-decoration:underline;cursor:pointer;cursor:hand;position:absolute; top:-15px; right:-16px; " onclick="javascript:$.modal.close();">
                <img src="<?php echo $close; ?> "/>
            </a>

        </span>
        <b></b>
    </div>
    <div id="popupmsg">
    </div>
</div>

<script type="text/javascript">
    function wpliketounlock()
    {
        
        if (document.addform.sco.checked == true)
            jQuery(".timetoclose").html(document.addform.ttc.value+"s").show();
        else
            jQuery(".timetoclose").hide();
       
        if (document.addform.scl.checked == true)
            jQuery("#exitbutton").show();
        else
            jQuery("#exitbutton").hide();        

        jQuery("div.popupTitle b").html(document.addform.title.value);
        jQuery("div.popupTitle").css({'background-color':''+document.addform.titlebg.value+'','color':''+document.addform.titlecolor.value+''})
        jQuery("#popupmsg").html(tinyMCE.get('txtmessage').getContent());
        jQuery("#popupmsg").css({'background-color':''+document.addform.msgbg.value+''});

        
        jQuery('#branding').css({'z-index':'1'});
        jQuery('#wpliketounlock').css({'height':'auto','width':'450','padding':'0px'});
        jQuery('#wpliketounlock').modal({
            opacity:document.addform.opacity.value,
            escClose:true,
            overlayCss: {backgroundColor:"black"},
            onClose: function (dialog) {
                dialog.data.fadeOut('slow', function () {
                    dialog.container.slideUp('fast', function () {
                        dialog.overlay.fadeOut('slow', function () {
                            jQuery.modal.close();
                        });
                    });
                });
            }
        });
        jQuery('#simplemodal-container').css({'background-color':'white'});
        jQuery('#simplemodal-container').css({'border':''+document.addform.borders.value+'px '+document.addform.bordert.value+' '+document.addform.borderc.value+''});
                

        if (document.addform.corners.value == "rounded") {
    
            jQuery('#simplemodal-container').css({'border-radius':'10px','-webkit-border-radius':'10px','-moz-border-radius':'10px','behavior': 'url(<?php echo $pie; ?>)'});
            jQuery('#likeboxdiv').css({'border-radius':'0px 0px 10px 10px','-webkit-border-radius':'0px 0px 10px 10px','-moz-border-radius':'0px 0px 10px 10px','behavior': 'url(<?php echo $pie; ?>)'});
            jQuery('.popupTitle').css({'border-radius':'8px 8px 0px 0px','-webkit-border-radius':'8px 8px 0px 0px','-moz-border-radius':'8px 8px 0px 0px','behavior': 'url(<?php echo $pie; ?>)'});
            jQuery('.simplemodal-wrap').css({'border-radius':'10px 10px 10px 10px','-webkit-border-radius':'10px 10px 10px 10px','-moz-border-radius':'10px 10px 10px 10px','behavior': 'url(<?php echo $pie; ?>)'});
    
        }
        jQuery(".simplemodal-wrap").css({'background-color':''+document.addform.msgbg.value+''});

    }
</script>