<?php
/**
 * faq_BlockQuestionListAction
 * @package modules.faq.lib.blocks
 */
class faq_BlockQuestionListAction extends website_BlockAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		$container = $this->getContainer();
		if ($container === null)
		{
			return website_BlockView::NONE;
		}
		$request->setAttribute('container', $container);
		
		$configuration = $this->getConfiguration();
		$items = faq_FaqService::getInstance()->getByContainer($container, $configuration->getOrder());
		if (count($items) > 0)
		{
			$nbItemPerPage = $configuration->getNbItemsPerPage();
			$paginator = new paginator_Paginator($this->moduleName, $request->getParameter(paginator_Paginator::PAGEINDEX_PARAMETER_NAME, 1), $items, $nbItemPerPage);
			$request->setAttribute('paginator', $paginator);
		}
		
		// Get the page with the tag for the form.
		try 
		{
			$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
			$formPage = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_form', $website);
			$request->setAttribute('formPage', $formPage);
		}
		catch (TagException $e)
		{
			if (Framework::isInfoEnabled())
			{
				Framework::info(__METHOD__ . ' No page to submit question (' . $e->getMessage() . ')');
			}
		}
		
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @return website_persistentdocument_topic
	 */
	private function getContainer()
	{
		$container = $this->getConfiguration()->getContainer();
		if ($container instanceof website_persistentdocument_topic)
		{
			return $container;
		}
		$container = $this->getPage()->getParent();
		if ($container instanceof website_persistentdocument_topic)
		{
			return $container;
		}
		return null;
	}
}