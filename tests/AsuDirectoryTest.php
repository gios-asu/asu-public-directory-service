<?php

class AsuDirectoryTest extends PHPUnit_Framework_TestCase{

  public function testNullCaseInAttributeAccessors() {
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( null ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( null ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( null ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( null ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( null ) );
  }

  public function testInvalidUserDirectoryQueries() {
    //$directoryInfo = Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'nonexistentuser' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'nonexistentuser' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Asu_Research\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testStaffDirectoryQueries() {
    //$directoryInfo = Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'ndrollin' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'staff' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Rollins',                Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan',                 Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan.Rollins@asu.edu', Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan Rollins',         Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Asu_Research\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testFacultyDirectoryQueries() {
    //$directoryInfo = Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'majansse' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'faculty' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Janssen',                Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marcus',                 Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marco.Janssen@asu.edu', Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marco Janssen',         Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Asu_Research\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testStudentDirectoryQueries() {
    //$directoryInfo = Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'mhtyson' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'student' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Tyson',                Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline',                 Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline.Tyson@asu.edu', Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline Tyson',         Asu_Research\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertTrue( Asu_Research\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }


  /**
   * TODO: create proper test double for ASUDirectory::get_directory_info_by_asurite()
   */
  protected function getMockDirectoryResponse( $responseType ) {
    switch ( $responseType ) {
      case 'student':
        return json_decode( file_get_contents(__DIR__.'/mocks/validDirectoryStudent.json'), true );
        break;

      case 'faculty':
        return json_decode( file_get_contents(__DIR__.'/mocks/validDirectoryFaculty.json'), true );
        break;

      case 'staff':
        return json_decode( file_get_contents(__DIR__.'/mocks/validDirectoryStaff.json'), true );
        break;

      case 'nonexistentuser':
        return json_decode( file_get_contents(__DIR__.'/mocks/invalidDirectoryUser.json'), true );
    }
  }
}
