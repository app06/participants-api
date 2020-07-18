<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParticipantCreateRequest;
use App\Http\Requests\ParticipantIndexRequest;
use App\Http\Requests\ParticipantUpdateRequest;
use App\Http\Resources\ParticipantResource;
use App\Mail\ParticipantCreated;
use App\Models\Participant;
use Illuminate\Support\Facades\Mail;

class ParticipantsController extends Controller
{
    public function index(ParticipantIndexRequest $request)
    {
        $query = Participant::query();

        $query->when($request->filled('filter'), function ($query) use ($request) {
            [$criteria, $value] = explode(':', $request->input('filter'));
            return $query->where($criteria, $value);
        });

        return ParticipantResource::collection(
            $query->with('event')->paginate(20)
        );
    }

    public function store(ParticipantCreateRequest $request)
    {
        $participant = Participant::create($request->all());

        Mail::to($participant->email)->queue(new ParticipantCreated($participant));

        return new ParticipantResource($participant);
    }

    public function show(Participant $participant)
    {
        return new ParticipantResource($participant);
    }

    public function update(ParticipantUpdateRequest $request, Participant $participant)
    {
        $participant->update($request->all());

        return new ParticipantResource($participant);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return response()->json(null, 204);
    }
}
