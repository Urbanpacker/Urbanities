<?php

class Member extends Manager
{

	public function updateExistingMember($memberData)
	{
		$db = self::dbConnect();
		$req = $db->prepare(
			'UPDATE Members
			SET
				memberName = :name,
				memberFirstname = :firstname,
				memberPseudo = :pseudo,
				memberEmail = :email,
				memberPostcode = :postcode,
				memberPassword = :password,
				fk_countryId = :countryId
			WHERE
				memberId = :memberId
		');
		$req->execute($memberData);
		$req->closeCursor();
		return $memberData['memberId']; 
	}

	public function recordNewMember($memberData)
	{
		$db = self::dbConnect();
		$req = $db->prepare(
			'INSERT INTO Members(
				memberName,
				memberFirstname,
				memberPseudo,
				memberEmail,
				memberPostcode,
				memberPassword,
				fk_countryId,
				memberCreationTimestamp
			)
			VALUES(
				:name,
				:firstname,
				:pseudo,
				:email,
				:postcode,
				:password,
				:countryId,
				NOW()
			)'
		);
		$req->execute($memberData);
		$req->closeCursor();
		
		return $db->lastInsertId();		
	}

	function getMember(array $data){
		$db = self::dbConnect();

		$data['id'] = $data['id'] ? $data['id'] : '';
		$data['email'] = $data['email'] ? $data['email'] : '';

		$memberData = $db->prepare(
			'SELECT
				memberId,
				memberIsAdmin,
				memberName,
				memberFirstname,
				memberPseudo,
				memberEmail,
				memberPostcode,
				DATE_FORMAT(
					memberCreationTimestamp,
					"%d/%m/%Y à %Hh%i"
				) AS memberCreationDate,
				DATE_FORMAT(
					memberLastConnection,
					"%d/%m/%Y à %Hh%i"
				) AS memberLastConnectionDate,
				Country.countryName AS countryName,
				Country.countryId AS countryId
			FROM
				Members
			INNER JOIN
				Country ON Members.fk_countryId = Country.countryId
			WHERE
				memberId = :memberId
			OR 	
				memberEmail = :memberEmail
			');
		$memberData->bindValue(':memberId', $data['id'], PDO::PARAM_STR);	
		$memberData->bindValue(':memberEmail', $data['email'], PDO::PARAM_STR);	
		$memberData->execute();
		
		return $memberData->fetch();

	}


	function memberConnection($memberEmail)
	{
		$db = self::dbConnect();
		$memberData = $db->prepare(
			'SELECT
				memberId,
				memberIsAdmin,
				memberName,
				memberFirstname,
				memberPseudo,
				memberEmail,
				memberPostcode,
				memberPassword,
				DATE_FORMAT(
					memberCreationTimestamp,
					"%d/%m/%Y à %Hh%i"
				) AS memberCreationDate,
				DATE_FORMAT(
					memberLastConnection,
					"%d/%m/%Y à %Hh%i"
				) AS memberLastConnectionDate,
				Country.countryName AS countryName,
				Country.countryId AS countryId
			FROM
				Members
			INNER JOIN
				Country ON Members.fk_countryId = Country.countryId
			WHERE
				memberEmail = ?
			');
		$memberData->execute([$memberEmail]);
		
		return $memberData->fetch();
	}

	function recordConnectionTimestamp($memberId){
		$db = self::dbConnect();
		$timestampConnection = $db->prepare(
			'UPDATE
				Members
			SET
				memberLastConnection = NOW()
			WHERE
				memberId = ?
			'
		);
		$timestampConnection->execute([$memberId]);
		$timestampConnection->closeCursor();
	}
}