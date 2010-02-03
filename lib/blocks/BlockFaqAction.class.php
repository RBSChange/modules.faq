<?php
class faq_BlockFaqAction extends abstractdirectory_BlockItemAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('faq');
		$this->setComponentName('faq');
	}
}