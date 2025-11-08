<?php

namespace DevJK\SLR\Enums;

enum Pages:string {

	case LOGIN            = 'slr_login';
	case REGISTRATION     = 'slr_registration';
	case RECOVER_PASSWORD = 'slr_recover_password';
	case RESET_PASSWORD   = 'slr_reset_password';
}
