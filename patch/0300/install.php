<?php
/**
 * faq_patch_0300
 * @package modules.faq
 */
class faq_patch_0300 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		// Import the inquiry form.
		$this->executeModuleScript('init.xml', 'faq');
		
		// Generate websitetopicsfolders.
		$rootfolderId = ModuleService::getInstance()->getRootFolderId('faq');
		$rootFolder = DocumentHelper::getDocumentInstance($rootfolderId, 'modules_generic/rootfolder');	
		$topics = $rootFolder->getTopicsArray();
		if (count($topics))
		{
			$rootFolder->removeAllTopics();
			$rootFolder->setTopicsArray($topics);
			$rootFolder->save();
		}
		
		// Delete old preferences.
		$model = 'modules_faq/preferences';
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id1 = '$model';");
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id2 = '$model';");
		$this->executeSQLQuery("DELETE FROM f_document WHERE document_model = '$model';");
		$this->executeSQLQuery("DELETE FROM m_abstractdirectory_doc_preferences WHERE document_model = '$model';");
		$this->executeSQLQuery("DELETE FROM m_abstractdirectory_doc_preferences_i18n WHERE document_id NOT IN(SELECT document_id FROM m_abstractdirectory_doc_preferences);");
		
		// Attach all questions lost in space to a system folder and cancel workflows.
		$fs = faq_FaqService::getInstance();
		$websiteRootId = ModuleService::getInstance()->getRootFolderId('website');
		$ids = $fs->createQuery()->add(Restrictions::descendentOf($websiteRootId))->setProjection(Projections::property('id'))->findColumn('id');
		$questions = $fs->createQuery()->add(Restrictions::notin('id', $ids))->find();
		if (count($questions) > 0)
		{
			$systemFolder = ModuleService::getInstance()->getSystemFolderId('faq', 'faq');
			foreach ($questions as $question)
			{
				$question->getDocumentService()->moveTo($question, $systemFolder);
				workflow_WorkflowEngineService::getInstance()->cancelWorkflowInstance($question->getId(), 'FAQWAITANSWER');	
			}
		}
		
		// Copy old templates in override if not exist.
		$templates = array(
			'Faq-Block-ContextualList-Success.all.all.html', 
			'Faq-Block-FaqList-Success.all.all.html', 
			'Faq-Block-Faq-Success.all.all.html', 
			'Faq-Block-Topic-Success.all.all.html'
		);
		foreach ($templates as $template)
		{
			$destPath = f_util_FileUtils::buildOverridePath('modules', 'faq', 'templates', $template);
			if (!file_exists($destPath))
			{
				$origPath = f_util_FileUtils::buildWebeditPath('modules', 'faq', 'patch', '0300', 'templates', $template);
				f_util_FileUtils::cp($origPath, $destPath);
			}
		}
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'faq';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0300';
	}
}