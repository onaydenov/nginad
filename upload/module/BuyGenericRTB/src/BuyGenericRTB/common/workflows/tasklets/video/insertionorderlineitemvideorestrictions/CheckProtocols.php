<?php
/**
 * NGINAD Project
 *
 * @link http://www.nginad.com
 * @copyright Copyright (c) 2013-2016 NginAd Foundation. All Rights Reserved
 * @license GPLv3
 */

namespace buyrtb\workflows\tasklets\video\insertionorderlineitemvideorestrictions;

class CheckProtocols {
	
	public static function execute(&$Logger, &$Workflow, \model\openrtb\RtbBidRequest &$RtbBidRequest, \model\openrtb\RtbBidRequestImp &$RtbBidRequestImp, &$InsertionOrderLineItem, &$InsertionOrderLineItemVideoRestrictions) {
		
		$RtbBidRequestVideo = $RtbBidRequestImp->RtbBidRequestVideo;

		$result = true;
		
		if (empty($InsertionOrderLineItemVideoRestrictions->ProtocolsCommaSeparated)):
			return $result;
		endif;

		$protocols_code_list = explode(',', $InsertionOrderLineItemVideoRestrictions->ProtocolsCommaSeparated);
		
		if (!count($protocols_code_list)):
			return $result;
		endif;
		
		// Validate that the value is an array
		if (!is_array($RtbBidRequestVideo->protocols)):
			if ($Logger->setting_log === true):
			$Logger->log[] = "Param Not Required: No Values to Match: " . "Check video protocols code :: EXPECTED: "
					. 'Array(),'
					. " GOT: " . $RtbBidRequestVideo->protocols;
			endif;
			return $result;
		endif;

		/*
		 * All codes in the publisher ad zone
		* for the publisher's video player settings
		* have to be included in the VAST video demand
		*/
		foreach($RtbBidRequestVideo->protocols as $protocols_code_to_match):
			
			if (!in_array($protocols_code_to_match, $protocols_code_list)):
			
				$result = false;
				break;
			
			endif;
		
		endforeach;
		
		if ($result === false && $Logger->setting_log === true):
			$Logger->log[] = "Failed: " . "Check video protocols code :: EXPECTED: "
				. $InsertionOrderLineItemVideoRestrictions->ProtocolsCommaSeparated
				. " GOT: " . join(',', $RtbBidRequestVideo->protocols);
		endif;
		
		return $result;
	}
}
