<?php
/**
 * @package    DD_SocialShare
 *
 * @author     HR IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2017 - 2017 Didldu e.K. | HR IT-Solutions
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * DD SocialShare Button Editors Plugin
 *
 * @since  1.0.0.0
 */
class PlgButtonDD_SocialShare extends JPlugin
{

	protected $app;

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0.0.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Display the button
	 *
	 * @param   string  $name  The name of the button to add
	 *
	 * @return  JObject  The button options as JObject
	 *
	 * @since   1.0.0.0
	 */
	public function onDisplay($name)
	{
		$user  = JFactory::getUser();

		if ($user->authorise('core.create', 'com_contact')
			|| $user->authorise('core.edit', 'com_contact')
			|| $user->authorise('core.edit.own', 'com_contact'))
		{
			// Get active content ID
			$content_id = $this->app->input->getCmd('id', '', 'INT');

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->qn('id'))
				->from($db->qn('#__dd_socialshare'))
				->where($db->quoteName('content_id') . '=' . (int) $content_id);

			// Get active socialshare ID
			$id = (int) $db->setQuery($query)->loadResult();

			// The URL for the socialshare edit modal
			if ($id === 0)
			{
				$task = 'article.add';
			}
			else
			{
				$task = 'article.edit';
			}

			$link = 'index.php?option=com_dd_socialshare&amp;task=' . $task . '&amp;layout=modal&amp;tmpl=component&amp;'
				. JSession::getFormToken() . '=1&amp;id=' . $id . '&amp;content_id=' . $content_id;

			$button          = new JObject;
			$button->modal   = true;
			$button->class   = 'btn';
			$button->link    = $link;
			$button->text    = JText::_('PLG_EDITORS-XTD_DD_SOCIALSHARE_BUTTON_SOCIALSHARE');
			$button->name    = 'grid';
			$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

			return $button;
		}
	}
}
