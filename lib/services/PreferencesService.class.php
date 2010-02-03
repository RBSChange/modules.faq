<?php
/**
 * @date Fri, 29 Jun 2007 16:18:10 +0200
 * @author intcoutl
 * @package modules.faq
 */
class faq_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @var faq_PreferencesService
	 */
	private static $instance;

	/**
	 * @return faq_PreferencesService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return faq_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_faq/preferences');
	}

	/**
	 * Create a query based on 'modules_faq/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_faq/preferences');
	}

	/**
	 * @param faq_persistentdocument_preferences $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId = null)
	{
		$document->setLabel('&modules.faq.bo.general.Module-name;');
	}
}