<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Imports\ImportUser;
use Illuminate\Support\Str;

use Flash;

class UserController extends AppBaseController
{
    /** @var UserRepository $userRepository*/
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     */
    public function index(Request $request)
    {
        
        $query = $request->input('query');
        $users = $this->userRepository->paginate($query);
       
        if ($request->ajax()) {
            return view('users.table')
                ->with('users', $users);
        }

        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);

        Flash::success(__('messages.saved', ['model' => __('models/users.singular')]));

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(__('models/users.singular').' '.__('messages.not_found'));

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(__('models/users.singular').' '.__('messages.not_found'));

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     */
    public function update($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(__('models/users.singular').' '.__('messages.not_found'));
            return redirect(route('users.index'));
        }
    
        $userData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
    
        $user = $this->userRepository->update($userData, $id);
    
        Flash::success(__('messages.updated', ['model' => __('models/users.singular')]));
        return redirect(route('users.index'));
    }
    

    /**
     * Remove the specified User from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            Flash::error(__('models/users.singular').' '.__('messages.not_found'));

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success(__('messages.deleted', ['model' => __('models/users.singular')]));

        return redirect(route('users.index'));
    }

    public function export()
    {
        return Excel::download(new ExportUser, 'Users.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new ImportUser, $request->file('file')->store('files'));
        return redirect()->back();
    }

    public function InitialiserMtp($id){

        $password = Str::random(9);

        $passwordHash = Hash::make($password);

        $userFound = $this->userRepository->find($id);

        if($userFound){

            $userFound->update([
                'password' => $passwordHash
            ]);

            Flash::success("Le mot de pass a ete initialisé avec succès :".$password);

            return back();

        }

    }


}
