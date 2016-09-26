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
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'program_name', 'a.`program_name`',
				'category', 'a.`category`',
				'genre', 'a.`genre`',
				'start_time', 'a.`start_time`',
				'end_time', 'a.`end_time`',
				'program_link', 'a.`program_link`',
				'days_of_the_week', 'a.`days_of_the_week`',
				'program_description', 'a.`program_description`',
				'broadcaster_name', 'a.`broadcaster_name`',
				'broadcaster_email', 'a.`broadcaster_email`',
				'broadcaster_image', 'a.`broadcaster_image`',
				'broadcaster_link', 'a.`broadcaster_link`',
				'broadcaster_facebook', 'a.`broadcaster_facebook`',
				'broadcaster_twitter', 'a.`broadcaster_twitter`',
				'broadcaster_instagram', 'a.`broadcaster_instagram`',
				'broadcaster_snapchat', 'a.`broadcaster_snapchat`',
				'broadcaster_telegram', 'a.`broadcaster_telegram`',
				'broadcaster_whatsapp', 'a.`broadcaster_whatsapp`',
				'broadcaster_blog', 'a.`broadcaster_blog`',
				'broadcaster_bio', 'a.`broadcaster_bio`',
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
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		// Filtering category
		$this->setState('filter.category', $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string'));

		// Filtering days_of_the_week
		$this->setState('filter.days_of_the_week', $app->getUserStateFromRequest($this->context.'.filter.days_of_the_week', 'filter_days_of_the_week', '', 'string'));


		// Load the parameters.
		$params = JComponentHelper::getParams('com_programas');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.program_name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
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
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__programas` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');

		// Join over the user field 'modified_by'
		$query->select('`modified_by`.name AS `modified_by`');
		$query->join('LEFT', '#__users AS `modified_by` ON `modified_by`.id = a.`modified_by`');
		// Join over the category 'category'
		$query->select('`category`.title AS `category`');
		$query->join('LEFT', '#__categories AS `category` ON `category`.id = a.`category`');

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
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
				$query->where('( a.program_name LIKE ' . $search . '  OR  a.category LIKE ' . $search . '  OR  a.genre LIKE ' . $search . '  OR  a.start_time LIKE ' . $search . '  OR  a.end_time LIKE ' . $search . '  OR  a.days_of_the_week LIKE ' . $search . '  OR  a.broadcaster_name LIKE ' . $search . '  OR  a.broadcaster_email LIKE ' . $search . ' )');
			}
		}


		//Filtering category
		$filter_category = $this->state->get("filter.category");
		if ($filter_category)
		{
			$query->where("a.`category` = '".$db->escape($filter_category)."'");
		}

		//Filtering days_of_the_week
		$filter_days_of_the_week = $this->state->get("filter.days_of_the_week");
		if ($filter_days_of_the_week)
		{
			$query->where("a.`days_of_the_week` LIKE '%\"".$db->escape($filter_days_of_the_week)."\"%'");
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
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		foreach ($items as $oneItem) {

			if ( isset($oneItem->genre) ) {
				// Catch the item tags (string with ',' coma glue)
				$tags = explode(",",$oneItem->genre);

				$db = JFactory::getDbo();
					$namedTags = array(); // Cleaning and initalization of named tags array

					// Get the tag names of each tag id
					foreach ($tags as $tag) {

						$query = $db->getQuery(true);
						$query->select("title");
						$query->from('`#__tags`');
						$query->where( "id=" . intval($tag) );

						$db->setQuery($query);
						$row = $db->loadObjectList();

						// Read the row and get the tag name (title)
						if (!is_null($row)) {
							foreach ($row as $value) {
								if ( $value && isset($value->title) ) {
									$namedTags[] = trim($value->title);
								}
							}
						}

					}

					// Finally replace the data object with proper information
					$oneItem->genre = !empty($namedTags) ? implode(', ',$namedTags) : $oneItem->genre;
				}

				// Get the title of every option selected.

				$options = json_decode($oneItem->days_of_the_week);

				$options_text = array();

				foreach($options as $option){
						$options_text[] = JText::_('COM_PROGRAMAS_PROGRAMAS_DAYS_OF_THE_WEEK_OPTION_' . strtoupper($option));

				}
					$oneItem->days_of_the_week = !empty($options_text) ? implode(',', $options_text) : $oneItem->days_of_the_week;
		}
		return $items;
	}
}
