<?php
class faq_BlockFaqListAction extends abstractdirectory_BlockListAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('faq');
		$this->setComponentName('faq');
	}
}