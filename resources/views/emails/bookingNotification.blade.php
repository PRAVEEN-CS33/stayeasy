<x-mail::message>
# New Booking Confirmed

A new booking has been made for your accommodation!

## Booking Details:
- **Guest Name:** {{ $bookingDetails->user_id }}
- **Accommodation Name:** {{ $bookingDetails->accommodation_id }}
- **Check in Date:** {{ $bookingDetails->check_in->format('F d, Y') }}
- **Check in Date:** {{ $bookingDetails->check_out->format('F d, Y') }}
- **Total Price:** ${{ $bookingDetails->amount }}

You can view more details about this booking in your account.

<x-mail::button :url="url('/owners/booking')">
View Booking
</x-mail::button>



Thanks,  
{{ config('app.name') }}
</x-mail::message>
