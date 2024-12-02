<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContactRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Services\UserContactService;
use Illuminate\Http\Request;

class UserContactController extends Controller
{
    public function __construct(protected UserContactService $userContactService) {}

    public function show()
    {
        return $this->userContactService->showMyContacts();
    }

    public function create(ContactRequest $request)
    {
        $data = $request->validated();
        return $this->userContactService->createContact($data);

    }

    public function update(int $id, UpdateRequest $request)
    {
        $data = $request->validated();
        return $this->userContactService->updatedContact($id, $data);
    }

    public function delete(int $id)
    {
        return $this->userContactService->deleteContact($id);
    }
}
