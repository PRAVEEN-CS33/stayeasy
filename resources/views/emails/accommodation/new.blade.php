<x-mail::message>
# New Accommodation Added

A new accommodation has been added:

- **Name:** {{ $accommodationDetails->accommodation_name }}
- **Location:** {{ $accommodationDetails->address }}, {{ $accommodationDetails->city }}, {{ $accommodationDetails->state }}
- **Preferred By:** {{ $accommodationDetails->preferred_by }}
- **Gender Type:** {{ $accommodationDetails->gender_types }}

<x-mail::button :url="''">
View Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>