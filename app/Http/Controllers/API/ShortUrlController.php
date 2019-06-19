<?php

namespace App\Http\Controllers\API;

use App\Urls;
use App\UrlsAliases;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShortUrlController extends Controller
{
    function get_title($url){
        $str = file_get_contents($url);
        if(strlen($str)>0){
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
            return $title[1];
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top100 = Urls::orderBy('visit_count', 'desc')
            ->with('alias')
            ->limit(100)
            ->get();

        return $top100;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = Validator::make(
            $request->all(),
            [
                'url' => 'required|string',
            ]
        );

        if ($rules->fails()) {
            return response()->json([
                "status" => "error",
                "msg" => $rules->errors(),
            ], 404);
        }

        $url_path = $request->get('url');

        $title = $this->get_title($url_path);


        $url= new Urls();
        $url->fill($request->all($url->getFillable()));
        $url->title = $title;

        $url->save();

        $alias = base_convert(crc32($url->id), 10, 36);

        $url_alias = new UrlsAliases();
        $url_alias->url_id = $url->id;
        $url_alias->alias = $alias;
        $url_alias->shortened_url = url('/'.$alias);

        $url_alias->save();

        return response()->json([
            "status" => "success",
            "msg" => 'URL successfully shortened.',
            "generated_url" => $url_alias->shortened_url
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
