<?php


include_once('BadgeosShowSub_LifeCycle.php');

class BadgeosShowSub_Plugin extends BadgeosShowSub_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }


    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }


    public function getPluginDisplayName() {
        return 'BadgeOS Show Submission Add-on';
    }


    protected function getMainPluginFileName() {
        return 'badgeos-show-submission-add-on.php';
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));
		add_filter('badgeos_render_achievement', array($this, 'addLinkToSubmission'), 10, 2);
		add_filter('the_content', array($this, 'removeBadgeThumbnail'), 11);
		add_filter('badgeos_get_submission_attachments', array($this, 'swapWordAttachmentProof'), 11);
    }


    public function removeBadgeThumbnail($content) {
    	$postType = get_post_type();

    	if($postType == 'badges') {
	    	$content = preg_replace('#<div class="alignleft badgeos-item-image">(.*?)</div>#', '', $content);
    	}

    	return $content;
    }


    public function swapWordAttachmentProof($content) {

    	$content = str_replace('Submitted Attachments:', 'Submitted evidence:', $content);
    	$content = str_replace('Pièces-Jointes Soumises :', ' Preuves soumises:', $content);

    	return $content;
    }


    public function addLinkToSubmission($output, $achivementID) {
		$displayedID = bp_displayed_user_id();

		if(empty($displayedID)) {
			return $output;
		}

		/*
		 * Now we take care of removing the thumbnail as requested by client
		 */
		$doc = new DOMDocument();
		#self::setErrorHandler();
		$doc->loadHTML (mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
		#self::setErrorHandler(TRUE);
		$xpath = new \DOMXPath ( $doc );

		/*
		 * Du parent div ID = badgeos-achievements-container,
		 * les enfants qui ont la classe badgeos-item-image
		 * From http://stackoverflow.com/questions/11686287/php-xml-removing-element-and-all-children-according-to-node-value
		*/
		$q = '//div[@class="badgeos-item-image"]';
		$thumbnailDiv = $xpath->query($q);
		$parentNode = $thumbnailDiv->item(0)->parentNode;
		$parentNode->removeChild($thumbnailDiv->item(0));

		$q = '//div[@class="badgeos-item-description"]';
		$descDiv = $xpath->query($q)->item(0);
		$descDiv->setAttribute("style", "width:100%");

		$q = '//h2[@class="badgeos-item-title"]';
		$descDiv = $xpath->query($q)->item(0);
		$descDiv->setAttribute("style", "font-size:140%;margin-bottom:0.5cm");

		$strReturn = $doc->saveHTML();
		#$strReturn = $output;

		/*
		 * According to badgeos_parse_feedback_args
		 * 'status'=> 'auto-approved' shows both approved & auto-approved
		 */
		$args = array(
				'author'			=> $displayedID,
				'achievement_id'	=> $achivementID,
				'post_type'    		=> 'submission',
				'show_attachments'	=> true,
				'show_comments'		=> true,
				'status'			=> 'auto-approved',
				'numberposts'		=> 1,
				'suppress_filters'	=> false,
		);

		$args = badgeos_parse_feedback_args($args);


		$wpq 	= new WP_Query;
		$posts	= $wpq->query($args);
		$submissionID = $posts[0]->ID;

		if(empty($submissionID)) {
			return $strReturn;
		}

		$linkURL = get_permalink($submissionID, false);

		$divToAdd = "<!-- BEGIN Show detail link for ach # $achivementID, author # $displayedID, submission postID # $submissionID --><div>" . '<a href="' . $linkURL . '">' . translate('Show Details', 'badgeos') .'</a></div><!-- END Show detail link -->';

		$htmlCommentMarker = '<!-- .badgeos-item-excerpt -->';
		$strReturn = str_replace($htmlCommentMarker, $htmlCommentMarker . $divToAdd, $strReturn);


		return $strReturn;
    }

}
