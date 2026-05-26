<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Vaccine $vaccine;
    protected Center $center;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name'            => 'Test Patient',
            'email'           => 'patient@example.com',
            'phone'           => '1234567890',
            'dob'             => '1990-01-01',
            'password'        => bcrypt('password'),
            'role'            => 'user',
            'aadhar_number'   => '123456789012',
            'aadhar_verified' => true,
        ]);

        $this->vaccine = Vaccine::create([
            'name'               => 'Test Vaccine',
            'manufacturer'       => 'Test Manufacturer',
            'description'        => 'Test Description',
            'doses_required'     => 2,
            'days_between_doses' => 28,
            'status'             => 'available',
            'stock'              => 10,
            'price'              => 0.00,
        ]);

        $this->center = Center::create([
            'name'         => 'Test Center',
            'address'      => '123 Test St',
            'city'         => 'Test City',
            'state'        => 'Test State',
            'pincode'      => '123456',
            'phone'        => '011-12345678',
            'email'        => 'center@example.com',
            'opening_time' => '09:00:00',
            'closing_time' => '17:00:00',
            'status'       => 'active',
        ]);
    }

    public function test_user_can_book_appointment_successfully(): void
    {
        $response = $this->actingAs($this->user)->post(route('user.appointments.store'), [
            'vaccine_id'       => $this->vaccine->id,
            'center_id'        => $this->center->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '10:00',
            'dose_number'      => 1,
            'notes'            => 'No allergies',
        ]);

        $response->assertRedirect(route('user.appointments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'user_id'     => $this->user->id,
            'vaccine_id'  => $this->vaccine->id,
            'center_id'   => $this->center->id,
            'dose_number' => 1,
            'status'      => 'pending',
        ]);

        $this->assertEquals(9, $this->vaccine->fresh()->stock);
    }

    public function test_user_can_cancel_appointment_successfully(): void
    {
        $appointment = \App\Models\Appointment::create([
            'user_id'          => $this->user->id,
            'vaccine_id'       => $this->vaccine->id,
            'center_id'        => $this->center->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'appointment_time' => '10:00:00',
            'dose_number'      => 1,
            'status'           => 'pending',
        ]);

        // Stock was not decremented automatically on direct Eloquent create in test setup
        $this->vaccine->update(['stock' => 9]);

        $response = $this->actingAs($this->user)->delete(route('user.appointments.destroy', $appointment));

        $response->assertRedirect(route('user.appointments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'id'     => $appointment->id,
            'status' => 'cancelled',
        ]);

        $this->assertEquals(10, $this->vaccine->fresh()->stock);
    }
}
