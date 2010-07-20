<?php
/**
 * faq_BlockDetailAction
 * @package modules.faq.lib.blocks
 */
class faq_BlockDetailAction extends website_BlockAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		$question = $this->getDocumentParameter();
		if (!($question instanceof faq_persistentdocument_faq))
		{
			return website_BlockView::NONE;
		}
		$request->setAttribute('item', $question);
		return $this->getTemplateByFullName('modules_faq', 'Faq-Block-DetailFaq-Success');
	}
}