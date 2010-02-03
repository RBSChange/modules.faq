<?php
class faq_FaqService extends f_persistentdocument_DocumentService
{
	/**
	 * @var faq_FaqService
	 */
	private static $instance;

	/**
	 * @return faq_FaqService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return faq_persistentdocument_faq
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_faq/faq');
	}

	/**
	 * Create a query based on 'modules_faq/faq' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_faq/faq');
	}

	/**
	 * @param faq_persistentdocument_faq $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId = null)
	{
		$document->setLabel( f_util_StringUtils::shortenString($document->getQuestion(), 80) );
	}

	/**
	 * Add a response for the faq without pass by the method save of document to skip the workflow status
	 *
	 * @param faq_persistentdocument_faq $document
	 * @param String $response
	 * @return boolean
	 */
	public function addResponse($document, $response)
	{
		try
		{
			$this->tm->beginTransaction();

			$document->setResponse($response);

			// Set the response of document by persistent provider
			$this->pp->updateDocument($document);

			$this->tm->commit();

			// Webcontact associated to faq
			$webContact = $document->getWebcontact();

			if (!is_null($webContact) )
			{
				// Send email for the webcontact who has post the question
				$mailerService = MailService::getInstance();
				$message = $mailerService->getNewMailMessage();
				$message->setSender(Framework::getDefaultNoReplySender());
				$message->setReceiver( implode(',', $webContact->getEmailAddresses() ) );
				$message->setSubject(f_Locale::translate('&modules.faq.mail.answer.Subject;'));
				$message->setHtmlAndTextBody(f_Locale::translate('&modules.faq.mail.answer.message;', array('question' => $document->getQuestion(), 'answer' => $response)));
				$mailerService->send($message);
			}

			return true;
		}
		catch (Exception $e)
		{
			$this->tm->rollBack($e);
		}
		return false;
	}
	
	/**
	 * @param faq_persistentdocument_faq $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
	public function isPublishable($document)
	{
		$reponse = $document->getResponse();
		if (!empty($reponse))
		{
		    return parent::isPublishable($document);
		}    
		return false;
	}

}