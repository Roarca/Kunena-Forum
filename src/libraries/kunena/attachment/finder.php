<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Framework
 * @subpackage    Attachment
 *
 * @copyright     Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/

namespace Joomla\Component\Kunena\Libraries\Attachment;

defined('_JEXEC') or die();

use Exception;
use RuntimeException;
use function defined;

/**
 * Class KunenaAttachmentFinder
 *
 * @since   Kunena 5.0
 */
class Finder extends KunenaDatabaseObjectFinder
{
	/**
	 * @var     string
	 * @since   Kunena 6.0
	 */
	protected $table = '#__kunena_attachments';

	/**
	 * Get log entries.
	 *
	 * @return  array|KunenaCollection
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception|void
	 */
	public function find()
	{
		if ($this->skip)
		{
			return [];
		}

		$query = clone $this->query;
		$this->build($query);
		$query->select('a.*');
		$query->setLimit($this->limit, $this->start);
		$this->db->setQuery($query);

		try
		{
			$results = (array) $this->db->loadObjectList('id');
		}
		catch (RuntimeException $e)
		{
			\Joomla\Component\Kunena\Libraries\Error::displayDatabaseError($e);
		}

		$instances = [];

		if (!empty($results))
		{
			foreach ($results as $id => $result)
			{
				$instances[$id] = \Joomla\Component\Kunena\Libraries\Attachment\Helper::get($id);
			}
		}

		$instances = new KunenaCollection($instances);

		unset($results);

		return $instances;
	}
}
