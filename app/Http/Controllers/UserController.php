<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(5);
        return view('user.index', compact('users'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }
        if ($id !== Auth::id()) {
            flash(__('Permission denied.'))->error();
            return redirect()->route('main');
        }

        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
            return redirect()->route('main');
        }

        $user = User::findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            flash(__('Invalid password.'))->warning();
            return redirect()->route('users.edit', $user);
        }

        $data = $this->validate($request, [
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|max:256|email',
        ]);
        $user->fill($data);
        $user->save();
        flash(__('Your account has been updated.'))->success();
        return redirect()->route('main');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (!Auth::check()) {
            flash(__('Please log in or register.'))->error();
        } elseif ($id === Auth::id()) {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                flash(__('Your account has been deleted.'))->success();
                //flash('Your account has been deleted!')->success();
            } else {
                flash(__('User doesn\'t exist!'))->error();
            }
        } else {
            flash(__('Permission denied!'))->error();
        }
        return redirect()->route('main');
    }
}
