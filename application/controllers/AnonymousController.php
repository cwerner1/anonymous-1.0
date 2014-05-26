<?php
require_once('Zend/Controller/Action.php');
ini_set("memory_limit","128M");

class AnonymousController extends Zend_Controller_Action {

    function init(){
        $this->view->baseUrl = $this->_request->getBaseUrl();

        $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
        $this->view->headScript()->appendFile('/anonymous/public/js/tablesorter/jquery.tablesorter.min.js');
        $this->view->headScript()->appendFile('/anonymous/public/js/jquery-anonymous-onload.js');  
        $this->view->headScript()->appendFile('/anonymous/public/js/cluetip/jquery.cluetip.min.js');
        $this->view->headLink()->appendStylesheet('/anonymous/public/css/jquery.cluetip.css'); 
        // $this->_helper->layout->disableLayout();
    }

    public function indexAction() {
        
        $this->view->title = 'Anonymous Source Tracker | Schaver.com';
        
        $pageNumber = $this->_getParam('page', 1);
        
        $anon = new Anonymous();
        $select = $anon->fetchAnonymous();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(12);
        $paginator->setPageRange(5);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->feeds = $paginator;

        $counts = $anon->fetchCounts(50);
        $this->view->counts = $counts;

        $total_count = $anon->fetchTotalCount();
        $this->view->total_count = $total_count;
        
        $outlet_count = $anon->fetchOutletCount();
        $this->view->outlet_count = $outlet_count;
        
        $source_count = $anon->fetchSourceCount();
        
        // To make Google chart have to express values as percent of max value
        $values = array();
        $max = max($source_count);
        foreach($source_count as $count){
            $new_value = 100*($count/$max);
            array_push($values,number_format($new_value));
        }
        // Remove the most recent partial day value because it is incomplete and misleading
        array_pop($values);
        $chart_values = implode(",",$values);
        
        $this->view->source_count = $chart_values;
        

    }
    
    public function outletAction() {
        $outlet = $this->_getParam('outlet');
        $pageNumber = $this->_getParam('page', 1);
        $anon = new Anonymous();
        $select = $anon->fetchOutlet($outlet);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(25);
        $paginator->setPageRange(5);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->results = $paginator;
        
        $outlet_name = $anon->fetchOutletName($outlet);
        $this->view->outlet = $outlet_name;
        $this->view->title = 'Anonymous Source Tracker | ' . $outlet_name . ' | Schaver.com';
    }
    
    public function outletsAction() {

        $pageNumber = $this->_getParam('page', 1);
        $anon = new Anonymous();
        $select = $anon->fetchOutlets();
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(50);
        $paginator->setPageRange(10);
        $paginator->setCurrentPageNumber($pageNumber);
        $this->view->counts = $paginator;
        $this->view->max = $anon->fetchMaxCount();
        $this->view->title = 'Anonymous Source Tracker | Outlets | Schaver.com';

  }
    
}
