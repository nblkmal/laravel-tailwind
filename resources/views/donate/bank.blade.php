@extends('layout')

@section('content')
<div class="w-4/5 my-16">
    <div class="bg-white rounded-xl">
        <div class="max-w-7xl mx-auto py-12 sm:px-6 lg:py-16 lg:px-1 lg:flex lg:items-center lg:justify-between">
            <h2 class="flex inline-flex items-center text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Donate to us!</span>
            </h2>
        </div>
    </div>
    <div class="bg-white justify-center mx-auto rounded-xl my-5 w-1/2 py-5 px-5">
        <div class="overflow-auto lg:overflow-visible sm:overflow-hidden ">
			<form method="POST" action="{{ route('donate:create') }}">
                @csrf
                <div class="grid grid-cols-4 gap-4 my-4">
                    @foreach ($banks as $bank)
                        <a href="{{ route('donate:runbill', [$bank['CODE'], $donation]) }}" class="border rounded-lg px-auto py-3 text-center">{{ $bank['NAME'] }}</a>
                    @endforeach
                </div>

                <div class="flex w-full">
                    <button type="submit" class="flex mt-2 items-center justify-center focus:outline-none mt-4 mb-3 w-full bg-green-700 hover:bg-green-500 text-white py-2 rounded-md transition duration-100">
                        <span class="mr-2">Donate now</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                
            </form>
		</div>
    </div>
    
</div>
@endsection