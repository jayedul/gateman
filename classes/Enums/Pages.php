<?php

namespace DevJK\Gateman\Enums;

enum Pages:string {

	case LOGIN            = 'gateman_login';
	case REGISTRATION     = 'gateman_registration';
	case RECOVER_PASSWORD = 'gateman_recover_password';
	case RESET_PASSWORD   = 'gateman_reset_password';
}
