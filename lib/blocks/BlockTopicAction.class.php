<?php
class faq_BlockTopicAction extends abstractdirectory_BlockTopicAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('faq');
		$this->setComponentName('faq');
	}

	/**
	 * This is a entry to add some informations to the template without rewrite execute method
	 *
	 */
	protected function setAdditionalParameters()
	{
		// Get the page with the tag for the form
		$ws = website_WebsiteModuleService::getInstance();
		try 
		{
			$formPage = $ws->getDocumentByContextualTag('contextual_website_website_form', $ws->getCurrentWebsite());
			
			// Set the display form link variable
			$diplayForm = false;
			
			// Get the preferences of module 
			$preferences = ModuleService::getInstance()->getPreferencesDocument('faq');
			if ( ! is_null($preferences) )
			{
				$diplayForm = $preferences->getActivequestion() == 1 ? true : false;
			}
			
			if ( $diplayForm )
			{
				$this->setParameter('formPage', $formPage);
			}
		}
		catch (TagException $e)
		{
			Framework::exception($e);
		}
	}
}