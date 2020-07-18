<?php

namespace Tests\Feature\Controllers\Api;

use App\Mail\ParticipantCreated;
use App\Models\Participant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Passport;
use Tests\Generators\EventsGenerator;
use Tests\Generators\ParticipantGenerator;
use Tests\Generators\UserGenerator;
use Tests\TestCase;

class ParticipantsControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @group api
     * @group participant_api
     */
    public function testList()
    {
        EventsGenerator::createEvents();
        ParticipantGenerator::createParticipant();
        ParticipantGenerator::createParticipant();

        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        $response = $this->json(
            'GET',
            route('api.participants.index')
        )
            ->assertStatus(200);

        $data = json_decode($response->content(), true);
        $this->assertEquals(2, count($data['data']));
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testListWillReturn401IfUnauthenticated()
    {
        EventsGenerator::createEvents();
        ParticipantGenerator::createParticipant();

        $this->json(
            'GET',
            route('api.participants.index')
        )
            ->assertStatus(401);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testStore()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::makeParticipant();

        $this->json(
            'POST',
            route('api.participants.store'),
            $participant->toArray()
        )->assertStatus(201);

        $this->assertDatabaseHas('participants', [
            'email' => $participant->email,
        ]);
    }


    /**
     * @group api
     * @group participant_api
     */
    public function testStoreWithEmail()
    {
        Mail::fake();

        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::makeParticipant();

        $this->json(
            'POST',
            route('api.participants.store'),
            $participant->toArray()
        )->assertStatus(201);

        Mail::assertQueued(ParticipantCreated::class, function($mail) use ($participant) {
            return $mail->hasTo($participant->email);
        });
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testStoreWontCreateParticipantWithoutEmail()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::makeParticipant()->toArray();
        $email = $participant['email'];
        unset($participant['email']);

        $this->json(
            'POST',
            route('api.participants.store'),
            $participant
        )->assertStatus(422);

        $this->assertDatabaseMissing('participants', [
            'email' => $email,
        ]);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testStoreWontCreateParticipantWithTheSameEmail()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::createParticipant();
        $email = $participant->email;

        $madeParticipant = ParticipantGenerator::makeParticipant()->toArray();
        $madeParticipant['email'] = $email;

        $this->json(
            'POST',
            route('api.participants.store'),
            $madeParticipant
        )->assertStatus(422);

        $count = Participant::where('email', $email)->count();
        $this->assertEquals(1, $count);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testStoreWontCreateParticipantWithNonExistentEvent()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::makeParticipant()->toArray();
        $participant['event_id'] = 99999;

        $this->json(
            'POST',
            route('api.participants.store'),
            $participant
        )->assertStatus(422);

        $this->assertDatabaseMissing('participants', [
            'email' => $participant['email'],
        ]);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testShow()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::createParticipant();

        $this->json(
            'GET',
            route('api.participants.show', [
                'participant' => $participant->id
            ])
        )->assertStatus(200)
        ->assertJsonFragment(['id' => $participant->id]);

    }

    /**
     * @group api
     * @group participant_api
     */
    public function testShowWillReturn404IfModelNotFound()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        ParticipantGenerator::createParticipant();

        $this->json(
            'GET',
            route('api.participants.show', [
                'participant' => 'sads'
            ])
        )->assertStatus(404);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testUpdate()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::createParticipant();

        $name = $this->faker->name;

        $response = $this->json(
            'PUT',
            route('api.participants.update', [
                'participant' =>$participant->id,
            ]), [
            'first_name' => $name,
        ])
            ->assertStatus(200);

        $response->assertJsonFragment(['first_name' => $name]);
    }

    /**
     * @group api
     * @group participant_api
     */
    public function testDelete()
    {
        $user = UserGenerator::createUser();
        Passport::actingAs($user);

        EventsGenerator::createEvents();

        $participant = ParticipantGenerator::createParticipant();

        $email = $participant->email;

        $this->json(
            'DELETE',
            route('api.participants.destroy', [
                'participant' =>$participant->id,
            ])
        )
            ->assertStatus(204);

        $this->assertDatabaseMissing('participants', [
            'email' => $email,
        ]);
    }
}
