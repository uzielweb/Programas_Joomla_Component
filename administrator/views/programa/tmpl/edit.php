<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Programas
 * @author     Ponto Mega <contato@pontomega.com.br>
 * @copyright  2016 Ponto Mega
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_programas/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'programa.cancel') {
			Joomla.submitform(task, document.getElementById('programa-form'));
		}
		else {
			
			if (task != 'programa.cancel' && document.formvalidator.isValid(document.id('programa-form'))) {
				
				Joomla.submitform(task, document.getElementById('programa-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_programas&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="programa-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PROGRAMAS_TITLE_PROGRAMA', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php if(empty($this->item->modified_by)){ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />

				<?php } ?>				<?php echo $this->form->renderField('program_name'); ?>
                <?php echo $this->form->renderField('state'); ?> 
				<?php echo $this->form->renderField('category'); ?>
				<?php echo $this->form->renderField('genre'); ?>
				<?php echo $this->form->renderField('start_time'); ?>
				<?php echo $this->form->renderField('end_time'); ?>
				<?php echo $this->form->renderField('program_link'); ?>
				<?php echo $this->form->renderField('days_of_the_week'); ?>
				<?php echo $this->form->renderField('program_description'); ?>

                	</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'advanced', JText::_('COM_PROGRAMAS_BROADCASTER_DETAILS', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">
				<?php echo $this->form->renderField('broadcaster_name'); ?>
				<?php echo $this->form->renderField('broadcaster_email'); ?>
				<?php echo $this->form->renderField('broadcaster_image'); ?>
				<?php echo $this->form->renderField('broadcaster_link'); ?>
				<?php echo $this->form->renderField('broadcaster_facebook'); ?>
				<?php echo $this->form->renderField('broadcaster_twitter'); ?>
				<?php echo $this->form->renderField('broadcaster_instagram'); ?>
				<?php echo $this->form->renderField('broadcaster_snapchat'); ?>
				<?php echo $this->form->renderField('broadcaster_telegram'); ?>
				<?php echo $this->form->renderField('broadcaster_whatsapp'); ?>
				<?php echo $this->form->renderField('broadcaster_blog'); ?>
				<?php echo $this->form->renderField('broadcaster_bio'); ?>


					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if (JFactory::getUser()->authorise('core.admin','programas')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
