<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogKeyword;
use App\Models\BlogSection;
use App\Models\BlogSubSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogsController extends Controller
{
    // Route::post('addBlogs',[BlogsController::class,'addBlogs']);

    // Route::put('updateBlogs/{service_id}',[BlogsController::class,'updateBlogs']);
   
    // Route::delete('deleteBlogs/{service_id}',[BlogsController::class,'deleteBlogs']);
   
    // Route::get('getAllBlogs',[BlogsController::class,'getAllBlogs']);
   
    /*
        Route::post('addBlogKeyword/{blog_id}',[BlogsController::class,'addBlogKeyword']);

        Route::put('updateBlogKeyword/{keyword_id}',[BlogsController::class,'updateBlogs']);

        Route::delete('deleteBlogs/{keyword_id}',[BlogsController::class,'deleteBlogs']);

        Route::get('getAllBlogs',[BlogsController::class,'getAllBlogs']);

    */
   
    public function addBlogs(Request $request){
        if($request->file('image')){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
            ]);
            // Get the file path after storage
            $path = $request->file('image')->store('public/images');
        
            // Determine the base URL based on the environment
            if (app()->isLocal()) {
                // For local development
                $baseUrl = url('/');
            } else {
                // For production or any other environment
                $baseUrl = config('app.url');
            }
        
            // Concatenate the base URL with the file path
            $url = $baseUrl . Storage::url($path);
            //$url = request()->url() . Storage::url($path); // This line is incorrect, use the $baseUrl variable instead
            $base_url_replace = str_replace('/storage', '/storage/app/public', $url); 
        }else{
            $path = "";
            $base_url_replace = "";
        }
        Blog::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'sub_title_en' => $request->sub_title_en,
            'sub_title_ar' => $request->sub_title_ar,
            'category_en' => $request->category_en,
            'category_ar' => $request->category_ar,
            'image_path' => $path,
            'image_url' => $base_url_replace 
        ]);
        return response()->json([
            'message' => 'Blog added successfully',
            'image_url' => $base_url_replace
        ]);
    }

    public function updateBlogs($blog_id , Request $request){
        $blog = Blog::find($blog_id);
        if($request->file('image')){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
            ]);
            Storage::delete($blog->image_path);
            // Get the file path after storage
            $path = $request->file('image')->store('public/images');
        
            // Determine the base URL based on the environment
            if (app()->isLocal()) {
                // For local development
                $baseUrl = url('/');
            } else {
                // For production or any other environment
                $baseUrl = config('app.url');
            }
        
            // Concatenate the base URL with the file path
            $url = $baseUrl . Storage::url($path);
            //$url = request()->url() . Storage::url($path); // This line is incorrect, use the $baseUrl variable instead
            $base_url_replace = str_replace('/storage', '/storage/app/public', $url); 
        }else{
            $path = $blog->image_path;
            $base_url_replace = $blog->image_url;
        }
        $blog->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'sub_title_en' => $request->sub_title_en,
            'sub_title_ar' => $request->sub_title_ar,
            'category_en' => $request->category_en,
            'category_ar' => $request->category_ar,
            'image_path' => $path,
            'image_url' => $base_url_replace 
        ]);
        return response()->json([
            'message' => 'Blog updated successfully',
            'image_url' => $base_url_replace
        ]);

    }

    public function deleteBlogs($blog_id){
        $blog = Blog::find($blog_id);
        Storage::delete($blog->image_path);
        $blog->delete();
        return response()->json([
            'message' => 'Blog deleted successfully',
        ]);
    }

    public function getAllBlogs(){
        //$blogs = Blog::all();
        return response()->json([
            'blogs' => Blog::all()
        ]);
    }


    public function addBlogKeyword($blog_id , Request $request){
        // $keywords_en = $request->keyword_en;
        // $keywords_ar = $request->keyword_ar;
        // foreach($keywords_en as $key => $value){
        //     BlogKeyword::create([
        //     'keyword_en' => $keywords_en[$key]->keyword_en,
        //     'keyword_ar' => $keywords_ar[$key]->keyword_ar,
        //     'blog_id' => $blog_id
        //     ]);
        // }
         BlogKeyword::create([
            
            'keyword_en' => json_encode($request->keyword_en),
            'keyword_ar' => json_encode($request->keyword_ar),
            'blog_id' => $blog_id
        ]);

        return response()->json([
            'message' => 'Keywords added successfully'
        ]);

    }
   public function updateBlogKeyword($blogId , Request $request){
        BlogKeyword::where('blog_id',$blogId)->delete();
        $blog_id = $blogId;
        // $keywords_en = $request->keyword_en;
        // $keywords_ar = $request->keyword_ar;
        // foreach($keywords_en as $key => $value){
        //     BlogKeyword::create([
        //     'keyword_en' => $keywords_en[$key]->keyword_en,
        //     'keyword_ar' => $keywords_ar[$key]->keyword_ar,
        //     'blog_id' => $blog_id
        //     ]);
        // }
        // return response()->json([
        //     'message' => 'Keywords updated successfully'
        // ]);
        BlogKeyword::create([
            
            'keyword_en' => json_encode($request->keyword_en),
            'keyword_ar' => json_encode($request->keyword_ar),
            'blog_id' => $blog_id
        ]);
    
        return response()->json([
            'message' => 'Keywords added successfully'
        ]);
    }
    public function deleteBlogKeyword($blogId){
        $blogKeywords = BlogKeyword::where('blog_id',$blogId)->delete();
        return response()->json([
            'message' => 'Keywords updated successfully'
        ]);
    }

