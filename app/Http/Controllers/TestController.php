<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestCheckRequest;
use App\Http\Requests\TestStoreRequest;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\TestResource;
use App\Models\Question;
use App\Models\Response;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TestController extends Controller
{
    public function show(Test $test)
    {
        return response(TestResource::make($test));
    }

    public function index()
    {
        return response(TestResource::collection(Auth::user()->tests));
    }

    public function store(TestStoreRequest $request)
    {
        $test = Test::create(array_merge(
            $request->only(['title', 'description']),
            [
                'uuid' => Str::uuid(),
                'user_id' => Auth::id()
            ]
        ));

        collect($request->questions)->map(function ($data) use ($test) {
            $question = $test->questions()->create(collect($data)->only(['question', 'type', 'answer'])->all());
            if ($data['type'] !== 'text')
                $question->answers()->createMany($data['answers']);
        });

        $test->ratings()->createMany($request->ratings);

        return collect(TestResource::make($test));
    }

    public function check(TestCheckRequest $request, Test $test)
    {
        $check = $test->questions->map(fn(Question $question) => $question->check($request->data));
        $response = Response::create(array_merge(
            $request->only(['name', 'time']),
            [
                'ip' => $request->ip(),
                'correct_answers' => $check->filter(fn($el) => $el['is_correct'])->count(),
                'data' => json_encode($request->data),
                'test_id' => $test->id
            ]
        ));

        return response(ResponseResource::make($response));
    }

    public function destroy(Test $test)
    {
        if (Auth::id() !== $test->user_id)
            throw new BadRequestHttpException('You can\'t delete');
        $test->delete();
        return response()->noContent();
    }

    public function info(Test $test)
    {
        return response([
            'test' => TestResource::make($test),
            'responses' => ResponseResource::collection($test->responses)
        ]);
    }
}
