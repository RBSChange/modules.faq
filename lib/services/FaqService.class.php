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
			self::$instance = new self();
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
	protected function preSave($document, $parentNodeId)
	{
		$document->setLabel(f_util_StringUtils::shortenString($document->getQuestion(), 80));
	}
	
	/**
	 * @param website_persistentdocument_topic $parent
	 * @param string $order
	 */
	public function getByContainer($parent, $order = null)
	{
		$query = $this->createQuery()->add(Restrictions::published())->add(Restrictions::childOf($parent->getId()));
		if ($order == 'alpha')
		{
			$query->addOrder(Order::asc('document_label'));
		}
		return $query->find();
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