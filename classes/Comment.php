<?phprequire_once 'Content.php';/** * class Comment *  */class Comment extends Content{     /** Aggregations: */     /** Compositions: */      /*** Attributes: ***/     /**      *       * @access private      */     private $parentId;     /**      *       * @access private      */     private $personTags;     /**      *       * @access private      */     private $commentId;     /**      *       *      * @return string      * @access public      */     public function getCommentId() {          return $this->commentId;     } // end of member function getCommentId     /**      *       *      * @return string      * @access public      */     public function getParentId() {          return $this->parentId;     } // end of member function getParentId     /**      *       *      * @return string      * @access public      */     public function getPersonTags() {          return $this->persontags;     } // end of member function getPersonTags} // end of Comment?>