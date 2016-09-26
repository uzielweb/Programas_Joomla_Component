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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_programas', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_programas/js/form.js');

$user    = JFactory::getUser();
$canEdit = ExampleHelpersExample::canUserEdit($this->item, $user);


?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-programa').submit(function (event) {
				
			});

			
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-programa').submit(function (event) {
				
			});

			
		});
	}
</script>

<div class="programa-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_PROGRAMAS_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1>Edit <?php echo $this->item->id; ?></h1>
		<?php else: ?>
			<h1>Add</h1>
		<?php endif; ?>

		<form id="form-programa"
			  action="<?php echo JRoute::_('index.php?option=com_programas&task=programa.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<?php if(empty($this->item->modified_by)): ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />
	<?php endif; ?>
	<?php echo $this->form->renderField('program_name'); ?>

	<?php echo $this->form->renderField('category'); ?>

	<?php echo $this->form->renderField('genre'); ?>

	<?php echo $this->form->renderField('start_time'); ?>

	<?php echo $this->form->renderField('end_time'); ?>

	<?php echo $this->form->renderField('program_link'); ?>

	<?php echo $this->form->renderField('days_of_the_week'); ?>

	<?php echo $this->form->renderField('program_description'); ?>

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
				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','programas')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','programas')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-programa").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_programas&task=programaform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_programas"/>
			<input type="hidden" name="task"
				   value="programaform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
