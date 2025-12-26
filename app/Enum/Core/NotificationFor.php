<?php

namespace App\Enum\Core;

/**
 * Enum defining named routes for use across the application.
 * Using enums helps ensure consistency and avoids hardcoded strings.
 */
enum NotificationFor: string
{
  case USER   = 'user';
    case ADMIN  = 'admin';
    case DOCTOR = 'doctor';


}
