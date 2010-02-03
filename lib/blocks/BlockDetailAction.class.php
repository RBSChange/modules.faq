<?php
class faq_BlockDetailAction extends abstractdirectory_BlockDetailAction
{
	/**
     * @param block_BlockContext $context
     * @param block_BlockRequest $request
     */
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('faq');
		$this->setComponentName('faq');
		$this->setViewModuleName('faq');
	}
}


class faq_BlockDetailSuccessView extends abstractdirectory_BlockDetailSuccessView
{
	
}

class faq_BlockDetailDummyView extends abstractdirectory_BlockDetailDummyView
{
	
}