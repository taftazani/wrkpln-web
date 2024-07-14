<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Todo\TodoService;

class TodoController extends Controller
{
    use ApiResponse;
    public function __construct(private TodoService $service)
    {
    }

    public function getTodo()
    {
        $result = $this->service->get();
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getTodoUser(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $result = $this->service->getUser($filter);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function getTodoUserId(Request $request)
    {
        $result = $this->service->getUserId($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
    public function makeTodo(Request $request)
    {
        $result = $this->service->make($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateTodo(Request $request)
    {
        $result = $this->service->update($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateTodoReject(Request $request)
    {
        $result = $this->service->updateReject($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function updateTodoApprove(Request $request)
    {
        $result = $this->service->updateApprove($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function makeTodoAttachment(Request $request)
    {
        $result = $this->service->makeAttachment($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function removeTodoAttachment(Request $request)
    {
        $result = $this->service->removeAttachment($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }

    public function deleteTodo(Request $request)
    {
        $result = $this->service->delete($request);
        return $this->response($result['data'], $result['message'], $result['status']);
    }
}
