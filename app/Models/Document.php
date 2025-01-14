<?php
// app/Models/Document.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'case_id', 'file_path', 'original_name', 'name', 'additional_notes'];

    // Belongs to a document category
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class);
    }
     // Belongs to a case
     public function case()
     {
         return $this->belongsTo(Cases::class); // Assuming you have a Case model
     }
}
