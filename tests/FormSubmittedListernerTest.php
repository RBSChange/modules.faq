<?php
class modules_faq_tests_FormSubmittedListenerTest extends f_tests_AbstractBaseTest
{
	
	protected function prepareTestCase()
	{
		$this->truncateAllTables();
		RequestContext::getInstance()->setLang(RequestContext::getInstance()->getDefaultLang());
	}
	
	protected function endTestCase()
	{
		$this->truncateAllTables();
	}
	
	public function testOnFormSubmitted()
	{
		// Import init data
		$importTask = new ImportInitDataTask();
		$importTask->main();
				
		// Get the persistent provider
		$pp = f_persistentdocument_PersistentProvider::getInstance();
		
		// Get an instance of the listener
		$listener = new faq_listener_FormSubmittedListener();
		
		// Create an array of parameters
		$responseParam = array();
		$responseParam['lastname']['value'] = "user name";
		$responseParam['email']['value'] = "toto@rbs.fr";
		$responseParam['firstname']['value'] = "user firstname";
		$responseParam['postaladdress']['value'] = "postal address";
		$responseParam['postalcode']['value'] = "postal code";
		$responseParam['city']['value'] = "city";
		$responseParam['phone']['value'] = "phone";
		$responseParam['fax']['value'] = "fax";
		$responseParam['question']['value'] = "QuestionTest";
		
		$params = array('response' => $this->getMockFormResponse($responseParam));
		
		$listener->onFormSubmitted($this->getMockPersistentForm('modules_faq/postnewfaq'), $params);
		
		// Test if webContact exist
		$query = $pp->createQuery('modules_contactcard/webcontact');
		$query->add(Restrictions::eq('lastname', "user name"))
			->add(Restrictions::eq('mails', 'toto@rbs.fr'));
		$webcontact = $query->find();
		$this->assertCount(1, $webcontact, 'Webcontact with lastname value : "user name" and mails value : "toto@rbs.fr" does not exist');
		
		// Test if faq exist
		$query = $pp->createQuery('modules_faq/faq');
		$query->add(Restrictions::eq('question', 'QuestionTest'))
			->add(Restrictions::eq('webcontact', $webcontact[0]->getId()));
		$faq = $query->find();
		$this->assertCount(1, $faq, 'Faq with question value : "QuestionTest" does not exist');

		// Test if workflow correctly launched
		$query = $pp->createQuery('modules_workflow/workitem');
		$query->add(Restrictions::eq('documentid', $faq[0]->getId()));
		$this->assertCount(1, $query->find(), 'Workflow not exist for faq "QuestionTest"');
		
	}

	/**
	 * Return a mock persistentForm that return the id you wanted to
	 *
	 * @param Integer $id
	 * @return form_persistentdocument_form
	 */
	protected function getMockPersistentForm($formId)
	{
		$mock = $this->getMock('form_persistentdocument_form');
		$mock->expects($this->any())
			->method('getFormid')
			->will($this->returnValue($formId));
		return $mock;
	}
	
	/**
	 * Return a mock 
	 *
	 * @param Array $array List of parameters for construct a response
 	 * @return form_persistentdocument_form
	 */
	protected function getMockFormResponse($array)
	{
		$mock = $this->getMock('form_persistentdocument_response');
		$mock->expects($this->any())
			->method('getAllData')
			->will($this->returnValue($array));
		return $mock;
	}
	
}