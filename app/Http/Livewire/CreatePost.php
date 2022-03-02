<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;
    public $open=false;
    public $title, $content, $image, $identificador;
    protected $rules = [
        'title' => 'required',
        'content' => 'required',
        'image' => 'required|image|max:2048'
    ];

    public function mount(){
        $this->identificador = rand();
    }
    /*public function updated($propertyName){
        $this->validateOnly($propertyName);
    }*/
    public function save(){
        $this->validate();

        $image = $this->image->store('public/posts');

        Post::create([
            'title'=>$this->title,
            'content'=>$this->content,
            'image' => $image
        ]);

        $this->reset(['open', 'title', 'content', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts','render');
        $this->emit('alert', 'El post se creo  satisfactoriamente');
    }

    public function render()
    {


        return view('livewire.create-post');
    }

    public function updatingOpen(){
        if(!$this->open){
             $this->reset(['content', 'title', 'image']);
             $this->identificador = rand();
             $this->emit(['resetCKEditor']);
        }

    }
}