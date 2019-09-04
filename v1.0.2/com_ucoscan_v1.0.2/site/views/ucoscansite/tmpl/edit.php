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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<form action="<?php echo JRoute::_('index.php?option=com_ucoscan&view=ucoscansite&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansite.save')">
					<i class="icon-new"></i> <?php echo JText::_('COM_UCOSCAN_BUTTON_SAVE_AND_CLOSE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansite.apply')">
					<i class="icon-new"></i> <?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('ucoscansite.cancel')">
					<i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
		</div>
	<div class="row-fluid">
		<div class="span10 form-horizontal">

	<fieldset>
		<?php echo JHtml::_('bootstrap.startPane', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addPanel', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_UCOSCAN_NEW_UCOSCANSITE', true) : JText::sprintf('COM_UCOSCAN_EDIT_UCOSCANSITE', $this->item->id, true)); ?>

             <?php $new_or_old_id = $this->form->getValue('id', 0); ?>            
            
            
            
            
                    
                               <?php foreach ($this->form->getFieldset('idfields') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>               
              
                    <?php if ($new_or_old_id == 0) { ?>
				<?php   foreach ($this->form->getFieldset('myfields') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
                    <?php } ?>
                    
                    
                            <?php if ($new_or_old_id != 0) { ?>
    				<?php foreach ($this->form->getFieldset('editfields') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>                
                                <?php } ?>
                    
                           <?php foreach ($this->form->getFieldset('catstatefields') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>                     
                    
                    
                                
            


			<?php echo JHtml::_('bootstrap.endPanel'); ?>

			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>

		<?php echo JHtml::_('bootstrap.endPane'); ?>
		</fieldset>
		</div>
	</div>
</form>