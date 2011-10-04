<?php
/**
 * @package		akeebasubs
 * @copyright	Copyright (c)2010-2011 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('KOOWA') or die('');

class ComAkeebasubsControllerTool extends ComAkeebasubsControllerDefault 
{
	public function __construct(KConfig $config)
	{
		$config->append(array(
			'request' => array(
				'import' => 'demo'
			)
		));
	
		parent::__construct($config);

		$this->registerCallback('before.browse' , array($this, 'raiseNotice'));
		
		$cache = JPATH_ROOT.'/cache/com_'.$this->getIdentifier()->package . '/maintenance.import.txt';
		
		jimport('joomla.filesystem.file');
		if(JFile::exists($cache))
		{
			JFile::delete($cache);
		}
	}
	
	public function raiseNotice()
	{
		JError::raiseNotice(0, JText::_("Imported data will replace any existing data. Always remember to backup your site prior to imports."));
	}
	
	protected function _actionRead(KCommandContext $context)
	{
		$this->_actionImport($context);
	}
	
	protected function _actionImport(KCommandContext $context)
	{
		$request   = $this->getRequest();		
		$converter = $this->getModel()->getItem()->convert();

		if($converter->splittable && !isset($request->offset))
		{
			JFactory::getApplication()->close();
			return;
		}

		if(isset($request->print_r)) echo '<pre>', print_r($converter, true), '</pre>';
		JFactory::getApplication()->close();
	}
	
	protected function _actionBrowse(KCommandContext $context)
	{
		$result = parent::_actionBrowse($context);
		
		$view = $this->getView();
		
		if(($context->status != KHttpResponse::NOT_FOUND) || $view instanceof KViewHtml)
		{
			if($view instanceof KViewTemplate && isset($this->_request->layout)) {
				$view->setLayout($this->_request->layout);
			}
			
			$result = $view->display();
		}
		else $result = false;
		
		return $result;
	}

}