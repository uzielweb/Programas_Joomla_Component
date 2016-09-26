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

jimport('joomla.application.component.view');

/**
 * View class for a list of Programas.
 *
 * @since  1.6
 */
class ProgramasViewProgramas extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		ProgramasHelpersProgramas::addSubmenu('programas');

		$this->addToolbar();

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = ProgramasHelpersProgramas::getActions();

		JToolBarHelper::title(JText::_('COM_PROGRAMAS_TITLE_PROGRAMAS'), 'programas.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/programa';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('programa.add', 'JTOOLBAR_NEW');
				JToolbarHelper::custom('programas.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
			}

			if ($canDo->get('core.edit') && isset($this->items[0]))
			{
				JToolBarHelper::editList('programa.edit', 'JTOOLBAR_EDIT');
			}
		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('programas.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('programas.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'programas.delete', 'JTOOLBAR_DELETE');
			}

			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::archiveList('programas.archive', 'JTOOLBAR_ARCHIVE');
			}

			if (isset($this->items[0]->checked_out))
			{
				JToolBarHelper::custom('programas.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
			}
		}

		// Show trash and delete for components that uses the state field
		if (isset($this->items[0]->state))
		{
			if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
			{
				JToolBarHelper::deleteList('', 'programas.delete', 'JTOOLBAR_EMPTY_TRASH');
				JToolBarHelper::divider();
			}
			elseif ($canDo->get('core.edit.state'))
			{
				JToolBarHelper::trash('programas.trash', 'JTOOLBAR_TRASH');
				JToolBarHelper::divider();
			}
		}

		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_programas');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_programas&view=programas');

		$this->extra_sidebar = '';
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_CATEGORY"),
			'filter_category',
			JHtml::_('select.options', JHtml::_('category.options', 'com_programas'), "value", "text", $this->state->get('filter.category'))

		);

		//Filter for the field days_of_the_week
		$select_label = JText::sprintf('COM_PROGRAMAS_FILTER_SELECT_LABEL', 'Days Of The Week');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "sunday";
		$options[0]->text = "Sunday";
		$options[1] = new stdClass();
		$options[1]->value = "monday";
		$options[1]->text = "Monday";
		$options[2] = new stdClass();
		$options[2]->value = "tuesday";
		$options[2]->text = "Tuesday";
		$options[3] = new stdClass();
		$options[3]->value = "wednesday";
		$options[3]->text = "Wednesday";
		$options[4] = new stdClass();
		$options[4]->value = "thursday";
		$options[4]->text = "Thursday";
		$options[5] = new stdClass();
		$options[5]->value = "friday";
		$options[5]->text = "Friday";
		$options[6] = new stdClass();
		$options[6]->value = "saturday";
		$options[6]->text = "Saturday";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_days_of_the_week',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.days_of_the_week'), true)
		);

	}

	/**
	 * Method to order fields 
	 *
	 * @return void 
	 */
	protected function getSortFields()
	{
		return array(
			'a.`id`' => JText::_('JGRID_HEADING_ID'),
			'a.`ordering`' => JText::_('JGRID_HEADING_ORDERING'),
			'a.`state`' => JText::_('JSTATUS'),
			'a.`program_name`' => JText::_('COM_PROGRAMAS_PROGRAMAS_PROGRAM_NAME'),
			'a.`category`' => JText::_('COM_PROGRAMAS_PROGRAMAS_CATEGORY'),
			'a.`genre`' => JText::_('COM_PROGRAMAS_PROGRAMAS_GENRE'),
			'a.`start_time`' => JText::_('COM_PROGRAMAS_PROGRAMAS_START_TIME'),
			'a.`end_time`' => JText::_('COM_PROGRAMAS_PROGRAMAS_END_TIME'),
			'a.`days_of_the_week`' => JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK'),
			'a.`broadcaster_name`' => JText::_('COM_PROGRAMAS_PROGRAMAS_BROADCASTER_NAME'),
			'a.`broadcaster_email`' => JText::_('COM_PROGRAMAS_PROGRAMAS_BROADCASTER_EMAIL'),
		);
	}
}
