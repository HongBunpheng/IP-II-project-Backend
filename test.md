Profile test
{
  "name": "Nimol",
  "email": "nimol@gmail.com",
  "password": "password123",
  "password_confirmation": "password123",
  "nickname": "Nikah",
  "location": "Phnom Penh",
  "instagram": "nikah.official",
  "phone": "012345678",
  "address": "St 123, Phnom Penh",
  "dob": "1999-09-01"
}
nimol@Nimols-Macbook-pro laravel % php artisan tinker              

Psy Shell v0.12.8 (PHP 8.4.1 — cli) by Justin Hileman
> App\Models\SearchPlace::create([
.   'name' => 'Angkor Wat',
.   'location' => 'Siem Reap',
.   'description' => 'Famous Cambodian temple'
. ]);
= App\Models\SearchPlace {#5277
    name: "Angkor Wat",
    location: "Siem Reap",
    description: "Famous Cambodian temple",
    updated_at: "2025-06-22 04:40:32",
    created_at: "2025-06-22 04:40:32",
    id: 3,
  }
