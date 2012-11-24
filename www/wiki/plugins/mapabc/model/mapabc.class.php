<?php
!defined('IN_HDWIKI') && exit('Access Denied');

//error_reporting(E_ALL);
//ini_set('display_errors', 'ON');

class mapabcmodel {

    var $db;
    var $base;

    function mapabcmodel(&$base) {
        $this->base = $base;
        $this->db = $base->db;
    }

    function install() {
		$sqls="CREATE TABLE ".DB_TABLEPRE."mapabc (
				`id` int(11) NOT NULL auto_increment,
				`did` int(11) NOT NULL default '0',
				`title` VARCHAR( 200 ) NOT NULL DEFAULT  '',
				`description` VARCHAR( 255 ) NOT NULL DEFAULT  '',
				`cordx` varchar(50) NOT NULL default '',
				`cordy` varchar(50) NOT NULL default '',
				`zoom` TINYINT NOT NULL DEFAULT  '13',
				`create_time` timestamp NOT NULL default '0000-00-00 00:00:00',
				PRIMARY KEY  (`id`),
				KEY `did` (`did`)) TYPE=MyISAM DEFAULT CHARSET=".DB_CHARSET.";";
		$this->db->query($sqls);
		$this->db->query("INSERT INTO `".DB_TABLEPRE."regular` (`name`,`regular`,`type`) VALUES ('Mapabc地图','mapabc-default|mapabc-add|mapabc-edit','1')");
		$this->db->query("UPDATE `".DB_TABLEPRE."usergroup` SET regulars =  CONCAT(regulars,'|mapabc-default|mapabc-add|mapabc-edit') ");
		
