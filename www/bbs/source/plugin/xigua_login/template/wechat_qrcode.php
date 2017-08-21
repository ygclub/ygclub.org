<?php exit('hrh');?>
<!--{template common/header}-->
<h3 class="flb">
    <em id="return_$_GET['handlekey']"><!--{if $_G['uid']}-->{lang xigua_login:wechat_bind}<!--{else}-->{lang xigua_login:wechat_login}<!--{/if}--></em>
			<span>
				<a href="javascript:;" class="flbc" onclick="clearTimeout(wechat_checkST);hideWindow('$_GET['handlekey']')" title="{lang close}">{lang close}</a>
			</span>
</h3>
<div class="c" align='center' style="width:240px;height:250px">
    <img width="220" height="220" src="$qrcodeurl" onerror="this.error=null;this.src='$qrcodeurl2'" />
    <div style="text-align:center;color:#666;font-size:14px">$tiptip2<div>
    $redirect_uri
</div>
<script>
var wechat_checkST = null, wechat_checkCount = 0;
function wechat_checkstart() {
    wechat_checkST = setTimeout(function () {wechat_check()}, 2000);
}
function wechat_check() {
    var x = new Ajax();
    x.get('plugin.php?id=xigua_login:login&check=$codeenc', function(s, x) {
        s = trim(s);
        if(s != 'done') {
            if(s == '1') {
                wechat_checkstart();
            }
            wechat_checkCount++;
            if(wechat_checkCount >= 100) {
                clearTimeout(wechat_checkST);
                hideWindow('$_GET['handlekey']');
            }
        } else {
            clearTimeout(wechat_checkST);
            <!--{if $_G['setting']['allowsynlogin']}-->
            window.location.href = 'plugin.php?id=xigua_login:login&synclogin=1&url_forward='+encodeURIComponent(window.location.href);
            <!--{else}-->
            window.location.href = window.location.href;
            <!--{/if}-->
        }
    });
}
wechat_checkstart();
</script>
<!--{template common/footer}-->