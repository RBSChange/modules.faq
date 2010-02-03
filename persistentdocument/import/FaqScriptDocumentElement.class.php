<?php
class faq_FaqScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return faq_persistentdocument_faq
     */
    protected function initPersistentDocument()
    {
    	return faq_FaqService::getInstance()->getNewDocumentInstance();
    }
}