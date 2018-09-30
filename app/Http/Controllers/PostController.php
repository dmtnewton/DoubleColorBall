<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo '<pre>' . PHP_EOL;
        print_r('posts->index');
        echo '</pre>' . PHP_EOL;

        return view('posts', ['user' => 'NEWTON']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo '<pre>' . PHP_EOL;
        print_r('post->create');
        echo '</pre>' . PHP_EOL;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo 'post->store<pre>' . PHP_EOL;
        print_r($request->input());
        echo '</pre>' . PHP_EOL;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo 'show-id:<pre>' . PHP_EOL;
        print_r($id);
        echo '</pre>' . PHP_EOL;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo 'edit-id:<pre>' . PHP_EOL;
        print_r($id);
        echo '</pre>' . PHP_EOL;
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
        echo 'update-input:<pre>' . PHP_EOL;
        print_r($request->input());
        echo '</pre>' . PHP_EOL;

        echo 'update-id:<pre>' . PHP_EOL;
        print_r($id);
        echo '</pre>' . PHP_EOL;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo 'destroy:<pre>' . PHP_EOL;
        print_r($id);
        echo '</pre>' . PHP_EOL;
    }
}
