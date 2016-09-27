
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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHTML::_('behavior.modal');
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_programas') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'programaform.xml');
$canEdit = $user->authorise('core.edit', 'com_programas') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'programaform.xml');
$canCheckin = $user->authorise('core.manage', 'com_programas');
$canChange = $user->authorise('core.edit.state', 'com_programas');
$canDelete = $user->authorise('core.delete', 'com_programas');
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'components/com_programas/assets/css/style.css');

// Obtém o Item de Menu atualmente ativo

$currentMenuItem = $app->getMenu()->getActive();

// Obtém os parâmetros do Item de Menu atualmente ativo

$menuparams = $currentMenuItem->params;
$show_link = $menuparams->get('link_titles');
$link_broadcaster = $menuparams->get('link_broadcaster');
$showintro = $menuparams->get('showintro');
$showreadmore = $menuparams->get('showreadmore');

// Para pegar os parâmetros do componente dentro do componente

$app = JFactory::getApplication('site');
$componentParams = $app->getParams('com_programas');
$icon_font_family = $componentParams->get('icon_font_family', defaultValue);
$load_font_awesome = $componentParams->get('load_font_awesome', defaultValue);
$iconfont = 'icon icon';

if ($icon_font_family == 0)
    {
    $iconfont = '';
    }

if ($icon_font_family == 1)
    {
    $iconfont = 'icon icon';
    }

if ($icon_font_family == 2)
    {
    $iconfont = 'fa fa';
    }

if ($load_font_awesome == '1')
    {
    $document->addStyleSheet(JURI::base() . 'components/com_programas/assets/css/font-awesome.min.css');
    }

// Acessa os parâmetros do Item de Menu atualmente ativo

$catID = JRequest::getVar('id');
$db = JFactory::getDBO();
$sql = "SELECT title FROM #__categories WHERE id = " . intval($catID);
$db->setQuery($sql);
$cat_title = $db->loadResult();
$date = JFactory::getDate();
$timezone = new DateTimeZone(JFactory::getConfig()->get('offset'));
$offset = $timezone->getOffset(new DateTime) / 3600;
$h = $offset; // Hour for time zone goes here e.g. +7 or -4, just remove the + or -
$hm = $h * 60;
$ms = $hm * 60;
$gmdate = gmdate("H:i", time() + ($ms)); // the "-" can be switched to a plus if that's what your time zone is.
$gmday = gmdate("l", time() + ($ms));
?>

<?php //echo JRoute::_('index.php?option=com_programas&view=programas');
 ?>

<form action="" method="post" name="adminForm" id="adminForm">



    <?php
echo JLayoutHelper::render('default_filter', array(
    'view' => $this
) , dirname(__FILE__)); ?>

    <div class="row" id="programalist">



            <?php

if ($canEdit || $canDelete): ?>

                <div class="center">

                    <?php
    echo JText::_('COM_PROGRAMAS_PROGRAMAS_ACTIONS'); ?>

                </div>

            <?php
endif; ?>









        <div class="programlist span12 col-md-12">





            <?php
$alldays = array(
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_SUNDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_MONDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_TUESDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_WEDNESDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_THURSDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_FRIDAY') ,
    JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_SATURDAY')
);

