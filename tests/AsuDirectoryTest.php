<?php

class AsuDirectoryTest extends PHPUnit_Framework_TestCase{

  public function testNullCaseInAttributeAccessors() {
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( null ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( null ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( null ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( null ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( null ) );
  }

  public function testInvalidUserDirectoryQueries() {
    //$directoryInfo = Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'nonexistentuser' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'nonexistentuser' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEmpty( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testStaffDirectoryQueries() {
    //$directoryInfo = Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'ndrollin' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'staff' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Rollins',                Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan',                 Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan.Rollins@asu.edu', Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Nathan Rollins',         Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testFacultyDirectoryQueries() {
    //$directoryInfo = Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'majansse' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'faculty' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Janssen',                Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marcus',                 Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marco.Janssen@asu.edu', Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Marco Janssen',         Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertFalse( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
  }

  public function testStudentDirectoryQueries() {
    //$directoryInfo = Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_directory_info_by_asurite( 'mhtyson' );
    //print_r( $directoryInfo );

    $directoryInfo = $this->getMockDirectoryResponse( 'student' );

    $this->assertInternalType( 'array', $directoryInfo );

    $this->assertEquals( 'Tyson',                Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_last_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline',                 Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_first_name_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline.Tyson@asu.edu', Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_email_from_directory_info( $directoryInfo ) );
    $this->assertEquals( 'Madeline Tyson',         Gios_Asu\AsuPublicDirectoryService\ASUDirectory::get_display_name_from_directory_info( $directoryInfo ) );

    $this->assertTrue( Gios_Asu\AsuPublicDirectoryService\ASUDirectory::has_SOS_plan_from_directory_info( $directoryInfo ) );
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
