<?php

	require("../../PHPMailer_v5.1/class.phpmailer.php");
	function postFetch($postid)
		{
			$conObj = new QoB();
			$values1 = array(0 => array($postid => 's'));
			$result1 = $conObj->fetchall("SELECT * FROM post WHERE postId = ?",$values1,false);
			if($conObj->error == "")
				{
					if($result1 != "")
						{
							$userId = $row2['userId'];
							$content = $row2['content'];
							$timestamp = $row2['timestamp'];
							$reportedBy = $row2['reportedBy'];
							$spamCount = $row2['spamCount'];
													
							$postId = $row2['postId'];
							$subject = $row2['subject'];
							$starCount = $row2['starCount'];
							$mailCount = $row2['mailCount'];
							$mailedBy = $row2['mailedBy'];
							
							$isPermanent = $row2['isPermanent'];
							$starredBy = $row2['starredBy'];
							$displayStatus = $row2['displayStatus'];
							$lastUpdated = $row2['lastUpdated'];
							$likeIndex = $row2['likeIndex'];
							
							$commentIndex = $row2['commentIndex'];
							$lifetime = $row2['lifetime'];
							$impIndex = $row2['impIndex'];
							$requestPermanence = $row2['$requestPermanence'];
							$popularityIndex = $row2['popularityIndex'];
							
							$followers = $row2['followers'];
							$hiddenTo = $row2['hiddenTo'];
							$filesAttached = $row2['filesAttached'];
							$commentCount = $row2['commentCount'];
							$mailToIndex = $row2['mailToIndex'];
							
							$taggedUsers = $row2['taggedUsers'];
							$seenBy = $row2['seenBy'];
							$seenCount = $row2['seenCount'];
							$sharedWith = $row2['sharedWith'];
							
							$postObj = new Post($userId,$subject,$content,$seenCount,$postId,$starCount,$mailCount,$commentCount,$timestamp,$reportedBy,$spamCount,$mailedby,$isPermanent,$starredBy,$displayStatus,$lastUpdated,$likeIndex,$commentIndex,$lifetime,$impIndex,
                                $requestPermanence,$popularityIndex,$followers,$hiddenTo,$filesAttached,$mailToIndex,$sharedWith,$seenBy,$taggedUsers);
							
							return $postObj;
						
						}
					
					else
						{
							return false;
						}

				}
			else
				{
					return false;
				}
		}
		
		function getUserFromHash($userHash)
		{
			$conn=new QoB();
			$fetchUserSQL="SELECT * FROM users WHERE userIdHash= ?";
			$values[0]=array($userHash=>'s');
			$result=$conn->fetchAll($fetchUserSQL,$values);
			if($conn->error==""&&$result!="")
			{	
				return $result;
			}
			else
			{
				return false;
			}
		}
		
		
		function getPostFromHash($postIdHash)
		{
			$conn=new QoB();
			$values = array(0 => array($postIdHash => 's'));
			$result = $conn->fetchAll("SELECT * FROM post WHERE postIdHash = ?",$values,false);
			if($conn->error==""&&$result!="")
			{
				return $result;
			}
			else
			{
				return false;
			}
		}


		function getEventFromHash($eventIdHash)
		{
			$conn=new QoB();
			$values = array(0 => array($eventIdHash => 's'));
			$result = $conn->fetchAll("SELECT * FROM event WHERE eventIdHash = ?",$values,false);
			if($conn->error==""&&$result!="")
			{
				return $result;
			}
			else
			{
				return false;
			}
		}


		/*function isfollowing($hash,$follwers)
		{
			$followersarray = explode(',',$followers);
			$nooffollowers = count($followersarray);
			
			$output = 0;
			for($i = 0;$i<$nooffollowers;$i++)
				{
					if(strcmp($hash,$followersarray[$i]) == 0)
						{
							$output++;
							break;
						}
				}
				
			if($output == 0)
				{
					return -1;
				}
			else
				{
					return 1;
				}
		}*/

		/*function hiddenTo($userId,$hiddenTo)
		{
			$hiddenToarray = explode(',',$hiddenTo);
			$noofhidden = count($hiddenToarray);
			
			$output = 0;
			for($i = 0;$i<$noofhidden;$i++)
				{
					if(strcmp($userId,$hiddenToarray[$i]) == 0)
						{
							$output++;
							break;
						}
				}
				
			if($output == 0)
				{
					return -1;
				}
			else
				{
					return 1;
				}
		}*/
		/*function hasStarred($userId,$starredBy)
		{
			$hiddenToarray = explode(',',$hiddenTo);
			$noofhidden = count($hiddenToarray);
			
			$output = 0;
			for($i = 0;$i<$noofhidden;$i++)
				{
					if(strcmp($userId,$hiddenToarray[$i]) == 0)
						{
							$output++;
							break;
						}
				}
				
			if($output == 0)
				{
					return -1;
				}
			else
				{
					return 1;
				}
		}*/



		function hasReported($userId,$hiddenTo)
		{
			$hiddenToarray = explode(',',$hiddenTo);
			$noofhidden = count($hiddenToarray);
			
			$output = 0;
			for($i = 0;$i<$noofhidden;$i++)
				{
					if(strcmp($userId,$hiddenToarray[$i]) == 0)
						{
							$output++;
							break;
						}
				}
				
			if($output == 0)
				{
					return -1;
				}
			else
				{
					return 1;
				}
		}
		function blockUserByHash($userIdHash,$crime,$privateData="")
		{
			$conn=new QoB();
			$isActive=0;
			$noteCrimeSQL="INSERT INTO suspicious (userId, suspicion) VALUES(?,?)";
			$UpdateUserSQL="UPDATE users SET isActive=? WHERE userIdHash= ?";
			$values[0]=array($isActive=>'i');
			$values[1]=array($userIdHash=>'s');
			$result=$conn->update($UpdateUserSQL,$values);
			if($conn->error==""&&$result==true)
			{
				$adminNotif="Blocked: ".$crime.",".$privateData;
				//$user=getUserFromHash($userIdHash);
				//$userId=$user['userId'];
				$values1[0]=array($userIdHash =>'s');
				$values1[1]=array($adminNotif => 's');
				$result2=$conn->insert($noteCrimeSQL,$values1);
				if($conn->error==""&&$result2==true){
					return 3;
				}
				else{
					return 2;
				}
			}
			else
			{
				return -1;
			}
		}
		function notifyAdmin($notification,$userIdentity)
		{
			$conn=new QoB();
			$notification="Notify: ".$notification;
			$noteCrimeSQL="INSERT INTO suspicious (userId, suspicion) VALUES(?,?)";
			$values1[0]=array($userIdentity =>'s');
			$values1[1]=array($notification => 's');
			$result=$conn->insert($noteCrimeSQL,$values1);
			if($conn->error==""&&$result==true)
			{
				return true;
			}
			else
			{
				return false;
			}

		}

		function mailContent($emailId,$content,$subject,$attachments="")
		{
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->IsHTML();
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;                   // set the SMTP port

			$mail->Username   = "coe12b013@iiitdm.ac.in";  // GMAIL username
			$mail->Password   = "";            // GMAIL password

			$mail->From       = "4pi-IIIT D&M Kancheepuram";
			$mail->FromName   = "Admin @ 4pi-IIIT D&M Kancheepuram";
			$mail->Subject    = $subject;
			$mail->WordWrap   = 500; // set word wrap
			$mail->AddAddress($emailId);
			$mailBody="<center><strong>--This is an Automated Email.Don't Waste Your TIME Replying to this email address!!--</strong></center><br/>";
			$mailBody=$mailBody."<strong>Subject:</strong>".$subject."<br/>";
			$mailBody=$mailBody."<strong>Content:</strong><br/>".$content."<br/><br/>";
			$mailBody=$mailBody."Have A Nice Time<br/>Regards,<br/><strong>4pi-Admin</strong><br/>";

			$mail->Body       = $mailBody;
			if($attachments!="")
			{
				$splitAttachments=explode(",",$attachments);
				$attachmentCount=count($splitAttachments);
				for($i=0;$i<$attachmentCount;$i++)
					{
						$mail->AddAttachment($splitAttachments[$i]);
					}
			}
			//$mail->isSMTP();
			
			if(!$mail->send())
			{
				notifyAdmin($mail->ErrorInfo."!!!! PostSubject: ".$subject,$userId);
				return false;
			}
			else
			{
				$mail->ClearAddresses();
				return true;
			}

		}

		function getCommentByPostIdAndHash($postId,$commentIdHash)
		{
			$conn = new QoB();
			$commentTable="p".$postId."c";
			$GetCommentSQL="SELECT * FROM ".$commentTable." WHERE commentIdHash= ?";
			// $values[]=array("commentTable" => 's');
			// $values[]=array($commentTable => 's');
			$values[0]=array($commentIdHash => 's');
			$result=$conn->fetchAll($GetCommentSQL,$values,false);
			if($conn->error==""&&$result!="")
			{
				return $result;
			}
			else
			{
				return false;
			}
		}

		function updatePostIndexesOnComment($postArray,$userId,$conn)
		{
			$postIdHash=$postArray['postIdHash'];
			$date = date_create();
			$followers=$postArray['followers'];
			if(stripos($followers,$userId)===false)
			{
				if($followers=="")
				{
					$followers=$userId;
				}
				else
				{
					$followers=$followers.",".$userId;
				}
			}

			$commentCount=$postArray['commentCount'];
			$commentCount=$commentCount+1;
							
			$commentIndexUpdated = ($postArray['commentIndex'] + date_timestamp_get($date))/2;
							
			$popularityIndexUpdated = $postArray['likeIndex']+ 1.4 * $commentIndexUpdated;
							
			$values = array(0 => array($commentIndexUpdated => 'i'), 1 => array($popularityIndexUpdated => 'i'), 2 => array($commentCount => 'i'), 3 => array($followers => 's'),4 => array($postIdHash => 's'));
							
			$result = $conn->update("UPDATE post SET commentIndex = ?, popularityIndex = ?,commentCount = ?,followers = ? WHERE postIdHash = ? ",$values);
			if($conn->error == ""&&$result==true)
			{
				//echo 'Updated successfully<br />';
				return true;
			}
			else
			{
				/*echo 'Error in Query 2 of Mode 2<br />';
				echo $conObj->error.'<br />';*/
				return false;
			}
		}

		function changeToRawSharedWith($sharedWith)
		{
			$sharedWithRegex="([\.]+)";
			$rawSharedWith=preg_replace($sharedWithRegex, '', $sharedWith);
			if($rawSharedWith=="")
			{
				return "EVERYONE";
			}
			return $rawSharedWith;
		}
		function changeToEventDateFormat($date)
		{
			$dateRegex="([/]+)";
			$eventDate=preg_replace($dateRegex, '', $date);
			return $eventDate;
		}
		function changeToEventTimeFormat($time)
		{
			$timeRegex="([:]+)";
			$eventTime=preg_replace($timeRegex, '', $time);
			return $eventTime;
		}
		function changeToRawDateFormat($eventDate)
		{
			$nDate=str_split($eventDate,2);
			$rawDate=implode("/",$nDate);
			return $rawDate;
		}

		function changeToRawTimeFormat($eventTime)
		{
			$nTime=explode('/',$eventTime,3);
			$rawTime=implode(":",$nTime);
			return $rawTime;
		}

		function isSharedTo($userId,$sharedWith)
		{
			//$nSharedWith=explode(",",$sharedWith);
			$finalRegex=getRollNoRegex($userId);
			if(preg_match('/'.$finalRegex.'/',$sharedWith))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function isThere($haystack,$needle)
		{
			if(stripos($haystack, $needle)===false)
			{
				return -1;
			}
			else
			{
				return 1;
			}
		}

		function validateDate($rawDate)
		{
			$ndate=explode('/',$rawDate);
			//var_dump($ndate);
			if($ndate[2]<2007||$ndate[2]>2030)
			{
				return false;
			}
			if($ndate[2]%4==0)
			{
				$daysArray=[31,29,31,30,31, 30,31,31,30,31, 30,31];
				if($ndate[1]>=1&&$ndate[1]<=12)
				{
					if($ndate[0]>=0&&$ndate[0]<=$daysArray[$ndate[1]])
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				$daysArray=[31,28,31,30,31, 30,31,31,30,31, 30,31];
				if($ndate[1]>=1&&$ndate[1]<=12)
				{
					if($ndate[0]>=0&&$ndate[0]<=$daysArray[$ndate[1]])
					{
						return true;
					}
					else
					{
						return false;
					}
				}
				else
				{
					return false;
				}
			}
		}

	function getRollNoRegex($rollno)
	{
		$stud['year']=substr($rollno, 3,2);
		$stud['branch']=substr($rollno,0,3);
		$stud['degree']=substr($rollno, 5,1);
		$stud['branchYear']=substr($rollno,0,5);
		$stud['branchDegree']=$stud['branch'].$stud['degree'];
		$stud['yearDegree']=$stud['year'].$stud['degree'];
		$stud['branchYearDegree']=$stud['branch'].$stud['yearDegree'];
		$regexString="(".$stud['year']."|".$stud['branch']."|".$stud['degree']."|".$stud['branchYear']."|".$stud['branchDegree']."|".$stud['yearDegree']."|".$stud['branchYearDegree'].")";
		$finalRegexString="(,".$regexString.",?)|(^".$regexString.",?)|(^All$)";
		return $finalRegexString;
	}

	function validateSharedWith($str)
	{
		$regstr;
		$conn=new QOB();
		
		if(strlen($str)==1)
		{
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$storeString="^.....".$str."...$";
			$values[0]=array('_____'.$str.'___'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString;
					return $regstr;
				}
				else
				{
					return "Invalid";
				}
			}	
		}
		else if(strlen($str)==2){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$storeString="^...".$str."....$";
			$values[0]=array('___'.$str.'____'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString;
					return $regstr;
				}
				else
				{
					return "Invalid";
				}
			}			
		}
		else if(strlen($str)==3){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array('___'.$str.'___'=>'s');
			$storeString1='^...'.$str.'...$';
			$values1[0]=array($str.'______'=>'s');
			$storeString2='^'.$str.'......$';
			$result=$conn->fetchALL($sql,$values,true);
			//
			if($conn->error!=''){
				notifyAdmin("Conn.Error".$conn->error."In validate Shared With1",$userId);
				//echo $conn->error;
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString1;
					return $regstr;
				}
				else
				{
					$result2=$conn->fetchALL($sql,$values1,true);
					if($conn->error!=""){
						notifyAdmin("Conn.Error".$conn->error."In validate Shared With2",$userId);
						//echo $conn->error;
						return "Invalid";
					}
					else{
						if($result2>=1){
							//echo "Accepted";
							$regstr=$storeString2;
							return $regstr;
						}
						else
						{
							//echo "here";
							return "Invalid";
						}
					}
				}
			}		
		}
		else if(strlen($str)==4){
			$divide=str_split($str);
			$searchString=$divide[0].$divide[1].$divide[2]."__".$divide[3];
			$storeString='^'.$divide[0].$divide[1].$divide[2]."..".$divide[3]."...$";
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($searchString.'___'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString;
					return $regstr;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else if(strlen($str)==5){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($str.'____'=>'s');
			$storeString="^".$str."....$";
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString;
					return $regstr;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else if(strlen($str)==6){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($str.'___'=>'s');
			$storeString="^".$str."...$";
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					$regstr=$storeString;
					return $regstr;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else{
			return "Invalid";
		}
	}//END OF validateSharedWith Function!!!!!!
	
	function newValidateSharedWith($str)
	{
		$conn=new QOB();
		
		if(strlen($str)==1)
		{
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array('_____'.$str.'___'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					return "Invalid";
				}
			}	
		}
		else if(strlen($str)==2){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array('___'.$str.'____'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					return "Invalid";
				}
			}			
		}
		else if(strlen($str)==3){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array('___'.$str.'___'=>'s');
			$values1[0]=array($str.'______'=>'s');
			$storeString2='^'.$str.'......$';
			$result=$conn->fetchALL($sql,$values,true);
			//
			if($conn->error!=''){
				notifyAdmin("Conn.Error".$conn->error."In validate Shared With1",$userId);
				//echo $conn->error;
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					$result2=$conn->fetchALL($sql,$values1,true);
					if($conn->error!=""){
						notifyAdmin("Conn.Error".$conn->error."In validate Shared With2",$userId);
						//echo $conn->error;
						return "Invalid";
					}
					else{
						if($result2>=1){
							//echo "Accepted";
							return $str;
						}
						else
						{
							//echo "here";
							return "Invalid";
						}
					}
				}
			}		
		}
		else if(strlen($str)==4){
			$divide=str_split($str);
			$searchString=$divide[0].$divide[1].$divide[2]."__".$divide[3];
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($searchString.'___'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else if(strlen($str)==5){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($str.'____'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else if(strlen($str)==6){
			$sql="SELECT * FROM users WHERE userId LIKE ?";
			$values[0]=array($str.'___'=>'s');
			$result=$conn->fetchALL($sql,$values,true);
			if($conn->error!=''){
				return "Invalid";
			}
			else{
				if($result>=1){
					//echo "Accepted";
					return $str;
				}
				else
				{
					return "Invalid";
				}
			}		
		}
		else{
			return "Invalid";
		}
	}












	/*function notifyPeople($toBeNotified,$message)
	{
		$list=explode(',', $toBeNotified);
		foreach () $userId 
	}*/
//---------Examples and Test Code Executed On Online Compiler Starts------------------------------ 


/*$regex="^.{3}$";
$word="boys";
if(ereg($regex,$word))
{
	echo 1;
}
else
{
	echo 2;
}	

$regex="([0-9\^\$\{\}\.]+)";
$word="^.{3}COE.{3}$";
$word2=ereg_replace($regex,'',$word);
echo $word2;*/

//$arr2 = str_split($str, 3);		

/*
    $a1 = array("1","2","3");
    $a2 = array("a");
    $a3 = array();
   
    echo "a1 is: '".implode("','",$a1)."'<br>";
    echo "a2 is: '".implode("','",$a2)."'<br>";
    echo "a3 is: '".implode("','",$a3)."'<br>";


will produce:
===========
a1 is: '1','2','3'
a2 is: 'a'
a3 is: ''*/

/*$time="21-10-21";
$timeRegex="([-]+)";
$eventTime=ereg_replace($timeRegex,'',$time);
echo $eventTime;
$nTime=str_split($eventTime,2);
print_r($nTime);
$rawTime=implode("-",$nTime);
echo $rawTime;

<?php
$str="COE12B013";
//echo $str[2];
$str=str_split($str);
$year=array_slice($str,3,2);
var_dump($year);
?>*/
?>
