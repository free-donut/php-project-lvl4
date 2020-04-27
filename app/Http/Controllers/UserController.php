<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
        if ($user->id === Auth::id()) {
            return view('user.show', compact('user'));
        }
        flash(__('messages.denied'))->error();
        return redirect()->route('main');
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
            flash(__('messages.not_logged'))->error();
            return redirect()->route('main');
        }
        if ($id !== Auth::id()) {
            flash(__('messages.denied'))->error();
            return redirect()->route('main');
        }
        $genders = ['male', 'female', 'neutral'];
        $user = User::findOrFail($id);
        return view('user.edit', compact('user', 'genders'));
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
        $user = User::findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            flash(__('Invalid password.'))->warning();
            return redirect()->route('users.edit', $user);
        }

        $data = $this->validate($request, [
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|max:256|email',
            'gender' => 'required|string',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string|size:12',
        ]);
        $user->fill($data);
        $user->save();
        flash(__('messages.updated', ['name' => 'account']))->success();
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
        if ($id === Auth::id()) {
            $user = User::findOrFail($id);
            $user->delete();
            flash(__('messages.deleled', ['name' => 'account']))->success();
        } else {
            flash(__('messages.denied'))->error();
        }
        return redirect()->route('main');
    }
}
