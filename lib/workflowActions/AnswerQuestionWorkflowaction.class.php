<?php
class faq_AnswerQuestionWorkflowaction extends workflow_BaseWorkflowaction
{
	/**
	 * This method will execute the action.
	 * @return boolean true if the execution end successfully, false in error case.
	 */
	function execute()
	{
		$request = Context::getInstance()->getRequest();
		
		$faqService = faq_FaqService::getInstance();
		$faqService->addResponse($this->getDocument(), $request->getParameter('response'));
		
		return parent::execute();
	}
}