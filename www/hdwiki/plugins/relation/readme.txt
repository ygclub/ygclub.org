1. 在根目录\control\doc.php 中 约 112 行 查找  $relatelist = $_ENV['doc']->get_related_doc($doc['did']);
    将钩子 eval($this->plugin['relation']['hooks']['relation']); 此行下面即可；
    注意，钩子一定要放在代码下面。
    
2. 钩子设置中的 选择自动匹配选项 是指，如果指定的相关词条不足设置的显示数量，则根据勾中的选项进行模糊的相关词条匹配
	 先进行标题匹配，然后进行标签匹配。

3. 如果遇到问题，请速到开源论坛提问：  kaiyuan.hudong.com/bbs