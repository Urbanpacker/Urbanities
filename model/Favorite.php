<?php

class Favorite extends Manager
{
	public function addToFav($spotId, $memberId)
	{	
		$db = self::dbConnect();
		$req = $db->prepare(
			'INSERT INTO Favorites(
				fk_spotId,
				fk_memberId
			)
			VALUES(
				?,
				?
			)'
		);
		$affectedLines = $req->execute([$spotId, $memberId]);
		$req->closeCursor();
		return $affectedLines;
	}

	public function removeFromFav($spotId, $memberId)
	{	
		$db = self::dbConnect();
		$req = $db->prepare(
			'DELETE FROM
				Favorites
			WHERE 
				fk_spotId = ?
			AND
				fk_memberId = ?
			');
		$affectedLines = $req->execute([$spotId, $memberId]);
		$req->closeCursor();
		return $affectedLines;
	}

	static public function getFavorites($memberId)
	{
		$db = self::dbConnect();
		$req = $db->prepare('
			SELECT
				spotId,
				spotName,
				spotPostcode,
				spotImage,
				spotAdress,
				catName
			FROM
				Spots
			INNER JOIN Favorites
			ON
				Favorites.fk_spotId = Spots.spotId
			INNER JOIN Members
			ON
				Favorites.fk_memberId = Members.memberId
			INNER JOIN Categories
			ON
				Categories.catId = Spots.fk_catId
			WHERE
				Favorites.fk_memberId = ?
		');
		
		$req->execute([$memberId]);
		return $req->fetchAll();
	}

	public function checkIfFavoriteByAnyone($spotId)
	{
		$db = self::dbConnect();
		$req = $db->prepare('
			SELECT
				favoriteId,
				fk_memberId AS memberId,
				fk_spotId AS spotId
			FROM
				Favorites
			WHERE
				Favorites.fk_spotId = ?
		');
		
		$req->execute([$spotId]);
		return $req->fetch();
	}

	public function checkIfFavoriteExists($spotId, $memberId)
	{
		$db = self::dbConnect();
		$req = $db->prepare('
			SELECT
				favoriteId
			FROM
				Favorites
			WHERE
				Favorites.fk_spotId = ?
			AND
				Favorites.fk_memberId = ?
		');
		
		$req->execute([$spotId, $memberId]);
		return $req->fetch();
	}


}	