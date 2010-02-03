<?php
class faq_persistentdocument_faq extends faq_persistentdocument_faqbase implements indexer_IndexableDocument{
	/**
	 * Get the indexable document
	 *
	 * @return indexer_IndexedDocument
	 */
	public function getIndexedDocument()
	{
		$indexedDoc = new indexer_IndexedDocument();
		$indexedDoc->setId($this->getId());
		$indexedDoc->setDocumentModel('modules_faq/faq');
		$indexedDoc->setLabel($this->getLabel());
		$indexedDoc->setLang(RequestContext::getInstance()->getLang());
		$indexedDoc->setText($this->getQuestion() . ' : ' . $this->getResponseAsHtml());
		return $indexedDoc;
	}
}