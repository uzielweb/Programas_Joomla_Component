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

JHTML::_('behavior.modal');

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_programas.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_programas' . $this->item->id)) {

	$canEdit = JFactory::getUser()->id == $this->item->created_by;

}

$app = JFactory::getApplication();

$document = JFactory::getDocument();

$document->addStyleSheet(JURI::base().'components/com_programas/assets/css/style.css');

// Obtém o Item de Menu atualmente ativo

$currentMenuItem = $app->getMenu()->getActive();

// Obtém os parâmetros do Item de Menu atualmente ativo

$menuparams = $currentMenuItem->params;

$show_link = $menuparams->get('link_titles');

$link_broadcaster = $menuparams->get('link_broadcaster');

$showintro = $menuparams->get('showintro');

$showreadmore = $menuparams->get('showreadmore');

// Para pegar os parâmetros do componente dentro do componente



$app =JFactory::getApplication('site');

$componentParams = $app->getParams('com_programas');

$icon_font_family = $componentParams->get('icon_font_family', defaultValue);

$load_font_awesome = $componentParams->get('load_font_awesome', defaultValue);

$iconfont = 'fa fa';

if ($icon_font_family == 0){

 $iconfont = '';

}

if ($icon_font_family == 1){

 $iconfont = 'icon icon';

}

if ($icon_font_family == 2){

 $iconfont = 'fa fa';

}

if ($load_font_awesome == '1'){

    $document->addStyleSheet(JURI::base().'components/com_programas/assets/css/font-awesome.min.css');

}

// Acessa os parâmetros do Item de Menu atualmente ativo

$catID = JRequest::getVar('id');

$db = JFactory::getDBO();

$sql = "SELECT title FROM #__categories WHERE id = ".intval($catID);

$db->setQuery($sql);

$cat_title = $db->loadResult();

?>

<?php if (($this->item)) : ?>



	<div class="item_fields">

		<div class="span12 col-md-12">



<?php
     $thefinaltime = date_format(date_create($this->item->end_time) , 'H:i');
    $theinitialtime = date_format(date_create($this->item->start_time) , 'H:i');

    if (!empty($this->item->program_name)) : ?>

			<h2><?php echo $this->item->program_name; ?></h2>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_GENRE'); ?></div>

			<div class="span8 col-md-8"><?php echo $this->item->genre; ?></div>

</div>

<?php endif; ?>

<?php if ((!empty($this->item->start_time)) and !empty($this->item->start_time)) : ?>

<div class="row-fluid">  

            <div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_TIME'); ?></div>

			<div class="span8 col-md-8"><?php echo $theinitialtime; ?> h - <?php echo $thefinaltime; ?> h</div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_PROGRAM_LINK'); ?></div>

			<div class="span8 col-md-8"><?php echo $this->item->program_link; ?></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_DAYS_OF_THE_WEEK'); ?></div>

			<div class="span8 col-md-8"><?php echo $this->item->days_of_the_week; ?></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span12 col-md-12"><?php echo $this->item->program_description; ?></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_BROADCASTER_NAME'); ?></div>

			<div class="span8 col-md-8"><?php echo $this->item->broadcaster_name; ?></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_BROADCASTER_EMAIL'); ?></div>

			<div class="span8 col-md-8"><a target="_blank" href="mailto:<?php echo $this->item->broadcaster_email; ?>"><?php echo $this->item->broadcaster_email; ?></a></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_BROADCASTER_IMAGE'); ?></div>

			<div class="span8 col-md-8"><a href="<?php echo $this->item->broadcaster_image; ?>" class="modal"><img src="<?php echo $this->item->broadcaster_image; ?>" alt="<?php echo $this->item->broadcaster_name; ?>" /></a></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

			<div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_BROADCASTER_LINK'); ?></div>

			<div class="span8 col-md-8"><a target="_blank" href="<?php echo $this->item->broadcaster_link; ?>"><?php echo $this->item->broadcaster_link; ?></a></div>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">  

    <a target="_blank" href="<?php echo $this->item->broadcaster_facebook; ?>" target="_blank"><i class="<?php echo $iconfont.'-facebook';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_twitter; ?>" target="_blank"><i class="<?php echo $iconfont.'-twitter';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_snapchat; ?>" target="_blank"><i class="<?php echo $iconfont.'-snapchat';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_instagram; ?>" target="_blank"><i class="<?php echo $iconfont.'-instagram';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_whatsapp; ?>" target="_blank"><i class="<?php echo $iconfont.'-whatsapp';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_telegram; ?>" target="_blank"><i class="<?php echo $iconfont.'-paper-plane';?>"></i></a>

    <a target="_blank" href="<?php echo $this->item->broadcaster_blog; ?>" target="_blank"><i class="<?php echo $iconfont.'-globe';?>"></i></a>

</div>

<?php endif; ?>

<?php if (!empty($this->item->genre)) : ?>

<div class="row-fluid">

            <div class="span4 col-md-4"><?php echo JText::_('COM_PROGRAMAS_FORM_LBL_PROGRAMA_BROADCASTER_BIO'); ?></div>

            <div class="span8 col-md-8"><?php echo $this->item->broadcaster_bio; ?></div>

		</div>

<?php endif; ?>

	</div>

    </div>

	<?php if($canEdit && $this->item->checked_out == 0): ?>

		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_programas&task=programa.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_PROGRAMAS_EDIT_ITEM"); ?></a>

	<?php endif; ?>

								<?php if(JFactory::getUser()->authorise('core.delete','com_programas.programa.'.$this->item->id)):?>

									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_programas&task=programa.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_PROGRAMAS_DELETE_ITEM"); ?></a>

								<?php endif; ?>

	<?php

else:

	echo JText::_('COM_PROGRAMAS_ITEM_NOT_LOADED');

endif;

