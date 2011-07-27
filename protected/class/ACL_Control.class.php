<?php
/*
	Access control list editor
		+-	add/remove permissions
		-	add/remove groups
		-	group inheritance	
		+	bind users with groups
		-	bind user/group with permission
	Marenin Alex
	July 2011
	
	*** Note
		Someday i'll decompose this class into ACL_User, ACL_Group and ACL_Permission
*/
class ACL_Control {
	
	protected $driver;
	
	public function __construct( $driver_name = 'ACL_MySQL_Driver' ){
		$this->driver = new $driver_name;
	}
	
	
	// PERMISSIONS section
	
	// add new permission
	// throws ACL_Exception
	public function add_permission( $name, $description ){
		$name = mb_trim( $name );
		if ( mb_ereg_match( '$[a-z0-9_]{1,50}^', $name ))
			return $this->driver->add_permission( $name, $description );
		else 
			throw new ACL_Exception( 'ACL_Control::add_permission : Incorrect permission name. Permission name must contain only small latin characters, digits and "_" symbol' );
	}
	
	
	// USER/GROUP BIND section
	
	// Add user to group
	public function bind_user_group( $user_id, $group ){
		$group_id = $this->driver->get_group_by_name( $group );
		if ( $group_id === false )
			throw new ACL_Exception("ACL_Control::bind_user_group : Unknown group \"$group\"");
		return $this->driver->bind_user_group( $user_id, $group_id );
	}
	
	
	// Remove user from group
	public function unbind_user_group( $user_id, $group ){
		$group_id = $this->driver->get_group_by_name( $group );
		return $this->driver->unbind_user_group( $user_id, $group_id );
	}
}

?>