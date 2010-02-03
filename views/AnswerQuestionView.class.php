<?php
class faq_AnswerQuestionView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->forceModuleName('faq');
		$this->setTemplateName('Faq-AddResponse', K::XUL);
		$task = $request->getAttribute('task');
		$workDocument = DocumentHelper::getDocumentInstance($task->getWorkitem()->getDocumentid());
		$model = $workDocument->getDocumentModel();
		$document = DocumentHelper::getPropertiesOf($workDocument);
		$document['id'] = $workDocument->getId();
		$document['taskId'] = $task->getId();
		$document["type"]   = f_Locale::translate($model->getLabel()) . " (" . $model->getDocumentName() . ")";
		$document["question"] = $workDocument->getQuestion();
		$this->setAttribute('document', $document);

		$this->setAttribute(
           'cssInclusion',
           $this->getStyleService()
	    	  ->registerStyle('modules.dashboard.dashboard')
	    	  ->registerStyle('modules.uixul.bindings')
	    	  ->registerStyle('modules.uixul.backoffice')
	    	  ->execute(K::XUL)
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
