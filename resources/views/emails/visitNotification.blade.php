<x-mail::message>
# Scheduled Visit Confirmation

A visit has been scheduled for the following accommodation:

{{-- ## Visit Details:
- **Accommodation Name:** {{ $accommodation_name }}
- **Accommodation Location:** {{ $accommodation_location }}
- **Scheduled By (Guest):** {{ $guest_name }}
- **Owner Name:** {{ $owner_name }}
- **Visit Date:** {{ $visit_date }}
- **Status:** {{ ucfirst($status) }} --}}

Please make necessary arrangements for the visit.

<x-mail::button :url="url('/owners/scheduledvisit')">
View Visit Details
</x-mail::button>

Thank you for using StayEasy!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
