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
    if ( $asurite == NULL || strlen( $asurite ) < 3 || strlen( $asurite ) > 12 ) {
      return NULL;
    }
    $asurite = urlencode( $asurite );
    $json = file_get_contents( "https://asudir-solr.asu.edu/asudir/directory/select?q=asuriteId:" . $asurite . "&wt=json" );
    if ( empty( $json ) ) {
      return NULL;
    }
    $info = json_decode ( $json, true );
    if ( 0 == $info['response']['numFound'] ) {
      return NULL;
    }
    return $info;
  }

  /**
   * Get user's EID from iSearch array
   *
   * @param  Array   $info
   * @return Integer
   */
  static public function getEid( $info ) {
    if ( isset( $info['response']['docs'][0]['eid'] ) ) {
      return intval( $info['response']['docs'][0]['eid'] );
    }
    return "";
  }

  /**
   * Get user's ASURITE from iSearch array
   *
   * A bit redundant, since we currently only retrieve the iSearch info using the ASURITE as key,
   * but alternate key options may be useful later, making this method useful.
   *
   * @param  Array   $info
   * @return String
   */
  static public function getAsurite( $info ) {
    if ( isset( $info['response']['docs'][0]['asuriteId'] ) ) {
      return strval( $info['response']['docs'][0]['asuriteId'] );
    }
    return "";
  }

  /**
   * Get user's full, display name from iSearch array
   *
   * @param  Array $info
   * @return String
   */
  static public function get_display_name_from_directory_info($info) {
    if ( isset( $info['response']['docs'][0]['displayName'] ) ) {
      return strval( $info['response']['docs'][0]['displayName'] );
    }
    return "";
  }

  /**
   * Get user's last name from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function get_last_name_from_directory_info($info) {
    if ( isset( $info['response']['docs'][0]['lastName'] ) ) {
      return strval( $info['response']['docs'][0]['lastName'] );
    }
    return "";
  }

  /**
   * Get user's first name from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function get_first_name_from_directory_info($info) {
    if ( isset( $info['response']['docs'][0]['firstName'] ) ) {
      return strval( $info['response']['docs'][0]['firstName'] );
    }
    return "";
  }

  /**
   * Get user's email address from iSearch array
   *
   * @param  Array   $info
   * @return String
   */
  static public function get_email_from_directory_info($info) {
    if ( isset( $info['response']['docs'][0]['emailAddress'] ) ) {
      return strval( $info['response']['docs'][0]['emailAddress'] );
    }
    return "";
  }

  /**
   * Return T/F whether a user is listed as a student in iSearch
   *
   * @param  Array   $info
   * @return String
   */
  static public function isStudent( $info ) {
    if ( isset( $info['response']['docs'][0]['affiliations'] ) ) {
      foreach ( $info['response']['docs'][0]['affiliations'] as $affiliation ) {
        if ( 'Student' == $affiliation ) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Return T/F whether a user is listed as faculty in iSearch
   *
   * @param  Array   $info
   * @return String
   */
  static public function isFaculty( $info ) {
    if ( isset( $info['response']['docs'][0]['affiliations'] ) ) {
      foreach ( $info['response']['docs'][0]['affiliations'] as $affiliation ) {
        if ( 'Employee' == $affiliation ) {
          foreach ( $info['response']['docs'][0]['emplClasses'] as $employee_class ) {
            if ( 'Faculty' == $employee_class ) {
              return TRUE;
            }
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * Return T/F whether a user is listed as staff in iSearch
   *
   * @param  Array   $info
   * @return String
   */
  static public function isStaff( $info ) {
    if ( isset( $info['response']['docs'][0]['affiliations'] ) ) {
      foreach ( $info['response']['docs'][0]['affiliations'] as $affiliation ) {
        if ( 'Employee' == $affiliation ) {
          foreach ( $info['response']['docs'][0]['emplClasses'] as $employee_class ) {
            if ( 'University Staff' == $employee_class ) {
              return TRUE;
            }
          }
        }
      }
    }
    return FALSE;
  }

  /**
   * Return T/F whether a user is listed in iSearch as majoring in a degree program from the School of Sustainability
   *
   * @param  Array   $info
   * @return String
   */
  static public function has_SOS_plan_from_directory_info($info) {
    if ( $info['response']['numFound'] > 0 ) {
      if ( $info['response']['docs'][0]['programs'] ) {
        foreach ( $info['response']['docs'][0]['programs'] as $program ) {
          // look for SOS program
          if ( 'School of Sustainability' == $program ) {
            foreach ( $info['response']['docs'][0]['majors'] as $major ) {
              // is student majoring in Sustainability
              if ( 'Sustainability' == $major ) {
                return TRUE;
              }
            }
          }
        }
      }
    }

    return FALSE;
  }

}
