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
    <style>{$customstyle}</style>
    </head>
<body>
<div id="page-loading-overlay">
    <div class="ajxloading"></div>
</div>
<!-- userinfo start -->
<div class="container_map container_map_ami">
    <div class="logo">$logo</div>
    <form class="form-control" id="loginform" method="post" action="member.php?mod=logging&action=login&loginsubmit=yes&loginhash=$loginhash&mobile=2" onsubmit="{if $_G['setting']['pwdsafety']}pwmd5('password3_$loginhash');{/if}" >
        <input type="hidden" name="formhash" id="formhash" value='{FORMHASH}' />
        <input type="hidden" name="referer" id="referer" value="<!--{if dreferer()}-->{echo dreferer()}<!--{else}-->forum.php?mobile=2<!--{/if}-->" />
        <input type="hidden" name="fastloginfield" value="username">
        <input type="hidden" name="cookietime" value="2592000">
        <!--{if $auth}-->
        <input type="hidden" name="auth" value="$auth" />
        <!--{/if}-->
        <div class="login_from">
            <ul>
                <li><input type="text" value="" tabindex="1" class="px p_fre" size="30" autocomplete="off" value="" name="username" placeholder="{lang inputyourname}" fwin="login"> {if $config[showborder]}<div class="border"></div>{/if}</li>
                <li><input type="password" tabindex="2" class="px p_fre" size="30" value="" name="password" placeholder="{lang login_password}" fwin="login"> {if $config[showborder]}<div class="border"></div>{/if}</li>
                <li class="questionli">
                    <div class="login_select">
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
            </ul>
            <!--{if $seccodecheck}-->
            {eval
            $sechash = 'S'.random(4);
            $sectpl = !empty($sectpl) ? explode("<sec>", $sectpl) : array('<br />',': ','<br />','');
                $ran = random(5, 1);
                }
                <!--{if $secqaacheck}-->
                <!--{eval
                    $message = '';
                    $question = make_secqaa();
                    $secqaa = lang('core', 'secqaa_tips').$question;
                }-->
                <!--{/if}-->
                <!--{if $sectpl}-->
                <!--{if $secqaacheck}-->
                <p>
                    {lang secqaa}:
                    <span class="xg2">$secqaa</span>
                    <input name="secqaahash" type="hidden" value="$sechash" />
                    <input name="secanswer" id="secqaaverify_$sechash" type="text" class="txt" />
                </p>
                <!--{/if}-->
                <!--{if $seccodecheck}-->
                <div class="sec_code vm">
                    <input name="seccodehash" type="hidden" value="$sechash" />
                    <input type="text" class="txt px vm" autocomplete="off" value="" id="seccodeverify_$sechash" name="seccodeverify" placeholder="{lang seccode}" fwin="seccode">
                    <img src="misc.php?mod=seccode&update={$ran}&idhash={$sechash}&mobile=2" class="seccodeimg"/>
                </div>
                <!--{/if}-->
                <!--{/if}-->
                <script type="text/javascript">
                    (function() {
                        $('.seccodeimg').on('click', function() {
                            $('#seccodeverify_$sechash').attr('value', '');
                            var tmprandom = 'S' + Math.floor(Math.random() * 1000);
                            $('.sechash').attr('value', tmprandom);
                            $(this).attr('src', 'misc.php?mod=seccode&update={$ran}&idhash='+ tmprandom +'&mobile=2');
                        });
                    })();
                </script>

            <!--{/if}-->
        </div>
        <div class="btn_login"><button tabindex="3" value="true" name="submit" type="submit" class="btn btn-outline"><span>{lang login}</span></button></div>
        <!--{if $config[showonekey]}-->
        <!--{if $inwechat}-->
            <div class="btn_login onekey">
                <button tabindex="3" type="button" class="btn btn-orange"><span>$config[onekey]</span></button>
            </div>
        <!--{else}-->
            <div class="btn_login onekey">
                <button tabindex="3" value="true" type="button" class="btn btn-orange"><span>$config[onekey]</span></button>
            </div>
        <!--{/if}-->
        <!--{/if}-->
    </form>

    <div class="reg_link">
        <div class="reg_tp">$config[sanguide]</div>
        <div class="loginbtn">
            <!--{if !$config[hidebtn]}-->
            <div class="btn_wechatlogin">
                <a href="{if $inwechat}$wehaturl{else}javascript:alert('$please_inwechat');{/if}"><span class="icon-wechat"></span></a>
                <span>$config[wechatword]</span>
            </div>
            <!--{/if}-->

            <!--{if $config[qq_uri]}-->
                <!--{eval $qq_uri= $config[qq_uri];}-->
            <!--{else}-->
                <!--{if $_G['setting']['connect']['allow'] && !$_G['setting']['bbclosed']}-->
                <!--{eval $qq_uri= $_G['connect']['login_url']."&statfrom=login_simple";}-->
                <!--{elseif is_file(DISCUZ_ROOT.'/qq.php')}-->
                <!--{eval $qq_uri= 'qq.php?mod=login&referer='.urlencode(dreferer());}-->
                <!--{/if}-->
            <!--{/if}-->

            <!--{if $qq_uri}-->
            <div class="btn_qqlogin">
                <a href="$qq_uri"><span class="icon-qq"></span></a>
                <span>$config[qqword]</span>
            </div>
            <!--{/if}-->
        </div>
        <!--{if $_G['setting']['regstatus']}-->
        <a href="member.php?mod={$_G[setting][regname]}">$config[regfont]</a>
        <!--{/if}-->
    </div>
</div>
<!-- userinfo end -->

<!--{if $_G['setting']['pwdsafety']}-->
<script type="text/javascript" src="{$_G['setting']['jspath']}md5.js?{VERHASH}" reload="1"></script>
<!--{/if}-->
<!--{eval updatesession();}-->

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
$('.onekey').on('click', function(){
{if $inwechat} window.location.href = '$wehaturl'; {else} window.location.href = '$qq_uri';{/if}
});
})();
</script>


<!--{if !$_G['uid'] && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'appbyme') !== false}-->
<script src="https://market-cdn.app.xiaoyun.com/release/sq-2.3.js"></script>
<script type="text/javascript">
    connectSQJavascriptBridge(function(){
        sq.logout(function(info){
            sq.login(function(userInfo){
                if(userInfo.errmsg == "OK"){
                    window.location.href="<!--{if dreferer()}-->{echo dreferer()}<!--{else}-->forum.php?mobile=2<!--{/if}-->";
                }
            });
        });
    });
</script>
<!--{/if}-->

</body>
</html>