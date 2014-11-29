<?php

error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL ^ E_WARNING);

	require_once 'miniClasses/miniPost.php';
	require_once 'miniClasses/miniComment.php';
	require_once '../../QOB/qob.php';
	require_once '../fetch.php';
	session_start();
	
	$_SESSION['jx']="999"; //999 for latest posts 998 for popular posts 997 for important posts
	
	//echo $_SESSION['mq']; 
	//$_SESSION['vj'] = 'de48275b8b7bf427e8704f000007d28f2cfdc6491b60c0c720d0bf3f9e556728741ae47c9805f5875e90894b29cec46a130403f77c5a94e5f7d76db1a55ddcd4';
	if($_POST['_call']==-1)
	{
		$_SESSION['mq']=0;
	}

//echo $_SESSION['mq'];
	include 'functions.php';	
			
	function latest()
		{
			$conObj = new QoB();
			$limit = 5;
			$maxlimit = 20*$limit;
			$noOfPostsShown = 0;
			$hashname = $_SESSION['vj'];
			$values1 = array(0 => array($hashname=>'s') );
			$result1 = $conObj->select("SELECT userId FROM users WHERE userIdHash = ?", $values1);
			
			if($conObj->error == "")
				{
					$row1 = $conObj->fetch($result1);
					$currentUserId = $row1['userId'];
					$values2 = array(0 => array($_SESSION['mq']=>'i'), 1 => array($maxlimit=>'i')); 
					$result2 = $conObj->select("SELECT * FROM post order by timestamp DESC LIMIT ?,?",$values2);
							
					if($conObj->error == "")
						{
							$noofelements2 = 0;
							while(($latestPosts = $conObj->fetch($result2))&&($noOfPostsShown<$limit))
								{
									//echo 'Entering while loop<br />';
									if(sharedwith($currentUserId,$latestPosts['sharedWith']) == 1)
										{
											$isHidden = hiddenTo($currentUserId,$latestPosts['hiddenTo']);
											if($isHidden == 1)
												{
													$date = date_create();
													$currentTimestamp = date_timestamp_get($date);
													//echo $currentTimestamp."   ".$latestPosts['lifetime']."\n";
													//echo 'postid - '.$latestPosts['postId'].'<br />';
													//echo 'currenttimestamp'.$currentTimestamp;
													//echo '<br /> latestPosts'.$latestPosts['lifetime'].'<br />'; 
													if($currentTimestamp < $latestPosts['lifetime'])
														{
															$days = ($latestPosts['lifetime'] - $latestPosts['timestamp'])/86400;
															$followPost = isFollowing($latestPosts['userId'],$latestPosts['followers']);
															$userIdHash = getHash($latestPosts['userId']);
															//print $userIdHash;
															$postUserName = getName($latestPosts['userId']);
															//print $postUserName;
															$ts = new DateTime();
															$ts->setTimestamp($latestPosts['timestamp']);
															 $timestamp=$ts->format(DateTime::ISO8601);
															// $timestamp="2008-07-17T09:24:17Z";
															$postOwner=-1;
															//echo $currentUserId." ".$latestPosts['userId'];
															if(strcasecmp($currentUserId,$latestPosts['userId']) == 0)
																{
																	//echo "compared correctly";
																	$postOwner = 1;
																}
															else
																{
																	$postOwner = -1;
																}
																//echo '<br/>';
																//echo $postOwner;
												
															//$outputac = "";
															//$commentvalues = array(0 => array($postId => 's'));
															$postId = 'p'.$latestPosts['postId'].'c';
															$commentResult = $conObj->select("SELECT * FROM ".$postId." ORDER BY timestamp DESC LIMIT 0,3");
															
															$userId = "";
															
															if($conObj->error == "")
																{
																	$noofelementsc = 0;
																	$outputac = "";
																	while($postComments = $conObj->fetch($commentResult))
																		{
																			$commentId = $postComments['commentId'];
																			$content = $postComments['content'];
																			$userId = $postComments['userId'];
																			if($currentUserId==$userId)
																			{
																				$commentOwner=1;
																			}
																			else
																			{
																				$commentOwner=-1;
																			}
																			$userIdHash = getHash($userId);
																			
																			$personTags = $postComments['personTags'];
																			$commentTimestamp = $postComments['timestamp'];
																			$commentIdHash = $postComments['commentIdHash'];
																			
																			$ts = new DateTime();
																			$ts->setTimestamp($commentTimestamp);
																			$commentCreationTime=$ts->format(DateTime::ISO8601);
																			//$idAndName = getIdAndName($userIdHash);
																			//$commentUserId = $idAndName[1];
																			$commentUserName = getName($userId);
																			$commentCount="";
																			
																			$objc = new miniComment($latestPosts['postIdHash'],$userIdHash,$postComments['content'],$commentCreationTime,$postComments['commentIdHash'],$userId,$commentUserName,$commentOwner);
																			
																			$outputac[$noofelementsc] = $objc;
																			
																			$noofelementsc++;
																		}
																	
																	if($noofelementsc == 0)
																		{
																			//echo 'No values found for Query of Comments<br />';
																			
																		}
																}
															
															else
																{
																	//echo 'Error in Query of comments<br />';
																	//echo $conObj->error.'<br />';
																	//return -1;
																}
																
																//echo $currentUserId;
															
																$hasStarred = hasStarred($currentUserId, $latestPosts['starredBy']);
																
																
																//print_r($outputac);
																//echo $outputac[1]->commentUserId;
																//echo '<br /> above obj'.$postOwner;
																$sWith=$latestPosts['sharedWith'];
																$sWith=changeToRawSharedWith($sWith);
																//echo $sWith;
																$obj = new miniPost($latestPosts['postIdHash'],$sWith,$days,$postUserName,$latestPosts['subject'],$latestPosts['content'],$latestPosts['starCount'],$latestPosts['commentCount'],$latestPosts['mailCount'],$latestPosts['seenCount'],$timestamp,$followPost,$userIdHash,$latestPosts['userId'],$hasStarred,$outputac,$postOwner );
															//echo $latestPosts['postIdHash'];
															$outputa[$noOfPostsShown] = $obj;
															$noOfPostsShown++;	
														}
													else 
														{
															//echo"DId not go";
														}
												}
										}
											
									
									$noofelements2++;
								}
							
							$_SESSION['mq'] = $_SESSION['mq'] + $noofelements2; 
							if($noofelements2 == 0)
								{
									echo 404;
									//echo 'No values found for Query2';
									//return 'No values found for Query2';
								}
							
						}
							
					else
						{
							//echo 'Error in Query 2<br />';
							//echo $conObj->error.'<br />';
						}
					
							
				}
			
			else
				{
					//echo 'Error in Query 1 <br />';
					//echo $conObj->error.'<br />';
				}
				
			if($noofelements2>0)
			{
					$outputarraylength = count($outputa);
					$jasonarray;
					
					for($i = 0;$i<$outputarraylength;$i++)
						{
							/*
							echo '<h2>POSTID</h2><br>' .$outputa[$i]->postId.'<br>';
							echo '<h2>SHARED WITH</h2><br>' .$outputa[$i]->sharedWith.'<br>';
							echo '<h2>POST VALIDITY</h2><br>' .$outputa[$i]->postValidity.'<br>';
							echo '<h2>POST USERs ID</h2><br>' .$outputa[$i]->postUserId.'<br>';
							echo '<h2>PROFILE PIC</h2><br>' .$outputa[$i]->postProfilePic.'<br>';

							echo '<h2>POST USER NAME</h2><br>' .$outputa[$i]->postUserName.'<br>';
							echo '<h2>SUBJECT</h2><br>' .$outputa[$i]->postSubject.'<br>';
							echo '<h2>CONTENT</h2><br>' .$outputa[$i]->postContent.'<br>';
							echo '<h2>NO OF STARS</h2><br>' .$outputa[$i]->noOfStars.'<br>';
							echo '<h2>NO OF COMMENTS</h2><br>' .$outputa[$i]->noOfComments.'<br>';

							echo '<h2>NO OF MAIL TOs</h2><br>' .$outputa[$i]->noOfMailTos.'<br>';
							echo '<h2>NO OF SEEN</h2><br>' .$outputa[$i]->postSeenNumber.'<br>';
							echo '<h2>TIMESTAMP</h2><br>' .$outputa[$i]->postCreationTime.'<br>';
							echo '<h2>FOLLOW</h2><br>' .$outputa[$i]->followPost.'<br>';
							echo '<h2>COMMENTS</h2><br>';
							*/
							$jasonarray[$i] = $outputa[$i];
						}
						
				print_r(json_encode($jasonarray));	
			}
			
		}

	latest();
	
	//////echo 'second call to important <br />';
	//echo 'session variable'.$_SESSION['mq'].'<br />';
	//slatest();

?>
