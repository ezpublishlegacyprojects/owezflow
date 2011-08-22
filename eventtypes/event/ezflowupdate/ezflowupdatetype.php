<?php

require_once( 'kernel/common/template.php' );

class ezflowUpdateType  extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "ezflowupdate";
    const HTML_CONTENT_TYPE = 'text/html';

    var $site_ini;

    function __construct()
    {
        $this->eZWorkflowEventType( self::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'extension/owezflow/workflow/event', 'eZFlow related Update' ) );
        $this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'after') ) ) );
        $this->site_ini = eZIni::instance('site.ini');
    }


    function execute( $process, $event )
    {

    	$parameters = $process->attribute( 'parameter_list' );
    	
	    $db = eZDB::instance();
	    $resArray = $db->arrayQuery( "SELECT DISTINCT ezm_block.node_id FROM ezm_pool, ezm_block WHERE ezm_pool.block_id=ezm_block.id AND ezm_pool.object_id='".$parameters['object_id']."'" );
	
	    foreach($resArray as $res) {
	    	$obj=eZContentObject::fetchByNodeID( $res['node_id']);
	    	eZContentCacheManager::clearObjectViewCache( $obj->ID, true, false );
	    }

        return eZWorkflowType::STATUS_ACCEPTED;
    }

}

eZWorkflowEventType::registerEventType( ezflowUpdateType::WORKFLOW_TYPE_STRING, 'ezflowUpdateType' );

?>