		$plugin=array(
                'name'			=>	'Mapabc 地图插件 ',
                'identifier'	=>	'mapabc',
                'description'	=>	'Mapabc地图插件可以用来标识词条的地理信息.此版本编码为UTF-8.',
                'datatables'	=>	'',
                'type'			=>	'0',
				'author'		=>	'cocowool',
                'copyright'		=>	'mapabc.com',
                'homepage'		=>	'http://mapabc.com',
                'version'		=>	'2.0',
                'suit'			=>	'5.0',
                'modules'		=>	''
        );
        $plugin['vars']=array(
                array(
                        'displayorder'=>'0',
                        'title'=>'Mapabc MAP API KEY',
                        'description'=>'Mapabc 地图API密钥。请不要使用默认密钥，否则影响地图正常使用！',
                        'variable'=>'mapapikey',
                        'type'=>'text',
                        'value'=>'ebf7f1c1386c65c6a290659d465bb2af4ee4fa5847439cf1e4e1687d92614ce6ac839d01c50ddbd2',
                        'extra'=>''
                ),
//                array(
//                        'displayorder'=>'1',
//                        'title'=>'是否开启默认词条搜索',
//                        'description'=>'1表示默认开启词条搜索，则会根据词条进行全国范围的关键字搜索，0表示默认不进行搜索',
//                        'variable'=>'poisearch',
//                        'type'=>'text',
//                        'value'=>'1',
//                        'extra'=>''
//                ),
        );
        $plugin['hooks']=array(
                array(
                        'available'=>"1",
                        'title'=>'maphtml',
                        'description'=>'在hdwiki根目录下control下的doc.php中，查找 "doview"函数，在函数的结束部分，将此钩子放在$_ENV[\\\'block\\\']->view(\\\'viewdoc\\\');代码上面。',
                        'code'=>'
                                $this->load("plugin");
                                $this->loadplugin("mapabc");
                                if($this->plugin["mapabc"]["available"]){
                                    $plugin=$_ENV["plugin"]->get_plugin_by_identifier("mapabc");
                                    if($plugin["pluginid"]){
                                        $pluginvars =$_ENV["plugin"]->get_pluginvar($plugin["pluginid"]);
                                        $mapabcviewstr=$_ENV["mapabc"]->get_mapabc_view_str($pluginvars, $doc["title"], $doc["did"]);
                                        $this->view->assign("mapabcviewstr",$mapabcviewstr);
                                    }}'
                ),
                array(
                        'available'=>"1",
                        'title'=>'mapsetpoint',
                        'description'=>'在hdwiki根目录下control下的doc.php中，查找 "doview"函数，在函数的结束部分，将此钩子放在$_ENV[\\\'block\\\']->view(\\\'viewdoc\\\');代码上面； 继续查找doedit函数，也在viewdoc代码前加入此钩子',
                        'code'=>'
                                $this->load("plugin");
                                $this->loadplugin("mapabc");
                                if($this->plugin["mapabc"]["available"]){
                                    $plugin=$_ENV["plugin"]->get_plugin_by_identifier("mapabc");
                                    if($plugin["pluginid"]){
                                        $pluginvars =$_ENV["plugin"]->get_pluginvar($plugin["pluginid"]);
                                        $mapabcsetpointstr=$_ENV["mapabc"]->get_mapabc_setpoint_str($pluginvars, $doc["title"], $doc["did"]);
                                        $this->view->assign("mapabcsetpointstr",$mapabcsetpointstr);
                                    }}'
                ),
        );
        return $plugin;
    }

	 //获得标点的区域
	 function get_mapabc_setpoint_str($pluginvars, $doctitle, $did){
        if(is_array($pluginvars) && !empty($pluginvars)) {
			$result = $this->get_point($did);
			if( empty($did) || empty($result) ){
				$tipx = "116.397428";
				$tipy = "39.90923";	
				$zoom = "13";
				$search = "1";
			}else{
				$tipx = $result['cordx'];
				$tipy = $result['cordy'];
				$zoom = $result['zoom'];
				$search = "0";
			}

			//考虑支持Flash和Ajax两个版本
			//<script src="http://app.mapabc.com/apis?&t=ajaxmap&v=2.1.2&key={$pluginvars[0]['value']}" type="text/javascript" ></script>
			$mapabcsetpointstr = <<<EXTRA
		<div class="columns ctgl">
			<h2 class="col-h2" style="cursor:pointer;" title="使用鼠标在地图上单击选择词条对应的地理位置，保存即可为词条设置地理信息。">
				词条标点&nbsp;&nbsp;&nbsp;
				<input type="text" id="citycode" name="citycode" value="010" style="width:50px;" />&nbsp;&nbsp;
				<span id="citysearch" title="(输入城市区号然后回车,例如北京010,可以帮你快速定位到城市)" style="font-size:12px; font-weight:200;">区号定位</span>
			</h2>
			<div>
				<script	src="http://app.mapabc.com/apis?&t=flashmap&v=2.3.4&key={$pluginvars[0]['value']}"	type="text/javascript"></script>
				<div id="mapabcObj" style="width:228px; height:260px;">
				</div>
				<div style="padding:3px;">
					<p style="display:none;">
						城市区号:<input type="text" id="citycode" name="citycode" style="width:50px;" />(输入城市区号,例如北京010,可以帮你快速定位到城市)
					</p>
					<p>	
						经度：<input style="width:55px;" type="text" id="cordx" name="cordx" readonly />&nbsp;&nbsp;
						纬度：<input style="width:55px;" type="text" id="cordy" name="cordy" readonly />
					</p>
					<input type="hidden" id="zoom" name="zoom" value="13" />
					<input type="hidden" id="did" name="did" value="{$did}" />
					<p>
						<input style="width:60px; line-height:14px; font-size:12px; cursor:pointer;" id="savePoint" name="savePoint" type="button" value="保存" />&nbsp;&nbsp;&nbsp;&nbsp;
						<input id="poiSearch" name="poiSearch" type="checkbox" checked http://www.fangdudu.com/index.php?keyword=%E5%8C%97%E4%BA%AC&type=website&action=search>&nbsp;&nbsp;<label for="poiSearch" title="选中后，对于未标点的词条会进行关键字搜索，查找可能的结果，默认选中的有效期为一个月">默认进行词条搜索</label>
					<p>
				</div>
				<div id="success"></div>
				<div id="waiting"></div>
				<script src="/js/jquery.js" type="text/javascript"></script>
				<script src="plugins/mapabc/js/cookie.js" type="text/javascript"></script>
				<script type="text/javascript">
					var drawpoints = true;
					var mapPoi = null;
					$(document).ready(function(){
						var tipx = "{$tipx}";
						var tipy = "{$tipy}";
						var zoom = "{$zoom}";
						var search = "{$search}";
						var mapoption = new MMapOptions();
						mapoption.zoom=zoom;//设置地图zoom级别
						mapoption.toolbar=MINI;
						mapoption.toolbarPos=new MPoint(5,5);
						mapoption.overviewMap = MINIMIZE;
						mapoption.center=new MLngLat(tipx,tipy);
						mapoption.returnCoordType=DEFAULT;
						mapPoint = new MMap("mapabcObj", mapoption); //地图初始化
						mapPoint.addEventListener(mapPoint,MOUSE_CLICK,MclickMouse); 

						function getPositionSuccess( position ){
							var lat = position.coords.latitude;
							var lng = position.coords.longitude;
							//alert( "您所在的位置： 经度" + lat + "，纬度" + lng );

							mapPoint.setCenter(new MLngLat(lng,lat));
							//mapObj.setCenter(new MLngLat(lng,lat));
						}

						function getPositionError( error ){
							switch(error.code){
								case error.TIMEOUT :
									alert( " 位置共享服务连接超时，请重试 " );
									break;
								case error.PERMISSION_DENIED :
									alert( " 您拒绝了使用位置共享服务，查询已取消 " );
									break;
								case error.POSITION_UNAVAILABLE : 
									alert( " 亲爱的火星网友，非常抱歉，我们暂时无法为您所在的星球提供位置服务 " );
									break;
							}
						}

						if( mapPoint ){
							$("div#mapabcObj #imgId").hide();
							drawpoints = true;
							//判断是进行搜索还是进行地理定位
							if( search == '1' ){
								if( $.cookie && $.cookie("poiSearch") == "1" ){
									searchByKeyword();
								}else{
									//进行地理定位
									if( navigator.geolocation ){
										navigator.geolocation.getCurrentPosition( getPositionSuccess, getPositionError );		
									}
								}
							}
						}

						//判断cookie，设置checkbox状态
						if( $.cookie && $.cookie("poiSearch") == "1" ){
							$("#poiSearch").attr("checked", "checked");
						}

						//监听checkbox事件
						$("#poiSearch").click(function(){
							if( $(this).attr('checked') ){
								if( $.cookie ){
									$.cookie("poiSearch", "1", {expires:30});
								}else{
									$.getScript("plugins/mapabc/js/cookie.js", function(){
										//alert("Test");
									});
								}
								drawpoints = true;
								searchByKeyword();
							}else{
								if( $.cookie ){
									$.cookie("poiSearch", "0");
								}
							}
						});

						$("#citysearch").click(function(){
							drawpoints = false;
							searchByKeyword($("#citycode").val(), "车站");
						});

						$("#citycode").bind("keypress", function(e){
							var code = (e.keyCode ? e.keyCode : e.which);
							 if(code == 13) { //Enter keycode
							   //Do something
								drawpoints = false;
								searchByKeyword(this.value, "车站");
							 }
						});
					});

					function searchByKeyword(citycode, keywords, drawpoint){
						mapPoint.removeAllOverlays();//在每次执行新查询时，先删除已经查出的POI点
						//以下是从文本框中得到查询参数
						var city = typeof(citycode) != 'undefined' ? citycode : "全国";
						var keyword = typeof(keywords) != 'undefined' ? keywords : "{$doctitle}";
						var MSearch = new MPoiSearch();
						var opt = new MPoiSearchOptions();
						opt.recordsPerPage = 10;//每页返回数据量，默认为10
						opt.pageNum = 1;//当前页数。
						opt.dataType = "";//数据类别，该处为分词查询，只需要相关行业关键字即可
						opt.dataSources = DS_BASE_ENPOI;//数据源，基础+企业地标数据库（默认）
						MSearch.setCallbackFunction(keywordSearch_CallBack); 
						MSearch.poiSearchByKeywords(keyword,city,opt); 
					}

					function keywordSearch_CallBack(data){
						var resultStr="";
						if(data.error_message != null){
							resultStr="查询异常！"+data.error_message;
						}else{
							switch(data.message){ 
								case 'ok':
									var marker = new Array();
									if(data.poilist.length!=0){
										resultCount=data.poilist.length;
										for (var i = 0; i < data.poilist.length; i++) {
											var tipOption = new MTipOptions();
											tipOption.title=(i+1) + ". "+ data.poilist[i].name;
											tipOption.content = "<br />"+TipContents(data.poilist[i].type,data.poilist[i].address,data.poilist[i].tel);
											var markerOption = new MMarkerOptions();
											var labelOption=new MLabelOptions();
											//labelOption.content=data.poilist[i].name;
											markerOption.labelOption=labelOption; //标注
											markerOption.isDraggable=false;//是否可以拖动
											markerOption.imageAlign=MIDDLE_CENTER;//设置图片锚点相对于图片的位置
											markerOption.tipOption = tipOption;
											markerOption.canShowTip= true;
											markerOption.hasShadow=true;
											markerOption.isDimorphic=true;//可选项，是否具有二态，默认为false即没有二态
											markerOption.dimorphicColor=0x046788;
											var mar = new MMarker(new MLngLat(data.poilist[i].x,data.poilist[i].y),markerOption);
											mar.id=(i);
											marker.push(mar);
										}
										mapPoint.addOverlays(marker,true);
										if( !drawpoints ){
											mapPoint.removeAllOverlays();
										}
										//mapPoint.addEventListener(mapPoint,TIP_OPEN,openTip);
									}else{
										alert("抱歉，未能找到匹配的定位信息");
									}
									break;
								case "error":
								default:
									alert("Error");
									break;

							}
						}
					}

					function TipContents(type,address,tel){ 
						if (type == "" || type == "undefined" || type == null || type == " undefined" || typeof type == "undefined") {
							type = "暂无"; 
						}
						if (address == "" || address == "undefined" || address == null || address == " undefined" || typeof address == "undefined") {
							address = "暂无"; 
						}
						if (tel == "" || tel == "undefined" || tel == null || tel == " undefined" || typeof tel == "undefined") {
							tel = "暂无"; 
						}

						var str ="地址：" + address + "<br>电话：" + tel + " <br>"; 
						return str;
					}

					function MclickMouse( param ){
						var tipx = param.eventX;
						var tipy = param.eventY;
						var zoom = mapPoint.getZoomLevel();

						if( param.overlayId != '' ){
							return false;	
						};

						$("#cordx").val(tipx);
						$("#cordy").val(tipy);
						$("#zoom").val(zoom);

						var tipOption = new MTipOptions();
						tipOption.title="我在这里";
						tipOption.content="<br> 这是点!<br>";//tip内容
						var markerOption = new MMarkerOptions();
						var labelOption=new MLabelOptions();
						labelOption.content="我在这里";
						markerOption.imageUrl ="http://map.house.sina.com.cn/images/tip.png"; 
						markerOption.labelOption=labelOption; //标注
						markerOption.labelAlign=TOP_CENTER;
						markerOption.isDraggable=false;//是否可以拖动
						markerOption.tipOption = tipOption;
						markerOption.canShowTip= true;
						markerOption.isDimorphic=true;//可选项，是否具有二态，默认为false即没有二态
						markerOption.dimorphicColor=0x046788;
						Mmarker = new MMarker(new MLngLat(tipx, tipy),markerOption);
						Mmarker.id="mark101";
						mapPoint.addOverlay(Mmarker,true);
					}

					$("#savePoint").click(function(){
						if( $("#cordx").val() == '' || $("#cordy").val() == '' ){
							alert("请先进行标点");
							return false;
						}

						$.ajax({
							url: "index.php?plugin-mapabc-mapabc-set",
							data: {did:$('#did').val(),cordx:$('#cordx').val(),cordy:$("#cordy").val(),zoom:$("#zoom").val()},
							cache: false,
							dataType: "xml",
							type:"post",
							beforeSend:function(){
								$('#success').html('');
								$('#waiting').html('<img src="plugins/mapabc/images/loading.gif" alt="Posting..." /> 正在发布，请稍等...');
							},
							success: function(xml){
								var	message=xml.lastChild.firstChild.nodeValue;
								if(message=='1'){
									$('#waiting').html('');
									$('#success').html('<p style="color:green; font-weight:400; padding:2px 5px;">保存成功，刷新一下就可以看到啦</p>');
									if( mapPoi ){
										mapPoi.removeAllOverlays();
										var tipOption = new MTipOptions();
										tipOption.title="{$doctitle}";
										tipOption.content="";//tip内容
										var markerOption = new MMarkerOptions();
										var labelOption=new MLabelOptions();
										labelOption.content="";
										markerOption.labelOption=labelOption; //标注
										markerOption.isDraggable=false;//是否可以拖动
										markerOption.imageAlign=MIDDLE_CENTER;//设置图片锚点相对于图片的位置
										markerOption.tipOption = tipOption;
										markerOption.canShowTip= true;
										markerOption.isDimorphic=true;//可选项，是否具有二态，默认为false即没有二态
										markerOption.dimorphicColor=0x046788;
										Mmarker = new MMarker(new MLngLat($('#cordx').val(), $('#cordy').val()),markerOption);
										Mmarker.id="mark102";
										mapPoi.addOverlay(Mmarker,true);
									};
								}else{
									alert('发布失败！');
								}
							}
						});
					});
				</script>
			</div>
		</div>
EXTRA;
		}else{
			$mapabcsetpointstr = '';
		}

		return $mapabcsetpointstr;
	 }

	//Flash版的标点
	//@TODO

	//Ajax版的标点
	//@TODO

	//得到标点显示的区域
    function get_mapabc_view_str($pluginvars, $doctitle, $did = 0) {
        if(is_array($pluginvars) && !empty($pluginvars)) {
			$result = $this->get_point($did);
			if( empty($did) || empty($result) ){
				$tipx = "116.397428";
				$tipy = "39.90923";	
				$zoom = "13";
				$search = "1";
			}else{
				$tipx = $result['cordx'];
				$tipy = $result['cordy'];
				$zoom = $result['zoom'];
				$search = "0";
			}

            $mapabcviewstr = '<div id="mapabc" class="columns">
                <h2 class="col-h2">相关地图</h2>
                <div id="map_canvas" style="width:230px; height:260px;"></div>
				<script	src="http://app.mapabc.com/apis?&t=flashmap&v=2.3.4&key='.$pluginvars[0]['value'].'"	type="text/javascript"></script>
				<script	type="text/javascript">
					var address = \'' . $doctitle . '\';
					var stipx = "'.$tipx.'";
					var stipy = "'.$tipy.'";
					var search = \''.$search.'\';
					var zoom = \''.$zoom.'\';
					$(document).ready(function(){
					try{
						var mapoption_s = new MMapOptions();
						mapoption_s.zoom=zoom;
						mapoption_s.toolbar=MINI;
						mapoption_s.toolbarPos=new MPoint(5,5);
						mapoption_s.overviewMap = MINIMIZE;
						mapoption_s.center=new MLngLat(stipx,stipy);
						mapoption_s.returnCoordType=COORD_TYPE_OFFSET;
						mapPoi = new MMap("map_canvas", mapoption_s); //地图初始化
					}catch(e){
						alert(e.name + ":" + e.message);
					}

						var tipOption = new MTipOptions();
						tipOption.title="'.$doctitle.'";
						tipOption.content="";//tip内容
						var markerOption = new MMarkerOptions();
						var labelOption=new MLabelOptions();
						labelOption.content="";
						markerOption.labelOption=labelOption; //标注
						markerOption.isDraggable=false;//是否可以拖动
						markerOption.imageAlign=MIDDLE_CENTER;//设置图片锚点相对于图片的位置
						markerOption.tipOption = tipOption;
						markerOption.canShowTip= true;
						markerOption.isDimorphic=true;//可选项，是否具有二态，默认为false即没有二态
						markerOption.dimorphicColor=0x046788;
						Mmarker = new MMarker(new MLngLat(stipx, stipy),markerOption);
						Mmarker.id="mark102";
						if( search == "0" ){
							mapPoi.addOverlay(Mmarker,true);
						}

						if( mapPoi ){
							$("div#map_canvas img").hide();
						}
					});
				</script>
              </div>';
        }else $mapabcviewstr = '';
        return $mapabcviewstr;
    }

	//用户添加标点
	function add_point( $did, $x, $y, $zoom){
		$time = date('Y-m-d H:i:s', time());
		$this->db->query("INSERT INTO  ".DB_TABLEPRE."mapabc (did,cordx,cordy,zoom,create_time) VALUES ('$did','$x','$y','$zoom','".$time."') ");
	}

	//获得词条的坐标
	function get_point( $did ){
		$wishlist = array();
		$query= $this->db->query("SELECT *  FROM ".DB_TABLEPRE."mapabc WHERE did = $did");
		return $this->db->fetch_array($query);
	}
	
	function set_point( $did, $x, $y, $title = '', $description = '', $zoom = ''){
		$result = $this->get_point($did);
		//PHP4环境不支持异常捕捉
//		try{
			if( $result ){
				$this->edit_point( $did, $x, $y, $zoom);
			}else{
				$this->add_point( $did, $x, $y, $zoom);
			}
			return true;
//		}catch(Exception $e){
//			return false;
//		}
	}

	//更新词条的坐标
	function edit_point($did = 0, $x, $y, $zoom = 13){
		if( $did ){
			$sql='UPDATE '.DB_TABLEPRE.'mapabc SET cordx = \'' . $x . '\', cordy = \'' . $y . '\', zoom = \'' . $zoom . '\' WHERE did = ' . $did;
			return $this->db->query($sql);
		}else{
			return false;
		}
	}
	
	//卸载插件
    function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS ".DB_TABLEPRE."mapabc");
		$this->db->query("DELETE FROM `".DB_TABLEPRE."regular` where regular = 'mapabc-default|mapabc-add|mapabc-edit' and type=1");
		$this->db->query("UPDATE ".DB_TABLEPRE."usergroup SET regulars=replace(regulars,'|mapabc-default|mapabc-add|mapabc-edit','')");
    }
}

?>
