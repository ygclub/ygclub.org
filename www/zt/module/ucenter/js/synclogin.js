
var _step=3;
function setime()
{
	$('#dvtime').html(_step);
	if(_step==0)
	{
		location.href=_linkurl;
	}
	else
	{
		setTimeout(setime,1000);
		_step--;
	}
}

$(document).ready(function(){
	setime();
});
