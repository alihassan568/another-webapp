<?php

namespace App\Modules\User\Enums;

enum PermissionCategory: string
{
    case CAN_MANAGE_DASHBOARD = 'can manage dashboard';
    case CAN_MANAGE_COMPANY = 'can manage company';
    case CAN_UPLOAD_DATA = 'can upload data';
    case CAN_MANAGE_MAPPING = 'can manage mapping';
    case CAN_MANAGE_METADATA = 'can manage metadata';
    case CAN_EXPORT_DATA = 'can export data';
    case CAN_MANAGE_CUSTOMS_DECLARATION = 'can manage customs declaration';
    case CAN_MANAGE_INVOICE = 'can manage invoice';
    case CAN_MANAGE_MASTER_DATA = 'can manage master data';
    case CAN_MANAGE_CBAM_REPORT = 'can manage cbam report';
    case CAN_MANAGE_TASK = 'can manage task';
    case CAN_MANAGE_ROLE = 'can manage role';
    case CAN_MANAGE_PACKAGE = 'can manage package';
    case CAN_MANAGE_BLOG = 'can manage blog';
    case CAN_MANAGE_USER = 'can manage user';
    case CAN_MANAGE_TRANSACTION = 'can manage transaction';
    case CAN_MANAGE_NOTE = 'can manage note';
    case CAN_MANAGE_ABOUT_US = 'can manage about us';
    case CAN_MANAGE_COMMENT = 'can manage comment';
    case CAN_MANAGE_CONTACT = 'can manage contact';
    case CAN_MANAGE_REVIEW = 'can manage review';
    case CAN_MANAGE_QUESTION = 'can manage question';
    case CAN_MANAGE_COMMENT_REPLY = 'can manage comment reply';
    case CAN_MANAGE_CONTACT_REPLY = 'can manage contact reply';
    case CAN_MANAGE_REVIEW_REPLY = 'can manage review reply';
    case CAN_MANAGE_SUBSCRIPTION = 'can manage subscription';
    case CAN_MANAGE_COMMISSION = 'can manage commission';
    case CAN_MANAGE_ITEMS = 'can manage items';

}
