<?php

namespace App\Http\Livewire;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;
    public $post;
    public $open;
    public $image, $identificador;
    public $open_edit = false;

    public function mount(Post $post){
        $this->post = $post;
        $this->identificador = rand();
    }



    protected $rules = [
        'post.title' => 'required',
        'post.content' => 'required'
    ];

    public function save(){

        $this->validate();

        if($this->image){
            Storage::delete([$this->post->image]);
            $this->post->image = $this->image->store('public/posts');
        }

        $this->post->save();
        $this->reset(['open', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts','render');
        $this->emit('alert', 'El post se actualizo satisfactoriamente');
    }
    public function render()
    {

        return view('livewire.edit-post');
    }
}
