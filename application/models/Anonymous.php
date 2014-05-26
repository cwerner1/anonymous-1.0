<?php

class Anonymous extends Zend_Db_Table {
    
    protected $_name = 'anonymous';
    
    function insertAnonymous($title, $summary, $url, $outlet, $date_published, $date_updated) {
        $table = new Anonymous();       
        $data = array(
            'title'             => $title,
            'summary'           => $summary,
            'url'               => $url,
            'outlet'            => $outlet,
            'date_published'    => $date_published,
            'date_updated'      => $date_updated
        );
        $table->insert($data);
    }
    
    function fetchAnonymous() {
            $table = new Anonymous();
            $select = $table->select(array('title','summary','url','outlet','date_published'));
            $select->where('display = ?', 1); 
    		$select->order('ID DESC');
    		return $select;
    }
    
    function fetchCounts($limit = 0) {
    	$db = Zend_Db_Table::getDefaultAdapter();
    	if($limit){
    	    $sql = "SELECT outlet, COUNT(*) AS outlet_count FROM anonymous WHERE display = 1 GROUP BY outlet ORDER BY outlet_count DESC, outlet ASC LIMIT $limit";
    	} else {
    	    $sql = "SELECT outlet, COUNT(*) AS outlet_count FROM anonymous WHERE display = 1 GROUP BY outlet ORDER BY outlet_count DESC, outlet ASC";
    	}
	    return $db->fetchAll($sql);
    }
    
    function fetchTotalCount(){
        $db = Zend_Db_Table::getDefaultAdapter();
        $sql = "SELECT count(1) as total_count FROM anonymous WHERE display = 1";
        return $db->fetchOne($sql);   
    }
    
    function fetchOutletCount(){
        $db = Zend_Db_Table::getDefaultAdapter();
        $sql = "SELECT count(distinct outlet) FROM anonymous WHERE display = 1";
        return $db->fetchOne($sql);   
    }
    
    function fetchSourceCount(){
        $db = Zend_Db_Table::getDefaultAdapter();
        $sql = "SELECT COUNT(*) as source_count FROM anonymous WHERE (display = 1) AND (date_published > DATE_SUB(CURRENT_DATE(), INTERVAL 60 DAY)) GROUP BY DAY(date_published)";
        return $db->fetchCol($sql);   
    }

    function fetchOutlets() {
        $table = new Anonymous();
        $select = $table->select();
        $select->from($table, array('COUNT(outlet) as`count`','outlet',));
        $select->group('outlet');
        $select->order('COUNT(outlet) DESC');
        return $select;
    }
    
    function fetchOutlet($outlet){
        	$table = new Anonymous();
            $select = $table->select();
            $select->where('outlet = ?', $outlet);
            $select->where('display = ?', 1);
    		$select->order('date_published DESC');
    		return $select;
    }
    
    function fetchMaxCount(){
        $db = Zend_Db_Table::getDefaultAdapter();
		$sql = "SELECT COUNT(*) AS outlet_count FROM anonymous GROUP BY outlet ORDER BY outlet_count DESC LIMIT 1";
		return $db->fetchOne($sql);	
    }
    
	function fetchOutletName($outlet){
		$db = Zend_Db_Table::getDefaultAdapter();
		$sql = "SELECT outlet FROM anonymous WHERE display = 1 AND outlet = ?";
		return $db->fetchOne($sql, $outlet);	
	}        
}
