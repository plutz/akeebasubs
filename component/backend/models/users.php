<?php
/**
 * @package AkeebaSubs
 * @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 * @license GNU General Public License version 3, or later
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

class AkeebasubsModelUsers extends FOFModel
{

	private function getFilterValues()
	{
		return (object) array(
				'enabled'		 => $this->getState('enabled', '', 'cmd'),
				'ordering'		 => $this->getState('ordering', '', 'int'),
				'user_id'		 => $this->getState('user_id', '', 'int'),
				'groupbydate'	 => $this->getState('groupbydate', '', 'int'),
				'search'		 => $this->getState('search', null, 'string'),
				'username'		 => $this->getState('username', null, 'string'),
				'name'			 => $this->getState('name', null, 'string'),
				'email'			 => $this->getState('email', null, 'string'),
				'businessname'	 => $this->getState('businessname', null, 'string'),
				'vatnumber'		 => $this->getState('vatnumber', null, 'string'),
				'occupation'	 => $this->getState('occupation', null, 'string'),
				'isbusiness'	 => $this->getState('isbusiness', null, 'int'),
				'viesregistered' => $this->getState('viesregistered', null, 'int'),
				'taxauthority'	 => $this->getState('taxauthority', null, 'string'),
				'address1'		 => $this->getState('address1', null, 'string'),
				'address2'		 => $this->getState('address2', null, 'string'),
				'city'			 => $this->getState('city', null, 'string'),
				'state'			 => $this->getState('state', null, 'string'),
				'zip'			 => $this->getState('zip', null, 'string'),
				'country'		 => $this->getState('country', null, 'string'),
				'getRenewals'	 => $this->getState('getRenewals', null, 'int'),
				'levelid'		 => $this->getState('levelid', null, 'int')
		);
	}

	public function buildCountQuery()
	{
		$db		 = $this->getDbo();

		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__akeebasubs_users') . ' AS ' . $db->qn('tbl'))
			->join('INNER', $db->qn('#__users') . ' AS ' . $db->qn('u') . ' ON ' .
			$db->qn('u') . '.' . $db->qn('id') . ' = ' .
			$db->qn('tbl') . '.' . $db->qn('user_id')
		);

		$this->_buildQueryWhere($query);

		return $query;
	}

	protected function _buildQueryColumns($query)
	{
		$db		 = $this->getDbo();
		$state	 = $this->getFilterValues();

		$query->select(array(
			$db->qn('tbl') . '.*',
			$db->qn('u') . '.' . $db->qn('name'),
			$db->qn('u') . '.' . $db->qn('username'),
			$db->qn('u') . '.' . $db->qn('email'),
		));

		if (!is_null($state->getRenewals))
		{
			$query->select('GROUP_CONCAT(DISTINCT ' . $db->qn('subs') . '.' . $db->qn('akeebasubs_level_id') . ' SEPARATOR ",") as raw_subs');
			$query->select('COUNT(' . $db->qn('subs') . '.' . $db->qn('akeebasubs_level_id') . ') as count_renewals');
		}
	}

	protected function _buildQueryWhere($query)
	{
		$db		 = $this->getDbo();
		$state	 = $this->getFilterValues();

		if (is_numeric($state->ordering))
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('ordering') .
				'=' . $state->ordering);
		}

		if (is_numeric($state->enabled))
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('enabled') .
				'=' . $state->enabled);
		}

		if (is_numeric($state->user_id) && ($state->user_id > 0))
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('user_id') .
				'=' . $state->user_id);
		}

		if ($state->username)
		{
			$query->where($db->qn('u') . '.' . $db->qn('username') .
				' LIKE ' . $db->q('%' . $state->username . '%'));
		}

		if ($state->name)
		{
			$query->where($db->qn('u') . '.' . $db->qn('name') .
				' LIKE ' . $db->q('%' . $state->name . '%'));
		}

		if ($state->email)
		{
			$query->where($db->qn('u') . '.' . $db->qn('email') .
				' LIKE ' . $db->q('%' . $state->email . '%'));
		}

		if ($state->businessname)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('businessname') .
				' LIKE ' . $db->q('%' . $state->businessname . '%'));
		}

		if ($state->occupation)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('occupation') .
				' LIKE ' . $db->q('%' . $state->occupation . '%'));
		}

		if ($state->vatnumber)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('vatnumber') .
				' LIKE ' . $db->q('%' . $state->vatnumber . '%'));
		}

		if ($state->address1)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('address1') .
				' LIKE ' . $db->q('%' . $state->address1 . '%'));
		}

		if ($state->address2)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('address2') .
				' LIKE ' . $db->q('%' . $state->address2 . '%'));
		}

		if ($state->city)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('city') .
				' LIKE ' . $db->q('%' . $state->city . '%'));
		}

		if ($state->state)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('state') .
				' LIKE ' . $db->q('%' . $state->state . '%'));
		}

		if ($state->zip)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('zip') .
				' LIKE ' . $db->q('%' . $state->zip . '%'));
		}

		if ($state->country)
		{
			$query->where($db->qn('tbl') . '.' . $db->qn('country') .
				' = ' . $db->q($state->country));
		}

		if ($state->search)
		{
			$search = '%' . $state->search . '%';
			$query->where(
				'(' .
				'(' . $db->qn('tbl') . '.' . $db->qn('businessname') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('occupation') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('vatnumber') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('address1') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('address2') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('city') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('state') .
				' LIKE ' . $db->q($search) . ') OR ' .
				'(' . $db->qn('tbl') . '.' . $db->qn('zip') .
				' LIKE ' . $db->q($search) . ')'
				. ')'
			);
		}

		// Ok I asked to get all the users with/without at least a renewal. So first of all let's get the expired ones
		if (!is_null($state->getRenewals))
		{
			$renewals = array_merge($this->getRenewals($state->getRenewals), array(-1));

			if (!empty($renewals))
			{
				$query->where($db->qn('tbl') . '.' . $db->qn('user_id') . ' IN(' . implode(',', $renewals) . ')');
			}
		}
	}

	public function buildQuery($overrideLimits = false)
	{
		$db = $this->getDbo();

		$state = $this->getFilterValues();

		$query = $db->getQuery(true)
			->from($db->qn('#__akeebasubs_users') . ' AS ' . $db->qn('tbl'))
			->join('INNER', $db->qn('#__users') . ' AS ' . $db->qn('u') . ' ON ' .
			$db->qn('u') . '.' . $db->qn('id') . ' = ' .
			$db->qn('tbl') . '.' . $db->qn('user_id')
		);

		// If I only want renewals I have to link the subscriptions table, so I can get the subscription
		// (decode from level_id to title is done with an array lookup, to avoid another join)
		if (!is_null($state->getRenewals))
		{
			$query->innerJoin($db->qn('#__akeebasubs_subscriptions') . ' AS ' . $db->qn('subs') . ' ON ' .
				$db->qn('subs') . '.' . $db->qn('user_id') . ' = ' . $db->qn('tbl') . '.' . $db->qn('user_id'));

			$query->group($db->qn('tbl') . '.' . $db->qn('user_id'));
		}

		$this->_buildQueryColumns($query);
		$this->_buildQueryWhere($query);

		$order = $this->getState('filter_order', 'akeebasubs_user_id', 'cmd');

		// I can have fields that aren't in the table object
		$whiteList	 = array_merge(array_keys($this->getTable()->getData()), array('username'));
		if (!in_array($order, $whiteList))
			$order		 = 'akeebasubs_user_id';
		$dir		 = $this->getState('filter_order_Dir', 'DESC', 'cmd');
		$query->order($order . ' ' . $dir);

		return $query;
	}

	private function getRenewals($type)
	{
		static $return = array();

		$db		 = JFactory::getDbo();
		$jDate	 = new JDate();
		$state	 = $this->getFilterValues();

		if (!in_array($type, array(1, -1)))
		{
			return array();
		}

		if (isset($return[$type]))
		{
			return $return[$type];
		}

		$subquery = $db->getQuery(true)
			->select('DISTINCT user_id')
			->from('#__akeebasubs_subscriptions')
			->where('publish_down < ' . $db->q($jDate->toSql()));
		if ($state->levelid)
		{
			$subquery->where($db->qn('akeebasubs_level_id') . ' = ' . $state->levelid);
		}

		$expired = $db->setQuery($subquery)->loadColumn();

		if (!empty($expired))
		{
			// I want users with a renewal, so let's search for users that once expired, they bought a new sub
			if ($type == 1)
			{
				$subquery		 = $db->getQuery(true)
					->select('user_id')
					->from('#__akeebasubs_subscriptions')
					->where('publish_down > ' . $db->q($jDate->toSql()))
					->where('user_id IN(' . implode(',', $expired) . ')');

				if (empty($expired))
				{
					$return[$type] = array();
				}
				else
				{
					$return[$type]	 = $db->setQuery($subquery)->loadColumn();
				}
			}
			elseif ($type == -1)
			{
				// I want users without a renewal, so let's get the people who renewed and then exclude them
				$subquery	 = $db->getQuery(true)
					->select('user_id')
					->from('#__akeebasubs_subscriptions')
					->where('publish_down > ' . $db->q($jDate->toSql()))
					->where('user_id IN(' . implode(',', $expired) . ')');

				if (empty($expired))
				{
					$renewed = array();
				}
				else
				{
					$renewed = $db->setQuery($subquery)->loadColumn();
				}

				if (!empty($renewed))
				{
					$subquery		 = $db->getQuery(true)
						->select('user_id')
						->from('#__akeebasubs_subscriptions')
						->where('publish_down < ' . $db->q($jDate->toSql()))
						->where('user_id NOT IN(' . implode(',', $renewed) . ')');
					$return[$type]	 = $db->setQuery($subquery)->loadColumn();
				}
				else
				{
					$return[$type] = array();
				}
			}
		}

		if (!isset($return[$type]))
		{
			$return[$type] = array();
		}

		return $return[$type];
	}

	public function getMergedData($user_id = null)
	{
		$state = $this->getFilterValues();

		if (is_null($user_id))
		{
			$user_id = $state->user_id;
		}

		// Get a legacy data set from the user parameters
		$userRow = FOFTable::getAnInstance('Juser', 'AkeebasubsTable');
		$userRow->load($user_id);

		if (!($userRow->params instanceof JRegistry))
		{
			JLoader::import('joomla.registry.registry');
			$params = new JRegistry($userRow->params);
		}
		else
		{
			$params = $userRow->params;
		}

		$businessname = $params->get('business_name', '');

		$nativeData = array(
			'isbusiness'	 => empty($businessname) ? 0 : 1,
			'businessname'	 => $params->get('business_name', ''),
			'occupation'	 => $params->get('occupation', ''),
			'vatnumber'		 => $params->get('vat_number', ''),
			'viesregistered' => 0,
			'taxauthority'	 => '',
			'address1'		 => $params->get('address', ''),
			'address2'		 => $params->get('address2', ''),
			'city'			 => $params->get('city', ''),
			'state'			 => $params->get('state', ''),
			'zip'			 => $params->get('zip', ''),
			'country'		 => $params->get('country', ''),
			'params'		 => array()
		);

		$userData	 = $userRow->getData();
		$myData		 = $nativeData;

		foreach (array('name', 'username', 'email') as $key)
		{
			$myData[$key] = $userData[$key];
		}

		$myData['email2'] = $userData['email'];

		unset($userData);

		if ($user_id > 0)
		{
			$row = FOFModel::getTmpInstance('Users', 'AkeebasubsModel')
				->user_id($user_id)
				->getFirstItem();

			if ($row->user_id == $user_id)
			{
				$myData = array_merge($myData, $row->getData());

				if (is_string($myData['params']))
				{
					$myData['params'] = json_decode($myData['params'], true);

					if (is_null($myData['params']))
					{
						$myData['params'] = array();
					}
				}
			}
		}
		else
		{
			$myData = array();
		}

		// Finally, merge data coming from the plugins. Note that the
		// plugins only run when a new subscription is in progress, not
		// every time the user data loads.
		JLoader::import('joomla.plugin.helper');
		JPluginHelper::importPlugin('akeebasubs');

		$app		 = JFactory::getApplication();
		$jResponse	 = $app->triggerEvent('onAKUserGetData', array((object) $myData));

		if (is_array($jResponse) && !empty($jResponse))
		{
			foreach ($jResponse as $pResponse)
			{
				if (!is_array($pResponse))
				{
					continue;
				}

				if (empty($pResponse))
				{
					continue;
				}

				if (array_key_exists('params', $pResponse))
				{
					if (!empty($pResponse['params']))
					{
						foreach ($pResponse['params'] as $k => $v)
						{
							$myData['params'][$k] = $v;
						}
					}

					unset($pResponse['params']);
				}

				$myData = array_merge($myData, $pResponse);
			}
		}

		if (!isset($myData['params']))
		{
			$myData['params'] = array();
		}

		$myData['params'] = (object) $myData['params'];

		return (object) $myData;
	}

	protected function onBeforeSave(&$data, &$table)
	{
		if (array_key_exists('custom', $data))
		{
			$params			 = json_encode($data['custom']);
			unset($data['custom']);
			$data['params']	 = $params;
		}

		return true;
	}

}