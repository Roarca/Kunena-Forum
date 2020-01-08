<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Framework
 * @subpackage    Cache
 *
 * @copyright     Copyright (C) 2008 - 2020 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/

namespace Joomla\Component\Kunena\Libraries\Cache;

defined('_JEXEC') or die();

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use function defined;

/**
 * Class Helper has helper functions to clear all caches that affects Kunena.
 *
 * @since   Kunena 6.0
 */
abstract class Helper
{
	/**
	 * Clear all cache types. Please avoid using this function except after installation and
	 * when user wants to do it manually.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public static function clearAll()
	{
		self::clearKunena();
		self::clearSystem();
		self::clearMenu();
		self::clearAccess();
		self::clearCacheFiles();
		self::clearTemplateFiles();
		self::clearStatCache();
		self::clearCompiledPHP();
	}

	/**
	 * Clear Kunena cache.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearKunena()
	{
		$cache = Factory::getCache();
		$cache->clean('com_kunena');
	}

	/**
	 * Clear Joomla system cache.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearSystem()
	{
		$cache = Factory::getCache();
		$cache->clean('_system');
	}

	/**
	 * Clear Joomla menu cache.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearMenu()
	{
		\Joomla\Component\Kunena\Libraries\Menu\Helper::cleanCache();
	}

	/**
	 * Clear Kunena access cache.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 *
	 * @throws  Exception
	 */
	public static function clearAccess()
	{
		\Joomla\Component\Kunena\Libraries\Access::getInstance()->clearCache();
	}

	/**
	 * Clear cached files from Kunena.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearCacheFiles()
	{
		// Delete all cached files.
		$cacheDir = JPATH_CACHE . '/kunena';

		if (is_dir($cacheDir))
		{
			Folder::delete($cacheDir);
		}

		Folder::create($cacheDir);
	}

	/**
	 * Clear cached template files.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearTemplateFiles()
	{
		// Delete all cached files.
		$cacheDir = JPATH_ROOT . "/media/kunena/cache";

		if (is_dir($cacheDir))
		{
			Folder::delete($cacheDir);
		}

		Folder::create($cacheDir);
	}

	/**
	 * Clear PHP statcache (contains file size etc).
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearStatCache()
	{
		clearstatcache();
	}

	/**
	 * Clear compiled PHP files, handy during installation when PHP files change.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearCompiledPHP()
	{
		// Remove all compiled files from APC cache.
		if (function_exists('apc_clear_cache'))
		{
			@apc_clear_cache();
		}

		// Remove all compiled files from XCache.
		if (function_exists('xcache_clear_cache'))
		{
			@xcache_clear_cache(XC_TYPE_PHP);
		}
	}

	/**
	 * Perform normal cache cleanup.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clear()
	{
		self::clearKunena();
		self::clearSystem();
		self::clearMenu();
	}

	/**
	 * Clear Category cache.
	 *
	 * @return  void
	 *
	 * @since   Kunena 6.0
	 */
	public static function clearCategories()
	{
		$cache = Factory::getCache();
		$cache->remove('categories', 'com_kunena');
	}
}
