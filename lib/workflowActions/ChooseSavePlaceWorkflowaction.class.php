<?php
class faq_ChooseSavePlaceWorkflowaction extends workflow_BaseWorkflowaction
{
	/**
	 * This method will execute the action.
	 * @return boolean true if the execution end successfully, false in error case.
	 */
	function execute()
	{
		$context = Context::getInstance();
		$request = $context->getRequest();
		
		$nodeId = $request->getParameter('node');
		
		$faqService = faq_FaqService::getInstance();
		$faq = $this->getDocument();
		
		if ( ! is_null($nodeId) )
		{
			// Add the faq in module tree
			$node = TreeService::getInstance()->getInstanceByDocumentId($nodeId);
			$node->addNewChild($node, $faq);
			// Pass the faq in draft status, because she mustn't be directly publicated
			$faqService->cancel($faq->getId());
		}
		else 
		{
			// Delete the faq
			//$this->getDocument()->delete();
			$faqService->cancel($faq->getId());
			$faqService->putInTrash($faq->getId());
		}
		
		return parent::execute();
	}
}