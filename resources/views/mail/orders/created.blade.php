<x-mail::message>
# Hello {{$fullName}},
    Here is your order summary:

<x-mail::table>
    | Item    | Quantity | Price  | Total  |
    |:--------|--------:|------:|------:|
    @foreach($items as $item)
        | {{ $item->title }} | {{ $item->pivot->quantity }} | ${{ round($item->pivot->single_price,2) }} | ${{ round($item->pivot->single_price * $item->pivot->quantity,2) }} |
    @endforeach
</x-mail::table>

<x-mail::button :url="$invoiceUrl">
Order invoice
</x-mail::button>
Thanks
{{ config('app.name') }}

</x-mail::message>
