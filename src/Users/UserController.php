<?php

namespace App\Users;

use App\Model\Query;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class UserController
 * @package App\Users\Controllers
 */
class UserController
{
    /**
     * @return mixed
     */
    public function index()
    {
        return (new Query())->findAll('users');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = $this->userService->store($request);
        return  UserResource::collection($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function show(User $user)
    {
        return  UserResource::collection($user);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => ['sometimes', 'required', 'string', 'min:8', 'confirmed']
        ]);

        $user = $this->userService->update($user, $request);
        return  UserResource::collection($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function destroy(User $user)
    {
        $this->userService->destroy($user);
        return json_encode(['status' => 200, 'message' => 'Register deleted']);
    }
}