<?php
class faq_listener_FormSubmittedListener
{

	/**
	* Get the informations posted by the module form to create a faq and 
	* @param form_persistentdocument_form $sender
	* @param mixed $params
	* @return void
	*/
	public function onFormSubmitted($sender, $params)
	{
		// Get the id of form and check if is a faq form
		if ( $sender->getFormid() == 'modules_faq/postnewfaq' )
		{
			
			$reponseData = $params['response']->getAllData();
			
			// Create a webContact
			$webContact = contactcard_WebcontactService::getInstance()->getNewDocumentInstance();
			$webContact->setLabel('[FAQ]' . $reponseData['lastname']['value']);
			$webContact->setLastname($reponseData['lastname']['value']);
			$webContact->setMails($reponseData['email']['value']);
			
			if ( isset($reponseData['firstname']['value']) )
			{
				$webContact->setFirstname($reponseData['firstname']['value']);
			}
			if ( isset($reponseData['postaladdress']['value']) )
			{
				$webContact->setAddress1($reponseData['postaladdress']['value']);
			}
			if ( isset($reponseData['postalcode']['value']) )
			{
				$webContact->setZipcode($reponseData['postalcode']['value']);
			}
			if ( isset($reponseData['city']['value']) )
			{
				$webContact->setCity($reponseData['city']['value']);
			}
			if ( isset($reponseData['phone']['value']) )
			{
				$webContact->setPhone($reponseData['phone']['value']);
			}
			if ( isset($reponseData['fax']['value']) )
			{
				$webContact->setFax($reponseData['fax']['value']);
			}
			$webContact->save();
			
			// Create a faq 
			$faq = faq_FaqService::getInstance()->getNewDocumentInstance();
			$faq->setQuestion($reponseData['question']['value']);
			$faq->setWebcontact($webContact);
			$faq->save();
		
			// Launch the workflow on the faq
			$case = workflow_WorkflowEngineService::getInstance()->initWorkflowInstance($faq->getId());
			
		}
		
	}
}