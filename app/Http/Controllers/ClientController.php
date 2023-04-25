<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Client;
use App\Models\VerifyUser;
use App\Models\Policy;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
class ClientController extends Controller
{

    public function add()
    {
    $policies = Policy::all();

    return view('dashboard.user.add', compact('policies'));
    }


    public function generateReport($id)
    {
        $client = Client::findOrFail($id);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $pdf = new Dompdf($options);

        $html = view('dashboard.user.client-report', compact('client'));

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream($client->client_fname . '-report.pdf');
    }
    public function generatePReport($id)
    {
        $client = Client::findOrFail($id);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $pdf = new Dompdf($options);

        $html = view('dashboard.user.premium-report', compact('client'));

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream($client->client_fname . '-report.pdf');
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->client_fname = $request->input('client_fname');
        $client->Age = $request->input('Age');
        $client->driving_license_number = $request->input('driving_license_number');
        $client->client_email = $request->input('client_email');
        $client->phone_number = $request->input('phone_number');
        $client->vehicle_model = $request->input('vehicle_model');
        $client->vehicle_registration = $request->input('vehicle_registration');

        $client->save();

        return redirect()->route('user.clients')->with('success', 'client has been updated');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return view('dashboard.user.edit-client', compact('client'));
    }

    public function getClientForCurrentUser()
    {
        $user = Auth::user();
        $clients = $user->clients;

        return view('dashboard.user.clients', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'policy_start_date' => 'required|date_format:Y-m-d',
        ]);

        
        $policy = Policy::findOrFail($request->input('policy_id'));

        $client = new Client();
        $client->client_fname = $request->input('client_fname');
        $client->Age = $request->input('Age');
        $client->driving_license_number = $request->input('driving_license_number');
        $client->client_email = $request->input('client_email');
        $client->phone_number = $request->input('phone_number');
        $client->vehicle_model = $request->input('vehicle_model');
        $client->policy_id = $policy->id;
        $client->policy_type = $policy->policy_type; // retrieve policy_type from policies table
        $client->policy_duration = $policy->policy_duration; // retrieve duration from policies table
        $client->coverage_amount = $request->input('coverage_amount');
        $client->excess_amount = $request->input('excess_amount');
        if ($client->Age < 25) {
            $client->excess_amount += 250;
        }
        // Calculate premium_amount using the formula
        $policyRate = $policy->coverage_rate;
        $premium = ($client->coverage_amount * $policyRate/100) - ($client->excess_amount * $policyRate/100);
        $premium -= ($client->excess_amount * $policyRate/100) * ($client->vehicle_model/100);
    
        $payment_period = $request->input('payment_period');
        if ($payment_period == 'monthly') {
            $premium = $premium / 12;
        } elseif ($payment_period == 'quarterly') {
            $premium = $premium / 4;
        }
        
        $client->premium_amount = $premium;

        $client->payment_period = $request->input('payment_period');
        $client->vehicle_registration = $request->input('vehicle_registration');
        $client->policy_start_date = $request->input('policy_start_date');
        $client->payment_date = $request->input('policy_start_date');
        $client->Insurer_id = auth()->user()->id;
        $client->premium_due_date = $client->calculatePremiumDueDate();
        $client->policy_end_date = $client->calculatePolicyEndDate();

        
        $client->save();

        return redirect()->route('user.clients');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('user.clients')->with('success', 'client has been deleted');
    }
}
