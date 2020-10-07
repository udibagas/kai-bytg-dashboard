<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = User::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('email', 'LIKE', "%{$request->keyword}%");
            });
        })->orderBy('name', 'asc')->get();

        return new UserCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $user
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $user
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return ['message' => 'Data telah dipahus'];
    }
}