//   public function getAllBlogKeywordsRelatedToBlog($blogId)
// {
//     // Retrieve the blog keywords associated with the specified blog ID
//     $blogKeywords = BlogKeyword::where('blog_id', $blogId)->get();

//     // Loop through each keyword and decode keyword_en and keyword_ar attributes
//     foreach ($blogKeywords as $keyword) {
//         $keyword->keyword_en = json_decode($keyword->keyword_en, true);
//         $keyword->keyword_ar = json_decode($keyword->keyword_ar, true);
//     }

//     // Return the response with the decoded keyword_en and keyword_ar attributes
//     return response()->json([
//         'blogKeywords' => $blogKeywords
//     ]);
// }

    public function getAllBlogKeywordsRelatedToBlog($blogId)
{
    // // Retrieve the blog keywords associated with the specified blog ID
    // $blogKeywords = BlogKeyword::where('blog_id', $blogId)->get();

    // // Return the response with the decoded keyword_en and keyword_ar attributes
    // return response()->json([
    //     'blogKeywords' => $blogKeywords
    // ]);
     $blogKeywords = BlogKeyword::where('blog_id' , $blogId)->get();
        // $formattedData = $blogKeywords->map(function ($blogKeyword) {
        //     return [
                
        //         'keyword_en' => json_decode($blogKeyword->keyword_en),
        //         'keyword_ar' => json_decode($blogKeyword->keyword_ar),
        //     ];
        // });

        // return response()->json([
        //     'blogKeywords' => $blogKeywords
        // ]);
        return response()->json([
            'blogKeywords' => $blogKeywords
        ]);
}
    
    public function addBlogSection(Request $request , $blogId){
        if($request->file('image')){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
            ]);
            // Get the file path after storage
            $path = $request->file('image')->store('public/images');
        
            // Determine the base URL based on the environment
            if (app()->isLocal()) {
                // For local development
                $baseUrl = url('/');
            } else {
                // For production or any other environment
                $baseUrl = config('app.url');
            }
        
            // Concatenate the base URL with the file path
            $url = $baseUrl . Storage::url($path);
            //$url = request()->url() . Storage::url($path); // This line is incorrect, use the $baseUrl variable instead
            $base_url_replace = str_replace('/storage', '/storage/app/public', $url); 
        }else{
            $path = "";
            $base_url_replace = "";
        }

        BlogSection::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'text_en' => $request->text_en,
            'text_ar' => $request->text_ar,
            'image_path' => $path,
            'image_url' => $base_url_replace,
            'blog_id' => $blogId
        ]);

        return response()->json([
            'message' => 'BlogSection added successfully'
        ]);
    }

    public function updateBlogSection(Request $request , $blogsect_id){
        $blogSection = BlogSection::find($blogsect_id);
        if($request->file('image')){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust max file size as necessary
            ]);
            // Get the file path after storage
            $path = $request->file('image')->store('public/images');
        
            // Determine the base URL based on the environment
            if (app()->isLocal()) {
                // For local development
                $baseUrl = url('/');
            } else {
                // For production or any other environment
                $baseUrl = config('app.url');
            }
        
            // Concatenate the base URL with the file path
            $url = $baseUrl . Storage::url($path);
            //$url = request()->url() . Storage::url($path); // This line is incorrect, use the $baseUrl variable instead
            $base_url_replace = str_replace('/storage', '/storage/app/public', $url); 
        }else{
            $path = $blogSection->image_path;
            $base_url_replace = $blogSection->image_url;
        }

        BlogSection::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'text_en' => $request->text_en,
            'text_ar' => $request->text_ar,
            'image_path' => $path,
            'image_url' => $base_url_replace,
            'blog_id' => $blogSection->blog_id
        ]);

        return response()->json([
            'message' => 'BlogSection updated successfully'
        ]);
    }

    public function deleteBlogSection($blogsect_id){
        $blogSection = BlogSection::find($blogsect_id);
        $blogSection->delete();

        return response()->json([
            'message' => 'BlogSection deleted successfully'
        ]);
    }

    public function getAllBlogSectionsRelatedToBlog($blogId){

        return response()->json([
            'blogSection' => BlogSection::where('blog_id',$blogId)->get()
        ]);
    }
    
    
    
    
    
    public function addBlogSubSection($section_id , Request $request){
        BlogSubSection::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'list_en' => json_encode($request->list_en), // Make sure $request->list_en is an array
            'list_ar' => json_encode($request->list_ar), // Make sure $request->list_ar is an array
            'section_id' => $section_id
        ]);
    
        return response()->json([
            'message' => 'blogSubSection added successfully'
         
        ]);
    }


    public function getAllBlogSubSectionsRelatedToSection($section_id){
        $blogSubSections = BlogSubSection::where('section_id' , $section_id)->get();
        // $formattedData = $blogSubSections->map(function ($blogSubSection) {
        //     return [
        //         'id' => $blogSubSection->id,
        //         'title_en' => $blogSubSection->title_en,
        //         'title_ar' => $blogSubSection->title_ar,
        //         'list_en' => json_decode($blogSubSection->list_en, true), // Decode JSON into array
        //         'list_ar' => json_decode($blogSubSection->list_ar, true), // Decode JSON into array
        //         'section_id' => $blogSubSection->section_id
        //     ];
        // });
    
        return response()->json([
            'blogSubSection' => $blogSubSections
        ]);
    }


    public function deleteBlogSubSection($blogsubsection_id){
        $blogSubSection = BlogSubSection::find($blogsubsection_id);
        $blogSubSection->delete();
        return response()->json([
            'message' => 'BlogSubSection deleted successfully'
        ]);
    }

    public function updateBlogSubSection($blogsubsection_id , Request $request){
        $blogSubSection = BlogSubSection::find($blogsubsection_id);
       
        $blogSubSection->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'list_en' => json_encode($request->list_en),
            'list_ar' => json_encode($request->list_ar),
            'section_id' => $blogSubSection->section_id
        ]);

        return response()->json([
            'message' => 'blogSubSection updated successfully'
        ]);
       
    }
    
    public function getBlogData($blogId) {
    $blog = Blog::with('keywords', 'sections.subsections')->find($blogId);
    
    return response()->json([
        'blog' => $blog
    ]);
    }
    
    
    
    
    
    
    
    
    
    
}
