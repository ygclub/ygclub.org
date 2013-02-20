<?php
/**
 * The manage product view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: manageproducts.html.php 3420 2012-08-28 06:37:10Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post'>
  <table align='center' class='table-6'> 
    <caption><?php echo $lang->project->manageProducts;?></caption>
    <tr>
      <td id='productsBox'><?php echo html::checkbox("products", $allProducts, $linkedProducts);?><?php echo html::hidden("post", 'post');?></td>
    </tr>
    <tr><td class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
