<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Programas
 * @author     Ponto Mega <contato@pontomega.com.br>
 * @copyright  2016 Ponto Mega
 * @license    GNU General Public License versão 2 ou posterior; consulte o arquivo License. txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Programas records.
 *
 * @since  1.6
 */
class ProgramasModelProgramas extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'modified_by', 'a.modified_by',
				'program_name', 'a.program_name',
				'category', 'a.category',
				'genre', 'a.genre',
				'start_time', 'a.start_time',
				'end_time', 'a.end_time',
				'program_link', 'a.program_link',
				'days_of_the_week', 'a.days_of_the_week',
				'program_description', 'a.program_description',
				'broadcaster_name', 'a.broadcaster_name',
				'broadcaster_email', 'a.broadcaster_email',
				'broadcaster_image', 'a.broadcaster_image',
				'broadcaster_link', 'a.broadcaster_link',
				'broadcaster_facebook', 'a.broadcaster_facebook',
				'broadcaster_twitter', 'a.broadcaster_twitter',
				'broadcaster_instagram', 'a.broadcaster_instagram',
				'broadcaster_snapchat', 'a.broadcaster_snapchat',
				'broadcaster_telegram', 'a.broadcaster_telegram',
				'broadcaster_whatsapp', 'a.broadcaster_whatsapp',
				'broadcaster_blog', 'a.broadcaster_blog',
				'broadcaster_bio', 'a.broadcaster_bio',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$list = $app->getUserState($this->context . '.list');
		
		if (empty($list['ordering']))
{
	$list['ordering'] = 'ordering';
}

if (empty($list['direction']))
{
	$list['direction'] = 'asc';
}

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}

		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select(
				$this->getState(
					'list.select', 'DISTINCT a.*'
				)
			);

		$query->from('`#__programas` AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the created by field 'modified_by'
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');
		// Join over the category 'category'
		$query->select('categories_2501645.title AS category');
		$query->join('LEFT', '#__categories AS categories_2501645 ON categories_2501645.id = a.category');
		
		if (!JFactory::getUser()->authorise('core.edit', 'com_programas'))
		{
			$query->where('a.state = 1');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.program_name LIKE ' . $search . '  OR categories_2501645.title LIKE ' . $search . '  OR  a.broadcaster_name LIKE ' . $search . '  OR  a.broadcaster_email LIKE ' . $search . ' )');
			}
		}
		

		// Filtering category
		$filter_category = $this->state->get("filter.category");
		if ($filter_category)
		{
			$query->where("a.category = '".$db->escape($filter_category)."'");
		}

		// Filtering days_of_the_week
		$filter_days_of_the_week = $this->state->get("filter.days_of_the_week");
		if ($filter_days_of_the_week != '') {
			$query->where("a.days_of_the_week LIKE '%\"".$db->escape($filter_days_of_the_week)."\"%'");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		
		foreach ($items as $item)
		{


			if (isset($item->category))
			{

				// Get the title of that particular template
					$title = ProgramasHelpersProgramas::getCategoryNameByCategoryId($item->category);

					// Finally replace the data object with proper information
					$item->category = !empty($title) ? $title : $item->category;
				}

			if (isset($item->genre))
			{
				// Catch the item tags (string with ',' coma glue)
				$tags = explode(",", $item->genre);
				$db = JFactory::getDbo();

				// Cleaning and initalization of named tags array
				$namedTags = array();

				// Get the tag names of each tag id
				foreach ($tags as $tag)
				{
					$query = $db->getQuery(true);
					$query->select("title");
					$query->from('`#__tags`');
					$query->where("id=" . intval($tag));

					$db->setQuery($query);
					$row = $db->loadObjectList();

					// Read the row and get the tag name (title)
					if (!is_null($row))
					{
						foreach ($row as $value)
						{
							if ( $value && isset($value->title))
							{
								$namedTags[] = trim($value->title);
							}
						}
					}
				}

				// Finally replace the data object with proper information
				$item->genre = !empty($namedTags) ? implode(', ', $namedTags) : $item->genre;
			}
				// Get the title of every option selected.
				$options = json_decode($item->days_of_the_week);
				$options_text = array();

				foreach ($options as $option)
				{
						$options_text[] = JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_' . strtoupper($option));

				}
					$item->days_of_the_week = !empty($options_text) ? implode(',', $options_text) : $item->days_of_the_week;
		}

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_PROGRAMAS_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? JFactory::getDate($date)->format("Y-m-d") : null;
	}
}
