@extends('butik::web.layouts.express-checkout')

@section('content')

    @if ($order->status == 'paid')
        <div class="b-px-24 b-max-w-sm b-mx-auto b-text-green-500">
            <svg class="b-fill-current b-mx-auto" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd"><g id="icon-shape"><path d="M2.92893219,17.0710678 C6.83417511,20.9763107 13.1658249,20.9763107 17.0710678,17.0710678 C20.9763107,13.1658249 20.9763107,6.83417511 17.0710678,2.92893219 C13.1658249,-0.976310729 6.83417511,-0.976310729 2.92893219,2.92893219 C-0.976310729,6.83417511 -0.976310729,13.1658249 2.92893219,17.0710678 L2.92893219,17.0710678 L2.92893219,17.0710678 Z M15.6568542,15.6568542 C18.7810486,12.5326599 18.7810486,7.46734008 15.6568542,4.34314575 C12.5326599,1.21895142 7.46734008,1.21895142 4.34314575,4.34314575 C1.21895142,7.46734008 1.21895142,12.5326599 4.34314575,15.6568542 C7.46734008,18.7810486 12.5326599,18.7810486 15.6568542,15.6568542 L15.6568542,15.6568542 Z M4,10 L6,8 L9,11 L14,6 L16,8 L9,15 L4,10 Z" id="Combined-Shape"></path></g></g></svg>
        </div>

        <h1 class="b-block b-my-8 b-text-center b-font-bold b-text-xl">{{ __('butik::payment.successful') }}</h1>
    @elseif ($order->status == 'open')
        <div class="b-px-24 b-max-w-sm b-mx-auto b-text-orange-500">
            <svg class="b-fill-current" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Page-1" stroke="none" stroke-width="1" fill-rule="evenodd"><g id="icon-shape"><path d="M12,10 L20,10 C20,7.44077682 19.0236893,4.88155365 17.0710678,2.92893219 C15.1184464,0.976310729 12.5592232,1.63312031e-10 10,1.63311142e-10 L10,8 C9.48815536,8 8.97631073,8.19526215 8.58578644,8.58578644 C7.80473785,9.36683502 7.80473785,10.633165 8.58578644,11.4142136 C9.36683502,12.1952621 10.633165,12.1952621 11.4142136,11.4142136 C11.8047379,11.0236893 12,10.5118446 12,10 L12,10 Z M19.9000864,11.4142136 C19.6060539,13.4838873 18.6630477,15.4790879 17.0710678,17.0710678 C13.1658249,20.9763107 6.83417511,20.9763107 2.92893219,17.0710678 C-0.976310729,13.1658249 -0.976310729,6.83417511 2.92893219,2.92893219 C4.52091212,1.33695225 6.51611265,0.39394606 8.58578644,0.0999136115 L8.58578644,2.12527585 C7.03175841,2.40295389 5.54404764,3.14224386 4.34314575,4.34314575 C1.21895142,7.46734008 1.21895142,12.5326599 4.34314575,15.6568542 C7.46734008,18.7810486 12.5326599,18.7810486 15.6568542,15.6568542 C16.8577561,14.4559524 17.5970461,12.9682416 17.8747242,11.4142136 L19.9000864,11.4142136 L19.9000864,11.4142136 Z M15.8318407,11.4142136 C15.5815375,12.4506982 15.0518041,13.4334772 14.2426407,14.2426407 C11.8994949,16.5857864 8.10050506,16.5857864 5.75735931,14.2426407 C3.41421356,11.8994949 3.41421356,8.10050506 5.75735931,5.75735931 C6.56652277,4.94819586 7.54930184,4.41846252 8.58578644,4.16815931 L8.58578644,6.25729053 C8.07014061,6.45178392 7.58660105,6.7565447 7.17157288,7.17157288 C5.60947571,8.73367004 5.60947571,11.26633 7.17157288,12.8284271 C8.73367004,14.3905243 11.26633,14.3905243 12.8284271,12.8284271 C13.2434553,12.4133989 13.5482161,11.9298594 13.7427095,11.4142136 L15.8318407,11.4142136 L15.8318407,11.4142136 Z" id="Combined-Shape"></path></g></g</svg>
        </div>

        <h1 class="b-block b-my-8 b-text-center b-font-bold b-text-xl">{{ __('butik::payment.waiting') }}</h1>
    @elseif ($order->status == 'failed')

        <div class="b-px-24 b-max-w-sm b-mx-auto b-text-red-500">
            <svg class="b-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM6.5 9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm7 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm2.16 6H4.34a6 6 0 0 1 11.32 0z"></path></svg>
        </div>

        <h1 class="b-block b-my-8 b-text-center b-font-bold b-text-xl">{{ __('butik::payment.failed') }}</h1>
    @elseif ($order->status == 'failed')

        <div class="b-px-24 b-max-w-sm b-mx-auto b-text-red-500">
            <svg class="b-fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM6.5 9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm7 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm2.16 6H4.34a6 6 0 0 1 11.32 0z"></path></svg>
        </div>

        <h1 class="b-block b-my-8 b-text-center b-font-bold b-text-xl">{{ __('butik::payment.canceled') }}</h1>
    @endif

    <div class="b-flex b-flex-col-reverse lg:b-flex-row b-px-5 b-justify-center lg:b-px-0 b-mb-20 b-mt-6">
        <div class="b-w-full lg:b-ml-5 lg:b-px-0 lg:b-w-1/2 xl:b-ml-0">

            <div class="b-bordeer b-border b-border-gray-500 b-px-6 b-py-3 b-mt-8 lg:b-mt-0 b-rounded-md">
                <h3 class="b-underline">{{ __('butik::payment.ship_to') }}</h3>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.name') }}</span>
                    <span>{{ $customer->name }}</span>
                </div>
                <div class="b-mb-2">
                    <span class="b-font-bold">{{ __('butik::payment.mail') }}</span>
                    <span>{{ $customer->mail }}</span>
                </div>

                <div>
                    <span class="b-font-bold">{{ __('butik::payment.address1') }}</span>
                    <span>{{ $customer->address1 }}</div>
                @if (isset($address2))
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.address2') }}</span>
                    <span>{{ $customer->address2 }}</span>
                </div>
                @endif
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.city') }}</span>
                    <span>{{ $customer->city }}</span>
                </div>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.zip') }}</span>
                    <span>{{ $customer->zip }}</span>
                </div>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.country') }}</span>
                    <span>{{ $customer->country }}</span>
                </div>
            </div>

            <div class="b-bordeer b-border b-border-gray-500 b-px-6 b-py-3 b-mt-8 b-rounded-md">
                <div>{{ __('butik::payment.mail_sent', ['mail' => $customer->mail]) }}</div>
                <div class="b-my-2">
                    <span class="b-font-bold">{{ __('butik::payment.status') }}</span>
                    <span>{{ $order->status }}</span>
                </div>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.id') }}</span>
                    <span>{{ $order->id }}</span>
                </div>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.method') }}</span>
                    <span>{{ $order->method }}</span>
                </div>
                <div>
                    <span class="b-font-bold">{{ __('butik::payment.total') }}</span>
                    <span>{{ config('butik.currency_symbol') }} {{ $order->total_amount }}</span>
                </div>
                <div class="b-font-bold b-mt-3">
                    {{ __('butik::payment.thank_you') }}
                </div>
            </div>

            <button id="submit-button" class="b-block b-w-full b-mt-3 b-bg-gray-900 b-mt-6 b-py-2 b-rounded b-text-center b-text-white b-text-xl hover:b-bg-gray-800">
                {{ __('butik::payment.go_back') }}
            </button>

        </div>

    </div>

    <script type="text/javascript">
        setTimeout(function () { location.reload(true); }, 3500);
    </script>

@endsection
