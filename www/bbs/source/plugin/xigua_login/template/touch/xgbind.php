<?php exit;?>
<!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="{CHARSET}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{eval echo lang('plugin/xigua_login', 'bind_now');}</title>
    <link href="source/plugin/xigua_login/static/common.css?t={$pluginversion}" rel="stylesheet"/>
    <script src="source/plugin/xigua_login/static/jquery.min.js?t={$pluginversion}"></script>
    <style>
        .login_from li:nth-child(2) input.p_fre, .login_from li:first-child input.p_fre{
            border: 1px solid #ccc;
            border-radius:5px!important;
            border-bottom-right-radius:5px!important;
            border-bottom-left-radius:5px!important;
        }
        {$customstyle}</style>
</head>
<body style="padding:20px">


<!--{if !$fetch}-->
<p class="pbm bbda xi2">{$tiptip}</p>
<br />


<p class="btn_login">
    <a class="btn btn-outline" href="plugin.php?id=xigua_login:login&bindfast=1" >{eval echo lang('plugin/xigua_login', 'bind_now');}</a>
</p>
<!--{else}-->
<p class="pbm bbda xi1">{lang xigua_login:wechat_spacecp_bind_title}</p>
<br />

<!--{if $fetch['isregister']}-->
<h2>
    <a href="javascript:;" onclick="display('unbind');" class="xi2">{lang xigua_login:wechat_spacecp_setpw}</a>
</h2>

<form id="wechatform" class="form-control" method="post" autocomplete="off" action="plugin.php?id=xigua_login:xgbind&rfr={eval echo urlencode(dreferer());}">
    <input type="hidden" name="formhash" value="{FORMHASH}" />
    <div class="password login_from">
        <ul>
            <li>
                <label>{lang xigua_login:wechat_spacecp_pw}</label>
                <input type="password" name="newpassword1" size="30" class="px p_fre" tabindex="1" />

            </li>
            <li>
                <label>{lang xigua_login:wechat_spacecp_repw}</label>
                <input type="password" name="newpassword2" size="30" class="px p_fre" tabindex="2" />
            </li>
            <li class="btn_login">
                <button type="submit" name="resetpwsubmit" value="yes" class="btn btn-outline"><strong>{lang submit}</strong></button>
            </li>
        </ul>
    </div>
</form>
<br />
<!--{/if}-->

<div id="unbind">
    <form id="wechatform" class="form-control" method="post" autocomplete="off" action="plugin.php?id=xigua_login:xgbind&rfr={eval echo urlencode(dreferer());}">
        <input type="hidden" name="formhash" value="{FORMHASH}" />
        <!--{if $fetch['isregister']}-->
        <p class="mtm mbm">
            {lang xigua_login:wechat_spacecp_setpw_first}
        </p>
        <!--{else}-->
        <p class="mtm mbm">
            {lang xigua_login:wechat_spacecp_unbind}
        </p>
        <p class="btn_login">
        <button type="submit" name="unbindsubmit" value="yes" class="btn btn-outline"><strong>{lang xigua_login:wechat_spacecp_unbind_button}</strong></button>
        </p>
        <!--{/if}-->
    </form>
</div>

</form>
<!--{/if}-->

<script>

    function display(id) {
        var obj = document.getElementById(id);
        if(obj.style.visibility) {
            obj.style.visibility = obj.style.visibility == 'visible' ? 'hidden' : 'visible';
        } else {
            obj.style.display = obj.style.display == '' ? 'none' : '';
        }
    }

</script>


</body>
</html>