<?php

class GalleriaPage extends Page {

	static $db = array (
	);

	static $has_one = array (
	);

	static $has_many = array (
		'GalleriaImages' => 'GalleriaImage'
	);

	static $defaults = array (
	);

	public function getCMSFields($cms)
	{
		$fields = parent::getCMSFields($cms);

		$manager = new ImageDataObjectManager(
			$this,
			'GalleriaImages',
			'GalleriaImage',
			'ImageFile',
			array(
				'Thumbnail' => 'Thumbnail',
				'Alt' => 'Alt',
				'Caption' => 'Caption'
			),
			'getCMSFields_forPopup'
		);
		$manager->setUploadFolder(ASSETS_DIR . "/" . $this->URLSegment);
		$fields->addFieldToTab('Root.Content.Images',$manager);
		return $fields;
	}

}

class GalleriaPage_Controller extends Page_Controller {

	public static $allowed_actions = array(
		'images',
	 );

	function init() {
		parent::init();
		Requirements::css('galleria/thirdparty/galleria/themes/classic/galleria.classic.css');
		Requirements::customCSS('
			#galleria {
				height:698px
			}
			.galleria-container {
			background-color: #F0F0F0;
			border: 1px solid #E0E0E0
			}
		');
		Requirements::javascript('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js');
		Requirements::javascript('galleria/thirdparty/galleria/galleria-1.2.7.min.js');
		Requirements::javascript('galleria/thirdparty/galleria/themes/classic/galleria.classic.min.js');
		Requirements::customScript('
			var data = [ ' . $this->getImageJson() . ' ];
			Galleria.loadTheme(\'galleria/thirdparty/galleria/themes/classic/galleria.classic.min.js\');
			Galleria.run(\'#galleria\', {
				dataSource: data,
				autoplay: 4500
			});
		');
	}

	public function getImageJson() {
		$json = false;
		if($images = $this->GalleriaImages()) {
		foreach ($images as $image) {
			$json.="{
			thumb:'{$image->getThumbSize()->URL}',
			image:'{$image->getWebSize()->URL}',
			big:'{$image->getFullSize()->URL}',
			title:'{$image->Alt}',
			description:'{$image->Caption}'
			},\n";
		}
		return $json;
		}
	}

}
