<?php
class modules_faq_tests_FaqWorkflowTest extends f_tests_AbstractBaseTest
{

	protected function prepareTestCase()
	{
		$this->truncateAllTables();
		
		// Import init data
		$importTask = new ImportInitDataTask();
		$importTask->main();

		RequestContext::getInstance()->setLang(RequestContext::getInstance()->getDefaultLang());
	}
	
	protected function endTestCase()
	{
		$this->truncateAllTables();
	}
	
	public function testAnswerQuestion()
	{
		
		// Create a faq and get the case
		$case = $this->createFaqAndLaunchWorkflow('question for answer question test');
		$workItem = $case->getWorkitem(0);
		$faq = DocumentHelper::getDocumentInstance($workItem->getDocumentid());
		
		$this->assertEquals($faq->getResponse(), null, 'The response is not null');
		
		$context = Context::getInstance();
		$request = $context->getRequest();
		
		$request->setParameter('response', 'response of faq for the answer question test');
		
		// Call the action to continue the workflow
		$action = new faq_AnswerQuestionWorkflowaction();
		$action->initialize($workItem);
		$action->execute();

		$this->assertEquals($faq->getResponse(), 'response of faq for the answer question test', 'The response not correctly set by the workflow');
		
	}
	
	public function testChooseSavePlaceWithDelete()
	{
		
		// Create a faq and get the case
		$case = $this->createFaqAndLaunchWorkflow('question for choose save place test with delete');
		$workItem = $case->getWorkitem(0);
		$faq = DocumentHelper::getDocumentInstance($workItem->getDocumentid());
		
		$context = Context::getInstance();
		$request = $context->getRequest();
		
		// FIRST TRANSITION //
		$request->setParameter('response', 'response of faq for the answer question test');
		
		// Call the action to continue the workflow
		$action = new faq_AnswerQuestionWorkflowaction();
		$action->initialize($workItem);
		$action->execute();
		// FIRST TRANSITION //
		
		// SECOND TRANSITION //
		// Test that the faq exist
		$this->assertType('faq_persistentdocument_faq', $faq, 'The document faq does not a faq_persistentdocument_faq');
		
		$request->setParameter('node', null);
		
		// Call the chooseSavePlace action to pass the transition
		$action = new faq_ChooseSavePlaceWorkflowaction();
		$action->initialize($workItem);
		$action->execute();
		
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		$query = $pp->createQuery('modules_faq/faq');
		$query->add(Restrictions::eq('question', 'question for choose save place test with delete'));
		$faq = $query->find();
		$this->assertCount(1, $faq, 'The faq not exist');
		if (isset($faq[0]))
		{
			$this->assertEquals($faq[0]->getPublicationstatus(), 'TRASH', 'The document faq is not in the good status : ' . $faq[0]->getPublicationstatus() . ' to TRASH');
		}
		// SECOND TRANSITION //
		
	}
	
	public function testChooseSavePlaceWithSave()
	{
		
		// Create a faq and get the case
		$case = $this->createFaqAndLaunchWorkflow('question for choose save place test with save');
		$workItem = $case->getWorkitem(0);
		$faq = DocumentHelper::getDocumentInstance($workItem->getDocumentid());
		
		$context = Context::getInstance();
		$request = $context->getRequest();
		
		// FIRST TRANSITION //
		$request->setParameter('response', 'response of faq for the answer question test');
		
		// Call the action to continue the workflow
		$action = new faq_AnswerQuestionWorkflowaction();
		$action->initialize($workItem);
		$action->execute();
		// FIRST TRANSITION //
		
		// SECOND TRANSITION //
		// Test that the faq exist
		$this->assertType('faq_persistentdocument_faq', $faq, 'The document faq does not a faq_persistentdocument_faq');
		
		// Get the rootfolder of module faq
		$rootId = ModuleService::getInstance()->getRootFolderId('faq');
		
		$request->setParameter('node', $rootId);
		
		// Call the chooseSavePlace action to pass the transition
		$action = new faq_ChooseSavePlaceWorkflowaction();
		$action->initialize($workItem);
		$action->execute();
		
		// Get the child of root folder and check if faq exist
		$rootFolder = DocumentHelper::getDocumentInstance($rootId);
		
		$childrenFaq = $rootFolder->getDocumentService()->getChildrenOf($rootFolder, 'modules_faq/faq');
		
		// Test that exist one faq
		$this->assertCount(1, $childrenFaq, 'More than one faq found in children of root folder');		
		$this->assertType('faq_persistentdocument_faq', $childrenFaq[0], 'The document faq does not a faq_persistentdocument_faq');
		$this->assertEquals($childrenFaq[0]->getQuestion(), 'question for choose save place test with save', 'The document faq has not the good question');
		$this->assertEquals($childrenFaq[0]->getPublicationstatus(), 'DRAFT', 'The document faq is not in the good status : ' . $childrenFaq[0]->getPublicationstatus() . ' to DRAFT');
		// SECOND TRANSITION //
		
	}

	/**
	 * @param String $question
	 * @return workflow_persistentdocument_case
	 */
	private function createFaqAndLaunchWorkflow($question)
	{

		// Create a new faq
		$faq = faq_FaqService::getInstance()->getNewDocumentInstance();
		$faq->setQuestion($question);
		$faq->save();

		$case = workflow_WorkflowEngineService::getInstance()->initWorkflowInstance($faq->getId());
		
		return $case;
		
	}
	
}