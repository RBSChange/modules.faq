<?php
class faq_ActionBase extends f_action_BaseAction
{
		
	/**
	 * Returns the faq_FaqService to handle documents of type "modules_faq/faq".
	 *
	 * @return faq_FaqService
	 */
	public function getFaqService()
	{
		return faq_FaqService::getInstance();
	}
		
	/**
	 * Returns the faq_PreferencesService to handle documents of type "modules_faq/preferences".
	 *
	 * @return faq_PreferencesService
	 */
	public function getPreferencesService()
	{
		return faq_PreferencesService::getInstance();
	}
		
}