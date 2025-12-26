<?php

namespace App\Enum\Core\Web;

/**
 * Enum defining named routes for use across the application.
 * Using enums helps ensure consistency and avoids hardcoded strings.
 */
enum RoutesNames: string
{
    // Public & Guest Routes
    case INDEX = 'index';                         // Landing or homepage route
    case LOG_OUT = 'logout';                      // Logout route
    case IS_ON_MAINTENANCE_MODE = 'maintenanceMode'; // Check if the site is in maintenance
    case SITE_PARAMETERS = 'siteParameters';      // View or update general site parameters
    case SET_LANG = 'setLang';                    // Route for changing language
    case SERVICE_DETAILS_PUBLIC = 'servicePublic';
    case SERVICES_PUBLIC = "servicesPublic";

        // Authentication Routes
    case LOGIN = 'login';                         // Login page
    case REGISTER = 'register';                   // Registration page
    case FORGET_PASSWORD = 'forgetPassword';      // Forgot password route
    case DASHBOARD = 'dashboard';                  // Dashboard

        // User Routes
    case USER_ROUTE = 'home';                     // Default user dashboard/home
    case PROFILE = 'profile';                     // User profile page
    case CHANGE_PASSWORD = 'changePassword';      // Route to change user password
    case CHANGE_EMAIL = 'changeEmail';            // Route to change user email
    case MEDICAL_FILE_ROUTE = 'medicalFile';            // Route to change user email
    case TOGGLE_ACCOUNT_STATUS = 'ToggleActiveState';

        // Super Admin Routes
    case SUPER_ADMIN_ROUTE = 'superAdminSpace';   // Super admin dashboard

    case BANKS = 'banks';                         // Bank management
    case OCCUPATION_FIELDS = 'occupationFields';
    case WILAYATES = 'wilayates';
    case WILAYA = 'WILAYA';


    case MESSAGES = 'messages';                   // View or manage contact messages
    case GENERAL_INFOS = 'generalInfos';          // General settings and info
    case MANAGE_HERO = 'heroScene';               // Hero section management
    case MANAGE_ABOUT_US = 'aboutUsScene';        // About Us section
    case MANAGE_OUR_QUALITIES = "ourQualities";   // Our Qualities management


        // Admin Routes
    case PERSONS_ROUTE = 'personsRoutes';              // Admin dashboard
    case USERS_ROUTE = 'usersPage';            // Landing page management
    case ESTABLISHMENT_ROUTE = 'establishmentDetails';
    case MENUS_ROUTE = "menus";                   // All menus listing
    case MENU_ROUTE = "menu";                     // Single menu view/edit
    case SERVICES_ROUTE = 'services';


        // Author Routes
    case SERVICE_ROUTE = "service";
    case ARTICLES_ROUTE = "articles";             // Articles management
    case TRENDS_ROUTE = "trends";                 // Trends management
    case SLIDERS_ROUTE = "sliders";               // Sliders management
    case SLIDER_ROUTE = "slides";                 // Slides management

        //social Admin Routes
    case  SOCIAL_WORKS_ROUTE = "socialWorks";
    case  ESTABLISHMENT_BANKING_INFO_ROUTE = "establishmentsBankingInfo";
    case  BONUSES_ROUTE = "bonuses";
    case  GLOBAL_TRANSFERS_ROUTE = "globalTransfers";
    case  GLOBAL_TRANSFERS_DETAILS_ROUTE = "globalTransfersDetails";
}
