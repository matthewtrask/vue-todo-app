<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    /** @var Response */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index() : Response
    {
        $todos = Todo::uncompleted()->get();

        return $this->response->setStatusCode(200)->setContent($todos);
    }

    public function create(TodoRequest $request) : Response
    {
        $todo = new Todo();

        $todo->todo = $request->getTodo();
        $todo->completed = false;
        $todo->notes = $request->getNotes();

        $todo->save();

        return $this->response->setStatusCode(201);
    }

    public function edit(TodoRequest $request) : Response
    {
        $todo = Todo::find($request->id);

        $todo->todo = $request->getTodo();
        $todo->notes = $request->getNotes();

        $todo->save();

        return $this->response->setContent(202);
    }

    public function markComplete(Request $request) : Response
    {
        $todo = Todo::find($request->id);

        $todo->completed = 1;

        $todo->save();

        return $this->response->setStatusCode(201);
    }

    public function destroy(Request $request) : Response
    {
        Todo::destroy($request->id);

        return $this->response->setStatusCode(204);
    }
}
