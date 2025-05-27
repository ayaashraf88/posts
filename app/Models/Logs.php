<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'activity_log'; // If using Spatie's table
    // OR
    // protected $table = 'custom_logs_table'; // Your custom table
}