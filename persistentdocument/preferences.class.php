<?php
/**
 * faq_persistentdocument_preferences
 * @package modules.faq
 */
class faq_persistentdocument_preferences extends faq_persistentdocument_preferencesbase 
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getLabel()
	 *
	 * @return String
	 */
	public function getLabel()
	{
		return f_Locale::translateUI(parent::getLabel());
	}
}