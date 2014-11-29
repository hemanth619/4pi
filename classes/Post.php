<?phprequire_once 'SharedContent.php';require_once 'Post.php';/** * class Post *  */class Post extends SharedContent{     /** Aggregations: */     /** Compositions: */     var $m_;      /*** Attributes: ***/     /**      *       * @access protected      */     protected $postId;     /**      *       * @access protected      */     protected $starCount = 0;     /**      *       * @access protected      */     protected $mailCount = 0;     /**      *       * @access protected      */     protected $expiryDate;     /**      *       * @access protected      */     protected $starredBy;     /**      *       * @access protected      */     protected $pi;     /**      *       * @access protected      */     protected $subject;     /**      *       * @access protected      */     protected $seenCount;     /**      *       * @access protected      */     protected $likeIndex;     /**      *       * @access protected      */     protected $commentIndex;     /**      *       * @access protected      */     protected $mailtoIndex;     /**      *       *      * @return bool      * @access public      */     public function edit() {     } // end of member function edit     /**      * remaining attributes which are not passed as parameters have to be generated      * inside the function.

To Improve security, we can have a pasword check to verify      * the user posting the content. This is to avoid anonymous posts even incase      * someone intrudes the existing security.      *      * @param string postingUser       * @param string Description       * @param string sharedWith       * @param bool LifetimeReq       * @param string expiryDate //time after which the post is automatically deleted.      * @return Post      * @access public      */     public function Post( $postingUser,  $Description = "No Description Yet",  $sharedWith = "ALL",  $LifetimeReq = false,  $expiryDate) {     } // end of member function Post     /**      *       *      * @return string      * @access public      */     public function getPostId() {          return $this->postId;     } // end of member function getPostId     /**      *       *      * @return int      * @access public      */     public function getStarCount() {          return $this->starCount;     } // end of member function getStarCount     /**      *       *      * @return int      * @access public      */     public function getMailCount() {          return $this->mailCount;     } // end of member function getMailCount     /**      *       *      * @return string      * @access public      */     public function getExpirydate() {          return $this->expiryDate;     } // end of member function getExpirydate     /**      *       *      * @return string      * @access public      */     public function getStarrers() {          return $this->starredBy;     } // end of member function getStarrers     /**      *       *      * @return float      * @access public      */     public function getPopularityIndex() {          return $this->pi;     } // end of member function getPopularityIndex     /**      *       *      * @return string      * @access public      */     public function getSubject() {          return $this->subject;     } // end of member function getSubject     /**      *       *      * @return int      * @access public      */     public function getSeenCount() {          return $this->seenCount;     } // end of member function getSeenCount     /**      *       *      * @return float      * @access public      */     public function getLikeIndex() {          return $this->likeIndex;     } // end of member function getLikeIndex     /**      *       *      * @return float      * @access public      */     public function getCommentIndex() {          return $this->commentIndex;     } // end of member function getCommentIndex     /**      *       *      * @param string content       * @param string userId       * @param string tags       * @return void      * @access public      */     public function CommentAction( $content,  $userId,  $tags) {     } // end of member function CommentAction     /**      *       *      * @param string event       * @return void      * @access private      */     private function computeIndex( $event) {     } // end of member function computeIndex} // end of Post?>