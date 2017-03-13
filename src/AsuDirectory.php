<?php namespace Gios_Asu\ASUPublicDirectoryService;

/**
 *  AsuDirectory
 *
 *  This is a static class for interacting with the ASU iSearch service.
 *  To display an ASU iSearch profile, you need their EID (Employee ID?):
 *  https://isearch.asu.edu/profile/{EID}
 *
 *  Profile data can be retrieved as XML and JSON:
 *  to find out about a person given an asurite:
 *  https://asudir-solr.asu.edu/asudir/directory/select?q=asuriteId:{ASURITE}&wt=json
 *  https://asudir-solr.asu.edu/asudir/directory/select?q=asuriteId:{ASURITE}&wt=xml
 *
 *  Thus, in order to access a user's iSearch profile page when you have their ASURITE,
 *  such as provided by the ASU CAS service, their EID must be retrieved using the XML or JSON service.
 *
 *  @author Nathan D. Rollins
 */
class ASUDirectory {

  /**
   * Get user's iSearch record using their ASURITE id
   *
   * @param String $asurite
   * @return Array
   */
  static public function get_directory_info_by_asurite($asurite) {
    if($asurite == NULL || strlen($asurite) < 3 || strlen($asurite) > 12) { return NULL; }
    $asurite = urlencode(strtolower($asurite));
    $xml = file_get_contents("https://webapp4.asu.edu/directory/ws/search?asuriteId=".$asurite);
    if(empty($xml)) return NULL; // could get nothing back from the server
    $feed = new SimpleXMLElement($xml);
    if(empty($feed)) return NULL; // could get an empty result set from the server
    return $feed;
  }

  /**
   * Get user's full, display name from iSearch array
   *
   * @param  Array $info
   * @return String
   */
  static public function  get_display_name_from_directory_info($xml) {
    if(isset($xml->person) && isset($xml->person->displayName)) {
      return strval($xml->person->displayName);
    }
    return "";
  }

  /**
   * Get user's last name from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function  get_last_name_from_directory_info($xml) {
    if(isset($xml->person) && isset($xml->person->lastName)) {
      return strval($xml->person->lastName);
    }
    return "";
  }

  /**
   * Get user's first name from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function  get_first_name_from_directory_info($xml) {
    if(isset($xml->person) && isset($xml->person->firstName)) {
      return strval($xml->person->firstName);
    }
    return "";
  }

  /**
   * Get user's email address from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function  get_email_from_directory_info($xml) {
    if(isset($xml->person) && isset($xml->person->email)) {
      return strval($xml->person->email);
    }
    return "";
  }


  /**
   * Return T/F whether a user is listed in iSearch as majoring in a degree program from the School of Sustainability
   *
   * @param  Array   $info
   * @return String
   */
  static public function  has_SOS_plan_from_directory_info($xml) {
    if(isset($xml->person) && isset($xml->person->plans)) {
      foreach($xml->person->plans->plan as $plan) {
        // print_r($plan->plan);
        if(isset($plan) && is_array($plan)) {
          // echo "more than one plan! ";
          // print_r($plan);
          // they have more than one plan:
          foreach($plan as $sub_plan) {
            if(isset($sub_plan->acadPlanDescr) && stristr($sub_plan->acadPlanDescr, "Sustainability")) {
              return TRUE;
            }
          }
        } else if(isset($plan->acadPlanDescr) && stristr($plan->acadPlanDescr, "Sustainability")) {
          return TRUE;
        } else {
          // echo "there is a plan but its not sustainability, its ".$plan->acadPlanDescr."\n";
        }
      }
    }
    // echo "NO PLANS\n";
    return FALSE;
  }

}
