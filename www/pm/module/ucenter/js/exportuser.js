function checkall(checker, id)
{
    $('#' + id + ' input').each(function() 
    {
        $(this).attr("checked", checker.checked)
    });
}

function output(msg)
{
	$('#msg').html(msg);

	$('#submit').val("导出");
	$('#submit').attr('disabled',false);
	$('#submit').removeClass();
	$('#submit').addClass('button-s');
}