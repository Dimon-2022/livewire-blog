<?php

namespace App\Livewire\Blog;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Subscribe extends Component
{
    #[Validate('required|email|unique:subscribers,email')]
    public $email = '';

    public function subscribe(){
        $this->validate();

        $subscriber = new \App\Models\Subscriber([
            'email' => $this->email,
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $subscriber->save();

        session()->flash('subscribe-success', 'Thank you for subscribing!');

        $this->email = '';

    }
    public function render()
    {
        return view('livewire.blog.subscribe');
    }
}
