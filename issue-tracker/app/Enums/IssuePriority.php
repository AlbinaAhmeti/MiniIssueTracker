<?php
namespace App\Enums;

enum IssuePriority:string {
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}
