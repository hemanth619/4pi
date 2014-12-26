<?php
session_start();	
require_once('../../QOB/qob.php');
require_once('./miniPoll.php');
require_once('../fetch.php');
//Testing Content Starts
	/*$userIdHash=$_SESSION['vj']=hash("sha512","COE12B021".SALT);
	$_SESSION['tn']=hash("sha512",$userIdHash.SALT2);
	$_POST['_pollId']="";
	$_POST['_pollQuestion']="Some Other Event";
	$_POST['_pollType']="SomeOther Peru kuda!";
	$_POST['_pollOptions']="Some Place";
	$_POST['_pollOptionType']="Some type";
	$_POST['_sharedWith']="EDS,EVD,EDM11";*/
//Testing Content Ends


/*
Code 3: SUCCESS!!
Code 13: SECURITY ALERT!! SUSPICIOUS BEHAVIOUR!!
Code 12: Database ERROR!!
code 14: Suspicious Behaviour and Blocked!
Code 16: Erroneous Entry By USER!!
Code 11: Session Variables unset!!
*/

if(!(isset($_SESSION['vj'])&&isset($_SESSION['tn'])))
{
	echo 11;
	exit();
}


//Actual editPoll Code Starts
$pollIdHash=$_POST['_pollId'];
$pollQuestion=$_POST['_pollQuestion'];
$pollType=$_POST['_pollType'];
$pollOptionsArray=$_POST['_pollOptions'];
$pollOptionsType=$_POST['_pollOptionType'];
$sharedWith=$_POST['_sharedWith'];

if($pollQuestion==""||gettype($pollType)!="integer"||count($pollOptionsArray)<=1)
{
	echo 16;
	exit();
}
if($pollType!=1&&$pollType!=2&&$pollType!=3)
{
	echo 16;
	exit();
}
if($pollOptionsType!=1&&$pollOptionsType!=2)
{
	echo 16;
	exit();
}
if($sharedWith=="")
{
	echo 16;
	exit();
}
$userIdHash=$_SESSION['vj'];
if(hash("sha512",$userIdHash.SALT2)!=$_SESSION['tn'])
{
	if(blockUserByHash($userIdHash,"Suspicious Session Variable in editPoll")>0)//Happy Birthday to Myself!! Its October 21st!! 00:00 hrs
	{
		$_SESSION=array();
		session_destroy();
		echo 14;
		exit();
	}
	else
	{
		notifyAdmin("Suspicious Session Variable in editPoll",$userIdHash.",sh:".$_SESSION['tn']);
		$_SESSION=array();
		session_destroy();
		echo 13;
		exit();
	}
}
else
{
	if(($user=getUserFromHash($userIdHash))==false)
	{
		notifyAdmin("Critical Error In editPoll",$userIdHash);
		$_SESSION=array();
		session_destroy();
		echo 13;
		exit();
	}
	else
	{


		$userId=$user['userId'];
		$userName=$user['name'];
		if(($poll=getPollFromHash($pollIdHash))==false)
		{
			if(blockUserByHash($userIdHash,"Tampering pollIdHash in editPoll",$userId.",sh:".$eventIdHash)>0)
			{
				$_SESSION=array();
				session_destroy();
				echo 14;
				exit();
			}
			else
			{
				notifyAdmin("Suspicious pollIdHash in editpoll",$userId.",sh:".$eventIdHash);
				$_SESSION=array();
				session_destroy();
				echo 13;
				exit();
			}
		}
		if($poll['approvalStatus']==1)
		{
			if(blockUserByHash($userIdHash,"Attempt To Edit anapproved poll",$userId.",sh:".$eventIdHash)>0)
			{
				$_SESSION=array();
				session_destroy();
				echo 14;
				exit();
			}
			else
			{
				notifyAdmin("Attempt to edit an approved poll",$userId.",sh:".$eventIdHash);
				$_SESSION=array();
				session_destroy();
				echo 13;
				exit();
			}
		}

		$pollUserId=$poll['userId'];
		if($pollUserId!=$userId)
		{
			if(blockUserByHash($userIdHash,"Illegal Attempt to Edit Poll",$userId.",sh:".$eventIdHash)>0)
			{
				$_SESSION=array();
				session_destroy();
				echo 14;
				exit();
			}
			else
			{
				notifyAdmin("Illegal Attempt to Edit Poll",$userId.",sh:".$eventIdHash);
				$_SESSION=array();
				session_destroy();
				echo 13;
				exit();
			}
		}

		$splitSharedWith=explode(",", $rawSharedWith);

		$n=count($splitSharedWith);
		$sharedWith="";
		if(stripos($rawSharedWith,"All")===false)
		{
			if($rawSharedWith!=",")
			{
				for($i=0;$i<$n;$i++)
				{
					if($splitSharedWith[$i]!="")
					{
						//echo $i.",".$splitSharedWith[$i]."<br/>";
						$out=newValidateSharedWith($splitSharedWith[$i]);
						if($out=="Invalid")
						{
							echo 16;
							exit();
						}
						else
						{
							//echo $out;
							if($sharedWith=="")
							{
								$sharedWith=$out;
							}
							else
							{
								$sharedWith=$sharedWith.",".$out;
							}
						}
					}
				}//2
			}
			else
			{
				echo 16;
				exit();
			}	
		}
		else
		{
			$sharedWith="All";
		}
		$pollOptions=implode(',',$pollOptionsArray);
		$pollOptionsCount=count(pollOptionsArray);
		for($i=0;$i<count($pollOptionsArray);$i++)
			$optionVotesArray[]=0;
		$optionVotes=implode(',',$optionVotesArray);
		$editPollSQL="UPDATE poll SET pollType = ?,question = ? ,options = ?,optionsType =? ,optionCount =?,sharedWith = ?,optionVotes=? 
						WHERE pollIdHash= ?";

		$values[0]=array($pollType => 's');
		$values[1]=array($pollQuestion => 's');
		$values[2]=array($pollOptions => 's');
		$values[3]=array($pollOptionsType => 's');
		$values[4]=array($pollOptionsCount => 's');
		$values[5]=array($sharedWith => 's');
		$values[6]=array($userId => 's');
		$values[7]=array($optionVotes => 's');
		$values[8]=array($pollIdHash => 's');


		$result=$conn->insert($editPollSQL,$values);
		if($conn->error==""&&$result==true)
		{
			//Success			
			$ts = new DateTime();
			$ts->setTimestamp($timestamp);
			$pollCreationTime=$ts->format(DateTime::ISO8601);
			$pollStatus=0;
			$hasVoted=1;
			
			$pollObj=new miniPoll($pollIdHash,$userName,$pollQuestion,$pollType,$pollOptionsArray,$pollOptionsType,
					$hasVoted,$optionVotesArray,$pollCreationTime,$pollStatus);
			print_r(json_encode($pollObj));
		}
		else
		{
			notifyAdmin("Conn.Error".$conn->error."! While inserting in edit Poll",$userId);
			echo 12;
			exit();
		}
	}
}
?>