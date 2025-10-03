<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Handle the subscription request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        // Validar el email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please enter a valid email address.');
        }

        try {
            $email = $request->input('email');
            
            // Verificar si el email ya está suscrito
            $existingSubscriber = Subscriber::where('email', $email)->first();
            
            if ($existingSubscriber) {
                if ($existingSubscriber->is_active) {
                    return redirect()->back()->with('error', 'This email is already subscribed to our newsletter.');
                } else {
                    // Reactivar suscripción
                    $existingSubscriber->is_active = true;
                    $existingSubscriber->token = Str::random(32);
                    $existingSubscriber->save();
                    
                    $this->sendConfirmationEmail($email, $existingSubscriber->token);
                    
                    return redirect()->back()->with('success', 'Your subscription has been reactivated! We have sent you a confirmation email.');
                }
            }
            
            // Crear nuevo suscriptor
            $token = Str::random(32);
            Subscriber::create([
                'email' => $email,
                'is_active' => true,
                'token' => $token
            ]);
            
            // Enviar email de confirmación
            $this->sendConfirmationEmail($email, $token);
            
            return redirect()->back()->with('success', 'Thank you for subscribing! We have sent you a confirmation email.');
        } catch (\Exception $e) {
            Log::error('Error en suscripción: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'An error occurred while processing your subscription. Please try again.');
        }
    }
    
    /**
     * Send a confirmation email to the subscriber.
     *
     * @param  string  $email
     * @param  string  $token
     * @return void
     */
    private function sendConfirmationEmail($email, $token = null)
    {
        // Aquí utilizamos la configuración SMTP del archivo .env
        $data = [
            'email' => $email,
            'siteName' => config('app.name'),
            'token' => $token
        ];

        Mail::send('emails.subscription_confirmation', $data, function ($message) use ($email) {
            $message->to($email)
                ->subject('Subscription Confirmation');
        });
    }
    
    /**
     * Confirm subscription with token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function confirmSubscription($token)
    {
        $subscriber = Subscriber::where('token', $token)->first();
        
        if (!$subscriber) {
            return redirect()->route('news')->with('error', 'The confirmation link is invalid.');
        }
        
        $subscriber->is_active = true;
        $subscriber->save();
        
        return redirect()->route('news')->with('success', 'Your subscription has been confirmed! Thank you for subscribing.');
    }
    
    /**
     * Unsubscribe with token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('token', $token)->first();
        
        if (!$subscriber) {
            return redirect()->route('news')->with('error', 'The unsubscribe link is invalid.');
        }
        
        $subscriber->is_active = false;
        $subscriber->save();
        
        return redirect()->route('news')->with('success', 'Your subscription has been unsubscribed successfully.');
    }
}