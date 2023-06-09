@extends('layouts.main-template')
@section('title', isset($title) ? $title : 'Payments | Insurer')
@section('content')

<div class="relative max-w-7xl mx-auto">
    <div class="max-w-lg mx-auto rounded-lg shadow-lg overflow-hidden lg:max-w-none lg:flex">
        <div class="flex-1 px-6 py-8 lg:p-12 bg-gray-600">
            <h3 class="text-2xl font-extrabold text-white sm:text-3xl">Active Policy Premiums</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-3">
    </div>
    @php
    $clients = App\Models\Client::paginate(5);
    $policies = App\Models\Policy::all();
    $payments = App\Models\Payment::all();
    @endphp

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Policy Type</th>
                <th>Payment Amount</th>
                <th>Period</th>
                <th>Last Payment Made</th>
                <th>Next Payment Due</th>
                <th>Policy End</th>
                <th>Status</th>
                <th></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->client_fname }}</td>
                <td>{{ $client->policy_type }}</td>
                <td>${{ number_format($client->premium_amount, 2, '.', ',') }}</td>
                <td>{{ $client->payment_period }}</td>
                <td>{{ $client->payment_date }}</td>
                <td>{{ $client->premium_due_date }}</td>
                <td>{{ $client->policy_end_date }}</td>
                <td>
                    @php
                    $status = '';
                    $class = '';

                    $current_date = date('Y-m-d');
                    $due_date = date('Y-m-d', strtotime($client->premium_due_date . ' - 20 days'));
                    $policy_end_date = date('Y-m-d', strtotime($client->policy_end_date));

                    if ($current_date > $policy_end_date) {
                        $status = 'Expired';
                        $class = 'm-2 inline-block rounded bg-danger py-1 px-2 text-sm font-semibold text-white';
                    } elseif ($current_date > $client->premium_due_date) {
                        $status = 'Expired';
                        $class = 'm-2 inline-block rounded bg-danger py-1 px-2 text-sm font-semibold text-white';
                    } elseif ($current_date >= $due_date && $current_date <= $client->premium_due_date) {
                        $status = 'Due';
                        $class = 'm-2 inline-block rounded bg-warning py-1 px-2 text-sm font-semibold text-black';
                    } else {
                        $status = 'Active';
                        $class = 'm-2 inline-block rounded bg-success py-1 px-2 text-sm font-semibold text-white';
                    }
                    @endphp

                    <span class="{{ $class }}">{{ $status }}</span>
                </td>
                @if ($status == 'Expired' || $current_date > $policy_end_date)
                <td>
                    <form action="{{ route('renew.policy', $client->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Renew</button>
                    </form>
                    <form action="{{ route('notify.client', $client->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">Notify User</button>
                    </form>
                </td>
                @else
                <td>
                    <form action="{{ route('cancel.renewal', $client->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Cancel Renewal</button>
                    </form>
                </td>
                @endif

                <td>
                    <a href="{{ route('premium.report', $client->id) }}" class="btn btn-success btn-sm"><i
                            class="nav-icon fas fa-download"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        <a href="{{ route('payments.report') }}" class="btn btn-success btn-sm">Generate Payments Report</a>
    </div>

    <div class="text-center">
        {{ $clients->links() }}
    </div>
</div>
@endsection
