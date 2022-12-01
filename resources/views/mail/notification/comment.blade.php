@component('mail::message')
@component('mail::title')
# New Comment
@endcomponent

The following tip:

> {{ $title }}

Has received the following comment from {{ $user }}:

> {{ $message }}

If you'd like to continue the conversation, then click the link below.

TipSea requires that all conversations are civil and lawful. If this
message contains inappropriate content, then please let us know.

{{-- Action --}}
@component('mail::button', ['url' => $url])
Reply
@endcomponent

{{-- Link Help --}}
@slot('subcopy')
If you are having trouble clicking the button, you can click the following
link, or copy and paste it into your web browser: [{{ $url }}]({{ $url }})
@endslot
@endcomponent