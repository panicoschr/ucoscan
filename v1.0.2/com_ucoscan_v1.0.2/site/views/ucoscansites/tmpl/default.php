<?php
/**
 * @package	UCOSCAN
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access
defined('_JEXEC') or die;

$user		= JFactory::getUser();

//make sure user is logged in
if($user->id == 0)
{
    JFactory::getApplication()->enqueueMessage(JText::_('COM_HR_ERROR_MUST_LOGIN'), 'warning');
	$joomlaLoginUrl = 'index.php?option=com_users&view=login';

	echo "<br><a href='".JRoute::_($joomlaLoginUrl)."'>".JText::_( 'COM_UCOSCAN_LOG_IN')."</a><br>";
}
else
{
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>
	<form action="<?php echo JRoute::_('index.php?option=com_ucoscan&view=ucoscansites'); ?>" method="post" name="adminForm" id="adminForm">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansite.add')">
					<i class="icon-new"></i> <?php echo JText::_('JNEW') ?>
				</button>
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansite.edit')">
					<i class="icon-new"></i> <?php echo JText::_('JEDIT') ?>
				</button>                            
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansites.publish')">
					<i class="icon-new"></i> <?php echo JText::_('JPUBLISH') ?>
				</button>    
                       
			</div>
		</div>
		<?php if (!empty( $this->sidebar)) : ?>
			<div id="j-sidebar-container" class="span2">
				<?php echo $this->sidebar; ?>
			</div>
			<div id="j-main-container" class="span10">
		<?php else : ?>
			<div id="j-main-container">
		<?php endif;?>
			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_UCOSCAN_SEARCH_IN_TITLE');?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_UCOSCAN_SEARCH_IN_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_UCOSCAN_SEARCH_IN_TITLE'); ?>" />
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>

                                        
                                        <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value = '';this.form.submit();"><i class="icon-remove"></i></button>                                       

                                        
                                        
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<div class="clearfix"> </div>
			<table class="table table-striped" id="ucoscansiteList">
				<thead>
					<tr>
						<th width="1%" class="hidden-phone">
							<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
						</th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                        </th>                                                
    <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_URL_LABEL', 'a.url', $listDirn, $listOrder); ?>
                        </th>     
                        <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_DETECTEUCOSCAN_LABEL', 'a.detecteucoscan', $listDirn, $listOrder); ?>
                        </th>   
       <?php                 
                       /* 
                        <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_DESCRIPTION_LABEL', 'a.description', $listDirn, $listOrder); ?>
                        </th>   
                        <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_CMS_LIST_VIEW_LABEL', 'a.cms_list', $listDirn, $listOrder); ?>
                        </th>                        
                        <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_DOMAIN_LABEL', 'd.title', $listDirn, $listOrder); ?>
                        </th>
                         <th width="25%" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'COM_UCOSCAN_FIELD_SUFFIX_LABEL', 's.title', $listDirn, $listOrder); ?>
                        </th>                       
 
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
                        * 
                                 */            
?> 
                       
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$canCheckin = $user->authorise('core.manage',     'com_checkin');
					$canChange  = $user->authorise('core.edit.state', 'com_ucoscan') && $canCheckin;
					$canEdit    = $user->authorise('core.edit',       'com_ucoscan.category.' . $item->catid);
					?>
					<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
						<td class="center hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
                            <td class="center ">
                                <?php echo JHtml::_('jgrid.published', $item->state, $i, 'ucoscansites.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
                                <?php /*echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange);*/?>
                            </td>                                                
                            <td class="nowrap has-context">
                                   <div class="pull-left break-word">   
                                <?php if ($canEdit) : ?>
                                    <a href="<?php echo $this->escape($item->url); ?>">
                                        <?php echo $this->escape($item->url); ?>
                                    </a>
                                <?php else : ?>
                                    <?php echo $this->escape($item->url); ?>
                                <?php endif; ?>
                                          </div>
                            </td>                            
                            <td class="nowrap has-context">
                                 <div class="pull-left break-word">   
                                <?php echo $this->escape($item->detecteucoscan); ?>
                                      </div>
                            </td>       
                            
       <?php                 
                       /*                             
                            <td class="nowrap has-context">
                                 <div class="pull-left break-word">   
                                <?php echo $this->escape($item->description); ?>
                                      </div>
                            </td>   
                            <td class="nowrap has-context">
                                 <div class="pull-left break-word">   
                                <?php 
                                $cms_list_with_slash = str_replace(" ",",",$this->escape($item->cms_list));
                                echo $cms_list_with_slash; ?>
                                      </div>
                            </td>                            
                            <td class="nowrap has-context">
                                 <div class="pull-left break-word">   
                                <?php echo $this->escape($item->dtitle); ?>
                                      </div>
                            </td>
                            <td class="nowrap has-context">
                                 <div class="pull-left break-word">   
                                <?php echo $this->escape($item->stitle); ?>
                                      </div>
                            </td>                            
                                  

						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
                        * 
                        */
				
       ?>
       </tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>

<?php
}