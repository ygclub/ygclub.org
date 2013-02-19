<?php

// 自定义部分排序
function ygclub_party_order_fields($a){
    global $SF_CONFIG_VALUES;
    $b = $a['field'];
	if ($b){
		foreach($b as $c=>$d){
			if ($d){
				$o = $a['order'][$c];
				$e[$o]['field']		= $d;
				$e[$o]['name']		= $a['name'][$c];
				$e[$o]['type']		= $a['type'][$c];
				$e[$o]['default']	= $a['default'][$c];
				$e[$o]['bold']		= $a['bold'][$c];
				$e[$o]['color']		= $a['color'][$c];
				$e[$o]['must']		= $a['must'][$c];
				$e[$o]['order']		= $a['order'][$c];
				$e[$o]['html']		= ygclub_party_form($d,$a['type'][$c],$a['default'][$c],$SF_CONFIG_VALUES[$d]);
				$e[$o]['value']		= is_array($SF_CONFIG_VALUES[$d]) ? implode(',',$SF_CONFIG_VALUES[$d]) : nl2br($SF_CONFIG_VALUES[$d]) ;
			}
		}
		ksort($e,SORT_NUMERIC);
    }
	return $e;
}

// 自定义部分队列
function ygclub_party_ck_fields($a){
	$b = $a['field'];
	foreach($b as $c=>$d){
		if ($d){
			$e['field'][]		= $d;
			$e['name'][]		= $a['name'][$c];
			$e['type'][]		= $a['type'][$c];
			$e['default'][]		= $a['default'][$c];
			$e['bold'][]		= $a['bold'][$c];
			$e['color'][]		= $a['color'][$c];
			$e['must'][]		= $a['must'][$c];
			$e['order'][]		= $a['order'][$c];
		}
	}
	return $e;
}

// 表单部分
function ygclub_party_form($field,$type,$default,$value){
	$html = '';
	$value = $value ? $value : $default;
	switch ($type){
		case 'text':
			$html = "<input name='SFDC[$field]' class='txt' type='text' value=\"$value\"/>";
		break;
		case 'textarea':
			$html = "<textarea name='SFDC[$field]' rows='6' cols='80'>$value</textarea>";
		break;
		case 'radio':
			if (strstr($default,'|')){
				$list = explode("|",$default);
				foreach($list as $k=>$v){
					$checked = $v==$value ? "checked" : "";
					$html .= "<label for='SFDC_{$field}_{$k}'><input type='radio' name='SFDC[$field]' id='SFDC_{$field}_{$k}' $checked value='$v'/>$v</label>&nbsp;";
				}
			}else{
				$html = "<font color=red>请重新设置，多个请用 | 分开。</font>";
			}
		break;
		case 'checkbox':
			if (strstr($default,"|")){
				$list = explode("|",$default);
				foreach($list as $k=>$v){
					$checked = is_array($value) && in_array($v,$value) ? "checked" : "";
					$html .= "<label for='SFDC_{$field}_{$k}'><input type='checkbox' name='SFDC[$field][]' id='SFDC_{$field}_{$k}' $checked value='$v'/>$v</label>&nbsp;";
				}
			}else{
				$html = "<font color=red>请重新设置，多个请用 | 分开。</font>";
			}
		break;
	}
	return $html;
}

?>
