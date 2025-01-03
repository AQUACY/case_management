<?php

// app/Models/DocumentCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // One-to-many relationship with documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
