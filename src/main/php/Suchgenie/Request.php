<?php

class Suchgenie_Request extends Suchgenie_Requester {

    private $defaultParams = array();
    private $documentsParams = array();
    private $sortings = array();
    private $documentsSortings = array();
    private $navigationSortings = array();

    function getNavigation ($attributes) {
        // query,                               comparators, filters,           attributes
        $params = array();
        $params += $this->defaultParams;
        $params['attributes'] = implode(',', $attributes);
        return $this->getParallelGet("/api/navigation.json", $params);
    }

    function getDocuments ($attributes) {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings, attributes
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        $params += $this->sortings;
        $params['attributes'] = implode(',', $attributes);
        return $this->getParallelGet("/api/documents.json", $params);
    }

    function getDocumentIdentifiers () {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        $params += $this->sortings;
        return $this->getParallelGet("/api/documentIdentifiers.json", $params);
    }

    function getDocumentsAndNavigation ($documentAttributes, $navigationAttributes) {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings, attributes
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        $params += $this->documentsSortings;
        $params += $this->navigationSortings;
        $params['documentAttributes'] = implode(',', $documentAttributes);
        $params['navigationAttributes'] = implode(',', $navigationAttributes);
        return $this->getParallelGet("/api/documentsAndNavigation.json", $params);
    }

    function getDocumentIdentifiersAndNavigation ($attributes) {
        // query, documentsPerPage, pageNumber, comparators, filters, sortings
        $params = array();
        $params += $this->defaultParams;
        $params += $this->documentsParams;
        $params += $this->documentsSortings;
        $params += $this->navigationSortings;
        $params['attributes'] = implode(',', $attributes);
        return $this->getParallelGet("/api/documentIdentifiersAndNavigation.json", $params);
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
        $this->sortings['sort' . $attribute] = $direction;
        return $this;
    }

    function setDocumentsSorting($attribute, $direction) {
        $this->documentsSortings['sortDocuments' . $attribute] = $direction;
        return $this;
    }

    function setNavigationSorting($attribute, $direction) {
        $this->navigationSortings['sortNavigation' . $attribute] = $direction;
        return $this;
    }

    function setFilter($attribute, $comparisonType, $value) {
        $filter = 'filter' . $attribute . $comparisonType;
        $this->defaultParams[$filter] = $value;
        if ($value == null) {
            unset($this->defaultParams[$filter]);
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
