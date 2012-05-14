<?php

class GalleriaImage extends DataObject
{
	static $db = array (
		'Alt' => 'Varchar(255)',
		'Caption' => 'Varchar(255)',
	);

	static $has_one = array (
		'ImageFile' => 'Image', // related action needed for DataObject/File
		'Page' => 'GalleriaPage' // relation needed to point back to the page (owner)
	);

	function getCMSFields_forPopup() {
		return new FieldSet(
			new TextField('Caption','Caption'),
			new TextField('Alt','Image Alt-Attribute (specifies an alternate text for the image)'),
			new FileIFrameField('ImageFile', 'Image File')
		);
	}

	public function getThumbSize() {
		return $this->ImageFile()->SetRatioSize(40,40);
	}

	public function getWebSize() {
		return $this->ImageFile()->SetRatioSize(935,700);
	}

	public function getFullSize() {
		return $this->ImageFile();
	}

}