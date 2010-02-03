<?php
class modules_faq_tests_FaqServiceTest extends f_tests_AbstractBaseTest
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
	
	public function testSave()
	{
		
		// Get the service
		$faqService = faq_FaqService::getInstance();
		
		// Get a new faq
		$faq = $faqService->getNewDocumentInstance();
		$faq->setQuestion('la question de moins de 80 caractères');
		$faq->save();
		
		$this->assertEquals($faq->getQuestion(), 'la question de moins de 80 caractères', 'bad save for fields question');
		$this->assertEquals($faq->getLabel(), 'la question de moins de 80 caractères', 'bad save for fields label');
		
		$faq->setQuestion('la question de plus de 80 caractères avec le label qui sera coupé pour respecter la contrainte accessibilité');
		$faq->save();
		
		$this->assertEquals($faq->getQuestion(), 'la question de plus de 80 caractères avec le label qui sera coupé pour respecter la contrainte accessibilité', 'bad save for fields question');
		$this->assertEquals($faq->getLabel(), 'la question de plus de 80 caractères avec le label qui sera coupé pour respec...', 'bad save for fields label, the string is not correctly cut');
		
	}	
	
	public function testAddResponse()
	{
		
		// Get the service
		$faqService = faq_FaqService::getInstance();
		
		// Get a new faq
		$faq = $faqService->getNewDocumentInstance();
		$faq->setQuestion('la question de moins de 80 caractères');
		$faq->save();
		
		$faqService->addResponse($faq, 'réponse à la question de moins de 80 caractères');
		
		$this->assertEquals($faq->getResponse(), 'réponse à la question de moins de 80 caractères');
		
	}

}