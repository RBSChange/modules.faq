<?php
class faq_ChooseSavePlaceView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->forceModuleName('faq');
		$this->setTemplateName('Faq-ChooseSavePlace', K::XUL);
		$task = $request->getAttribute('task');
		$workDocument = DocumentHelper::getDocumentInstance($task->getWorkitem()->getDocumentid());
		$model = $workDocument->getDocumentModel();
		$document = DocumentHelper::getPropertiesOf($workDocument);
		$document['id'] = $workDocument->getId();
		$document['taskId'] = $task->getId();
		$document["type"]   = f_Locale::translate($model->getLabel()) . " (" . $model->getDocumentName() . ")";
		$document["question"] = $workDocument->getQuestion();
		$document["response"] = $workDocument->getResponse();
		$this->setAttribute('document', $document);

		

	     $cssInclusion = $this->getStyleService()
	    	  ->registerStyle('modules.dashboard.dashboard')
	    	  ->registerStyle('modules.uixul.bindings')
	    	  ->registerStyle('modules.uixul.backoffice')
	    	  ->registerStyle('modules.faq.backoffice')
	    	  ->execute(K::XUL);
	     
	    $moduleStylesheetUrl = LinkHelper::getUIChromeActionLink('uixul', 'GetStylesheet')
	        ->setQueryParametre(K::WEBEDIT_MODULE_ACCESSOR, 'faq')->getUrl();
	        
	    $cssInclusion .= "\n" . '<?xml-stylesheet href="' . $moduleStylesheetUrl . '" type="text/css"?>';
	    
	    $this->setAttribute(
           'cssInclusion',
           $cssInclusion
	    );
	    
		// include JavaScript
		$scripts = array(
			'modules.uixul.lib.default',
			'modules.dashboard.lib.js.dashboardwidget'
		);
		foreach ($scripts as $script)
		{
			$this->getJsService()->registerScript($script);
		}

        $this->setAttribute('scriptInclusion', $this->getJsService()->execute(K::XUL));
	}
}
