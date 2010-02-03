<?php
class faq_PreferencesScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return faq_persistentdocument_preferences
     */
    protected function initPersistentDocument()
    {
    	$document = ModuleService::getInstance()->getPreferencesDocument('faq');
    	return ($document !== null) ? $document : faq_PreferencesService::getInstance()->getNewDocumentInstance();
    }
}