foreach($alldays as $d => $oneday)
    {
?>

               <div class="program">

                   <div>

                       <h3 class="oneday"><?php
    echo $oneday; ?></h3>

            <?php
    foreach($this->items as $i => $item)
        { ?>



                <?php
        if ($cat_title == $item->category)
            { ?>

                    <?php
            $canEdit = $user->authorise('core.edit', 'com_programas'); ?>

                        <?php
            if (!$canEdit && $user->authorise('core.edit.own', 'com_programas')): ?>

                    <?php
                $canEdit = JFactory::getUser()->id == $item->created_by; ?>

                <?php
            endif; ?>

            <?php
            $thedays = explode(',', $item->days_of_the_week);
            foreach($thedays as $countdays => $theday)
                {
                if ($theday == $oneday)
                    { ?>

                            <?php
                    $thefinaltime = date_format(date_create($item->end_time) , 'H:i');
                    $theinitialtime = date_format(date_create($item->start_time) , 'H:i');
?>

                            	<?php
                    if (!$canEdit && $user->authorise('core.edit.own', 'com_programas')): ?>

                <?php
                        if (isset($this->items[0]->state)): ?>

                    <?php
                            $class = ($canChange) ? 'active' : 'disabled'; ?>

                    <div class="center">

                        <a class="btn btn-micro <?php
                            echo $class; ?>" href="<?php
                            echo ($canChange) ? JRoute::_('index.php?option=com_programas&task=programa.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2) , false, 2) : '#'; ?>">

                            <?php
                            if ($item->state == 1): ?>

                                <i class="<?php
                                echo $iconfont; ?>-publish"></i>

                            <?php
                            else: ?>

                                <i class="<?php
                                echo $iconfont; ?>-unpublish"></i>

                            <?php
                            endif; ?>

                        </a>

                                        </div>

                <?php
                        endif; ?>

                                <?php
                    endif; ?>



                <h4 class="program_name">

                 <?php
                    if ($canEdit): ?>

                <?php
                        if (isset($item->checked_out) && $item->checked_out): ?>

                    <?php
                            echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'programas.', $canCheckin); ?>

                <?php
                        endif; ?>

                 <?php
                    endif; ?>



                    <?php
                    if ($show_link == 1): ?>

                        <a href="<?php
                        echo JRoute::_('index.php?option=com_programas&view=programa&id=' . (int)$item->id); ?>">

                    <?php
                    endif; ?>

                <?php
                    echo $this->escape($item->program_name); ?>

                    <?php
                    if ($show_link == 1): ?>

                        </a>

                    <?php
                    endif; ?>



                </h4>

                <div class="category">

                    <i class="<?php
                    echo $iconfont; ?>-flag"></i><?php
                    echo $item->category; ?>



                </div>
                <?php if ($item->program_name) : ?>
                <div class="programas_genre">

                                <i class="<?php
                    echo $iconfont; ?>-music"></i><?php
                    echo $item->genre; ?>

                </div>
                <?php endif;?>
                <?php if (($item->start_time) or ($item->end_time)) : ?>
                <div>

                                <i class="<?php
                    echo $iconfont; ?>-arrow-up"></i><?php
                    echo $theinitialtime; ?> h

                                <br />

                                <i class="<?php
                    echo $iconfont; ?>-arrow-down"></i><?php
                    echo $thefinaltime; ?> h

                </div>
                <?php endif;?>
                <?php if ($item->days_of_the_week) : ?>
                <div>

                                <i class="<?php
                    echo $iconfont; ?>-calendar"></i><?php
                    echo $item->days_of_the_week; ?>

                </div>
                <?php endif;?>
                <?php if ($item->broadcaster_image) : ?>
                <div>



                                <?php
                    if ($link_broadcaster == 1): ?>

                                <a class="modal" href="<?php
                        echo $item->broadcaster_image; ?>">

                                <?php
                    endif; ?>

                                <i class="<?php
                    echo $iconfont; ?>-user"></i><?php
                    echo $item->broadcaster_name; ?>

                                <?php
                    if ($link_broadcaster == 1): ?>

                                </a>

                                <?php
                    endif; ?>

                </div>
                <?php endif;?>
                <?php if ($item->broadcaster_email) : ?>
                <div>

                                <i class="<?php
                    echo $iconfont; ?>-mail"></i><?php
                    echo $item->broadcaster_email; ?>

                </div>
                <?php endif;?>
                <?php if ($item->program_description) : ?>


                     <?php
                    $tag = '<hr id="system-readmore" />';
                    $texts = explode($tag, $item->program_description);
                    foreach($texts as $counttext => $text)
                        {
                        if ($counttext == '0' and $showintro == '1')
                            { ?>



                            <div class="program_intro">

                                <?php
                            echo $text; ?>



                         <?php
                            if ($showreadmore == '1')
                                { ?>



                             <a class="btn button button-alarm" href="<?php
                                echo JRoute::_('index.php?option=com_programas&view=programa&id=' . (int)$item->id); ?>">

                             <?php
                                echo JText::_('COM_PROGRAMAS_READ_MORE'); ?>

                             </a>

                            <?php
                                } ?>

                            </div>

                        <?php
                            } ?>

                    <?php
                        } ?>
                    <?php endif;?>

                        <?php
                    if ($canEdit || $canDelete): ?>

                            <div class="center">

                        <?php
                        if ($canEdit): ?>

                            <a href="<?php
                            echo JRoute::_('index.php?option=com_programas&task=programaform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="<?php
                            echo $iconfont; ?>-edit" ></i></a>

                        <?php
                        endif; ?>

                        <?php
                        if ($canDelete): ?>

                            <a href="<?php
                            echo JRoute::_('index.php?option=com_programas&task=programaform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="<?php
                            echo $iconfont; ?>-trash" ></i></a>

                        <?php
                        endif; ?>

                            </div>

                        <?php
                    endif; ?>







                            <?php
                    }
                }
            }
        } ?>



                </div>

            </div>

            <?php
    }

?>

        </div>

    </div>



    <?php

if ($canCreate): ?>

        <a href="<?php
    echo JRoute::_('index.php?option=com_programas&task=programaform.edit&id=0', false, 2); ?>"

           class="btn btn-success btn-small"><i

                class="<?php
    echo $iconfont; ?>-plus"></i>

            <?php
    echo JText::_('COM_PROGRAMAS_ADD_ITEM'); ?></a>

    <?php
endif; ?>



    <input type="hidden" name="task" value=""/>

    <input type="hidden" name="boxchecked" value="0"/>

    <input type="hidden" name="filter_order" value="<?php
echo $listOrder; ?>"/>

    <input type="hidden" name="filter_order_Dir" value="<?php
echo $listDirn; ?>"/>

    <?php
echo JHtml::_('form.token'); ?>

</form>



<?php

if ($canDelete): ?>

<script type="text/javascript">



    jQuery(document).ready(function () {

        jQuery('.delete-button').click(deleteItem);

    });



    function deleteItem() {



        if (!confirm("<?php
    echo JText::_('COM_PROGRAMAS_DELETE_MESSAGE'); ?>")) {

            return false;

        }

    }

</script>

<?php
endif; ?>

<script type="text/javascript">



jQuery(document).ready(function($){

/* Thanks to CSS Tricks for pointing out this bit of jQuery

http://css-tricks.com/equal-height-blocks-in-rows/

It's been modified into a function called at page load and then each time the page is resized. One large modification was to remove the set height before each new calculation. */



    equalheight = function(container){



        var currentTallest = 0,

        currentRowStart = 0,

        rowDivs = new Array(),

        $el,

        topPosition = 0;

        $(container).each(function() {



        $el = $(this);

        $($el).height('auto')

        topPostion = $el.position().top;



        if (currentRowStart != topPostion) {

            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {

                rowDivs[currentDiv].height(currentTallest);

            }

            rowDivs.length = 0; // empty the array

            currentRowStart = topPostion;

            currentTallest = $el.height();

            rowDivs.push($el);

        }

          else {

            rowDivs.push($el);

            currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);

        }

            for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {

                    rowDivs[currentDiv].height(currentTallest);

                }

            });

    }


    // set function on load page


    $(window).load(function() {


        // set each parent div


        $('.programlist').each(function(){


            // set eachh child div to be resized to same size


            equalheight('.program');

        });

    });




    // set function on resize page


    $(window).resize(function(){


        // set each parent div


        $('.programlist').each(function(){


            // set each child div to be resized to same size


            equalheight('.program');

        });

    });

});

</script>
