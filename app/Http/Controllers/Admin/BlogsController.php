<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Blogs;
use App\Models\BlogTags;
use App\Models\Sliders;
use App\Models\Categorias;
use App\Models\Productos;
use App\Models\ProductosImagenes;
use Auth;
use Illuminate\Support\Str;
use View;


class BlogsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $blogs = Blogs::get();
        return view('admin.blogs.home', compact('blogs'));
    }

    public function add()
    {
        return view('admin.blogs.add');
    }


    public function store(Request $request)
    {

        $imagen = 'default.jpg';

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/blogs/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;

        }

        $request->contenido = $this->generaImagenes($request->contenido);

        $blog = Blogs::create([
        'blog_category_id'=>'1',
        'titulo'=>$request->titulo,
        'contenido'=>$request->contenido,
        'idioma'=>$request->idioma,
        'slug'=>Str::slug($request->titulo),
        'imagen'=>$imagen,
        'estatus'=>1,
        'user_id'=>Auth::getUser()->id
       ]);

       $tags = json_decode($request->tags);

       foreach($tags as $tag ){

            BlogTags::create([
                'titulo'=>$tag->value,
                'slug'=>Str::slug($tag->value),
                'blog_id'=>$blog->id
            ]);
       }

      return redirect('admin/blogs');
        
    }

    public function detail($id)
    {
        $item = Blogs::where('id', $id)->first();

        return view('admin.blogs.detail', compact('item'));
    }



    public function edit($id)
    {
        $item = Blogs::where('id', $id)->first();

        $tags = BlogTags::select('titulo')->where('blog_id', $item->id)->get();

        $tagString = '';

        $i=0;

        foreach($tags as $tag ){

            if($i==0){
                $tagString=$tagString.$tag->titulo;
            }else{
                $tagString=$tagString.','.$tag->titulo;
            }
            
            $i++;
        }

        $tags = $tagString;

        return view('admin.blogs.edit', compact('item', 'tags'));
    }


    public function update(Request $request)
    {

        $blog = Blogs::where('id', $request->id)->first();

        $imagen = $blog->imagen;

        if ($request->hasFile('myfile')) {
  
            $file = $request->file('myfile');
            $extension = $file->extension()?: 'png';
            $picture = Str::random(10) . '.' . $extension;
            $destinationPath = public_path('/uploads/blogs/');
            $file->move($destinationPath, $picture);
            $imagen = $picture;
        }

        $request->contenido = $this->generaImagenes($request->contenido);

       $blog->update([
        'titulo'=>$request->titulo,
        'contenido'=>$request->contenido,
        'idioma'=>$request->idioma,
        'imagen'=>$imagen,
        'estatus'=>$request->estatus,
        'user_id'=>Auth::getUser()->id
       ]);

       $tags = json_decode($request->tags);

       BlogTags::where('blog_id', $blog->id)->delete();

       if(!is_array($tags)){
        $tags = explode(',', $tags);
       }

       foreach($tags as $tag ){

            BlogTags::create([
                'titulo'=>$tag->value??$tag,
                'slug'=>Str::slug($tag->value??$tag),
                'blog_id'=>$blog->id
            ]);
       }




      return redirect('admin/blogs');
        
    }

    public function delete($id)
    {

        $blog = Blogs::where('id', $id)->delete();

        return redirect('admin/blogs');
        
    }


}
