@extends('layout')

@section('content')
<div class="w-4/5 my-16">
  <div class="bg-gray-50 rounded-xl">
      <div class="max-w-7xl mx-auto py-12 sm:px-6 lg:py-16 lg:px-1 lg:flex lg:items-center lg:justify-between">
          <h2 class="flex inline-flex items-center text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Documentation</span>
          </h2>
      </div>
  </div>
  <div class="bg-[#1E293B] text-white rounded-xl my-5">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <pre class="language-html">
          <code class="language-html">
            <span>
              asukcasn
            </span>
          </code>
        </pre>
    </div>
  </div>
</div>
@endsection