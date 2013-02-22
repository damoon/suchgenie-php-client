<?php

class Suchgenie_Request extends Suchgenie_Requester {
    
    private $defaultParams = array();
    private $documentsParams = array();
    
    function getNavigation ($attributes=array()) {
        // query,                               comparators, filters,           attributes
        $params = array();
        $params += $this->defaultParams;
        $params['attributes'] = implode(',', $attributes);
        return $this->getParallelGet("/api/navigation.json", $params);
    }
    
    function getDocuments ($attributes=array()) {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings, attributes
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        $params['attributes'] = implode(',', $attributes);
        return $this->getParallelGet("/api/documents.json", $params);
    }

    function getDocumentIdentifiers () {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        return $this->getParallelGet("/api/documentIdentifiers.json", $params);
    }
    
    function setQuery($query) {
        $this->defaultParams['query'] = $query;
        return $this;
    }
    
    function setDocumentsPerPage($documentsPerPage) {
        $this->documentsParams['documentsPerPage'] = $documentsPerPage;
        return $this;
    }
    
    function setPageNumber($pageNumber) {
        $this->documentsParams['pageNumber'] = $pageNumber;
        return $this;
    }
    
    function setComparator($attribute, $comparatorClass) {
        $this->defaultParams[$attribute . 'Comparator'] = $comparatorClass;
        return $this;
    }
    
    function setSorting($attribute, $direction) {
        $this->documentsParams['sort' . $attribute] = $direction;
        return $this;
    }
    
    function setFilter($attribute, $comparisonType, $value) {
        $this->documentsParams['filter' . $comparisonType . $attribute] = $value;
        if ($value == null) {
            unset($this->documentsParams['filter' . $comparisonType . $attribute]);
        }
        return $this;
    }
    
    function setEqualFilter($attribute, $value) {
        $this->setFilter($attribute, "eq", $value);
        return $this;
    }
    
    function setNotEqualFilter($attribute, $value) {
        $this->setFilter($attribute, "ne", $value);
        return $this;
    }
    
    function setGreaterEqualFilter($attribute, $value) {
        $this->setFilter($attribute, "ge", $value);
        return $this;
    }
    
    function setGreaterThenFilter($attribute, $value) {
        $this->setFilter($attribute, "gt", $value);
        return $this;
    }
    
    function setLessEqualFilter($attribute, $value) {
        $this->setFilter($attribute, "le", $value);
        return $this;
    }
    
    function setLessThenFilter($attribute, $value) {
        $this->setFilter($attribute, "lt", $value);
        return $this;
    }
    
}
