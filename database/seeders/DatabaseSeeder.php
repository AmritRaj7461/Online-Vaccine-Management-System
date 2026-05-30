<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Center;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        // Mock Aadhaar Registry (Always seed/update to ensure new test users are available)
        $aadhaarRecords = [
            [
                'aadhar_number' => '123456789012',
                'registered_name' => 'Amrit Mishra',
                'registered_mobile' => '9876500001',
            ],
            [
                'aadhar_number' => '123456789013',
                'registered_name' => 'Amrit Raj',
                'registered_mobile' => '917461006461',
            ],
            [
                'aadhar_number' => '123456789014',
                'registered_name' => 'Muskan Kumari',
                'registered_mobile' => '9876500004',
            ],
            [
                'aadhar_number' => '123456789015',
                'registered_name' => 'Kanak Sao',
                'registered_mobile' => '9302264639',
            ],
            [
                'aadhar_number' => '987654321098',
                'registered_name' => 'Priya Sharma',
                'registered_mobile' => '9876500002',
            ],
            [
                'aadhar_number' => '555566667777',
                'registered_name' => 'Raj Malhotra',
                'registered_mobile' => '9876500003',
            ],
        ];

        foreach ($aadhaarRecords as $record) {
            \DB::table('aadhaar_registries')->updateOrInsert(
                ['aadhar_number' => $record['aadhar_number']],
                [
                    'registered_name' => $record['registered_name'],
                    'registered_mobile' => $record['registered_mobile']
                ]
            );
        }

        // Prevent duplicate seeding in production for Users, Vaccines, Centers, and Appointments
        if (User::where('email', 'admin@vaccicare.com')->exists()) {
            return;
        }

        
        
        
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@vaccicare.com',
            'phone'    => '9876543210',
            'dob'      => '1985-06-15',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $user1 = User::create([
            'name'     => 'Amrit Mishra',
            'email'    => 'user@vaccicare.com',
            'phone'    => '9876500001',
            'dob'      => '1998-03-22',
            'aadhar_number' => '123456789012',
            'aadhar_verified' => false,
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $user2 = User::create([
            'name'     => 'Priya Sharma',
            'email'    => 'priya@example.com',
            'phone'    => '9876500002',
            'dob'      => '2000-07-10',
            'aadhar_number' => '987654321098',
            'aadhar_verified' => true,
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        
        
        
        $vaccines = [
            [
                'name'               => 'Covishield',
                'manufacturer'       => 'Serum Institute of India',
                'description'        => 'AstraZeneca COVID-19 vaccine manufactured by Serum Institute of India. Highly effective against severe disease.',
                'doses_required'     => 2,
                'days_between_doses' => 84,
                'status'             => 'available',
                'stock'              => 500,
                'price'             => 0.00,
            ],
            [
                'name'               => 'Covaxin',
                'manufacturer'       => 'Bharat Biotech',
                'description'        => 'India\'s first indigenous COVID-19 vaccine, developed using whole-virion inactivated coronavirus technology.',
                'doses_required'     => 2,
                'days_between_doses' => 28,
                'status'             => 'available',
                'stock'              => 300,
                'price'             => 0.00,
            ],
            [
                'name'               => 'MMR Vaccine',
                'manufacturer'       => 'Merck & Co.',
                'description'        => 'Combined vaccine that protects against measles, mumps, and rubella (German measles).',
                'doses_required'     => 2,
                'days_between_doses' => 28,
                'status'             => 'available',
                'stock'              => 200,
                'price'             => 150.00,
            ],
            [
                'name'               => 'Hepatitis B Vaccine',
                'manufacturer'       => 'GlaxoSmithKline',
                'description'        => 'Provides immunity against Hepatitis B virus infection. Recommended for all age groups.',
                'doses_required'     => 3,
                'days_between_doses' => 30,
                'status'             => 'available',
                'stock'              => 400,
                'price'             => 200.00,
            ],
            [
                'name'               => 'Flu Vaccine (Influvac)',
                'manufacturer'       => 'Abbott India',
                'description'        => 'Annual influenza vaccine providing protection against seasonal flu strains.',
                'doses_required'     => 1,
                'days_between_doses' => 0,
                'status'             => 'available',
                'stock'              => 250,
                'price'             => 500.00,
            ],
            [
                'name'               => 'Typhoid Vaccine (Typbar TCV)',
                'manufacturer'       => 'Bharat Biotech',
                'description'        => 'Single-dose typhoid conjugate vaccine providing long-lasting protection.',
                'doses_required'     => 1,
                'days_between_doses' => 0,
                'status'             => 'available',
                'stock'              => 180,
                'price'             => 350.00,
            ],
            [
                'name'               => 'Rabies Vaccine (Verorab)',
                'manufacturer'       => 'Sanofi Pasteur',
                'description'        => 'Used for pre-exposure and post-exposure prophylaxis against rabies.',
                'doses_required'     => 3,
                'days_between_doses' => 7,
                'status'             => 'available',
                'stock'              => 120,
                'price'             => 800.00,
            ],
            [
                'name'               => 'HPV Vaccine (Gardasil)',
                'manufacturer'       => 'Merck & Co.',
                'description'        => 'Protects against human papillomavirus (HPV) types that cause cervical cancer and genital warts.',
                'doses_required'     => 2,
                'days_between_doses' => 168,
                'status'             => 'available',
                'stock'              => 90,
                'price'             => 2500.00,
            ],
        ];

        $vaccineModels = [];
        foreach ($vaccines as $vData) {
            $vaccineModels[] = Vaccine::create($vData);
        }

        
        
        
        $centers = [
            [
                'name'         => 'AIIMS Primary Health Center',
                'address'      => 'Ansari Nagar East, New Delhi',
                'city'         => 'New Delhi',
                'state'        => 'Delhi',
                'pincode'      => '110029',
                'phone'        => '011-26588500',
                'email'        => 'aiims.phc@example.com',
                'opening_time' => '08:00:00',
                'closing_time' => '18:00:00',
                'status'       => 'active',
            ],
            [
                'name'         => 'KEM Hospital Vaccination Center',
                'address'      => 'Acharya Donde Marg, Parel',
                'city'         => 'Mumbai',
                'state'        => 'Maharashtra',
                'pincode'      => '400012',
                'phone'        => '022-24107000',
                'email'        => 'kem.vacc@example.com',
                'opening_time' => '09:00:00',
                'closing_time' => '17:00:00',
                'status'       => 'active',
            ],
            [
                'name'         => 'Bangalore Community Health Center',
                'address'      => 'Jayanagar 4th Block, Bengaluru',
                'city'         => 'Bengaluru',
                'state'        => 'Karnataka',
                'pincode'      => '560011',
                'phone'        => '080-26631234',
                'email'        => 'blr.chc@example.com',
                'opening_time' => '09:00:00',
                'closing_time' => '16:00:00',
                'status'       => 'active',
            ],
            [
                'name'         => 'Hyderabad Vaccine Clinic',
                'address'      => 'Banjara Hills Road No.12',
                'city'         => 'Hyderabad',
                'state'        => 'Telangana',
                'pincode'      => '500034',
                'phone'        => '040-23541234',
                'email'        => null,
                'opening_time' => '10:00:00',
                'closing_time' => '18:00:00',
                'status'       => 'active',
            ],
            [
                'name'         => 'Chennai Government Hospital',
                'address'      => 'Park Town, Chennai',
                'city'         => 'Chennai',
                'state'        => 'Tamil Nadu',
                'pincode'      => '600003',
                'phone'        => '044-25305000',
                'email'        => 'cgh.vacc@example.com',
                'opening_time' => '09:00:00',
                'closing_time' => '17:30:00',
                'status'       => 'active',
            ],
        ];

        $centerModels = [];
        foreach ($centers as $cData) {
            $centerModels[] = Center::create($cData);
        }

        
        
        
        Appointment::create([
            'user_id'          => $user1->id,
            'vaccine_id'       => $vaccineModels[0]->id,
            'center_id'        => $centerModels[0]->id,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'appointment_time' => '10:00:00',
            'dose_number'      => 1,
            'status'           => 'confirmed',
        ]);

        Appointment::create([
            'user_id'          => $user1->id,
            'vaccine_id'       => $vaccineModels[2]->id,
            'center_id'        => $centerModels[1]->id,
            'appointment_date' => now()->addDays(10)->toDateString(),
            'appointment_time' => '14:00:00',
            'dose_number'      => 1,
            'status'           => 'pending',
        ]);

        Appointment::create([
            'user_id'          => $user2->id,
            'vaccine_id'       => $vaccineModels[1]->id,
            'center_id'        => $centerModels[2]->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '11:30:00',
            'dose_number'      => 1,
            'status'           => 'completed',
        ]);

        Appointment::create([
            'user_id'          => $user2->id,
            'vaccine_id'       => $vaccineModels[4]->id,
            'center_id'        => $centerModels[3]->id,
            'appointment_date' => now()->addDays(7)->toDateString(),
            'appointment_time' => '15:30:00',
            'dose_number'      => 1,
            'status'           => 'pending',
        ]);
    }
}
