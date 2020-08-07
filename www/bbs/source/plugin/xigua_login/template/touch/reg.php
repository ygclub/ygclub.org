<?php exit('hrh');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="{CHARSET}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta content="telephone=no" name="format-detection"/>
    <title>{$navtitle}</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="source/plugin/xigua_login/static/common.css?t={$pluginversion}" rel="stylesheet"/>
    <script src="source/plugin/xigua_login/static/jquery.min.js?t={$pluginversion}"></script>
    <style>
        .login_from, li{    margin-top: 15px;}
        .form-control .sel_list, .form-control input.p_fre{border:1px solid #ccc;border-radius:2px}
        .container_map{padding: 0;width:100%;min-height:100vh;}
        .form-control{margin:15px 25px;}
        .buttons-tab{position:relative;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;width:100%;background-color:rgba(245,247,248,.95);height:50px;overflow:hidden;line-height:50px}
        /*.buttons-tab:before{content:" ";position:absolute;left:0;bottom:0;width:100%;height:1px;border-top:1px solid #ccc;color:#ccc;-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(.5);transform:scaleY(.5)}*/
        .buttons-tab .a{position:relative;display:block;-webkit-box-flex:1;-webkit-flex:1;-ms-flex:1;flex:1;text-align:center;font-size:18px}
        .buttons-tab .a.active{color:$config[logincolor];border-color:$config[logincolor]}
        /*.buttons-tab .a.active:before{content:" ";position:absolute;left:0;bottom:0;width:100%;height:1px;border-top:1px solid $config[logincolor];color:$config[logincolor];-webkit-transform-origin:0 0;transform-origin:0 0;-webkit-transform:scaleY(.5);transform:scaleY(.5)}*/
        .buttons-tab .a:last-child:after{content: " ";position: absolute;left: 0;top: 0;width: 1px;height:49px;border-left: 1px solid #ccc;color: #ccc; -webkit-transform-origin: 0 0; transform-origin: 0 0;-webkit-transform: scaleX(.5);transform: scaleX(.5)}
        .tiph{padding:25px 25px 0;font-size:16px!important;}
        $customstyle
    </style>
</head>
<body>
<div id="page-loading-overlay">
    <div class="ajxloading"></div>
</div>
{eval $loginhash = 'L'.random(4);}
<!-- userinfo start -->
<div class="container_map container_map_ami">

    <div class="buttons-tab">
        <a class="a <!--{if $_GET[rb]==0}-->active<!--{/if}-->" href="plugin.php?id=xigua_login:reg&rb=0">$navtitle2</a>
        <a class="a <!--{if $_GET[rb]==1}-->active<!--{/if}-->" href="plugin.php?id=xigua_login:reg&rb=1">$navtitle1</a>
    </div>
    <!--{if $_GET[has]}-->
    <h2 class="tiph">{$config[confilt]}</h2>
    <!--{else}-->
    <h2 class="tiph">{$tip}</h2>
    <!--{/if}-->

    <form onsubmit="return recheck();" class="form-control" action="plugin.php?id=xigua_login:reg" method="post" <!--{if $config[charset]}-->accept-charset="{$config[charset]}"<!--{/if}-->>
        <input type="hidden" name="formhash" id="formhash" value='{FORMHASH}' />
        <input type="hidden" name="referer" id="referer" value="{$url}" />
        <input type="hidden" name="reg" value="1" />
        <input type="hidden" name="rb" value="<!--{if $_GET[rb]==0}-->1<!--{else}-->0<!--{/if}-->" />

        <div class="login_from">
            <ul>
                <li>
                    <input type="text" value="<!--{if $_GET[rb]!=0}-->{$reg_nickname}<!--{/if}-->" tabindex="2" class="px p_fre" size="30" autocomplete="off" name="username" placeholder="{lang inputyourname}" >
                    {if $config[showborder]}<div class="border"></div>{/if}
                </li>

                <!--{if $_GET[rb]==0}-->
                <li class="btn_login"><input type="password" tabindex="1" class="px p_fre" size="30" value="" name="password" placeholder="{lang login_password}" >
                    {if $config[showborder]}<div class="border"></div>{/if}
                </li>
                <li class="questionli">
                    <div class="login_select">
					<!--<span class="login-btn-inner">
						<span class="login-btn-text">
							<span class="span_question">{lang security_question}</span>
						</span>
						<span class="icon-arrow">&nbsp;</span>
					</span>-->
                        <select id="questionid_{$loginhash}" name="questionid" class="sel_list">
                            <option value="0" selected="selected">{lang security_question}</option>
                            <option value="1">{lang security_question_1}</option>
                            <option value="2">{lang security_question_2}</option>
                            <option value="3">{lang security_question_3}</option>
                            <option value="4">{lang security_question_4}</option>
                            <option value="5">{lang security_question_5}</option>
                            <option value="6">{lang security_question_6}</option>
                            <option value="7">{lang security_question_7}</option>
                        </select>
                    </div>
                </li>
                <li class="bl_none answerli" style="display:none;"><input type="text" name="answer" id="answer_{$loginhash}" class="px p_fre" size="30" placeholder="{lang security_a}"></li>
                <!--{/if}-->
            </ul>
        </div>
        <div class="btn_login"><button tabindex="3" value="true" name="submit" type="submit" class="btn btn-outline"><span><!--{if $_GET[rb]!=0}-->{$config['newbtn']}<!--{else}-->$config[bindexbtn]<!--{/if}--></span></button></div>

    </form>
</div>
<script src="source/plugin/xigua_login/static/custom.js"></script>
<script type="text/javascript">
    (function() {
        $(document).on('change', '.sel_list', function() {
            var obj = $(this);
            $('.span_question').text(obj.find('option:selected').text());
            if(obj.val() == 0) {
                $('.answerli').css('display', 'none');
                $('.questionli').addClass('bl_none');
            } else {
                $('.answerli').css('display', 'block');
                $('.questionli').removeClass('bl_none');
            }
        });
    })();
    function recheck(){
        if($.trim($('input[name="username"]').val())==''){
            alert('{lang inputyourname}');
            return false;
        }
        return true;
    }
</script>
</body>
</html>
